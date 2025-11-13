<?php
// import

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// ===== THÊM DÒNG NÀY =====
if (!defined('JWT_SECRET_KEY')) {
    define('JWT_SECRET_KEY', 'your-super-secret-key-change-this-2024');
}

/**
 * User API Endpoints
 * Xử lý các endpoint liên quan đến user profile
 */

// Đăng ký REST API endpoints
add_action('rest_api_init', function() {
    // Endpoint lấy thông tin user hiện tại
    register_rest_route('my-api/v1', '/user/profile', array(
        'methods' => 'GET',
        'callback' => 'get_current_user_profile',
        'permission_callback' => 'check_jwt_authentication'
    ));
    
    // Endpoint cập nhật thông tin user
    register_rest_route('my-api/v1', '/user/profile', array(
        'methods' => 'POST',
        'callback' => 'update_current_user_profile',
        'permission_callback' => 'check_jwt_authentication'
    ));
});

// Function check JWT authentication
function check_jwt_authentication() {
    // Thử nhiều cách lấy header
    $auth_header = '';
    
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $auth_header = $_SERVER['HTTP_AUTHORIZATION'];
    } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        $auth_header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
    } elseif (function_exists('apache_request_headers')) {
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $auth_header = $headers['Authorization'];
        } elseif (isset($headers['authorization'])) {
            $auth_header = $headers['authorization'];
        }
    }
    
    if (empty($auth_header)) {
        return new WP_Error('no_token', 'No authentication token provided', array('status' => 401));
    }
    
    $parts = explode(' ', $auth_header, 2);
    if (count($parts) !== 2) {
        return new WP_Error('invalid_format', 'Invalid authorization header format', array('status' => 401));
    }
    
    list($token_type, $token) = $parts;
    
    if ($token_type !== 'Bearer') {
        return new WP_Error('invalid_token_type', 'Invalid token type', array('status' => 401));
    }
    
    try {
        $decoded = verify_jwt_token($token);
        
        global $current_user_id;
        $current_user_id = $decoded->user_id;
        
        return true;
    } catch (Exception $e) {
        return new WP_Error('invalid_token', 'Invalid or expired token: ' . $e->getMessage(), array('status' => 401));
    }
}

// Function lấy thông tin user
function get_current_user_profile() {
    global $current_user_id;
    
    $user = get_userdata($current_user_id);
    
    if (!$user) {
        return new WP_Error('user_not_found', 'User not found', array('status' => 404));
    }
    
    return array(
        'id' => $user->ID,
        'email' => $user->user_email,
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
        'display_name' => $user->display_name,
        'avatar_url' => get_avatar_url($user->ID),
        'registered_date' => $user->user_registered,
        'phone' => get_user_meta($user->ID, 'phone', true) ?: '',
    );
}

// Function cập nhật thông tin user
function update_current_user_profile($request) {
    global $current_user_id;
    
    $params = $request->get_json_params();
    
    // Update first name
    if (isset($params['first_name'])) {
        update_user_meta($current_user_id, 'first_name', sanitize_text_field($params['first_name']));
    }
    
    // Update last name
    if (isset($params['last_name'])) {
        update_user_meta($current_user_id, 'last_name', sanitize_text_field($params['last_name']));
    }
    
    // Update display name nếu có thay đổi first_name hoặc last_name
    if (isset($params['first_name']) || isset($params['last_name'])) {
        $first_name = isset($params['first_name']) ? $params['first_name'] : get_user_meta($current_user_id, 'first_name', true);
        $last_name = isset($params['last_name']) ? $params['last_name'] : get_user_meta($current_user_id, 'last_name', true);
        
        wp_update_user(array(
            'ID' => $current_user_id,
            'display_name' => $first_name . ' ' . $last_name
        ));
    }

    // Update phone
    if (isset($params['phone'])) {
        $phone = sanitize_text_field($params['phone']);
        if (!empty($phone) && !preg_match('/^[0-9]{10,11}$/', $phone)) {
            return new WP_Error('invalid_phone', 'Số điện thoại không hợp lệ', array('status' => 400));
        }
        update_user_meta($current_user_id, 'phone', $phone);
    }
    
    return array(
        'success' => true,
        'message' => 'Cập nhật thông tin thành công'
    );
}

// ===== JWT FUNCTIONS =====

function generate_jwt_token($user_id) {
    $issuedAt = time();
    $expire = $issuedAt + (7 * 24 * 60 * 60);

    $payload = [
        'iss' => get_bloginfo('url'),
        'iat' => $issuedAt,
        'exp' => $expire,
        'user_id' => $user_id,
    ];

    $token = JWT::encode($payload, JWT_SECRET_KEY, 'HS256');
    return $token;
}

function verify_jwt_token($token) {
    try {
        $decoded = JWT::decode($token, new Key(JWT_SECRET_KEY, 'HS256'));
        
        if ($decoded->exp < time()) {
            throw new Exception('Token has expired');
        }
        
        return $decoded;
        
    } catch (Exception $e) {
        error_log('JWT Verification Error: ' . $e->getMessage());
        throw new Exception('Token verification failed: ' . $e->getMessage());
    }
}

function refresh_jwt_token($old_token) {
    try {
        $decoded = verify_jwt_token($old_token);
        return generate_jwt_token($decoded->user_id);
    } catch (Exception $e) {
        throw new Exception('Cannot refresh token: ' . $e->getMessage());
    }
}
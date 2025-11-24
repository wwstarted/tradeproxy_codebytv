<?php
// /inc/api-user.php - SỬA LẠI

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Đảm bảo hằng số JWT
if (!defined('JWT_SECRET_KEY')) {
    define('JWT_SECRET_KEY', 'your-super-secret-key-change-this-2024');
}

/**
 * User API Endpoints
 */
add_action('rest_api_init', function () {
    // ===== PROFILE ENDPOINTS =====
    // GET
    register_rest_route('my-api/v1', '/user/profile', [
        'methods' => 'GET',
        'callback' => 'get_current_user_profile',
        'permission_callback' => 'check_jwt_authentication'
    ]);

    // POST
    register_rest_route('my-api/v1', '/user/profile', [
        'methods' => 'POST',
        'callback' => 'update_current_user_profile',
        'permission_callback' => 'check_jwt_authentication'
    ]);

    // ===== CHANGE PASSWORD ENDPOINT =====
    // POST (update password)
    register_rest_route('my-api/v1', '/user/change-password', [
        'methods' => 'POST',
        'callback' => 'change_user_password',
        'permission_callback' => 'check_jwt_authentication',
        'args' => [
            'current_password' => [
                'required' => true,
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ],
            'new_password' => [
                'required' => true,
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ],
        ]
    ]);

    // ===== WALLET ENDPOINT =====
    register_rest_route('my-api/v1', '/wallet/balance', [
        'methods' => 'GET',
        'callback' => 'get_wallet_balance',
        'permission_callback' => 'check_jwt_authentication'
    ]);
});

// ===== JWT AUTHENTICATION =====
function check_jwt_authentication()
{
    $auth_header = '';
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $auth_header = $_SERVER['HTTP_AUTHORIZATION'];
    } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        $auth_header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
    } elseif (function_exists('apache_request_headers')) {
        $headers = apache_request_headers();
        $auth_header = $headers['Authorization'] ?? $headers['authorization'] ?? '';
    }

    if (empty($auth_header)) {
        return new WP_Error('no_token', 'No token', ['status' => 401]);
    }

    $parts = explode(' ', trim($auth_header), 2);
    if (count($parts) !== 2 || $parts[0] !== 'Bearer') {
        return new WP_Error('invalid_token', 'Invalid token', ['status' => 401]);
    }

    try {
        $decoded = verify_jwt_token($parts[1]);
        global $current_user_id;
        $current_user_id = $decoded->user_id;
        return true;
    } catch (Exception $e) {
        return new WP_Error('invalid_token', 'Token error: ' . $e->getMessage(), ['status' => 401]);
    }
}

// ===== GET USER PROFILE =====
function get_current_user_profile()
{
    global $current_user_id;
    $user = get_userdata($current_user_id);

    if (!$user) {
        return new WP_Error('not_found', 'User not found', ['status' => 404]);
    }

    return [
        'display_name' => $user->display_name,
        'email' => $user->user_email,
        'phone' => get_user_meta($current_user_id, 'phone', true) ?: '',
    ];
}

// ===== UPDATE USER PROFILE =====
function update_current_user_profile($request)
{
    global $current_user_id;
    $params = $request->get_json_params();

    // DISPLAY NAME
    if (isset($params['display_name'])) {
        wp_update_user([
            'ID' => $current_user_id,
            'display_name' => sanitize_text_field($params['display_name'])
        ]);
    }

    // EMAIL
    if (isset($params['email'])) {
        $new_email = sanitize_email($params['email']);
        $current_user = get_userdata($current_user_id);
        $current_email = $current_user->user_email;

        if ($new_email !== $current_email) {
            if (!is_email($new_email)) {
                return new WP_Error('invalid_email', 'Email không hợp lệ', ['status' => 400]);
            }
            if (email_exists($new_email)) {
                return new WP_Error('email_exists', 'Email đã được sử dụng', ['status' => 400]);
            }
            
            wp_update_user([
                'ID' => $current_user_id,
                'user_email' => $new_email
            ]);
        }
    }

    // PHONE
    if (isset($params['phone'])) {
        $phone = sanitize_text_field($params['phone']);

        if (!empty($phone) && !preg_match('/^(\+\d{1,3})?\d{7,15}$/', $phone)) {
            return new WP_Error('invalid_phone', 'Số điện thoại không hợp lệ', ['status' => 400]);
        }
        update_user_meta($current_user_id, 'phone', $phone);
    }

    // Return
    $user = get_userdata($current_user_id);
    return [
        'success' => true,
        'message' => 'Cập nhật thành công',
        'data' => [
            'display_name' => $user->display_name,
            'email' => $user->user_email,
            'phone' => get_user_meta($current_user_id, 'phone', true)
        ]
    ];
}

// ===== CHANGE PASSWORD =====
function change_user_password($request)
{
    global $current_user_id;
    
    if (!$current_user_id) {
        return new WP_Error(
            'unauthorized',
            'Phiên đăng nhập hết hạn',
            ['status' => 401]
        );
    }

    $user = get_userdata($current_user_id);
    if (!$user) {
        return new WP_Error(
            'user_not_found',
            'Không tìm thấy người dùng',
            ['status' => 404]
        );
    }

    // Lấy dữ liệu từ request
    $params = $request->get_json_params();
    $current_password = $params['current_password'] ?? '';
    $new_password = $params['new_password'] ?? '';

    // ===== VALIDATION =====
    
    // 1. Kiểm tra mật khẩu cũ
    if (!wp_check_password($current_password, $user->user_pass, $current_user_id)) {
        return new WP_Error(
            'invalid_password',
            'Mật khẩu cũ không đúng',
            ['status' => 401]
        );
    }

    // 2. Kiểm tra mật khẩu mới không được trùng mật khẩu cũ
    if ($current_password === $new_password) {
        return new WP_Error(
            'same_password',
            'Mật khẩu mới không được trùng với mật khẩu cũ',
            ['status' => 400]
        );
    }

    // 3. Kiểm tra độ dài mật khẩu mới
    if (strlen($new_password) < 8) {
        return new WP_Error(
            'weak_password',
            'Mật khẩu mới phải có ít nhất 8 ký tự',
            ['status' => 400]
        );
    }

    // 4. Kiểm tra độ mạnh mật khẩu
    if (!preg_match('/[a-z]/', $new_password) || 
        !preg_match('/[A-Z]/', $new_password) || 
        !preg_match('/[0-9]/', $new_password)) {
        return new WP_Error(
            'weak_password',
            'Mật khẩu phải chứa chữ hoa, chữ thường và số',
            ['status' => 400]
        );
    }

    // ===== CẬP NHẬT MẬT KHẨU =====
    wp_set_password($new_password, $current_user_id);

    // ===== LOG ACTIVITY =====
    error_log(sprintf(
        '[CHANGE_PASSWORD] User ID: %d, Email: %s, Time: %s',
        $current_user_id,
        $user->user_email,
        current_time('mysql')
    ));

    // ===== TRẢ VỀ RESPONSE =====
    return new WP_REST_Response([
        'success' => true,
        'message' => 'Đổi mật khẩu thành công',
        'data' => [
            'user_id' => $current_user_id,
            'email' => $user->user_email,
            'changed_at' => current_time('mysql')
        ]
    ], 200);
}

// ===== JWT HELPERS =====
function generate_jwt_token($user_id)
{
    $payload = [
        'iss' => get_bloginfo('url'),
        'iat' => time(),
        'exp' => time() + (7 * 24 * 60 * 60),
        'user_id' => $user_id,
    ];
    return JWT::encode($payload, JWT_SECRET_KEY, 'HS256');
}

function verify_jwt_token($token)
{
    return JWT::decode($token, new Key(JWT_SECRET_KEY, 'HS256'));
}

// ===== WALLET FUNCTIONS =====
function get_wallet_balance() {
    global $current_user_id;
    $balance = (float) get_user_meta($current_user_id, 'wallet_balance', true);
    return [
        'balance' => $balance,
        'formatted' => number_format($balance, 0, ',', '.') . ' VND'
    ];
}

function my_wallet_adjust($user_id, $amount) {
    $current = (float) get_user_meta($user_id, 'wallet_balance', true);
    $new = $current + $amount;
    update_user_meta($user_id, 'wallet_balance', $new > 0 ? $new : 0);
    return $new;
}

// ===== WALLET INIT =====
add_action('init', function() {
    register_post_type('wallet_transaction', [
        'labels' => ['name' => 'Giao dịch ví'],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => 'users.php',
        'show_in_rest' => true,
        'supports' => ['title', 'custom-fields'],
    ]);
});

add_action('user_register', function($user_id) {
    update_user_meta($user_id, 'wallet_balance', 0);
});

add_action('wp_login', function($user_login, $user) {
    if (!metadata_exists('user', $user->ID, 'wallet_balance')) {
        update_user_meta($user->ID, 'wallet_balance', 0);
    }
}, 10, 2);

//   endpoint logout (clear cookie)

/**
 * Thêm code này vào functions.php hoặc file plugin của bạn
 * API Endpoint để xử lý logout và xóa cookie từ server
 */

// Đăng ký REST API endpoint cho logout
add_action('rest_api_init', function () {
    register_rest_route('my-api/v1', '/logout', array(
        'methods' => 'POST',
        'callback' => 'handle_api_logout',
        'permission_callback' => '__return_true' // Cho phép tất cả user gọi
    ));
});

/**
 * Xử lý logout - Xóa tất cả cookie và session
 */
function handle_api_logout(WP_REST_Request $request) {
    // Set timezone Việt Nam
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    
    // Xóa cookie jwt_token (httpOnly)
    setcookie(
        "jwt_token",
        "",
        time() - 3600, // Thời gian quá khứ để expire
        "/",
        "",
        false,
        true
    );
    
    // Xóa cookie pending_jwt_token
    setcookie(
        "pending_jwt_token",
        "",
        time() - 3600,
        "/",
        "",
        false,
        false
    );
    
    // Logout khỏi WordPress
    wp_logout();
    
    // Xóa tất cả WordPress auth cookies
    wp_clear_auth_cookie();
    
    return new WP_REST_Response(array(
        'success' => true,
        'message' => 'Đăng xuất thành công'
    ), 200);
}

// =============================================== endpoint logout =========================================================

// Thêm vào file functions.php hoặc một file plugin/class quản lý API của bạn

function register_logout_endpoint() {
    register_rest_route('my-api/v1', '/logout', array(
        'methods' => 'POST',
        'callback' => 'handle_logout_cookie_clear',
        'permission_callback' => '__return_true', // Có thể để true vì nó chỉ xóa cookie của người dùng
    ));
}
add_action('rest_api_init', 'register_logout_endpoint');

function handle_logout_cookie_clear($request) {
    // Xóa cookie JWT bằng cách đặt thời gian hết hạn trong quá khứ
    // Đảm bảo các tham số (tên, path, domain) khớp với cách bạn đã set ban đầu
    setcookie('jwt_token', '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN);
    setcookie('jwt_token', '', time() - 3600, '/', $_SERVER['HTTP_HOST'], is_ssl(), true); // Xóa trên mọi path và với HttpOnly

    // Thiết lập header để thông báo đăng xuất thành công
    return new WP_REST_Response(array('success' => true, 'message' => 'Logged out successfully, cookie cleared.'), 200);
}
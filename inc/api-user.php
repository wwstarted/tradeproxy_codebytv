<?php
// /inc/api-user.php

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
});

// === KIỂM TRA JWT ===
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

// === get user data ===
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

// === update user data ===
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
        $current_user = wp_get_current_user();
        $current_email = $current_user->user_email;

        if ($new_email !== $current_email) {
            if (!is_email($new_email)) {
                return new WP_Error('invalid_email', 'Email không hợp lệ', ['status' => 400]);
            }
            if (email_exists($new_email)) {
                return new WP_Error('email_exists', 'Email đã được sử dụng', ['status' => 400]);
            }
        }

        wp_update_user([
            'ID' => $current_user_id,
            'user_email' => $new_email
        ]);
    }

    // PHONE
    if (isset($params['phone'])) {
        $phone = sanitize_text_field($params['phone']);

        if (!empty($phone) && !preg_match('/^(\+\d{1,3})?\d{7,15}$/', $phone)) {
            return new WP_Error('invalid_phone', 'Số điện thoại không hợp lệ', ['status' => 400]);
        }
        update_user_meta($current_user_id, 'phone', $phone);
    }

    // return new data
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

// === JWT HELPER ===
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

// ================ wallet ===================
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

// /inc/wallet-init.php
add_action('user_register', function($user_id) {
    update_user_meta($user_id, 'wallet_balance', 0);
});

// Cho user cũ (khi login lần đầu)
add_action('wp_login', function($user_login, $user) {
    if (!metadata_exists('user', $user->ID, 'wallet_balance')) {
        update_user_meta($user->ID, 'wallet_balance', 0);
    }
}, 10, 2);

function my_wallet_adjust($user_id, $amount) {
    $current = (float) get_user_meta($user_id, 'wallet_balance', true);
    $new = $current + $amount;
    update_user_meta($user_id, 'wallet_balance', $new > 0 ? $new : 0);
    return $new;
}

// /inc/api-wallet.php
add_action('rest_api_init', function() {
    register_rest_route('my-api/v1', '/wallet/balance', [
        'methods' => 'GET',
        'callback' => 'get_wallet_balance',
        'permission_callback' => 'check_jwt_authentication' // bạn đã có
    ]);
});

function get_wallet_balance() {
    global $current_user_id;
    $balance = (float) get_user_meta($current_user_id, 'wallet_balance', true);
    return [
        'balance' => $balance,
        'formatted' => number_format($balance, 0, ',', '.') . ' VND'
    ];
}


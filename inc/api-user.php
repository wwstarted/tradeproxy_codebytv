<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// JWT
if (!defined('JWT_SECRET_KEY')) {
    define('JWT_SECRET_KEY', 'your-super-secret-key-change-this-2024');
}

// 
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
    // POST
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

    // request
    $params = $request->get_json_params();
    $current_password = $params['current_password'] ?? '';
    $new_password = $params['new_password'] ?? '';

    // ===== VALIDATION =====

    // check current password
    if (!wp_check_password($current_password, $user->user_pass, $current_user_id)) {
        return new WP_Error(
            'invalid_password',
            'Mật khẩu cũ không đúng',
            ['status' => 401]
        );
    }

    // check new password 
    if ($current_password === $new_password) {
        return new WP_Error(
            'same_password',
            'Mật khẩu mới không được trùng với mật khẩu cũ',
            ['status' => 400]
        );
    }

    // length pass
    if (strlen($new_password) < 8) {
        return new WP_Error(
            'weak_password',
            'Mật khẩu mới phải có ít nhất 8 ký tự',
            ['status' => 400]
        );
    }

    // strong pass
    if (
        !preg_match('/[a-z]/', $new_password) ||
        !preg_match('/[A-Z]/', $new_password) ||
        !preg_match('/[0-9]/', $new_password)
    ) {
        return new WP_Error(
            'weak_password',
            'Mật khẩu phải chứa chữ hoa, chữ thường và số',
            ['status' => 400]
        );
    }

    // ===== update password =====
    wp_set_password($new_password, $current_user_id);

    // ===== log activity =====
    error_log(sprintf(
        '[CHANGE_PASSWORD] User ID: %d, Email: %s, Time: %s',
        $current_user_id,
        $user->user_email,
        current_time('mysql')
    ));

    // ===== return RESPONSE =====
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
function get_wallet_balance()
{
    global $current_user_id;
    $balance = (float) get_user_meta($current_user_id, 'wallet_balance', true);
    return [
        'balance' => $balance,
        'formatted' => number_format($balance, 0, ',', '.') . ' VND'
    ];
}

function my_wallet_adjust($user_id, $amount)
{
    $current = (float) get_user_meta($user_id, 'wallet_balance', true);
    $new = $current + $amount;
    update_user_meta($user_id, 'wallet_balance', $new > 0 ? $new : 0);
    return $new;
}

// ===== WALLET INIT =====
add_action('init', function () {
    register_post_type('wallet_transaction', [
        'labels' => ['name' => 'Giao dịch ví'],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => 'users.php',
        'show_in_rest' => true,
        'supports' => ['title', 'custom-fields'],
    ]);
});

add_action('user_register', function ($user_id) {
    update_user_meta($user_id, 'wallet_balance', 0);
});

add_action('wp_login', function ($user_login, $user) {
    if (!metadata_exists('user', $user->ID, 'wallet_balance')) {
        update_user_meta($user->ID, 'wallet_balance', 0);
    }
}, 10, 2);


// ======================== register REST API endpoint cho logout =================================
add_action('rest_api_init', function () {
    register_rest_route('my-api/v1', '/logout', array(
        'methods' => 'POST',
        'callback' => 'handle_api_logout',
        'permission_callback' => '__return_true'
    ));
});

function handle_api_logout(WP_REST_Request $request)
{
    // timezone
    date_default_timezone_set('Asia/Ho_Chi_Minh');

    // clear cookie jwt_token (httpOnly)
    setcookie(
        "jwt_token",
        "",
        time() - 3600,
        "/",
        "",
        false,
        true
    );

    // clear cookie pending_jwt_token
    setcookie(
        "pending_jwt_token",
        "",
        time() - 3600,
        "/",
        "",
        false,
        false
    );

    // Logout 
    wp_logout();

    //clear WordPress auth cookies
    wp_clear_auth_cookie();

    return new WP_REST_Response(array(
        'success' => true,
        'message' => 'Đăng xuất thành công'
    ), 200);
}

// =============================== endpoint logout ============================

function register_logout_endpoint()
{
    register_rest_route('my-api/v1', '/logout', array(
        'methods' => 'POST',
        'callback' => 'handle_logout_cookie_clear',
        'permission_callback' => '__return_true',
    ));
}
add_action('rest_api_init', 'register_logout_endpoint');

function handle_logout_cookie_clear($request)
{
    setcookie('jwt_token', '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN);
    setcookie('jwt_token', '', time() - 3600, '/', $_SERVER['HTTP_HOST'], is_ssl(), true); // Xóa trên mọi path và với HttpOnly

    return new WP_REST_Response(array('success' => true, 'message' => 'Logged out successfully, cookie cleared.'), 200);
}
// ================================ endpoint & functions sent, check verify otp =======================================
// Generate OTP
function generate_otp($length = 6)
{
    return str_pad(rand(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
}

// Send OTP Email
add_action('rest_api_init', function () {
    // send
    register_rest_route('my-api/v1', '/otp/send-email', array(
        'methods' => 'POST',
        'callback' => 'send_email_otp',
        'permission_callback' => 'check_jwt_authentication'
    ));

    // verify
    register_rest_route('my-api/v1', '/otp/verify-email', array(
        'methods' => 'POST',
        'callback' => 'verify_email_otp',
        'permission_callback' => 'check_jwt_authentication'
    ));
});
function send_email_otp($request)
{
    $user_id = get_current_user_id();
    $email = sanitize_email($request->get_param('email'));
    $purpose = sanitize_text_field($request->get_param('purpose')) ?: 'verify_email';

    error_log("========== SEND OTP START ==========");
    error_log("User ID: " . $user_id);
    error_log("Email: " . $email);

    if (!is_email($email)) {
        return new WP_Error('invalid_email', 'Email không hợp lệ', array('status' => 400));
    }

    // Generate OTP
    $otp = generate_otp(6);
    $current_time = time();
    $expires_at = $current_time + 300; // 5 phút

    error_log("Current time: " . $current_time . " (" . date('Y-m-d H:i:s', $current_time) . ")");
    error_log("Expires at: " . $expires_at . " (" . date('Y-m-d H:i:s', $expires_at) . ")");
    error_log("Generated OTP: " . $otp);

    // Lưu vào Transients (tự động hết hạn sau 5 phút)
    $transient_key = 'otp_email_' . $user_id;
    $otp_data = array(
        'otp' => $otp,
        'email' => $email,
        'expires_at' => $expires_at,
        'attempts' => 0,
        'created_at' => $current_time
    );

    $saved = set_transient($transient_key, $otp_data, 300); // 300 giây = 5 phút

    error_log("Save to transient: " . ($saved ? 'SUCCESS' : 'FAILED'));
    error_log("Transient key: " . $transient_key);

    // Verify ngay sau khi lưu
    $verify_data = get_transient($transient_key);
    error_log("Verify read data: " . print_r($verify_data, true));

    if (!$verify_data) {
        error_log("ERROR: Cannot save transient!");
        return new WP_Error('save_failed', 'Không thể lưu OTP. Vui lòng thử lại.', array('status' => 500));
    }

    // Gửi email
    $subject = '[TradeProxy] Mã xác thực OTP';
    $message = "
        <h2>Xác thực Email</h2>
        <p>Mã OTP của bạn là: <strong style='font-size: 24px; color: #4CAF50;'>$otp</strong></p>
        <p>Mã này có hiệu lực trong <strong>5 phút</strong>.</p>
        <p>Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email.</p>
    ";

    $headers = array('Content-Type: text/html; charset=UTF-8');
    $sent = wp_mail($email, $subject, $message, $headers);

    error_log("Email sent: " . ($sent ? 'SUCCESS' : 'FAILED'));
    error_log("========== SEND OTP END ==========");

    if (!$sent) {
        return new WP_Error('email_failed', 'Không thể gửi email', array('status' => 500));
    }

    return array(
        'success' => true,
        'message' => 'Mã OTP đã được gửi đến ' . $email,
        'expires_in' => 300,
        'debug_otp' => $otp,
        'debug_expires' => $expires_at,
        'debug_current' => $current_time
    );
}

function verify_email_otp($request)
{
    $user_id = get_current_user_id();
    $otp_input = sanitize_text_field($request->get_param('otp'));
    $email = sanitize_email($request->get_param('email'));

    error_log("========== VERIFY OTP START ==========");
    error_log("User ID: " . $user_id);
    error_log("Input OTP: " . $otp_input);
    error_log("Input Email: " . $email);

    // Lấy từ Transients
    $transient_key = 'otp_email_' . $user_id;
    $otp_data = get_transient($transient_key);

    error_log("Transient key: " . $transient_key);
    error_log("Retrieved data: " . print_r($otp_data, true));

    // Kiểm tra có data không
    if (!$otp_data || !is_array($otp_data)) {
        error_log("ERROR: No OTP data found!");
        return new WP_Error('no_otp', 'Không tìm thấy OTP. Vui lòng gửi lại mã mới.', array('status' => 400));
    }

    $stored_otp = $otp_data['otp'];
    $stored_email = $otp_data['email'];
    $expires_at = (int) $otp_data['expires_at'];
    $attempts = (int) $otp_data['attempts'];

    $current_time = time();
    $time_remaining = $expires_at - $current_time;

    error_log("Stored OTP: " . $stored_otp);
    error_log("Stored Email: " . $stored_email);
    error_log("Expires at: " . $expires_at . " (" . date('Y-m-d H:i:s', $expires_at) . ")");
    error_log("Current time: " . $current_time . " (" . date('Y-m-d H:i:s', $current_time) . ")");
    error_log("Time remaining: " . $time_remaining . " seconds");
    error_log("Attempts: " . $attempts);

    // Kiểm tra số lần thử
    if ($attempts >= 3) {
        delete_transient($transient_key);
        return new WP_Error('too_many_attempts', 'Bạn đã nhập sai quá 3 lần. Vui lòng yêu cầu mã mới.', array('status' => 429));
    }

    // Kiểm tra hết hạn
    if ($current_time > $expires_at) {
        error_log("OTP EXPIRED!");
        delete_transient($transient_key);
        return new WP_Error(
            'otp_expired',
            'Mã OTP đã hết hạn. Còn lại: ' . $time_remaining . ' giây',
            array('status' => 400)
        );
    }

    // Kiểm tra email
    if ($email !== $stored_email) {
        error_log("Email mismatch: '$email' !== '$stored_email'");
        return new WP_Error('email_mismatch', 'Email không khớp', array('status' => 400));
    }

    // Kiểm tra OTP
    if ($otp_input !== $stored_otp) {
        error_log("OTP mismatch: '$otp_input' !== '$stored_otp'");
        // Tăng số lần thử
        $otp_data['attempts'] = $attempts + 1;
        set_transient($transient_key, $otp_data, $time_remaining);
        return new WP_Error('invalid_otp', 'Mã OTP không đúng. Còn ' . (3 - $attempts - 1) . ' lần thử.', array('status' => 400));
    }

    // Xác thực thành công
    error_log("OTP VERIFY SUCCESS!");
    delete_transient($transient_key);

    error_log("========== VERIFY OTP END ==========");

    return array(
        'success' => true,
        'message' => 'Xác thực thành công',
        'verified_email' => $email
    );
}

//  ============================= verify otp chang password ==============================
// endpoint sent otp
add_action('rest_api_init', function () {
    register_rest_route('my-api/v1', '/otp/send-change-password', array(
        'methods' => 'POST',
        'callback' => 'send_change_password_otp',
        'permission_callback' => 'check_jwt_authentication'
    ));
});

function send_change_password_otp($request)
{
    // $user_id = get_current_user_id();
    // $user = wp_get_current_user();
    global $current_user_id;
    $user = get_userdata($current_user_id);
    $email = $user->user_email;

    if (!$email) {
        return new WP_Error('no_email', 'Không tìm thấy email', array('status' => 400));
    }

    // Generate OTP
    $otp = generate_otp(6);
    $current_time = time();
    $expires_at = $current_time + 300;

    error_log("========== SEND CHANGE PASSWORD OTP ==========");
    error_log("User ID: " . $current_user_id);
    error_log("Email: " . $email);
    error_log("OTP: " . $otp);

    // save Transients
    $transient_key = 'otp_change_password_' . $current_user_id;
    $otp_data = array(
        'otp' => $otp,
        'email' => $email,
        'expires_at' => $expires_at,
        'attempts' => 0,
        'created_at' => $current_time
    );

    $saved = set_transient($transient_key, $otp_data, 300);

    if (!$saved) {
        return new WP_Error('save_failed', 'Không thể lưu OTP', array('status' => 500));
    }

    // send email
    $subject = '[TradeProxy] Mã xác thực đổi mật khẩu';
    $message = "
        <h2>Xác thực đổi mật khẩu</h2>
        <p>Bạn đang yêu cầu đổi mật khẩu tài khoản.</p>
        <p>Mã OTP của bạn là: <strong style='font-size: 24px; color: #4CAF50;'>$otp</strong></p>
        <p>Mã này có hiệu lực trong <strong>5 phút</strong>.</p>
        <p><strong>Nếu bạn không thực hiện yêu cầu này, vui lòng BỎ QUA email và thay đổi mật khẩu ngay.</strong></p>
    ";

    $headers = array('Content-Type: text/html; charset=UTF-8');
    $sent = wp_mail($email, $subject, $message, $headers);

    error_log("Email sent: " . ($sent ? 'SUCCESS' : 'FAILED'));

    if (!$sent) {
        return new WP_Error('email_failed', 'Không thể gửi email', array('status' => 500));
    }

    return array(
        'success' => true,
        'message' => 'Mã OTP đã được gửi đến ' . $email,
        'expires_in' => 300,
        'debug_otp' => $otp
    );
}

// Endpoint verify OTP cho change password
add_action('rest_api_init', function () {
    register_rest_route('my-api/v1', '/otp/verify-change-password', array(
        'methods' => 'POST',
        'callback' => 'verify_change_password_otp',
        'permission_callback' => 'check_jwt_authentication'
    ));
});

function verify_change_password_otp($request)
{
    global $current_user_id;
    // $user_id = get_current_user_id();
    $otp_input = sanitize_text_field($request->get_param('otp'));

    error_log("========== VERIFY CHANGE PASSWORD OTP ==========");
    error_log("User ID: " . $current_user_id);
    error_log("Input OTP: " . $otp_input);

    //Transients
    $transient_key = 'otp_change_password_' . $current_user_id;
    $otp_data = get_transient($transient_key);

    if (!$otp_data || !is_array($otp_data)) {
        error_log("ERROR: No OTP data found!");
        return new WP_Error('no_otp', 'Không tìm thấy OTP. Vui lòng gửi lại mã mới.', array('status' => 400));
    }

    $stored_otp = $otp_data['otp'];
    $expires_at = (int) $otp_data['expires_at'];
    $attempts = (int) $otp_data['attempts'];

    $current_time = time();
    $time_remaining = $expires_at - $current_time;

    error_log("Stored OTP: " . $stored_otp);
    error_log("Time remaining: " . $time_remaining . " seconds");

    // count check
    if ($attempts >= 3) {
        delete_transient($transient_key);
        return new WP_Error('too_many_attempts', 'Bạn đã nhập sai quá 3 lần. Vui lòng yêu cầu mã mới.', array('status' => 429));
    }

    // kiem tra het han
    if ($current_time > $expires_at) {
        delete_transient($transient_key);
        return new WP_Error('otp_expired', 'Mã OTP đã hết hạn', array('status' => 400));
    }

    // Kiểm tra OTP
    if ($otp_input !== $stored_otp) {
        $otp_data['attempts'] = $attempts + 1;
        set_transient($transient_key, $otp_data, $time_remaining);
        return new WP_Error('invalid_otp', 'Mã OTP không đúng. Còn ' . (3 - $attempts - 1) . ' lần thử.', array('status' => 400));
    }

    // verify success
    error_log("OTP VERIFY SUCCESS!");
    delete_transient($transient_key);

    return array(
        'success' => true,
        'message' => 'Xác thực thành công'
    );
}

// =========================== forget password ========================

// endpoint sent otp forget pass
add_action('rest_api_init', function () {
    register_rest_route('my-api/v1', '/otp/send-forgot-password', array(
        'methods' => 'POST',
        'callback' => 'send_forgot_password_otp',
        'permission_callback' => '__return_true'
    ));
});

function send_forgot_password_otp($request)
{
    $email = sanitize_email($request->get_param('email'));

    if (!$email || !is_email($email)) {
        return new WP_Error('invalid_email', 'Email không hợp lệ', array('status' => 400));
    }

    $user = get_user_by('email', $email);
    if (!$user) {
        return new WP_Error('email_not_found', 'Email không tồn tại trong hệ thống', array('status' => 404));
    }

    $user_id = $user->ID;

    // Generate OTP
    $otp = generate_otp(6);
    $current_time = time();
    $expires_at = $current_time + 300;

    error_log("========== SEND FORGOT PASSWORD OTP ==========");
    error_log("User ID: " . $user_id);
    error_log("Email: " . $email);
    error_log("OTP: " . $otp);

    $transient_key = 'otp_forgot_password_' . md5($email);
    $otp_data = array(
        'otp' => $otp,
        'email' => $email,
        'user_id' => $user_id,
        'expires_at' => $expires_at,
        'attempts' => 0,
        'created_at' => $current_time
    );

    $saved = set_transient($transient_key, $otp_data, 300);

    if (!$saved) {
        return new WP_Error('save_failed', 'Không thể lưu OTP', array('status' => 500));
    }

    // Gửi email
    $subject = '[TradeProxy] Mã xác thực khôi phục mật khẩu';
    $message = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <h2 style='color: #333;'>Khôi phục mật khẩu</h2>
            <p>Xin chào <strong>{$user->display_name}</strong>,</p>
            <p>Bạn đang yêu cầu khôi phục mật khẩu cho tài khoản: <strong>{$email}</strong></p>
            <div style='background: #f5f5f5; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                <p style='margin: 0; font-size: 14px; color: #666;'>Mã OTP của bạn là:</p>
                <p style='margin: 10px 0; font-size: 32px; font-weight: bold; color: #4CAF50; letter-spacing: 5px;'>{$otp}</p>
                <p style='margin: 0; font-size: 14px; color: #666;'>Mã này có hiệu lực trong <strong>5 phút</strong></p>
            </div>
            <p style='color: #d32f2f; font-weight: bold;'>⚠️ Nếu bạn không thực hiện yêu cầu này, vui lòng BỎ QUA email này và thay đổi mật khẩu ngay lập tức để bảo mật tài khoản.</p>
            <hr style='border: none; border-top: 1px solid #ddd; margin: 20px 0;'>
            <p style='font-size: 12px; color: #999;'>Email tự động từ TradeProxy - Vui lòng không trả lời email này.</p>
        </div>
    ";

    $headers = array('Content-Type: text/html; charset=UTF-8');
    $sent = wp_mail($email, $subject, $message, $headers);

    error_log("Email sent: " . ($sent ? 'SUCCESS' : 'FAILED'));

    if (!$sent) {
        return new WP_Error('email_failed', 'Không thể gửi email. Vui lòng thử lại sau.', array('status' => 500));
    }

    return array(
        'success' => true,
        'message' => 'Mã OTP đã được gửi đến email ' . $email,
        'expires_in' => 300,
        // 'debug_otp' => $otp 
    );
}

// Endpoint verify OTP reset password
add_action('rest_api_init', function () {
    register_rest_route('my-api/v1', '/otp/verify-forgot-password', array(
        'methods' => 'POST',
        'callback' => 'verify_and_reset_password',
        'permission_callback' => '__return_true'
    ));
});

function verify_and_reset_password($request)
{
    // get data request
    $email = sanitize_email($request->get_param('email'));
    $otp_input = sanitize_text_field($request->get_param('otp'));
    $new_password = $request->get_param('new_password');
    $confirm_password = $request->get_param('confirm_password');

    error_log("========== VERIFY FORGOT PASSWORD OTP ==========");
    error_log("Email: " . $email);
    error_log("Input OTP: " . $otp_input);

    // Validation
    if (!$email || !is_email($email)) {
        return new WP_Error('invalid_email', 'Email không hợp lệ', array('status' => 400));
    }

    if (!$otp_input || strlen($otp_input) !== 6) {
        return new WP_Error('invalid_otp_format', 'Mã OTP phải có 6 chữ số', array('status' => 400));
    }

    if (!$new_password || strlen($new_password) < 6) {
        return new WP_Error('weak_password', 'Mật khẩu phải có ít nhất 6 ký tự', array('status' => 400));
    }

    if ($new_password !== $confirm_password) {
        return new WP_Error('password_mismatch', 'Mật khẩu xác nhận không khớp', array('status' => 400));
    }

    // check user
    $user = get_user_by('email', $email);
    if (!$user) {
        return new WP_Error('user_not_found', 'Không tìm thấy tài khoản với email này', array('status' => 404));
    }

    // get otp
    $transient_key = 'otp_forgot_password_' . md5($email);
    $otp_data = get_transient($transient_key);

    if (!$otp_data || !is_array($otp_data)) {
        error_log("ERROR: No OTP data found for email: " . $email);
        return new WP_Error('no_otp', 'Không tìm thấy mã OTP. Vui lòng yêu cầu gửi lại mã mới.', array('status' => 400));
    }

    $stored_otp = $otp_data['otp'];
    $expires_at = (int) $otp_data['expires_at'];
    $attempts = (int) $otp_data['attempts'];
    $user_id = (int) $otp_data['user_id'];

    $current_time = time();
    $time_remaining = $expires_at - $current_time;

    error_log("Stored OTP: " . $stored_otp);
    error_log("User ID: " . $user_id);
    error_log("Time remaining: " . $time_remaining . " seconds");
    error_log("Attempts: " . $attempts);

    if ($attempts >= 3) {
        delete_transient($transient_key);
        return new WP_Error('too_many_attempts', 'Bạn đã nhập sai quá 3 lần. Vui lòng yêu cầu mã OTP mới.', array('status' => 429));
    }

    if ($current_time > $expires_at) {
        delete_transient($transient_key);
        return new WP_Error('otp_expired', 'Mã OTP đã hết hạn. Vui lòng yêu cầu mã mới.', array('status' => 400));
    }

    if ($otp_input !== $stored_otp) {
        $otp_data['attempts'] = $attempts + 1;
        set_transient($transient_key, $otp_data, $time_remaining);

        $remaining_attempts = 3 - $attempts - 1;
        return new WP_Error(
            'invalid_otp',
            'Mã OTP không đúng. Còn ' . $remaining_attempts . ' lần thử.',
            array('status' => 400)
        );
    }

    error_log("OTP VERIFY SUCCESS! Resetting password for user ID: " . $user_id);

    wp_set_password($new_password, $user_id);

    delete_transient($transient_key);

    $subject = '[TradeProxy] Mật khẩu đã được thay đổi';
    $message = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <h2 style='color: #4CAF50;'>✓ Mật khẩu đã được thay đổi thành công</h2>
            <p>Xin chào <strong>{$user->display_name}</strong>,</p>
            <p>Mật khẩu cho tài khoản <strong>{$email}</strong> đã được thay đổi thành công vào lúc <strong>" . current_time('d/m/Y H:i:s') . "</strong></p>
            <div style='background: #e8f5e9; padding: 15px; border-left: 4px solid #4CAF50; margin: 20px 0;'>
                <p style='margin: 0; color: #2e7d32;'>Bạn có thể đăng nhập ngay bây giờ với mật khẩu mới.</p>
            </div>
            <p style='color: #d32f2f; font-weight: bold;'>⚠️ Nếu bạn không thực hiện thay đổi này, vui lòng liên hệ với chúng tôi ngay lập tức.</p>
            <hr style='border: none; border-top: 1px solid #ddd; margin: 20px 0;'>
            <p style='font-size: 12px; color: #999;'>Email tự động từ TradeProxy - Vui lòng không trả lời email này.</p>
        </div>
    ";
    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail($email, $subject, $message, $headers);

    error_log("Password reset successful for user ID: " . $user_id);

    return array(
        'success' => true,
        'message' => 'Đặt lại mật khẩu thành công! Bạn có thể đăng nhập với mật khẩu mới.',
        'redirect' => home_url('/login')
    );
}

// Helper function - generate OTP(if not have)
if (!function_exists('generate_otp')) {
    function generate_otp($length = 6)
    {
        $otp = '';
        for ($i = 0; $i < $length; $i++) {
            $otp .= mt_rand(0, 9);
        }
        return $otp;
    }
}
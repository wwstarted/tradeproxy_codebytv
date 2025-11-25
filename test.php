<?php
function send_email_otp($request) {
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

function verify_email_otp($request) {
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
        return new WP_Error('otp_expired', 
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
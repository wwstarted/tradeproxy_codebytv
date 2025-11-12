<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/auth.css" />
    <!-- Font Awesome cho eye icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>

<div class="container">

    <!-- PHẦN HÌNH ẢNH (TRÁI) -->
    <div class="image-section">
        <img src="https://i.imgur.com/5vR8xL2.png" alt="Đăng ký Trade Proxy">
    </div>

    <!-- PHẦN FORM (PHẢI) -->
    <div class="form-section">
        <div class="form-box">
            <h1 class="title">Đăng ký</h1>
            <p class="welcome-text">Trở thành thành viên để tiến hành giao dịch dễ dàng hơn!</p>

            <!-- Form xử lý PHP (giữ nguyên logic bạn đã có) -->
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $firstName = sanitize_text_field($_POST['firstName']);
                $lastName = sanitize_text_field($_POST['lastName']);
                $email = sanitize_email($_POST['email']);
                $password = $_POST['password'];
                $confirmPassword = $_POST['confirmPassword'];
                $terms = isset($_POST['terms']) ? true : false;
                $recaptcha_response = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';

                $errors = [];

                if ($password !== $confirmPassword) {
                    $errors[] = 'Mật khẩu và xác nhận mật khẩu không khớp.';
                }
                if (!$terms) {
                    $errors[] = 'Bạn phải đồng ý với điều khoản dịch vụ.';
                }
                if (empty($recaptcha_response)) {
                    $errors[] = 'Vui lòng xác nhận CAPTCHA.';
                } else {
                    $secret_key = '6LfNxwksAAAAACA60xyxdsVnijI9E-QEOi0Qs9AJ';
                    $verify = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
                        'body' => [
                            'secret' => $secret_key,
                            'response' => $recaptcha_response,
                            'remoteip' => $_SERVER['REMOTE_ADDR']
                        ]
                    ]);
                    $body = wp_remote_retrieve_body($verify);
                    $result = json_decode($body, true);
                    if (!isset($result['success']) || $result['success'] != true) {
                        $errors[] = 'CAPTCHA không hợp lệ, vui lòng thử lại.';
                    }
                }
                if (email_exists($email)) {
                    $errors[] = 'Email đã được sử dụng.';
                }

                if (empty($errors)) {
                    $username = sanitize_user(strtolower($firstName . '.' . $lastName));
                    $userdata = [
                        'user_login' => $email,
                        'user_email' => $email,
                        'user_pass' => $password,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'role' => 'subscriber'
                    ];
                    $user_id = wp_insert_user($userdata);
                    if (is_wp_error($user_id)) {
                        echo '<div class="error-message">Đăng ký thất bại: ' . $user_id->get_error_message() . '</div>';
                    } else {
                        echo '<div class="success-message">Đăng ký thành công! <a href="' . wp_login_url() . '">Đăng nhập ngay</a></div>';
                    }
                } else {
                    foreach ($errors as $error) {
                        echo '<div class="error-message">' . $error . '</div>';
                    }
                }
            }
            ?>

            <form id="registerForm" method="post">
                <!-- Họ + Tên -->
                <div class="input-row">
                    <div class="input-group">
                        <label for="firstName">Họ *</label>
                        <input type="text" name="firstName" id="firstName" placeholder="Họ" required>
                    </div>
                    <div class="input-group">
                        <label for="lastName">Tên *</label>
                        <input type="text" name="lastName" id="lastName" placeholder="Tên" required>
                    </div>
                </div>

                <!-- Email -->
                <div class="input-group">
                    <label for="email">Email *</label>
                    <input type="email" name="email" id="email" placeholder="Email" required>
                </div>

                <!-- Mật khẩu -->
                <div class="input-group">
                    <label for="password">Mật khẩu *</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" placeholder="Mật khẩu" required>
                        <button type="button" class="toggle-password" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Xác nhận mật khẩu -->
                <div class="input-group">
                    <label for="confirmPassword">Xác nhận mật khẩu *</label>
                    <div class="password-wrapper">
                        <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Xác nhận mật khẩu" required>
                        <button type="button" class="toggle-password" id="toggleConfirm">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- reCAPTCHA -->
                <div class="g-recaptcha" data-sitekey="6LfNxwksAAAAALrxpsJlGqGllZ2P-UF9Wmk-5VSc"></div>

                <!-- Checkbox điều khoản -->
                <div class="checkbox-container">
                    <input type="checkbox" name="terms" id="terms" required>
                    <span class="checkmark"></span>
                    <label for="terms" class="checkbox-label">
                        Tôi đồng ý với <a href="#">Điều khoản dịch vụ</a> của Trade Proxy.
                    </label>
                </div>

                <!-- Nút đăng ký -->
                <button type="submit" class="btn btn-primary">Đăng ký</button>

                <!-- Link đăng nhập -->
                <p class="signup-link">Đã có tài khoản? <a href="/login">Đăng nhập ngay</a></p>
            </form>
        </div>
    </div>
</div>

<!-- Google reCAPTCHA -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<!-- JS toggle password -->
<script>
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', function () {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });
</script>

</body>
</html>
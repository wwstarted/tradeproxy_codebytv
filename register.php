<?php
/*
Template Name: Register Page
*/
?>
<?php get_header(); ?>

<div class="register-container">
    <!-- image -->
    <div class="image-section">
        <img src="<?php echo esc_url('https://tradeproxy.vn/images/background/bg_register.webp'); ?>"
            alt="Register illustration">
    </div>

    <!-- signup -->
    <div class="form-section">
        <div class="form-box">
            <h1 class="title">Đăng ký</h1>
            <p class="welcome-text">Trở thành thành viên để tiến hành giao dịch dễ dàng hơn!</p>

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

                // check password
                if ($password !== $confirmPassword) {
                    $errors[] = 'Mật khẩu và xác nhận mật khẩu không khớp.';
                }

                // check pri
                if (!$terms) {
                    $errors[] = 'Bạn phải đồng ý với điều khoản dịch vụ.';
                }

                // check reCAPTCHA
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

                // check email
                if (email_exists($email)) {
                    $errors[] = 'Email đã được sử dụng.';
                }

                // create user if not error
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
                        echo '<div class="register-message error" style="padding-bottom:15px;">Đăng ký thất bại: ' . $user_id->get_error_message() . '</div>';
                    } else {
                        echo '<div class="register-message success" style="padding-bottom:15px;">Đăng ký thành công! <a href="' . home_url('/login') . '">Đăng nhập ngay</a></div>';
                    }
                } else {
                    foreach ($errors as $error) {
                        echo '<div class="register-message error" style="padding-bottom:15px;">' . $error . '</div>';
                    }
                }
            }
            ?>

            <form id="registerForm" method="post">

                <div class="input-row">
                    <div class="input-group">
                        <label for="firstName">Họ*</label>
                        <input type="text" name="firstName" id="firstName" placeholder="Họ" required>
                    </div>
                    <div class="input-group">
                        <label for="lastName">Tên*</label>
                        <input type="text" name="lastName" id="lastName" placeholder="Tên" required>
                    </div>
                </div>
                <div class="input-group">
                    <label for="email">Email*</label>
                    <input type="email" name="email" id="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <label for="password">Mật khẩu*</label>
                    <input type="password" name="password" id="password" placeholder="Mật khẩu" required>
                </div>
                <div class="input-group">
                    <label for="confirmPassword">Xác nhận mật khẩu*</label>
                    <input type="password" name="confirmPassword" id="confirmPassword"
                        placeholder="Xác nhận mật khẩu" required>
                </div>

                <div class="g-recaptcha" data-sitekey="6LfNxwksAAAAALrxpsJlGqGllZ2P-UF9Wmk-5VSc"></div>

                <div class="terms-box">
                    <label class="checkbox-container">
                        <input type="checkbox" name="terms" id="terms" required>
                        <span class="checkmark"></span>
                        <span class="checkbox-label">
                            Tôi đồng ý với <a href="#">Điều khoản dịch vụ</a> của Trade Proxy.
                        </span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">Đăng ký</button>
            </form>
        </div>
    </div>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<?php get_footer(); ?>
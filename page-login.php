<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <!-- <link rel="stylesheet" href="/css/login.css"> -->
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/login.css" />
</head>

<body>
    <div class="container">
        <div class="image-section">
            <img src="<?php echo esc_url('https://tse4.mm.bing.net/th/id/OIP.NLuP6Q7V5dg3eSdpLxUt2QHaE7?pid=Api&P=0&h=220'); ?>"
                alt="Login illustration">
        </div>

        <div class="form-section">
            <div class="form-box">
                <h1 class="title">Đăng nhập</h1>
                <p class="welcome-text">Chào mừng bạn đã quay trở lại!</p>

                <?php
                // import
                use Firebase\JWT\JWT;
                use Firebase\JWT\Key;

                // google login
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['credential'])) {
                    $id_token = sanitize_text_field($_POST['credential']);

                    // Verify token với Google
                    $response = wp_remote_get("https://oauth2.googleapis.com/tokeninfo?id_token={$id_token}");
                    $body = wp_remote_retrieve_body($response);
                    $user_data = json_decode($body, true);

                    if (isset($user_data['email'])) {
                        $email = $user_data['email'];

                        // Kiểm tra user đã tồn tại chưa
                        $user = get_user_by('email', $email);
                        if (!$user) {
                            // Tạo user mới
                            $userdata = [
                                'user_login' => $email,
                                'user_email' => $email,
                                'first_name' => $user_data['given_name'] ?? '',
                                'last_name' => $user_data['family_name'] ?? '',
                                'role' => 'subscriber',
                                'user_pass' => wp_generate_password()
                            ];
                            $user_id = wp_insert_user($userdata);
                            $user = get_user_by('id', $user_id);
                        }

                        // Login user
                        wp_set_current_user($user->ID);
                        wp_set_auth_cookie($user->ID);
                        do_action('wp_login', $user->user_login, $user);
                        $token = generate_jwt_token($user->ID);

                        // Lưu token vào cookie (7 ngày)
                        setcookie(
                            "jwt_token",
                            $token,
                            time() + (7 * 24 * 60 * 60),
                            "/",
                            "",
                            false,
                            true
                        );
                        wp_redirect(home_url());
                        exit;
                    } else {
                        echo '<div class="login-message error" style="padding-bottom:15px;">Google login thất bại.</div>';
                    }
                }


                // login email + password
                
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_email'])) {
                    $email = sanitize_email($_POST['login_email']);
                    $password = $_POST['login_password'];
                    $errors = [];

                    if (empty($email) || empty($password)) {
                        $errors[] = 'Vui lòng nhập đầy đủ thông tin.';
                    } else {
                        $user_obj = get_user_by('email', $email);
                        if ($user_obj) {
                            $user = wp_authenticate($user_obj->user_login, $password);
                        } else {
                            $user = new WP_Error('invalid_user', 'Email không tồn tại.');
                        }

                        if (is_wp_error($user)) {
                            $errors[] = 'Email hoặc mật khẩu không đúng.';
                        } else {
                            // Login thành công → redirect Home
                            wp_set_current_user($user->ID);
                            wp_set_auth_cookie($user->ID);
                            do_action('wp_login', $user->user_login, $user);

                            $token = generate_jwt_token($user->ID);

                            // Lưu token vào cookie (7 ngày)
                            setcookie(
                                "jwt_token",
                                $token,
                                time() + (7 * 24 * 60 * 60),
                                "/",
                                "",
                                false,
                                true
                            );

                            wp_redirect(home_url());
                            exit;
                        }
                    }

                    if (!empty($errors)) {
                        foreach ($errors as $error) {
                            echo '<div class="login-message error" style="padding-bottom:15px;">' . $error . '</div>';
                        }
                    }
                }
                ?>
                <!-- FORM LOGIN BÌNH THƯỜNG -->
                <form id="loginForm" method="post">
                    <div class="input-group">
                        <label for="login_email">Email *</label>
                        <input type="email" name="login_email" id="login_email" placeholder="Nhập email" required>
                    </div>

                    <div class="input-group">
                        <label for="login_password">Mật khẩu *</label>
                        <input type="password" name="login_password" id="login_password" placeholder="Mật khẩu"
                            required>
                    </div>

                    <!-- forget password -->
                    <div class="forgot-password"> <a href="<?php echo home_url('/forgetpassword') ?>">Quên mật khẩu?</a>
                    </div>

                    <button type="submit" class="btn btn-primary">Đăng nhập</button>

                    <!-- Divider -->
                    <div class="divider"><span>Hoặc</span></div>

                    <!-- GOOGLE SIGN-IN -->
                    <form id="googleLoginForm" method="post">
                        <input type="hidden" name="credential" id="credential">
                        <div id="g_id_onload" data-client_id="YOUR_GOOGLE_CLIENT_ID" data-login_uri=""
                            data-auto_prompt="false">
                        </div>

                        <div class="g_id_signin" data-type="standard"></div>
                    </form>

                    <p class="signup-link">
                        Chưa có tài khoản? <a href="<?php echo get_permalink(get_page_by_path('register')); ?>">Đăng
                            ký</a>
                    </p>
                </form>


            </div>
        </div>
    </div>
    <script type="text/javascript">
        var onloadCallback = function () {
            alert("grecaptcha is ready!");
        };
    </script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script>
        // Google Sign-In callback
        function handleCredentialResponse(response) {
            document.getElementById('credential').value = response.credential;
            document.getElementById('googleLoginForm').submit();
        }
        window.onload = function () {
            google.accounts.id.initialize({
                client_id: 'YOUR_GOOGLE_CLIENT_ID',
                callback: handleCredentialResponse
            });
            google.accounts.id.renderButton(
                document.querySelector('.g_id_signin'),
                { theme: 'outline', size: 'large' }
            );
        };
    </script>

    <!-- <script src="/js/login.js"></script> -->
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/login.js"></script>

</body>

</html>
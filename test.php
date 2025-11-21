<?php get_header(); ?>

<body>
    <div class="login-container">
        <div class="image-section">
            <img src="<?php echo esc_url('https://tse4.mm.bing.net/th/id/OIP.NLuP6Q7V5dg3eSdpLxUt2QHaE7?pid=Api&P=0&h=220'); ?>"
                alt="Login illustration">
        </div>

        <div class="form-section">
            <div class="form-box">
                <h1 class="title">Đăng nhập</h1>
                <p class="welcome-text">Chào mừng bạn đã quay trở lại!</p>

                <?php
                // ========== GOOGLE LOGIN HANDLER ==========
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['credential'])) {
                    $id_token = sanitize_text_field($_POST['credential']);

                    // Verify token với Google
                    $response = wp_remote_get("https://oauth2.googleapis.com/tokeninfo?id_token={$id_token}");
                    $body = wp_remote_retrieve_body($response);
                    $user_data = json_decode($body, true);

                    if (isset($user_data['email'])) {
                        $email = $user_data['email'];

                        // Check user exists
                        $user = get_user_by('email', $email);
                        if (!$user) {
                            // Create new user
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

                        // Generate JWT token
                        $token = generate_jwt_token($user->ID);

                        // Save token to cookie
                        setcookie(
                            "jwt_token",
                            $token,
                            time() + (7 * 24 * 60 * 60),
                            "/",
                            "",
                            false,
                            true
                        );

                        echo "<script>
                            localStorage.setItem('jwt_token', '{$token}');
                            window.location.href = '" . home_url('/account') . "';
                        </script>";
                        exit;
                    } else {
                        echo '<div class="login-message error" style="padding-bottom:15px;">Google login thất bại.</div>';
                    }
                }

                // ========== EMAIL + PASSWORD LOGIN HANDLER ==========
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
                            wp_set_current_user($user->ID);
                            wp_set_auth_cookie($user->ID);
                            do_action('wp_login', $user->user_login, $user);

                            $token = generate_jwt_token($user->ID);

                            setcookie(
                                "jwt_token",
                                $token,
                                time() + (7 * 24 * 60 * 60),
                                "/",
                                "",
                                false,
                                true
                            );

                            echo "<script>
                                localStorage.setItem('jwt_token', '{$token}');
                                window.location.href = '" . home_url('/account') . "';
                            </script>";
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

                <!-- ========== EMAIL LOGIN FORM ========== -->
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

                    <div class="forgot-password">
                        <a href="<?php echo home_url('/forgetpassword') ?>">Quên mật khẩu?</a>
                    </div>

                    <button type="submit" class="btn btn-primary">Đăng nhập</button>
                </form>

                <!-- Divider -->
                <div class="divider"><span>Hoặc</span></div>

                <!-- ========== GOOGLE SIGN-IN (RIÊNG BIỆT) ========== -->
                <form id="googleLoginForm" method="post" style="margin-top: 20px;">
                    <input type="hidden" name="credential" id="google_credential">

                    <!-- Google Sign-In Button Container -->
                    <div id="g_id_signin" style="width: 100%;"></div>
                </form>

                <p class="signup-link">
                    Chưa có tài khoản?
                    <a href="<?php echo get_permalink(get_page_by_path('register')); ?>">Đăng ký</a>
                </p>
            </div>
        </div>
    </div>

    <!-- ========== CUSTOM GOOGLE BUTTON STYLE ========== -->
    <style>
        /* ========== CUSTOM GOOGLE BUTTON STYLE (FIXED) ========== */

        /* Container Google button - full width */
        #g_id_signin {
            width: 100% !important;
            display: flex !important;
            justify-content: center !important;
        }

        /* Wrapper bên trong */
        #g_id_signin>div {
            width: 100% !important;
        }

        /* iframe của Google button - Match với button Đăng nhập */
        #g_id_signin iframe {
            width: 100% !important;
            height: 50px !important;
            /* Tăng chiều cao để match button trên */
            min-height: 50px !important;
            max-height: 50px !important;
            border-radius: 8px !important;
            /* Bo góc giống button trên */
        }

        /* Ẩn One Tap popup */
        #g_id_onload {
            display: none !important;
        }

        /* Ẩn credential_picker_container nếu xuất hiện */
        #credential_picker_container {
            display: none !important;
        }

        /* Optional: Thêm margin cho form Google */
        #googleLoginForm {
            margin-top: 0 !important;
        }
    </style>

    <!-- ========== GOOGLE SIGN-IN SCRIPT ========== -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script>
        // ========== GOOGLE SIGN-IN SCRIPT (FIXED) ==========
        const GOOGLE_CLIENT_ID = "338423940617-c7m265a6kd3v5nror80iomqlb2jrv5hi.apps.googleusercontent.com";

        function handleCredentialResponse(response) {
            document.getElementById('google_credential').value = response.credential;
            document.getElementById('googleLoginForm').submit();
        }

        window.addEventListener('load', function () {
            if (typeof google !== 'undefined' && google.accounts) {

                // Initialize
                google.accounts.id.initialize({
                    client_id: GOOGLE_CLIENT_ID,
                    callback: handleCredentialResponse,
                    auto_select: false,
                    cancel_on_tap_outside: true
                });

                // off onetab & selected
                google.accounts.id.disableAutoSelect();
                google.accounts.id.cancel();

                // Render button
                google.accounts.id.renderButton(
                    document.getElementById("g_id_signin"),
                    {
                        theme: "outline",
                        size: "large",
                        type: "standard",
                        text: "signin_with",
                        shape: "rectangular",
                        logo_alignment: "left",
                        width: 400
                    }
                );
            }
        });
    </script>


    <?php get_footer(); ?>
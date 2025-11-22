<?php
// ========== XỬ LÝ LOGIN TRƯỚC KHI OUTPUT BẤT KỲ HTML NÀO ==========
$login_error = '';

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

        // Set timezone Việt Nam
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        
        // Login user vào WordPress
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID);
        do_action('wp_login', $user->user_login, $user);
        
        // Generate JWT token
        $token = generate_jwt_token($user->ID);

        // Save token to cookie (httpOnly cho bảo mật)
        setcookie(
            "jwt_token",
            $token,
            time() + (7 * 24 * 60 * 60), // 7 ngày
            "/",
            "",
            false,
            true
        );

        // Lưu token vào cookie để JS có thể đọc và lưu vào localStorage
        setcookie("pending_jwt_token", $token, time() + 60, "/", "", false, false);
        
        // Chuyển hướng ngay lập tức
        wp_redirect(home_url('/account'));
        exit;
    } else {
        $login_error = 'Google login thất bại.';
    }
}

// ========== EMAIL + PASSWORD LOGIN HANDLER ==========
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_email'])) {
    $email = sanitize_email($_POST['login_email']);
    $password = $_POST['login_password'];

    if (empty($email) || empty($password)) {
        $login_error = 'Vui lòng nhập đầy đủ thông tin.';
    } else {
        $user_obj = get_user_by('email', $email);
        if ($user_obj) {
            $user = wp_authenticate($user_obj->user_login, $password);
        } else {
            $user = new WP_Error('invalid_user', 'Email không tồn tại.');
        }

        if (is_wp_error($user)) {
            $login_error = 'Email hoặc mật khẩu không đúng.';
        } else {
            // Set timezone Việt Nam
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            
            // Login user vào WordPress
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID);
            do_action('wp_login', $user->user_login, $user);
            
            // Generate JWT token
            $token = generate_jwt_token($user->ID);

            // Save token to cookie (httpOnly cho bảo mật)
            setcookie(
                "jwt_token",
                $token,
                time() + (7 * 24 * 60 * 60), // 7 ngày
                "/",
                "",
                false,
                true
            );

            // Lưu token vào cookie để JS có thể đọc và lưu vào localStorage
            setcookie("pending_jwt_token", $token, time() + 60, "/", "", false, false);
            
            // Chuyển hướng ngay lập tức
            wp_redirect(home_url('/account'));
            exit;
        }
    }
}

// ========== BẮT ĐẦU OUTPUT HTML ==========
get_header(); 
?>

<body>
    <div class="login-content">
        <div class="login-container">
            <div class="image-section">
                <img src="<?php echo esc_url('https://tradeproxy.vn/images/background/bg_login.webp'); ?>"
                    alt="Login illustration">
            </div>

            <div class="form-section">
                <div class="form-box">
                    <h1 class="title">Đăng nhập</h1>
                    <p class="welcome-text">Chào mừng bạn đã quay trở lại!</p>

                    <?php if (!empty($login_error)): ?>
                        <div class="login-message error" style="padding-bottom:15px;">
                            <?php echo esc_html($login_error); ?>
                        </div>
                    <?php endif; ?>

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
                            <a href="<?php echo esc_url(home_url('/forgetpassword')) ?>">Quên mật khẩu?</a>
                        </div>

                        <button type="submit" class="btn btn-primary">Đăng nhập</button>
                    </form>

                    <!-- Divider -->
                    <div class="divider"><span>Hoặc</span></div>

                    <!-- ========== GOOGLE SIGN-IN ========== -->
                    <form id="googleLoginForm" method="post" style="margin-top: 20px;">
                        <input type="hidden" name="credential" id="google_credential">

                        <!-- Google Sign-In Button Container -->
                        <div id="g_id_signin" style="width: 100%;"></div>
                    </form>

                    <p class="signup-link">
                        Chưa có tài khoản? <a href="<?php echo get_permalink(get_page_by_path('register')); ?>">Đăng
                            ký</a>
                    </p>

                </div>
            </div>
        </div>
    </div>

    <!-- ========== CUSTOM GOOGLE BUTTON STYLE ========== -->
    <style>
        /*button Google full width */
        #g_id_signin {
            width: 100% !important;
        }

        #g_id_signin>div {
            width: 100% !important;
        }

        #g_id_signin iframe {
            width: 100% !important;
            height: 44px !important;
            min-height: 44px !important;
        }

        /* ================= GOOGLE SIGN-IN SCRIPT ================ */
        #g_id_onload {
            display: none !important;
        }
    </style>

    <!-- ========== GOOGLE SIGN-IN SCRIPT ========== -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script>
        // Kiểm tra và lưu token vào localStorage từ cookie khi trang account load
        window.addEventListener('load', function() {
            const pendingToken = getCookie('pending_jwt_token');
            if (pendingToken) {
                localStorage.setItem('jwt_token', pendingToken);
                // Xóa cookie tạm
                document.cookie = 'pending_jwt_token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
            }
        });

        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
            return null;
        }

        const GOOGLE_CLIENT_ID = "1039910145576-cthvk2plbd1l3320nd3ieibvb5bb3o13.apps.googleusercontent.com";

        function handleCredentialResponse(response) {
            document.getElementById('google_credential').value = response.credential;
            document.getElementById('googleLoginForm').submit();
        }

        window.addEventListener('load', function() {
            if (typeof google !== 'undefined' && google.accounts) {
                google.accounts.id.initialize({
                    client_id: GOOGLE_CLIENT_ID,
                    callback: handleCredentialResponse,
                    auto_select: false,
                    cancel_on_tap_outside: true,
                    prompt_parent_id: 'g_id_signin'
                });

                google.accounts.id.disableAutoSelect();
                google.accounts.id.cancel();
                
                // RENDER BUTTON CHUẨN GOOGLE
                google.accounts.id.renderButton(
                    document.getElementById("g_id_signin"), {
                        theme: "outline",
                        size: "large",
                        width: "100%", 
                        text: "signin_with",
                        shape: "rectangular"
                    }
                );
            }
        });
    </script>

    <?php get_footer(); ?>
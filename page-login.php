<?php
$login_error = "Chào mừng bạn đã quay trở lại!";

// ========== GOOGLE LOGIN ==========
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['credential'])) {

    $id_token = sanitize_text_field($_POST['credential']);
    $response = wp_remote_get("https://oauth2.googleapis.com/tokeninfo?id_token={$id_token}");
    $body = wp_remote_retrieve_body($response);
    $user_data = json_decode($body, true);

    if (!isset($user_data['email'])) {
        $login_error = "Google login thất bại.";
    } else {
        $email = $user_data['email'];

        $user = get_user_by('email', $email);
        if (!$user) {
            // Tạo mới user
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

        // Login
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID);
        do_action('wp_login', $user->user_login, $user);

        // JWT
        $token = generate_jwt_token($user->ID);
        setcookie("jwt_token", $token, time() + 604800, "/", "", false, true);

        // JS redirect + save localStorage
        echo "<script>
            localStorage.setItem('jwt_token', '{$token}');
            window.location.href = '" . home_url('/account') . "';
        </script>";
        exit;
    }
}


// ========== EMAIL + PASSWORD LOGIN ==========
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_email'])) {

    $email = sanitize_email($_POST['login_email']);
    $password = $_POST['login_password'];

    if (empty($email) || empty($password)) {
        $login_error = "Vui lòng nhập đầy đủ thông tin.";
    } else {
        $user_obj = get_user_by('email', $email);

        if (!$user_obj) {
            $login_error = "Email không tồn tại.";
        } else {
            $user = wp_authenticate($user_obj->user_login, $password);

            if (is_wp_error($user)) {
                $login_error = "Email hoặc mật khẩu không đúng.";
            } else {
                // LOGIN OK
                wp_set_current_user($user->ID);
                wp_set_auth_cookie($user->ID);
                do_action('wp_login', $user->user_login, $user);

                $token = generate_jwt_token($user->ID);
                setcookie("jwt_token", $token, time() + 604800, "/", "", false, true);

                echo "<script>
                    localStorage.setItem('jwt_token', '{$token}');
                    window.location.href = '" . home_url('/account') . "';
                </script>";
                exit;
            }
        }
    }
}
?>

<?php get_header(); ?>

<div class="login-content">
    <div class="login-container">
        <div class="image-section">
            <img src="<?php echo esc_url('https://tradeproxy.vn/images/background/bg_login.webp'); ?>"
                alt="Login illustration">
        </div>

        <div class="form-section">
            <div class="form-box">
                <h1 class="title">Đăng nhập</h1>
                <?php if ($login_error): ?>
                <p class="welcome-text"><?php echo esc_html($login_error); ?></p>
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
                <form id="googleLoginForm" method="post" style="margin-top: 0px;">
                    <input type="hidden" name="credential" id="google_credential">

                    <!-- Custom Google Button -->
                    <button type="button" class="google-btn" id="customGoogleBtn">
                        <svg class="google-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                fill="#4285F4" />
                            <path
                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                fill="#34A853" />
                            <path
                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                                fill="#FBBC05" />
                            <path
                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                fill="#EA4335" />
                        </svg>
                        <span>Đăng nhập với Google</span>
                    </button>

                    <!-- Hidden default Google button for authentication -->
                    <div id="g_id_signin" style="display: none;"></div>
                </form>

                <p class="signup-link">
                    Chưa có tài khoản? <a href="<?php echo get_permalink(get_page_by_path('register')); ?>">Đăng ký</a>
                </p>

            </div>
        </div>
    </div>
</div>

<!-- ========== CUSTOM GOOGLE BUTTON STYLE ========== -->
<style>
/* Ẩn Google One Tap prompt */
#credential_picker_container {
    display: none !important;
}

/* Custom Google Button */
.google-btn {
    width: 100%;
    height: 48px;
    background: white;
    border: 1px solid #dadce0;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    cursor: pointer;
    font-family: 'Roboto', Arial, sans-serif;
    font-size: 14px;
    font-weight: 500;
    color: #3c4043;
    transition: all 0.3s;
    margin-bottom: 20px;
}

.google-btn:hover {
    background: #f8f9fa;
    border-color: #d2e3fc;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.google-btn:active {
    background: #f1f3f4;
}

.google-icon {
    width: 20px;
    height: 20px;
}

/* Hide default Google button */
#g_id_signin {
    display: none !important;
}
</style>

<!-- ========== GOOGLE SIGN-IN SCRIPT ========== -->
<script src="https://accounts.google.com/gsi/client" async defer></script>
<script>
const GOOGLE_CLIENT_ID = "1039910145576-cthvk2plbd1l3320nd3ieibvb5bb3o13.apps.googleusercontent.com";

function handleCredentialResponse(response) {
    console.log('Google login successful');
    document.getElementById('google_credential').value = response.credential;
    document.getElementById('googleLoginForm').submit();
}

window.addEventListener('load', function() {
    if (typeof google !== 'undefined' && google.accounts) {

        // TẮT One Tap prompt
        google.accounts.id.cancel();
        google.accounts.id.disableAutoSelect();

        // Initialize Google Sign-In
        google.accounts.id.initialize({
            client_id: GOOGLE_CLIENT_ID,
            callback: handleCredentialResponse,
            auto_select: false,
            cancel_on_tap_outside: true,
            ux_mode: 'popup' // Thêm dòng này để dùng popup mode
        });

        // Render button vào hidden div
        google.accounts.id.renderButton(
            document.getElementById('g_id_signin'), {
                theme: 'outline',
                size: 'large',
                width: 400
            }
        );

        // Custom button click handler - GỌI NÚT THẬT
        const customBtn = document.getElementById('customGoogleBtn');
        if (customBtn) {
            customBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Custom Google button clicked');

                // Click vào nút Google thật (đã render ẩn)
                const realButton = document.querySelector('#g_id_signin div[role="button"]');
                if (realButton) {
                    realButton.click();
                } else {
                    // Fallback: dùng OAuth2 flow trực tiếp
                    const authUrl =
                        `https://accounts.google.com/o/oauth2/v2/auth?client_id=${GOOGLE_CLIENT_ID}&redirect_uri=${encodeURIComponent(window.location.origin + '/login')}&response_type=id_token&scope=openid email profile&nonce=${Math.random().toString(36)}`;
                    window.location.href = authUrl;
                }
            });
        }
    }
});
</script>

<?php get_footer(); ?>
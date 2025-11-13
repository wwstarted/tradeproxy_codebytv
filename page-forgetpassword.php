<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu</title>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/login.css" />
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/forgetpass.css" />
</head>

<body>
    <div class="container">
        <!-- image -->
        <div class="image-section">
            <img src="https://tse4.mm.bing.net/th/id/OIP.NLuP6Q7V5dg3eSdpLxUt2QHaE7?pid=Api&P=0&h=220"
                alt="Forgot password illustration">
        </div>

        <!-- form forgot password -->
        <div class="form-section">
            <div class="form-box">
                <h1 class="title">Quên mật khẩu</h1>
                <p class="welcome-text">Chúng tôi sẽ gửi email cho bạn một liên kết để đặt lại mật khẩu của bạn.</p>


                <form id="forgotPasswordForm">
                    <!-- Email input -->
                    <div class="input-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" placeholder="Nhập email" required>
                    </div>

                    <!-- OTP input  -->
                    <div class="otp-group">
                        <div class="input-group" style="margin-bottom: 0; flex: 1;">
                            <label for="otp">Mã OTP *</label>
                            <input type="text" id="otp" placeholder="Nhập mã OTP" maxlength="6">
                        </div>
                        <button type="button" id="sendOtpBtn">Gửi</button>
                    </div>

                    <!-- new password -->
                    <div class="input-group">
                        <label for="newPassword">Mật khẩu mới *</label>
                        <div class="password-wrapper">
                            <input type="password" id="newPassword" placeholder="Nhập mật khẩu mới" required>
                            <button type="button" class="toggle-password" id="toggleNewPassword">
                                <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path class="eye-path" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle class="eye-circle" cx="12" cy="12" r="3"></circle>
                                    <line class="eye-slash" x1="1" y1="1" x2="23" y2="23" style="display: none;"></line>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- conform password -->
                    <div class="input-group">
                        <label for="confirmPassword">Xác nhận mật khẩu *</label>
                        <div class="password-wrapper">
                            <input type="password" id="confirmPassword" placeholder="Nhập lại mật khẩu" required>
                            <button type="button" class="toggle-password" id="toggleConfirmPassword">
                                <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path class="eye-path" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle class="eye-circle" cx="12" cy="12" r="3"></circle>
                                    <line class="eye-slash" x1="1" y1="1" x2="23" y2="23" style="display: none;"></line>
                                </svg>
                            </button>
                        </div>
                    </div>


                    <!-- recaptcha -->
                    <div class="g-recaptcha" data-sitekey="6LfNxwksAAAAALrxpsJlGqGllZ2P-UF9Wmk-5VSc"></div>

                    <!-- conform -->
                    <button type="submit" class="btn btn-primary">Xác nhận</button>

                    <!-- back to login -->
                    <div class="back-to-login">
                        <p>Đã nhớ mật khẩu? <a href="<?php echo home_url('/login') ?>">Quay lại đăng nhập</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://www.google.com/recaptcha/api.js" async defer>
    </script>
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/forgetpass.js"></script>
</body>

</html>
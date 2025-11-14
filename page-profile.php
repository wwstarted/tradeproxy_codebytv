<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
     <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/pages/profile.css" />
</head>

<body>
    <div class="page-header">
        <h1>Hồ sơ</h1>
        <p class="page-subtitle">Quản lý hồ sơ và thông tin đăng nhập</p>
    </div>

    <div class="profile-form">
        <div class="form-group">
            <label class="form-label">Họ tên</label>
            <div class="form-input-wrapper">
                <input type="text" class="form-input" id="fullname" value="Anh Nhi" placeholder="Nhập họ tên">
            </div>
        </div>
       
        <div class="form-group">
            <label class="form-label">Email</label>
            <div class="form-input-wrapper">
                <input type="email" class="form-input disabled" id="email" value="davidbeckham.tvd11@gmail.com"
                    disabled>
                <button class="edit-btn" id="edit-email-btn" title="Chỉnh sửa email">
                    <i class="fas fa-edit"></i>
                </button>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Số điện thoại</label>
            <div class="form-input-wrapper phone-input">
                <div class="country-select" id="country-select">
                    <img src="https://flagcdn.com/w40/vn.png" alt="Vietnam" class="country-flag">
                    <span class="country-code">+84</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <input type="tel" class="form-input" id="phone" value="84" placeholder="84xxxxxxxxx">
                
                <div class="country-dropdown" id="country-dropdown">
                    <div class="country-search">
                        <input type="text" placeholder="Tìm kiếm quốc gia..." id="country-search">
                    </div>
                    <div class="country-list">
                        <div class="country-item active" data-code="+84" data-flag="vn">
                            <img src="https://flagcdn.com/w40/vn.png" alt="Vietnam" class="country-flag">
                            <span class="country-name">Vietnam</span>
                            <span class="country-code">+84</span>
                        </div>
                        <div class="country-item" data-code="+1" data-flag="us">
                            <img src="https://flagcdn.com/w40/us.png" alt="United States" class="country-flag">
                            <span class="country-name">United States</span>
                            <span class="country-code">+1</span>
                        </div>
                        <div class="country-item" data-code="+44" data-flag="gb">
                            <img src="https://flagcdn.com/w40/gb.png" alt="United Kingdom" class="country-flag">
                            <span class="country-name">United Kingdom</span>
                            <span class="country-code">+44</span>
                        </div>
                        <div class="country-item" data-code="+86" data-flag="cn">
                            <img src="https://flagcdn.com/w40/cn.png" alt="China" class="country-flag">
                            <span class="country-name">China</span>
                            <span class="country-code">+86</span>
                        </div>
                        <div class="country-item" data-code="+81" data-flag="jp">
                            <img src="https://flagcdn.com/w40/jp.png" alt="Japan" class="country-flag">
                            <span class="country-name">Japan</span>
                            <span class="country-code">+81</span>
                        </div>
                        <div class="country-item" data-code="+82" data-flag="kr">
                            <img src="https://flagcdn.com/w40/kr.png" alt="South Korea" class="country-flag">
                            <span class="country-name">South Korea</span>
                            <span class="country-code">+82</span>
                        </div>
                        <div class="country-item" data-code="+65" data-flag="sg">
                            <img src="https://flagcdn.com/w40/sg.png" alt="Singapore" class="country-flag">
                            <span class="country-name">Singapore</span>
                            <span class="country-code">+65</span>
                        </div>
                        <div class="country-item" data-code="+66" data-flag="th">
                            <img src="https://flagcdn.com/w40/th.png" alt="Thailand" class="country-flag">
                            <span class="country-name">Thailand</span>
                            <span class="country-code">+66</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save -->
        <div class="form-actions">
            <button class="btn-save" id="save-profile-btn">Lưu</button>
        </div>
    </div>

    <!-- Modal OTP Email -->
    <div class="modal" id="email-otp-modal">
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h3>Xác thực Email</h3>
                <button class="modal-close" id="close-modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <p class="modal-text">Mã OTP đã được gửi đến email <strong id="current-email"></strong></p>
                <div class="otp-input-group">
                    <input type="text" class="otp-input" maxlength="1" />
                    <input type="text" class="otp-input" maxlength="1" />
                    <input type="text" class="otp-input" maxlength="1" />
                    <input type="text" class="otp-input" maxlength="1" />
                    <input type="text" class="otp-input" maxlength="1" />
                    <input type="text" class="otp-input" maxlength="1" />
                </div>
                <p class="resend-text">Không nhận được mã? <a href="#" id="resend-otp">Gửi lại</a></p>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" id="cancel-otp">Hủy</button>
                <button class="btn-verify" id="verify-otp">Xác nhận</button>
            </div>
        </div>
    </div>
</body>

</html>
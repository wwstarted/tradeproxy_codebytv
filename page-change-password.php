<div class="page-header">
    <h1>Đổi mật khẩu</h1>
    <p class="page-subtitle">Thử mật khẩu với các ký tự đặt biệt để tăng tính bảo mật</p>
</div>

<div class="password-form">
    <div class="form-group">
        <label class="form-label">
            Mật khẩu cũ<span class="required">*</span>
        </label>
        <div class="password-input-wrapper">
            <input type="password" class="form-input" id="current-password" placeholder="Mật khẩu cũ">
            <button type="button" class="toggle-password" onclick="togglePassword('current-password', this)">
                <i class="fas fa-eye"></i>
            </button>
        </div>
    </div>


    <div class="form-group">
        <label class="form-label">
            Mật khẩu mới<span class="required">*</span>
        </label>
        <div class="password-input-wrapper">
            <input type="password" class="form-input" id="new-password" placeholder="Mật khẩu mới"
                oninput="checkPasswordStrength(this.value)">
            <button type="button" class="toggle-password" onclick="togglePassword('new-password', this)">
                <i class="fas fa-eye"></i>
            </button>
        </div>
        <div class="password-strength" id="password-strength">
            <div class="strength-bar">
                <div class="strength-fill" id="strength-fill"></div>
            </div>
            <span class="strength-text" id="strength-text"></span>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">
            Xác nhận mật khẩu mới<span class="required">*</span>
        </label>
        <div class="password-input-wrapper">
            <input type="password" class="form-input" id="confirm-password" placeholder="Xác nhận mật khẩu mới">
            <button type="button" class="toggle-password" onclick="togglePassword('confirm-password', this)">
                <i class="fas fa-eye"></i>
            </button>
        </div>
    </div>

    <div class="form-actions">
        <button class="btn-save" onclick="changePassword()">Lưu</button>
    </div>
</div>

<div class="modal" id="change-password-otp-modal">
    <div class="modal-overlay modal-overlay-change-password"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3>Xác thực đổi mật khẩu</h3>
            <button class="modal-close" id="close-modal-change-password">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p class="modal-text">Mã OTP đã được gửi đến email của bạn để xác thực danh tính</p>
            <div class="otp-input-group">
                <input type="text" class="otp-input otp-input-change-password" maxlength="1" />
                <input type="text" class="otp-input otp-input-change-password" maxlength="1" />
                <input type="text" class="otp-input otp-input-change-password" maxlength="1" />
                <input type="text" class="otp-input otp-input-change-password" maxlength="1" />
                <input type="text" class="otp-input otp-input-change-password" maxlength="1" />
                <input type="text" class="otp-input otp-input-change-password" maxlength="1" />
            </div>
            <p class="resend-text resend-text-change-password">Không nhận được mã? <a href="#" id="resend-otp-change-password">Gửi lại</a></p>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" id="cancel-otp-change-password">Hủy</button>
            <button class="btn-verify" id="verify-otp-change-password">Xác nhận</button>
        </div>
    </div>
</div>
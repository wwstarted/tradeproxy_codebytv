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
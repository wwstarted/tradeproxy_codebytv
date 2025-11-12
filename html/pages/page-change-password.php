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
            <input 
                type="password" 
                class="form-input" 
                id="current-password" 
                placeholder="Mật khẩu cũ"
            >
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
            <input 
                type="password" 
                class="form-input" 
                id="new-password" 
                placeholder="Mật khẩu mới"
                oninput="checkPasswordStrength(this.value)"
            >
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
            <input 
                type="password" 
                class="form-input" 
                id="confirm-password" 
                placeholder="Xác nhận mật khẩu mới"
            >
            <button type="button" class="toggle-password" onclick="togglePassword('confirm-password', this)">
                <i class="fas fa-eye"></i>
            </button>
        </div>
    </div>

    <div class="form-actions">
        <button class="btn-save" onclick="changePassword()">Lưu</button>
    </div>
</div>

<style>
/* Password Form Styles */
.password-form {
    background: white;
    padding: 40px;
    border-radius: 10px;
    /* box-shadow: 0 2px 4px rgba(0,0,0,0.08); */
    width: 100%;                
    max-width: none;            
    box-sizing: border-box;
}

.form-group {
    margin-bottom: 25px;
}

.form-label {
    display: block;
    margin-bottom: 10px;
    color: #333;
    font-size: 14px;
    font-weight: 500;
}

.form-label .required {
    color: #f44336;
    margin-left: 2px;
}

.password-input-wrapper {
    position: relative;
    width: 100%; 
}

.form-input {
    width: 100%;                
    padding: 14px 45px 14px 16px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 15px;
    transition: all 0.3s;
    box-sizing: border-box;     
}

.form-input:focus {
    outline: none;
    border-color: #2196F3;
    box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.1);
}

.form-input::placeholder {
    color: #999;
}

.toggle-password {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #999;
    cursor: pointer;
    font-size: 18px;
    padding: 5px;
}

.toggle-password:hover {
    color: #666;
}

.form-actions {
    margin-top: 35px;
    display: flex;
    justify-content: flex-end;
}

.btn-save {
    background: #2196F3;
    color: white;
    border: none;
    padding: 14px 40px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 15px;
    font-weight: 500;
    transition: all 0.3s;
}

.btn-save:hover {
    background: #1976D2;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(33, 150, 243, 0.3);
}

.btn-save:active {
    transform: translateY(0);
}

/* Password strength indicator */
.password-strength {
    margin-top: 8px;
    display: none;
}

.password-strength.show {
    display: block;
}

.strength-bar {
    height: 4px;
    background: #eee;
    border-radius: 2px;
    overflow: hidden;
    margin-bottom: 6px;
}

.strength-fill {
    height: 100%;
    width: 0%;
    transition: all 0.3s;
    border-radius: 2px;
}

.strength-fill.weak {
    width: 33%;
    background: #f44336;
}

.strength-fill.medium {
    width: 66%;
    background: #ff9800;
}

.strength-fill.strong {
    width: 100%;
    background: #4CAF50;
}

.strength-text {
    font-size: 12px;
    color: #666;
}

/* Responsive fix */
@media (max-width: 768px) {
    .password-form {
        padding: 20px;
    }

    .btn-save {
        width: 100%;
        text-align: center;
    }
}

</style>

<script>
// Toggle password visibility
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Check password strength
function checkPasswordStrength(password) {
    const strengthIndicator = document.getElementById('password-strength');
    const strengthFill = document.getElementById('strength-fill');
    const strengthText = document.getElementById('strength-text');
    
    if (password.length === 0) {
        strengthIndicator.classList.remove('show');
        return;
    }
    
    strengthIndicator.classList.add('show');
    
    let strength = 0;
    
    // Check length
    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;
    
    // Check for lowercase and uppercase
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    
    // Check for numbers
    if (/\d/.test(password)) strength++;
    
    // Check for special characters
    if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength++;
    
    // Update UI
    strengthFill.className = 'strength-fill';
    
    if (strength <= 2) {
        strengthFill.classList.add('weak');
        strengthText.textContent = 'Mật khẩu yếu';
        strengthText.style.color = '#f44336';
    } else if (strength <= 4) {
        strengthFill.classList.add('medium');
        strengthText.textContent = 'Mật khẩu trung bình';
        strengthText.style.color = '#ff9800';
    } else {
        strengthFill.classList.add('strong');
        strengthText.textContent = 'Mật khẩu mạnh';
        strengthText.style.color = '#4CAF50';
    } 
}

// Change password
function changePassword() {
    const currentPassword = document.getElementById('current-password').value;
    const newPassword = document.getElementById('new-password').value;
    const confirmPassword = document.getElementById('confirm-password').value;
    
    // Validation
    if (!currentPassword) {
        alert('Vui lòng nhập mật khẩu cũ');
        return;
    }
    
    if (!newPassword) {
        alert('Vui lòng nhập mật khẩu mới');
        return;
    }
    
    if (newPassword.length < 8) {
        alert('Mật khẩu mới phải có ít nhất 8 ký tự');
        return;
    }
    
    if (newPassword !== confirmPassword) {
        alert('Mật khẩu xác nhận không khớp');
        return;
    }
    
    if (currentPassword === newPassword) {
        alert('Mật khẩu mới không được trùng với mật khẩu cũ');
        return;
    }
    
    // Success
    console.log({
        currentPassword,
        newPassword
    });
    
    alert('Đổi mật khẩu thành công!');
    
    // Clear form
    document.getElementById('current-password').value = '';
    document.getElementById('new-password').value = '';
    document.getElementById('confirm-password').value = '';
    document.getElementById('password-strength').classList.remove('show');
}
</script>
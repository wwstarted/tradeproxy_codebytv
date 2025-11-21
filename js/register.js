// ========== TẠO PASSWORD STRENGTH INDICATOR (DYNAMIC) ==========
document.addEventListener('DOMContentLoaded', function() {
function createPasswordStrengthIndicator() {
    const passwordInput = document.getElementById('password');
    if (!passwordInput) return;

    // Kiểm tra xem đã có indicator chưa
    let indicator = document.getElementById('password-strength');
    if (indicator) return;

    // Tạo HTML cho strength indicator
    const strengthHTML = `
        <div class="password-strength" id="password-strength" style="margin-top: 8px; display: none;">
            <div class="strength-bar" style="height: 4px; background: #eee; border-radius: 2px; overflow: hidden; margin-bottom: 6px;">
                <div class="strength-fill" id="strength-fill" style="height: 100%; width: 0%; transition: all 0.3s; border-radius: 2px;"></div>
            </div>
            <span class="strength-text" id="strength-text" style="font-size: 12px; color: #666;"></span>
        </div>
    `;

    // Insert sau password input
    passwordInput.insertAdjacentHTML('afterend', strengthHTML);

    console.log('✅ Password strength indicator created');
}

// ========== CHECK PASSWORD STRENGTH (VISUAL) ==========
function checkPasswordStrength(password) {
    const strengthIndicator = document.getElementById('password-strength');
    const strengthFill = document.getElementById('strength-fill');
    const strengthText = document.getElementById('strength-text');

    if (!strengthIndicator || !strengthFill || !strengthText) {
        console.warn('Strength indicator elements not found');
        return;
    }

    // 
    if (password.length === 0) {
        strengthIndicator.style.display = 'none';
        return;
    }

    // 
    strengthIndicator.style.display = 'block';

    let strength = 0;
    let feedback = [];

    // Check length
    if (password.length >= 8) {
        strength++;
    } else {
        feedback.push('8+ ký tự');
    }

    if (password.length >= 12) {
        strength++;
    }

    // Check lowercase
    if (/[a-z]/.test(password)) {
        strength++;
    } else {
        feedback.push('chữ thường (a-z)');
    }

    // Check uppercase
    if (/[A-Z]/.test(password)) {
        strength++;
    } else {
        feedback.push('chữ hoa (A-Z)');
    }

    // Check numbers
    if (/\d/.test(password)) {
        strength++;
    } else {
        feedback.push('số (0-9)');
    }

    // Check special characters (bonus)
    if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
        strength++;
    }

    // Update UI
    strengthFill.className = 'strength-fill';
    strengthFill.style.transition = 'all 0.3s';
    strengthFill.style.height = '100%';
    strengthFill.style.borderRadius = '2px';

    let strengthLevel = 'weak';
    let strengthColor = '#f44336';
    let strengthLabel = 'Mật khẩu yếu';
    let width = '33%';

    if (strength <= 2) {
        strengthLevel = 'weak';
        strengthColor = '#f44336';
        strengthLabel = 'Mật khẩu yếu';
        width = '33%';
    } else if (strength <= 4) {
        strengthLevel = 'medium';
        strengthColor = '#ff9800';
        strengthLabel = 'Mật khẩu trung bình';
        width = '66%';
    } else {
        strengthLevel = 'strong';
        strengthColor = '#4CAF50';
        strengthLabel = 'Mật khẩu mạnh';
        width = '100%';
    }

    // Apply styles
    strengthFill.style.width = width;
    strengthFill.style.background = strengthColor;
    strengthText.style.color = strengthColor;

    // Set text với feedback
    if (feedback.length > 0 && strength <= 3) {
        strengthText.textContent = `${strengthLabel}`;
        // strengthText.textContent = `${strengthLabel} - Thiếu: ${feedback.join(', ')}`;
    } else {
        strengthText.textContent = strengthLabel;
    }
}

// ========== VALIDATE PASSWORD FUNCTION ==========
function validatePassword(password, confirmPassword) {
    // 1. Check độ dài tối thiểu
    if (password.length < 8) {
        return { 
            valid: false, 
            message: 'Mật khẩu phải có ít nhất 8 ký tự' 
        };
    }

    // 2. Check chữ thường
    if (!/[a-z]/.test(password)) {
        return { 
            valid: false, 
            message: 'Mật khẩu phải chứa ít nhất 1 chữ thường (a-z)' 
        };
    }

    // 3. Check chữ hoa
    if (!/[A-Z]/.test(password)) {
        return { 
            valid: false, 
            message: 'Mật khẩu phải chứa ít nhất 1 chữ hoa (A-Z)' 
        };
    }

    // 4. Check số
    if (!/\d/.test(password)) {
        return { 
            valid: false, 
            message: 'Mật khẩu phải chứa ít nhất 1 chữ số (0-9)' 
        };
    }

    // 5. Check password khớp
    if (password !== confirmPassword) {
        return { 
            valid: false, 
            message: 'Mật khẩu xác nhận không khớp' 
        };
    }

    return { valid: true };
}

// ========== INIT ==========

    const registerForm = document.getElementById('registerForm');
    if (!registerForm) return;

    // Tạo strength indicator
    createPasswordStrengthIndicator();

    // Auto-check password strength khi gõ
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', function(e) {
            checkPasswordStrength(e.target.value);
        });
        console.log('✅ Password strength checker enabled');
    }

    // Handle form submission
    registerForm.addEventListener('submit', function(e) {
        const firstName = document.getElementById('firstName').value.trim();
        const lastName = document.getElementById('lastName').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        const terms = document.getElementById('terms').checked;

        // 1. Check empty fields
        if (!firstName || !lastName || !email || !password || !confirmPassword) {
            e.preventDefault();
            alert('Vui lòng điền đầy đủ thông tin!');
            return false;
        }

        // 2. Check email format
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert('Email không hợp lệ!');
            return false;
        }

        // ========== 3. VALIDATE PASSWORD STRENGTH ==========
        const validation = validatePassword(password, confirmPassword);
        if (!validation.valid) {
            e.preventDefault();
            alert(validation.message);
            passwordInput.focus();
            return false;
        }

        // 4. Check terms
        if (!terms) {
            e.preventDefault();
            alert('Vui lòng đồng ý với điều khoản dịch vụ!');
            return false;
        }

        // 5. Check reCAPTCHA (nếu có)
        if (typeof grecaptcha !== 'undefined') {
            const recaptchaResponse = grecaptcha.getResponse();
            if (!recaptchaResponse || recaptchaResponse.length === 0) {
                e.preventDefault();
                alert('Vui lòng xác nhận bạn không phải là người máy!');
                return false;
            }
        }

        // All validations passed - form will submit normally
        console.log('✅ Form validation passed, submitting...');
        return true;
    });

    console.log('✅ Register form initialized');
});
// ===== changepassword.js =====
console.log("🔐 [ChangePassword] Script loaded");

const API_CHANGE_PASSWORD_URL = wpAccountData.restUrl + 'my-api/v1/user/change-password/';

// ========== TOGGLE PASSWORD VISIBILITY (GLOBAL) ==========
window.togglePassword = function(inputId, button) {
  console.log("👁️ togglePassword called for:", inputId);
  const input = document.getElementById(inputId);
  const icon = button.querySelector('i');

  if (!input || !icon) {
    console.error("❌ Input or icon not found!");
    return;
  }

  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.remove('fa-eye');
    icon.classList.add('fa-eye-slash');
    console.log("✅ Password visible");
  } else {
    input.type = 'password';
    icon.classList.remove('fa-eye-slash');
    icon.classList.add('fa-eye');
    console.log("✅ Password hidden");
  }
};
console.log("✅ togglePassword defined:", typeof window.togglePassword);

// ========== CHECK PASSWORD STRENGTH (GLOBAL) ==========
window.checkPasswordStrength = function(password) {
  const strengthIndicator = document.getElementById('password-strength');
  const strengthFill = document.getElementById('strength-fill');
  const strengthText = document.getElementById('strength-text');

  if (!strengthIndicator || !strengthFill || !strengthText) return;

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
};

function initChangePasswordPage() {
  const token = localStorage.getItem("jwt_token");
  if (!token) {
    alert("Vui lòng đăng nhập!");
    window.location.href = wpAccountData.loginUrl;
    return;
  }

  // Check required elements
  const required = ["current-password", "new-password", "confirm-password"];
  if (!required.every(id => document.getElementById(id))) {
    console.error("Thiếu phần tử HTML bắt buộc");
    return;
  }

  // Elements
  const currentPasswordInput = document.getElementById("current-password");
  const newPasswordInput = document.getElementById("new-password");
  const confirmPasswordInput = document.getElementById("confirm-password");
  const saveBtn = document.querySelector(".btn-save");
  const strengthIndicator = document.getElementById("password-strength");
  const strengthFill = document.getElementById("strength-fill");
  const strengthText = document.getElementById("strength-text");

  // ========== VALIDATE PASSWORD ==========
  function validatePasswordInput(password, confirmPassword) {
    if (password.length < 8) {
      return { valid: false, message: 'Mật khẩu mới phải có ít nhất 8 ký tự' };
    }

    if (!/[a-z]/.test(password)) {
      return { valid: false, message: 'Mật khẩu phải chứa ít nhất 1 chữ thường' };
    }

    if (!/[A-Z]/.test(password)) {
      return { valid: false, message: 'Mật khẩu phải chứa ít nhất 1 chữ hoa' };
    }

    if (!/\d/.test(password)) {
      return { valid: false, message: 'Mật khẩu phải chứa ít nhất 1 chữ số' };
    }

    if (password !== confirmPassword) {
      return { valid: false, message: 'Mật khẩu xác nhận không khớp' };
    }

    return { valid: true };
  }

  // ========== CHANGE PASSWORD API ==========
  window.changePassword = async function() {
    const currentPassword = currentPasswordInput.value.trim();
    const newPassword = newPasswordInput.value.trim();
    const confirmPassword = confirmPasswordInput.value.trim();

    // Validation
    if (!currentPassword) {
      alert('Vui lòng nhập mật khẩu cũ');
      currentPasswordInput.focus();
      return;
    }

    if (!newPassword) {
      alert('Vui lòng nhập mật khẩu mới');
      newPasswordInput.focus();
      return;
    }

    if (!confirmPassword) {
      alert('Vui lòng xác nhận mật khẩu mới');
      confirmPasswordInput.focus();
      return;
    }

    if (currentPassword === newPassword) {
      alert('Mật khẩu mới không được trùng với mật khẩu cũ');
      newPasswordInput.focus();
      return;
    }

    // Validate password strength
    const validation = validatePasswordInput(newPassword, confirmPassword);
    if (!validation.valid) {
      alert(validation.message);
      newPasswordInput.focus();
      return;
    }

    if (!saveBtn) return;

    saveBtn.textContent = 'Đang lưu...';
    saveBtn.disabled = true;

    console.log("🔐 [ChangePassword] Starting password change...");

    try {
      const response = await fetch(API_CHANGE_PASSWORD_URL, {
        method: "POST",
        headers: {
          "Authorization": `Bearer ${token}`,
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          current_password: currentPassword,
          new_password: newPassword,
        }),
      });

      if (!response.ok) {
        if (response.status === 401) {
          const result = await response.json().catch(() => ({}));
          if (result.message && result.message.includes('mật khẩu cũ')) {
            throw new Error('Mật khẩu cũ không đúng');
          }
          localStorage.removeItem("jwt_token");
          alert("Phiên đăng nhập hết hạn!");
          window.location.href = wpAccountData.loginUrl;
          return;
        }
        const err = await response.json().catch(() => ({}));
        throw new Error(err.message || `Lỗi ${response.status}`);
      }

      const result = await response.json();
      console.log("📦 [ChangePassword] Response:", result);

      // Success
      alert("✅ Đổi mật khẩu thành công!");
      
      // Clear form
      currentPasswordInput.value = '';
      newPasswordInput.value = '';
      confirmPasswordInput.value = '';
      
      // Hide strength indicator
      if (strengthIndicator) {
        strengthIndicator.classList.remove('show');
      }

      // Reset all password type and icons
      ['current-password', 'new-password', 'confirm-password'].forEach(id => {
        const input = document.getElementById(id);
        if (input && input.type === 'text') {
          input.type = 'password';
          const wrapper = input.closest('.password-input-wrapper');
          if (wrapper) {
            const icon = wrapper.querySelector('.toggle-password i');
            if (icon) {
              icon.classList.remove('fa-eye-slash');
              icon.classList.add('fa-eye');
            }
          }
        }
      });

      console.log("✅ [ChangePassword] Password changed successfully");

    } catch (err) {
      console.error("❌ [ChangePassword] Error:", err);
      alert(err.message || "Lỗi kết nối server");
    } finally {
      saveBtn.textContent = 'Lưu';
      saveBtn.disabled = false;
    }
  };

  // ========== EVENT LISTENERS ==========
  
  // ===== TOGGLE PASSWORD BUTTONS =====
  // Attach event listeners thay vì dùng onclick inline
  document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
      const wrapper = this.closest('.password-input-wrapper');
      const input = wrapper.querySelector('.form-input');
      const icon = this.querySelector('i');
      
      if (!input || !icon) return;
      
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });
  });
  
  // Auto-check strength when typing
  if (newPasswordInput) {
    newPasswordInput.addEventListener('input', (e) => {
      checkPasswordStrength(e.target.value);
    });
  }

  // Enter key support
  [currentPasswordInput, newPasswordInput, confirmPasswordInput].forEach(input => {
    if (input) {
      input.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
          changePassword();
        }
      });
    }
  });
}

// Export global
window.initChangePasswordPage = initChangePasswordPage;
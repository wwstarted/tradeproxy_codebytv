// ==================== WRAP TOÀN BỘ CODE TRONG DOMContentLoaded ====================
document.addEventListener('DOMContentLoaded', function() {

// ==================== TOGGLE PASSWORD (ĐÃ CÓ SẴN) ====================
const OTP_FORGOT_PASSWORD_URL = wpAccountData.restUrl + 'my-api/v1/otp/';

// Toggle Mật khẩu mới
document
  .getElementById("toggleNewPassword")
  .addEventListener("click", function () {
    const input = document.getElementById("newPassword");
    const eyePath = this.querySelector(".eye-path");
    const eyeCircle = this.querySelector(".eye-circle");
    const eyeSlash = this.querySelector(".eye-slash");

    if (input.type === "password") {
      input.type = "text";
      eyeSlash.style.display = "block";
      eyePath.style.opacity = "0.3";
      eyeCircle.style.opacity = "0.3";
    } else {
      input.type = "password";
      eyeSlash.style.display = "none";
      eyePath.style.opacity = "1";
      eyeCircle.style.opacity = "1";
    }
  });

// Toggle Xác nhận mật khẩu
document
  .getElementById("toggleConfirmPassword")
  .addEventListener("click", function () {
    const input = document.getElementById("confirmPassword");
    const eyePath = this.querySelector(".eye-path");
    const eyeCircle = this.querySelector(".eye-circle");
    const eyeSlash = this.querySelector(".eye-slash");

    if (input.type === "password") {
      input.type = "text";
      eyeSlash.style.display = "block";
      eyePath.style.opacity = "0.3";
      eyeCircle.style.opacity = "0.3";
    } else {
      input.type = "password";
      eyeSlash.style.display = "none";
      eyePath.style.opacity = "1";
      eyeCircle.style.opacity = "1";
    }
  });

// ==================== FORGOT PASSWORD - OTP SYSTEM ====================
let otpSent = false;
let countdownTimer = null;

// Helper function: Validate email
function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

// ========== TẠO PASSWORD STRENGTH INDICATOR ==========
function createPasswordStrengthIndicator() {
  const passwordInput = document.getElementById('newPassword');
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

  // Insert sau password wrapper
  const passwordWrapper = passwordInput.closest('.password-wrapper');
  const inputGroup = passwordInput.closest('.input-group');
  
  if (inputGroup) {
    inputGroup.insertAdjacentHTML('beforeend', strengthHTML);
  } else if (passwordWrapper) {
    passwordWrapper.insertAdjacentHTML('afterend', strengthHTML);
  } else {
    passwordInput.insertAdjacentHTML('afterend', strengthHTML);
  }

  console.log('✅ Password strength indicator created');
}

// ========== CHECK PASSWORD STRENGTH ==========
function checkPasswordStrength(password) {
  const strengthIndicator = document.getElementById('password-strength');
  const strengthFill = document.getElementById('strength-fill');
  const strengthText = document.getElementById('strength-text');

  if (!strengthIndicator || !strengthFill || !strengthText) {
    console.warn('Strength indicator elements not found');
    return;
  }

  // Ẩn nếu chưa nhập gì
  if (password.length === 0) {
    strengthIndicator.style.display = 'none';
    return;
  }

  // Hiển thị indicator
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
  } else {
    strengthText.textContent = strengthLabel;
  }
}

// ========== VALIDATE PASSWORD ==========
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

// Countdown timer function
function startCountdown(seconds) {
  let remaining = seconds;
  const btn = document.getElementById("sendOtpBtn");
  
  btn.disabled = true;
  btn.textContent = `Gửi lại (${remaining}s)`;
  
  countdownTimer = setInterval(function() {
    remaining--;
    if (remaining <= 0) {
      clearInterval(countdownTimer);
      btn.disabled = false;
      btn.textContent = "Gửi lại";
    } else {
      btn.textContent = `Gửi lại (${remaining}s)`;
    }
  }, 1000);
}

// Send OTP button
document.getElementById("sendOtpBtn").addEventListener("click", function () {
  const email = document.getElementById("email").value.trim();
  const btn = this;
  
  // Validation
  if (!email) {
    alert("Vui lòng nhập email");
    document.getElementById("email").focus();
    return;
  }
  
  if (!isValidEmail(email)) {
    alert("Email không hợp lệ");
    document.getElementById("email").focus();
    return;
  }
  
  // Disable button and show loading
  btn.disabled = true;
  btn.textContent = "Đang gửi...";
  
  // Send OTP request
  fetch(OTP_FORGOT_PASSWORD_URL + 'send-forgot-password', {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ email: email }),
  })
    .then(response => {
      console.log("Response status:", response.status);
      
      const clonedResponse = response.clone();
      
      if (!response.ok) {
        return clonedResponse.text().then(text => {
          console.error("Error response text:", text);
          try {
            const json = JSON.parse(text);
            return Promise.reject(json);
          } catch (e) {
            return Promise.reject({ message: "Server trả về lỗi: " + text.substring(0, 200) });
          }
        });
      }
      return response.json();
    })
    .then(data => {
      console.log("OTP sent:", data);
      
      otpSent = true;
      alert("✅ Mã OTP đã được gửi đến email: " + email);
      
      // Focus vào input OTP
      document.getElementById("otp").focus();
      
      // Start countdown (5 minutes)
      startCountdown(300);
    })
    .catch(error => {
      console.error("Send OTP error:", error);
      
      let errorMsg = "Không thể gửi OTP. Vui lòng thử lại.";
      
      if (error.message) {
        errorMsg = error.message;
      } else if (error.data && error.data.message) {
        errorMsg = error.data.message;
      }
      
      alert("❌ " + errorMsg);
      btn.disabled = false;
      btn.textContent = "Gửi";
    });
});

// Submit form - Verify OTP and Reset Password
document.getElementById("forgotPasswordForm").addEventListener("submit", function (e) {
  e.preventDefault();
  
  // Validate reCAPTCHA
  const recaptchaResponse = grecaptcha.getResponse();
  if (!recaptchaResponse) {
    alert("Vui lòng xác thực reCAPTCHA");
    return;
  }
  
  const email = document.getElementById("email").value.trim();
  const otp = document.getElementById("otp").value.trim();
  const newPassword = document.getElementById("newPassword").value;
  const confirmPassword = document.getElementById("confirmPassword").value;
  
  // Validation
  if (!email || !isValidEmail(email)) {
    alert("Email không hợp lệ");
    document.getElementById("email").focus();
    return;
  }
  
  if (!otp || otp.length !== 6) {
    alert("Vui lòng nhập mã OTP (6 chữ số)");
    document.getElementById("otp").focus();
    return;
  }
  
  if (!otpSent) {
    alert("Vui lòng gửi mã OTP trước");
    document.getElementById("sendOtpBtn").focus();
    return;
  }
  
  // ========== VALIDATE PASSWORD STRENGTH ==========
  const validation = validatePassword(newPassword, confirmPassword);
  if (!validation.valid) {
    alert(validation.message);
    document.getElementById("newPassword").focus();
    return;
  }
  
  // Show loading state
  const submitBtn = this.querySelector('button[type="submit"]');
  const originalText = submitBtn.textContent;
  submitBtn.disabled = true;
  submitBtn.textContent = "Đang xử lý...";
  
  // Verify OTP and Reset Password
  fetch(OTP_FORGOT_PASSWORD_URL + 'verify-forgot-password', {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      email: email,
      otp: otp,
      new_password: newPassword,
      confirm_password: confirmPassword
    }),
  })
    .then(response => {
      console.log("Response status:", response.status);
      
      const clonedResponse = response.clone();
      
      if (!response.ok) {
        return clonedResponse.text().then(text => {
          console.error("Error response text:", text);
          try {
            const json = JSON.parse(text);
            return Promise.reject(json);
          } catch (e) {
            return Promise.reject({ message: "Server trả về lỗi: " + text.substring(0, 200) });
          }
        });
      }
      return response.json();
    })
    .then(data => {
      console.log("Reset password success:", data);
      
      // Clear countdown timer
      if (countdownTimer) {
        clearInterval(countdownTimer);
      }
      
      alert("✅ " + data.message);
      
      // Reset form
      document.getElementById("forgotPasswordForm").reset();
      grecaptcha.reset();
      
      // Reset password strength indicator
      const strengthIndicator = document.getElementById('password-strength');
      if (strengthIndicator) {
        strengthIndicator.style.display = 'none';
      }
      
      // Redirect to login page
      if (data.redirect) {
        setTimeout(function() {
          window.location.href = data.redirect;
        }, 1000);
      }
    })
    .catch(error => {
      console.error("Reset password error:", error);
      
      let errorMsg = "Đã xảy ra lỗi. Vui lòng thử lại.";
      
      if (error.message) {
        errorMsg = error.message;
      } else if (error.data && error.data.message) {
        errorMsg = error.data.message;
      }
      
      alert("❌ " + errorMsg);
      
      // Reset submit button
      submitBtn.disabled = false;
      submitBtn.textContent = originalText;
    });
});

// Only allow numbers in OTP input
document.getElementById("otp").addEventListener("input", function () {
  this.value = this.value.replace(/[^0-9]/g, "");
  if (this.value.length > 6) {
    this.value = this.value.slice(0, 6);
  }
  
  // Auto-focus to password field when OTP is complete
  if (this.value.length === 6) {
    document.getElementById("newPassword").focus();
  }
});

// Press Enter on email field to send OTP
document.getElementById("email").addEventListener("keypress", function (e) {
  if (e.key === "Enter") {
    e.preventDefault();
    document.getElementById("sendOtpBtn").click();
  }
});

// ========== INIT - CHẠY KHI TRANG LOAD ==========
// Tạo password strength indicator
createPasswordStrengthIndicator();

// Auto-check password strength khi gõ
const newPasswordInput = document.getElementById('newPassword');
if (newPasswordInput) {
  newPasswordInput.addEventListener('input', function(e) {
    checkPasswordStrength(e.target.value);
  });
  console.log('✅ Password strength checker enabled');
}

console.log('✅ Forgot password form initialized');

});
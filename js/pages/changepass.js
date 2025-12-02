// ===== changepassword.js =====
console.log("🔐 [ChangePassword] Script loaded");

const API_CHANGE_PASSWORD_URL = wpAccountData.restUrl + 'my-api/v1/user/change-password/';
const OTP_CHANGE_PASSWORD_URL = wpAccountData.restUrl + 'my-api/v1/otp/';

// ========== TOGGLE PASSWORD VISIBILITY (GLOBAL) ==========
window.togglePassword = function(inputId, button) {
    console.log("togglePassword called for:", inputId);
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');

    if (!input || !icon) {
        console.error("Input or icon not found!");
        return;
    }

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
        console.log("Password visible");
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
        console.log("Password hidden");
    }
};
console.log("togglePassword defined:", typeof window.togglePassword);

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
    const otpModal = document.getElementById("change-password-otp-modal");
    const otpInputs = document.querySelectorAll(".otp-input-change-password");

    // OTP timer
    let otpTimer = null;
    let remainingTime = 300;

    // save password
    let pendingPasswordChange = null;

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

    // ========== OTP FUNCTIONS ==========
    
    // send OTP
    async function sendChangePasswordOTP() {
        try {
            const response = await fetch(OTP_CHANGE_PASSWORD_URL + 'send-change-password', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Không thể gửi OTP');
            }

            return result;

        } catch (error) {
            console.error('❌ [OTP] Send error:', error);
            throw error;
        }
    }

    // Đếm ngược timer
    function startOtpTimer() {
        const resendText = document.querySelector(".resend-text-change-password");
        if (!resendText) return;
        
        clearInterval(otpTimer);
        
        otpTimer = setInterval(() => {
            remainingTime--;
            
            const minutes = Math.floor(remainingTime / 60);
            const seconds = remainingTime % 60;
            resendText.innerHTML = `Mã hết hạn sau <strong>${minutes}:${seconds.toString().padStart(2, '0')}</strong>`;
            
            if (remainingTime <= 0) {
                clearInterval(otpTimer);
                resendText.innerHTML = 'Mã đã hết hạn. <a href="#" id="resend-otp-change-password">Gửi lại</a>';
                const resendLink = document.getElementById("resend-otp-change-password");
                if (resendLink) {
                    resendLink.addEventListener("click", handleResendOtp);
                }
            }
        }, 1000);
    }

    // Resend OTP
    async function handleResendOtp(e) {
        e.preventDefault();
        const resendLink = e.target;
        resendLink.textContent = "Đang gửi...";
        resendLink.style.pointerEvents = "none";

        try {
            const result = await sendChangePasswordOTP();
            
            remainingTime = result.expires_in || 300;
            startOtpTimer();
            
            if (result.debug_otp) {
                console.log('🔐 DEBUG OTP (Resend):', result.debug_otp);
            }
            
            alert('✅ ' + result.message);

        } catch (error) {
            alert(error.message || 'Không thể gửi lại mã OTP');
        } finally {
            resendLink.textContent = "Gửi lại";
            resendLink.style.pointerEvents = "auto";
        }
    }

    // ========== CHANGE PASSWORD WITH OTP ==========
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

        saveBtn.textContent = 'Đang gửi OTP...';
        saveBtn.disabled = true;

        console.log("🔐 [ChangePassword] Sending OTP...");

        try {
            // Bước 1: Gửi OTP
            const otpResult = await sendChangePasswordOTP();
            
            // Lưu thông tin để dùng sau khi verify
            pendingPasswordChange = {
                currentPassword: currentPassword,
                newPassword: newPassword
            };

            // Hiển thị modal OTP
            if (otpModal) {
                otpModal.classList.add("show");
            }

            // Bắt đầu timer
            remainingTime = otpResult.expires_in || 300;
            startOtpTimer();

            // DEBUG
            if (otpResult.debug_otp) {
                console.log('🔐 DEBUG OTP:', otpResult.debug_otp);
                console.log('⚠️ Xóa debug_otp khi production!');
            }

            alert('✅ ' + otpResult.message);

        } catch (error) {
            console.error('❌ [ChangePassword] OTP error:', error);
            alert(error.message || 'Không thể gửi mã OTP');
        } finally {
            saveBtn.textContent = 'Lưu';
            saveBtn.disabled = false;
        }
    };

    // ========== VERIFY OTP & CHANGE PASSWORD ==========
    async function verifyOtpAndChangePassword() {
        const otp = Array.from(otpInputs).map(i => i.value).join("");
        
        if (otp.length !== 6) {
            alert("Vui lòng nhập đủ 6 số!");
            return;
        }

        if (!pendingPasswordChange) {
            alert("Lỗi: Không tìm thấy thông tin đổi mật khẩu");
            return;
        }

        const verifyBtn = document.getElementById("verify-otp-change-password");
        if (!verifyBtn) return;

        const originalText = verifyBtn.textContent;
        verifyBtn.textContent = "Đang xác thực...";
        verifyBtn.disabled = true;

        try {
            // Bước 2: Verify OTP
            const verifyResponse = await fetch(OTP_CHANGE_PASSWORD_URL + 'verify-change-password', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ otp: otp })
            });

            const verifyResult = await verifyResponse.json();

            if (!verifyResponse.ok) {
                throw new Error(verifyResult.message || 'OTP không đúng');
            }

            console.log("✅ [OTP] Verify success, changing password...");

            // Bước 3: Đổi mật khẩu
            const changeResponse = await fetch(API_CHANGE_PASSWORD_URL, {
                method: "POST",
                headers: {
                    "Authorization": `Bearer ${token}`,
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    current_password: pendingPasswordChange.currentPassword,
                    new_password: pendingPasswordChange.newPassword,
                }),
            });

            if (!changeResponse.ok) {
                if (changeResponse.status === 401) {
                    const result = await changeResponse.json().catch(() => ({}));
                    if (result.message && result.message.includes('mật khẩu cũ')) {
                        throw new Error('Mật khẩu cũ không đúng');
                    }
                    localStorage.removeItem("jwt_token");
                    alert("Phiên đăng nhập hết hạn!");
                    window.location.href = wpAccountData.loginUrl;
                    return;
                }
                const err = await changeResponse.json().catch(() => ({}));
                throw new Error(err.message || `Lỗi ${changeResponse.status}`);
            }

            const result = await changeResponse.json();
            console.log("📦 [ChangePassword] Response:", result);

            // Success
            clearInterval(otpTimer);
            alert("✅ Đổi mật khẩu thành công!");

            // Đóng modal
            if (otpModal) {
                otpModal.classList.remove("show");
            }

            // Clear form
            currentPasswordInput.value = '';
            newPasswordInput.value = '';
            confirmPasswordInput.value = '';
            pendingPasswordChange = null;

            // Clear OTP
            otpInputs.forEach(input => input.value = "");

            // Hide strength indicator
            if (strengthIndicator) {
                strengthIndicator.classList.remove('show');
            }

            // Reset password visibility
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
            alert(err.message || "Xác thực thất bại");
            
            // Clear OTP inputs
            otpInputs.forEach(input => input.value = "");
            if (otpInputs[0]) otpInputs[0].focus();
        } finally {
            verifyBtn.textContent = originalText;
            verifyBtn.disabled = false;
        }
    }

    // ========== EVENT LISTENERS ==========

    // Toggle password buttons
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

    // OTP Modal - Close handlers
    const closeModalBtn = document.getElementById("close-modal-change-password");
    const cancelOtpBtn = document.getElementById("cancel-otp-change-password");
    const modalOverlay = document.querySelector(".modal-overlay-change-password");

    [closeModalBtn, cancelOtpBtn, modalOverlay].forEach(el => {
        if (el) {
            el.addEventListener("click", () => {
                clearInterval(otpTimer);
                if (otpModal) {
                    otpModal.classList.remove("show");
                }
                otpInputs.forEach(input => input.value = "");
                pendingPasswordChange = null;
            });
        }
    });

    // OTP Inputs - Auto focus & navigation
    otpInputs.forEach((input, i) => {
        input.addEventListener("input", (e) => {
            input.value = input.value.replace(/[^0-9]/g, '');
            if (input.value.length === 1 && i < 5) {
                otpInputs[i + 1].focus();
            }
        });
        
        input.addEventListener("keydown", (e) => {
            if (e.key === "Backspace" && !input.value && i > 0) {
                otpInputs[i - 1].focus();
            }
        });

        input.addEventListener("paste", (e) => {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0, 6);
            
            pastedData.split('').forEach((char, index) => {
                if (otpInputs[index]) {
                    otpInputs[index].value = char;
                }
            });
            
            const lastFilledIndex = Math.min(pastedData.length, otpInputs.length) - 1;
            otpInputs[lastFilledIndex]?.focus();
        });
    });

    // Verify OTP button
    const verifyOtpBtn = document.getElementById("verify-otp-change-password");
    if (verifyOtpBtn) {
        verifyOtpBtn.addEventListener("click", verifyOtpAndChangePassword);
    }

    // Initial resend button
    const initialResendBtn = document.getElementById("resend-otp-change-password");
    if (initialResendBtn) {
        initialResendBtn.addEventListener("click", handleResendOtp);
    }
}

// Export global
window.initChangePasswordPage = initChangePasswordPage;
const API_URL = wpAccountData.restUrl + 'my-api/v1/user/profile/';
const OTP_API_URL = wpAccountData.restUrl + 'my-api/v1/otp/';

function initProfilePage() {
  const token = localStorage.getItem("jwt_token");
  if (!token) {
    alert("Vui lòng đăng nhập!");
    window.location.href = wpAccountData.loginUrl;
    return;
  }

  // Check required elements
  const required = ["fullname", "email", "phone", "save-profile-btn", "edit-email-btn", "country-select", "country-dropdown"];
  if (!required.every(id => document.getElementById(id))) {
    console.error("Thiếu phần tử HTML bắt buộc");
    return;
  }

  // Elements
  const fullnameInput = document.getElementById("fullname");
  const emailInput = document.getElementById("email");
  const phoneInput = document.getElementById("phone");
  const saveBtn = document.getElementById("save-profile-btn");
  const editEmailBtn = document.getElementById("edit-email-btn");
  const countrySelect = document.getElementById("country-select");
  const countryDropdown = document.getElementById("country-dropdown");
  const countryItems = document.querySelectorAll(".country-item");
  const otpInputs = document.querySelectorAll(".otp-input");
  let currentCountryCode = "+84";
  let isEmailEditing = false;
  
  // ========== Email & OTP variables ==========
  let originalEmail = "";
  let otpTimer = null;
  let remainingTime = 300; // 5 phút

  // Country data
  const COUNTRY_DATA = {
    "+84": { flag: "vn", name: "Việt Nam", prefix: "90" },
    "+1":  { flag: "us", name: "Hoa Kỳ", prefix: "201" },
    "+44": { flag: "gb", name: "Anh", prefix: "71" },
    "+86": { flag: "cn", name: "Trung Quốc", prefix: "13" },
    "+81": { flag: "jp", name: "Nhật Bản", prefix: "90" },
    "+82": { flag: "kr", name: "Hàn Quốc", prefix: "10" },
    "+65": { flag: "sg", name: "Singapore", prefix: "8" },
    "+66": { flag: "th", name: "Thái Lan", prefix: "8" },
  };

  // Update country select UI
  function updateCountrySelect(code) {
    const data = COUNTRY_DATA[code];
    if (!data) return;

    const flagImg = countrySelect.querySelector(".country-flag");
    const codeSpan = countrySelect.querySelector(".country-code");

    if (flagImg && codeSpan) {
      flagImg.src = `https://flagcdn.com/w40/${data.flag}.png`;
      flagImg.alt = data.name;
      codeSpan.textContent = code;
    }

    countryItems.forEach(i => i.classList.remove("active"));
    const activeItem = document.querySelector(`.country-item[data-code="${code}"]`);
    if (activeItem) activeItem.classList.add("active");
  }

  // === FETCH USER PROFILE ===
  async function fetchUserProfile() {
    saveBtn.textContent = "Đang tải...";
    saveBtn.disabled = true;

    try {
      const response = await fetch(API_URL, {
        method: "GET",
        headers: {
          "Authorization": `Bearer ${token}`,
          "Content-Type": "application/json",
        },
      });

      if (!response.ok) {
        if (response.status === 401) {
          localStorage.removeItem("jwt_token");
          alert("Phiên đăng nhập hết hạn!");
          window.location.href = wpAccountData.loginUrl;
          return;
        }
        const err = await response.json().catch(() => ({}));
        throw new Error(err.message || `Lỗi ${response.status}`);
      }

      const data = await response.json();
      renderUserProfile(data);

    } catch (error) {
      console.error("Fetch error:", error);
      alert("Không thể tải thông tin. Vui lòng thử lại.");
    } finally {
      saveBtn.textContent = "Lưu";
      saveBtn.disabled = false;
    }
  }

  // === RENDER USER PROFILE ===
  function renderUserProfile(data) {
    fullnameInput.value = data.display_name || "";
    emailInput.value = data.email || "";
    
    // ========== save email current ==========
    originalEmail = data.email || "";

    // Parse phone number
    if (data.phone) {
      const phoneStr = data.phone.toString().trim();
      let detectedCode = "+84";
      let phoneNumber = phoneStr;

      for (const code of Object.keys(COUNTRY_DATA)) {
        const cleanCode = code.replace('+', '');
        
        if (phoneStr.startsWith(cleanCode)) {
          detectedCode = code;
          phoneNumber = phoneStr.substring(cleanCode.length).trim();
          break;
        }
        
        if (phoneStr.startsWith(code)) {
          detectedCode = code;
          phoneNumber = phoneStr.substring(code.length).trim();
          break;
        }
      }
      currentCountryCode = detectedCode;
      updateCountrySelect(detectedCode);
      phoneInput.value = phoneNumber;

    } else {
      console.log("[Profile] No phone, using default +84");
      currentCountryCode = "+84";
      updateCountrySelect("+84");
      phoneInput.value = "";
    }
  }

  // ========== SAVE PROFILE ==========
  saveBtn.addEventListener("click", async () => {
    if (isEmailEditing) {
      alert("Vui lòng xác thực email trước khi lưu!");
      return;
    }

    const fullname = fullnameInput.value.trim();
    const email = emailInput.value.trim();
    const phoneNumber = phoneInput.value.trim().replace(/\D/g, "");
    const fullPhone = phoneNumber ? currentCountryCode + phoneNumber : "";

    if (!fullname) {
      alert("Vui lòng nhập họ tên!");
      return;
    }

    // ========== save email if change ==========
    const payload = {
      display_name: fullname,
      phone: fullPhone,
    };

    if (email !== originalEmail) {
      if (!email) {
        alert("Email không được để trống!");
        return;
      }
      payload.email = email;
      console.log("[Profile] Email changed, will update:", email);
    } else {
      console.log("[Profile] Email unchanged, skipping email update");
    }

    console.log("💾 [Profile] Saving data:", payload);

    saveBtn.textContent = "Đang lưu...";
    saveBtn.disabled = true;

    try {
      const response = await fetch(API_URL, {
        method: "POST",
        headers: {
          "Authorization": `Bearer ${token}`,
          "Content-Type": "application/json",
        },
        body: JSON.stringify(payload),
      });

      const result = await response.json();
      console.log("📦 [Profile] Save response:", result);

      if (!response.ok) {
        throw new Error(result.message || "Cập nhật thất bại");
      }

      alert("✅ Cập nhật hồ sơ thành công!");
      
      // Cập nhật originalEmail sau khi lưu thành công
      originalEmail = email;
      
      // Re-render với data mới
      renderUserProfile({ 
        display_name: fullname, 
        email: email,
        phone: fullPhone 
      });

    } catch (err) {
      console.error("❌ [Profile] Save error:", err);
      alert(err.message || "Lỗi kết nối server");
    } finally {
      saveBtn.textContent = "Lưu";
      saveBtn.disabled = false;
    }
  });

  // === SELECT COUNTRY ===
  countrySelect.addEventListener("click", (e) => {
    e.stopPropagation();
    countryDropdown.classList.toggle("show");
  });

  countryItems.forEach(item => {
    item.addEventListener("click", () => {
      const code = item.dataset.code;
      const data = COUNTRY_DATA[code];
      if (!data) return;

      console.log("🌍 [Profile] Country changed:", code);
      currentCountryCode = code;
      updateCountrySelect(code);
      countryDropdown.classList.remove("show");
    });
  });

  // Search country
  document.getElementById("country-search")?.addEventListener("input", (e) => {
    const query = e.target.value.toLowerCase();
    countryItems.forEach(item => {
      const name = item.querySelector(".country-name").textContent.toLowerCase();
      const code = item.dataset.code;
      item.style.display = (name.includes(query) || code.includes(query)) ? "flex" : "none";
    });
  });

  // Close dropdown when click outside
  document.addEventListener("click", (e) => {
    if (!e.target.closest(".phone-input")) {
      countryDropdown.classList.remove("show");
    }
  });

  // ========================================
  // ==== EMAIL + OTP VERIFICATION (ĐỘNG) ===
  // ========================================

  // send otp
  async function sendEmailOTP(email) {
    try {
      const response = await fetch(OTP_API_URL + 'send-email', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          email: email,
          purpose: 'verify_email_change'
        })
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

  // Hàm đếm ngược thời gian OTP
  function startOtpTimer() {
    const resendText = document.querySelector(".resend-text");
    if (!resendText) return;
    
    clearInterval(otpTimer);
    
    otpTimer = setInterval(() => {
      remainingTime--;
      
      const minutes = Math.floor(remainingTime / 60);
      const seconds = remainingTime % 60;
      resendText.innerHTML = `Mã hết hạn sau <strong>${minutes}:${seconds.toString().padStart(2, '0')}</strong>. <a href="#" id="resend-otp-link" style="display:none;">Gửi lại</a>`;
      
      if (remainingTime <= 0) {
        clearInterval(otpTimer);
        resendText.innerHTML = 'Mã đã hết hạn. <a href="#" id="resend-otp-link">Gửi lại</a>';
        // Re-attach event listener cho nút gửi lại
        const resendLink = document.getElementById("resend-otp-link");
        if (resendLink) {
          resendLink.addEventListener("click", handleResendOtp);
        }
      }
    }, 1000);
  }

  // Xử lý Resend OTP
  async function handleResendOtp(e) {
    e.preventDefault();
    const currentEmail = emailInput.value.trim();
    
    if (!currentEmail) {
      alert("Email không hợp lệ!");
      return;
    }

    const resendLink = e.target;
    resendLink.textContent = "Đang gửi...";
    resendLink.style.pointerEvents = "none";

    try {
      const result = await sendEmailOTP(currentEmail);
      
      // Reset timer
      remainingTime = result.expires_in || 300;
      startOtpTimer();
      
      // DEBUG: Hiển thị OTP trong console (chỉ để test)
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

  // Click Edit Email → Gửi OTP
  editEmailBtn.addEventListener("click", async () => {
    const currentEmail = emailInput.value.trim();
    
    if (!currentEmail) {
      alert("Email không hợp lệ!");
      return;
    }

    // Disable nút để tránh spam
    editEmailBtn.disabled = true;
    const originalHTML = editEmailBtn.innerHTML;
    editEmailBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    try {
      // Gọi API gửi OTP
      const result = await sendEmailOTP(currentEmail);

      // Hiển thị modal
      document.getElementById("current-email").textContent = currentEmail;
      document.getElementById("email-otp-modal").classList.add("show");
      
      // Bắt đầu đếm ngược
      remainingTime = result.expires_in || 300;
      startOtpTimer();

      // DEBUG: Hiển thị OTP trong console (chỉ để test)
      if (result.debug_otp) {
        console.log('🔐 DEBUG OTP:', result.debug_otp);
        console.log('⚠️ Lưu ý: Xóa debug_otp khi lên production!');
      }

      alert('✅ ' + result.message);

    } catch (error) {
      alert(error.message || 'Không thể gửi mã OTP');
    } finally {
      editEmailBtn.disabled = false;
      editEmailBtn.innerHTML = originalHTML;
    }
  });

  // Verify OTP
  document.getElementById("verify-otp").addEventListener("click", async () => {
    const otp = Array.from(otpInputs).map(i => i.value).join("");
    
    if (otp.length !== 6) {
      alert("Vui lòng nhập đủ 6 số!");
      return;
    }

    const verifyBtn = document.getElementById("verify-otp");
    const originalText = verifyBtn.textContent;
    verifyBtn.textContent = "Đang xác thực...";
    verifyBtn.disabled = true;

    try {
      const response = await fetch(OTP_API_URL + 'verify-email', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          email: emailInput.value.trim(),
          otp: otp
        })
      });

      const result = await response.json();

      if (!response.ok) {
        throw new Error(result.message || 'OTP không đúng');
      }

      // Xác thực thành công
      clearInterval(otpTimer);
      console.log("✅ [OTP] Verify success:", result);
      alert("✅ " + result.message);
      
      // Unlock email input để user có thể chỉnh sửa
      emailInput.disabled = false;
      emailInput.classList.remove("disabled");
      emailInput.focus();
      
      // Đóng modal
      document.getElementById("email-otp-modal").classList.remove("show");
      isEmailEditing = false;
      
      // Clear OTP inputs
      otpInputs.forEach(input => input.value = "");

    } catch (error) {
      console.error('❌ [OTP] Verify error:', error);
      alert(error.message || 'Xác thực thất bại');
      
      // Clear OTP inputs khi sai
      otpInputs.forEach(input => input.value = "");
      otpInputs[0].focus();
    } finally {
      verifyBtn.textContent = originalText;
      verifyBtn.disabled = false;
    }
  });

  // Đóng modal
  document.querySelectorAll("#close-modal, #cancel-otp, .modal-overlay").forEach(el => {
    el.addEventListener("click", () => {
      clearInterval(otpTimer);
      document.getElementById("email-otp-modal").classList.remove("show");
      emailInput.disabled = true;
      emailInput.classList.add("disabled");
      isEmailEditing = false;
      // Reset về email gốc nếu user hủy
      emailInput.value = originalEmail;
      // Clear OTP inputs
      otpInputs.forEach(input => input.value = "");
    });
  });

  // Auto focus và navigation cho OTP inputs
  otpInputs.forEach((input, i) => {
    // Chỉ cho phép nhập số
    input.addEventListener("input", (e) => {
      input.value = input.value.replace(/[^0-9]/g, '');
      if (input.value.length === 1 && i < 5) {
        otpInputs[i + 1].focus();
      }
    });
    
    // Xử lý phím Backspace
    input.addEventListener("keydown", (e) => {
      if (e.key === "Backspace" && !input.value && i > 0) {
        otpInputs[i - 1].focus();
      }
    });

    // Xử lý paste OTP (nếu user copy/paste cả chuỗi 6 số)
    input.addEventListener("paste", (e) => {
      e.preventDefault();
      const pastedData = e.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0, 6);
      
      pastedData.split('').forEach((char, index) => {
        if (otpInputs[index]) {
          otpInputs[index].value = char;
        }
      });
      
      // Focus vào ô cuối hoặc ô đầu tiên chưa điền
      const lastFilledIndex = Math.min(pastedData.length, otpInputs.length) - 1;
      otpInputs[lastFilledIndex]?.focus();
    });
  });

  // Event listener cho nút resend OTP ban đầu
  const initialResendBtn = document.getElementById("resend-otp");
  if (initialResendBtn) {
    initialResendBtn.addEventListener("click", handleResendOtp);
  }

  // === INITIALIZE ===
  updateCountrySelect("+84");

  if (!phoneInput.value.trim()) {
    phoneInput.value = COUNTRY_DATA["+84"].prefix;
  }

  fetchUserProfile();
}

// Export global
window.initProfilePage = initProfilePage;

// Auto run
if (document.getElementById("fullname")) {
  initProfilePage();
}
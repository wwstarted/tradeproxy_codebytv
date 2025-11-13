// ===== profile.js =====

function initProfilePage() {
  const token = localStorage.getItem("jwt_token");
  if (!token) {
    alert("Vui lòng đăng nhập!");
    window.location.href = "/login";
    return;
  }

  // Kiểm tra
  const required = ["fullname", "email", "phone", "save-profile-btn", "edit-email-btn", "country-select", "country-dropdown", "country-search"];
  if (!required.every(id => document.getElementById(id))) return;

  // Elements
  const fullnameInput = document.getElementById("fullname");
  const emailInput = document.getElementById("email");
  const phoneInput = document.getElementById("phone");
  const saveBtn = document.getElementById("save-profile-btn");
  const editEmailBtn = document.getElementById("edit-email-btn");
  const countrySelect = document.getElementById("country-select");
  const countryDropdown = document.getElementById("country-dropdown");
  const countrySearch = document.getElementById("country-search");
  const countryItems = document.querySelectorAll(".country-item");
  const otpInputs = document.querySelectorAll(".otp-input");

  let currentCountryCode = "+84";
  let isEmailEditing = false;

  // === QUỐC GIA + ĐỘ DÀI SỐ ĐIỆN THOẠI ===
  const COUNTRY_RULES = {
    "+84": { name: "Việt Nam", prefix: "90", min: 9, max: 10 },
    "+1":  { name: "Hoa Kỳ",   prefix: "201", min: 10, max: 10 },
    "+44": { name: "Anh",      prefix: "71",  min: 10, max: 10 },
    "+86": { name: "Trung Quốc", prefix: "13", min: 11, max: 11 },
    "+81": { name: "Nhật Bản", prefix: "90",  min: 10, max: 11 },
    "+82": { name: "Hàn Quốc", prefix: "10",  min: 10, max: 11 },
    "+65": { name: "Singapore", prefix: "8",  min: 8,  max: 8 },
    "+66": { name: "Thái Lan", prefix: "8",   min: 9,  max: 10 },
  };

  // === FETCH & RENDER ===
  function fetchUserProfile() {
    saveBtn.textContent = "Đang tải...";
    saveBtn.disabled = true;

    fetch("http://localhost/tradeproxy/wordpress-6.8.3-vi/wordpress/wp-json/my-api/v1/user/profile/", {
      method: "GET",
      headers: {
        "Authorization": "Bearer " + token,
        "Content-Type": "application/json",
      },
    })
      .then(res => {
        if (!res.ok) {
          if (res.status === 401) {
            localStorage.removeItem("jwt_token");
            alert("Phiên hết hạn!");
            window.location.href = "/login";
          }
          throw new Error("Lỗi server");
        }
        return res.json();
      })
      .then(data => renderUserProfile(data))
      .catch(() => alert("Không thể tải thông tin."))
      .finally(() => {
        saveBtn.textContent = "Lưu";
        saveBtn.disabled = false;
      });
  }

  function renderUserProfile(data) {
    fullnameInput.value = data.display_name || "";
    emailInput.value = data.email || "";

    if (data.phone && data.phone.startsWith("+")) {
      const match = data.phone.match(/^\+(\d+)/);
      if (match) {
        const code = "+" + match[1];
        const rule = COUNTRY_RULES[code];
        const item = document.querySelector(`.country-item[data-code="${code}"]`);
        if (item && rule) {
          currentCountryCode = code;
          updateCountrySelect(item);
          phoneInput.value = data.phone.replace(code, "").replace(/^\d*/, rule.prefix);
        } else {
          phoneInput.value = data.phone.replace(/^\+\d+/, "");
        }
      }
    } else {
      phoneInput.value = data.phone || "";
    }
  }

  // === CẬP NHẬT HỒ SƠ ===
  saveBtn.addEventListener("click", async function () {
    if (isEmailEditing) {
      alert("Vui lòng xác thực email trước!");
      return;
    }

    const fullname = fullnameInput.value.trim();
    const email = emailInput.value.trim();
    const rawPhone = phoneInput.value.replace(/\D/g, "");
    const rule = COUNTRY_RULES[currentCountryCode];

    // Validate
    if (!fullname || !email) {
      alert("Vui lòng nhập họ tên và email!");
      return;
    }

    if (!rule) {
      alert("Quốc gia không được hỗ trợ!");
      return;
    }

    if (rawPhone.length < rule.min || rawPhone.length > rule.max) {
      alert(`Số điện thoại ${rule.name} phải có ${rule.min} đến ${rule.max} số!`);
      return;
    }

    const fullPhone = currentCountryCode + rawPhone;

    saveBtn.textContent = "Đang lưu...";
    saveBtn.disabled = true;

    try {
      const response = await fetch("http://localhost/tradeproxy/wordpress-6.8.3-vi/wordpress/wp-json/my-api/v1/user/profile/", {
        method: "POST",
        headers: {
          "Authorization": "Bearer " + token,
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          display_name: fullname,
          email: email,
          phone: fullPhone,
        }),
      });

      const result = await response.json();

      if (!response.ok) {
        throw new Error(result.message || "Cập nhật thất bại");
      }

      alert("Cập nhật hồ sơ thành công!");
      fetchUserProfile();

    } catch (err) {
      console.error(err);
      alert(err.message || "Lỗi kết nối server");
    } finally {
      saveBtn.textContent = "Lưu";
      saveBtn.disabled = false;
    }
  });

  // === CHỌN QUỐC GIA + TỰ ĐỘNG ĐIỀN SỐ ĐẦU ===
  countrySelect.addEventListener("click", (e) => {
    e.stopPropagation();
    countryDropdown.classList.toggle("show");
  });

  countryItems.forEach(item => {
    item.addEventListener("click", function () {
      const code = this.dataset.code;
      const rule = COUNTRY_RULES[code];
      if (!rule) return;

      currentCountryCode = code;
      updateCountrySelect(this);

      // Tự động điền số đầu + placeholder
      const clean = phoneInput.value.replace(/\D/g, "").replace(/^\d+/, "");
      phoneInput.value = rule.prefix + clean.replace(new RegExp(`^${rule.prefix}`), "");
      phoneInput.placeholder = rule.prefix + "x".repeat(rule.min);

      countryDropdown.classList.remove("show");
    });
  });

  function updateCountrySelect(item) {
    const flag = item.querySelector("img").src;
    const code = item.querySelector(".country-code").textContent;

    countrySelect.innerHTML = `
      <img src="${flag}" alt="" class="country-flag">
      <span class="country-code">${code}</span>
      <i class="fas fa-chevron-down"></i>
    `;

    countryItems.forEach(i => i.classList.remove("active"));
    item.classList.add("active");
  }

  // Tìm kiếm quốc gia
  countrySearch.addEventListener("input", (e) => {
    const query = e.target.value.toLowerCase();
    countryItems.forEach(item => {
      const name = item.querySelector(".country-name").textContent.toLowerCase();
      const code = item.dataset.code;
      item.style.display = (name.includes(query) || code.includes(query)) ? "flex" : "none";
    });
  });

  // Đóng dropdown
  document.addEventListener("click", () => countryDropdown.classList.remove("show"));

  // === CHỈNH SỬA EMAIL + OTP ===
  editEmailBtn.addEventListener("click", () => {
    emailInput.disabled = false;
    emailInput.classList.remove("disabled");
    emailInput.focus();
    isEmailEditing = true;
    document.getElementById("current-email").textContent = emailInput.value;
    document.getElementById("email-otp-modal").classList.add("show");
  });

  document.querySelectorAll("#close-modal, #cancel-otp, .modal-overlay").forEach(el => {
    el.addEventListener("click", () => {
      document.getElementById("email-otp-modal").classList.remove("show");
      emailInput.disabled = true;
      emailInput.classList.add("disabled");
      isEmailEditing = false;
    });
  });

  otpInputs.forEach((input, i) => {
    input.addEventListener("input", () => {
      if (input.value.length === 1 && i < 5) otpInputs[i + 1].focus();
    });
    input.addEventListener("keydown", (e) => {
      if (e.key === "Backspace" && !input.value && i > 0) otpInputs[i - 1].focus();
    });
  });

  document.getElementById("verify-otp").addEventListener("click", () => {
    const otp = Array.from(otpInputs).map(i => i.value).join("");
    if (otp.length !== 6) {
      alert("Vui lòng nhập đủ 6 số!");
      return;
    }
    alert("Xác thực email thành công!");
    document.getElementById("email-otp-modal").classList.remove("show");
    isEmailEditing = false;
  });


  document.getElementById("resend-otp").addEventListener("click", (e) => {
    e.preventDefault();
    alert("Mã OTP mới đã được gửi!");
  });

  // === KHỞI CHẠY ===
  fetchUserProfile();
}

// === XUẤT RA TOÀN CỤC ===
window.initProfilePage = initProfilePage;

// === TỰ CHẠY NẾU LOAD TRỰC TIẾP ===
if (document.getElementById("fullname")) {
  initProfilePage();
}
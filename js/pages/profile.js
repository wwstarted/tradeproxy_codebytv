// ===== profile.js =====
const API_URL = wpAccountData.restUrl + 'my-api/v1/user/profile/';
function initProfilePage() {
  const token = localStorage.getItem("jwt_token");
  if (!token) {
    alert("Vui lòng đăng nhập!");
    window.location.href = "/login";
    return;
  }

  // check 
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

  // country format phone number
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

  // === UI country phone number ===
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
      const response = await fetch("http://localhost/tradeproxy/wordpress-6.8.3-vi/wordpress/wp-json/my-api/v1/user/profile/", {
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
          window.location.href = "/login";
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

  // === HIỂN THỊ DỮ LIỆU ===
  function renderUserProfile(data) {
    fullnameInput.value = data.display_name || "";
    emailInput.value = data.email || "";

    // SỬA: DÙNG !== undefined ĐỂ NHẬN CẢ "" VÀ null
    if (data.phone !== undefined && data.phone !== null) {
        let phone = (data.phone || "").toString().trim();
        let selectedCode = currentCountryCode;

        // Tìm mã quốc gia
        for (const code of Object.keys(COUNTRY_DATA)) {
            if (phone.startsWith(code.replace('+', ''))) {
                selectedCode = '+' + code.replace('+', '');
                phone = phone.substring(code.length).trim();
                break;
            }
        }
      currentCountryCode = selectedCode;
      updateCountrySelect(selectedCode);
      phoneInput.value = phone || "";
    } else {
      currentCountryCode="+84";
      updateCountrySelect("+84");
      phoneInput.value = "";
    }
  }

  // === LƯU HỒ SƠ ===
  saveBtn.addEventListener("click", async () => {
    if (isEmailEditing) {
      alert("Vui lòng xác thực email trước khi lưu!");
      return;
    }

    const fullname = fullnameInput.value.trim();
    const email = emailInput.value.trim();
    const phoneNumber = phoneInput.value.replace(/\D/g, "");
    const fullPhone = phoneNumber ? currentCountryCode + phoneNumber : "";

    if (!fullname || !email) {
      alert("Vui lòng nhập họ tên và email!");
      return;
    }

    saveBtn.textContent = "Đang lưu...";
    saveBtn.disabled = true;

    try {
      const response = await fetch("http://localhost/tradeproxy/wordpress-6.8.3-vi/wordpress/wp-json/my-api/v1/user/profile/", {
        method: "POST",
        headers: {
          "Authorization": `Bearer ${token}`,
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
      renderUserProfile({ display_name: fullname, email: email, phone: fullPhone });

    } catch (err) {
      console.error(err);
      alert(err.message || "Lỗi kết nối server");
    } finally {
      saveBtn.textContent = "Lưu";
      saveBtn.disabled = false;
    }
  });

  // === CHỌN QUỐC GIA  ===
  countrySelect.addEventListener("click", (e) => {
    e.stopPropagation();
    countryDropdown.classList.toggle("show");
  });

  countryItems.forEach(item => {
    item.addEventListener("click", () => {
      const code = item.dataset.code;
      const data = COUNTRY_DATA[code];
      if (!data) return;

      const currentValue = phoneInput.value.trim();
      const oldPrefix = COUNTRY_DATA[currentCountryCode]?.prefix || "";

      // Case 1: Input trống
      if (!currentValue) {
        phoneInput.value = data.prefix;
      }
      // Case 2: Chỉ có prefix cũ (90, 90 )
      else if (currentValue === oldPrefix || currentValue === oldPrefix + " ") {
        phoneInput.value = data.prefix;
      }
      // Case 3: Có số thật
      else {
        // Không thay đổi
      }

      currentCountryCode = code;
      updateCountrySelect(code);
      countryDropdown.classList.remove("show");
    });
  });

  // find country
  document.getElementById("country-search")?.addEventListener("input", (e) => {
    const query = e.target.value.toLowerCase();
    countryItems.forEach(item => {
      const name = item.querySelector(".country-name").textContent.toLowerCase();
      const code = item.dataset.code;
      item.style.display = (name.includes(query) || code.includes(query)) ? "flex" : "none";
    });
  });

  // close when click out
  document.addEventListener("click", (e) => {
    if (!e.target.closest(".phone-input")) {
      countryDropdown.classList.remove("show");
    }
  });

  // ==== EMAIL + OTP ===
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

  // === KHỞI TẠO ===
  updateCountrySelect("+84");

  // Set prefix mặc định nếu input trống
  if (!phoneInput.value.trim()) {
    phoneInput.value = COUNTRY_DATA["+84"].prefix; // "90"
  }

  fetchUserProfile();
}

// Export global
window.initProfilePage = initProfilePage;

// Tự chạy nếu load trực tiếp
if (document.getElementById("fullname")) {
  initProfilePage();
}
// ============================================
// STATE MANAGEMENT - Quản lý trạng thái đăng nhập
// ============================================

// Mặc định: chưa đăng nhập (false)
// Khi user đăng nhập thành công, set = true
let isLoggedIn = false; // THAY ĐỔI THÀNH true KHI USER ĐĂNG NHẬP

// Thông tin user (lấy từ API sau khi đăng nhập)
const currentUser = {
  name: "luoinghe",
  email: "user@email.com",
  avatar: "L", // Chữ cái đầu của tên
};

// ============================================
// MOBILE MENU TOGGLE FUNCTIONALITY
// ============================================

// Get DOM elements
const hamburger = document.getElementById("hamburger");
const mobileMenu = document.getElementById("mobileMenu");
const closeMenu = document.getElementById("closeMenu");
const overlay = document.getElementById("overlay");

// Function to open mobile menu
function openMenu() {
  if (mobileMenu && overlay) {
    mobileMenu.classList.add("active");
    overlay.classList.add("active");
    document.body.style.overflow = "hidden";
  }
}

// Function to close mobile menu
function closeMenuFunc() {
  if (mobileMenu && overlay) {
    mobileMenu.classList.remove("active");
    overlay.classList.remove("active");
    document.body.style.overflow = "";
  }
}

// Event Listeners
if (hamburger) {
  hamburger.addEventListener("click", openMenu);
}

if (closeMenu) {
  closeMenu.addEventListener("click", closeMenuFunc);
}

if (overlay) {
  overlay.addEventListener("click", closeMenuFunc);
}

// Close menu when clicking on menu items or buttons
// KHÔNG đóng menu nếu click vào item có submenu
document.querySelectorAll(".mobile-menu-item, .mobile-btn").forEach((item) => {
  item.addEventListener("click", function (e) {
    // Nếu item có class "has-submenu" thì KHÔNG đóng menu
    if (this.classList.contains("has-submenu")) {
      return; // Dừng lại, không đóng menu
    }
    closeMenuFunc();
  });
});

// Close menu on ESC key press
document.addEventListener("keydown", function (event) {
  if (event.key === "Escape") {
    closeMenuFunc();
  }
});

// ============================================
// USER DROPDOWN FUNCTIONALITY
// ============================================

// Get user dropdown elements
const userDropdown = document.getElementById("userDropdown");
const userTrigger = document.getElementById("userTrigger");
const authButtons = document.getElementById("authButtons");
const logoutBtn = document.getElementById("logoutBtn");
const mobileLogoutBtn = document.getElementById("mobileLogoutBtn");
const mobileUserInfo = document.getElementById("mobileUserInfo");
const mobileLoggedInMenu = document.getElementById("mobileLoggedInMenu");
const mobileAuthButtons = document.getElementById("mobileAuthButtons");

// Toggle user dropdown on desktop
if (userTrigger) {
  userTrigger.addEventListener("click", (e) => {
    e.stopPropagation();
    userDropdown.classList.toggle("active");
  });
}

// Close dropdown when clicking outside
document.addEventListener("click", (e) => {
  if (userDropdown && !userDropdown.contains(e.target)) {
    userDropdown.classList.remove("active");
  }
});

// Update UI based on login state
function updateAuthUI() {
  if (isLoggedIn) {
    // Desktop: Hiển thị user dropdown, ẩn auth buttons
    if (userDropdown) userDropdown.style.display = "block";
    if (authButtons) authButtons.style.display = "none";

    // Mobile: Hiển thị user info và logged-in menu, ẩn auth buttons
    if (mobileUserInfo) {
      mobileUserInfo.style.display = "flex";
      // Cập nhật thông tin user
      const avatar = mobileUserInfo.querySelector(".user-avatar");
      const name = mobileUserInfo.querySelector("h3");
      const email = mobileUserInfo.querySelector("p");
      if (avatar) avatar.textContent = currentUser.avatar;
      if (name) name.textContent = currentUser.name;
      if (email) email.textContent = currentUser.email;
    }
    if (mobileLoggedInMenu) mobileLoggedInMenu.style.display = "block";
    if (mobileAuthButtons) mobileAuthButtons.style.display = "none";

    // Cập nhật avatar và tên trên desktop
    if (userDropdown) {
      const desktopAvatar = userDropdown.querySelector(".user-avatar");
      const desktopName = userDropdown.querySelector(".user-name");
      if (desktopAvatar) desktopAvatar.textContent = currentUser.avatar;
      if (desktopName) desktopName.textContent = currentUser.name;
    }
  } else {
    // Desktop: Ẩn user dropdown, hiển thị auth buttons
    if (userDropdown) userDropdown.style.display = "none";
    if (authButtons) authButtons.style.display = "flex";

    // Mobile: Ẩn user info và logged-in menu, hiển thị auth buttons
    if (mobileUserInfo) mobileUserInfo.style.display = "none";
    if (mobileLoggedInMenu) mobileLoggedInMenu.style.display = "none";
    if (mobileAuthButtons) mobileAuthButtons.style.display = "flex";
  }
}

// Logout functionality
function handleLogout() {
  isLoggedIn = false;
  updateAuthUI();
  closeMenuFunc();
  if (userDropdown) userDropdown.classList.remove("active");

  // Redirect hoặc xử lý logout
  console.log("Đã đăng xuất!");
  // window.location.href = '/logout'; // Uncomment để redirect
}

// Logout event listeners
if (logoutBtn) {
  logoutBtn.addEventListener("click", (e) => {
    e.preventDefault();
    handleLogout();
  });
}

if (mobileLogoutBtn) {
  mobileLogoutBtn.addEventListener("click", (e) => {
    e.preventDefault();
    handleLogout();
  });
}

// ============================================
// STICKY HEADER - Fixed position khi scroll
// ============================================
let lastScrollTop = 0;
const headerNavigate = document.querySelector(".header-navigate");
const headerTop = document.querySelector(".header-top");

// Tính toán offset để tránh content bị nhảy
function updateHeaderOffset() {
  if (headerNavigate && headerNavigate.classList.contains("scrolled")) {
    const headerHeight = headerNavigate.offsetHeight;
    const mainContent = document.querySelector(".main-content");
    if (mainContent) {
      mainContent.style.paddingTop = headerHeight + "px";
    }
  } else {
    const mainContent = document.querySelector(".main-content");
    if (mainContent) {
      mainContent.style.paddingTop = "0px";
    }
  }
}

window.addEventListener("scroll", function () {
  if (headerNavigate) {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const headerTopHeight = headerTop ? headerTop.offsetHeight : 0;

    if (scrollTop > headerTopHeight) {
      headerNavigate.classList.add("scrolled");
      updateHeaderOffset();
    } else {
      headerNavigate.classList.remove("scrolled");
      updateHeaderOffset();
    }

    lastScrollTop = scrollTop;
  }
});

// ============================================
// INITIALIZE - Khởi tạo khi load trang
// ============================================

// Gọi updateAuthUI khi trang load
document.addEventListener("DOMContentLoaded", function () {
  updateAuthUI();
  console.log("Header navigation loaded successfully!");
  console.log("Login status:", isLoggedIn);
});

// ============================================
// PUBLIC FUNCTIONS - Các hàm có thể gọi từ ngoài
// ============================================

// Hàm để set user đã đăng nhập (gọi sau khi login thành công)
function setUserLoggedIn(userData) {
  isLoggedIn = true;
  if (userData) {
    currentUser.name = userData.name || currentUser.name;
    currentUser.email = userData.email || currentUser.email;
    currentUser.avatar = userData.name
      ? userData.name.charAt(0).toUpperCase()
      : currentUser.avatar;
  }
  updateAuthUI();
  console.log("User logged in:", currentUser);
}

// Hàm để set user đã đăng xuất
function setUserLoggedOut() {
  handleLogout();
}

// Export functions để có thể sử dụng ở file khác
window.setUserLoggedIn = setUserLoggedIn;
window.setUserLoggedOut = setUserLoggedOut;

// ============================================
// ACCORDION MENU FUNCTIONALITY
// ============================================

// Hàm setup accordion cho một cặp trigger-submenu
function setupAccordion(triggerId, submenuId) {
  const trigger = document.getElementById(triggerId);
  const submenu = document.getElementById(submenuId);

  if (trigger && submenu) {
    trigger.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();

      // Kiểm tra xem submenu này đang mở hay không
      const isActive = submenu.classList.contains("active");

      // Đóng tất cả submenu khác
      document.querySelectorAll(".submenu").forEach((sub) => {
        sub.classList.remove("active");
      });
      document.querySelectorAll(".has-submenu").forEach((item) => {
        item.classList.remove("active");
      });

      // Nếu submenu đang đóng thì mở, nếu đang mở thì giữ đóng
      if (!isActive) {
        trigger.classList.add("active");
        submenu.classList.add("active");
      }
    });
  }
}

// Setup tất cả các accordion khi DOM đã load
document.addEventListener("DOMContentLoaded", function () {
  // Setup các accordion menu
  setupAccordion("accountTrigger", "accountSubmenu");
  setupAccordion("walletTrigger", "walletSubmenu");
  setupAccordion("memberTrigger", "memberSubmenu");

  // Active state cho submenu items
  document.querySelectorAll(".submenu-item").forEach((item) => {
    item.addEventListener("click", function (e) {
      e.preventDefault();

      // Remove active từ tất cả items trong cùng submenu
      const parentSubmenu = this.closest(".submenu");
      if (parentSubmenu) {
        parentSubmenu.querySelectorAll(".submenu-item").forEach((i) => {
          i.classList.remove("active");
        });
      }

      // Add active cho item được click
      this.classList.add("active");
    });
  });

  console.log("Accordion menu initialized!");
});
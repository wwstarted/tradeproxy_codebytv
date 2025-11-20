// ============================================
// STATE MANAGEMENT - Quản lý trạng thái đăng nhập
// ============================================
let isLoggedIn = false; // mặc định chưa đăng nhập
const currentUser = {
  name: "",
  email: "",
  avatar: ""
};

// ============================================
// UTILITY - parse display_name -> tên hiển thị + avatar
// ============================================
function parseUserName(displayName) {
  if (!displayName) return { name: "", avatar: "" };
  const words = displayName.trim().split(/\s+/);
  const lastTwoWords = words.slice(-2).join(" "); // 2 từ cuối
  const lastWord = words[words.length - 1];
  const avatar = lastWord.charAt(0).toUpperCase();
  return { name: lastTwoWords, avatar };
}

// ============================================
// CHECK LOGIN STATUS TỪ localStorage
// ============================================
function checkLoginStatus() {
  const token = localStorage.getItem("jwt_token");
  isLoggedIn = !!token;
  return isLoggedIn;
}

// ============================================
// FETCH USER DATA TỪ API
// ============================================
async function fetchUserData() {
  const token = localStorage.getItem("jwt_token");
  if (!token) return;

  try {
    const res = await fetch(`${window.wpAccountData.baseUrl}/wp-json/my-api/v1/user/profile`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
    if (!res.ok) throw new Error("Không lấy được user data");
    const data = await res.json();
    const { name, avatar } = parseUserName(data.display_name);
    currentUser.name = name;
    currentUser.email = data.email || "";
    currentUser.avatar = avatar;
  } catch (err) {
    console.error("Lỗi fetch user:", err);
  }
 
}

// ============================================
// UPDATE UI BASED ON LOGIN STATE
// ============================================
function updateAuthUI() {
  checkLoginStatus();

  const userDropdown = document.getElementById("userDropdown");
  const authButtons = document.getElementById("authButtons");
  const mobileUserInfo = document.getElementById("mobileUserInfo");
  const mobileLoggedInMenu = document.getElementById("mobileLoggedInMenu");
  const mobileAuthButtons = document.getElementById("mobileAuthButtons");

  if (isLoggedIn) {
    // Desktop
    if (userDropdown) userDropdown.style.display = "block";
    if (authButtons) authButtons.style.display = "none";
    if (userDropdown) {
      const desktopAvatar = userDropdown.querySelector(".user-avatar");
      const desktopName = userDropdown.querySelector(".user-name");
      if (desktopAvatar) desktopAvatar.textContent = currentUser.avatar;
      if (desktopName) desktopName.textContent = currentUser.name;
    }

    // Mobile
    if (mobileUserInfo) {
      mobileUserInfo.style.display = "flex";
      const avatarEl = mobileUserInfo.querySelector(".user-avatar");
      const nameEl = mobileUserInfo.querySelector("h3");
      const emailEl = mobileUserInfo.querySelector("p");
      if (avatarEl) avatarEl.textContent = currentUser.avatar;
      if (nameEl) nameEl.textContent = currentUser.name;
      if (emailEl) emailEl.textContent = currentUser.email;
    }
    if (mobileLoggedInMenu) mobileLoggedInMenu.style.display = "block";
    if (mobileAuthButtons) mobileAuthButtons.style.display = "none";
  } else {
    // Desktop
    if (userDropdown) userDropdown.style.display = "none";
    if (authButtons) authButtons.style.display = "flex";
    // Mobile
    if (mobileUserInfo) mobileUserInfo.style.display = "none";
    if (mobileLoggedInMenu) mobileLoggedInMenu.style.display = "none";
    if (mobileAuthButtons) mobileAuthButtons.style.display = "flex";
  }
}

// ============================================
// LOGOUT FUNCTION
// ============================================
function handleLogout() {
  isLoggedIn = false;
  localStorage.removeItem("jwt_token");
  updateAuthUI();
  closeMenuFunc();
  document.getElementById("userDropdown")?.classList.remove("active");
  window.location.href = `${window.wpAccountData.baseUrl}/login`;
  console.log("Đã đăng xuất!");
}

// Event listeners logout
document.getElementById("logoutBtn")?.addEventListener("click", e => {
  e.preventDefault();
  handleLogout();
});
document.getElementById("mobileLogoutBtn")?.addEventListener("click", e => {
  e.preventDefault();
  handleLogout();
});

// ============================================
// MOBILE MENU TOGGLE
// ============================================
const hamburger = document.getElementById("hamburger");
const mobileMenu = document.getElementById("mobileMenu");
const closeMenu = document.getElementById("closeMenu");
const overlay = document.getElementById("overlay");

function openMenu() {
  if (mobileMenu && overlay) {
    mobileMenu.classList.add("active");
    overlay.classList.add("active");
    document.body.style.overflow = "hidden";
  }
}
function closeMenuFunc() {
  if (mobileMenu && overlay) {
    mobileMenu.classList.remove("active");
    overlay.classList.remove("active");
    document.body.style.overflow = "";
  }
}
hamburger?.addEventListener("click", openMenu);
closeMenu?.addEventListener("click", closeMenuFunc);
overlay?.addEventListener("click", closeMenuFunc);

document.querySelectorAll(".mobile-menu-item, .mobile-btn").forEach(item => {
  item.addEventListener("click", function (e) {
    if (this.classList.contains("has-submenu")) return;
    closeMenuFunc();
  });
});
document.addEventListener("keydown", e => { if (e.key === "Escape") closeMenuFunc(); });

// ============================================
// USER DROPDOWN DESKTOP
// ============================================
const userTrigger = document.getElementById("userTrigger");
const userDropdown = document.getElementById("userDropdown");
userTrigger?.addEventListener("click", e => {
  e.stopPropagation();
  userDropdown?.classList.toggle("active");
});
document.addEventListener("click", e => {
  if (userDropdown && !userDropdown.contains(e.target)) {
    userDropdown.classList.remove("active");
  }
});

// ============================================
// ACCORDION MENU
// ============================================
function setupAccordion(triggerId, submenuId) {
  const trigger = document.getElementById(triggerId);
  const submenu = document.getElementById(submenuId);
  if (!trigger || !submenu) return;

  trigger.addEventListener("click", e => {
    e.preventDefault();
    e.stopPropagation();
    const isActive = submenu.classList.contains("active");

    document.querySelectorAll(".submenu").forEach(sub => sub.classList.remove("active"));
    document.querySelectorAll(".has-submenu").forEach(item => item.classList.remove("active"));

    if (!isActive) {
      trigger.classList.add("active");
      submenu.classList.add("active");
    }
  });
}

// ============================================
// STICKY HEADER
// ============================================
const headerNavigate = document.querySelector(".header-navigate");
const headerTop = document.querySelector(".header-top");
function updateHeaderOffset() {
  const mainContent = document.querySelector(".main-content");
  if (!mainContent) return;
  if (headerNavigate?.classList.contains("scrolled")) {
    mainContent.style.paddingTop = `${headerNavigate.offsetHeight}px`;
  } else {
    mainContent.style.paddingTop = "0px";
  }
}
window.addEventListener("scroll", () => {
  const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
  const headerTopHeight = headerTop?.offsetHeight || 0;
  if (scrollTop > headerTopHeight) {
    headerNavigate?.classList.add("scrolled");
  } else {
    headerNavigate?.classList.remove("scrolled");
  }
  updateHeaderOffset();
});

// ============================================
// PUBLIC FUNCTIONS
// ============================================
function setUserLoggedIn(userData) {
  isLoggedIn = true;
  if (userData?.display_name) {
    const { name, avatar } = parseUserName(userData.display_name);
    currentUser.name = name;
    currentUser.avatar = avatar;
    currentUser.email = userData.email || "";
  }
  updateAuthUI();
}
function setUserLoggedOut() {
  handleLogout();
}
window.setUserLoggedIn = setUserLoggedIn;
window.setUserLoggedOut = setUserLoggedOut;

// ============================================
// INITIALIZE
// ============================================
document.addEventListener("DOMContentLoaded", async () => {
  if (checkLoginStatus()) await fetchUserData();
  updateAuthUI();

  // Setup accordions
  setupAccordion("accountTrigger", "accountSubmenu");
  setupAccordion("walletTrigger", "walletSubmenu");
  setupAccordion("memberTrigger", "memberSubmenu");

  // Active state submenu items
  document.querySelectorAll(".submenu-item").forEach(item => {
    item.addEventListener("click", e => {
      e.preventDefault();
      const parentSubmenu = item.closest(".submenu");
      if (parentSubmenu) {
        parentSubmenu.querySelectorAll(".submenu-item").forEach(i => i.classList.remove("active"));
      }
      item.classList.add("active");
    });
  });

  console.log("Header initialized, login status:", isLoggedIn);
});

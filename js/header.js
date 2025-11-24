// ============================= STATE MANAGEMENT ====================================

// Lấy trạng thái và dữ liệu ban đầu từ PHP (đã được định nghĩa trong header.php)
const initialState = window.wpAccountData.initialState; 

let isLoggedIn = initialState.isLoggedIn; // Sử dụng trạng thái từ PHP
const currentUser = {
    name: initialState.userName,
    email: initialState.userEmail,
    avatar: initialState.userAvatar
};

// ============================================
// UTILITY - ten hien thi 
// (Giữ nguyên, dùng để xử lý dữ liệu sau này)
// ============================================
function parseUserName(displayName) {
    if (!displayName) return { name: "", avatar: "" };
    const words = displayName.trim().split(/\s+/);
    const lastTwoWords = words.slice(-2).join(" "); 
    const lastWord = words[words.length - 1];
    // Thay đổi này để đảm bảo lấy chữ cái đầu chính xác (trong trường hợp PHP chưa cung cấp)
    const avatar = lastWord.charAt(0).toUpperCase(); 
    return { name: lastTwoWords, avatar };
}

// ============================================
// CHECK LOGIN STATUS TỪ localStorage
// (Giữ lại để JS có thể kiểm tra token sau các thao tác)
// ============================================
function checkLoginStatus() {
    const token = localStorage.getItem("jwt_token");
    isLoggedIn = !!token;
    return isLoggedIn;
}

// ============================================
// FETCH USER DATA TỪ API (Không dùng await trong INITIALIZE)
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
        
        if (!res.ok) {
            // Nếu token có trong localStorage nhưng API báo lỗi (token hết hạn), cần logout
            console.error("Lỗi fetch user: Token có thể đã hết hạn.");
            throw new Error("Token expired or invalid.");
        }
        
        const data = await res.json();
        const { name, avatar } = parseUserName(data.display_name);
        currentUser.name = name;
        currentUser.email = data.email || "";
        currentUser.avatar = avatar;
        isLoggedIn = true; // Cập nhật chắc chắn đã đăng nhập
    } catch (err) {
        console.error("Lỗi fetch user:", err);
        // Xoá token nếu fetch thất bại (đảm bảo đồng bộ với trạng thái đăng xuất)
        handleLogout(false); // Gọi handleLogout nhưng không redirect
    }
}

// ============================================
// UPDATE UI BASED ON LOGIN STATE
// (Cập nhật logic để tìm và cập nhật các phần tử avatar/name/email cho chuẩn xác)
// ============================================
function updateAuthUI() {
    // Không cần gọi checkLoginStatus() ở đây nữa vì nó đã được cập nhật bởi handleLogout/setUserLoggedIn
    
    const userDropdown = document.getElementById("userDropdown");
    const authButtons = document.getElementById("authButtons");
    const mobileUserInfo = document.getElementById("mobileUserInfo");
    const mobileLoggedInMenu = document.getElementById("mobileLoggedInMenu");
    const mobileAuthButtons = document.getElementById("mobileAuthButtons");

    if (isLoggedIn) {
        // Desktop
        if (userDropdown) {
            userDropdown.style.display = "block";
            const desktopAvatar = userDropdown.querySelector(".user-avatar-text"); // Đã đổi tên class trong PHP
            const desktopName = userDropdown.querySelector(".user-name");
            if (desktopAvatar) desktopAvatar.textContent = currentUser.avatar;
            if (desktopName) desktopName.textContent = currentUser.name;
        }
        if (authButtons) authButtons.style.display = "none";

        // Mobile
        if (mobileUserInfo) {
            mobileUserInfo.style.display = "flex";
            const avatarEl = mobileUserInfo.querySelector(".user-avatar-text-mobile"); // Đã đổi tên class trong PHP
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


// ================================================== LOGOUT FUNCTION =======================================

// function handleLogout(shouldRedirect = true) {
//     isLoggedIn = false;
//     localStorage.removeItem("jwt_token");
//     // Xoá cookie (do PHP đã set http-only nên JS không xoá được. Cần dùng cách này)
//     document.cookie = "jwt_token=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC;"; 
    
//     // Reset currentUser data
//     currentUser.name = initialState.userName;
//     currentUser.email = initialState.userEmail;
//     currentUser.avatar = initialState.userAvatar;
    
//     updateAuthUI();
//     closeMenuFunc();
//     document.getElementById("userDropdown")?.classList.remove("active");
    
//     if (shouldRedirect) {
//         window.location.href = `${window.wpAccountData.baseUrl}/login`;
//     }
//     console.log("Đã đăng xuất!");
// }
// ... (các hàm khác)

// ================================================== LOGOUT FUNCTION =======================================

async function handleLogout(shouldRedirect = true) {
    // 1. Gửi yêu cầu đến Server để xóa JWT Cookie an toàn (vì cookie có thể là HttpOnly)
    try {
        await fetch(`${window.wpAccountData.baseUrl}/wp-json/my-api/v1/logout`, {
            method: 'POST',
            // Có thể thêm nonce bảo mật nếu cần
            // headers: { 'X-WP-Nonce': window.wpAccountData.nonce } 
        });
        console.log("Server đã xác nhận đăng xuất và xóa Cookie.");
    } catch (e) {
        console.error("Lỗi khi gửi yêu cầu đăng xuất đến server:", e);
        // Tiếp tục quá trình đăng xuất phía client dù server lỗi để UI được cập nhật
    }

    // 2. Xóa trạng thái client-side
    isLoggedIn = false;
    localStorage.removeItem("jwt_token");
    // Dòng này cố gắng xóa cookie phía client (hữu ích nếu cookie KHÔNG phải HttpOnly), 
    // nhưng Server API call ở trên mới là cách đáng tin cậy nhất.
    document.cookie = "jwt_token=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC;"; 
    
    // Reset currentUser data
    currentUser.name = initialState.userName;
    currentUser.email = initialState.userEmail;
    currentUser.avatar = initialState.userAvatar;
    
    // 3. Cập nhật UI ngay lập tức
    updateAuthUI();
    closeMenuFunc();
    document.getElementById("userDropdown")?.classList.remove("active");
    
    // 4. Chuyển hướng
    if (shouldRedirect) {
        // Đợi một chút (nếu cần) hoặc chuyển hướng ngay lập tức
        window.location.href = `${window.wpAccountData.baseUrl}/login`;
    }
    console.log("Đã đăng xuất!");
}

// ... (phần Event Listeners và INITIALIZE)

// Event listeners logout (Giữ nguyên)
document.getElementById("logoutBtn")?.addEventListener("click", e => {
    e.preventDefault();
    handleLogout(true);
});
document.getElementById("mobileLogoutBtn")?.addEventListener("click", e => {
    e.preventDefault();
    handleLogout(true);
});

// ============================================
// CÁC HÀM KHÁC (MOBILE MENU, DROPDOWN, STICKY HEADER, ACCORDION)
// (Giữ nguyên)
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

// USER DROPDOWN DESKTOP
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

// ACCORDION MENU
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

// STICKY HEADER
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
document.addEventListener("DOMContentLoaded", () => {
    // 1. UI đã được PHP render đúng trạng thái ngay từ đầu, 
    //    nên ta không cần gọi updateAuthUI lần đầu ngay lập tức
    
    // 2. Nếu đã đăng nhập (theo PHP), bắt đầu fetch dữ liệu API NGAY LẬP TỨC 
    //    để đảm bảo dữ liệu là mới nhất, NHƯNG KHÔNG DÙNG 'await'
    if (isLoggedIn) {
        fetchUserData().then(() => {
            // Sau khi fetch xong, nếu dữ liệu có thay đổi hoặc muốn đảm bảo đồng bộ
            updateAuthUI(); 
        }).catch(err => {
             // Logic xử lý lỗi token (đã có trong fetchUserData)
        });
    }

    // Setup accordions
    setupAccordion("accountTrigger", "accountSubmenu");
    setupAccordion("walletTrigger", "walletSubmenu");
    setupAccordion("memberTrigger", "memberSubmenu");

    // Active state submenu items
    document.querySelectorAll(".submenu-item").forEach(item => {
        item.addEventListener("click", function (e) {
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
// ============================== AUTH MODULE ======================================

const AuthModule = {
    // Lấy token từ localStorage
    getToken() {
        return localStorage.getItem("jwt_token");
    },

    // Lưu token
    setToken(token) {
        localStorage.setItem("jwt_token", token);
    },

    // Xóa token (logout)
    removeToken() {
        localStorage.removeItem("jwt_token");
    },

    // Kiểm tra xem user đã login chưa
    isAuthenticated() {
        const token = this.getToken();
        return !!token; // Trả về true nếu có token
    },

    // Verify token với server (optional - nếu cần kiểm tra token còn hợp lệ không)
    async verifyToken() {
        const token = this.getToken();
        if (!token) return false;

        try {
            const response = await fetch(wpAccountData.apiUrl + "/validate-token", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": `Bearer ${token}`
                }
            });

            return response.ok;
        } catch (error) {
            console.error("Token verification failed:", error);
            return false;
        }
    },

    // Redirect về trang login
    redirectToLogin(message = "Vui lòng đăng nhập để tiếp tục!") {
        // save url 
        const currentUrl = window.location.href;
        localStorage.setItem("redirect_after_login", currentUrl);

        if (message) {
            alert(message);
        }

        window.location.href = wpAccountData.loginUrl || "/login";
    },

    // Redirect 
    redirectAfterLogin(defaultUrl = "/") {
        const redirectUrl = localStorage.getItem("redirect_after_login") || defaultUrl;
        localStorage.removeItem("redirect_after_login");
        window.location.href = redirectUrl;
    },

    // ======== HÀM CHÍNH: Require Auth cho trang ========
    requireAuth(options = {}) {
        const {
            verifyWithServer = false,
                redirectUrl = null,
                onAuthFail = null,
                message = "Vui lòng đăng nhập để truy cập trang này!"
        } = options;

        return new Promise(async(resolve, reject) => {
            // check localStorage jwt_token
            if (!this.isAuthenticated()) {
                console.log("[Auth] Không tìm thấy token");

                if (onAuthFail) {
                    onAuthFail();
                } else {
                    this.redirectToLogin(message);
                }

                reject(new Error("Not authenticated"));
                return;
            }

            console.log("✅ [Auth] Token found");

            // verify
            if (verifyWithServer) {
                console.log("🔍 [Auth] Verifying token with server...");

                const isValid = await this.verifyToken();

                if (!isValid) {
                    console.log("❌ [Auth] Token không hợp lệ");
                    this.removeToken();

                    if (onAuthFail) {
                        onAuthFail();
                    } else {
                        this.redirectToLogin("Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại!");
                    }

                    reject(new Error("Invalid token"));
                    return;
                }

                console.log("✅ [Auth] Token verified");
            }

            resolve(true);
        });
    },

    // Logout user
    logout(redirectUrl = "/") {
        this.removeToken();
        localStorage.removeItem("user_data");
        alert("Đã đăng xuất thành công!");
        window.location.href = redirectUrl;
    }
};

// Export để dùng ở file khác
window.AuthModule = AuthModule;
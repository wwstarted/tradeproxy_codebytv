
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tài khoản của tôi</title>
    <?php wp_head(); ?>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/account.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
</head>

<body>
    <div class="account-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-section">
                <div class="section-header">
                    <i class="fas fa-user-circle"></i>
                    <span>Tài khoản của tôi</span>
                </div>
                <nav class="sidebar-menu">
                    <a href="#profile" class="menu-item active" data-page="profile">
                        Hồ sơ
                    </a>
                    <a href="#change-password" class="menu-item" data-page="change-password">
                        Đổi mật khẩu
                    </a>
                </nav>
            </div>

            <div class="sidebar-section">
                <a href="#wallet" class="menu-item" data-page="wallet">
                    <i class="fas fa-wallet"></i>
                    <span>Ví tiền</span>
                </a>
            </div>

            <div class="sidebar-section">
                <a href="#membership" class="menu-item" data-page="membership">
                    <i class="fas fa-crown"></i>
                    <span>Hạng thành viên</span>
                </a>
            </div>

            <div class="sidebar-section">
                <a href="#deposit-history" class="menu-item" data-page="deposit-history">
                    <i class="fas fa-history"></i>
                    <span>Lịch sử nạp tiền</span>
                </a>
            </div>

            <div class="sidebar-section">
                <a href="#purchase-history" class="menu-item" data-page="purchase-history">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Lịch sử mua hàng</span>
                </a>
            </div>
        </aside>

        <!-- Content Area -->
        <main class="content-area">
            <div id="page-content">
                <!-- fetch data here -->
                <div class="loading">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Đang tải...</p>
                </div>
            </div>
        </main>
    </div>
    

    <?php wp_footer(); ?>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title></title>
    <?php wp_head(); ?>
    <!-- <link rel="stylesheet" href="style.css" /> -->
    <!-- <link rel="stylesheet" href="./css/home.css" /> -->
    <!-- Link CDN font awesome -->
    <!-- <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" /> -->
    <!-- Import file dịch class tailwind -> css -->
    <!-- <link rel="stylesheet" href="./src/output.css" /> -->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
     <script>
        window.wpAccountData = {
            baseUrl: '<?php echo home_url(); ?>',
            accountUrl: '<?php echo home_url('/account'); ?>',
            ajaxUrl: '<?php echo admin_url('admin-ajax.php'); ?>',
            restUrl: '<?php echo get_rest_url(); ?>', 
            nonce: '<?php echo wp_create_nonce('account_nonce'); ?>'
        };
    </script>
</head>

<body class="bg-[#F9FDFF]">
    <div class="wrapper">
        <!-- Header -->
        <header class="header">
            <div class="header-top w-full bg-[#007BF3]">
                <div class="max-w-[90%] mx-auto">
                    <div class="pt-2 pb-2 flex justify-between items-center gap-2">
                        <i class="fa-solid fa-bell text-white text-xl"></i>
                        <div class="flex-1 overflow-hidden">
                            <span class="marquee text-white whitespace-nowrap">
                                Từ ngày 1/5 ABC Proxy & Novada Proxy tiếp tục bổ sung thêm
                                20.000 địa chỉ IP thuộc khu vực các bang của Hoa Kỳ!
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-navigate w-full bg-white">
                <div class="m-auto max-w-[90%]">
                    <div class="flex justify-between items-center pt-2 pb-2">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="w-30">
                            <img src="<?php echo get_template_directory_uri(); ?>/images/trade-proxy.svg" alt="" />
                        </a>

                        <!-- Desktop Navigation -->
                        <nav class="desktop-nav">
                            <ul class="flex gap-2 items-center">
                                <li>
                                    <a
                                        class="capitalize pt-2 pb-2 pl-4 pr-4 rounded-md hover:bg-[#f7f8f9]"
                                        href="<?php echo esc_url(home_url('/proxy')); ?>">mua proxy</a>
                                </li>
                                <li>
                                    <a
                                        class="capitalize pt-2 pb-2 pl-4 pr-4 rounded-md hover:bg-[#f7f8f9]"
                                        href="#">hướng dẫn</a>
                                </li>
                                <li>
                                    <a
                                        class="capitalize pt-2 pb-2 pl-4 pr-4 rounded-md hover:bg-[#f7f8f9]"
                                        href="<?php echo esc_url(home_url('/blog')); ?>">blog</a>
                                </li>
                                <li>
                                    <a
                                        class="capitalize pt-2 pb-2 pl-4 pr-4 rounded-md hover:bg-[#f7f8f9]"
                                        href="<?php echo esc_url(home_url('/contact')); ?>">liên hệ</a>
                                </li>
                                <li>
                                    <a class="capitalize pt-2 pb-2 pl-4 pr-4" href="<?php echo esc_url(home_url('/cart')); ?>">
                                        <i class="fa-solid fa-cart-shopping"></i>
                                    </a>
                                </li>
                                <li class="flex items-center relative group">
                                    <div class="flex items-center cursor-pointer">
                                        <img
                                            src="<?php echo get_template_directory_uri(); ?>/images/lang-VN.png"
                                            alt="Cờ Việt Nam"
                                            class="w-5" />
                                        <i
                                            class="fa-solid fa-chevron-down ml-3 text-[10px] transform transition-transform duration-200 group-hover:rotate-180"></i>
                                    </div>
                                    <nav
                                        class="absolute top-full w-40 bg-white shadow-lg rounded-lg p-1 z-10 hidden group-hover:block">
                                        <button
                                            class="flex items-center w-full p-2 rounded-md bg-blue-500 text-white font-semibold mb-1 hover:bg-blue-600 transition-colors duration-150">
                                            <img
                                                src="<?php echo get_template_directory_uri(); ?>/images/lang-VN.png"
                                                alt="Cờ Việt Nam"
                                                class="w-5 h-5 mr-3" />
                                            Việt Nam
                                        </button>
                                        <button
                                            class="flex items-center w-full p-2 rounded-md text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                                            <img
                                                src="<?php echo get_template_directory_uri(); ?>/images/lang-EN.png"
                                                alt="Cờ Anh"
                                                class="w-5 h-5 mr-3" />
                                            English
                                        </button>
                                    </nav>
                                </li>

                                <!-- ===== USER DROPDOWN (khi đã login) ===== -->
                                <li
                                    class="user-dropdown"
                                    id="userDropdown"
                                    style="display: none">
                                    <div class="user-trigger" id="userTrigger">
                                        <div class="user-avatar">
                                            <img
                                                src="https://images.unsplash.com/photo-1728577740843-5f29c7586afe?q=80&w=880&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                                                alt="" />
                                        </div>
                                        <span class="user-name">luoinghe</span>
                                        <i class="fa-solid fa-chevron-down dropdown-arrow"></i>
                                    </div>
                                    <div class="user-menu">
                                        <a href="<?php echo esc_url(home_url('/account')) ?>" class="user-menu-item">
                                            <i class="fa-regular fa-user"></i>
                                            <span>Tài khoản của tôi</span>
                                        </a>
                                        <a href="<?php echo esc_url(home_url('/purchase-history')) ?>" class="user-menu-item">
                                            <i class="fa-regular fa-clock"></i>
                                            <span>Lịch sử mua hàng</span>
                                        </a>
                                        <a href="#" class="user-menu-item" id="logoutBtn">
                                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                            <span>Đăng xuất</span>
                                        </a>
                                    </div>
                                </li>

                                <!-- ===== AUTH BUTTONS (khi chưa login) ===== -->
                                <li class="auth-buttons" id="authButtons">
                                    <a
                                        class="capitalize pt-2 pb-2 pl-4 pr-4 font-medium text-[#298ADE]"
                                        href="<?php echo esc_url(home_url('/login')); ?>">đăng nhập</a>
                                    <a
                                        class="capitalize pt-2 pb-2 pl-4 pr-4 font-medium bg-[#298ADE] text-white rounded-md"
                                        href="<?php echo esc_url(home_url('/register')); ?>">đăng ký</a>
                                </li>
                            </ul>
                        </nav>

                        <!-- Mobile Navigation -->
                        <div class="mobile-nav">
                            <div class="mobile-icon">
                                <i class="fa-solid fa-cart-shopping"></i>
                            </div>
                            <div class="mobile-icon" id="hamburger">
                                <i class="fa-solid fa-bars"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Mobile Menu Sidebar -->
        <div class="mobile-menu" id="mobileMenu">
            <div class="mobile-menu-header">
                <div class="logo">
                    <img
                        src="<?php echo get_template_directory_uri(); ?>/images/trade-proxy.svg"
                        alt="Trade Proxy"
                        style="height: 28px" />
                </div>
                <div class="close-menu" id="closeMenu">
                    <i class="fa-solid fa-xmark"></i>
                </div>
            </div>

            <!-- ===== MOBILE USER INFO (khi đã login) ===== -->
            <div class="mobile-user-info" id="mobileUserInfo">
                <div class="user-avatar">
                    <img
                        src="https://plus.unsplash.com/premium_photo-1671656349218-5218444643d8?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                        alt="" />
                </div>
                <div class="mobile-user-details">
                    <h3>luoinghe</h3>
                    <p>user@email.com</p>
                </div>
            </div>

            <div class="mobile-menu-items">
                <!-- Menu mặc định (luôn hiển thị) -->
                <a href="<?php echo esc_url(home_url('/proxies')); ?>" class="mobile-menu-item">
                    <i class="fa-solid fa-shopping-bag"></i>
                    <span>Mua proxy</span>
                </a>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="mobile-menu-item">
                    <i class="fa-solid fa-book"></i>
                    <span>Hướng dẫn</span>
                </a>
                <a href="<?php echo esc_url(home_url('/blog')); ?>" class="mobile-menu-item">
                    <i class="fa-solid fa-newspaper"></i>
                    <span>Blog</span>
                </a>
                <a href="<?php echo esc_url(home_url('/contact')); ?>" class="mobile-menu-item">
                    <i class="fa-solid fa-envelope"></i>
                    <span>Liên hệ</span>
                </a>

                <!-- ===== MENU KHI ĐÃ LOGIN (có accordion) ===== -->
                <div id="mobileLoggedInMenu" style="display: none">
                    <!-- Tài khoản của tôi - CÓ SUBMENU -->
                    <div class="mobile-menu-item has-submenu" id="accountTrigger">
                        <i class="fa-regular fa-user"></i>
                        <span>Tài khoản của tôi</span>
                    </div>
                    <div class="submenu" id="accountSubmenu">
                        <a href="<?php echo esc_url(home_url('/account')); ?>" class="submenu-item">
                            <i class="fa-regular fa-user"></i>
                            <span>Tài khoản của tôi</span>
                        </a>
                        <a href="<?php echo esc_url(home_url('/cart')); ?>" class="submenu-item">
                            <i class="fa-regular fa-id-card"></i>
                            <span>Hồ sơ</span>
                        </a>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="submenu-item">
                            <i class="fa-solid fa-key"></i>
                            <span>Đổi mật khẩu</span>
                        </a>
                    </div>

                    <!-- Ví tiền - CÓ SUBMENU -->
                    <div class="mobile-menu-item has-submenu" id="walletTrigger">
                        <i class="fa-regular fa-folder-open"></i>
                        <span>Ví tiền</span>
                    </div>
                    <div class="submenu" id="walletSubmenu">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="submenu-item">
                            <i class="fa-regular fa-folder-open"></i>
                            <span>Ví tiền</span>
                        </a>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="submenu-item">
                            <i class="fa-solid fa-credit-card"></i>
                            <span>Lịch sử nạp tiền</span>
                        </a>
                    </div>

                    <!-- Hạng thành viên - KHÔNG CÓ SUBMENU -->
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="mobile-menu-item">
                        <i class="fa-solid fa-gem"></i>
                        <span>Hạng thành viên</span>
                    </a>

                    <!-- Lịch sử mua hàng - KHÔNG CÓ SUBMENU -->
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="mobile-menu-item">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>Lịch sử mua hàng</span>
                    </a>

                    <!-- Đăng xuất -->
                    <a href="#" class="mobile-menu-item logout" id="mobileLogoutBtn">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span>Đăng xuất</span>
                    </a>
                </div>
            </div>

            <!-- Language Selector -->
            <nav class="mobile-lang-dropdown flex gap-2 p-2">
                <button
                    class="flex items-center w-full p-2 rounded-md bg-blue-500 text-white font-semibold hover:bg-blue-600 transition-colors duration-150">
                    <img
                        src="<?php echo get_template_directory_uri(); ?>/images/lang-VN.png"
                        alt="Cờ Việt Nam"
                        class="w-5 h-5 mr-3" />
                    Việt Nam
                </button>
                <button
                    class="flex items-center w-full p-2 rounded-md text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/lang-EN.png" alt="Cờ Anh" class="w-5 h-5 mr-3" />
                    English
                </button>
            </nav>

            <!-- ===== AUTH BUTTONS (khi chưa login) ===== -->
            <div class="mobile-menu-buttons" id="mobileAuthButtons">
                <a href="<?php echo esc_url(home_url('/sign-in')); ?>" class="mobile-btn mobile-btn-login">Đăng nhập</a>
                <a href="<?php echo esc_url(home_url('/register')); ?>" class="mobile-btn mobile-btn-register">Đăng ký</a>
            </div>
        </div>

        <!-- Overlay -->
        <div class="overlay" id="overlay"></div>
<?php
// Thêm hàm để parse tên hiển thị (copy từ JS sang PHP)
function parseUserName_php($displayName)
{
    if (empty($displayName))
        return ['name' => '', 'avatar' => ''];
    $words = explode(' ', trim($displayName));
    $lastTwoWords = implode(' ', array_slice($words, -2));
    $lastWord = end($words);
    $avatar = strtoupper(substr($lastWord, 0, 1));
    return ['name' => $lastTwoWords, 'avatar' => $avatar];
}

$is_logged_in_server = false;
$current_user_data = [
    'name' => '',
    'email' => '',
    'avatar' => '',
    'is_logged_in' => false
];

// Kiểm tra JWT token trong Cookie (phía Server)
if (isset($_COOKIE['jwt_token']) && !empty($_COOKIE['jwt_token'])) {
    $is_logged_in_server = true;
}

if ($is_logged_in_server) {
    // Nếu JWT có trong cookie, thử lấy thông tin user WordPress
    $user = wp_get_current_user();
    if ($user && $user->ID !== 0) {
        $user_display_name = $user->display_name;
        $parsed_name = parseUserName_php($user_display_name);

        $current_user_data['name'] = $parsed_name['name'];
        $current_user_data['email'] = $user->user_email;
        $current_user_data['avatar'] = $parsed_name['avatar'];
        $current_user_data['is_logged_in'] = true;
    } else {
        $is_logged_in_server = false;
        $current_user_data['is_logged_in'] = false;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title></title>
    <?php wp_head(); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <script>
        window.wpAccountData = {
            baseUrl: '<?php echo home_url(); ?>',
            accountUrl: '<?php echo home_url('/account'); ?>',
            ajaxUrl: '<?php echo admin_url('admin-ajax.php'); ?>',
            restUrl: '<?php echo get_rest_url(); ?>',
            nonce: '<?php echo wp_create_nonce('account_nonce'); ?>',
            // Dữ liệu từ Server-Side
            initialState: {
                isLoggedIn: <?php echo $current_user_data['is_logged_in'] ? 'true' : 'false'; ?>,
                userName: '<?php echo esc_js($current_user_data['name']); ?>',
                userEmail: '<?php echo esc_js($current_user_data['email']); ?>',
                userAvatar: '<?php echo esc_js($current_user_data['avatar']); ?>'
            }
        };
    </script>


</head>

<body class="bg-[#F9FDFF]">
    <div class="wrapper">
        <header class="header">
            <div class="header-top w-full bg-[#007BF3]">
                <div class="max-w-[80%] mx-auto">
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
                <div class="m-auto max-w-[80%]">
                    <div class="flex justify-between items-center pt-2 pb-2">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="w-30">
                            <img src="<?php echo get_template_directory_uri(); ?>/images/trade-proxy.svg" alt="" />
                        </a>

                        <nav class="desktop-nav">
                            <ul class="flex gap-2 items-center">
                                <li>
                                    <a class="capitalize pt-2 pb-2 pl-4 pr-4 rounded-md hover:bg-[#f7f8f9]"
                                        href="<?php echo esc_url(home_url('/proxies')); ?>">mua proxy</a>
                                </li>
                                <li>
                                    <a class="capitalize pt-2 pb-2 pl-4 pr-4 rounded-md hover:bg-[#f7f8f9]"
                                        href="#">hướng dẫn</a>
                                </li>
                                <li>
                                    <a class="capitalize pt-2 pb-2 pl-4 pr-4 rounded-md hover:bg-[#f7f8f9]"
                                        href="<?php echo esc_url(home_url('/blog')); ?>">blog</a>
                                </li>
                                <li>
                                    <a class="capitalize pt-2 pb-2 pl-4 pr-4 rounded-md hover:bg-[#f7f8f9]"
                                        href="<?php echo esc_url(home_url('/contact')); ?>">liên hệ</a>
                                </li>
                                <li>
                                    <div class="cart-icon">
                                        <a href="<?php echo wc_get_cart_url(); ?>" class="cart-icon-link">
                                            <i class="fa-solid fa-cart-shopping"></i>
                                            <span class="cart-count" id="cart-count">
                                                <?php echo WC()->cart->get_cart_contents_count(); ?>
                                            </span>
                                        </a>
                                    </div>
                                </li>
                                <li class="flex items-center relative group">
                                    <div class="flex items-center cursor-pointer">
                                        <img src="<?php echo get_template_directory_uri(); ?>/images/lang-VN.png"
                                            alt="Cờ Việt Nam" class="w-5" />
                                        <i
                                            class="fa-solid fa-chevron-down ml-3 text-[10px] transform transition-transform duration-200 group-hover:rotate-180"></i>
                                    </div>
                                    <nav
                                        class="absolute top-full w-40 bg-white shadow-lg rounded-lg p-1 z-10 hidden group-hover:block">
                                        <button
                                            class="flex items-center w-full p-2 rounded-md bg-blue-500 text-white font-semibold mb-1 hover:bg-blue-600 transition-colors duration-150">
                                            <img src="<?php echo get_template_directory_uri(); ?>/images/lang-VN.png"
                                                alt="Cờ Việt Nam" class="w-5 h-5 mr-3" />
                                            Việt Nam
                                        </button>
                                        <button
                                            class="flex items-center w-full p-2 rounded-md text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                                            <img src="<?php echo get_template_directory_uri(); ?>/images/lang-EN.png"
                                                alt="Cờ Anh" class="w-5 h-5 mr-3" />
                                            English
                                        </button>
                                    </nav>
                                </li>

                                <li class="user-dropdown" id="userDropdown"
                                    style="display: <?php echo $current_user_data['is_logged_in'] ? 'block' : 'none'; ?>">
                                    <div class="user-trigger" id="userTrigger">
                                        <div class="user-avatar">
                                            <span
                                                class="user-avatar-text"><?php echo esc_html($current_user_data['avatar']); ?></span>
                                        </div>
                                        <span
                                            class="user-name"><?php echo esc_html($current_user_data['name']); ?></span>
                                        <i class="fa-solid fa-chevron-down dropdown-arrow"></i>
                                    </div>
                                    
                                    <div class="user-menu">
                                        <a href="<?php echo esc_url(home_url('/account')) ?>" class="user-menu-item">
                                            <i class="fa-regular fa-user"></i>
                                            <span>Tài khoản của tôi</span>
                                        </a>
                                        <a href="<?php echo esc_url(home_url('/purchase-history')) ?>"
                                            class="user-menu-item">
                                            <i class="fa-regular fa-clock"></i>
                                            <span>Lịch sử mua hàng</span>
                                        </a>
                                        <a href="#" class="user-menu-item" id="logoutBtn">
                                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                            <span>Đăng xuất</span>
                                        </a>
                                    </div>
                                    </li>

                                <li class="auth-buttons" id="authButtons"
                                    style="display: <?php echo $current_user_data['is_logged_in'] ? 'none' : 'flex'; ?>">
                                    <a class="capitalize pt-2 pb-2 pl-4 pr-4 font-medium text-[#298ADE]"
                                        href="<?php echo esc_url(home_url('/login')); ?>">đăng nhập</a>
                                    <a class="capitalize pt-2 pb-2 pl-4 pr-4 font-medium bg-[#298ADE] text-white rounded-md"
                                        href="<?php echo esc_url(home_url('/register')); ?>">đăng ký</a>
                                </li>
                            </ul>
                        </nav>

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

        <div class="mobile-menu" id="mobileMenu">
            <div class="mobile-menu-header">
                <div class="logo">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/trade-proxy.svg" alt="Trade Proxy"
                        style="height: 28px" />
                </div>
                <div class="close-menu" id="closeMenu">
                    <i class="fa-solid fa-xmark"></i>
                </div>
            </div>

            <div class="mobile-user-info" id="mobileUserInfo"
                style="display: <?php echo $current_user_data['is_logged_in'] ? 'flex' : 'none'; ?>">
                <div class="user-avatar">
                    <span class="user-avatar-text-mobile"><?php echo esc_html($current_user_data['avatar']); ?></span>
                </div>
                <div class="mobile-user-details">
                    <h3><?php echo esc_html($current_user_data['name']); ?></h3>
                    <p><?php echo esc_html($current_user_data['email']); ?></p>
                </div>
            </div>

            <div class="mobile-menu-buttons" id="mobileAuthButtons"
                style="display: <?php echo $current_user_data['is_logged_in'] ? 'none' : 'flex'; ?>">
                <a href="<?php echo esc_url(home_url('/login')); ?>" class="mobile-btn mobile-btn-login">Đăng nhập</a>
                <a href="<?php echo esc_url(home_url('/register')); ?>" class="mobile-btn mobile-btn-register">Đăng
                    ký</a>
            </div>

            <div class="mobile-menu-items">
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

                <div class="mobile-logged-in-menu-items" id="mobileLoggedInMenu" 
                    style="display: <?php echo $current_user_data['is_logged_in'] ? 'block' : 'none'; ?>">
                    <div class="mobile-menu-item has-submenu" id="accountTrigger">
                        <i class="fa-regular fa-user"></i>
                        <span>Tài khoản của tôi</span>
                        <i class="fa-solid fa-chevron-right accordion-arrow"></i>
                    </div>
                    <div class="submenu" id="accountSubmenu">
                        <a href="<?php echo esc_url(home_url('/account')); ?>" class="submenu-item">
                            <i class="fa-regular fa-user"></i>
                            <span>Thông tin cá nhân</span>
                        </a>
                        <a href="<?php echo esc_url(home_url('/profile')); ?>" class="submenu-item">
                            <i class="fa-regular fa-id-card"></i>
                            <span>Hồ sơ</span>
                        </a>
                        <a href="<?php echo esc_url(home_url('/change-password')); ?>" class="submenu-item">
                            <i class="fa-solid fa-key"></i>
                            <span>Đổi mật khẩu</span>
                        </a>
                    </div>

                    <div class="mobile-menu-item has-submenu" id="walletTrigger">
                        <i class="fa-regular fa-folder-open"></i>
                        <span>Ví tiền</span>
                        <i class="fa-solid fa-chevron-right accordion-arrow"></i>
                    </div>
                    <div class="submenu" id="walletSubmenu">
                        <a href="<?php echo esc_url(home_url('/wallet')); ?>" class="submenu-item">
                            <i class="fa-regular fa-folder-open"></i>
                            <span>Xem Ví tiền</span>
                        </a>
                        <a href="<?php echo esc_url(home_url('/deposit-history')); ?>" class="submenu-item">
                            <i class="fa-solid fa-credit-card"></i>
                            <span>Lịch sử nạp tiền</span>
                        </a>
                    </div>

                    <a href="<?php echo esc_url(home_url('/member-rank')); ?>" class="mobile-menu-item">
                        <i class="fa-solid fa-gem"></i>
                        <span>Hạng thành viên</span>
                    </a>

                    <a href="<?php echo esc_url(home_url('/purchase-history')); ?>" class="mobile-menu-item">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>Lịch sử mua hàng</span>
                    </a>

                    <a href="#" class="mobile-menu-item logout" id="mobileLogoutBtn">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span>Đăng xuất</span>
                    </a>
                </div>
            </div>

            <nav class="mobile-lang-dropdown flex gap-2 p-2">
                <button
                    class="flex items-center w-full p-2 rounded-md bg-blue-500 text-white font-semibold hover:bg-blue-600 transition-colors duration-150">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/lang-VN.png" alt="Cờ Việt Nam"
                        class="w-5 h-5 mr-3" />
                    Việt Nam
                </button>
                <button
                    class="flex items-center w-full p-2 rounded-md text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/lang-EN.png" alt="Cờ Anh"
                        class="w-5 h-5 mr-3" />
                    English
                </button>
            </nav>
        </div>

        <div class="overlay" id="overlay"></div>
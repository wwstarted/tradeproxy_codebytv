<?php
// Ẩn header setting defautl của WP
add_filter('show_admin_bar', '__return_false');

// Cho phép theme hỗ trợ ảnh đại diện
add_theme_support('post-thumbnails');

// tạo đường dẫn đến css js và các dẫn link khác
function tradeproxy_theme_enqueue_assets()
{
    // Gọi file CSS chính của theme
    wp_enqueue_style('main-style', get_stylesheet_directory_uri() . '/style.css', array(), filemtime(get_stylesheet_directory() . '/style.css'));

    // Gọi Link CDN Font awesome
    wp_enqueue_style('font-icon', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css', array(), '1.0', 'all');

    // Gọi file CSS được chuyển đổi từ TailwindCSS
    wp_enqueue_style('tailwind-output', get_template_directory_uri() . '/src/output.css', array(), filemtime(get_stylesheet_directory() . '/src/output.css'));

    // Gọi thêm các file CSS con trong thư mục /css/
    wp_enqueue_style('tradeproxy-home', get_template_directory_uri() . '/css/home.css', array(), filemtime(get_stylesheet_directory() . '/css/home.css'));
    wp_enqueue_style('tradeproxy-proxies', get_template_directory_uri() . '/css/product-proxy.css', array(), filemtime(get_stylesheet_directory() . '/css/product-proxy.css'));
    wp_enqueue_style('tradeproxy-detail-proxy', get_template_directory_uri() . '/css/detail-proxy.css', array(), filemtime(get_stylesheet_directory() . '/css/detail-proxy.css'));
    wp_enqueue_style('tradeproxy-contact', get_template_directory_uri() . '/css/contact.css', array(), filemtime(get_stylesheet_directory() . '/css/contact.css'));
    wp_enqueue_style('tradeproxy-cart', get_template_directory_uri() . '/css/cart.css', array(), filemtime(get_stylesheet_directory() . '/css/cart.css'));
    wp_enqueue_style('tradeproxy-payment', get_template_directory_uri() . '/css/payment.css', array(), filemtime(get_stylesheet_directory() . '/css/payment.css'));
    wp_enqueue_style('tradeproxy-provider', get_template_directory_uri() . '/css/provider.css', array(), filemtime(get_stylesheet_directory() . '/css/provider.css'));
    wp_enqueue_style('tradeproxy-detail-provider', get_template_directory_uri() . '/css/detail-provider.css', array(), filemtime(get_stylesheet_directory() . '/css/detail-provider.css'));
    wp_enqueue_style('tradeproxy-blog', get_template_directory_uri() . '/css/blog.css', array(), filemtime(get_stylesheet_directory() . '/css/blog.css'));
    wp_enqueue_style('tradeproxy-single-posts', get_template_directory_uri() . '/css/single-posts.css', array(), filemtime(get_stylesheet_directory() . '/css/single-posts.css'));
    wp_enqueue_style('tradeproxy-sign-in', get_template_directory_uri() . '/css/sign-in.css', array(), filemtime(get_stylesheet_directory() . '/css/sign-in.css'));
    wp_enqueue_style('tradeproxy-register', get_template_directory_uri() . '/css/register.css', array(), filemtime(get_stylesheet_directory() . '/css/register.css'));
    wp_enqueue_style('tradeproxy-forgetpass', get_template_directory_uri() . '/css/forgetpass.css', array(), filemtime(get_stylesheet_directory() . '/css/forgetpass.css'));
    wp_enqueue_style('tradeproxy-user-account', get_template_directory_uri() . '/css/user-account.css', array(), filemtime(get_stylesheet_directory() . '/css/user-account.css'));
    wp_enqueue_style('tradeproxy-profile', get_template_directory_uri() . '/css/profile.css', array(), filemtime(get_stylesheet_directory() . '/css/profile.css'));


    // Gọi file JS trong thư mục /js/
    wp_enqueue_script('tradeproxy-header', get_template_directory_uri() . '/js/header.js', array('jquery'), filemtime(get_template_directory() . '/js/header.js'), true);
    wp_enqueue_script('tradeproxy-home', get_template_directory_uri() . '/js/home.js', array('jquery'), filemtime(get_template_directory() . '/js/home.js'), true);
    wp_enqueue_script('tradeproxy-proxies', get_template_directory_uri() . '/js/product-proxy.js', array('jquery'), filemtime(get_template_directory() . '/js/product-proxy.js'), true);
    wp_enqueue_script('tradeproxy-detail-proxy', get_template_directory_uri() . '/js/detail-proxy.js', array('jquery'), filemtime(get_template_directory() . '/js/detail-proxy.js'), true);
    wp_enqueue_script('tradeproxy-contact', get_template_directory_uri() . '/js/contact.js', array('jquery'), filemtime(get_template_directory() . '/js/contact.js'), true);
    wp_enqueue_script('tradeproxy-cart', get_template_directory_uri() . '/js/cart.js', array('jquery'), filemtime(get_template_directory() . '/js/cart.js'), true);
    wp_enqueue_script('tradeproxy-payment', get_template_directory_uri() . '/js/payment.js', array('jquery'), filemtime(get_template_directory() . '/js/payment.js'), true);
    wp_enqueue_script('tradeproxy-provider', get_template_directory_uri() . '/js/provider.js', array('jquery'), filemtime(get_template_directory() . '/js/provider.js'), true);
    wp_enqueue_script('tradeproxy-detail-provider', get_template_directory_uri() . '/js/detail-provider.js', array('jquery'), filemtime(get_template_directory() . '/js/detail-provider.js'), true);
    wp_enqueue_script('tradeproxy-blog', get_template_directory_uri() . '/js/blog.js', array('jquery'), filemtime(get_template_directory() . '/js/blog.js'), true);
    wp_enqueue_script('tradeproxy-single-posts', get_template_directory_uri() . '/js/single-posts.js', array('jquery'), filemtime(get_template_directory() . '/js/single-posts.js'), true);
    wp_enqueue_script('tradeproxy-sign-in', get_template_directory_uri() . '/js/sign-in.js', array('jquery'), filemtime(get_template_directory() . '/js/sign-in.js'), true);
    wp_enqueue_script('tradeproxy-register', get_template_directory_uri() . '/js/register.js', array('jquery'), filemtime(get_template_directory() . '/js/register.js'), true);
    wp_enqueue_script('tradeproxy-forgetpass', get_template_directory_uri() . '/js/forgetpass.js', array('jquery'), filemtime(get_template_directory() . '/js/forgetpass.js'), true);
    wp_enqueue_script('tradeproxy-user-account', get_template_directory_uri() . '/js/user-account.js', array('jquery'), filemtime(get_template_directory() . '/js/user-account.js'), true);
    wp_enqueue_script('tradeproxy-profile', get_template_directory_uri() . '/js/pages/profile.js', array('jquery'), filemtime(get_template_directory() . '/js/pages/profile.js'), true);

}

add_action('wp_enqueue_scripts', 'tradeproxy_theme_enqueue_assets');

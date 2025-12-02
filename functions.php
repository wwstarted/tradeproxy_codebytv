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
    wp_enqueue_style('tradeproxy-single-post', get_template_directory_uri() . '/css/single-post.css', array(), filemtime(get_stylesheet_directory() . '/css/single-post.css'));
    wp_enqueue_style('tradeproxy-sign-in', get_template_directory_uri() . '/css/sign-in.css', array(), filemtime(get_stylesheet_directory() . '/css/sign-in.css'));
    wp_enqueue_style('tradeproxy-register', get_template_directory_uri() . '/css/register.css', array(), filemtime(get_stylesheet_directory() . '/css/register.css'));
    wp_enqueue_style('tradeproxy-forgetpass', get_template_directory_uri() . '/css/forgetpass.css', array(), filemtime(get_stylesheet_directory() . '/css/forgetpass.css'));
    wp_enqueue_style('tradeproxy-user-account', get_template_directory_uri() . '/css/user-account.css', array(), filemtime(get_stylesheet_directory() . '/css/user-account.css'));
    wp_enqueue_style('tradeproxy-profile', get_template_directory_uri() . '/css/pages/profile.css', array(), filemtime(get_stylesheet_directory() . '/css/pages/profile.css'));
    wp_enqueue_style('tradeproxy-changepass', get_template_directory_uri() . '/css/pages/changepass.css', array(), filemtime(get_stylesheet_directory() . '/css/pages/changepass.css'));




    // Gọi file JS trong thư mục /js/
    // wp_enqueue_script('auth-login', get_template_directory_uri() . '/js/auth-login.js', array('jquery'), filemtime(get_template_directory() . '/js/auth-login.js'), true);
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
    wp_enqueue_script('tradeproxy-single-post', get_template_directory_uri() . '/js/single-post.js', array('jquery'), filemtime(get_template_directory() . '/js/single-post.js'), true);
    wp_enqueue_script('tradeproxy-sign-in', get_template_directory_uri() . '/js/sign-in.js', array('jquery'), filemtime(get_template_directory() . '/js/sign-in.js'), true);
    wp_enqueue_script('tradeproxy-register', get_template_directory_uri() . '/js/register.js', array('jquery'), filemtime(get_template_directory() . '/js/register.js'), true);
    wp_enqueue_script('tradeproxy-forgetpass', get_template_directory_uri() . '/js/forgetpass.js', array('jquery'), filemtime(get_template_directory() . '/js/forgetpass.js'), true);
    wp_enqueue_script('tradeproxy-user-account', get_template_directory_uri() . '/js/user-account.js', array('jquery'), filemtime(get_template_directory() . '/js/user-account.js'), true);
    wp_enqueue_script('tradeproxy-profile', get_template_directory_uri() . '/js/pages/profile.js', array('jquery'), filemtime(get_template_directory() . '/js/pages/profile.js'), true);
    wp_enqueue_script('tradeproxy-changepass', get_template_directory_uri() . '/js/pages/changepass.js', array('jquery'), filemtime(get_template_directory() . '/js/pages/changepass.js'), true);
    wp_enqueue_script('tradeproxy-variations', get_template_directory_uri() . '/js/product-variations.js', array('jquery'), filemtime(get_template_directory() . '/js/product-variations.js'), true);

}

add_action('wp_enqueue_scripts', 'tradeproxy_theme_enqueue_assets');

// require_once get_theme_file_path('/inc/cf-proxy.php');
require_once get_theme_file_path('/inc/api-user.php');
require_once get_theme_file_path('/inc/cpt_blog.php');
require_once get_theme_file_path('/inc/cpt_provider.php');
require_once get_theme_file_path('/inc/api-product.php');

// // create jwt token for login
// require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once get_template_directory() . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Enqueue account scripts
 */
function enqueue_account_scripts()
{
    // load account content
    if (is_page('account')) {
        // Enqueue script
        wp_enqueue_script(
            'account-js',
            get_template_directory_uri() . '/js/account.js',
            array(),
            '1.0.2',
            true
        );

        // data php => javascripts
        wp_localize_script('account-js', 'wpAccountData', array(
            'baseUrl' => home_url(),
            'accountUrl' => get_permalink(get_page_by_path('account')),
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('account_nonce'),
            'loginUrl' => get_permalink(get_page_by_path('login')),
        ));
    }
}
add_action('wp_enqueue_scripts', 'enqueue_account_scripts');

/**
 * AJAX handler - Load page content
 */
function load_account_page_callback()
{
    // Verify nonce
    if (!check_ajax_referer('account_nonce', 'nonce', false)) {
        wp_send_json_error('Invalid security token');
        return;
    }

    // Get page slug
    $page_slug = isset($_POST['page_slug']) ? sanitize_text_field($_POST['page_slug']) : '';

    if (empty($page_slug)) {
        wp_send_json_error('Page slug is required');
        return;
    }

    // Find page by slug
    $page = get_page_by_path($page_slug);

    if (!$page) {
        wp_send_json_error('Page not found: ' . $page_slug);
        return;
    }

    // Setup global post
    global $post;
    $original_post = $post;
    $post = $page;
    setup_postdata($post);

    // Start output buffering
    ob_start();

    // Find template file
    $template_file = 'page-' . $page_slug . '.php';
    $template_path = locate_template($template_file);

    if (!$template_path) {
        // Try without 'page-' prefix
        $template_file = $page_slug . '.php';
        $template_path = locate_template($template_file);
    }

    if ($template_path) {
        // Include template
        include($template_path);
    } else {
        // Fallback: show page content
        echo '<div class="page-content">';
        the_content();
        echo '</div>';
    }

    // Get output
    $content = ob_get_clean();

    // Reset post data
    $post = $original_post;
    wp_reset_postdata();

    // Return JSON
    wp_send_json_success($content);
}

// Register AJAX actions
add_action('wp_ajax_load_account_page', 'load_account_page_callback');
add_action('wp_ajax_nopriv_load_account_page', 'load_account_page_callback');


// function update_user_profile($request) {
//     $user_id = get_current_user_id();
//     if (!$user_id) return new WP_Error('no_auth', 'Unauthorized', ['status' => 401]);

//     $display_name = sanitize_text_field($request['display_name']);
//     $email = sanitize_email($request['email']);
//     $phone = sanitize_text_field($request['phone']);

//     // Cập nhật user
//     wp_update_user([
//         'ID' => $user_id,
//         'display_name' => $display_name,
//         'user_email' => $email,
//     ]);

//     // Lưu phone vào user meta
//     update_user_meta($user_id, 'phone', $phone);

//     return [
//         'success' => true,
//         'message' => 'Cập nhật thành công',
//         'data' => [
//             'display_name' => $display_name,
//             'email' => $email,
//             'phone' => $phone,
//         ]
//     ];
// }

// on cors

add_action('rest_api_init', function () {
    remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
    add_filter('rest_pre_serve_request', function ($value) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Authorization, Content-Type');
        header('Access-Control-Allow-Credentials: true');
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            http_response_code(200);
            exit();
        }
        return $value;
    });
});

//  ep ko load page con 
add_filter('template_include', function ($template) {
    $account_pages = ['account', 'profile', 'wallet', 'change-password', 'membership', 'deposit-history', 'purchase-history'];

    if (is_page() && in_array(get_post_field('post_name', get_queried_object_id()), $account_pages)) {
        $custom_template = locate_template('page-account.php');
        if ($custom_template)
            return $custom_template;
    }

    return $template;
});

//  =================================================rewrite rule singleblog =================================================

function my_blog_rewrite_rules()
{
    add_rewrite_rule(
        '^blog/([^/]+)/?$',
        'index.php?post_type=cpt_post&name=$matches[1]',
        'top'
    );
}
add_action('init', 'my_blog_rewrite_rules');

add_filter('template_include', function ($template) {

    if (is_singular('cpt_post')) {
        $tpl = locate_template('page-singleblog.php');
        if ($tpl)
            return $tpl;
    }

    if (is_page('blog')) {
        $tpl = locate_template('page-blog.php');
        if ($tpl)
            return $tpl;
    }

    return $template;
});


function my_provider_rewrite_rules()
{
    add_rewrite_rule(
        '^providers/([^/]+)/?$',
        'index.php?post_type=provider&name=$matches[1]',
        'top'
    );
}
add_action('init', 'my_provider_rewrite_rules');

add_filter('template_include', function ($template) {
    if (is_singular('provider')) {
        $tpl = locate_template('page-provider-detail.php');
        if ($tpl)
            return $tpl;
    }

    if (is_page('providers')) {
        $tpl = locate_template('page-providers.php');
        if ($tpl)
            return $tpl;
    }

    return $template;
});
//======================================= override template woo =====================================

add_filter('woocommerce_enqueue_styles', '__return_empty_array');

add_theme_support('woocommerce');

//=========================================== AJAX add to cart =========================================
// AJAX handler cho add to cart (nếu WooCommerce không có sẵn)
add_action('wp_ajax_woocommerce_add_to_cart', 'custom_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_add_to_cart', 'custom_ajax_add_to_cart');

function custom_ajax_add_to_cart()
{
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    $variation_id = isset($_POST['variation_id']) ? absint($_POST['variation_id']) : 0;
    $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;

    if ($variation_id) {
        $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity, $variation_id);
    } else {
        $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity);
    }

    if ($cart_item_key) {
        // Get cart fragments
        WC_AJAX::get_refreshed_fragments();
    } else {
        wp_send_json_error(array(
            'error' => true,
            'message' => 'Không thể thêm sản phẩm vào giỏ hàng'
        ));
    }
}
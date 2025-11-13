<?php
/**
 * Theme Functions
 */

// Autoload các file trong /inc
require_once get_theme_file_path('/inc/cpt-proxy.php');
require_once get_theme_file_path('/inc/cpt-post.php');
require_once get_theme_file_path('/inc/api-user.php');


// create jwt token for login
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Enqueue account scripts
 */
function enqueue_account_scripts() {
    // Chỉ load trên trang account
    if (is_page('account')) {
        // Enqueue script
        wp_enqueue_script(
            'account-js',
            get_template_directory_uri() . '/js/account.js',
            array(),
            '1.0.2', // Tăng version để clear cache
            true
        );
        
        // Pass data từ PHP sang JavaScript
        wp_localize_script('account-js', 'wpAccountData', array(
            'baseUrl' => home_url(),
            'accountUrl' => get_permalink(get_page_by_path('account')),
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('account_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'enqueue_account_scripts');

/**
 * AJAX handler - Load page content
 */
function load_account_page_callback() {
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


function update_user_profile($request) {
    $user_id = get_current_user_id();
    if (!$user_id) return new WP_Error('no_auth', 'Unauthorized', ['status' => 401]);

    $display_name = sanitize_text_field($request['display_name']);
    $email = sanitize_email($request['email']);
    $phone = sanitize_text_field($request['phone']);

    // Cập nhật user
    wp_update_user([
        'ID' => $user_id,
        'display_name' => $display_name,
        'user_email' => $email,
    ]);

    // Lưu phone vào user meta
    update_user_meta($user_id, 'phone', $phone);

    return [
        'success' => true,
        'message' => 'Cập nhật thành công',
        'data' => [
            'display_name' => $display_name,
            'email' => $email,
            'phone' => $phone,
        ]
    ];
}

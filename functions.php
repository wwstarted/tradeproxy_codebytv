<?php
/**
 * Theme Functions
 */

// Autoload các file trong /inc
require_once get_theme_file_path('/inc/cpt-proxy.php');
require_once get_theme_file_path('/inc/cpt-post.php');


// create jwt token for login
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use Firebase\JWT\JWT;

function generate_jwt_token($user_id) {
    $issuedAt = time();
    $expire    = $issuedAt + (7 * 24 * 60 * 60);

    $payload = [
        'iss' => get_bloginfo('url'),
        'iat' => $issuedAt,
        'exp' => $expire,
        'user_id' => $user_id,
    ];

    $token = JWT::encode($payload, JWT_SECRET_KEY, 'HS256');

    return $token;
}



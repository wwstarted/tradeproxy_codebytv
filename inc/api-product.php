<?php
// REST API cho related products
add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/related-products/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_related_products_api',
        'permission_callback' => '__return_true'
    ));
});

function get_related_products_api($request)
{
    $product_id = $request['id'];
    $related_ids = wc_get_related_products($product_id, 6);

    $products = array();
    foreach ($related_ids as $related_id) {
        $product = wc_get_product($related_id);
        if ($product) {
            $products[] = array(
                'id' => $product->get_id(),
                'name' => $product->get_name(),
                'price' => $product->get_price_html(),
                'image' => wp_get_attachment_url($product->get_image_id()),
                'link' => get_permalink($product->get_id()),
                'type' => $product->get_type()
            );
        }
    }

    return $products;
}
<?php get_header(); ?>
<?php
/**
 * Cart Page - Custom Template
 * Override WooCommerce cart với HTML tĩnh
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart');

// Lấy data từ WooCommerce cart để pass vào JavaScript
$cart_data = array();
foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
    $_product = $cart_item['data'];
    $product_id = $cart_item['product_id'];

    // Lấy variation attributes
    $variation_text = array();
    if (!empty($cart_item['variation'])) {
        foreach ($cart_item['variation'] as $name => $value) {
            $taxonomy = str_replace('attribute_', '', $name);
            if (taxonomy_exists($taxonomy)) {
                $term = get_term_by('slug', $value, $taxonomy);
                $variation_text[] = $term ? $term->name : $value;
            } else {
                $variation_text[] = $value;
            }
        }
    }

    $cart_data[] = array(
        'cart_item_key' => $cart_item_key,
        'product_id' => $product_id,
        'name' => $_product->get_name(),
        'description' => !empty($variation_text) ? '(' . implode(', ', $variation_text) . ')' : '',
        'image' => wp_get_attachment_image_url($_product->get_image_id(), 'thumbnail'),
        'price' => $_product->get_price(),
        'price_html' => $_product->get_price_html(),
        'priceUSD' => get_post_meta($product_id, '_price_usd', true) ?: 0,
        'quantity' => $cart_item['quantity'],
        'permalink' => $_product->get_permalink(),
        'remove_url' => wc_get_cart_remove_url($cart_item_key),
        'max_quantity' => $_product->get_max_purchase_quantity()
    );
}
?>

<!-- Main -->
<div class="main-content bg-[#f9fdff]">
    <div class="cart-container">
        <!-- Cart Header -->
        <h1 class="cart-title">Giỏ hàng</h1>
        <p class="cart-subtitle">
            Sẽ có bài viết hướng dẫn ở mỗi cuối đơn hàng!
        </p>
        <p class="cart-support">Liên hệ nếu cần hỗ trợ: 034 770 0437</p>

        <!-- Hidden WooCommerce Form (để xử lý update cart) -->
        <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post"
            style="display: none;">
            <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item): ?>
            <input type="number" name="cart[<?php echo $cart_item_key; ?>][qty]" class="hidden-qty-input"
                data-key="<?php echo esc_attr($cart_item_key); ?>"
                value="<?php echo esc_attr($cart_item['quantity']); ?>" />
            <?php endforeach; ?>

            <button type="submit" name="update_cart" id="hidden-update-cart-btn"></button>
            <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
        </form>

        <?php if (wc_coupons_enabled()): ?>
        <!-- Hidden Coupon Form -->
        <form class="woocommerce-coupon-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post"
            style="display: none;">
            <input type="text" name="coupon_code" id="hidden-coupon-code" />
            <button type="submit" name="apply_coupon" id="hidden-apply-coupon-btn"></button>
            <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
        </form>
        <?php endif; ?>

        <!-- Cart Content (HTML tĩnh của bạn) -->
        <div class="cart-content">
            <!-- Left Side - Cart Items -->
            <div class="cart-items">
                <!-- Table Header -->
                <div class="cart-table-header">
                    <div>
                        <input type="checkbox" class="cart-checkbox" id="selectAll" />
                    </div>
                    <div class="cart-name-product">Tên mặt hàng</div>
                    <div>Số lượng</div>
                    <div>Giá</div>
                    <div>Thành tiền</div>
                    <div></div>
                </div>

                <!-- Cart items sẽ được render bằng JavaScript -->
                <div id="cart-items-wrapper"></div>

                <!-- Cart Footer -->
                <div class="cart-footer">
                    <span class="cart-footer-text">Tổng:</span>
                    <span class="cart-footer-count">0</span>
                </div>
            </div>

            <!-- Right Side - Cart Summary -->
            <div class="cart-summary">
                <!-- Discount Code Section -->
                <div class="discount-section">
                    <label class="discount-label">Nhập mã giảm giá:</label>
                    <div class="discount-input-wrapper">
                        <input type="text" class="discount-input" placeholder="Nhập mã giảm giá" id="discountCode" />
                        <button class="discount-btn" id="applyDiscount">
                            Xác nhận
                        </button>
                        <div class="discount-tooltip" id="discountTooltip" style="display: none">
                            Nhận mã giảm giá !
                        </div>
                    </div>
                </div>

                <!-- Summary Details -->
                <div class="summary-row">
                    <span class="summary-label">Tổng:</span>
                    <span class="summary-value" id="subtotalAmount">0đ</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Tên mã giảm:</span>
                    <span class="summary-value" id="discountName">-</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Tiền giảm giá:</span>
                    <div>
                        <div class="discount-amount" id="discountValue">0đ</div>
                        <div class="discount-percent" id="discountPercent">0$</div>
                    </div>
                </div>

                <div class="summary-divider"></div>

                <!-- Total -->
                <div class="summary-total">
                    <span class="summary-label">Thành tiền:</span>
                    <div>
                        <div class="total-price" id="finalTotal">0đ</div>
                        <div class="total-discount-info" id="finalDiscount">
                            ≈ 0$
                        </div>
                    </div>
                </div>

                <!-- Email Section -->
                <div class="email-section">
                    <label class="email-label">
                        Nhập email để nhận thông báo <span class="required">*:</span>
                    </label>
                    <input type="email" class="email-input" placeholder="Email" id="customerEmail" required />

                    <button class="checkout-btn" id="checkoutBtn">
                        Thanh toán
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Method Modal -->
<div class="payment-modal" id="paymentModal">
    <div class="payment-modal-content">
        <div class="payment-modal-header">
            <h2>Chọn hình thức thanh toán</h2>
            <button class="payment-modal-close" id="closePaymentModal">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="payment-modal-body">
            <div class="payment-methods">
                <div class="payment-method" data-method="wallet">
                    <div class="payment-method-icon">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <div class="payment-method-info">
                        <h3>Ví của tôi</h3>
                        <p class="payment-balance">0 đ</p>
                    </div>
                </div>

                <div class="payment-method" data-method="bank">
                    <div class="payment-method-icon payment-method-icon-bank">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                    <div class="payment-method-info">
                        <h3>Ngân hàng</h3>
                    </div>
                </div>

                <div class="payment-method" data-method="crypto">
                    <div class="payment-method-icon payment-method-icon-crypto">
                        <i class="fa-brands fa-bitcoin"></i>
                    </div>
                    <div class="payment-method-info">
                        <h3>Tiền điện tử</h3>
                    </div>
                </div>
            </div>

            <div class="payment-warning">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Số dư ví không đủ.</span>
            </div>
        </div>

        <div class="payment-modal-footer">
            <a href="<?php echo esc_url(home_url('/account/#wallet')); ?>" class="payment-topup-btn">
                <i class="fa-solid fa-wallet"></i>
                Nạp tiền vào ví
            </a>
            <div class="payment-actions">
                <button class="payment-cancel-btn" id="cancelPayment">Huỷ</button>
                <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="payment-confirm-btn" id="confirmPayment">
                    Tiếp tục
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Pass WooCommerce cart data to JavaScript -->
<script type="text/javascript">
var wooCartData = <?php echo json_encode($cart_data); ?>;
var wooCartSubtotal = <?php echo WC()->cart->get_subtotal(); ?>;
var wooCartTotal = <?php echo WC()->cart->get_total('raw'); ?>;
var wooCoupons = <?php echo json_encode(array_keys(WC()->cart->get_applied_coupons())); ?>;
var wooCheckoutUrl = '<?php echo esc_url(wc_get_checkout_url()); ?>';
</script>

<?php do_action('woocommerce_after_cart'); ?>

<?php get_footer(); ?>
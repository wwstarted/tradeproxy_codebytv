<?php
/*
Template Name: Cart Page
*/
?>

<?php get_header(); ?>

<!-- Main -->
<div class="main-content bg-[#f9fdff]">
    <div class="cart-container">
        <!-- Cart Header -->
        <h1 class="cart-title">Giỏ hàng</h1>
        <p class="cart-subtitle">
            Sẽ có bài viết hướng dẫn ở mỗi cuối đơn hàng!
        </p>
        <p class="cart-support">Liên hệ nếu cần hỗ trợ: 034 770 0437</p>

        <!-- Cart Content -->
        <div class="cart-content">
            <!-- Left Side - Cart Items -->
            <div class="cart-items">
                <!-- Table Header -->
                <div class="cart-table-header">
                    <div>
                        <input type="checkbox" class="cart-checkbox" id="selectAll" />
                    </div>
                    <div>Tên mặt hàng</div>
                    <div>Số lượng</div>
                    <div>Giá</div>
                    <div>Thành tiền</div>
                    <div></div>
                </div>

                <!-- Cart Item 1 -->
                <div class="cart-item">
                    <div class="btn-checkbox">
                        <input
                            type="checkbox"
                            class="cart-checkbox item-checkbox"
                            checked />
                    </div>

                    <div class="cart-item-info">
                        <div class="cart-item-logo">
                            <img
                                src="https://tradeproxy.vn/images/logo/9proxy.png"
                                alt="" />
                        </div>
                        <div class="cart-item-details">
                            <h3>9proxy</h3>
                            <p>(50 ip)</p>
                        </div>
                    </div>

                    <div class="cart-quantity">
                        <div class="cart-quantity-mobile">
                            <button
                                class="quantity-btn minus-btn"
                                data-action="decrease">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                            <input
                                type="number"
                                class="quantity-input"
                                value="1"
                                min="1"
                                readonly />
                            <button
                                class="quantity-btn plus-btn"
                                data-action="increase">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="cart-price">
                        <div class="cart-price-mobile">
                            <div class="original-price">50,000đ</div>
                            <div class="discount-badge">
                                <img
                                    src="https://tradeproxy.vn/images/icon/tether.png"
                                    alt="" />
                                3.2$
                            </div>
                        </div>
                    </div>

                    <div class="cart-total-price">
                        <div>
                            <div class="total-amount">50,000đ</div>
                            <div class="total-discount">
                                <img
                                    src="https://tradeproxy.vn/images/icon/tether.png"
                                    alt="" />
                                3.2$
                            </div>
                        </div>
                    </div>

                    <div class="cart-delete-btn">
                        <button class="delete-btn" title="Xóa sản phẩm">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>

                <!-- Cart Footer -->
                <div class="cart-footer">
                    <span class="cart-footer-text">Tổng:</span>
                    <span class="cart-footer-count">1</span>
                </div>
            </div>

            <!-- Right Side - Cart Summary -->
            <div class="cart-summary">
                <!-- Discount Code Section -->
                <div class="discount-section">
                    <label class="discount-label">Nhập mã giảm giá:</label>
                    <div class="discount-input-wrapper">
                        <input
                            type="text"
                            class="discount-input"
                            placeholder="Nhập mã giảm giá"
                            id="discountCode" />
                        <button class="discount-btn" id="applyDiscount">
                            Xác nhận mã
                        </button>
                        <div
                            class="discount-tooltip"
                            id="discountTooltip"
                            style="display: none">
                            Nhận mã giảm giá !
                        </div>
                    </div>
                </div>

                <!-- Summary Details -->
                <div class="summary-row">
                    <span class="summary-label">Tổng:</span>
                    <span class="summary-value" id="subtotalAmount">50,000đ</span>
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
                        <div class="total-price" id="finalTotal">50,000đ</div>
                        <div class="total-discount-info" id="finalDiscount">
                            ≈ 3.2$
                        </div>
                    </div>
                </div>

                <!-- Email Section -->
                <!-- Email Section -->
                <div class="email-section">
                    <label class="email-label">
                        Nhập email để nhận thông báo <span class="required">*:</span>
                    </label>
                    <input
                        type="email"
                        class="email-input"
                        placeholder="Email"
                        id="customerEmail"
                        required />

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
            <button class="payment-topup-btn">
                <i class="fa-solid fa-wallet"></i>
                Nạp tiền vào ví
            </button>
            <div class="payment-actions">
                <button class="payment-cancel-btn" id="cancelPayment">Huỷ</button>
                <a href="<?php echo esc_url(home_url('/payment')) ?>" class="payment-confirm-btn" id="confirmPayment">
                    Tiếp tục
                </a>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
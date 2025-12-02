<?php get_header(); ?>

<!-- Main content -->
<div class="main-content bg-[#f9fdff]">
    <div class="product-detail-container">
        <!-- Left Column - Product Information -->
        <div class="product-info">
            <!-- Product Header -->
            <div class="product-header">
                <div class="product-logo">
                    <img src="https://tradeproxy.vn/images/categories/9proxy.png" alt="" />
                </div>
                <div class="product-title-section">
                    <h1>9proxy</h1>
                    <div class="product-rating">
                        <div class="stars">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <span class="rating-text">5.0</span>
                    </div>
                    <div class="product-meta">
                        <span><strong>Nhà cung cấp:</strong> 9proxy</span>
                        <span><strong>Website:</strong>
                            <a href="#">www.9proxy.com</a></span>
                        <span><strong>Điện thoại:</strong> 0912345678</span>
                        <span><strong>Giao thức:</strong> Http, socks5</span>
                        <span><strong>Loại Proxy:</strong> IPv4 dân cư (mã đạo diễn các
                            IP đạo đạt 2G cuối giá)</span>
                        <span><strong>Định dạng:</strong> CDkey (Mã cộng IP vào tài
                            khoản) xem ngay bài viết <a href="">Hướng Dẫn</a></span>
                        <span><strong>Bảo hành:</strong> Vĩnh viễn</span>
                    </div>
                </div>
            </div>

            <!-- Feature Grid -->
            <div class="feature-grid">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div class="feature-title">Hàng chính hãng</div>
                    <div class="feature-desc">
                        Cam kết hàng chính hãng bảo hành đến khi xài hết
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fa-solid fa-gauge-high"></i>
                    </div>
                    <div class="feature-title">Nhận proxy nhanh</div>
                    <div class="feature-desc">
                        Hiển thị CDkey trong lịch sử mua hàng và trên email
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fa-solid fa-gears"></i>
                    </div>
                    <div class="feature-title">Hỗ trợ cài đặt</div>
                    <div class="feature-desc">
                        Hỗ trợ miễn phí qua Teamvier hoặc Anydesk
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                    </div>
                    <div class="feature-title">Đảm bảo hoàn tiền</div>
                    <div class="feature-desc">
                        Hoàn tiền 100% nếu CDkey không hoạt động
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="product-description">
                <h2>Thảo luận về 9Proxy</h2>
                <p>
                    Nhà cung cấp dịch vụ 9proxy hiện có cung cấp đầy đủ các loại
                    proxy tại khu vực các quốc gia Mỹ, với các tính cần sử dụng của
                    các loại trên IP gắm này chất lượng dịch thử bản các đạo để đạt.
                </p>
                <p>
                    Máy nhớ gồm bó dạng và 9proxy TRADEPROXY VN, trước làm gái được
                    các thử phần điểm gắm kéo đối tốc độ sử đạt có viết cái định
                    hành bảo chạm tải các, chú tùng vào các hệ văn mái về chất thực
                    của các học định các loại.
                </p>
                <p>
                    Chủ nhất 9proxy vả TRADEPROXY VN, triết làm gái định thể mái gắm
                    điểm thử chế được cấp mỗi các nhiều điện nhổ IP các đạt từ viết
                    mọi mọi địa nổ chạy các đứng định nhớ định chất người giá bản
                    đạt định bệnh.
                </p>
            </div>

            <!-- Dashboard Preview -->
            <div class="dashboard-preview">
                <h3>Giao diện quản trị của 9proxy</h3>
                <div class="dashboard-image">
                    <img src="https://tradeproxy.vn/images/public/giao-dien-phan-mem-9proxy.webp"
                        alt="9proxy Dashboard" />
                </div>
                <p class="dashboard-caption">Giao diện quản trị của 9proxy</p>
            </div>
        </div>

        <!-- Right Sidebar - Pricing -->
        <div class="pricing-sidebar">
            <div class="pricing-card">
                <?php
                while (have_posts()):
                    the_post();
                    global $product;

                    // check object
                    if (!is_object($product)) {
                        $product = wc_get_product(get_the_ID());
                    }

                    if ($product && is_a($product, 'WC_Product') && $product->is_type('variable')):
                        $available_variations = $product->get_available_variations();
                        $variation_attributes = $product->get_variation_attributes();

                        // covert variations to json;
                        $variation_data_json = htmlspecialchars(wp_json_encode($available_variations));
                        ?>

                <h3><?php echo $product->get_name(); ?></h3>

                <form class="variations_form cart" method="post" enctype='multipart/form-data'
                    data-product_id="<?php echo absint($product->get_id()); ?>"
                    data-product_variations="<?php echo $variation_data_json; ?>">

                    <?php
                            $ke_hoach_attr = null;
                            $ke_hoach_taxonomy = null;

                            // find
                            if (isset($variation_attributes['pa_ke-hoach'])) {
                                $ke_hoach_attr = 'pa_ke-hoach';
                                $ke_hoach_taxonomy = 'pa_ke-hoach';
                            } elseif (isset($variation_attributes['ke-hoach'])) {
                                $ke_hoach_attr = 'ke-hoach';
                                $ke_hoach_taxonomy = 'ke-hoach';
                            }

                            if ($ke_hoach_attr):
                                $ke_hoach_slugs = $variation_attributes[$ke_hoach_attr];
                                ?>
                    <p style="font-size: 14px; font-weight: 600; margin-bottom: 8px; margin-top: 16px; color: #1f2937;">
                        Kế hoạch
                    </p>
                    <div class="quantity-grid">
                        <?php foreach ($ke_hoach_slugs as $slug):
                                        // Lấy term name từ slug (nếu là global attribute)
                                        $term = get_term_by('slug', $slug, 'pa_ke-hoach'); // Luôn thử với pa_ slug
                                        $term_name = $term ? $term->name : $slug;
                                        ?>
                        <button type="button" class="quantity-btn-proxy"
                            data-attribute="<?php echo esc_attr($ke_hoach_attr); ?>"
                            data-value="<?php echo esc_attr($slug); ?>">
                            <?php echo esc_html($term_name); ?>
                        </button>
                        <?php endforeach; ?>
                    </div>
                    <input type="hidden" name="attribute_<?php echo esc_attr($ke_hoach_attr); ?>" value=""
                        class="attribute-selector">
                    <?php endif; ?>

                    <?php
                            $thoi_han_attr = null;
                            $thoi_han_taxonomy = null;

                            if (isset($variation_attributes['pa_thoi-han'])) {
                                $thoi_han_attr = 'pa_thoi-han';
                                $thoi_han_taxonomy = 'pa_thoi-han';
                            } elseif (isset($variation_attributes['thoi-han'])) {
                                $thoi_han_attr = 'thoi-han';
                                $thoi_han_taxonomy = 'thoi-han';
                            }

                            if ($thoi_han_attr):
                                $thoi_han_slugs = $variation_attributes[$thoi_han_attr];
                                ?>
                    <p style="font-size: 14px; font-weight: 600; margin-bottom: 8px; margin-top: 16px; color: #1f2937;">
                        Thời hạn
                    </p>
                    <div class="duration-grid">
                        <?php foreach ($thoi_han_slugs as $slug):
                                        $term = get_term_by('slug', $slug, 'pa_thoi-han');
                                        $term_name = $term ? $term->name : $slug;
                                        ?>
                        <button type="button" class="duration-btn"
                            data-attribute="<?php echo esc_attr($thoi_han_attr); ?>"
                            data-value="<?php echo esc_attr($slug); ?>">
                            <?php echo esc_html($term_name); ?>
                        </button>
                        <?php endforeach; ?>
                    </div>
                    <input type="hidden" name="attribute_<?php echo esc_attr($thoi_han_attr); ?>" value=""
                        class="attribute-selector">
                    <?php endif; ?>

                    <p style="font-size: 14px; font-weight: 600; margin-bottom: 8px; margin-top: 16px; color: #1f2937;">
                        Số lượng lớn hơn (Vui lòng liên hệ để được giá tốt hơn)
                    </p>

                    <div class="price-display">
                        <div class="price-label">Tổng tiền</div>
                        <div class="price-amount">
                            <span id="variation-price-display" class="woocommerce-Price-amount amount">Vui lòng chọn
                                gói</span>
                        </div>
                    </div>

                    <input type="hidden" name="variation_id" class="variation_id" value="0">
                    <input type="hidden" name="product_id" value="<?php echo absint($product->get_id()); ?>">
                    <input type="hidden" name="quantity" value="1">

                    <div class="action-buttons">
                        <button type="submit" class="btn-primary-proxy single_add_to_cart_button" disabled>
                            <i class="fa-solid fa-credit-card"></i>
                            Mua ngay
                        </button>
                        <button type="button" class="btn-secondary-proxy ajax_add_to_cart" disabled>
                            <i class="fa-solid fa-cart-plus"></i>
                            Thêm vào giỏ hàng
                        </button>
                    </div>
                </form>

                <?php
                    else:
                        ?>
                <?php endif;
                endwhile;
                ?>
            </div>

            <!-- Related Products -->
            <div class="related-products">
                <h3>Sản phẩm liên quan</h3>

                <div class="related-product-item">
                    <div class="related-product-logo">
                        <img src="https://tradeproxy.vn/images/categories/922-proxy.png" alt="IPRoyal" />
                    </div>
                    <div class="related-product-info">
                        <div class="related-product-name">IPRoyal</div>
                        <div class="related-product-type">IPv4</div>
                        <div class="related-product-price">
                            80.000đ
                            <span class="related-product-stock">còn hàng</span>
                        </div>
                    </div>
                    <div class="related-product-action">
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </div>

                <div class="related-product-item">
                    <div class="related-product-logo">
                        <img src="https://tradeproxy.vn/images/categories/abc-proxy.png" alt="ABC Proxy" />
                    </div>
                    <div class="related-product-info">
                        <div class="related-product-name">ABC Proxy</div>
                        <div class="related-product-type">IPv4</div>
                        <div class="related-product-price">
                            80.000đ
                            <span class="related-product-stock">còn hàng</span>
                        </div>
                    </div>
                    <div class="related-product-action">
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </div>

                <div class="related-product-item">
                    <div class="related-product-logo">
                        <img src="https://tradeproxy.vn/images/categories/cliproxy.png" alt="CTProxy" />
                    </div>
                    <div class="related-product-info">
                        <div class="related-product-name">CTproxy</div>
                        <div class="related-product-type">IPv4</div>
                        <div class="related-product-price">
                            140.000đ
                            <span class="related-product-stock">còn hàng</span>
                        </div>
                    </div>
                    <div class="related-product-action">
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </div>

                <div class="related-product-item">
                    <div class="related-product-logo">
                        <img src="https://tradeproxy.vn/images/categories/ip2world.png" alt="HP2 WORLD" />
                    </div>
                    <div class="related-product-info">
                        <div class="related-product-name">hp2world</div>
                        <div class="related-product-type">IPv4</div>
                        <div class="related-product-price">
                            475.000đ
                            <span class="related-product-stock">còn hàng</span>
                        </div>
                    </div>
                    <div class="related-product-action">
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </div>

                <div class="related-product-item">
                    <div class="related-product-logo">
                        <img src="https://tradeproxy.vn/images/categories/pia-proxy.png" alt="922 Proxy" />
                    </div>
                    <div class="related-product-info">
                        <div class="related-product-name">922 Proxy</div>
                        <div class="related-product-type">IPv4</div>
                        <div class="related-product-price">
                            360.000đ
                            <span class="related-product-stock">còn hàng</span>
                        </div>
                    </div>
                    <div class="related-product-action">
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </div>

                <div class="related-product-item">
                    <div class="related-product-logo">
                        <img src="https://tradeproxy.vn/images/categories/Pyproxy.png" alt="PyProxy" />
                    </div>
                    <div class="related-product-info">
                        <div class="related-product-name">Pyproxy</div>
                        <div class="related-product-type">IPv4</div>
                        <div class="related-product-price">
                            575.000đ
                            <span class="related-product-stock">còn hàng</span>
                        </div>
                    </div>
                    <div class="related-product-action">
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Slide proxies bottom -->
    <div class="detail-proxy-carousel-section">
        <h2 class="detail-section-title">Các Gói Proxy Phổ Biến</h2>

        <div class="detail-carousel-container">
            <button class="detail-carousel-nav-button detail-prev" id="detailPrevBtn">
                <i class="fa-solid fa-chevron-left"></i>
            </button>

            <div class="detail-cards-wrapper" id="detailCardsWrapper">
                <!-- Card 1: ABC Proxy -->
                <div class="detail-proxy-card">
                    <div class="detail-proxy-card-header">
                        <div class="detail-proxy-card-logo">
                            <img src="https://tradeproxy.vn/images/categories/abc-proxy.png" alt="ABC Proxy" />
                        </div>
                        <h2 class="detail-proxy-card-title">ABC Proxy</h2>
                        <p class="detail-proxy-card-description line-clamp">
                            Tốc độ truyền tải mạnh, số lượng IP đa dạng.
                            <strong>Mua ABC proxy</strong> ngay!
                        </p>
                    </div>
                    <div class="detail-proxy-card-price">1,290đ / IP</div>
                    <div class="detail-proxy-card-body">
                        <ul class="detail-proxy-features">
                            <li>Tốc độ kết nối nhanh</li>
                            <li>Hỗ trợ Http, Socks5...</li>
                            <li>Window, Mac, Linux, Android</li>
                        </ul>
                        <button class="detail-proxy-btn-buy">Mua ngay</button>
                    </div>
                </div>

                <!-- Repeat cards... -->
            </div>

            <button class="detail-carousel-nav-button detail-next" id="detailNextBtn">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

<?php get_footer(); ?>
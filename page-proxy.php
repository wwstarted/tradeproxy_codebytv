<?php
/*
 Template Name: Proxies Page
*/
?>

<?php get_header(); ?>

<!-- Main content -->
<div class="main-content bg-[#f9fdff]">
    <div class="product-container">
        <!-- Header -->
        <div class="product-header-section">
            <h1 class="product-title">Danh sách sản phẩm</h1>
            <button class="filter-btn-mobile" id="openFilterModal">
                <i class="fa-solid fa-sliders"></i>
                Bộ lọc
            </button>
        </div>

        <!-- Layout -->
        <div class="product-layout">
            <!-- Sidebar Filters - Desktop -->
            <aside class="product-sidebar">
                <div class="sidebar-header">
                    <h2 class="sidebar-title">Chọn danh mục</h2>
                    <a href="#" class="clear-filter">Xóa lọc</a>
                </div>

                <!-- Category Filter -->
                <div class="filter-group">
                    <div class="filter-options">
                        <div class="filter-option active">
                            <input type="radio" id="cat-all" name="category" checked />
                            <label for="cat-all">
                                <i class="fa-solid fa-border-all"></i> Tất cả
                            </label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" id="cat-isp" name="category" />
                            <label for="cat-isp">
                                <i class="fa-solid fa-house"></i> Proxy dân cư ISP
                            </label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" id="cat-traffic" name="category" />
                            <label for="cat-traffic">
                                <i class="fa-solid fa-arrow-trend-up"></i> Proxy dân cư
                                traffic
                            </label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" id="cat-rotating" name="category" />
                            <label for="cat-rotating">
                                <i class="fa-solid fa-rotate"></i> Proxy xoay dân cư
                            </label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" id="cat-static" name="category" />
                            <label for="cat-static">
                                <i class="fa-solid fa-house-signal"></i> Proxy cố định
                            </label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" id="cat-unlimited" name="category" />
                            <label for="cat-unlimited">
                                <i class="fa-solid fa-house-laptop"></i> Proxy Unlimited
                            </label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" id="cat-modem" name="category" />
                            <label for="cat-modem">
                                <i class="fa-solid fa-tower-cell"></i> Modem proxy
                            </label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" id="cat-mmo" name="category" />
                            <label for="cat-mmo">
                                <i class="fa-regular fa-circle-dot"></i> Phần mềm MMO
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Sort Filter -->
                <div class="filter-group">
                    <h3 class="filter-group-title">Sắp xếp theo</h3>
                    <div class="filter-options">
                        <div class="filter-option active">
                            <input
                                type="radio"
                                id="sort-bestseller"
                                name="sort"
                                checked />
                            <label for="sort-bestseller">Bán chạy nhất</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" id="sort-latest" name="sort" />
                            <label for="sort-latest">Mới cập nhật</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" id="sort-price-low" name="sort" />
                            <label for="sort-price-low">Giá thấp đến cao</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" id="sort-price-high" name="sort" />
                            <label for="sort-price-high">Giá cao đến thấp</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" id="sort-name-az" name="sort" />
                            <label for="sort-name-az">Tên từ A → Z</label>
                        </div>
                        <div class="filter-option">
                            <input type="radio" id="sort-name-za" name="sort" />
                            <label for="sort-name-za">Tên từ Z → A</label>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Products Grid -->
            <section class="products-section">
                <div class="products-grid">
                    <!-- Product Card 1 -->
                    <a href="<?php echo esc_url(home_url('/singleproxy')); ?>" class="product-card">
                        <div class="product-logo-proxy">
                            <img
                                src="https://tradeproxy.vn/images/categories/9proxy.png"
                                alt="9proxy" />
                        </div>
                        <h3 class="product-name">9proxy</h3>
                        <p class="product-package">50IP Vô tận</p>
                        <div class="product-pricing">
                            <div class="product-price">
                                <span class="price-current">50,000đ</span>
                                <span class="price-rating">
                                    &asymp;
                                    <img src="https://tradeproxy.vn/images/icon/tether.png" class="w-4" alt="">
                                    3.2$
                                </span>
                            </div>
                            <div>
                                <span class="price-original">390,000đ</span>
                                <span class="price-discount">-88%</span>
                            </div>
                        </div>
                    </a>

                    <!-- Product Card 2 -->
                    <a href="<?php echo esc_url(home_url('/singleproxy')); ?>" class="product-card">
                        <div class="product-logo-proxy">
                            <img
                                src="https://tradeproxy.vn/images/categories/pia-proxy.png"
                                alt="Pia" />
                        </div>
                        <h3 class="product-name">Pia</h3>
                        <p class="product-package">100IP Vô tận</p>
                        <div class="product-pricing">
                            <div class="product-price">
                                <span class="price-current">140,000đ</span>
                                <span class="price-rating">
                                    &asymp;
                                    <img src="https://tradeproxy.vn/images/icon/tether.png" class="w-4" alt="">
                                    7$
                                </span>
                            </div>
                            <div>
                                <span class="price-original">850,000đ</span>
                                <span class="price-discount">-84%</span>
                            </div>
                        </div>
                    </a>

                    <!-- Product Card 3 -->
                    <a href="<?php echo esc_url(home_url('/singleproxy')); ?>" class="product-card">
                        <div class="product-logo-proxy">
                            <img
                                src="https://tradeproxy.vn/images/categories/abc-proxy.png"
                                alt="ABC" />
                        </div>
                        <h3 class="product-name">ABC</h3>
                        <p class="product-package">50IP Vô tận</p>
                        <div class="product-pricing">
                            <div class="product-price">
                                <span class="price-current">80,000đ</span>
                                <span class="price-rating">
                                    &asymp;
                                    <img src="https://tradeproxy.vn/images/icon/tether.png" class="w-4" alt="">
                                    5$
                                </span>
                            </div>
                            <div>
                                <span class="price-original">500,000đ</span>
                                <span class="price-discount">-84%</span>
                            </div>
                        </div>
                    </a>

                    <!-- Product Card 4 -->
                    <a href="<?php echo esc_url(home_url('/singleproxy')); ?>" class="product-card">
                        <div class="product-logo-proxy">
                            <img
                                src="https://tradeproxy.vn/images/categories/922-proxy.png"
                                alt="922" />
                        </div>
                        <h3 class="product-name">922</h3>
                        <p class="product-package">50IP Vô tận</p>
                        <div class="product-pricing">
                            <div class="product-price">
                                <span class="price-current">80,000đ</span>
                                <span class="price-rating">
                                    &asymp;
                                    <img src="https://tradeproxy.vn/images/icon/tether.png" class="w-4" alt="">
                                    4$
                                </span>
                            </div>
                            <div>
                                <span class="price-original">450,000đ</span>
                                <span class="price-discount">-82%</span>
                            </div>
                        </div>
                    </a>

                    <!-- Product Card 5 -->
                    <a href="<?php echo esc_url(home_url('/singleproxy')); ?>" class="product-card">
                        <div class="product-logo-proxy">
                            <img
                                src="https://tradeproxy.vn/images/categories/Luna.png"
                                alt="Luna" />
                        </div>
                        <h3 class="product-name">Luna Proxy</h3>
                        <p class="product-package">200 GB Data</p>
                        <div class="product-pricing">
                            <div class="product-price">
                                <span class="price-current">75,000đ</span>
                                <span class="price-rating">
                                    &asymp;
                                    <img src="https://tradeproxy.vn/images/icon/tether.png" class="w-4" alt="">
                                    3.5$
                                </span>
                            </div>
                            <div>
                                <span class="price-original">400,000đ</span>
                                <span class="price-discount">-81%</span>
                            </div>
                        </div>
                    </a>

                    <!-- Product Card 6 -->
                    <a href="<?php echo esc_url(home_url('/singleproxy')); ?>" class="product-card">
                        <div class="product-logo-proxy">
                            <img
                                src="https://tradeproxy.vn/images/categories/mobile-hop.png"
                                alt="922 Xoay" />
                        </div>
                        <h3 class="product-name">922 Xoay</h3>
                        <p class="product-package">100 GB Data</p>
                        <div class="product-pricing">
                            <div class="product-price">
                                <span class="price-current">50,000đ</span>
                                <span class="price-rating">
                                    &asymp;
                                    <img src="https://tradeproxy.vn/images/icon/tether.png" class="w-4" alt="">
                                    2.5$
                                </span>
                            </div>
                            <div>
                                <span class="price-original">300,000đ</span>
                                <span class="price-discount">-83%</span>
                            </div>
                        </div>
                    </a>

                    <!-- Product Card 7 -->
                    <a href="<?php echo esc_url(home_url('/singleproxy')); ?>" class="product-card">
                        <div class="product-logo-proxy">
                            <img
                                src="https://tradeproxy.vn/images/categories/abc-proxy-xoay.png"
                                alt="Mobile hop" />
                        </div>
                        <h3 class="product-name">Mobile hop</h3>
                        <p class="product-package">150 GB Data</p>
                        <div class="product-pricing">
                            <div class="product-price">
                                <span class="price-current">375,000đ</span>
                                <span class="price-rating">
                                    &asymp;
                                    <img src="https://tradeproxy.vn/images/icon/tether.png" class="w-4" alt="">
                                    15$
                                </span>
                            </div>
                            <div>
                                <span class="price-original">800,000đ</span>
                                <span class="price-discount">-53%</span>
                            </div>
                        </div>
                    </a>

                    <!-- Product Card 8 -->
                    <a href="<?php echo esc_url(home_url('/singleproxy')); ?>" class="product-card">
                        <div class="product-logo-proxy">
                            <img
                                src="https://tradeproxy.vn/images/categories/bee-proxy.webp"
                                alt="ABC Xoay" />
                        </div>
                        <h3 class="product-name">ABC Xoay</h3>
                        <p class="product-package">500 GB Data</p>
                        <div class="product-pricing">
                            <div class="product-price">
                                <span class="price-current">310,000đ</span>
                                <span class="price-rating">
                                    &asymp;
                                    <img src="https://tradeproxy.vn/images/icon/tether.png" class="w-4" alt="">
                                    12$
                                </span>
                            </div>
                            <div>
                                <span class="price-original">700,000đ</span>
                                <span class="price-discount">-56%</span>
                            </div>
                        </div>
                    </a>

                    <!-- Product Card 9 -->
                    <a href="<?php echo esc_url(home_url('/singleproxy')); ?>" class="product-card">
                        <div class="product-logo-proxy">
                            <img
                                src="https://tradeproxy.vn/images/categories/ipmars-proxy.png"
                                alt="Pyproxy Xoay" />
                        </div>
                        <h3 class="product-name">Pyproxy Xoay</h3>
                        <p class="product-package">100 GB Data</p>
                        <div class="product-pricing">
                            <div class="product-price">
                                <span class="price-current">105,000đ</span>
                                <span class="price-rating">
                                    &asymp;
                                    <img src="https://tradeproxy.vn/images/icon/tether.png" class="w-4" alt="">
                                    5$
                                </span>
                            </div>
                            <div>
                                <span class="price-original">400,000đ</span>
                                <span class="price-discount">-74%</span>
                            </div>
                        </div>
                    </a>

                    <!-- Product Card 10 -->
                    <a href="<?php echo esc_url(home_url('/singleproxy')); ?>" class="product-card">
                        <div class="product-logo-proxy">
                            <img
                                src="https://tradeproxy.vn/images/categories/cliproxy-xoay.png"
                                alt="Ip2world" />
                        </div>
                        <h3 class="product-name">Ip2world</h3>
                        <p class="product-package">200 GB Data</p>
                        <div class="product-pricing">
                            <div class="product-price">
                                <span class="price-current">275,000đ</span>
                                <span class="price-rating">
                                    &asymp;
                                    <img src="https://tradeproxy.vn/images/icon/tether.png" class="w-4" alt="">
                                    11$
                                </span>
                            </div>
                            <div>
                                <span class="price-original">600,000đ</span>
                                <span class="price-discount">-54%</span>
                            </div>
                        </div>
                    </a>

                    <!-- Product Card 11 -->
                    <a href="<?php echo esc_url(home_url('/singleproxy')); ?>" class="product-card">
                        <div class="product-logo-proxy">
                            <img
                                src="https://tradeproxy.vn/images/categories/iprocket-premium.png"
                                alt="iPwera" />
                        </div>
                        <h3 class="product-name">iPwera</h3>
                        <p class="product-package">50IP Vô tận</p>
                        <div class="product-pricing">
                            <div class="product-price">
                                <span class="price-current">80,000đ</span>
                                <span class="price-rating">
                                    &asymp;
                                    <img src="https://tradeproxy.vn/images/icon/tether.png" class="w-4" alt="">
                                    4$
                                </span>
                            </div>
                            <div>
                                <span class="price-original">450,000đ</span>
                                <span class="price-discount">-82%</span>
                            </div>
                        </div>
                    </a>

                    <!-- Product Card 12 -->
                    <a href="<?php echo esc_url(home_url('/singleproxy')); ?>" class="product-card">
                        <div class="product-logo-proxy">
                            <img
                                src="https://tradeproxy.vn/images/categories/tab-proxy.jpeg"
                                alt="Bee" />
                        </div>
                        <h3 class="product-name">Bee</h3>
                        <p class="product-package">100 GB Data</p>
                        <div class="product-pricing">
                            <div class="product-price">
                                <span class="price-current">55,000đ</span>
                                <span class="price-rating">
                                    &asymp;
                                    <img src="https://tradeproxy.vn/images/icon/tether.png" class="w-4" alt="">
                                    3$
                                </span>
                            </div>
                            <div>
                                <span class="price-original">350,000đ</span>
                                <span class="price-discount">-84%</span>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Pagination -->
                <div class="pagination">
                    <button disabled>
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <button class="active">1</button>
                    <button>2</button>
                    <button>3</button>
                    <button><i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </section>
        </div>
    </div>
</div>

<!-- Mobile Filter Modal -->
<div class="filter-modal" id="filterModal">
    <div class="filter-modal-content">
        <div class="filter-modal-header">
            <h2 class="filter-modal-title">Bộ lọc</h2>
            <div class="filter-modal-close" id="closeFilterModal">
                <i class="fa-solid fa-xmark"></i>
            </div>
        </div>

        <div class="filter-modal-body">
            <!-- Category Filter -->
            <div class="filter-group">
                <h3 class="filter-group-title">Chọn danh mục</h3>
                <div class="filter-options">
                    <div class="filter-option active">
                        <input
                            type="radio"
                            id="modal-cat-all"
                            name="modal-category"
                            checked />
                        <label for="modal-cat-all">
                            <i class="fa-solid fa-border-all"></i> Tất cả
                        </label>
                    </div>
                    <div class="filter-option">
                        <input
                            type="radio"
                            id="modal-cat-isp"
                            name="modal-category" />
                        <label for="modal-cat-isp">
                            <i class="fa-solid fa-house"></i> Proxy dân cư ISP
                        </label>
                    </div>
                    <div class="filter-option">
                        <input
                            type="radio"
                            id="modal-cat-traffic"
                            name="modal-category" />
                        <label for="modal-cat-traffic">
                            <i class="fa-solid fa-arrow-trend-up"></i> Proxy dân cư
                            traffic
                        </label>
                    </div>
                    <div class="filter-option">
                        <input
                            type="radio"
                            id="modal-cat-rotating"
                            name="modal-category" />
                        <label for="modal-cat-rotating">
                            <i class="fa-solid fa-rotate"></i> Proxy xoay dân cư
                        </label>
                    </div>
                    <div class="filter-option">
                        <input
                            type="radio"
                            id="modal-cat-static"
                            name="modal-category" />
                        <label for="modal-cat-static">
                            <i class="fa-solid fa-house-signal"></i> Proxy cố định
                        </label>
                    </div>
                    <div class="filter-option">
                        <input
                            type="radio"
                            id="modal-cat-unlimited"
                            name="modal-category" />
                        <label for="modal-cat-unlimited">
                            <i class="fa-solid fa-house-laptop"></i> Proxy Unlimited
                        </label>
                    </div>
                    <div class="filter-option">
                        <input
                            type="radio"
                            id="modal-cat-modem"
                            name="modal-category" />
                        <label for="modal-cat-modem">
                            <i class="fa-solid fa-tower-cell"></i> Modem proxy
                        </label>
                    </div>
                    <div class="filter-option">
                        <input
                            type="radio"
                            id="modal-cat-mmo"
                            name="modal-category" />
                        <label for="modal-cat-mmo">
                            <i class="fa-regular fa-circle-dot"></i> Phần mềm MMO
                        </label>
                    </div>
                </div>
            </div>

            <!-- Sort Filter -->
            <div class="filter-group">
                <h3 class="filter-group-title">Sắp xếp theo</h3>
                <div class="filter-options">
                    <div class="filter-option active">
                        <input
                            type="radio"
                            id="modal-sort-bestseller"
                            name="modal-sort"
                            checked />
                        <label for="modal-sort-bestseller">Bán chạy nhất</label>
                    </div>
                    <div class="filter-option">
                        <input
                            type="radio"
                            id="modal-sort-latest"
                            name="modal-sort" />
                        <label for="modal-sort-latest">Mới cập nhật</label>
                    </div>
                    <div class="filter-option">
                        <input
                            type="radio"
                            id="modal-sort-price-low"
                            name="modal-sort" />
                        <label for="modal-sort-price-low">Giá thấp đến cao</label>
                    </div>
                    <div class="filter-option">
                        <input
                            type="radio"
                            id="modal-sort-price-high"
                            name="modal-sort" />
                        <label for="modal-sort-price-high">Giá cao đến thấp</label>
                    </div>
                    <div class="filter-option">
                        <input
                            type="radio"
                            id="modal-sort-name-az"
                            name="modal-sort" />
                        <label for="modal-sort-name-az">Tên từ A → Z</label>
                    </div>
                    <div class="filter-option">
                        <input
                            type="radio"
                            id="modal-sort-name-za"
                            name="modal-sort" />
                        <label for="modal-sort-name-za">Tên từ Z → A</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="filter-modal-footer">
            <button class="btn-cancel" id="cancelFilter">Hủy</button>
            <button class="btn-apply" id="applyFilter">Áp dụng bộ lọc</button>
        </div>
    </div>
</div>

<?php get_footer(); ?>
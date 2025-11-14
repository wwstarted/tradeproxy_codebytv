<?php
/*
Template Name: Blog Page
*/
?>
<?php get_header(); ?>

<!-- Hero Banner Section -->
<section class="hero-banner">
    <div class="blog-container">
        <h1 class="hero-title-blog">Kiến thức MMO</h1>
        <p class="hero-summary">Chia sẻ các tips thuật, kiến thức, tut trong ngành Make Money Online!</p>
    </div>
</section>

<!-- Main Content Section -->
<section class="main-content">
    <div class="blog-container">
        <div class="content-wrapper">
            <!-- Left Sidebar -->
            <aside class="left-sidebar">
                <!-- Search Form -->
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Tìm kiếm" class="search-input">
                    <button type="button" class="search-btn">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path
                                d="M7 13C10.3137 13 13 10.3137 13 7C13 3.68629 10.3137 1 7 1C3.68629 1 1 3.68629 1 7C1 10.3137 3.68629 13 7 13Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M15 15L11.5 11.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>

                <!-- Categories List -->
                <div class="categories-box">
                    <h3 class="categories-title">Danh mục</h3>
                    <ul class="categories-list">
                        <li class="category-item active" data-category="all">
                            <a href="#" class="category-link">Bài viết mới nhất</a>
                        </li>
                        <li class="category-item" data-category="ads">
                            <a href="#" class="category-link">Kiến thức ADS</a>
                        </li>
                        <li class="category-item" data-category="checkout">
                            <a href="#" class="category-link">Kiến thức checkout</a>
                        </li>
                        <li class="category-item" data-category="software">
                            <a href="#" class="category-link">Hướng dẫn phần mềm</a>
                        </li>
                        <li class="category-item" data-category="cdkey">
                            <a href="#" class="category-link">Hướng dẫn nạp CDkey</a>
                        </li>
                    </ul>
                </div>
            </aside>

            <!-- Right Content - Blog Posts List -->
            <main class="right-content">
                <div class="posts-list" id="postsList">
                    <!-- Post Card 1 -->
                    <article class="post-card" data-category="software">
                        <div class="post-thumbnail">
                            <img src="https://tradeproxy.vn/images/blogs/huong-dan-nap-gift-va-su-dung-global-proxies.webp"
                                alt="Hướng dẫn nạp Gift">
                        </div>
                        <time class="post-date">26 tháng 9</time>
                        <div class="post-content">
                            <a href="#" class="post-title">Hướng dẫn nạp Gift và sử dụng Global Proxies</a>
                            <p class="post-summary">Bài viết hướng dẫn chi tiết các bước nạp gift quà tặng và mua
                                gói proxy 4G/5G trên Global Proxies, từ nhập mã, chọn gói dịch vụ cho đến kích hoạt
                                và sử dụng proxy.</p>
                            <a href="<?php echo esc_url(home_url('/single-post')) ?>" class="post-link">Đọc tiếp →</a>
                        </div>
                    </article>

                    <!-- Post Card 2 -->
                    <article class="post-card" data-category="software">
                        <div class="post-thumbnail">
                            <img src="https://tradeproxy.vn/images/blogs/pionlogin-phan-mem-gia-lap-trinh-duyet-ket-hop-tu-dong-hoa-tot-nhat.webp"
                                alt="Hướng dẫn PionLogin">
                        </div>
                        <time class="post-date">15 tháng 8</time>
                        <div class="post-content">
                            <a href="#" class="post-title">Hướng dẫn sử dụng PionLogin để quản lý profile</a>
                            <p class="post-summary">Chi tiết cách cài đặt và sử dụng phần mềm PionLogin để tạo và
                                quản lý nhiều profile trình duyệt an toàn, tránh bị phát hiện khi làm việc với nhiều
                                tài khoản.</p>
                            <a href="<?php echo esc_url(home_url('/single-post')) ?>" class="post-link">Đọc tiếp →</a>
                        </div>
                    </article>

                    <!-- Post Card 3 -->
                    <article class="post-card" data-category="ads">
                        <div class="post-thumbnail">
                            <img src="https://tradeproxy.vn/images/blogs/omocaptcha-nen-tang-tu-dong-giai-captcha-hang-dau-cho-lap-trinh-vien-va-doanh-nghiep.webp"
                                alt="Facebook ADS">
                        </div>
                        <time class="post-date">10 tháng 8</time>
                        <div class="post-content">
                            <a href="#" class="post-title">Kiến thức cơ bản về Facebook ADS cho người mới</a>
                            <p class="post-summary">Tổng hợp các kiến thức nền tảng về quảng cáo Facebook, từ thiết
                                lập tài khoản, tạo chiến dịch, đến tối ưu chi phí và đo lường hiệu quả quảng cáo.
                            </p>
                            <a href="<?php echo esc_url(home_url('/single-post')) ?>" class="post-link">Đọc tiếp →</a>
                        </div>
                    </article>
                </div>

                <!-- No Results Message -->
                <div class="no-results" id="noResults" style="display: none;">
                    <p>Không tìm thấy bài viết phù hợp.</p>
                </div>

                <!-- Pagination -->
                <div class="pagination-blog" id="pagination-blog"
                    style="display: flex; justify-content: center; margin-top: 20px;">
                    <button id="prevBtn" class="pagination-btn">« Trước</button>
                    <div id="paginationNumbers" class="pagination-numbers"
                        style="display: flex; gap: 6px; margin: 0 10px;"></div>
                    <button id="nextBtn" class="pagination-btn">Sau »</button>
                </div>
            </main>

        </div>
    </div>
</section>

<?php get_footer(); ?>
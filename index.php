<?php get_header(); ?>

<!-- Main -->
<div class="main-content bg-[#f9fdff]">

    <!-- hero section -->
    <div class="m-auto max-w-[80%]">
        <div class="hero-title text-2xl font-bold text-center mb-5">
            Nơi cung cấp Proxy của các hãng lớn với
            <div class="text-[#007BF3]">mức chiết khấu đại lý giá rẻ</div>
        </div>
        <div class="hero-image">
            <img src="https://tradeproxy.vn/images/background/tradeproxy-phan-phoi-piaproxy-922-abc-gia-dai-ly.webp"
                alt="" />
        </div>
    </div>

    <!-- content section -->
    <div class="m-auto max-w-[80%] pb-10">
        <!-- list proxy section  -->
        <div class="pt-10 pb-10">
            <div class="container list-proxy hide-scrollbar pb-10">
                <div class="text-2xl font-bold mb-5">
                    Danh sách các loại proxy
                    <span class="text-[#007BF3]">tốt nhất</span>
                </div>
                <div id="scrollContainer"
                    class="flex overflow-x-auto whitespace-nowrap space-x-4 hide-scrollbar cursor-grab select-none">
                    <button
                        class="pt-1 pb-1 pl-3 pr-3 bg-[#007BF3] text-lg text-white rounded-xl border-1 flex-shrink-0">
                        tất cả
                    </button>

                    <button class="flex pt-1 pb-1 pl-3 pr-3 border-1 rounded-xl border-[#007BF3] flex-shrink-0">
                        <img src="https://tradeproxy.vn/images/categories/9proxy(32x32).png" alt="" class="w-7 h-7" />
                        <p class="capitalize ml-2">9proxy</p>
                    </button>

                    <button class="flex pt-1 pb-1 pl-3 pr-3 border-1 rounded-xl border-[#007BF3] flex-shrink-0">
                        <img src="https://tradeproxy.vn/images/categories/pia-proxy(32x32).png" alt=""
                            class="w-7 h-7" />
                        <p class="capitalize ml-2">pia</p>
                    </button>

                    <button class="flex pt-1 pb-1 pl-3 pr-3 border-1 rounded-xl border-[#007BF3] flex-shrink-0">
                        <img src="https://tradeproxy.vn/images/categories/922-all(32x32).png" alt="" class="w-7 h-7" />
                        <p class="capitalize ml-2">922</p>
                    </button>

                    <button class="flex pt-1 pb-1 pl-3 pr-3 border-1 rounded-xl border-[#007BF3] flex-shrink-0">
                        <img src="https://tradeproxy.vn/images/categories/abc-all(32x32).png" alt="" class="w-7 h-7" />
                        <p class="capitalize ml-2">acb</p>
                    </button>

                    <button class="flex pt-1 pb-1 pl-3 pr-3 border-1 rounded-xl border-[#007BF3] flex-shrink-0">
                        <img src="https://tradeproxy.vn/images/categories/pyproxy-all(32x32).png" alt=""
                            class="w-7 h-7" />
                        <p class="capitalize ml-2">pyproxy</p>
                    </button>

                    <button class="flex pt-1 pb-1 pl-3 pr-3 border-1 rounded-xl border-[#007BF3] flex-shrink-0">
                        <img src="https://tradeproxy.vn/images/categories/global-proxies(32x32).webp" alt=""
                            class="w-7 h-7" />
                        <p class="capitalize ml-2">global proxies</p>
                    </button>

                    <button class="flex pt-1 pb-1 pl-3 pr-3 border-1 rounded-xl border-[#007BF3] flex-shrink-0">
                        <img src="https://tradeproxy.vn/images/categories/global-proxies(32x32).webp" alt=""
                            class="w-7 h-7" />
                        <p class="capitalize ml-2">global proxies 2</p>
                    </button>

                    <button class="flex pt-1 pb-1 pl-3 pr-3 border-1 rounded-xl border-[#007BF3] flex-shrink-0">
                        <img src="https://tradeproxy.vn/images/categories/global-proxies(32x32).webp" alt=""
                            class="w-7 h-7" />
                        <p class="capitalize ml-2">global proxies 3</p>
                    </button>

                    <button class="flex pt-1 pb-1 pl-3 pr-3 border-1 rounded-xl border-[#007BF3] flex-shrink-0">
                        <img src="https://tradeproxy.vn/images/categories/global-proxies(32x32).webp" alt=""
                            class="w-7 h-7" />
                        <p class="capitalize ml-2">global proxies 4</p>
                    </button>

                    <button class="flex pt-1 pb-1 pl-3 pr-3 border-1 rounded-xl border-[#007BF3] flex-shrink-0">
                        <img src="https://tradeproxy.vn/images/categories/global-proxies(32x32).webp" alt=""
                            class="w-7 h-7" />
                        <p class="capitalize ml-2">global proxies 5</p>
                    </button>
                </div>
            </div>
            <div class="proxy-table-container">
                <!-- Header (ẩn trên mobile) -->
                <div class="proxy-table-header">
                    <div>Tên dịch vụ</div>
                    <div style="text-align: center">Số lượng</div>
                    <div style="text-align: center">Hiện có</div>
                    <div style="text-align: center">Giá</div>
                    <div></div>
                </div>

                <!-- Search Bar -->
                <div class="proxy-search-wrapper">
                    <i class="fa-solid fa-magnifying-glass proxy-search-icon"></i>
                    <input type="text" placeholder="Search..." class="proxy-search-input" />
                </div>

                <!-- Table Body -->
                <div class="proxy-table-body">
                    <!-- Row 1: 9proxy -->
                    <div class="proxy-row">
                        <div class="proxy-info">
                            <div class="proxy-logo">
                                <img src="https://tradeproxy.vn/images/logo/9proxy.png" alt="9proxy"
                                    style="width: 100%; height: 100%; object-fit: contain" />
                            </div>
                            <div class="proxy-details">
                                <h3>9proxy</h3>
                                <p>Proxy ISP dân cư, Hỗ trợ giao thức Http, Socks5</p>
                            </div>
                        </div>
                        <div class="proxy-quantity">
                            <select class="proxy-select">
                                <option>50ip</option>
                                <option>100ip</option>
                                <option>200ip</option>
                                <option>500ip</option>
                            </select>
                        </div>
                        <div class="proxy-status">
                            <span class="proxy-badge available">Có sẵn</span>
                        </div>
                        <div class="proxy-price">
                            <div class="proxy-price-wrapper">
                                <div class="proxy-price-main">50,000đ</div>
                                <div class="proxy-price-sub">
                                    <i class="fa-regular fa-clock"></i>
                                    <span>3.2$</span>
                                </div>
                            </div>
                        </div>
                        <div class="proxy-actions">
                            <button class="proxy-btn-cart">
                                <i class="fa-solid fa-cart-shopping"></i>
                            </button>
                            <button class="proxy-btn-buy">Mua ngay</button>
                        </div>
                    </div>
                    <!-- Row 1: 9proxy -->
                    <div class="proxy-row">
                        <div class="proxy-info">
                            <div class="proxy-logo">
                                <img src="https://tradeproxy.vn/images/logo/9proxy.png" alt="9proxy"
                                    style="width: 100%; height: 100%; object-fit: contain" />
                            </div>
                            <div class="proxy-details">
                                <h3>9proxy</h3>
                                <p>Proxy ISP dân cư, Hỗ trợ giao thức Http, Socks5</p>
                            </div>
                        </div>
                        <div class="proxy-quantity">
                            <select class="proxy-select">
                                <option>50ip</option>
                                <option>100ip</option>
                                <option>200ip</option>
                                <option>500ip</option>
                            </select>
                        </div>
                        <div class="proxy-status">
                            <span class="proxy-badge available">Có sẵn</span>
                        </div>
                        <div class="proxy-price">
                            <div class="proxy-price-wrapper">
                                <div class="proxy-price-main">50,000đ</div>
                                <div class="proxy-price-sub">
                                    <i class="fa-regular fa-clock"></i>
                                    <span>3.2$</span>
                                </div>
                            </div>
                        </div>
                        <div class="proxy-actions">
                            <button class="proxy-btn-cart">
                                <i class="fa-solid fa-cart-shopping"></i>
                            </button>
                            <button class="proxy-btn-buy">Mua ngay</button>
                        </div>
                    </div>
                    <!-- Row 1: 9proxy -->
                    <div class="proxy-row">
                        <div class="proxy-info">
                            <div class="proxy-logo">
                                <img src="https://tradeproxy.vn/images/logo/9proxy.png" alt="9proxy"
                                    style="width: 100%; height: 100%; object-fit: contain" />
                            </div>
                            <div class="proxy-details">
                                <h3>9proxy</h3>
                                <p>Proxy ISP dân cư, Hỗ trợ giao thức Http, Socks5</p>
                            </div>
                        </div>
                        <div class="proxy-quantity">
                            <select class="proxy-select">
                                <option>50ip</option>
                                <option>100ip</option>
                                <option>200ip</option>
                                <option>500ip</option>
                            </select>
                        </div>
                        <div class="proxy-status">
                            <span class="proxy-badge available">Có sẵn</span>
                        </div>
                        <div class="proxy-price">
                            <div class="proxy-price-wrapper">
                                <div class="proxy-price-main">50,000đ</div>
                                <div class="proxy-price-sub">
                                    <i class="fa-regular fa-clock"></i>
                                    <span>3.2$</span>
                                </div>
                            </div>
                        </div>
                        <div class="proxy-actions">
                            <button class="proxy-btn-cart">
                                <i class="fa-solid fa-cart-shopping"></i>
                            </button>
                            <button class="proxy-btn-buy">Mua ngay</button>
                        </div>
                    </div>
                    <!-- Row 1: 9proxy -->
                    <div class="proxy-row">
                        <div class="proxy-info">
                            <div class="proxy-logo">
                                <img src="https://tradeproxy.vn/images/logo/9proxy.png" alt="9proxy"
                                    style="width: 100%; height: 100%; object-fit: contain" />
                            </div>
                            <div class="proxy-details">
                                <h3>9proxy</h3>
                                <p>Proxy ISP dân cư, Hỗ trợ giao thức Http, Socks5</p>
                            </div>
                        </div>
                        <div class="proxy-quantity">
                            <select class="proxy-select">
                                <option>50ip</option>
                                <option>100ip</option>
                                <option>200ip</option>
                                <option>500ip</option>
                            </select>
                        </div>
                        <div class="proxy-status">
                            <span class="proxy-badge available">Có sẵn</span>
                        </div>
                        <div class="proxy-price">
                            <div class="proxy-price-wrapper">
                                <div class="proxy-price-main">50,000đ</div>
                                <div class="proxy-price-sub">
                                    <i class="fa-regular fa-clock"></i>
                                    <span>3.2$</span>
                                </div>
                            </div>
                        </div>
                        <div class="proxy-actions">
                            <button class="proxy-btn-cart">
                                <i class="fa-solid fa-cart-shopping"></i>
                            </button>
                            <button class="proxy-btn-buy">Mua ngay</button>
                        </div>
                    </div>
                    <!-- Row 1: 9proxy -->
                    <div class="proxy-row">
                        <div class="proxy-info">
                            <div class="proxy-logo">
                                <img src="https://tradeproxy.vn/images/logo/9proxy.png" alt="9proxy"
                                    style="width: 100%; height: 100%; object-fit: contain" />
                            </div>
                            <div class="proxy-details">
                                <h3>9proxy</h3>
                                <p>Proxy ISP dân cư, Hỗ trợ giao thức Http, Socks5</p>
                            </div>
                        </div>
                        <div class="proxy-quantity">
                            <select class="proxy-select">
                                <option>50ip</option>
                                <option>100ip</option>
                                <option>200ip</option>
                                <option>500ip</option>
                            </select>
                        </div>
                        <div class="proxy-status">
                            <span class="proxy-badge available">Có sẵn</span>
                        </div>
                        <div class="proxy-price">
                            <div class="proxy-price-wrapper">
                                <div class="proxy-price-main">50,000đ</div>
                                <div class="proxy-price-sub">
                                    <i class="fa-regular fa-clock"></i>
                                    <span>3.2$</span>
                                </div>
                            </div>
                        </div>
                        <div class="proxy-actions">
                            <button class="proxy-btn-cart">
                                <i class="fa-solid fa-cart-shopping"></i>
                            </button>
                            <button class="proxy-btn-buy">Mua ngay</button>
                        </div>
                    </div>
                    <!-- Row 1: 9proxy -->
                    <div class="proxy-row">
                        <div class="proxy-info">
                            <div class="proxy-logo">
                                <img src="https://tradeproxy.vn/images/logo/9proxy.png" alt="9proxy"
                                    style="width: 100%; height: 100%; object-fit: contain" />
                            </div>
                            <div class="proxy-details">
                                <h3>9proxy</h3>
                                <p>Proxy ISP dân cư, Hỗ trợ giao thức Http, Socks5</p>
                            </div>
                        </div>
                        <div class="proxy-quantity">
                            <select class="proxy-select">
                                <option>50ip</option>
                                <option>100ip</option>
                                <option>200ip</option>
                                <option>500ip</option>
                            </select>
                        </div>
                        <div class="proxy-status">
                            <span class="proxy-badge available">Có sẵn</span>
                        </div>
                        <div class="proxy-price">
                            <div class="proxy-price-wrapper">
                                <div class="proxy-price-main">50,000đ</div>
                                <div class="proxy-price-sub">
                                    <i class="fa-regular fa-clock"></i>
                                    <span>3.2$</span>
                                </div>
                            </div>
                        </div>
                        <div class="proxy-actions">
                            <button class="proxy-btn-cart">
                                <i class="fa-solid fa-cart-shopping"></i>
                            </button>
                            <button class="proxy-btn-buy">Mua ngay</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Guides list -->
        <div class="container pb-10">
            <div class="Guides-title text-center">
                <div class="text-2xl font-bold">
                    <span class="text-[#007BF3]">Trade Proxy</span> hoạt động như
                    thế nào?
                </div>
                <p class="font-light">
                    Cung cấp cho bạn một mức giá hợp lý tại các đối tác proxy thông
                    dụng với hình thức thanh toán dễ dàng và tiết kiệm nhất
                </p>
            </div>

            <div class="features">
                <div class="feature-card">
                    <div class="icon-container">
                        <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="12" y="12" width="24" height="32" rx="3" stroke="#0ea5e9" stroke-width="2.5"
                                fill="#e0f2fe" />
                            <circle cx="24" cy="50" r="3" fill="#0ea5e9" />
                            <rect x="18" y="16" width="12" height="2" rx="1" fill="#0ea5e9" />
                            <rect x="18" y="22" width="12" height="2" rx="1" fill="#0ea5e9" />
                            <rect x="18" y="28" width="8" height="2" rx="1" fill="#0ea5e9" />
                            <circle cx="46" cy="18" r="8" fill="#0ea5e9" />
                            <path d="M43 18l2 2 4-4" stroke="white" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <circle cx="46" cy="38" r="8" fill="#0ea5e9" />
                            <path d="M43 38l2 2 4-4" stroke="white" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h3>Dễ dàng sử dụng</h3>
                    <p>
                        Bạn chỉ cần tạo tài khoản, lựa chọn nhà cung cấp Proxy mà bạn
                        mong muốn, tiếp tục đến phần giỏ hàng và thực hiện thanh toán.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="icon-container">
                        <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="16" y="20" width="32" height="10" rx="2" stroke="#0ea5e9" stroke-width="2.5"
                                fill="#e0f2fe" />
                            <rect x="16" y="34" width="32" height="10" rx="2" stroke="#0ea5e9" stroke-width="2.5"
                                fill="#e0f2fe" />
                            <line x1="20" y1="25" x2="28" y2="25" stroke="#0ea5e9" stroke-width="2"
                                stroke-linecap="round" />
                            <line x1="20" y1="39" x2="28" y2="39" stroke="#0ea5e9" stroke-width="2"
                                stroke-linecap="round" />
                            <circle cx="52" cy="16" r="8" fill="#0ea5e9" />
                            <path d="M49 16l2 2 4-4" stroke="white" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <line x1="24" y1="20" x2="24" y2="44" stroke="#0ea5e9" stroke-width="2"
                                stroke-linecap="round" />
                            <line x1="20" y1="48" x2="44" y2="48" stroke="#0ea5e9" stroke-width="2"
                                stroke-linecap="round" />
                        </svg>
                    </div>
                    <h3>Thủ tục nhanh chóng</h3>
                    <p>
                        Việc kích hoạt Proxy bằng CDkey sẽ tối ưu hoá thời gian lps và
                        giảm rủi ro thông qua chúng tôi.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="icon-container">
                        <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="14" y="16" width="36" height="28" rx="3" stroke="#0ea5e9" stroke-width="2.5"
                                fill="#e0f2fe" />
                            <rect x="20" y="22" width="24" height="16" rx="2" fill="white" stroke="#0ea5e9"
                                stroke-width="2" />
                            <circle cx="32" cy="30" r="6" fill="#0ea5e9" />
                            <text x="32" y="34" text-anchor="middle" font-size="8" font-weight="bold" fill="white">
                                IP
                            </text>
                            <circle cx="52" cy="12" r="8" fill="#0ea5e9" />
                            <path d="M49 12l2 2 4-4" stroke="white" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <circle cx="26" cy="50" r="3" fill="#64748b" />
                            <circle cx="32" cy="50" r="3" fill="#64748b" />
                            <circle cx="38" cy="50" r="3" fill="#64748b" />
                        </svg>
                    </div>
                    <h3>Địa chỉ IP đa dạng</h3>
                    <p>
                        Với hơn 190 quốc gia và hơn 2 nghìn trung tâm dữ liệu, cung
                        cấp cho bạn một lựa chọn IP đa dạng.
                    </p>
                </div>
            </div>
        </div>

        <!-- Commit section -->
        <div class="container pb-10">
            <div class="commit-section">
                <div class="commit-left-section">
                    <img src="https://tradeproxy.vn/images/background/banner_server.webp" alt="Trade Proxy Server" />
                </div>

                <div class="commit-right-section">
                    <div class="commit-header">
                        <h1 class="commit-title">
                            Cam kết <span class="commit-highlight">giá tốt nhất</span><br />thị trường
                        </h1>
                        <p class="commit-subtitle">
                            Tối ưu ngân sách, tiết kiệm tối đa. Trade Proxy cam kết mức
                            giá chỉ bằng 1/3 giá thị trường với chế độ bảo hành chất
                            lượng.
                        </p>
                    </div>

                    <div class="commit-feature">
                        <div class="commit-feature-icon">
                            <img src="https://tradeproxy.vn/images/img/world.svg" alt="" />
                        </div>
                        <div class="commit-feature-content">
                            <h3>Băng thông tốc độ cao</h3>
                            <p>
                                Tốc độ thời gian thực có thể đạt đến 1M-5M/s,99% đảm bảo
                                tỷ lệ thành công cho các hoạt động thu thập dữ liệu. Hỗ
                                trợ tối đa nhu cầu của bạn.
                            </p>
                        </div>
                    </div>

                    <div class="commit-feature">
                        <div class="commit-feature-icon">
                            <img src="https://tradeproxy.vn/images/img/secure.svg" alt="" />
                        </div>
                        <div class="commit-feature-content">
                            <h3>An toàn, ổn định</h3>
                            <p>
                                Nhân IP đàn cư thực có tính ẩn danh cao và sự an toàn về
                                quyền riêng tư được bảo vệ hoàn toàn. Mỗi trường mạng thực
                                sẽ không bị mua lại ở bất kỳ thời điểm nào.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing plans proxies section -->
        <div class="container pb-10">
            <h1 class="Plans-title">
                Thuê Proxy với giá ưu đãi nhất tại
                <span class="highlight">Trade Proxy</span>
            </h1>

            <div class="carousel-container">
                <button class="nav-button prev" id="prevBtn">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>

                <div class="cards-wrapper" id="cardsWrapper">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-logo">
                                <img src="https://tradeproxy.vn/images/categories/abc-proxy.png" alt="" />
                            </div>
                            <h2 class="card-title">ABC proxy</h2>
                            <p class="card-description line-clamp">
                                Tốc độ truyền tải mạnh, số lượng ip đa dạng.
                                <strong>Mua ABC proxy</strong> ngay!
                            </p>
                        </div>
                        <div class="card-price">1,290đ/ IP</div>
                        <div class="card-body">
                            <ul class="features-plans">
                                <li>Tốc độ kết nối nhanh</li>
                                <li>Hỗ trợ Http, Socks5...</li>
                                <li>Window,Mac,Linux,Android</li>
                            </ul>
                            <button class="btn-buy">Mua ngay</button>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-logo">
                                <img src="https://tradeproxy.vn/images/categories/abc-proxy.png" alt="" />
                            </div>
                            <h2 class="card-title">ABC proxy</h2>
                            <p class="card-description line-clamp">
                                Tốc độ truyền tải mạnh, số lượng ip đa dạng.
                                <strong>Mua ABC proxy</strong> ngay!
                            </p>
                        </div>
                        <div class="card-price">1,290đ/ IP</div>
                        <div class="card-body">
                            <ul class="features-plans">
                                <li>Tốc độ kết nối nhanh</li>
                                <li>Hỗ trợ Http, Socks5...</li>
                                <li>Window,Mac,Linux,Android</li>
                            </ul>
                            <button class="btn-buy">Mua ngay</button>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-logo">
                                <img src="https://tradeproxy.vn/images/categories/abc-proxy.png" alt="" />
                            </div>
                            <h2 class="card-title">ABC proxy</h2>
                            <p class="card-description line-clamp">
                                Tốc độ truyền tải mạnh, số lượng ip đa dạng.
                                <strong>Mua ABC proxy</strong> ngay!
                            </p>
                        </div>
                        <div class="card-price">1,290đ/ IP</div>
                        <div class="card-body">
                            <ul class="features-plans">
                                <li>Tốc độ kết nối nhanh</li>
                                <li>Hỗ trợ Http, Socks5...</li>
                                <li>Window,Mac,Linux,Android</li>
                            </ul>
                            <button class="btn-buy">Mua ngay</button>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-logo">
                                <img src="https://tradeproxy.vn/images/categories/abc-proxy.png" alt="" />
                            </div>
                            <h2 class="card-title">ABC proxy</h2>
                            <p class="card-description line-clamp">
                                Tốc độ truyền tải mạnh, số lượng ip đa dạng.
                                <strong>Mua ABC proxy</strong> ngay!
                            </p>
                        </div>
                        <div class="card-price">1,290đ/ IP</div>
                        <div class="card-body">
                            <ul class="features-plans">
                                <li>Tốc độ kết nối nhanh</li>
                                <li>Hỗ trợ Http, Socks5...</li>
                                <li>Window,Mac,Linux,Android</li>
                            </ul>
                            <button class="btn-buy">Mua ngay</button>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="card-logo">
                                <img src="https://tradeproxy.vn/images/categories/abc-proxy.png" alt="" />
                            </div>
                            <h2 class="card-title">ABC proxy</h2>
                            <p class="card-description line-clamp">
                                Tốc độ truyền tải mạnh, số lượng ip đa dạng.
                                <strong>Mua ABC proxy</strong> ngay!
                            </p>
                        </div>
                        <div class="card-price">1,290đ/ IP</div>
                        <div class="card-body">
                            <ul class="features-plans">
                                <li>Tốc độ kết nối nhanh</li>
                                <li>Hỗ trợ Http, Socks5...</li>
                                <li>Window,Mac,Linux,Android</li>
                            </ul>
                            <button class="btn-buy">Mua ngay</button>
                        </div>
                    </div>
                </div>

                <button class="nav-button next" id="nextBtn">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Proxies in world -->
        <div class="proxy-world-section container">
            <div class="world-header">
                <h2 class="world-title">
                    IP Proxy dân cư từ khắp nơi trên thế giới
                </h2>
                <p class="world-description">
                    TradeProxy cung cấp đại IP proxy đa dạng, phủ sóng hơn 200 quốc
                    gia và khu vực trên toàn cầu, nhằm hỗ trợ ẩn danh an toàn một
                    cách tối ưu.
                </p>
            </div>

            <div class="countries-grid">
                <!-- Row 1 -->
                <div class="country-card featured">
                    <div class="country-flag">
                        <img src="https://flagcdn.com/w80/us.png" alt="Hoa Kỳ" />
                    </div>
                    <div class="country-info">
                        <div class="country-name">Hoa kỳ</div>
                        <div class="country-ips">54,134,562 Ips</div>
                    </div>
                </div>

                <div class="country-card featured">
                    <div class="country-flag">
                        <img src="https://flagcdn.com/w80/gb.png" alt="Vương Quốc Anh" />
                    </div>
                    <div class="country-info">
                        <div class="country-name">Vương Quốc Anh</div>
                        <div class="country-ips">1,513,456 Ips</div>
                    </div>
                </div>

                <div class="country-card featured">
                    <div class="country-flag">
                        <img src="https://flagcdn.com/w80/vn.png" alt="Việt Nam" />
                    </div>
                    <div class="country-info">
                        <div class="country-name">Việt Nam</div>
                        <div class="country-ips">2,896,312 Ips</div>
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="country-card">
                    <div class="country-flag">
                        <img src="https://flagcdn.com/w80/jp.png" alt="Nhật Bản" />
                    </div>
                    <div class="country-info">
                        <div class="country-name">Nhật Bản</div>
                        <div class="country-ips">3,662,712 Ips</div>
                    </div>
                </div>

                <div class="country-card">
                    <div class="country-flag">
                        <img src="https://flagcdn.com/w80/tr.png" alt="Thổ Nhĩ Kỳ" />
                    </div>
                    <div class="country-info">
                        <div class="country-name">Thổ Nhĩ Kỳ</div>
                        <div class="country-ips">6,633,262 Ips</div>
                    </div>
                </div>

                <div class="country-card">
                    <div class="country-flag">
                        <img src="https://flagcdn.com/w80/cn.png" alt="Hồng Kông" />
                    </div>
                    <div class="country-info">
                        <div class="country-name">Hồng Kông</div>
                        <div class="country-ips">119,312 Ips</div>
                    </div>
                </div>

                <div class="country-card">
                    <div class="country-flag">
                        <img src="https://flagcdn.com/w80/de.png" alt="Nước Đức" />
                    </div>
                    <div class="country-info">
                        <div class="country-name">Nước Đức</div>
                        <div class="country-ips">4,982,127 Ips</div>
                    </div>
                </div>

                <!-- Row 3 -->
                <div class="country-card">
                    <div class="country-flag">
                        <img src="https://flagcdn.com/w80/ae.png" alt="UAE" />
                    </div>
                    <div class="country-info">
                        <div class="country-name">UAE</div>
                        <div class="country-ips">5,783,872 Ips</div>
                    </div>
                </div>

                <div class="country-card">
                    <div class="country-flag">
                        <img src="https://flagcdn.com/w80/br.png" alt="Brazil" />
                    </div>
                    <div class="country-info">
                        <div class="country-name">Brazil</div>
                        <div class="country-ips">54,134,562 Ips</div>
                    </div>
                </div>

                <div class="country-card">
                    <div class="country-flag">
                        <img src="https://flagcdn.com/w80/fr.png" alt="Pháp" />
                    </div>
                    <div class="country-info">
                        <div class="country-name">Pháp</div>
                        <div class="country-ips">2,714,754 Ips</div>
                    </div>
                </div>

                <div class="country-card">
                    <div class="country-flag">
                        <img src="https://flagcdn.com/w80/id.png" alt="Indonesia" />
                    </div>
                    <div class="country-info">
                        <div class="country-name">Indonesia</div>
                        <div class="country-ips">5,002,418 Ips</div>
                    </div>
                </div>
            </div>

            <button class="view-all-btn">Xem tất cả</button>
        </div>
    </div>

    <!-- Partners section -->
    <div class="partners-section pb-10">
        <h2 class="partners-title">
            Đối tác của <span class="highlight">Trade Proxy</span>
        </h2>

        <!-- Row 1: Trượt từ trái sang phải -->
        <div class="partners-carousel">
            <div class="partners-row partners-row-1">
                <!-- Duplicate để tạo hiệu ứng loop liền mạch -->
                <a href="<?php echo esc_url(home_url('/detail-provider')) ?>" class="partner-card">
                    <div class="partner-logo">
                        <img src="https://tradeproxy.vn/images/logo/carousel/nest_logo.png" alt="Mulogin" />
                    </div>
                    <div class="partner-name">Mulogin</div>
                </a>

                <a href="<?php echo esc_url(home_url('/detail-provider')) ?>" class="partner-card">
                    <div class="partner-logo">
                        <img src="https://tradeproxy.vn/images/partners/pionlogin-logo.webp" alt="NestBrowser" />
                    </div>
                    <div class="partner-name">NestBrowser</div>
                </a>

                <a href="<?php echo esc_url(home_url('/detail-provider')) ?>" class="partner-card">
                    <div class="partner-logo">
                        <img src="https://tradeproxy.vn/images/partners/omocaptcha-logo.webp" alt="TigerAi Browser" />
                    </div>
                    <div class="partner-name">TigerAi Browser</div>
                </a>

                <a href="<?php echo esc_url(home_url('/detail-provider')) ?>" class="partner-card">
                    <div class="partner-logo">
                        <img src="https://tradeproxy.vn/images/partners/duoplus-logo.webp" alt="VMlogin" />
                    </div>
                    <div class="partner-name">VMlogin</div>
                </a>

                <a href="<?php echo esc_url(home_url('/detail-provider')) ?>" class="partner-card">
                    <div class="partner-logo">
                        <img src="https://tradeproxy.vn/images/partners/vmlogin-logo.webp" alt="YunLogin" />
                    </div>
                    <div class="partner-name">YunLogin</div>
                </a>

                <!-- Duplicate để loop -->
                <a href="<?php echo esc_url(home_url('/detail-provider')) ?>" class="partner-card">
                    <div class="partner-logo">
                        <img src="https://tradeproxy.vn/images/logo/carousel/tigerai.png" alt="Mulogin" />
                    </div>
                    <div class="partner-name">Mulogin</div>
                </a>

                <a href="<?php echo esc_url(home_url('/detail-provider')) ?>" class="partner-card">
                    <div class="partner-logo">
                        <img src="https://tradeproxy.vn/images/logo/carousel/mulogin.png" alt="NestBrowser" />
                    </div>
                    <div class="partner-name">NestBrowser</div>
                </a>

                <a href="<?php echo esc_url(home_url('/detail-provider')) ?>" class="partner-card">
                    <div class="partner-logo">
                        <img src="https://tradeproxy.vn/images/partners/genzolo-agency-logo.webp"
                            alt="TigerAi Browser" />
                    </div>
                    <div class="partner-name">TigerAi Browser</div>
                </a>

                <a href="<?php echo esc_url(home_url('/detail-provider')) ?>" class="partner-card">
                    <div class="partner-logo">
                        <img src="https://tradeproxy.vn/images/partners/omnilogin-logo.webp" alt="VMlogin" />
                    </div>
                    <div class="partner-name">VMlogin</div>
                </a>

                <a href="<?php echo esc_url(home_url('/detail-provider')) ?>" class="partner-card">
                    <div class="partner-logo">
                        <img src="https://tradeproxy.vn/images/partners/genlogin-logo.webp" alt="YunLogin" />
                    </div>
                    <div class="partner-name">YunLogin</div>
                </a>
            </div>
        </div>

        <!-- Row 2: Trượt từ phải sang trái -->
        <div class="partners-carousel">
            <div class="partners-row partners-row-2">
                <!-- Duplicate để tạo hiệu ứng loop liền mạch -->
                <a href="<?php echo esc_url(home_url('/detail-provider')) ?>" class="partner-card">
                    <div class="partner-logo">
                        <img src="https://tradeproxy.vn/images/logo/carousel_after/flexcard.png"
                            alt="XLogin Antidetect" />
                    </div>
                    <div class="partner-name">XLogin Antidetect</div>
                </a>

                <a href="<?php echo esc_url(home_url('/detail-provider')) ?>" class="partner-card">
                    <div class="partner-logo">
                        <img src="https://tradeproxy.vn/images/logo/carousel_after/hubstudio.png" alt="Gemlogin" />
                    </div>
                    <div class="partner-name">Gemlogin</div>
                </a>

                <a href="<?php echo esc_url(home_url('/detail-provider')) ?>" class="partner-card">
                    <div class="partner-logo">
                        <img src="https://tradeproxy.vn/images/logo/carousel_after/Ix.png" alt="Nocaptcha AI" />
                    </div>
                    <div class="partner-name">Nocaptcha AI</div>
                </a>

                <a href="<?php echo esc_url(home_url('/detail-provider')) ?>" class="partner-card">
                    <div class="partner-logo">
                        <img src="https://tradeproxy.vn/images/logo/carousel_after/vmlogin.png" alt="Undetectable" />
                    </div>
                    <div class="partner-name">Undetectable</div>
                </a>

                <a href="<?php echo esc_url(home_url('/detail-provider')) ?>" class="partner-card">
                    <div class="partner-logo">
                        <img src="https://tradeproxy.vn/images/logo/carousel_after/vmlogin.png" alt="MostLogin" />
                    </div>
                    <div class="partner-name">MostLogin</div>
                </a>

                <!-- Duplicate để loop -->
                <a href="<?php echo esc_url(home_url('/detail-provider')) ?>" class="partner-card">
                    <div class="partner-logo">
                        <img src="https://tradeproxy.vn/images/partners/undetectable-browser-en-logo.webp"
                            alt="XLogin Antidetect" />
                    </div>
                    <div class="partner-name">XLogin Antidetect</div>
                </a>

                <a href="<?php echo esc_url(home_url('/detail-provider')) ?>" class="partner-card">
                    <div class="partner-logo">
                        <img src="https://tradeproxy.vn/images/partners/mostlogin-logo.webp" alt="Gemlogin" />
                    </div>
                    <div class="partner-name">Gemlogin</div>
                </a>

                <a href="<?php echo esc_url(home_url('/detail-provider')) ?>" class="partner-card">
                    <div class="partner-logo">
                        <img src="https://tradeproxy.vn/images/logo/carousel_after/Linken.png" alt="Nocaptcha AI" />
                    </div>
                    <div class="partner-name">Nocaptcha AI</div>
                </a>

                <a href="<?php echo esc_url(home_url('/detail-provider')) ?>" class="partner-card">
                    <div class="partner-logo">
                        <img src="https://tradeproxy.vn/images/logo/carousel_after/vmlogin.png" alt="Undetectable" />
                    </div>
                    <div class="partner-name">Undetectable</div>
                </a>

                <a href="<?php echo esc_url(home_url('/detail-provider')) ?>" class="partner-card">
                    <div class="partner-logo">
                        <img src="https://tradeproxy.vn/images/logo/carousel_after/xlogin.png" alt="MostLogin" />
                    </div>
                    <div class="partner-name">MostLogin</div>
                </a>
            </div>
        </div>

        <a href="<?php echo esc_url(home_url('/providers')) ?>" class="view-all-btn">Xem tất cả</a>
    </div>

</div>

<!-- Footer -->
<?php get_footer(); ?>
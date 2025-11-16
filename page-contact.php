<?php
/*
Template Name: Contact Page
*/
?>
<?php get_header(); ?>

<!-- Main -->
<div class="main-content bg-[#f9fdff]">
    <div class="contact-container">
        <div class="contact-layout">
            <!-- Left Side - Contact Info -->
            <div class="contact-info">
                <h1 class="contact-title">Liên hệ TradeProxy</h1>
                <p class="contact-description">
                    Nếu bạn có bất kỳ thắc mắc hoặc cần hỗ trợ nào, đừng ngần ngại
                    liên hệ với chúng tôi qua biểu mẫu phản hồi hoặc phương thức
                    liên lạc mà bạn ưa thích. Đội ngũ hỗ trợ của chúng tôi luôn sẵn
                    sàng 24/7 để kịp thời phản hồi và giải quyết mọi vấn đề mà bạn
                    có thể gặp phải.
                </p>

                <!-- Contact Methods -->
                <div class="contact-methods">
                    <!-- Telegram -->
                    <div class="contact-method-card">
                        <div class="contact-method-icon telegram-icon">
                            <i class="fa-brands fa-telegram"></i>
                        </div>
                        <div class="contact-method-info">
                            <h3 class="contact-method-title">Telegram</h3>
                            <div class="contact-method-value">
                                <a href="https://t.me/TradeProxyVN" target="_blank">@TradeProxyVN</a>
                                <button
                                    class="contact-copy-btn"
                                    data-copy="@TradeProxyVN">
                                    <i class="fa-regular fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="contact-method-card">
                        <div class="contact-method-icon email-icon">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div class="contact-method-info">
                            <h3 class="contact-method-title">Email</h3>
                            <div class="contact-method-value">
                                <a href="mailto:support@tradeproxy.vn">support@tradeproxy.vn</a>
                                <button
                                    class="contact-copy-btn"
                                    data-copy="support@tradeproxy.vn">
                                    <i class="fa-regular fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Contact Form -->
            <div class="contact-form-wrapper">
                <div class="contact-form-card">
                    <h2 class="contact-form-title">Biểu mẫu</h2>

                    <form class="contact-form" id="contactForm">
                        <div class="form-group">
                            <input
                                type="text"
                                class="form-input"
                                placeholder="Tên"
                                id="contactName"
                                required />
                            <span class="form-error" id="nameError"></span>
                        </div>

                        <div class="form-group">
                            <input
                                type="email"
                                class="form-input"
                                placeholder="Email"
                                id="contactEmail"
                                required />
                            <span class="form-error" id="emailError"></span>
                        </div>

                        <div class="form-group">
                            <textarea
                                class="form-textarea"
                                placeholder="Nhập nội dung..."
                                rows="6"
                                id="contactMessage"
                                required></textarea>
                            <span class="form-error" id="messageError"></span>
                        </div>

                        <button type="submit" class="contact-submit-btn">Gửi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
<?php
/*
Template Name: Payment Page
*/
?>
<?php get_header(); ?>

<!-- Main -->
<div class="main-content bg-[#f9fdff]">
    <div class="payment-container">
        <div class="payment-layout">
            <!-- Left Side - Payment Info -->
            <div class="payment-info-card">
                <div class="payment-logo">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/trade-proxy.svg" alt="Trade Proxy" />
                </div>

                <div class="payment-detail">
                    <div class="payment-detail-label">Ngân hàng</div>
                    <div class="payment-detail-value">BIDV</div>
                </div>

                <div class="payment-detail">
                    <div class="payment-detail-label">Số tài khoản</div>
                    <div class="payment-detail-value payment-detail-copyable">
                        96386910064
                        <button class="copy-btn" data-copy="96386910064">
                            <i class="fa-regular fa-copy"></i>
                        </button>
                    </div>
                </div>

                <div class="payment-detail">
                    <div class="payment-detail-label">Chủ tài khoản</div>
                    <div class="payment-detail-value">Huỳnh Văn Tuấn</div>
                </div>

                <div class="payment-detail">
                    <div class="payment-detail-label">Số tiền thanh toán</div>
                    <div class="payment-detail-value payment-amount">50,000đ</div>
                </div>

                <div class="payment-detail">
                    <div class="payment-detail-label">Nội dung chuyển khoản</div>
                    <div class="payment-detail-value payment-detail-copyable">
                        62ZFKY34Z
                        <button class="copy-btn" data-copy="62ZFKY34Z">
                            <i class="fa-regular fa-copy"></i>
                        </button>
                    </div>
                </div>

                <div class="payment-detail">
                    <div class="payment-detail-label">Trạng thái</div>
                    <div class="payment-detail-value payment-status">
                        Chưa thanh toán
                        <i class="fa-regular fa-clock"></i>
                    </div>
                </div>
            </div>

            <!-- Right Side - QR Code -->
            <div class="payment-qr-card">
                <div class="payment-support">Liên hệ hỗ trợ: 034.770.0437</div>

                <h2 class="payment-qr-title">QUÉT MÃ QR ĐỂ THANH TOÁN</h2>
                <p class="payment-qr-subtitle">
                    Sử dụng ứng dụng Internet Banking hoặc ứng dụng camera hỗ trợ
                    quét mã QR code để quét mã
                </p>

                <div class="payment-qr-code">
                    <img
                        src="https://api.vietqr.io/image/970415-113366668888-oa50NDm.jpg?amount=0"
                        alt="QR Code" />
                </div>

                <div class="payment-timer">
                    <span class="timer-label">Thời gian còn lại:</span>
                    <span class="timer-countdown" id="paymentTimer">25:11</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
<!-- Wallet Tab -->
<div class="tab-content" id="wallet-tab">
    <h2 class="content-title">Ví tiền</h2>
    <p class="content-subtitle">Nạp tiền vào ví để thanh toán bất cứ lúc nào!</p>

    <div class="wallet-card">
        <div class="wallet-header">
            <span class="wallet-label">ATM</span>
        </div>
        <div class="wallet-balance-label">Số tiền hiện có</div>
        <div class="wallet-balance">0 VND</div>
        <div class="wallet-note">*Bạn có thể nạp thêm để sử dụng dịch vụ của chúng tôi</div>
    </div>

    <div class="deposit-section">
        <div class="deposit-header">
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                <path
                    d="M20 10C14.48 10 10 14.48 10 20C10 25.52 14.48 30 20 30C25.52 30 30 25.52 30 20C30 14.48 25.52 10 20 10ZM20 22C18.9 22 18 21.1 18 20C18 18.9 18.9 18 20 18C21.1 18 22 18.9 22 20C22 21.1 21.1 22 20 22Z"
                    fill="#0066ff" />
                <path d="M25 15L28 12M12 28L15 25M12 12L15 15M28 28L25 25" stroke="#0066ff" stroke-width="2"
                    stroke-linecap="round" />
            </svg>
            <h3>Nạp tiền vào ví</h3>
        </div>

        <div class="form-group">
            <label>Nhập số tiền bạn cần nạp:</label>
            <input type="text" class="form-input" placeholder="Ví dụ: 50,000" id="deposit-amount">
        </div>

        <div class="form-btn-submit">
            <a href="<?php echo esc_url(home_url('/payment')) ?>" type="submit" class="submit-btn">Nạp</a>
        </div>

        <div class="deposit-notes">
            <p><strong>Lưu ý:</strong></p>
            <p>- Sau khi nhập số tiền và nạp sẽ được chuyển sang trang thanh toán bằng mã QR và số tiền sẽ được chúng
                tôi xử
                lý ngay lập tức mọi thắc mắc liên hệ số 034.770.0437</p>
            <p>- Chỉ hỗ trợ nạp trên 50.000 VND</p>
        </div>
    </div>
</div>
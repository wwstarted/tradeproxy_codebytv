<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* ========== PAGE HEADER ========== */
    .page-header {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    .page-title {
        font-size: 24px;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 5px;
    }

    .page-subtitle {
        font-size: 14px;
        color: #666;
    }

    .page-subtitle a {
        color: #0066ff;
        text-decoration: none;
    }

    .page-subtitle a:hover {
        text-decoration: underline;
    }

    /* ========== ORDER BOX ========== */
    .order-box {
        background: white;
        border-radius: 10px;
        margin-bottom: 20px;
        overflow: hidden;
        width: 100%;
        max-width: none;
        box-sizing: border-box;
    }

    .order-header {
        background: #E3F2FD;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
    }

    .order-code {
        font-size: 14px;
        color: #333;
    }

    .order-code strong {
        font-weight: 600;
    }

    .order-date {
        font-size: 13px;
        color: #666;
    }

    .order-status {
        font-size: 14px;
        font-weight: 600;
        color: #4CAF50;
        margin-left: auto;
    }

    .order-status.pending {
        color: #FF9800;
    }

    /* ========== PRODUCT ITEM ========== */
    .product-item {
        padding: 20px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .product-left {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
        min-width: 0;
    }

    .product-logo {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: bold;
        color: white;
        flex-shrink: 0;
    }

    .product-logo.proxy9 {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .product-logo.pia {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .product-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .product-name {
        font-size: 15px;
        font-weight: 600;
        color: #333;
    }

    .product-type {
        font-size: 13px;
        color: #666;
    }

    .product-center {
        flex: 1;
        text-align: left;
    }

    .product-cdk {
        font-size: 13px;
        color: #666;
    }

    .product-right {
        flex-shrink: 0;
    }

    .product-price {
        font-size: 16px;
        font-weight: 700;
        color: #333;
        text-align: right;
    }

    /* ========== ORDER FOOTER ========== */
    .order-footer {
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        background: #fafafa;
    }

    .order-actions {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .btn {
        padding: 10px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-primary {
        background: #0066ff;
        color: white;
    }

    .btn-primary:hover {
        background: #0052cc;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 102, 255, 0.3);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    .guide-link {
        color: #0066ff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
    }

    .guide-link:hover {
        text-decoration: underline;
    }

    .order-total {
        text-align: right;
    }

    .total-amount {
        font-size: 20px;
        font-weight: 700;
        color: #e91e63;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 768px) {
        .order-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .order-status {
            margin-left: 0;
        }

        .product-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .product-center,
        .product-right {
            width: 100%;
        }

        .product-price {
            text-align: left;
        }

        .order-footer {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .order-total {
            width: 100%;
            text-align: left;
        }
    }
</style>

<!-- Header -->
<div class="page-header">
    <h1 class="page-title">Lịch sử mua hàng</h1>
    <p class="page-subtitle">Hướng dẫn nạp CDkey <a href="#">tại đây</a></p>
</div>

<!-- Order Box 1 - Hoàn thành -->
<div class="order-box">
    <div class="order-header">
        <span class="order-code">Đơn hàng: <strong>#CBQGLKH</strong></span>
        <span class="order-date">11/11/2025 09:21</span>
        <span class="order-status">Hoàn thành</span>
    </div>

    <div class="product-item">
        <div class="product-left">
            <div class="product-logo proxy9">9</div>
            <div class="product-info">
                <div class="product-name">9proxy</div>
                <div class="product-type">50 ip</div>
            </div>
        </div>
        <div class="product-center">
            <div class="product-cdk">CDK: Đang chờ thanh toán</div>
        </div>
        <div class="product-right">
            <div class="product-price">200,000đ</div>
        </div>
    </div>

    <div class="product-item">
        <div class="product-left">
            <div class="product-logo pia">P</div>
            <div class="product-info">
                <div class="product-name">Pia</div>
                <div class="product-type">100 ip</div>
            </div>
        </div>
        <div class="product-center">
            <div class="product-cdk">CDK: Đang chờ thanh toán</div>
        </div>
        <div class="product-right">
            <div class="product-price">280,000đ</div>
        </div>
    </div>

    <div class="order-footer">
        <div class="product-left">
            <div class="order-actions">
                <button class="btn btn-primary">Mua lại</button>
            </div>
        </div>
        <div class="product-center">
            <a href="#" class="guide-link">Hướng dẫn nạp 9proxy</a>
        </div>
        <div class="product-right">
            <div class="order-total">
                <div class="total-amount">480,000đ</div>
            </div>
        </div>
    </div>
</div>

<!-- Order Box 2 - Chờ thanh toán -->
<div class="order-box">
    <div class="order-header">
        <span class="order-code">Đơn hàng: <strong>#ABCD1234</strong></span>
        <span class="order-date">10/11/2025 14:30</span>
        <span class="order-status pending">Chờ thanh toán</span>
    </div>

    <div class="product-item">
        <div class="product-left">
            <div class="product-logo proxy9">9</div>
            <div class="product-info">
                <div class="product-name">9proxy</div>
                <div class="product-type">100 ip</div>
            </div>
        </div>
        <div class="product-center">
            <div class="product-cdk">CDK: Đang chờ thanh toán</div>
        </div>
        <div class="product-right">
            <div class="product-price">350,000đ</div>
        </div>
    </div>

    <div class="order-footer">
        <div class="product-left">
            <div class="order-actions">
                <button class="btn btn-primary">Thanh toán</button>
            </div>
        </div>
        <div class="product-center">
            <a href="#" class="guide-link">Hướng dẫn nạp 9proxy</a>
        </div>
        <div class="product-right">
            <div class="order-total">
                <div class="total-amount">350,000đ</div>
            </div>
        </div>
    </div>
</div>
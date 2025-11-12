<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch Sử Mua Hàng</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
        }

        .page-header {
            margin-bottom: 20px;
        }

        .page-title {
            font-size: 22px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
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

        /* Order Box */
        .order-box {
            background: white;
            border-radius: 8px;
            margin-bottom: 20px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .order-header {
            background: #E3F2FD;
            padding: 12px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-code {
            font-size: 14px;
            color: #333;
        }

        .order-code strong {
            font-weight: 600;
        }

        .order-status {
            font-size: 14px;
            font-weight: 600;
            color: #4CAF50;
        }

        .order-status.pending {
            color: #FF9800;
        }

        .order-date {
            font-size: 13px;
            color: #666;
            margin-left: auto;
            margin-right: 16px;
        }

        /* Chi tiết sản phẩm */
        .product-item {
            padding: 16px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }

        .product-left {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
        }

        .product-logo {
            width: 40px;
            height: 40px;
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
            align-items: center;
            gap: 8px;
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

        .order-footer {
            padding: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
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
        }

        .guide-link {
            color: #0066ff;
            text-decoration: none;
            font-size: 14px;
        }

        .guide-link:hover {
            text-decoration: underline;
        }

        .order-total {
            text-align: right;
        }

        .total-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 4px;
        }

        .total-amount {
            font-size: 18px;
            font-weight: 700;
            color: #e91e63;
        }
    </style>
</head>

<body>
    <div class="container">
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

    </div>
</body>

</html>
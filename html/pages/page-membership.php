<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hạng Thành Viên</title>
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
            max-width:100%;
            margin: 0 auto;
        }

        h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 30px;
            font-weight: 600;
        }

        /* giới thiệu 4 hạng */
        .ranks-intro {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            position: relative;
            overflow: hidden;
        }

        .ranks-intro::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300"><text x="200" y="150" font-size="120" fill="%23f0f0f0" text-anchor="middle" opacity="0.3" font-weight="bold">Pixel Perfect</text></svg>') center/contain no-repeat;
            pointer-events: none;
        }

        .intro-header {
            text-align: center;
            margin-bottom: 25px;
            position: relative;
            z-index: 1;
        }

        .intro-title {
            color: #0066ff;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .intro-subtitle {
            color: #666;
            font-size: 13px;
        }

        .ranks-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            position: relative;
            z-index: 1;
        }

        .rank-item {
            text-align: center;
        }

        .medal-container {
            position: relative;
            display: inline-block;
            margin-bottom: 10px;
        }

        .medal {
            width: 65px;
            height: 90px;
            position: relative;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.15));
        }

        .medal-ribbon {
            width: 32px;
            height: 45px;
            margin: 0 auto;
            position: relative;
        }

        .medal-ribbon::before,
        .medal-ribbon::after {
            content: '';
            position: absolute;
            top: 0;
            width: 16px;
            height: 45px;
            background: linear-gradient(180deg, var(--color1) 0%, var(--color2) 100%);
        }

        .medal-ribbon::before {
            left: 0;
            clip-path: polygon(0 0, 100% 0, 100% 85%, 50% 100%, 0 85%);
            background: linear-gradient(145deg, var(--color1) 0%, var(--color2) 100%);
        }

        .medal-ribbon::after {
            right: 0;
            clip-path: polygon(0 0, 100% 0, 100% 85%, 50% 100%, 0 85%);
            background: linear-gradient(215deg, var(--color1) 0%, var(--color2) 100%);
        }

        .medal-circle {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--color1) 0%, var(--color2) 100%);
            margin: -8px auto 0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 
                0 6px 16px rgba(0,0,0,0.25),
                inset 0 2px 4px rgba(255,255,255,0.3),
                inset 0 -2px 4px rgba(0,0,0,0.2);
            border: 4px solid #fff;
            position: relative;
        }

        .medal-circle::before {
            content: '';
            position: absolute;
            inset: 4px;
            border-radius: 50%;
            background: linear-gradient(135deg, 
                rgba(255,255,255,0.3) 0%, 
                transparent 50%, 
                rgba(0,0,0,0.2) 100%);
            pointer-events: none;
        }

        .medal-circle::after {
            content: '';
            position: absolute;
            inset: 8px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.4);
            pointer-events: none;
        }
 
        .medal-number {
            color: white;
            font-size: 26px;
            font-weight: 900;
            font-style: italic;
            text-shadow: 
                0 2px 4px rgba(0,0,0,0.3),
                0 -1px 1px rgba(255,255,255,0.2);
            position: relative;
            z-index: 1;
        }

        .rank-level {
            font-size: 13px;
            color: #666;
            font-weight: 500;
        }

        /* Level 1 */
        .rank-item:nth-child(1) {
            --color1: #FFD700;
            --color2: #FF8C00;
        }

        /* Level 2 */
        .rank-item:nth-child(2) {
            --color1: #4169E1;
            --color2: #0047AB;
        }

        /* Level 3  */
        .rank-item:nth-child(3) {
            --color1: #FF1493;
            --color2: #C71585;
        }

        /* Level 4 */
        .rank-item:nth-child(4) {
            --color1: #FF4500;
            --color2: #DC143C;
        }

        .join-button {
            background: #0066ff;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 15px;
            transition: background 0.3s;
        }

        .join-button:hover {
            background: #0052cc;
        }

       /* ============== */
        .user-rank-card {
            background: linear-gradient(135deg, #003366 0%, #001a33 100%);
            border-radius: 12px;
            padding: 0;
            position: relative;
            overflow: visible;
            box-shadow: 0 4px 16px rgba(0,0,0,0.2);
        }

        .member-badge {
            position: absolute;
            top: -15px;
            right: 30px;
            z-index: 10;
        }

        .member-badge .medal {
            width: 75px;
            height: 100px;
        }

        .member-badge .medal-ribbon {
            width: 36px;
            height: 50px;
        }

        .member-badge .medal-ribbon::before,
        .member-badge .medal-ribbon::after {
            width: 18px;
            height: 50px;
        }

        .member-badge .medal-circle {
            width: 65px;
            height: 65px;
            margin-top: -10px;
        }

        .member-badge .medal-number {
            font-size: 30px;
        }

        .card-header {
            background: #FF6B35;
            padding: 10px 20px;
            border-radius: 12px 12px 12px 12px;
            display: inline-block;
            margin-top: 12px;
            margin-left: 12px;
        }

        .badge-label {
            color: white;
            font-size: 13px;
            font-weight: 600;
        }

        .card-content {
            padding: 30px;
            padding-top: 40px;
        }

        .rank-title {
            color: white;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .info-label {
            color: #fff;
            font-size: 14px;
        }

        .info-value {
            color: #FFD700;
            font-size: 18px;
            font-weight: 700;
        }

        .progress-bar {
            width: 100%;
            height: 12px;
            background: rgba(255,255,255,0.15);
            border-radius: 20px;
            overflow: hidden;
            margin-top: 12px;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.2);
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #4CAF50 0%, #8BC34A 100%);
            border-radius: 20px;
            width: 100%;
            box-shadow: 0 0 10px rgba(76, 175, 80, 0.5);
        }

        .discount-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.15);
        }

        .discount-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .discount-label {
            color: #fff;
            font-size: 14px;
            font-weight: 400;
        }

        .discount-badge-wrapper {
            display: flex;
            gap: 8px;
        }

        .discount-badge {
            background: #ffffff;
            color: #1a1a1a;
            padding: 8px 32px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 13px;
            display: inline-block;
            box-shadow: 
                0 2px 4px rgba(0,0,0,0.3),
                inset 0 1px 0 rgba(255,255,255,0.8);
            position: relative;
            overflow: visible;
            min-width: 140px;
            text-align: center;
            border: 1px solid #d0d0d0;
        }

        .discount-badge::before {
            content: '';
            position: absolute;
            top: -1px;
            bottom: -2px;
            left: -8px;
            width: 20px;
            background: linear-gradient(135deg, #e8e8e8 0%, #a8a8a8 100%);
            clip-path: polygon(0 0, 100% 0, 80% 100%, 0 100%);
            box-shadow: 
                inset -1px 0 2px rgba(0,0,0,0.2),
                0 2px 4px rgba(0,0,0,0.3);
            border-radius: 4px 0 0 4px;
        }

        .discount-badge::after {
            content: '';
            position: absolute;
            top: -1px;
            bottom: -2px;
            right: -8px;
            width: 20px;
            background: linear-gradient(135deg, #a8a8a8 0%, #e8e8e8 100%);
            clip-path: polygon(20% 0, 100% 0, 100% 100%, 0 100%);
            box-shadow: 
                inset 1px 0 2px rgba(0,0,0,0.2),
                0 2px 4px rgba(0,0,0,0.3);
            border-radius: 0 4px 4px 0;
        }

        .discount-value {
            color: #FFD700;
            font-size: 20px;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hạng thành viên</h1>
        <div class="ranks-intro">
            <div class="intro-header">
                <div class="intro-title">Thành viên TRADE PROXY</div>
                <div class="intro-subtitle">Hãy nhanh tay trở thành membership của TRADE PROXY để nhận được những ưu đãi hấp dẫn</div>
            </div>

            <div class="ranks-grid">
                <div class="rank-item">
                    <div class="medal-container">
                        <div class="medal">
                            <div class="medal-ribbon"></div>
                            <div class="medal-circle">
                                <span class="medal-number">I</span>
                            </div>
                        </div>
                    </div>
                    <div class="rank-level">Level 1</div>
                </div>

                <div class="rank-item">
                    <div class="medal-container">
                        <div class="medal">
                            <div class="medal-ribbon"></div>
                            <div class="medal-circle">
                                <span class="medal-number">II</span>
                            </div>
                        </div>
                    </div>
                    <div class="rank-level">Level 2</div>
                </div>

                <div class="rank-item">
                    <div class="medal-container">
                        <div class="medal">
                            <div class="medal-ribbon"></div>
                            <div class="medal-circle">
                                <span class="medal-number">III</span>
                            </div>
                        </div>
                    </div>
                    <div class="rank-level">Level 3</div>
                </div>

                <div class="rank-item">
                    <div class="medal-container">
                        <div class="medal">
                            <div class="medal-ribbon"></div>
                            <div class="medal-circle">
                                <span class="medal-number">IV</span>
                            </div>
                        </div>
                    </div>
                    <div class="rank-level">Level 4</div>
                </div>
            </div>

            <center>
                <button class="join-button">Nạp ngay</button>
            </center>
        </div>

        <!-- Rank user -->
        <div class="user-rank-card">
            <div class="member-badge">
                <div class="medal">
                    <div class="medal-ribbon" style="--color1: #FFD700; --color2: #FFA500;"></div>
                    <div class="medal-circle" style="--color1: #FFD700; --color2: #FFA500;">
                        <span class="medal-number">I</span>
                    </div>
                </div>
            </div>

            <div class="card-header">
                <span class="badge-label">Thành viên mới</span>
            </div>

            <div class="card-content">
                <h2 class="rank-title">Anh Nhi</h2>

                <div class="info-row">
                    <span class="info-label">Tổng giá trị nạp</span>
                    <span class="info-value">2,000,000 VNĐ</span>
                </div>

                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>

                <div class="discount-row">
                    <div class="discount-left">
                        <span class="discount-label">Mã giảm giá:</span>
                        <div class="discount-badge-wrapper">
                            <span class="discount-badge">Cần Level 1</span>
                        </div>
                    </div>
                    <div class="discount-value">0 VNĐ</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
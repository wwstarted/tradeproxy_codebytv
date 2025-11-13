<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

        .page-header h1 {
            font-size: 24px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 5px;
        }

        .page-subtitle {
            font-size: 14px;
            color: #666;
        }

        /* ========== PROFILE FORM ========== */
        .profile-form {
            background: white;
            padding: 5px;
            border-radius: 10px;
            width: 100%;
            max-width: none;
            box-sizing: border-box;
        }

        .form-group {
            margin-bottom: 25px;
            width: 100%;
        }

        .form-label {
            display: block;
            margin-bottom: 10px;
            color: #333;
            font-size: 14px;
            font-weight: 500;
        }

        .form-input-wrapper {
            position: relative;
            width: 100%;
        }

        .form-input {
            width: 100%;
            padding: 14px 45px 14px 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s;
            box-sizing: border-box;
        }

        .form-input:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        }

        .form-input.disabled {
            background: #f5f5f5;
            cursor: not-allowed;
        }

        .form-input::placeholder {
            color: #999;
        }

        .edit-btn {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #4CAF50;
            cursor: pointer;
            font-size: 18px;
            padding: 5px;
        }

        .edit-btn:hover {
            color: #45a049;
        }

        /* ========== PHONE INPUT ========== */
        .phone-input {
            display: flex;
            gap: 10px;
            position: relative;
            width: 100%;
        }

        .country-select {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            background: white;
            min-width: 140px;
            transition: border-color 0.3s;
        }

        .country-select:hover {
            border-color: #4CAF50;
        }

        .country-flag {
            width: 24px;
            height: 16px;
            object-fit: cover;
        }

        .country-code {
            font-weight: 500;
        }

        .country-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 5px;
            width: 100%;
            max-width: 400px;
            max-height: 300px;
            overflow-y: auto;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none;
        }

        .country-dropdown.show {
            display: block;
        }

        .country-search {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            position: sticky;
            top: 0;
            background: white;
        }

        .country-search input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .country-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .country-item:hover {
            background: #f5f5f5;
        }

        .country-item.active {
            background: #e8f5e9;
        }

        .country-name {
            flex: 1;
        }

        /* ========== FORM ACTIONS ========== */
        .form-actions {
            margin-top: 35px;
            display: flex;
            justify-content: flex-end;
        }

        .btn-save {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 14px 40px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-save:hover {
            background: #45a049;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(76, 175, 80, 0.3);
        }

        .btn-save:active {
            transform: translateY(0);
        }

        /* ========== MODAL ========== */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 2000;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            position: relative;
            background: white;
            border-radius: 12px;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            border-bottom: 1px solid #f0f0f0;
        }

        .modal-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .modal-close {
            width: 32px;
            height: 32px;
            border: none;
            background: transparent;
            color: #999;
            cursor: pointer;
            border-radius: 4px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            background: #f5f5f5;
            color: #333;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-text {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
            text-align: center;
        }

        .modal-text strong {
            color: #4CAF50;
        }

        .otp-input-group {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 20px;
        }

        .otp-input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 20px;
            font-weight: 600;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .otp-input:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        }

        .resend-text {
            text-align: center;
            font-size: 13px;
            color: #666;
        }

        .resend-text a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: 500;
        }

        .resend-text a:hover {
            text-decoration: underline;
        }

        .modal-footer {
            display: flex;
            gap: 12px;
            padding: 20px 24px;
            border-top: 1px solid #f0f0f0;
        }

        .btn-cancel,
        .btn-verify {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-cancel {
            background: #f5f5f5;
            color: #666;
        }

        .btn-cancel:hover {
            background: #e0e0e0;
        }

        .btn-verify {
            background: #4CAF50;
            color: #fff;
        }

        .btn-verify:hover {
            background: #45a049;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .profile-form {
                padding: 20px;
            }

            .btn-save {
                width: 100%;
                text-align: center;
            }

            .otp-input {
                width: 42px;
                height: 42px;
                font-size: 18px;
            }
        }
    </style>
</head>

<body>
    <div class="page-header">
        <h1>Hồ sơ</h1>
        <p class="page-subtitle">Quản lý hồ sơ và thông tin đăng nhập</p>
    </div>

    <div class="profile-form">
        <div class="form-group">
            <label class="form-label">Họ tên</label>
            <div class="form-input-wrapper">
                <input type="text" class="form-input" id="fullname" value="Anh Nhi" placeholder="Nhập họ tên">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Email</label>
            <div class="form-input-wrapper">
                <input type="email" class="form-input disabled" id="email" value="davidbeckham.tvd11@gmail.com"
                    disabled>
                <button class="edit-btn" id="edit-email-btn" title="Chỉnh sửa email">
                    <i class="fas fa-edit"></i>
                </button>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Số điện thoại</label>
            <div class="form-input-wrapper phone-input">
                <div class="country-select" id="country-select">
                    <img src="https://flagcdn.com/w40/vn.png" alt="Vietnam" class="country-flag">
                    <span class="country-code">+84</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <input type="tel" class="form-input" id="phone" value="84" placeholder="84xxxxxxxxx">

                <!-- Dropdown country -->
                <div class="country-dropdown" id="country-dropdown">
                    <div class="country-search">
                        <input type="text" placeholder="Tìm kiếm quốc gia..." id="country-search">
                    </div>
                    <div class="country-list">
                        <div class="country-item active" data-code="+84" data-flag="vn">
                            <img src="https://flagcdn.com/w40/vn.png" alt="Vietnam" class="country-flag">
                            <span class="country-name">Vietnam</span>
                            <span class="country-code">+84</span>
                        </div>
                        <div class="country-item" data-code="+1" data-flag="us">
                            <img src="https://flagcdn.com/w40/us.png" alt="United States" class="country-flag">
                            <span class="country-name">United States</span>
                            <span class="country-code">+1</span>
                        </div>
                        <div class="country-item" data-code="+44" data-flag="gb">
                            <img src="https://flagcdn.com/w40/gb.png" alt="United Kingdom" class="country-flag">
                            <span class="country-name">United Kingdom</span>
                            <span class="country-code">+44</span>
                        </div>
                        <div class="country-item" data-code="+86" data-flag="cn">
                            <img src="https://flagcdn.com/w40/cn.png" alt="China" class="country-flag">
                            <span class="country-name">China</span>
                            <span class="country-code">+86</span>
                        </div>
                        <div class="country-item" data-code="+81" data-flag="jp">
                            <img src="https://flagcdn.com/w40/jp.png" alt="Japan" class="country-flag">
                            <span class="country-name">Japan</span>
                            <span class="country-code">+81</span>
                        </div>
                        <div class="country-item" data-code="+82" data-flag="kr">
                            <img src="https://flagcdn.com/w40/kr.png" alt="South Korea" class="country-flag">
                            <span class="country-name">South Korea</span>
                            <span class="country-code">+82</span>
                        </div>
                        <div class="country-item" data-code="+65" data-flag="sg">
                            <img src="https://flagcdn.com/w40/sg.png" alt="Singapore" class="country-flag">
                            <span class="country-name">Singapore</span>
                            <span class="country-code">+65</span>
                        </div>
                        <div class="country-item" data-code="+66" data-flag="th">
                            <img src="https://flagcdn.com/w40/th.png" alt="Thailand" class="country-flag">
                            <span class="country-name">Thailand</span>
                            <span class="country-code">+66</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save -->
        <div class="form-actions">
            <button class="btn-save" id="save-profile-btn">Lưu</button>
        </div>
    </div>

    <!-- Modal OTP Email -->
    <div class="modal" id="email-otp-modal">
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h3>Xác thực Email</h3>
                <button class="modal-close" id="close-modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <p class="modal-text">Mã OTP đã được gửi đến email <strong id="current-email"></strong></p>
                <div class="otp-input-group">
                    <input type="text" class="otp-input" maxlength="1" />
                    <input type="text" class="otp-input" maxlength="1" />
                    <input type="text" class="otp-input" maxlength="1" />
                    <input type="text" class="otp-input" maxlength="1" />
                    <input type="text" class="otp-input" maxlength="1" />
                    <input type="text" class="otp-input" maxlength="1" />
                </div>
                <p class="resend-text">Không nhận được mã? <a href="#" id="resend-otp">Gửi lại</a></p>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" id="cancel-otp">Hủy</button>
                <button class="btn-verify" id="verify-otp">Xác nhận</button>
            </div>
        </div>
    </div>

    <script>
        // Country dropdown toggle
        document.getElementById('country-select').addEventListener('click', function() {
            document.getElementById('country-dropdown').classList.toggle('show');
        });

        // Country search
        document.getElementById('country-search').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const items = document.querySelectorAll('.country-item');
            
            items.forEach(item => {
                const countryName = item.querySelector('.country-name').textContent.toLowerCase();
                if (countryName.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Country selection
        document.querySelectorAll('.country-item').forEach(item => {
            item.addEventListener('click', function() {
                const code = this.dataset.code;
                const flag = this.dataset.flag;
                const name = this.querySelector('.country-name').textContent;
                
                // Update active state
                document.querySelectorAll('.country-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
                
                // Update display
                document.querySelector('.country-select img').src = `https://flagcdn.com/w40/${flag}.png`;
                document.querySelector('.country-select .country-code').textContent = code;
                
                // Close dropdown
                document.getElementById('country-dropdown').classList.remove('show');
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.phone-input')) {
                document.getElementById('country-dropdown').classList.remove('show');
            }
        });

        // Edit email button
        document.getElementById('edit-email-btn').addEventListener('click', function() {
            document.getElementById('email-otp-modal').classList.add('show');
            document.getElementById('current-email').textContent = document.getElementById('email').value;
        });

        // Close modal
        document.getElementById('close-modal').addEventListener('click', function() {
            document.getElementById('email-otp-modal').classList.remove('show');
        });

        document.getElementById('cancel-otp').addEventListener('click', function() {
            document.getElementById('email-otp-modal').classList.remove('show');
        });

        document.querySelector('.modal-overlay').addEventListener('click', function() {
            document.getElementById('email-otp-modal').classList.remove('show');
        });

        // OTP input auto-focus
        const otpInputs = document.querySelectorAll('.otp-input');
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', function() {
                if (this.value.length === 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            });
            
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value === '' && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
        });

        // Verify OTP
        document.getElementById('verify-otp').addEventListener('click', function() {
            const otp = Array.from(otpInputs).map(input => input.value).join('');
            console.log('OTP:', otp);
            alert('Xác thực thành công!');
            document.getElementById('email-otp-modal').classList.remove('show');
        });

        // Resend OTP
        document.getElementById('resend-otp').addEventListener('click', function(e) {
            e.preventDefault();
            alert('Đã gửi lại mã OTP!');
        });

        // Save profile
        document.getElementById('save-profile-btn').addEventListener('click', function() {
            const fullname = document.getElementById('fullname').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            
            console.log({fullname, email, phone});
            alert('Đã lưu thông tin!');
        });
    </script>
</body>

</html>
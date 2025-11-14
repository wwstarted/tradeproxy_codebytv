<!-- Header giống change-password -->
<div class="page-header">
  <h1>💳 Ví tiền</h1>
  <p class="page-subtitle">Nạp tiền vào ví để thanh toán bất cứ lúc nào!</p>
</div>

<!-- Ví tiền -->
<div class="wallet-balance-section">
  <div class="wallet-card">
    <div class="wallet-card-header">ATM</div>
    <div class="wallet-card-body">
      <p class="wallet-balance-label">Số tiền hiện có</p>
      <h3 class="wallet-balance-amount" id="wallet-balance">0 VND</h3>
      <small>*Nạp thêm tiền để sử dụng dịch vụ</small>
    </div>
  </div>
</div>

<!-- Nạp tiền -->
<div class="wallet-topup-section">
  <div class="topup-header">
    <img src="https://cdn-icons-png.flaticon.com/512/1170/1170678.png" alt="topup" class="topup-icon">
    <h3>Nạp tiền vào ví</h3>
  </div>

  <div class="topup-form">
    <label for="topup-amount">Nhập số tiền bạn cần nạp:</label>
    <div class="input-group">
      <input type="number" id="topup-amount" placeholder="Ví dụ: 50000">
      <button id="topup-btn">Nạp</button>
    </div>
  </div>

  <div class="topup-note">
    <strong>Lưu ý:</strong>
    <ul>
      <li>Sau khi nhập số tiền và nạp, bạn sẽ được chuyển sang trang thanh toán bằng mã QR. Mọi thắc mắc vui lòng liên
        hệ số <strong>034.***.***7</strong>.</li>
      <li>Chỉ hỗ trợ nạp trên <strong>50.000 VND</strong>.</li>
    </ul>
  </div>
</div>


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

  /* ========== WALLET PAGE ========== */
  .wallet-page {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
  }

  /* ========== Ví tiền  ========== */
  .wallet-balance-section {
    background: white;
    padding: 5px;
    border-radius: 10px;
    width: 100%;
    max-width: none;
    box-sizing: border-box;
    margin-bottom: 15px;
  }

  .wallet-card {
    margin-top: -10px;
    width: 320px;
    height: 180px;
    border-radius: 12px;
    background: linear-gradient(145deg, #0092ff, #005fcc);
    color: #fff;
    padding: 1.2rem 1.5rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: transform 0.3s ease;
  }

  .wallet-card:hover {
    transform: translateY(-3px);
  }

  .wallet-card-header {
    font-size: 1.2rem;
    font-weight: 600;
  }

  .wallet-balance-label {
    font-size: 0.9rem;
    opacity: 0.8;
  }

  .wallet-balance-amount {
    font-size: 2rem;
    font-weight: 700;
    margin: 0.5rem 0;
  }

  /* ========== Nạp tiền  ========== */
  .wallet-topup-section {
    background: white;
    padding: 5px;
    border-radius: 10px;
    width: 100%;
    max-width: none;
    box-sizing: border-box;
  }

  .topup-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 25px;
  }

  .topup-header h3 {
    font-size: 18px;
    font-weight: 600;
    color: #1a1a1a;
  }

  .topup-icon {
    width: 32px;
    height: 32px;
  }

  .topup-form label {
    display: block;
    margin-bottom: 10px;
    color: #333;
    font-size: 14px;
    font-weight: 500;
  }

  .input-group {
    display: flex;
    gap: 10px;
    margin-bottom: 25px;
  }

  .input-group input {
    flex: 1;
    padding: 14px 16px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 15px;
    background: white;
    transition: all 0.3s;
    box-sizing: border-box;
  }

  .input-group input:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
  }

  .input-group button {
    background: #007bff;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 14px 40px;
    font-size: 15px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
  }

  .input-group button:hover {
    background: #0056b3;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
  }

  .input-group button:active {
    transform: translateY(0);
  }

  .topup-note {
    font-size: 14px;
    color: #666;
    line-height: 1.6;
  }

  .topup-note strong {
    color: #333;
  }

  .topup-note ul {
    margin-top: 10px;
    padding-left: 20px;
    list-style-type: disc;
  }

  .topup-note li {
    margin-bottom: 8px;
  }

  /* ========== RESPONSIVE ========== */
  @media (max-width: 768px) {

    .wallet-balance-section,
    .wallet-topup-section {
      padding: 20px;
    }

    .wallet-card {
      width: 100%;
      height: auto;
    }

    .input-group {
      flex-direction: column;
    }

    .input-group button {
      width: 100%;
    }
  }
</style>

<script>
  const topupBtn = document.getElementById("topup-btn");
  const amountInput = document.getElementById("topup-amount");
  const walletBalance = document.getElementById("wallet-balance");

  let balance = 0;

  topupBtn.addEventListener("click", () => {
    const amount = parseInt(amountInput.value);
    if (isNaN(amount) || amount < 50000) {
      alert("Số tiền nạp tối thiểu là 50.000 VND");
      return;
    }

    balance += amount;
    walletBalance.textContent = balance.toLocaleString("vi-VN") + " VND";
    amountInput.value = "";

    alert(`Bạn đã nạp thành công ${amount.toLocaleString("vi-VN")} VND vào ví!`);
  });


</script>
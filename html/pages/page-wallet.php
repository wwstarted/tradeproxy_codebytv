<section class="wallet-page">
  <!-- Ví tiền -->
  <div class="wallet-balance-section">
    <h2 class="section-title">💳 Ví tiền</h2>
    <p class="section-subtitle">Nạp tiền vào ví để thanh toán bất cứ lúc nào!</p>

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
        <li>Sau khi nhập số tiền và nạp, bạn sẽ được chuyển sang trang thanh toán bằng mã QR. Mọi thắc mắc vui lòng liên hệ số <strong>034.***.***7</strong>.</li>
        <li>Chỉ hỗ trợ nạp trên <strong>50.000 VND</strong>.</li>
      </ul>
    </div>
  </div>
</section>
<style>
    .wallet-page {
  display: flex;
  flex-direction: column;
  gap: 2rem;
  color: #222;
  font-family: 'Inter', sans-serif;
}

/* ========== Ví tiền========== */
.wallet-balance-section {
  background: #fff;
  padding: 1.5rem;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.section-title {
  font-size: 1.5rem;
  margin-bottom: 0.2rem;
  color: #111;
}

.section-subtitle {
  color: #555;
  margin-bottom: 1rem;
}

.wallet-card {
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

/* ========== Nạp tiền========== */
.wallet-topup-section {
  background: #fff;
  padding: 1.5rem;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.topup-header {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 1rem;
}

.topup-icon {
  width: 32px;
  height: 32px;
}

.topup-form label {
  font-weight: 500;
  color: #333;
}

.input-group {
  display: flex;
  gap: 0.5rem;
  margin-top: 0.5rem;
}

.input-group input {
  flex: 1;
  padding: 0.6rem 0.8rem;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 1rem;
  background: #f9fafb;
}

.input-group button {
  background: #007bff;
  color: #fff;
  border: none;
  border-radius: 6px;
  padding: 0.6rem 1.2rem;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.3s ease;
}

.input-group button:hover {
  background: #005fcc;
}

.topup-note {
  margin-top: 1rem;
  font-size: 0.9rem;
  color: #444;
  line-height: 1.5;
}

.topup-note ul {
  margin-top: 0.5rem;
  padding-left: 1.2rem;
  list-style-type: disc;
}

@media (max-width: 768px) {
  .wallet-card {
    width: 100%;
    height: auto;
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
<section class="deposit-history">
  <h2>Lịch sử nạp tiền</h2>
  <p class="subtitle">Quản lý giao dịch của bạn tại Tradeproxy.vn</p>

  <div class="transaction-list">

    <!-- Box 1 -->
    <div class="transaction-box cancelled">
      <div class="row top-row">
        <span class="code">F10KR5YIX</span>
        <span class="date">07/11/2025 14:35</span>
      </div>
      <div class="row middle-row">
        <span class="content">Nội dung: VQRaf5bc54a29 F10KR5YIX</span>
        <span class="status cancelled">Đã hủy</span>
      </div>
      <div class="row bottom-row">
        <button class="btn-de btn-blue">Thanh toán</button>
        <span class="amount">50,000đ</span>
      </div>
    </div>

    <!-- Box 2: success -->
    <div class="transaction-box success">
      <div class="row top-row">
        <span class="code">T9HX82QL</span>
        <span class="date">05/11/2025 09:20</span>
      </div>
      <div class="row middle-row">
        <span class="content">Nội dung: ABCD1234XYZ</span>
        <span class="status success">Hoàn thành</span>
      </div>
      <div class="row bottom-row">
        <button class="btn-de btn-blue">Lặp lại</button>
        <span class="amount">100,000đ</span>
      </div>
    </div>

  </div>
</section>

<style>
.deposit-history {
  background: #fff;
  border-radius: 10px;
  padding: 20px;
}

.deposit-history h2 {
  font-size: 20px;
  margin-bottom: 5px;
}

.deposit-history .subtitle {
  font-size: 14px;
  color: #666;
  margin-bottom: 20px;
}

.transaction-box {
  border: 1px solid #e5e7eb;
  border-radius: 10px;
  overflow: hidden;
  margin-bottom: 15px;
  transition: box-shadow 0.2s;
}

.transaction-box:hover {
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.transaction-box .row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 14px;
  padding: 10px 15px;
}

.transaction-box .top-row {
  background-color: #f9fafb;
  font-weight: 600;
  color: #111827;
  border-bottom: 1px solid #e5e7eb;
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.transaction-box .middle-row {
  border-bottom: 1px solid #e5e7eb;
  color: #374151;
}

.transaction-box .bottom-row {
  padding-top: 12px;
  padding-bottom: 12px;
}

.row + .row {
  margin-top: 2px;
}


.code {
  font-weight: 600;
}

.date {
  color: #6b7280;
}

.status {
  font-weight: 600;
}

.status.success {
  color: #16a34a;
}

.status.cancelled {
  color: #ef4444;
}

.btn-de {
  padding: 6px 18px;
  border-radius: 8px;
  border: none;
  font-size: 14px;
  cursor: pointer;
  color: #fff;
  min-width: 100px;
  text-align: center;
}

.btn-blue {
  background-color: #2563eb;
}

.amount {
  font-weight: 700;
  color: #e11d48;
  font-size: 15px;
}
</style>

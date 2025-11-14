// js/pages/wallet.js
document.addEventListener('DOMContentLoaded', () => {
    const card = document.querySelector('.wallet-card');
    const token = localStorage.getItem('jwt_token');

    // BẮT BUỘC KIỂM TRA
    if (!card) {
        console.error('LỖI: Không tìm thấy .wallet-card trong HTML');
        return;
    }

    if (!token) {
        card.innerHTML = '<p style="color:red">Chưa đăng nhập!</p>';
        return;
    }

    async function loadBalance() {
        try {
            const res = await fetch('http://localhost/tradeproxy/wordpress-6.8.3-vi/wordpress/wp-json/my-api/v1/wallet/balance', {
                headers: { 'Authorization': `Bearer ${token}` }
            });

            if (!res.ok) {
                const err = await res.json();
                throw new Error(err.message || 'Lỗi server');
            }

            const data = await res.json();

            card.innerHTML = `
                <div class="wallet-card-header">ATM</div>
                <div class="wallet-card-body">
                    <p class="wallet-balance-label">Số tiền hiện có</p>
                    <h3 class="wallet-balance-amount">${data.formatted}</h3>
                    <small>*Nạp thêm tiền để sử dụng dịch vụ</small>
                </div>
            `;

        } catch (err) {
            card.innerHTML = `<p style="color:red">Lỗi: ${err.message}</p>`;
            console.error('Wallet error:', err);
        }
    }

    loadBalance();
});
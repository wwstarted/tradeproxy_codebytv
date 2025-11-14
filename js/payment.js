// Copy to clipboard functionality
document.addEventListener("click", function (e) {
  const copyBtn = e.target.closest(".copy-btn");
  if (!copyBtn) return;

  const textToCopy = copyBtn.dataset.copy;

  // Copy to clipboard
  navigator.clipboard
    .writeText(textToCopy)
    .then(() => {
      showToast("Đã sao chép: " + textToCopy);
    })
    .catch((err) => {
      console.error("Failed to copy:", err);
      showToast("Không thể sao chép!", "error");
    });
});

// Show toast notification
function showToast(message, type = "success") {
  // Remove existing toast
  const existingToast = document.querySelector(".copy-toast");
  if (existingToast) {
    existingToast.remove();
  }

  // Create new toast
  const toast = document.createElement("div");
  toast.className = "copy-toast";
  if (type === "error") {
    toast.style.background = "#dc2626";
  }

  toast.innerHTML = `
    <i class="fa-solid fa-check-circle"></i>
    <span>${message}</span>
  `;

  document.body.appendChild(toast);

  // Remove after 3 seconds
  setTimeout(() => {
    toast.style.animation = "slideOut 0.3s ease";
    setTimeout(() => {
      toast.remove();
    }, 300);
  }, 3000);
}

// Add slideOut animation
const style = document.createElement("style");
style.textContent = `
  @keyframes slideOut {
    from {
      transform: translateX(0);
      opacity: 1;
    }
    to {
      transform: translateX(400px);
      opacity: 0;
    }
  }
`;
document.head.appendChild(style);

// Payment Timer Countdown
function startPaymentTimer(minutes = 25) {
  const timerElement = document.getElementById("paymentTimer");
  if (!timerElement) return;

  let totalSeconds = minutes * 60;

  const countdown = setInterval(() => {
    totalSeconds--;

    const mins = Math.floor(totalSeconds / 60);
    const secs = totalSeconds % 60;

    // Format time as MM:SS
    timerElement.textContent = `${mins.toString().padStart(2, "0")}:${secs
      .toString()
      .padStart(2, "0")}`;

    // Change color when time is running out
    if (totalSeconds <= 300) {
      // Last 5 minutes
      timerElement.style.color = "#dc2626";
    }

    // Timer expired
    if (totalSeconds <= 0) {
      clearInterval(countdown);
      timerElement.textContent = "00:00";
      handlePaymentExpired();
    }
  }, 1000);
}

// Handle payment expiration
function handlePaymentExpired() {
  showToast("Phiên thanh toán đã hết hạn!", "error");

  // You can add more actions here like:
  // - Disable the page
  // - Show a modal
  // - Redirect to another page
  setTimeout(() => {
    if (
      confirm(
        "Phiên thanh toán đã hết hạn. Bạn có muốn tạo đơn hàng mới không?"
      )
    ) {
      // Redirect to cart or create new payment
      window.location.href = "cart.html";
    }
  }, 1000);
}

// Initialize timer when page loads
document.addEventListener("DOMContentLoaded", function () {
  startPaymentTimer(25); // 25 minutes countdown

  // Check payment status periodically (optional)
  checkPaymentStatus();
});

// Check payment status (simulate)
function checkPaymentStatus() {
  // In real application, this would make API calls to check payment status
  const checkInterval = setInterval(() => {
    // Simulate random payment success for demo
    // Remove this in production
    const randomCheck = Math.random();
    if (randomCheck > 0.99) {
      // 1% chance per check
      clearInterval(checkInterval);
      handlePaymentSuccess();
    }
  }, 5000); // Check every 5 seconds
}

// Handle successful payment
// function handlePaymentSuccess() {
//   const statusElement = document.querySelector(".payment-status");
//   if (statusElement) {
//     statusElement.innerHTML = `
//       Đã thanh toán
//       <i class="fa-solid fa-check-circle"></i>
//     `;
//     statusElement.style.color = "#10b981";
//   }

//   showToast("Thanh toán thành công!", "success");

//   setTimeout(() => {
//     if (confirm("Thanh toán thành công! Chuyển đến trang đơn hàng?")) {
//       // Redirect to orders page
//       window.location.href = "orders.html";
//     }
//   }, 1500);
// }

// Get payment info from URL params or localStorage (for demo)
// function getPaymentInfo() {
//   const urlParams = new URLSearchParams(window.location.search);

//   return {
//     amount: urlParams.get("amount") || "50,000đ",
//     orderId: urlParams.get("orderId") || "62ZFKY34Z",
//     accountNumber: urlParams.get("account") || "96386910064",
//     accountName: urlParams.get("name") || "Huỳnh Văn Tuấn",
//     bank: urlParams.get("bank") || "BIDV",
//   };
// }

// Update payment info if passed via URL
const paymentInfo = getPaymentInfo();
console.log("Payment Info:", paymentInfo);

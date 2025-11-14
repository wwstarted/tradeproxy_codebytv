// Cart functionality
let cartItems = [
  {
    id: 1,
    name: "9proxy",
    description: "(50 ip)",
    price: 50000,
    priceUSD: 3.2,
    quantity: 1,
    selected: true,
  },
];

let discountCode = null;
let discountValue = 0;

// Format currency
function formatCurrency(amount) {
  return amount.toLocaleString("vi-VN") + "đ";
}

function formatUSD(amount) {
  return amount.toFixed(1) + "$";
}

// Calculate totals
function calculateTotals() {
  let subtotal = 0;
  let subtotalUSD = 0;
  let selectedCount = 0;

  cartItems.forEach((item) => {
    if (item.selected) {
      subtotal += item.price * item.quantity;
      subtotalUSD += item.priceUSD * item.quantity;
      selectedCount++;
    }
  });

  // Apply discount
  let discountAmount = 0;
  if (discountCode) {
    discountAmount = subtotal * (discountValue / 100);
  }

  let finalTotal = subtotal - discountAmount;
  let finalTotalUSD = subtotalUSD - subtotalUSD * (discountValue / 100);

  return {
    subtotal,
    subtotalUSD,
    discountAmount,
    finalTotal,
    finalTotalUSD,
    selectedCount,
  };
}

// Update UI
function updateCartUI() {
  const totals = calculateTotals();

  // Update summary
  document.getElementById("subtotalAmount").textContent = formatCurrency(
    totals.subtotal
  );
  document.getElementById("discountValue").textContent = formatCurrency(
    totals.discountAmount
  );
  document.getElementById("discountPercent").textContent = "0$";
  document.getElementById("finalTotal").textContent = formatCurrency(
    totals.finalTotal
  );
  document.getElementById("finalDiscount").textContent =
    "≈ " + formatUSD(totals.finalTotalUSD);

  // Update footer count
  const footerCount = document.querySelector(".cart-footer-count");
  if (footerCount) {
    footerCount.textContent = totals.selectedCount;
  }

  // Update item total prices
  document.querySelectorAll(".cart-item").forEach((itemEl, index) => {
    const item = cartItems[index];
    if (item) {
      const totalAmount = itemEl.querySelector(".total-amount");
      const totalDiscount = itemEl.querySelector(".total-discount");

      if (totalAmount) {
        totalAmount.textContent = formatCurrency(item.price * item.quantity);
      }
      if (totalDiscount) {
        totalDiscount.innerHTML = `<img src="https://tradeproxy.vn/images/icon/tether.png" alt="" class="w-3"> ${formatUSD(
          item.priceUSD * item.quantity
        )}`;
      }
    }
  });
}

// Quantity buttons
document.addEventListener("click", function (e) {
  const quantityBtn = e.target.closest(".quantity-btn");
  if (!quantityBtn) return;

  const cartItem = quantityBtn.closest(".cart-item");
  const itemIndex = Array.from(document.querySelectorAll(".cart-item")).indexOf(
    cartItem
  );
  const quantityInput = cartItem.querySelector(".quantity-input");
  const action = quantityBtn.dataset.action;

  if (action === "increase") {
    cartItems[itemIndex].quantity++;
    quantityInput.value = cartItems[itemIndex].quantity;
  } else if (action === "decrease" && cartItems[itemIndex].quantity > 1) {
    cartItems[itemIndex].quantity--;
    quantityInput.value = cartItems[itemIndex].quantity;
  }

  updateCartUI();
});

// Delete button
document.addEventListener("click", function (e) {
  const deleteBtn = e.target.closest(".delete-btn");
  if (!deleteBtn) return;

  if (confirm("Bạn có chắc muốn xóa sản phẩm này?")) {
    const cartItem = deleteBtn.closest(".cart-item");
    const itemIndex = Array.from(
      document.querySelectorAll(".cart-item")
    ).indexOf(cartItem);

    cartItems.splice(itemIndex, 1);
    cartItem.remove();

    updateCartUI();

    // Show empty cart if no items
    if (cartItems.length === 0) {
      showEmptyCart();
    }
  }
});

// Checkbox handling
document.addEventListener("change", function (e) {
  if (e.target.classList.contains("item-checkbox")) {
    const cartItem = e.target.closest(".cart-item");
    const itemIndex = Array.from(
      document.querySelectorAll(".cart-item")
    ).indexOf(cartItem);
    cartItems[itemIndex].selected = e.target.checked;
    updateCartUI();
  }

  // Select all
  if (e.target.id === "selectAll") {
    const isChecked = e.target.checked;
    document.querySelectorAll(".item-checkbox").forEach((checkbox, index) => {
      checkbox.checked = isChecked;
      cartItems[index].selected = isChecked;
    });
    updateCartUI();
  }
});

// Apply discount code
document
  .getElementById("applyDiscount")
  ?.addEventListener("click", function () {
    const codeInput = document.getElementById("discountCode");
    const code = codeInput.value.trim().toUpperCase();
    const tooltip = document.getElementById("discountTooltip");

    // Mock discount codes
    const validCodes = {
      SAVE10: 10,
      SAVE20: 20,
      WELCOME: 15,
    };

    if (validCodes[code]) {
      discountCode = code;
      discountValue = validCodes[code];
      document.getElementById("discountName").textContent = code;

      // Show success tooltip
      tooltip.textContent = `Áp dụng mã giảm ${discountValue}% thành công!`;
      tooltip.style.display = "block";
      tooltip.style.background = "#10b981";

      setTimeout(() => {
        tooltip.style.display = "none";
      }, 3000);

      updateCartUI();
    } else {
      // Show error tooltip
      tooltip.textContent = "Mã giảm giá không hợp lệ!";
      tooltip.style.display = "block";
      tooltip.style.background = "#dc2626";

      setTimeout(() => {
        tooltip.style.display = "none";
      }, 3000);
    }
  });

// Show discount tooltip on hover
const discountInput = document.getElementById("discountCode");
if (discountInput) {
  discountInput.addEventListener("focus", function () {
    const tooltip = document.getElementById("discountTooltip");
    if (!discountCode) {
      tooltip.textContent = "Nhận mã giảm giá !";
      tooltip.style.background = "#2563eb";
      tooltip.style.display = "block";
    }
  });

  discountInput.addEventListener("blur", function () {
    setTimeout(() => {
      const tooltip = document.getElementById("discountTooltip");
      if (!discountCode) {
        tooltip.style.display = "none";
      }
    }, 200);
  });
}
// Show empty cart
function showEmptyCart() {
  const cartItems = document.querySelector(".cart-items");
  cartItems.innerHTML = `
    <div class="empty-cart">
      <div class="empty-cart-icon">
        <i class="fa-solid fa-cart-shopping"></i>
      </div>
      <h2>Giỏ hàng trống</h2>
      <p>Bạn chưa có sản phẩm nào trong giỏ hàng</p>
      <a href="#" class="shop-now-btn">Mua sắm ngay</a>
    </div>
  `;
}

// Initialize
updateCartUI();

// Create error message element
function createErrorMessage() {
  let errorMsg = document.getElementById("emailError");

  if (!errorMsg) {
    errorMsg = document.createElement("div");
    errorMsg.id = "emailError";
    errorMsg.style.cssText =
      "color: #dc2626; font-size: 14px; margin-bottom: 10px; display: none;";
    const emailInput = document.getElementById("customerEmail");
    emailInput.parentNode.insertBefore(errorMsg, emailInput.nextSibling);
  }

  return errorMsg;
}

// Show error message
function showError(message) {
  const errorMsg = createErrorMessage();
  errorMsg.textContent = message;
  errorMsg.style.display = "block";

  const emailInput = document.getElementById("customerEmail");
  emailInput.style.borderColor = "#dc2626";
}

// Hide error message
function hideError() {
  const errorMsg = document.getElementById("emailError");
  if (errorMsg) {
    errorMsg.style.display = "none";
  }

  const emailInput = document.getElementById("customerEmail");
  emailInput.style.borderColor = "";
}

// Clear error when user types
document
  .getElementById("customerEmail")
  ?.addEventListener("input", function () {
    hideError();
  });

// Payment Modal functionality
const paymentModal = document.getElementById("paymentModal");
const closePaymentModalBtn = document.getElementById("closePaymentModal");
const cancelPaymentBtn = document.getElementById("cancelPayment");
const confirmPaymentBtn = document.getElementById("confirmPayment");
const paymentMethods = document.querySelectorAll(".payment-method");
let selectedPaymentMethod = null;

// Open payment modal when checkout button is clicked
document.getElementById("checkoutBtn")?.addEventListener("click", function (e) {
  e.preventDefault();

  // Reset error state
  hideError();

  const email = document.getElementById("customerEmail").value.trim();
  const totals = calculateTotals();

  // Validate email first
  if (!email) {
    showError("Vui lòng nhập email!");
    document.getElementById("customerEmail").focus();
    return;
  }

  if (!email.includes("@")) {
    showError("Email không hợp lệ!");
    document.getElementById("customerEmail").focus();
    return;
  }

  // Then check if products are selected
  if (totals.selectedCount === 0) {
    alert("Vui lòng chọn ít nhất 1 sản phẩm!");
    return;
  }

  // All validations passed - show payment modal
  paymentModal.classList.add("active");
  document.body.style.overflow = "hidden";
});

// Close modal functions
function closePaymentModal() {
  paymentModal.classList.remove("active");
  document.body.style.overflow = "";
  selectedPaymentMethod = null;
  paymentMethods.forEach((method) => method.classList.remove("selected"));
  confirmPaymentBtn.classList.remove("active");
}

closePaymentModalBtn.addEventListener("click", closePaymentModal);
cancelPaymentBtn.addEventListener("click", closePaymentModal);

// Close modal when clicking outside
paymentModal.addEventListener("click", function (e) {
  if (e.target === paymentModal) {
    closePaymentModal();
  }
});

// Payment method selection
paymentMethods.forEach((method) => {
  method.addEventListener("click", function () {
    paymentMethods.forEach((m) => m.classList.remove("selected"));
    this.classList.add("selected");
    selectedPaymentMethod = this.dataset.method;
    confirmPaymentBtn.classList.add("active");
  });
});

// Confirm payment
// confirmPaymentBtn.addEventListener("click", function () {
//   if (!selectedPaymentMethod) {
//     alert("Vui lòng chọn phương thức thanh toán!");
//     return;
//   }

//   const email = document.getElementById("customerEmail").value.trim();
//   const totals = calculateTotals();

//   // Process payment
//   alert(
//     `Đang xử lý thanh toán cho ${
//       totals.selectedCount
//     } sản phẩm\nPhương thức: ${selectedPaymentMethod}\nTổng: ${formatCurrency(
//       totals.finalTotal
//     )}\nEmail: ${email}`
//   );

//   console.log("Payment data:", {
//     items: cartItems.filter((item) => item.selected),
//     email: email,
//     paymentMethod: selectedPaymentMethod,
//     discountCode: discountCode,
//     totals: totals,
//   });

//   closePaymentModal();
// });

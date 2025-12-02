// Cart functionality
let cartItems = [];
let discountCode = null;
let discountValue = 0;

// Format currency
function formatCurrency(amount) {
  return Number(amount).toLocaleString("vi-VN") + "đ";
}

function formatUSD(amount) {
  return Number(amount).toFixed(1) + "$";
}

// Load cart items from WooCommerce data
function loadWooCartData() {
  if (typeof wooCartData !== 'undefined' && wooCartData.length > 0) {
    cartItems = wooCartData.map(item => ({
      cart_item_key: item.cart_item_key,
      id: item.product_id,
      name: item.name,
      description: item.description,
      price: parseFloat(item.price),
      priceUSD: parseFloat(item.priceUSD),
      quantity: parseInt(item.quantity),
      selected: true,
      image: item.image,
      permalink: item.permalink,
      remove_url: item.remove_url,
      max_quantity: item.max_quantity
    }));
    
    renderCartItems();
    
    // Load applied coupons
    if (typeof wooCoupons !== 'undefined' && wooCoupons.length > 0) {
      discountCode = wooCoupons[0];
      document.getElementById("discountName").textContent = discountCode;
    }
  } else {
    showEmptyCart();
  }
}

// Render cart items
function renderCartItems() {
  const wrapper = document.getElementById('cart-items-wrapper');
  
  if (!wrapper) return;
  
  if (cartItems.length === 0) {
    showEmptyCart();
    return;
  }
  
  wrapper.innerHTML = cartItems.map((item, index) => `
    <div class="cart-item" data-index="${index}" data-cart-key="${item.cart_item_key}">
      <div class="btn-checkbox">
        <input type="checkbox" class="cart-checkbox item-checkbox" ${item.selected ? 'checked' : ''} />
      </div>

      <div class="cart-item-info">
        <div class="cart-item-logo">
          ${item.permalink ? `<a href="${item.permalink}">` : ''}
            <img src="${item.image || 'https://tradeproxy.vn/images/logo/9proxy.png'}" alt="${item.name}" />
          ${item.permalink ? '</a>' : ''}
        </div>
        <div class="cart-item-details">
          <h3>${item.permalink ? `<a href="${item.permalink}">${item.name}</a>` : item.name}</h3>
          ${item.description ? `<p>${item.description}</p>` : ''}
        </div>
      </div>

      <div class="cart-quantity">
        <div class="cart-quantity-mobile">
          <button class="quantity-btn minus-btn" data-action="decrease">
            <i class="fa-solid fa-minus"></i>
          </button>
          <input type="number" class="quantity-input" value="${item.quantity}" min="1" max="${item.max_quantity || 9999}" readonly />
          <button class="quantity-btn plus-btn" data-action="increase">
            <i class="fa-solid fa-plus"></i>
          </button>
        </div>
      </div>

      <div class="cart-price">
        <div class="cart-price-mobile">
          <div class="original-price">${formatCurrency(item.price)}</div>
          ${item.priceUSD ? `
            <div class="discount-badge-cart">
              <img src="https://tradeproxy.vn/images/icon/tether.png" alt="" />
              ${formatUSD(item.priceUSD)}
            </div>
          ` : ''}
        </div>
      </div>

      <div class="cart-total-price">
        <div>
          <div class="total-amount">${formatCurrency(item.price * item.quantity)}</div>
          ${item.priceUSD ? `
            <div class="total-discount">
              <img src="https://tradeproxy.vn/images/icon/tether.png" alt="" />
              ${formatUSD(item.priceUSD * item.quantity)}
            </div>
          ` : ''}
        </div>
      </div>

      <div class="cart-delete-btn">
        <button class="delete-btn" title="Xóa sản phẩm" data-remove-url="${item.remove_url}">
          <i class="fa-solid fa-trash"></i>
        </button>
      </div>
    </div>
  `).join('');
  
  updateCartUI();
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
  if (discountCode && typeof wooCartTotal !== 'undefined') {
    discountAmount = wooCartSubtotal - wooCartTotal;
  }

  let finalTotal = subtotal - discountAmount;
  let finalTotalUSD = subtotalUSD - (subtotalUSD * (discountAmount / subtotal));

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
  document.getElementById("subtotalAmount").textContent = formatCurrency(totals.subtotal);
  document.getElementById("discountValue").textContent = formatCurrency(totals.discountAmount);
  document.getElementById("finalTotal").textContent = formatCurrency(totals.finalTotal);
  document.getElementById("finalDiscount").textContent = "≈ " + formatUSD(totals.finalTotalUSD);

  // Update footer count
  const footerCount = document.querySelector(".cart-footer-count");
  if (footerCount) {
    footerCount.textContent = totals.selectedCount;
  }
}

// Update WooCommerce cart quantity via hidden form
function updateWooCartQuantity(cartItemKey, newQuantity) {
  const hiddenInput = document.querySelector(`.hidden-qty-input[data-key="${cartItemKey}"]`);
  if (hiddenInput) {
    hiddenInput.value = newQuantity;
    document.getElementById('hidden-update-cart-btn').click();
  }
}

// Quantity buttons
document.addEventListener("click", function (e) {
  const quantityBtn = e.target.closest(".quantity-btn");
  if (!quantityBtn) return;

  const cartItem = quantityBtn.closest(".cart-item");
  const itemIndex = parseInt(cartItem.dataset.index);
  const cartItemKey = cartItem.dataset.cartKey;
  const quantityInput = cartItem.querySelector(".quantity-input");
  const action = quantityBtn.dataset.action;

  if (action === "increase") {
    const max = parseInt(quantityInput.getAttribute('max')) || 9999;
    if (cartItems[itemIndex].quantity < max) {
      cartItems[itemIndex].quantity++;
      quantityInput.value = cartItems[itemIndex].quantity;
      updateWooCartQuantity(cartItemKey, cartItems[itemIndex].quantity);
    }
  } else if (action === "decrease" && cartItems[itemIndex].quantity > 1) {
    cartItems[itemIndex].quantity--;
    quantityInput.value = cartItems[itemIndex].quantity;
    updateWooCartQuantity(cartItemKey, cartItems[itemIndex].quantity);
  }

  updateCartUI();
});

// Delete button
document.addEventListener("click", function (e) {
  const deleteBtn = e.target.closest(".delete-btn");
  if (!deleteBtn) return;

  if (confirm("Bạn có chắc muốn xóa sản phẩm này?")) {
    const removeUrl = deleteBtn.dataset.removeUrl;
    if (removeUrl) {
      window.location.href = removeUrl;
    }
  }
});

// Checkbox handling
document.addEventListener("change", function (e) {
  if (e.target.classList.contains("item-checkbox")) {
    const cartItem = e.target.closest(".cart-item");
    const itemIndex = parseInt(cartItem.dataset.index);
    cartItems[itemIndex].selected = e.target.checked;
    updateCartUI();
  }

  // Select all
  if (e.target.id === "selectAll") {
    const isChecked = e.target.checked;
    document.querySelectorAll(".item-checkbox").forEach((checkbox, index) => {
      checkbox.checked = isChecked;
      if (cartItems[index]) {
        cartItems[index].selected = isChecked;
      }
    });
    updateCartUI();
  }
});

// Apply discount code
document.getElementById("applyDiscount")?.addEventListener("click", function () {
  const codeInput = document.getElementById("discountCode");
  const code = codeInput.value.trim();
  const tooltip = document.getElementById("discountTooltip");

  if (!code) {
    tooltip.textContent = "Vui lòng nhập mã giảm giá!";
    tooltip.style.display = "block";
    tooltip.style.background = "#dc2626";
    setTimeout(() => tooltip.style.display = "none", 3000);
    return;
  }

  // Submit WooCommerce coupon form
  document.getElementById("hidden-coupon-code").value = code;
  document.getElementById("hidden-apply-coupon-btn").click();
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
  const wrapper = document.getElementById('cart-items-wrapper');
  if (wrapper) {
    wrapper.innerHTML = `
      <div class="empty-cart" style="text-align: center; padding: 60px 20px; grid-column: 1 / -1;">
        <div class="empty-cart-icon" style="font-size: 80px; color: #ddd; margin-bottom: 20px;">
          <i class="fa-solid fa-cart-shopping"></i>
        </div>
        <h2 style="font-size: 24px; margin-bottom: 10px;">Giỏ hàng trống</h2>
        <p style="color: #666; margin-bottom: 30px;">Bạn chưa có sản phẩm nào trong giỏ hàng</p>
        <a href="<?php echo home_url('/home'); ?>" class="btn-primary-proxy" style="display: inline-block; padding: 12px 30px; text-decoration: none;">
          Mua sắm ngay
        </a>
      </div>
    `;
  }
}

// Email validation functions
function createErrorMessage() {
  let errorMsg = document.getElementById("emailError");
  if (!errorMsg) {
    errorMsg = document.createElement("div");
    errorMsg.id = "emailError";
    errorMsg.style.cssText = "color: #dc2626; font-size: 14px; margin-bottom: 10px; display: none;";
    const emailInput = document.getElementById("customerEmail");
    emailInput.parentNode.insertBefore(errorMsg, emailInput.nextSibling);
  }
  return errorMsg;
}

function showError(message) {
  const errorMsg = createErrorMessage();
  errorMsg.textContent = message;
  errorMsg.style.display = "block";
  document.getElementById("customerEmail").style.borderColor = "#dc2626";
}

function hideError() {
  const errorMsg = document.getElementById("emailError");
  if (errorMsg) errorMsg.style.display = "none";
  document.getElementById("customerEmail").style.borderColor = "";
}

document.getElementById("customerEmail")?.addEventListener("input", hideError);

// Payment Modal functionality
const paymentModal = document.getElementById("paymentModal");
const closePaymentModalBtn = document.getElementById("closePaymentModal");
const cancelPaymentBtn = document.getElementById("cancelPayment");
const confirmPaymentBtn = document.getElementById("confirmPayment");
const paymentMethods = document.querySelectorAll(".payment-method");
let selectedPaymentMethod = null;

document.getElementById("checkoutBtn")?.addEventListener("click", function (e) {
  e.preventDefault();
  hideError();

  const email = document.getElementById("customerEmail").value.trim();
  const totals = calculateTotals();

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

  if (totals.selectedCount === 0) {
    alert("Vui lòng chọn ít nhất 1 sản phẩm!");
    return;
  }

  // Store email in session/cookie if needed
  sessionStorage.setItem('checkout_email', email);
  
  // Redirect to checkout
  window.location.href = wooCheckoutUrl;
});

function closePaymentModal() {
  paymentModal.classList.remove("active");
  document.body.style.overflow = "";
  selectedPaymentMethod = null;
  paymentMethods.forEach((method) => method.classList.remove("selected"));
  confirmPaymentBtn.classList.remove("active");
}

closePaymentModalBtn?.addEventListener("click", closePaymentModal);
cancelPaymentBtn?.addEventListener("click", closePaymentModal);

paymentModal?.addEventListener("click", function (e) {
  if (e.target === paymentModal) closePaymentModal();
});

paymentMethods.forEach((method) => {
  method.addEventListener("click", function () {
    paymentMethods.forEach((m) => m.classList.remove("selected"));
    this.classList.add("selected");
    selectedPaymentMethod = this.dataset.method;
    confirmPaymentBtn.classList.add("active");
  });
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
  console.log('=== TradeProxy Cart Initialized ===');
  loadWooCartData();
});
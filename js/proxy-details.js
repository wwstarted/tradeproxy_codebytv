// Package Tab Switching
const packageTabs = document.querySelectorAll(".package-tab");
packageTabs.forEach((tab) => {
  tab.addEventListener("click", function () {
    packageTabs.forEach((t) => t.classList.remove("active"));
    this.classList.add("active");
  });
});

// Quantity Button Selection
const quantityBtns = document.querySelectorAll(".quantity-btn");
quantityBtns.forEach((btn) => {
  btn.addEventListener("click", function () {
    quantityBtns.forEach((b) => b.classList.remove("active"));
    this.classList.add("active");
    calculatePrice();
  });
});

// Duration Button Selection
const durationBtns = document.querySelectorAll(".duration-btn");
durationBtns.forEach((btn) => {
  btn.addEventListener("click", function () {
    durationBtns.forEach((b) => b.classList.remove("active"));
    this.classList.add("active");
    calculatePrice();
  });
});

// Price Calculation (Example)
function calculatePrice() {
  const activeQuantity = document.querySelector(".quantity-btn.active");
  const activeDuration = document.querySelector(".duration-btn.active");

  if (activeQuantity && activeDuration) {
    // Extract numbers from text
    const quantity = parseInt(activeQuantity.textContent);
    const duration = parseInt(activeDuration.textContent);

    // Simple calculation (you can adjust the formula)
    const basePrice = 3000; // Base price per IP per month
    const total = quantity * duration * basePrice;

    // Format and display price
    const priceAmount = document.querySelector(".price-amount");
    if (priceAmount) {
      priceAmount.innerHTML = `
        ${total.toLocaleString("vi-VN")}đ
        <span class="price-unit">VNĐ</span>
      `;
    }
  }
}

// Related Product Click Handler
const relatedProducts = document.querySelectorAll(".related-product-item");
relatedProducts.forEach((product) => {
  product.addEventListener("click", function () {
    // Add your navigation logic here
    console.log(
      "Clicked product:",
      this.querySelector(".related-product-name").textContent
    );
  });
});

// Buy Now Button
const buyNowBtn = document.querySelector(".btn-primary-proxy");
if (buyNowBtn) {
  buyNowBtn.addEventListener("click", function () {
    // Add your purchase logic here
    alert("Chức năng mua hàng đang được phát triển");
  });
}

// Add to Cart Button
const addToCartBtn = document.querySelector(".btn-secondary");
if (addToCartBtn) {
  addToCartBtn.addEventListener("click", function () {
    // Add your cart logic here
    alert("Đã thêm vào giỏ hàng");
  });
}

// Initialize price on page load
calculatePrice();

// Slide proxies
const detailCardsWrapper = document.getElementById("detailCardsWrapper");
const detailPrevBtn = document.getElementById("detailPrevBtn");
const detailNextBtn = document.getElementById("detailNextBtn");

// Scroll amount (adjust based on card width + gap)
const detailScrollAmount = 350;

// Check scroll position and update button states
function updateDetailButtons() {
  const scrollLeft = detailCardsWrapper.scrollLeft;
  const maxScroll =
    detailCardsWrapper.scrollWidth - detailCardsWrapper.clientWidth;

  detailPrevBtn.disabled = scrollLeft <= 0;
  detailNextBtn.disabled = scrollLeft >= maxScroll - 1;
}

// Previous button click
detailPrevBtn.addEventListener("click", () => {
  detailCardsWrapper.scrollBy({
    left: -detailScrollAmount,
    behavior: "smooth",
  });
});

// Next button click
detailNextBtn.addEventListener("click", () => {
  detailCardsWrapper.scrollBy({
    left: detailScrollAmount,
    behavior: "smooth",
  });
});

// Update buttons on scroll
detailCardsWrapper.addEventListener("scroll", updateDetailButtons);

// Initial button state
updateDetailButtons();

// Touch/drag scroll support
let detailIsDown = false;
let detailStartX;
let detailScrollLeft;

detailCardsWrapper.addEventListener("mousedown", (e) => {
  detailIsDown = true;
  detailCardsWrapper.style.cursor = "grabbing";
  detailStartX = e.pageX - detailCardsWrapper.offsetLeft;
  detailScrollLeft = detailCardsWrapper.scrollLeft;
});

detailCardsWrapper.addEventListener("mouseleave", () => {
  detailIsDown = false;
  detailCardsWrapper.style.cursor = "grab";
});

detailCardsWrapper.addEventListener("mouseup", () => {
  detailIsDown = false;
  detailCardsWrapper.style.cursor = "grab";
});

detailCardsWrapper.addEventListener("mousemove", (e) => {
  if (!detailIsDown) return;
  e.preventDefault();
  const x = e.pageX - detailCardsWrapper.offsetLeft;
  const walk = (x - detailStartX) * 2;
  detailCardsWrapper.scrollLeft = detailScrollLeft - walk;
});

// Buy button handlers
document.querySelectorAll(".detail-proxy-btn-buy").forEach((btn) => {
  btn.addEventListener("click", function () {
    const cardTitle = this.closest(".detail-proxy-card").querySelector(
      ".detail-proxy-card-title"
    ).textContent;
    alert(`Đang chuyển đến trang mua ${cardTitle}...`);
  });
});
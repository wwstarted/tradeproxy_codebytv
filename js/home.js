// Scroll bar list proxy
const container = document.getElementById("scrollContainer");
let isDown = false;
let startScroll;
let scrollLeft;

container.addEventListener("mousedown", (e) => {
  isDown = true;
  container.classList.add("dragging");
  startScroll = e.pageX - container.offsetLeft;
  scrollLeft = container.scrollLeft;
});

container.addEventListener("mouseleave", () => {
  isDown = false;
  container.classList.remove("dragging");
});

container.addEventListener("mouseup", () => {
  isDown = false;
  container.classList.remove("dragging");
});

container.addEventListener("mousemove", (e) => {
  if (!isDown) return;
  e.preventDefault();
  const x = e.pageX - container.offsetLeft;
  const walk = (x - startScroll) * 2;
  container.scrollLeft = scrollLeft - walk;
});

// Hỗ trợ touch cho mobile
let touchStartScroll = 0;
let touchScrollLeft = 0;

container.addEventListener("touchstart", (e) => {
  touchStartScroll = e.touches[0].pageX - container.offsetLeft;
  touchScrollLeft = container.scrollLeft;
});

container.addEventListener("touchmove", (e) => {
  const x = e.touches[0].pageX - container.offsetLeft;
  const walk = (x - touchStartScroll) * 2;
  container.scrollLeft = touchScrollLeft - walk;
});

// Slide pricing plans
const wrapper = document.getElementById("cardsWrapper");
const prevBtn = document.getElementById("prevBtn");
const nextBtn = document.getElementById("nextBtn");
const cards = document.querySelectorAll(".card");

let currentIndex = 0;
let startX = 0;
let currentX = 0;
let isDragging = false;
let cardsPerView = 3;

function updateCardsPerView() {
  const width = window.innerWidth;
  if (width <= 768) {
    cardsPerView = 1;
  } else if (width <= 1024) {
    cardsPerView = 2;
  } else {
    cardsPerView = 3;
  }
  updateCarousel();
}

function updateCarousel() {
  if (!cards.length) return;

  const cardWidth = cards[0].offsetWidth;
  const gap = window.innerWidth <= 768 ? 20 : 30;
  const offset = -(currentIndex * (cardWidth + gap));
  wrapper.style.transform = `translateX(${offset}px)`;

  updateButtons();
}

function updateButtons() {
  const maxIndex = cards.length - cardsPerView;
  prevBtn.disabled = currentIndex <= 0;
  nextBtn.disabled = currentIndex >= maxIndex;
}

prevBtn.addEventListener("click", () => {
  if (currentIndex > 0) {
    currentIndex--;
    updateCarousel();
  }
});

nextBtn.addEventListener("click", () => {
  const maxIndex = cards.length - cardsPerView;
  if (currentIndex < maxIndex) {
    currentIndex++;
    updateCarousel();
  }
});

// Touch/Mouse drag functionality
wrapper.addEventListener("mousedown", startDrag);
wrapper.addEventListener("touchstart", startDrag);
wrapper.addEventListener("mousemove", drag);
wrapper.addEventListener("touchmove", drag);
wrapper.addEventListener("mouseup", endDrag);
wrapper.addEventListener("touchend", endDrag);
wrapper.addEventListener("mouseleave", endDrag);

function startDrag(e) {
  isDragging = true;
  startX = e.type.includes("mouse") ? e.pageX : e.touches[0].pageX;
  wrapper.style.transition = "none";
}

function drag(e) {
  if (!isDragging) return;
  e.preventDefault();

  currentX = e.type.includes("mouse") ? e.pageX : e.touches[0].pageX;
  const diff = currentX - startX;

  const cardWidth = cards[0].offsetWidth;
  const gap = window.innerWidth <= 768 ? 20 : 30;
  const currentOffset = -(currentIndex * (cardWidth + gap));
  wrapper.style.transform = `translateX(${currentOffset + diff}px)`;
}

function endDrag(e) {
  if (!isDragging) return;
  isDragging = false;

  const diff = currentX - startX;
  const threshold = 50;

  wrapper.style.transition =
    "transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94)";

  if (Math.abs(diff) > threshold) {
    if (diff > 0 && currentIndex > 0) {
      currentIndex--;
    } else if (diff < 0) {
      const maxIndex = cards.length - cardsPerView;
      if (currentIndex < maxIndex) {
        currentIndex++;
      }
    }
  }

  updateCarousel();
}

window.addEventListener("resize", updateCardsPerView);
updateCardsPerView();

document.querySelectorAll(".btn-buy").forEach((button) => {
  button.addEventListener("click", function (e) {
    e.stopPropagation();
    const cardTitle =
      this.closest(".card").querySelector(".card-title").textContent;
    alert(`Bạn đã chọn gói ${cardTitle}`);
  });
});
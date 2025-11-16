// ============================================
// MOBILE FILTER MODAL
// ============================================

const openFilterBtn = document.getElementById("openFilterModal");
const closeFilterBtn = document.getElementById("closeFilterModal");
const cancelFilterBtn = document.getElementById("cancelFilter");
const applyFilterBtn = document.getElementById("applyFilter");
const filterModal = document.getElementById("filterModal");

// Open modal
if (openFilterBtn) {
  openFilterBtn.addEventListener("click", () => {
    filterModal.classList.add("active");
    document.body.style.overflow = "hidden";
  });
}

// Close modal
function closeFilterModal() {
  filterModal.classList.remove("active");
  document.body.style.overflow = "";
}

if (closeFilterBtn) {
  closeFilterBtn.addEventListener("click", closeFilterModal);
}

if (cancelFilterBtn) {
  cancelFilterBtn.addEventListener("click", closeFilterModal);
}

// Apply filter
if (applyFilterBtn) {
  applyFilterBtn.addEventListener("click", () => {
    // Sync modal filters with sidebar filters
    syncFilters();
    closeFilterModal();
    // Here you would add your filter logic
    console.log("Filters applied");
  });
}

// Close modal when clicking outside
filterModal?.addEventListener("click", (e) => {
  if (e.target === filterModal) {
    closeFilterModal();
  }
});

// Close on ESC key
document.addEventListener("keydown", (e) => {
  if (e.key === "Escape" && filterModal.classList.contains("active")) {
    closeFilterModal();
  }
});

// ============================================
// SYNC FILTERS BETWEEN MODAL AND SIDEBAR
// ============================================

function syncFilters() {
  // Get selected category from modal
  const modalCategory = document.querySelector(
    'input[name="modal-category"]:checked'
  );
  const sidebarCategory = document.querySelector(
    `input[name="category"][id="${modalCategory.id.replace("modal-", "")}"]`
  );
  if (sidebarCategory) {
    sidebarCategory.checked = true;
  }

  // Get selected sort from modal
  const modalSort = document.querySelector('input[name="modal-sort"]:checked');
  const sidebarSort = document.querySelector(
    `input[name="sort"][id="${modalSort.id.replace("modal-", "")}"]`
  );
  if (sidebarSort) {
    sidebarSort.checked = true;
  }

  // Update active classes
  updateActiveClasses();
}

// ============================================
// FILTER FUNCTIONALITY
// ============================================

function updateActiveClasses() {
  // Update category active states
  document.querySelectorAll(".filter-option").forEach((option) => {
    const input = option.querySelector('input[type="radio"]');
    if (input && input.checked) {
      option.classList.add("active");
    } else {
      option.classList.remove("active");
    }
  });
}

// Add event listeners to all radio buttons
document.querySelectorAll('input[type="radio"]').forEach((radio) => {
  radio.addEventListener("change", updateActiveClasses);
});

// ============================================
// CLEAR FILTER
// ============================================

const clearFilterBtn = document.querySelector(".clear-filter");
if (clearFilterBtn) {
  clearFilterBtn.addEventListener("click", (e) => {
    e.preventDefault();

    // Reset all filters to default
    document.querySelectorAll('input[name="category"]').forEach((input) => {
      input.checked = input.id === "cat-all";
    });

    document.querySelectorAll('input[name="sort"]').forEach((input) => {
      input.checked = input.id === "sort-bestseller";
    });

    // Reset modal filters too
    document
      .querySelectorAll('input[name="modal-category"]')
      .forEach((input) => {
        input.checked = input.id === "modal-cat-all";
      });

    document.querySelectorAll('input[name="modal-sort"]').forEach((input) => {
      input.checked = input.id === "modal-sort-bestseller";
    });

    updateActiveClasses();
  });
}

// ============================================
// PAGINATION
// ============================================

const paginationButtons = document.querySelectorAll(
  ".pagination button:not(:disabled)"
);
paginationButtons.forEach((button) => {
  button.addEventListener("click", () => {
    // Remove active class from all buttons
    paginationButtons.forEach((btn) => btn.classList.remove("active"));

    // Add active class to clicked button (if it's a number)
    if (!button.querySelector("i")) {
      button.classList.add("active");
    }

    // Scroll to top
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
});

// ============================================
// PRODUCT CARD CLICK
// ============================================

const productCards = document.querySelectorAll(".product-card");
productCards.forEach((card) => {
  card.addEventListener("click", () => {
    console.log(
      "Product clicked:",
      card.querySelector(".product-name").textContent
    );
    // Here you would navigate to product detail page
  });
});

// Initialize
updateActiveClasses();

console.log("Product proxy page loaded successfully!");
// ============================================
// PROVIDER PAGE FUNCTIONALITY
// ============================================

// Filter Tabs Functionality
document.addEventListener("DOMContentLoaded", function () {
  const filterTabs = document.querySelectorAll(".filter-tab");
  const providerCards = document.querySelectorAll(".provider-card");

  // Add click event to each tab
  filterTabs.forEach((tab) => {
    tab.addEventListener("click", function () {
      // Remove active class from all tabs
      filterTabs.forEach((t) => t.classList.remove("active"));

      // Add active class to clicked tab
      this.classList.add("active");

      // Get filter text
      const filterText = this.textContent.trim().toLowerCase();

      // Filter cards (for now just show/hide animation)
      providerCards.forEach((card) => {
        card.style.animation = "fadeIn 0.5s ease";
      });

      console.log("Filter applied:", filterText);
    });
  });

  // Add animation to cards on load
  providerCards.forEach((card, index) => {
    card.style.animation = `fadeInUp 0.5s ease ${index * 0.05}s both`;
  });

  // Smooth scroll for CTA button
  const ctaButton = document.querySelector(".cta-button");
  if (ctaButton) {
    ctaButton.addEventListener("click", function () {
      console.log("CTA button clicked - Redirect to registration page");
      // Add your registration page URL here
      // window.location.href = '/register';
    });
  }
});

// Add CSS animations dynamically
const style = document.createElement("style");
style.textContent = `
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
    }
    to {
      opacity: 1;
    }
  }
`;
document.head.appendChild(style);

console.log("Provider page loaded successfully!");
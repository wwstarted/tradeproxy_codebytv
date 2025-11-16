// Copy to clipboard functionality
document.addEventListener("click", function (e) {
  const copyBtn = e.target.closest(".contact-copy-btn");
  if (!copyBtn) return;

  const textToCopy = copyBtn.dataset.copy;

  navigator.clipboard
    .writeText(textToCopy)
    .then(() => {
      showToast("Đã sao chép: " + textToCopy, "success");
    })
    .catch((err) => {
      console.error("Failed to copy:", err);
      showToast("Không thể sao chép!", "error");
    });
});

// Show toast notification
function showToast(message, type = "success") {
  // Remove existing toast
  const existingToast = document.querySelector(".contact-toast");
  if (existingToast) {
    existingToast.remove();
  }

  // Create new toast
  const toast = document.createElement("div");
  toast.className = `contact-toast ${type}`;

  const icon = type === "success" ? "fa-check-circle" : "fa-exclamation-circle";

  toast.innerHTML = `
    <i class="fa-solid ${icon}"></i>
    <span>${message}</span>
  `;

  document.body.appendChild(toast);

  // Remove after 3 seconds
  setTimeout(() => {
    toast.style.animation = "slideOutRight 0.3s ease";
    setTimeout(() => {
      toast.remove();
    }, 300);
  }, 3000);
}

// Form validation
function validateEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}

function showError(inputId, errorId, message) {
  const input = document.getElementById(inputId);
  const error = document.getElementById(errorId);

  input.classList.add("error");
  error.textContent = message;
  error.classList.add("show");
}

function clearError(inputId, errorId) {
  const input = document.getElementById(inputId);
  const error = document.getElementById(errorId);

  input.classList.remove("error");
  error.textContent = "";
  error.classList.remove("show");
}

// Clear errors on input
document.getElementById("contactName")?.addEventListener("input", function () {
  clearError("contactName", "nameError");
});

document.getElementById("contactEmail")?.addEventListener("input", function () {
  clearError("contactEmail", "emailError");
});

document
  .getElementById("contactMessage")
  ?.addEventListener("input", function () {
    clearError("contactMessage", "messageError");
  });

// Form submission
document
  .getElementById("contactForm")
  ?.addEventListener("submit", function (e) {
    e.preventDefault();

    // Clear all previous errors
    clearError("contactName", "nameError");
    clearError("contactEmail", "emailError");
    clearError("contactMessage", "messageError");

    // Get form values
    const name = document.getElementById("contactName").value.trim();
    const email = document.getElementById("contactEmail").value.trim();
    const message = document.getElementById("contactMessage").value.trim();

    let hasError = false;

    // Validate name
    if (!name) {
      showError("contactName", "nameError", "Vui lòng nhập tên của bạn");
      hasError = true;
    } else if (name.length < 2) {
      showError("contactName", "nameError", "Tên phải có ít nhất 2 ký tự");
      hasError = true;
    }

    // Validate email
    if (!email) {
      showError("contactEmail", "emailError", "Vui lòng nhập email của bạn");
      hasError = true;
    } else if (!validateEmail(email)) {
      showError("contactEmail", "emailError", "Email không hợp lệ");
      hasError = true;
    }

    // Validate message
    if (!message) {
      showError("contactMessage", "messageError", "Vui lòng nhập nội dung");
      hasError = true;
    } else if (message.length < 10) {
      showError(
        "contactMessage",
        "messageError",
        "Nội dung phải có ít nhất 10 ký tự"
      );
      hasError = true;
    }

    // If has errors, stop here
    if (hasError) {
      return;
    }

    // Disable submit button
    const submitBtn = document.querySelector(".contact-submit-btn");
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = "Đang gửi...";

    // Simulate form submission (replace with actual API call)
    setTimeout(() => {
      // Success
      showToast("Gửi thành công! Chúng tôi sẽ phản hồi sớm nhất.", "success");

      // Reset form
      document.getElementById("contactForm").reset();

      // Re-enable button
      submitBtn.disabled = false;
      submitBtn.textContent = originalText;

      // Log data (in real app, send to server)
      console.log("Contact Form Data:", {
        name: name,
        email: email,
        message: message,
        timestamp: new Date().toISOString(),
      });

      // Optional: Redirect or show success page
      // window.location.href = 'thank-you.html';
    }, 1500);
  });

// Add smooth scroll to top when page loads
window.addEventListener("load", function () {
  window.scrollTo({ top: 0, behavior: "smooth" });
});
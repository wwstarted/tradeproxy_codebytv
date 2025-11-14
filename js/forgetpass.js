// Toggle Mật khẩu mới
document
  .getElementById("toggleNewPassword")
  .addEventListener("click", function () {
    const input = document.getElementById("newPassword");
    const eyePath = this.querySelector(".eye-path");
    const eyeCircle = this.querySelector(".eye-circle");
    const eyeSlash = this.querySelector(".eye-slash");

    if (input.type === "password") {
      input.type = "text";
      eyeSlash.style.display = "block";
      eyePath.style.opacity = "0.3";
      eyeCircle.style.opacity = "0.3";
    } else {
      input.type = "password";
      eyeSlash.style.display = "none";
      eyePath.style.opacity = "1";
      eyeCircle.style.opacity = "1";
    }
  });

// Toggle Xác nhận mật khẩu
document
  .getElementById("toggleConfirmPassword")
  .addEventListener("click", function () {
    const input = document.getElementById("confirmPassword");
    const eyePath = this.querySelector(".eye-path");
    const eyeCircle = this.querySelector(".eye-circle");
    const eyeSlash = this.querySelector(".eye-slash");

    if (input.type === "password") {
      input.type = "text";
      eyeSlash.style.display = "block";
      eyePath.style.opacity = "0.3";
      eyeCircle.style.opacity = "0.3";
    } else {
      input.type = "password";
      eyeSlash.style.display = "none";
      eyePath.style.opacity = "1";
      eyeCircle.style.opacity = "1";
    }
  });

// Gửi OTP (giả lập)
document.getElementById("sendOtpBtn").addEventListener("click", function () {
  const email = document.getElementById("email").value;
  if (!email) {
    alert("Vui lòng nhập email trước!");
    return;
  }
  this.textContent = "Đã gửi!";
  this.disabled = true;
  setTimeout(() => {
    this.textContent = "Gửi lại";
    this.disabled = false;
  }, 3000);
});

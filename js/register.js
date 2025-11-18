// Toggle hiện/ẩn mật khẩu
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');
const eyeSlash1 = togglePassword.querySelector('.eye-slash');

togglePassword.addEventListener('click', function() {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    
    if (type === 'text') {
        eyeSlash1.style.display = 'block';
    } else {
        eyeSlash1.style.display = 'none';
    }
});

// Toggle hiện/ẩn xác nhận mật khẩu
const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
const confirmPasswordInput = document.getElementById('confirmPassword');
const eyeSlash2 = toggleConfirmPassword.querySelector('.eye-slash');

toggleConfirmPassword.addEventListener('click', function() {
    const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    confirmPasswordInput.setAttribute('type', type);
    
    if (type === 'text') {
        eyeSlash2.style.display = 'block';
    } else {
        eyeSlash2.style.display = 'none';
    }
});

// Xử lý form đăng ký
const registerForm = document.getElementById('registerForm');

registerForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const firstName = document.getElementById('firstName').value.trim();
    const lastName = document.getElementById('lastName').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const recaptcha = document.getElementById('recaptcha').checked;
    const terms = document.getElementById('terms').checked;
    
    // Kiểm tra validation
    if (!firstName || !lastName || !email || !password || !confirmPassword) {
        alert('Vui lòng điền đầy đủ thông tin!');
        return;
    }
    
    // Kiểm tra định dạng email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Email không hợp lệ!');
        return;
    }
    
    // Kiểm tra mật khẩu khớp
    if (password !== confirmPassword) {
        alert('Mật khẩu xác nhận không khớp!');
        return;
    }
    
    // Kiểm tra độ dài mật khẩu
    if (password.length < 6) {
        alert('Mật khẩu phải có ít nhất 6 ký tự!');
        return;
    }
    
    // Kiểm tra reCAPTCHA
    if (!recaptcha) {
        alert('Vui lòng xác nhận bạn không phải là người máy!');
        return;
    }
    
    // Kiểm tra điều khoản
    if (!terms) {
        alert('Vui lòng đồng ý với điều khoản dịch vụ!');
        return;
    }
    
    // TODO: Xử lý đăng ký thực tế ở đây
    console.log('Đăng ký với:', { 
        firstName, 
        lastName, 
        email, 
        password 
    });
    alert('Đăng ký thành công! (Demo)');
    
    // Reset form
    registerForm.reset();
});
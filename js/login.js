// Toggle hide/show password
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');
const eyeSlash = document.querySelector('.eye-slash');

togglePassword.addEventListener('click', function() {
    // Đổi type của input
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    
    // hide/show
    if (type === 'text') {
        eyeSlash.style.display = 'block'; 
    } else {
        eyeSlash.style.display = 'none'; 
    }
});

// Xử lý form đăng nhập
const loginForm = document.getElementById('loginForm');

loginForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    
    // Kiểm tra validation
    if (!email || !password) {
        alert('Vui lòng điền đầy đủ thông tin!');
        return;
    }
    
    // Kiểm tra định dạng email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Email không hợp lệ!');
        return;
    }
    
    // Xử lý đăng nhập thực tế ở đây
    console.log('Đăng nhập với:', { email, password });
    alert('Đăng nhập thành công! (Demo)');
    
    // Reset form
    loginForm.reset();
});

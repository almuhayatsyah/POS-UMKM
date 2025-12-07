<!DOCTYPE html>
<html
  lang="id"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Login | Sistem Manajemen</title>

    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    
    <!-- Modern CSS Additions -->
    <style>
      :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --primary-gradient-hover: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        --card-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        --input-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
        --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      }
      
      body {
        font-family: 'Inter', sans-serif;
        background-color: #f8fafc;
        overflow-x: hidden;
      }
      
      .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
      }
      
      .login-card {
        background: white;
        border-radius: 24px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
        width: 100%;
        max-width: 1200px;
        display: flex;
        min-height: 700px;
        transition: var(--transition-smooth);
      }
      
      .login-card:hover {
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.12);
      }
      
      .login-form-section {
        flex: 1;
        padding: 60px 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
        overflow: hidden;
      }
      
      .login-form-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: var(--primary-gradient);
      }
      
      .login-image-section {
        flex: 1.2;
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                    url('{{ asset('assets/img/backgrounds/19.jpg') }}');
        background-size: cover;
        background-position: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 40px;
        color: white;
        position: relative;
      }
      
      .login-image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: var(--primary-gradient);
        opacity: 0.85;
        z-index: 1;
      }
      
      .login-image-content {
        position: relative;
        z-index: 2;
        text-align: center;
        max-width: 500px;
      }
      
      .logo-container {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 30px;
      }
      
      .logo-icon {
        width: 50px;
        height: 50px;
        background: var(--primary-gradient);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        color: white;
        font-size: 24px;
        font-weight: bold;
      }
      
      .logo-text {
        font-size: 22px;
        font-weight: 700;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: -0.5px;
      }
      
      .welcome-title {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 10px;
        color: #1e293b;
      }
      
      .welcome-subtitle {
        color: #64748b;
        margin-bottom: 40px;
        font-size: 16px;
      }
      
      .form-group {
        margin-bottom: 24px;
        position: relative;
      }
      
      .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #475569;
        font-size: 14px;
      }
      
      .input-with-icon {
        position: relative;
      }
      
      .form-control {
        width: 100%;
        padding: 14px 16px;
        padding-left: 45px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 16px;
        transition: var(--transition-smooth);
        background-color: #f8fafc;
      }
      
      .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: var(--input-shadow);
        background-color: white;
      }
      
      .input-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 20px;
        z-index: 2;
      }
      
      .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        font-size: 20px;
        z-index: 2;
      }
      
      .password-toggle:hover {
        color: #667eea;
      }
      
      .remember-forgot {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
      }
      
      .form-check {
        display: flex;
        align-items: center;
      }
      
      .form-check-input {
        width: 18px;
        height: 18px;
        margin-right: 10px;
        accent-color: #667eea;
        cursor: pointer;
      }
      
      .form-check-label {
        color: #475569;
        font-size: 14px;
        cursor: pointer;
      }
      
      .forgot-link {
        color: #667eea;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: var(--transition-smooth);
      }
      
      .forgot-link:hover {
        color: #5a6fd8;
        text-decoration: underline;
      }
      
      .login-btn {
        background: var(--primary-gradient);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 16px;
        font-size: 16px;
        font-weight: 600;
        width: 100%;
        cursor: pointer;
        transition: var(--transition-smooth);
        margin-bottom: 20px;
      }
      
      .login-btn:hover {
        background: var(--primary-gradient-hover);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
      }
      
      .login-btn:active {
        transform: translateY(0);
      }
      
      .divider {
        display: flex;
        align-items: center;
        margin: 30px 0;
        color: #94a3b8;
        font-size: 14px;
      }
      
      .divider::before,
      .divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e2e8f0;
      }
      
      .divider span {
        padding: 0 15px;
      }
      
      .social-login {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 30px;
      }
      
      .social-btn {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border: 2px solid #e2e8f0;
        color: #475569;
        font-size: 22px;
        cursor: pointer;
        transition: var(--transition-smooth);
      }
      
      .social-btn:hover {
        border-color: #667eea;
        color: #667eea;
        transform: translateY(-3px);
      }
      
      .register-link {
        text-align: center;
        color: #64748b;
        font-size: 14px;
      }
      
      .register-link a {
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
      }
      
      .register-link a:hover {
        text-decoration: underline;
      }
      
      .error-message {
        color: #ef4444;
        font-size: 13px;
        margin-top: 5px;
        display: flex;
        align-items: center;
      }
      
      .error-message i {
        margin-right: 5px;
      }
      
      .image-title {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 15px;
        line-height: 1.2;
      }
      
      .image-description {
        font-size: 16px;
        line-height: 1.6;
        opacity: 0.9;
        margin-bottom: 30px;
      }
      
      .feature-list {
        text-align: left;
        margin-top: 30px;
      }
      
      .feature-item {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        font-size: 15px;
      }
      
      .feature-icon {
        width: 24px;
        height: 24px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-size: 14px;
      }
      
      @media (max-width: 992px) {
        .login-card {
          flex-direction: column;
          max-width: 500px;
        }
        
        .login-image-section {
          display: none;
        }
        
        .login-form-section {
          padding: 40px 30px;
        }
      }
      
      @media (max-width: 576px) {
        .login-form-section {
          padding: 30px 20px;
        }
        
        .welcome-title {
          font-size: 24px;
        }
        
        .remember-forgot {
          flex-direction: column;
          align-items: flex-start;
          gap: 15px;
        }
      }
      
      /* Animation for form elements */
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
      
      .form-group, .login-btn, .divider, .social-login {
        animation: fadeInUp 0.5s ease-out forwards;
      }
      
      .form-group:nth-child(1) { animation-delay: 0.1s; }
      .form-group:nth-child(2) { animation-delay: 0.2s; }
      .remember-forgot { animation-delay: 0.3s; }
      .login-btn { animation-delay: 0.4s; }
      .divider { animation-delay: 0.5s; }
      .social-login { animation-delay: 0.6s; }
      .register-link { animation-delay: 0.7s; }
    </style>
  </head>

  <body>
    <div class="login-container">
      <div class="login-card">
        <!-- Left Side: Login Form -->
        <div class="login-form-section">
          <div class="logo-container">
            <div class="logo-icon">
              <i class='bx bx-store'></i>
            </div>
            <div class="logo-text">Sistem Kasir & Stok Barang</div>
          </div>
          
          <h2 class="welcome-title">Selamat Datang! 👋</h2>
          <p class="welcome-subtitle">Silakan masuk ke akun Anda untuk mengelola kasir dan stok barang.</p>

          <form id="formAuthentication" action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="form-group">
              <label for="email" class="form-label">Email</label>
              <div class="input-with-icon">
                <i class='bx bx-envelope input-icon'></i>
                <input
                  type="email"
                  class="form-control"
                  id="email"
                  name="email"
                  placeholder="nama@contoh.com"
                  value="{{ old('email') }}"
                  autofocus
                  required
                />
              </div>
              @error('email') 
                <div class="error-message">
                  <i class='bx bx-error-circle'></i>
                  {{ $message }}
                </div>
              @enderror
            </div>
            
            <div class="form-group">
              <label class="form-label" for="password">Password</label>
              <div class="input-with-icon">
                <i class='bx bx-lock-alt input-icon'></i>
                <input
                  type="password"
                  id="password"
                  class="form-control"
                  name="password"
                  placeholder="Masukkan password Anda"
                  required
                />
                <button type="button" class="password-toggle" id="togglePassword">
                  <i class='bx bx-hide'></i>
                </button>
              </div>
              @error('password') 
                <div class="error-message">
                  <i class='bx bx-error-circle'></i>
                  {{ $message }}
                </div>
              @enderror
            </div>
            
            <div class="remember-forgot">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember-me" name="remember" />
                <label class="form-check-label" for="remember-me"> Ingat Saya </label>
              </div>
              <a href="#" class="forgot-link">Lupa Password?</a>
            </div>
            
            <button class="login-btn" type="submit">Masuk</button>
          </form>
          
          <div class="divider">
            <span>Atau masuk dengan</span>
          </div>
          
          <div class="social-login">
            <button type="button" class="social-btn">
              <i class='bx bxl-google'></i>
            </button>
            <button type="button" class="social-btn">
              <i class='bx bxl-facebook'></i>
            </button>
            <button type="button" class="social-btn">
              <i class='bx bxl-microsoft'></i>
            </button>
          </div>
          
          <div class="register-link">
            Belum punya akun? <a href="#">Daftar disini</a>
          </div>
        </div>

        <!-- Right Side: Image & Features -->
        <div class="login-image-section">
          <div class="login-image-overlay"></div>
          <div class="login-image-content">
            <h2 class="image-title">Sistem Manajemen Kasir & Stok Barang</h2>
            <p class="image-description">
              Kelola transaksi kasir, pantau stok barang, dan optimalkan operasional bisnis Anda dengan sistem terintegrasi kami.
            </p>
            
            <div class="feature-list">
              <div class="feature-item">
                <div class="feature-icon">
                  <i class='bx bx-check'></i>
                </div>
                <span>Transaksi kasir yang cepat dan akurat</span>
              </div>
              <div class="feature-item">
                <div class="feature-icon">
                  <i class='bx bx-check'></i>
                </div>
                <span>Pantau stok barang secara real-time</span>
              </div>
              <div class="feature-item">
                <div class="feature-icon">
                  <i class='bx bx-check'></i>
                </div>
                <span>Laporan keuangan otomatis</span>
              </div>
              <div class="feature-item">
                <div class="feature-icon">
                  <i class='bx bx-check'></i>
                </div>
                <span>Akses dari mana saja, kapan saja</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    
    <!-- Modern Script Additions -->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        
        if (togglePassword) {
          togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle icon
            const icon = this.querySelector('i');
            if (type === 'password') {
              icon.classList.remove('bx-show');
              icon.classList.add('bx-hide');
            } else {
              icon.classList.remove('bx-hide');
              icon.classList.add('bx-show');
            }
          });
        }
        
        // Form validation and interaction
        const form = document.getElementById('formAuthentication');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        
        // Add focus effect
        [emailInput, passwordInput].forEach(input => {
          input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
          });
          
          input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
          });
        });
        
        // Social login buttons interaction
        const socialButtons = document.querySelectorAll('.social-btn');
        socialButtons.forEach(button => {
          button.addEventListener('click', function() {
            // Visual feedback
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
              this.style.transform = '';
            }, 200);
            
            // Here you would normally trigger the actual social login
            // For demo, just show an alert
            const platform = this.querySelector('i').className.includes('google') ? 'Google' : 
                           this.querySelector('i').className.includes('facebook') ? 'Facebook' : 'Microsoft';
            alert(`Login dengan ${platform} akan diarahkan ke halaman otentikasi.`);
          });
        });
        
        // Form submission animation
        form.addEventListener('submit', function(e) {
          const submitBtn = this.querySelector('.login-btn');
          
          // Only animate if form is valid
          if (this.checkValidity()) {
            e.preventDefault(); // Remove this in production
            
            // Visual feedback
            submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Memproses...';
            submitBtn.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
              submitBtn.innerHTML = 'Berhasil!';
              submitBtn.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
              
              // In production, remove this timeout and let the form submit naturally
              setTimeout(() => {
                // This would be the actual form submission
                // form.submit();
                
                // For demo, reset the button
                submitBtn.innerHTML = 'Masuk';
                submitBtn.disabled = false;
                submitBtn.style.background = '';
                alert('Login berhasil! (Demo saja - di production akan redirect)');
              }, 1000);
            }, 1500);
          }
        });
      });
    </script>
  </body>
</html>
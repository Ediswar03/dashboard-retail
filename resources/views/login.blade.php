<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — DataLens Pro</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root {
  --bg: #F8FAFC;
  --s1: #FFFFFF;
  --s2: #F1F5F9;
  --blue: #4F46E5;
  --blue-dim: rgba(79,70,229,.12);
  --teal: #0D9488;
  --purple: #7C3AED;
  --t1: #0F172A;
  --t2: #475569;
  --t3: #94A3B8;
  --border: #E2E8F0;
  --border2: #CBD5E1;
  --mono: 'JetBrains Mono', monospace;
  --sans: 'Inter', sans-serif;
  --transition: .25s cubic-bezier(.4,0,.2,1);
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html, body { 
  height: 100%; 
  background: var(--bg); 
  color: var(--t1); 
  font-family: var(--sans); 
  overflow: hidden; 
  display: flex;
}

/* Split Screen Layout */
.left-pane {
  flex: 1;
  background: linear-gradient(135deg, var(--t1), #1E293B);
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 60px;
  position: relative;
  overflow: hidden;
}
.left-pane::before {
  content: '';
  position: absolute;
  top: -20%; left: -20%; width: 140%; height: 140%;
  background: radial-gradient(circle at 30% 70%, rgba(79,70,229,0.15) 0%, rgba(0,0,0,0) 50%),
              radial-gradient(circle at 70% 30%, rgba(13,148,136,0.15) 0%, rgba(0,0,0,0) 50%);
  z-index: 1;
}
.branding {
  position: relative;
  z-index: 10;
  color: #fff;
  max-width: 480px;
}
.logo-box {
  width: 54px;
  height: 54px;
  border-radius: 12px;
  background: linear-gradient(135deg, var(--blue), var(--teal));
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: var(--mono);
  font-size: 20px;
  font-weight: 700;
  margin-bottom: 30px;
  box-shadow: 0 10px 25px rgba(79,70,229,0.4);
}
.branding h1 {
  font-size: 48px;
  font-weight: 800;
  line-height: 1.1;
  margin-bottom: 20px;
  letter-spacing: -0.02em;
}
.branding p {
  font-size: 16px;
  color: #94A3B8;
  line-height: 1.6;
}

.right-pane {
  width: 480px;
  background: var(--s1);
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 40px 60px;
  box-shadow: -10px 0 30px rgba(0,0,0,0.03);
  z-index: 20;
}
.login-header {
  margin-bottom: 40px;
}
.login-header h2 {
  font-size: 24px;
  font-weight: 700;
  margin-bottom: 8px;
}
.login-header p {
  font-size: 14px;
  color: var(--t2);
}
.form-group {
  margin-bottom: 24px;
}
.form-group label {
  display: block;
  font-size: 12px;
  font-weight: 600;
  color: var(--t2);
  margin-bottom: 8px;
}
.form-control {
  width: 100%;
  background: var(--s1);
  border: 1px solid var(--border2);
  border-radius: 8px;
  padding: 14px 16px;
  font-size: 14px;
  font-family: var(--sans);
  color: var(--t1);
  transition: all var(--transition);
}
.form-control:focus {
  outline: none;
  border-color: var(--blue);
  box-shadow: 0 0 0 4px var(--blue-dim);
}
.form-options {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 30px;
  font-size: 13px;
}
.checkbox {
  display: flex;
  align-items: center;
  gap: 8px;
  color: var(--t2);
  cursor: pointer;
}
.checkbox input {
  accent-color: var(--blue);
  width: 16px; height: 16px;
}
.forgot-link {
  color: var(--blue);
  font-weight: 500;
  text-decoration: none;
}
.forgot-link:hover {
  text-decoration: underline;
}
.btn-login {
  width: 100%;
  background: var(--t1);
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 16px;
  font-size: 14px;
  font-weight: 600;
  font-family: var(--sans);
  cursor: pointer;
  transition: all var(--transition);
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
  text-decoration: none;
}
.btn-login:hover {
  background: var(--blue);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
}
.back-btn {
  position: absolute;
  top: 40px;
  right: 40px;
  font-size: 13px;
  font-weight: 600;
  color: var(--t2);
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 6px;
  transition: color var(--transition);
}
.back-btn:hover {
  color: var(--t1);
}

@media (max-width: 900px) {
  .left-pane { display: none; }
  .right-pane { width: 100%; padding: 40px; }
}
</style>
</head>
<body>

  <div class="left-pane">
    <div class="branding">
      <div class="logo-box">DL</div>
      <h1>Unlock the power of your retail data.</h1>
      <p>DataLens PRO provides deep analytical insights, predictive customer segmentation, and market basket analysis designed for high-volume retail environments.</p>
    </div>
  </div>

  <div class="right-pane">
    <a href="{{ route('welcome') }}" class="back-btn">&larr; Back to Home</a>
    
    <div class="login-header">
      <h2>Welcome back</h2>
      <p>Please enter your credentials to access the platform.</p>
    </div>
    
    <form action="{{ route('login.post') }}" method="POST">
      @csrf
      
      @if ($errors->any())
        <div style="background: rgba(225, 29, 72, 0.1); border: 1px solid #E11D48; color: #E11D48; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; font-size: 13px; font-weight: 500;">
          {{ $errors->first() }}
        </div>
      @endif

      <div class="form-group">
        <label for="email">Work Email</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="analyst@company.com" value="{{ old('email') }}" required>
      </div>
      
      <div class="form-group">
        <label for="password">Password</label>
        <div style="position: relative;">
          <input type="password" id="password" name="password" class="form-control" style="padding-right: 40px;" placeholder="••••••••" required>
          <button type="button" id="togglePassword" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--t3); cursor: pointer; padding: 4px; display: flex;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
      </div>
      
      <div class="form-options">
        <label class="checkbox">
          <input type="checkbox" name="remember"> Remember me for 30 days
        </label>
        <a href="#" class="forgot-link">Forgot password?</a>
      </div>
      
      <button type="submit" class="btn-login">
        Sign In
      </button>
    </form>
    
    <div style="margin-top: 30px; text-align: center; font-size: 12px; color: var(--t3);">
      Don't have an account? <a href="#" style="color: var(--t1); font-weight: 600; text-decoration: none;">Request access</a>
    </div>
  </div>

  <script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function () {
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      
      if (type === 'text') {
        this.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>';
      } else {
        this.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>';
      }
    });
  </script>
</body>
</html>

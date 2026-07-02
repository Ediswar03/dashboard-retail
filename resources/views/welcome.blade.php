<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DataLens Pro — Welcome</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root {
  --bg: #F8FAFC;
  --s1: #FFFFFF;
  --blue: #4F46E5;
  --teal: #0D9488;
  --purple: #7C3AED;
  --t1: #0F172A;
  --t2: #475569;
  --t3: #94A3B8;
  --border: #E2E8F0;
  --mono: 'JetBrains Mono', monospace;
  --sans: 'Inter', sans-serif;
  --r: 8px;
  --r2: 12px;
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
  align-items: center;
  justify-content: center;
}
.bg-glow {
  position: absolute;
  width: 500px;
  height: 500px;
  background: radial-gradient(circle, rgba(79,70,229,0.15) 0%, rgba(13,148,136,0.05) 50%, rgba(248,250,252,0) 70%);
  top: -100px;
  left: -100px;
  border-radius: 50%;
  z-index: 0;
  filter: blur(40px);
  animation: float 10s ease-in-out infinite;
}
.bg-glow-2 {
  position: absolute;
  width: 400px;
  height: 400px;
  background: radial-gradient(circle, rgba(124,58,237,0.15) 0%, rgba(79,70,229,0.05) 50%, rgba(248,250,252,0) 70%);
  bottom: -50px;
  right: -50px;
  border-radius: 50%;
  z-index: 0;
  filter: blur(40px);
  animation: float 12s ease-in-out infinite reverse;
}
@keyframes float {
  0% { transform: translateY(0) translateX(0); }
  50% { transform: translateY(30px) translateX(20px); }
  100% { transform: translateY(0) translateX(0); }
}
.splash-container {
  position: relative;
  z-index: 10;
  text-align: center;
  max-width: 600px;
  padding: 40px;
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.5);
  border-radius: 24px;
  box-shadow: 0 20px 40px -10px rgba(0,0,0,0.05), 0 1px 3px rgba(0,0,0,0.05);
  animation: fadeinUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}
@keyframes fadeinUp {
  0% { opacity: 0; transform: translateY(30px); }
  100% { opacity: 1; transform: translateY(0); }
}
.logo-box {
  width: 64px;
  height: 64px;
  border-radius: 16px;
  background: linear-gradient(135deg, var(--blue), var(--teal));
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: var(--mono);
  font-size: 24px;
  font-weight: 700;
  color: #fff;
  margin: 0 auto 24px;
  box-shadow: 0 10px 25px rgba(79,70,229,0.3);
}
.title {
  font-size: 42px;
  font-weight: 800;
  letter-spacing: -0.03em;
  margin-bottom: 12px;
  background: linear-gradient(135deg, var(--t1), var(--blue));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
.subtitle {
  font-size: 16px;
  color: var(--t2);
  line-height: 1.6;
  margin-bottom: 40px;
  max-width: 400px;
  margin-left: auto;
  margin-right: auto;
}
.badge {
  display: inline-block;
  font-family: var(--mono);
  font-size: 11px;
  background: rgba(79,70,229,0.1);
  color: var(--blue);
  border: 1px solid rgba(79,70,229,0.2);
  padding: 4px 12px;
  border-radius: 20px;
  margin-bottom: 20px;
  font-weight: 600;
  letter-spacing: 0.05em;
  text-transform: uppercase;
}
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 14px 28px;
  border-radius: 30px;
  background: var(--t1);
  color: #fff;
  font-size: 15px;
  font-weight: 600;
  text-decoration: none;
  transition: all var(--transition);
  box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2);
}
.btn:hover {
  background: var(--blue);
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(79, 70, 229, 0.3);
}
.btn span {
  font-family: var(--mono);
}
.footer-text {
  position: absolute;
  bottom: 30px;
  left: 0;
  right: 0;
  text-align: center;
  font-family: var(--mono);
  font-size: 11px;
  color: var(--t3);
  z-index: 10;
}
</style>
</head>
<body>
  <div class="bg-glow"></div>
  <div class="bg-glow-2"></div>

  <div class="splash-container">
    <div class="badge">Enterprise Edition v2.5</div>
    <div class="logo-box">DL</div>
    <h1 class="title">DataLens PRO</h1>
    <p class="subtitle">Advanced Retail Analytics Engine for uncovering hidden insights in your transaction data.</p>
    
    <a href="{{ route('login') }}" class="btn">
      Enter Platform <span>&rarr;</span>
    </a>
  </div>

  <div class="footer-text">
    &copy; 2026 DataLens Analytics. Authorized Personnel Only.
  </div>
</body>
</html>

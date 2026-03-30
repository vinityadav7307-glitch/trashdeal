<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | TrashDeal</title>
  <link rel="stylesheet" href="/assets/site.css?v=20260330c">
</head>
<body data-page="login">
  <header class="site-header">
    <div class="container site-header-inner">
      <a href="/" class="brand">
        <span class="brand-mark">TD</span>
        <span class="brand-text">TrashDeal</span>
      </a>
      <button class="mobile-nav-toggle" type="button" data-mobile-nav-toggle aria-label="Open menu" aria-expanded="false">Menu</button>
      <nav class="top-nav">
        <a href="/">Home</a>
        <a href="/register" class="cta-link">Create Account</a>
      </nav>
    </div>
  </header>

  <main class="auth-wrap">
    <div class="auth-grid">
      <section class="auth-panel glass-card">
        <span class="eyebrow"><span class="dot"></span> Your reward journey starts here</span>
        <h1>Turn your waste into rewards. Start your journey with TrashDeal.</h1>
        <p>Sign in to track your pickups, earn points, and redeem exciting rewards.</p>
        <div class="auth-highlight">
          <div class="item">
            <p class="item-title">Track your impact</p>
            <div class="item-meta">Monitor your points, scheduled pickups, and rewards in real time.</div>
          </div>
          <div class="item">
            <p class="item-title">Unlock extra benefits</p>
            <div class="item-meta">Upgrade anytime to unlock bonus points and priority pickups.</div>
          </div>
          <div class="item">
            <p class="item-title">Fully connected system</p>
            <div class="item-meta">Everything is powered by a real system for a smooth experience.</div>
          </div>
        </div>
      </section>
<section class="auth-panel auth-form-panel">
  <div class="premium-pill">Secure login</div>
  <h2 style="margin:0 0 10px;">Welcome back 👋</h2>
  <p class="item-meta" style="margin:0 0 20px;">Use your phone or email to continue.</p>

  <form id="login-form" class="form-grid">
    <div>
      <label class="field-label" for="login">Phone or email</label>
      <input id="login" name="login" class="input" placeholder="Enter phone or email" required>
    </div>

    <div>
      <label class="field-label" for="password">Password</label>
      <div class="password-field">
        <input id="password" name="password" type="password" class="input" placeholder="Enter password" required>
        <button class="password-toggle" id="toggle-password" type="button">Show</button>
      </div>
    </div>

    <button class="button primary" type="submit">Continue</button>
  </form>

  <div id="auth-status" class="status-box hidden"></div>

  <!-- moved up -->
  <p class="item-meta" style="margin-top:18px;">
    New here? 
    <a href="/register" style="color:var(--brand-dark);font-weight:700;">
      Create an account
    </a>
  </p>
</section>
      
    </div>
  </main>

  <script src="/assets/site.js?v=20260330c"></script>
</body>
</html>

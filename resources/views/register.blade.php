<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register | TrashDeal</title>
  <link rel="stylesheet" href="/assets/site.css?v=20260330c">
</head>
<body data-page="register">
  <header class="site-header">
    <div class="container site-header-inner">
      <a href="/" class="brand">
        <span class="brand-mark">TD</span>
        <span class="brand-text">TrashDeal</span>
      </a>
      <button class="mobile-nav-toggle" type="button" data-mobile-nav-toggle aria-label="Open menu" aria-expanded="false">Menu</button>
      <nav class="top-nav">
        <a href="/">Home</a>
        <a href="/login" class="cta-link">Login</a>
      </nav>
    </div>
  </header>

  <main class="auth-wrap">
    <div class="auth-grid">
      <section class="auth-panel glass-card">
        <span class="eyebrow"><span class="dot"></span> Join the points network</span>
        <h1>Create your account and start earning points.</h1>
        <p>Create your account in seconds and start earning points from your very first pickup.</p>
        <div class="auth-highlight">
          <div class="item">
            <p class="item-title">Points-only reward system</p>
            <div class="item-meta">No rupee conversion. Just clean pickup-to-points-to-rewards flows.</div>
          </div>
          <div class="item">
            <p class="item-title">Multi-page website</p>
            <div class="item-meta">Profile, rewards, pickups, and dashboard each have their own interface.</div>
          </div>
          <div class="item">
            <p class="item-title">Premium when you want it</p>
            <div class="item-meta">Upgrade later with monthly ₹99 or annual ₹799 from inside your account.</div>
          </div>
        </div>
      </section>

      <section class="auth-panel auth-form-panel">
        <div class="premium-pill">New account</div>
        <h2 style="margin:0 0 10px;">Create your TrashDeal account</h2>
        <form id="register-form" class="form-grid">
          <div class="split">
            <div>
              <label class="field-label" for="name">Full name</label>
              <input id="name" name="name" class="input" placeholder="Your full name" required>
            </div>
            <div>
              <label class="field-label" for="phone">Phone</label>
              <input id="phone" name="phone" class="input" placeholder="10-digit phone number" required>
            </div>
          </div>
          <div>
            <label class="field-label" for="email">Email</label>
            <input id="email" name="email" type="email" class="input" placeholder="Optional email address">
          </div>
          <div>
            <label class="field-label" for="register-address">Address</label>
            <input id="register-address" name="address" class="input" placeholder="Your pickup address">
          </div>
          <div class="split">
            <div>
              <label class="field-label" for="register-latitude">Latitude</label>
              <input id="register-latitude" name="latitude" class="input" placeholder="Optional latitude">
            </div>
            <div>
              <label class="field-label" for="register-longitude">Longitude</label>
              <input id="register-longitude" name="longitude" class="input" placeholder="Optional longitude">
            </div>
          </div>
          <div>
            <label class="field-label" for="register-password">Password</label>
            <input id="register-password" name="password" type="password" class="input" placeholder="Create password" required>
          </div>
          <button class="button primary" type="submit">Create account</button>
        </form>
        <div id="auth-status" class="status-box hidden"></div>
        <p class="item-meta" style="margin-top:18px;">Already registered? <a href="/login" style="color:var(--brand-dark);font-weight:700;">Login here</a></p>
      </section>
    </div>
  </main>

  <script src="/assets/site.js?v=20260330c"></script>
</body>
</html>

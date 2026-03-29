<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile | TrashDeal</title>
  <link rel="stylesheet" href="/assets/site.css?v=20260330c">
</head>
<body data-page="profile">
  <header class="site-header">
    <div class="container site-header-inner">
      <a href="/" class="brand">
        <span class="brand-mark">TD</span>
        <span class="brand-text">TrashDeal</span>
      </a>
      <button class="mobile-nav-toggle" type="button" data-mobile-nav-toggle aria-label="Open menu" aria-expanded="false">Menu</button>
      <nav class="top-nav">
        <a href="/dashboard">Dashboard</a>
        <a href="/pickups">Pickups</a>
        <a href="/rewards">Rewards</a>
        <a href="/profile" class="active">Profile</a>
        <button type="button" data-logout>Logout</button>
      </nav>
    </div>
  </header>

  <main class="page-shell">
    <div class="container">
      <section class="page-hero">
        <span class="eyebrow"><span class="dot"></span> Your profile and progress</span>
        <h1>Your account details and point history.</h1>
        <p>Check your account details, follow your progress, and keep an eye on every point you earn.</p>
      </section>

      <div class="grid-two">
        <section class="app-card">
          <h3 id="profile-name">User</h3>
          <div class="item-meta">Role: <span id="profile-role">--</span></div>
          <div class="item-meta">Phone: <span id="profile-phone">--</span></div>
          <div class="item-meta">Email: <span id="profile-email">--</span></div>
          <div class="item-meta">Address: <span id="profile-address">--</span></div>
          <div class="item-meta">Membership: <span id="profile-premium">--</span></div>
          <div class="item-meta">Premium until: <span id="profile-expiry">--</span></div>
          <div class="metric" style="margin-top:18px;">
            <span>Total points</span>
            <strong id="profile-points">--</strong>
          </div>
        </section>

        <section class="list-card">
          <h3>Premium plans</h3>
          <p>Upgrade or extend your current plan right from your profile.</p>
          <div id="profile-status" class="status-box hidden"></div>
          <div id="profile-plan-grid" class="premium-grid" style="margin-top:18px;"></div>
        </section>
      </div>

      <div class="grid-two" style="margin-top:18px;">
        <section class="list-card">
          <h3>Point activity</h3>
          <p>Your recent point transactions.</p>
          <div id="history-list" class="list" style="margin-top:18px;"></div>
        </section>
        <section class="list-card">
          <h3>Account summary</h3>
          <p>Everything you need to stay on top of your rewards journey is right here.</p>
          <ul class="feature-list">
            <li>Keep your account details up to date</li>
            <li>See your membership status instantly</li>
            <li>Track every reward redemption and points update</li>
          </ul>
        </section>
      </div>
    </div>
  </main>

  <script src="/assets/site.js?v=20260330c"></script>
</body>
</html>

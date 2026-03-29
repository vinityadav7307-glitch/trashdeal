<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rewards | TrashDeal</title>
  <link rel="stylesheet" href="/assets/site.css?v=20260330c">
</head>
<body data-page="rewards">
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
        <a href="/rewards" class="active">Rewards</a>
        <a href="/profile">Profile</a>
        <button type="button" data-logout>Logout</button>
      </nav>
    </div>
  </header>

  <main class="page-shell">
    <div class="container">
      <section class="page-hero">
        <span class="eyebrow"><span class="dot"></span> Rewards made simple</span>
        <h1>Spend your points on rewards.</h1>
        <p>Choose from everyday essentials, eco picks, and vouchers as your points grow.</p>
      </section>

      <div class="metric-grid">
        <div class="metric">
          <span>Your points balance</span>
          <strong id="reward-points">--</strong>
        </div>
      </div>

      <section class="list-card">
        <h3>Rewards marketplace</h3>
        <p>Earn more points to unlock rewards.</p>
        <div class="filter-row" id="reward-filters" style="margin-top:18px;">
          <button class="button secondary is-active" type="button" data-filter-category="all">All</button>
          <button class="button secondary" type="button" data-filter-category="grocery">Grocery</button>
          <button class="button secondary" type="button" data-filter-category="eco">Eco</button>
          <button class="button secondary" type="button" data-filter-category="voucher">Voucher</button>
        </div>
        <div id="reward-status" class="status-box hidden"></div>
        <div id="reward-list" class="list" style="margin-top:18px;"></div>
      </section>
    </div>
  </main>

  <script src="/assets/site.js?v=20260330c"></script>
</body>
</html>

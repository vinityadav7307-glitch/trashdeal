<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Collector Dashboard | TrashDeal</title>
  <link rel="stylesheet" href="/assets/site.css?v=20260330c">
</head>
<body data-page="collector-dashboard">
  <header class="site-header">
    <div class="container site-header-inner">
      <a href="/" class="brand">
        <span class="brand-mark">TD</span>
        <span class="brand-text">TrashDeal</span>
      </a>
      <button class="mobile-nav-toggle" type="button" data-mobile-nav-toggle aria-label="Open menu" aria-expanded="false">Menu</button>
      <nav class="top-nav">
        <a href="/collector/dashboard" class="active">Collector</a>
        <a href="/profile">Profile</a>
        <button type="button" data-logout>Logout</button>
      </nav>
    </div>
  </header>

  <main class="page-shell">
    <div class="container">
      <section class="page-hero">
        <span class="eyebrow"><span class="dot"></span> Collector workflow</span>
        <h1>Assigned pickups for <span id="collector-name">Collector</span></h1>
        <p>Track pickup status in real time, verify the pickup token on site, and complete collections after the scheduled time.</p>
      </section>

      <div class="metric-grid collector-metrics">
        <div class="metric">
          <span>Assigned pickups</span>
          <strong id="collector-total">--</strong>
        </div>
        <div class="metric">
          <span>In progress</span>
          <strong id="collector-progress">--</strong>
        </div>
        <div class="metric">
          <span>Completed today</span>
          <strong id="collector-completed">--</strong>
        </div>
      </div>

      <section class="list-card">
        <h3>Assigned pickups</h3>
        <p>Start the pickup when it is due, verify the pickup token on site, and complete it to add points automatically.</p>
        <div id="collector-status" class="status-box hidden"></div>
        <div id="collector-pickups" class="collector-grid" style="margin-top:18px;"></div>
      </section>
    </div>
  </main>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <script src="/assets/site.js?v=20260330c"></script>
</body>
</html>

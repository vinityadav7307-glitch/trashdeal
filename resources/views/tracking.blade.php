<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Live Tracking | TrashDeal</title>
  <link rel="stylesheet" href="/assets/site.css?v=20260330c">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
</head>
<body data-page="tracking">
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
        <a href="/tracking" class="active">Live Tracking</a>
        <a href="/profile">Profile</a>
        <button type="button" data-logout>Logout</button>
      </nav>
    </div>
  </header>

  <main class="page-shell">
    <div class="container">
      <section class="page-hero">
        <span class="eyebrow"><span class="dot"></span> Live tracking</span>
        <h1>Follow your collector in real time.</h1>
        <p>See pickup status changes, collector movement, and the route between your address and the collector.</p>
      </section>

      <div class="grid-two tracking-layout">
        <section class="list-card">
          <h3>Track a pickup</h3>
          <p>Choose any assigned or in-progress pickup to see live updates.</p>
          <div>
            <label class="field-label" for="tracking-pickup-select">Pickup</label>
            <select id="tracking-pickup-select" class="select"></select>
          </div>
          <div id="tracking-status" class="status-box hidden"></div>
          <div class="tracking-status-grid">
            <div class="metric">
              <span>Status</span>
              <strong id="tracking-current-status">--</strong>
            </div>
            <div class="metric">
              <span>Collector</span>
              <strong id="tracking-collector-name">--</strong>
            </div>
          </div>
        </section>

        <section class="app-card">
          <h3>Map view</h3>
          <p class="item-meta">The map refreshes every 5 seconds while your pickup is active.</p>
          <div id="tracking-map" class="tracking-map"></div>
        </section>
      </div>
    </div>
  </main>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="/assets/site.js?v=20260330c"></script>
</body>
</html>

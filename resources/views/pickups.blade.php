<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pickups | TrashDeal</title>
  <link rel="stylesheet" href="/assets/site.css?v=20260330c">
</head>
<body data-page="pickups">
  <header class="site-header">
    <div class="container site-header-inner">
      <a href="/" class="brand">
        <span class="brand-mark">TD</span>
        <span class="brand-text">TrashDeal</span>
      </a>
      <button class="mobile-nav-toggle" type="button" data-mobile-nav-toggle aria-label="Open menu" aria-expanded="false">Menu</button>
      <nav class="top-nav">
        <a href="/dashboard">Dashboard</a>
        <a href="/scan-waste">Scan Waste</a>
        <a href="/pickups" class="active">Pickups</a>
        <a href="/tracking">Tracking</a>
        <a href="/rewards">Rewards</a>
        <a href="/profile">Profile</a>
        <button type="button" data-logout>Logout</button>
      </nav>
    </div>
  </header>

  <main class="page-shell">
    <div class="container">
      <section class="page-hero">
        <span class="eyebrow"><span class="dot"></span> Plan your next pickup</span>
        <h1>Book your next pickup.</h1>
        <p>Pick a time that works for you and we will keep the rest simple.</p>
      </section>

      <div class="grid-two">
        <section class="app-card">
          <h3>Schedule pickup</h3>
          <p class="item-meta">Choose a pickup time, add your location, and your nearest collector will be assigned automatically.</p>
          <form id="pickup-form" class="form-grid" style="margin-top:18px;">
            <div>
              <label class="field-label" for="pickup-address">Pickup address</label>
              <input id="pickup-address" name="address" class="input" required>
            </div>
            <div class="split">
              <div>
                <label class="field-label" for="waste-type">Waste type</label>
                <select id="waste-type" name="waste_type" class="select" required>
                  <option value="organic">Organic</option>
                  <option value="recyclable">Recyclable</option>
                  <option value="e-waste">E-waste</option>
                  <option value="inert">Inert</option>
                  <option value="mixed">Mixed</option>
                </select>
              </div>
              <div>
                <label class="field-label" for="pickup-weight">Estimated weight (kg)</label>
                <input id="pickup-weight" name="estimated_weight_kg" type="number" min="0.1" step="0.1" class="input">
              </div>
            </div>
            <div class="item" id="scan-fill-card">
              <p class="item-title">AI waste suggestion</p>
              <div class="item-meta" id="scan-fill-text">No scan linked yet. Use the scan screen to auto-fill your waste type.</div>
              <div class="inline-actions" style="margin-top:14px;">
                <a href="/scan-waste" class="button secondary">Open scan screen</a>
              </div>
            </div>
            <div>
              <label class="field-label" for="pickup-date">Pickup date and time</label>
              <input id="pickup-date" name="scheduled_at" type="datetime-local" class="input" required>
            </div>
            <div>
              <label class="field-label">Pickup location</label>
              <div class="inline-actions" style="margin-top:0;">
                <button class="button secondary" id="use-location" type="button">Use my location</button>
              </div>
              <input id="pickup-lat" name="pickup_lat" type="hidden">
              <input id="pickup-lng" name="pickup_lng" type="hidden">
              <div id="pickup-location-status" class="status-box hidden"></div>
            </div>
            <div>
              <label class="field-label" for="pickup-notes">Notes</label>
              <textarea id="pickup-notes" name="notes" class="textarea"></textarea>
            </div>
            <button class="button primary" type="submit">Schedule pickup</button>
          </form>
          <div id="pickup-status" class="status-box hidden"></div>
        </section>

        <section class="list-card">
          <h3>Your pickup history</h3>
          <p>See your upcoming pickups and completed collections in one place.</p>
          <div id="pickup-list" class="list pickup-history-list" style="margin-top:18px;"></div>
        </section>
      </div>
    </div>
  </main>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <script src="/assets/site.js?v=20260330c"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Scan Waste | TrashDeal</title>
  <link rel="stylesheet" href="/assets/site.css?v=20260330c">
</head>
<body data-page="scan-waste">
  <header class="site-header">
    <div class="container site-header-inner">
      <a href="/" class="brand">
        <span class="brand-mark">TD</span>
        <span class="brand-text">TrashDeal</span>
      </a>
      <button class="mobile-nav-toggle" type="button" data-mobile-nav-toggle aria-label="Open menu" aria-expanded="false">Menu</button>
      <nav class="top-nav">
        <a href="/dashboard">Dashboard</a>
        <a href="/scan-waste" class="active">Scan Waste</a>
        <a href="/pickups">Pickups</a>
        <a href="/rewards">Rewards</a>
        <button type="button" data-logout>Logout</button>
      </nav>
    </div>
  </header>

  <main class="page-shell">
    <div class="container">
      <section class="page-hero">
        <span class="eyebrow"><span class="dot"></span> AI waste camera</span>
        <h1>Scan your waste before scheduling a pickup.</h1>
        <p>Use your camera to detect the waste type, review the confidence score, and send the result straight into your pickup form.</p>
      </section>

      <div class="grid-two">
        <section class="app-card">
          <h3>Live camera</h3>
          <p class="item-meta">This screen tries a lightweight browser model first, then confirms the scan with the backend.</p>
          <div class="scan-stage">
            <video id="scan-video" class="scan-preview" autoplay playsinline muted></video>
            <canvas id="scan-canvas" class="hidden"></canvas>
          </div>
          <div class="inline-actions">
            <button class="button secondary" id="scan-start-camera" type="button">Start camera</button>
            <button class="button secondary" id="scan-stop-camera" type="button">Stop camera</button>
            <button class="button primary" id="scan-capture" type="button">Capture and analyze</button>
          </div>
          <div id="scan-status" class="status-box hidden"></div>
        </section>

        <section class="list-card">
          <h3>Detection result</h3>
          <p>Once detected, your pickup form can use the suggested waste type automatically.</p>
          <div class="scan-result-card">
            <div class="metric-grid" style="margin-bottom:0;">
              <div class="metric">
                <span>Detected type</span>
                <strong id="scan-detected-type">--</strong>
              </div>
              <div class="metric">
                <span>Confidence</span>
                <strong id="scan-confidence">--</strong>
              </div>
            </div>
            <div class="item" style="margin-top:18px;">
              <p class="item-title">Pickup form suggestion</p>
              <div class="item-meta" id="scan-pickup-type">No suggestion yet</div>
            </div>
            <div class="inline-actions">
              <a href="/pickups" class="button primary">Use this in pickup form</a>
            </div>
          </div>
        </section>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@4.22.0/dist/tf.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/mobilenet@2.1.1/dist/mobilenet.min.js"></script>
  <script src="/assets/site.js?v=20260330c"></script>
</body>
</html>

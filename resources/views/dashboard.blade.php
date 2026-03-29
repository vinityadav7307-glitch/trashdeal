<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | TrashDeal</title>
  <link rel="stylesheet" href="/assets/site.css?v=20260330c">
</head>
<body data-page="dashboard">
  <header class="site-header">
    <div class="container site-header-inner">
      <a href="/" class="brand">
        <span class="brand-mark">TD</span>
        <span class="brand-text">TrashDeal</span>
      </a>
      <button class="mobile-nav-toggle" type="button" data-mobile-nav-toggle aria-label="Open menu" aria-expanded="false">Menu</button>
      <nav class="top-nav">
        <a href="/dashboard" class="active">Dashboard</a>
        <a href="/scan-waste">Scan Waste</a>
        <a href="/pickups">Pickups</a>
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
        <span class="eyebrow"><span class="dot"></span> Your activity at a glance</span>
        <h1>Welcome back, <span id="welcome-name">User</span> 👋</h1>
        <p id="profile-snippet">You're doing great! Keep earning.</p>
      </section>

      <div class="dashboard-layout">
        <aside class="sidebar panel">
          <div class="user-badge">
            <strong style="font-size:1.3rem;" data-user-name>User</strong>
            <div style="margin-top:6px;color:rgba(255,255,255,.68);">Your account is active</div>
          </div>
          <nav>
            <a href="/dashboard" class="active">Overview</a>
            <a href="/pickups">Schedule Pickups</a>
            <a href="/rewards">Redeem Rewards</a>
            <a href="/profile">Profile & History</a>
          </nav>
        </aside>

        <section>
          <div class="metric-grid">
            <div class="metric">
              <span>Points earned</span>
              <strong id="metric-points">--</strong>
            </div>
            <div class="metric">
              <span>Rewards unlocked</span>
              <strong id="metric-rewards">--</strong>
            </div>
            <div class="metric">
              <span>Your activity</span>
              <strong id="metric-pickups">--</strong>
            </div>
            <div class="metric">
              <span>Account type</span>
              <strong id="metric-role">--</strong>
            </div>
          </div>

          <div class="grid-two">
            <div class="list-card">
              <h3>Available rewards</h3>
              <p>Redeem your points for useful and eco-friendly rewards.</p>
              <div id="dashboard-rewards" class="list" style="margin-top:16px;"></div>
            </div>
            <div class="list-card">
              <h3>Recent pickups</h3>
              <p>Track the latest pickups and see what is coming up next.</p>
              <div id="dashboard-pickups" class="list" style="margin-top:16px;"></div>
            </div>
          </div>

          <div class="grid-two" style="margin-top:18px;">
            <div class="list-card">
              <h3>Smart analytics</h3>
              <p>Watch your pickup trends, waste mix, and points progress over time.</p>
              <div class="analytics-hero-grid">
                <div class="metric">
                  <span>Total pickups</span>
                  <strong id="analytics-total-pickups">--</strong>
                </div>
                <div class="metric">
                  <span>Total waste collected</span>
                  <strong id="analytics-total-weight">--</strong>
                </div>
                <div class="metric">
                  <span>Rewards redeemed</span>
                  <strong id="analytics-rewards-redeemed">--</strong>
                </div>
              </div>
            </div>
            <div class="list-card">
              <h3>Pickup trends</h3>
              <p>Monthly pickup activity from your account.</p>
              <canvas id="pickup-trends-chart" height="220"></canvas>
            </div>
          </div>

          <div class="grid-two" style="margin-top:18px;">
            <div class="list-card">
              <h3>Waste type distribution</h3>
              <p>See which waste categories you hand over most often.</p>
              <canvas id="waste-distribution-chart" height="240"></canvas>
            </div>
            <div class="list-card">
              <h3>Points earned over time</h3>
              <p>Your pickup rewards trend over the last 30 days.</p>
              <canvas id="points-over-time-chart" height="240"></canvas>
            </div>
          </div>
        </section>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
  <script src="/assets/site.js?v=20260330c"></script>
</body>
</html>

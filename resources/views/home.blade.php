<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TrashDeal | Waste Into Points</title>
  <link rel="stylesheet" href="/assets/site.css?v=20260330c">
</head>
<body data-page="landing">
  <header class="site-header">
    <div class="container site-header-inner">
      <a href="/" class="brand">
        <span class="brand-mark">TD</span>
        <span class="brand-text">TrashDeal</span>
      </a>
      <button class="mobile-nav-toggle" type="button" data-mobile-nav-toggle aria-label="Open menu" aria-expanded="false">Menu</button>
      <nav class="top-nav">
        <a href="#features">Features</a>
        <a href="#flow">How It Works</a>
        <a href="#impact">Impact</a>
        <a href="/dashboard" data-auth-only class="hidden">Dashboard</a>
        <a href="/login" data-guest-only>Login</a>
        <a href="/register" data-guest-only class="cta-link">Create Account</a>
        <button type="button" data-logout data-auth-only class="hidden">Logout</button>
      </nav>
    </div>
  </header>

  <main>
    <section class="hero">
      <div class="container hero-grid">
        <div class="hero-copy">
          <span class="eyebrow"><span class="dot"></span> Cleaner waste flow, better rewards</span>
          <h1>Turn your daily waste into real rewards.</h1>
          <p>Schedule pickups, earn points instantly, and redeem useful rewards — all in one place.</p>
          <div class="hero-actions">
            <a href="/register" class="button primary" data-guest-only>Start Earning Points</a>
            <a href="/dashboard" class="button primary hidden" data-auth-only>Open Dashboard</a>
            <a href="/login" class="button secondary" data-guest-only>Continue</a>
            <a href="#features" class="button secondary">See How It Works</a>
          </div>
          <div class="hero-stats">
            <div class="stat-chip">
              <strong>1200+</strong>
              <span>Earn up to 1200+ points every month</span>
            </div>
            <div class="stat-chip">
              <strong>60 pts/kg</strong>
              <span>Earn more for recyclable waste (up to 60 pts/kg)</span>
            </div>
            <div class="stat-chip">
              <strong>Real-time updates</strong>
              <span>Your activity updates instantly in real-time</span>
            </div>
          </div>
        </div>

        <div class="hero-card">
          <div class="phone-shell">
            <div class="phone-screen">
              <div class="eyebrow" style="background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.12); color: #d6f5dc;">Live user journey</div>
              <p style="margin:16px 0 8px;color:rgba(255,255,255,.72);">Your progress this week</p>
              <div class="phone-balance">1,280 pts</div>
              <p style="margin:10px 0 0;color:rgba(255,255,255,.62);">Stay consistent and unlock more rewards.</p>
              <div class="mini-grid">
                <div class="mini-card">
                  <strong>2 pickups</strong>
                  <span>Scheduled from dashboard</span>
                </div>
                <div class="mini-card">
                  <strong>4 rewards</strong>
                  <span>Ready to redeem</span>
                </div>
                <div class="mini-card">
                  <strong>Profile sync</strong>
                  <span>Your account stays in sync</span>
                </div>
                <div class="mini-card">
                  <strong>Quick sign in</strong>
                  <span>Jump back into your rewards anytime</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="section" id="features">
      <div class="container">
        <div class="section-head">
          <span class="eyebrow">Platform Highlights</span>
          <h2>Turn your daily waste into rewards — all in one place.</h2>
          <p>Everything you need to manage waste, track points, and redeem rewards — in one simple dashboard.</p>
        </div>
        <div class="feature-grid">
          <div class="feature-card">
            <h3>Easy sign in</h3>
            <p>Get started in seconds and pick up where you left off anytime.</p>
          </div>
          <div class="feature-card">
            <h3>Points-only rewards</h3>
            <p>Earn points for every action and redeem them for real benefits.</p>
          </div>
          <div class="feature-card">
            <h3>Premium membership</h3>
            <p>Unlock faster rewards, bonus points, and exclusive perks.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <div class="premium-hero">
          <div class="premium-hero-grid">
            <div>
              <span class="eyebrow" style="background:rgba(255,255,255,.08);border-color:rgba(255,255,255,.14);color:#fff;">Premium Membership</span>
              <h2>Boost your rewards with Premium </h2>
              <p>Earn faster, unlock exclusive rewards, and maximize your points.</p>
              <ul class="feature-list">
                <li>Monthly plan: ₹99</li>
                <li>Annual plan: ₹799</li>
                <li>Premium-only rewards unlock instantly after activation</li>
              </ul>
            </div>
            <div class="section-stack">
              <div class="premium-option featured">
                <div class="premium-option-top">
                  <div>
                    <span class="eyebrow subtle">Best value</span>
                    <h3>Annual Premium</h3>
                  </div>
                  <span class="premium-price">₹799</span>
                </div>
                <p>Designed for regular households that want the full TrashDeal experience for the whole year.</p>
              </div>
              <div class="premium-option">
                <div class="premium-option-top">
                  <div>
                    <span class="eyebrow subtle">Flexible</span>
                    <h3>Monthly Premium</h3>
                  </div>
                  <span class="premium-price">₹99</span>
                </div>
                <p>Start small, test the premium flow, and extend anytime directly from your dashboard or profile.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="section" id="flow">
      <div class="container">
        <div class="section-head">
          <span class="eyebrow">How It Works</span>
          <h2>Start with login, then move through the full product flow.</h2>
        </div>
        <div class="summary-grid">
          <div class="summary-card">
            <h3>1. Create your account</h3>
            <p>Create your account and start your waste-to-reward journey.</p>
          </div>
          <div class="summary-card">
            <h3>2. Open your dashboard</h3>
            <p>Track pickups, points, and rewards from your dashboard.</p>
          </div>
          <div class="summary-card">
            <h3>3. Schedule and redeem</h3>
            <p>Redeem rewards instantly when you have enough points.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="section" id="impact">
      <div class="container">
        <div class="section-head">
          <span class="eyebrow">Why This Feels Better</span>
          <h2>Cleaner UX for a real multi-page product.</h2>
        </div>
        <div class="card-grid">
          <div class="list-card">
            <h3>Sharper hierarchy</h3>
            <p>Marketing, auth, dashboard work, and profile management each live in the right place now.</p>
          </div>
          <div class="list-card">
            <h3>Safer testing flow</h3>
            <p>Everything feels clear, simple, and ready to use from the moment you sign in.</p>
          </div>
          <div class="list-card">
            <h3>Ready for next steps</h3>
            <p>This structure is much easier to grow into a full production website with images, partials, and custom interactions later.</p>
          </div>
        </div>
        <div class="hero-actions" style="margin-top:30px;">
          <a href="/register" class="button primary" data-guest-only>Start earning today</a>
          <a href="/dashboard" class="button primary hidden" data-auth-only>Continue to Dashboard</a>
          <a href="/login" class="button secondary" data-guest-only>Login to continue</a>
        </div>
      </div>
    </section>
  </main>

  <footer class="footer">
    <div class="container">
      TrashDeal helps you turn everyday waste into points, progress, and useful rewards.
    </div>
  </footer>

  <script src="/assets/site.js?v=20260330c"></script>
</body>
</html>

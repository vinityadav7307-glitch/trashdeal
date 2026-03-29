<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TrashDeal - Turn Your Waste Into Points</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800;900&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
<style>
/* ══════════════════ TOKENS ══════════════════ */
:root {
  --g: #2ECC40;
  --gd: #1a9929;
  --gdk: #0d2410;
  --gm: #52d962;
  --gl: #e8f5ea;
  --glm: #c5ead0;
  --y: #E8C900;
  --yd: #b89b00;
  --yl: #fffad6;
  --text: #0f1f11;
  --muted: #6b7f6c;
  --border: #dde8de;
  --bg: #f6f8f6;
  --white: #ffffff;
}

/* ══════════════════ RESET ══════════════════ */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body {
  font-family: 'DM Sans', sans-serif;
  background: var(--white);
  color: var(--text);
  overflow-x: hidden;
}

/* ══════════════════ TYPOGRAPHY ══════════════════ */
.display { font-family: 'Sora', sans-serif; font-weight: 900; letter-spacing: -1.5px; line-height: 1.05; }
.heading { font-family: 'Sora', sans-serif; font-weight: 800; letter-spacing: -0.5px; line-height: 1.2; }
.subhead { font-family: 'Sora', sans-serif; font-weight: 700; }

/* ══════════════════ NAV ══════════════════ */
nav {
  position: fixed; top: 0; left: 0; right: 0; z-index: 100;
  background: rgba(255,255,255,0.92);
  backdrop-filter: blur(16px);
  border-bottom: 1px solid var(--border);
  padding: 0 5%;
  display: flex; align-items: center; justify-content: space-between;
  height: 68px;
}
.nav-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
.nav-logo-icon {
  width: 36px; height: 36px; background: var(--gdk); border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
}
.nav-logo-icon svg { width: 20px; height: 20px; color: white; }
.nav-logo-name { font-family: 'Sora', sans-serif; font-size: 20px; font-weight: 900; color: var(--text); letter-spacing: -0.5px; }
.nav-links { display: flex; align-items: center; gap: 32px; }
.nav-links a { font-size: 14px; font-weight: 600; color: var(--muted); text-decoration: none; transition: color .15s; }
.nav-links a:hover { color: var(--text); }
.nav-cta {
  background: var(--g); color: white; padding: 10px 22px; border-radius: 50px;
  font-size: 14px; font-weight: 700; text-decoration: none; transition: all .15s;
  box-shadow: 0 4px 14px rgba(46,204,64,.3);
}
.nav-cta:hover { background: var(--gd); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(46,204,64,.4); }
.nav-ham { display: none; cursor: pointer; flex-direction: column; gap: 5px; }
.nav-ham span { display: block; width: 22px; height: 2px; background: var(--text); border-radius: 2px; }
.mob-menu { display: none; }

/* ══════════════════ HERO ══════════════════ */
.hero {
  min-height: 100vh;
  background: linear-gradient(160deg, #071a09 0%, #0d3312 40%, #1a6620 70%, #2ECC40 100%);
  display: flex; align-items: center;
  padding: 100px 5% 80px;
  position: relative; overflow: hidden;
}
.hero::before {
  content: '';
  position: absolute; inset: 0;
  background:
    radial-gradient(circle at 20% 50%, rgba(46,204,64,.12) 0%, transparent 60%),
    radial-gradient(circle at 80% 20%, rgba(255,255,255,.04) 0%, transparent 50%);
}
.hero-inner {
  max-width: 1200px; margin: 0 auto; width: 100%;
  display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center;
  position: relative; z-index: 1;
}
.hero-kicker {
  display: inline-flex; align-items: center; gap: 8px;
  background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
  border-radius: 50px; padding: 6px 16px; margin-bottom: 24px;
}
.hero-kicker-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--g); animation: pulse 2s infinite; }
@keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.6;transform:scale(.85)} }
.hero-kicker span { font-size: 13px; font-weight: 600; color: rgba(255,255,255,.85); }
.hero-title { font-size: clamp(42px, 5vw, 68px); color: white; margin-bottom: 22px; }
.hero-title span { color: var(--y); }
.hero-desc { font-size: 18px; color: rgba(255,255,255,.7); line-height: 1.7; margin-bottom: 36px; max-width: 500px; }
.hero-btns { display: flex; gap: 14px; flex-wrap: wrap; }
.btn-hero-primary {
  background: var(--g); color: white; padding: 16px 32px; border-radius: 14px;
  font-family: 'Sora', sans-serif; font-size: 15px; font-weight: 700;
  text-decoration: none; display: inline-flex; align-items: center; gap: 10px;
  box-shadow: 0 8px 28px rgba(46,204,64,.4); transition: all .2s;
}
.btn-hero-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 36px rgba(46,204,64,.5); }
.btn-hero-secondary {
  background: rgba(255,255,255,.12); color: white; padding: 16px 32px; border-radius: 14px;
  font-family: 'Sora', sans-serif; font-size: 15px; font-weight: 700;
  text-decoration: none; border: 1px solid rgba(255,255,255,.25); transition: all .2s;
}
.btn-hero-secondary:hover { background: rgba(255,255,255,.2); }
.hero-stats { display: flex; gap: 32px; margin-top: 48px; }
.hero-stat-val { font-family: 'Sora', sans-serif; font-size: 28px; font-weight: 900; color: white; }
.hero-stat-lbl { font-size: 13px; color: rgba(255,255,255,.55); font-weight: 500; margin-top: 3px; }

/* Phone mockup */
.hero-phone-wrap { display: flex; justify-content: center; align-items: center; position: relative; }
.hero-phone-wrap::before {
  content: '';
  position: absolute; width: 340px; height: 340px; border-radius: 50%;
  background: rgba(46,204,64,.15); filter: blur(60px);
}
.phone-mockup {
  width: 280px; background: #0d1a10; border-radius: 42px;
  padding: 14px; box-shadow: 0 40px 100px rgba(0,0,0,.5), 0 0 0 1px rgba(255,255,255,.08);
  position: relative; z-index: 1;
}
.phone-screen {
  background: #f4f6f4; border-radius: 32px; overflow: hidden;
}
.phone-sb {
  background: white; padding: 10px 16px 8px;
  display: flex; justify-content: space-between; align-items: center;
  font-size: 10px; font-weight: 700; font-family: 'Sora', sans-serif;
  border-bottom: 1px solid #e8ece8;
}
.phone-sb-brand { font-size: 13px; font-weight: 900; }
.phone-hero-card {
  background: linear-gradient(150deg, #0d2410 0%, #1a5c20 50%, #228b2c 100%);
  margin: 10px; border-radius: 16px; padding: 16px 16px 0; position: relative; overflow: hidden;
}
.phone-hero-card::after {
  content: ''; position: absolute; bottom: -12px; left: 0; right: 0;
  height: 24px; background: #f4f6f4; border-radius: 50% 50% 0 0 / 100% 100% 0 0;
}
.phc-lbl { color: rgba(255,255,255,.6); font-size: 9px; font-weight: 600; }
.phc-val { font-family: 'Sora', sans-serif; font-size: 36px; font-weight: 900; color: #E8C900; letter-spacing: -2px; }
.phc-sub { color: rgba(255,255,255,.5); font-size: 9px; margin-top: 2px; margin-bottom: 12px; }
.phc-actions { display: flex; border-top: 1px solid rgba(255,255,255,.1); }
.phca { flex: 1; text-align: center; padding: 9px 4px; border-right: 1px solid rgba(255,255,255,.1); }
.phca:last-child { border-right: none; }
.phca svg { width: 14px; height: 14px; color: rgba(255,255,255,.7); display: block; margin: 0 auto 2px; }
.phca span { font-size: 8px; color: rgba(255,255,255,.75); font-weight: 600; }
.phone-tracker {
  background: var(--g); margin: 18px 10px 10px; border-radius: 14px;
  padding: 12px; display: flex; align-items: center; gap: 11px;
}
.pt-ring { width: 52px; height: 52px; flex-shrink: 0; position: relative; }
.pt-ring svg { width: 52px; height: 52px; transform: rotate(-90deg); }
.pt-ring-inner { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; }
.pt-ring-val { font-family: 'Sora', sans-serif; font-size: 13px; font-weight: 900; color: white; }
.pt-ring-lbl { font-size: 6px; color: rgba(255,255,255,.7); font-weight: 600; }
.pt-info-t { font-family: 'Sora', sans-serif; font-size: 11px; font-weight: 800; color: white; }
.pt-info-s { font-size: 9px; color: rgba(255,255,255,.65); margin-top: 2px; }
.phone-stats { display: flex; gap: 7px; padding: 0 10px; margin-bottom: 10px; }
.ps { flex: 1; background: white; border-radius: 10px; padding: 9px; text-align: center; }
.ps-v { font-family: 'Sora', sans-serif; font-size: 13px; font-weight: 900; color: var(--g); }
.ps-l { font-size: 7px; color: #7a927c; font-weight: 600; margin-top: 2px; text-transform: uppercase; }
.phone-nav {
  background: white; border-top: 1px solid #e8ece8;
  display: flex; justify-content: space-around; padding: 8px 0 12px;
}
.pn-item { display: flex; flex-direction: column; align-items: center; gap: 2px; }
.pn-item svg { width: 16px; height: 16px; color: #7a927c; }
.pn-item.on svg { color: var(--g); }
.pn-item span { font-size: 8px; color: #7a927c; font-weight: 600; }
.pn-item.on span { color: var(--g); }

/* floating badge */
.float-badge {
  position: absolute; background: white; border-radius: 14px;
  padding: 10px 14px; box-shadow: 0 8px 32px rgba(0,0,0,.2);
  display: flex; align-items: center; gap: 8px; z-index: 2;
}
.fb-1 { bottom: -10px; left: -40px; animation: floatUp 3s ease-in-out infinite; }
.fb-2 { top: 80px; right: -50px; animation: floatUp 3s ease-in-out infinite .8s; }
@keyframes floatUp { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
.fb-icon { width: 32px; height: 32px; border-radius: 9px; display: flex; align-items: center; justify-content: center; }
.fb-icon svg { width: 16px; height: 16px; }
.fb-val { font-family: 'Sora', sans-serif; font-size: 14px; font-weight: 800; color: var(--text); }
.fb-lbl { font-size: 10px; color: var(--muted); font-weight: 500; }

/* ══════════════════ SECTION COMMON ══════════════════ */
section { padding: 100px 5%; }
.container { max-width: 1200px; margin: 0 auto; }
.section-kicker {
  display: inline-block; background: var(--gl); color: var(--gd);
  font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;
  padding: 5px 14px; border-radius: 50px; margin-bottom: 16px;
}
.section-title { font-size: clamp(30px, 4vw, 48px); color: var(--text); margin-bottom: 16px; }
.section-sub { font-size: 17px; color: var(--muted); line-height: 1.7; max-width: 580px; }
.text-center { text-align: center; }
.text-center .section-sub { margin: 0 auto; }

/* ══════════════════ HOW IT WORKS ══════════════════ */
#how { background: var(--bg); }
.steps-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-top: 60px; }
.step-card {
  background: var(--white); border-radius: 20px; padding: 28px 24px;
  box-shadow: 0 2px 16px rgba(0,0,0,.06); position: relative;
  transition: transform .2s, box-shadow .2s;
}
.step-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(0,0,0,.1); }
.step-num {
  width: 44px; height: 44px; border-radius: 14px;
  background: var(--gdk); color: white;
  font-family: 'Sora', sans-serif; font-size: 20px; font-weight: 900;
  display: flex; align-items: center; justify-content: center; margin-bottom: 18px;
}
.step-icon { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; }
.step-icon svg { width: 26px; height: 26px; }
.step-title { font-family: 'Sora', sans-serif; font-size: 16px; font-weight: 800; color: var(--text); margin-bottom: 8px; }
.step-desc { font-size: 14px; color: var(--muted); line-height: 1.6; }
.step-connector {
  position: absolute; top: 44px; right: -24px;
  width: 24px; height: 2px; background: var(--border); z-index: 1;
}
.step-card:last-child .step-connector { display: none; }

/* ══════════════════ FEATURES ══════════════════ */
#features { background: var(--white); }
.features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-top: 60px; }
.feat-card {
  background: var(--bg); border-radius: 20px; padding: 28px;
  border: 1px solid var(--border); transition: all .2s;
}
.feat-card:hover { background: var(--white); border-color: var(--glm); box-shadow: 0 8px 32px rgba(46,204,64,.1); transform: translateY(-2px); }
.feat-icon { width: 52px; height: 52px; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 18px; }
.feat-icon svg { width: 28px; height: 28px; }
.feat-title { font-family: 'Sora', sans-serif; font-size: 17px; font-weight: 800; color: var(--text); margin-bottom: 10px; }
.feat-desc { font-size: 14px; color: var(--muted); line-height: 1.7; }
.feat-tag { display: inline-block; margin-top: 14px; padding: 4px 11px; background: var(--gl); color: var(--gd); border-radius: 50px; font-size: 11px; font-weight: 700; }

/* ══════════════════ POINTS SYSTEM ══════════════════ */
#points { background: var(--gdk); padding: 100px 5%; overflow: hidden; position: relative; }
#points::before {
  content: '';
  position: absolute; inset: 0;
  background: radial-gradient(ellipse at 10% 50%, rgba(46,204,64,.18) 0%, transparent 60%),
              radial-gradient(ellipse at 90% 20%, rgba(232,201,0,.08) 0%, transparent 50%);
}
.pts-inner { max-width: 1200px; margin: 0 auto; position: relative; z-index: 1; display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center; }
.pts-left .section-kicker { background: rgba(46,204,64,.2); color: var(--gm); }
.pts-left .section-title { color: white; }
.pts-left .section-sub { color: rgba(255,255,255,.6); }
.pts-table-wrap { margin-top: 32px; }
.pts-table {
  width: 100%; border-collapse: collapse;
  background: rgba(255,255,255,.05); border-radius: 16px; overflow: hidden;
}
.pts-table th {
  background: rgba(46,204,64,.25); color: rgba(255,255,255,.8);
  padding: 14px 18px; font-size: 12px; font-weight: 700; text-align: left; text-transform: uppercase; letter-spacing: .5px;
}
.pts-table td { padding: 14px 18px; border-bottom: 1px solid rgba(255,255,255,.07); font-size: 14px; color: rgba(255,255,255,.8); }
.pts-table tr:last-child td { border-bottom: none; }
.pts-table .pts-val { font-family: 'Sora', sans-serif; font-weight: 800; color: var(--g); }
.pts-table .rs-val { font-weight: 700; color: var(--y); }

.pts-right { display: flex; flex-direction: column; gap: 20px; }
.pts-card {
  background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.1);
  border-radius: 20px; padding: 24px;
}
.pts-card-title { font-family: 'Sora', sans-serif; font-size: 15px; font-weight: 800; color: white; margin-bottom: 16px; }
.level-bars { display: flex; flex-direction: column; gap: 12px; }
.lv-bar-row { display: flex; align-items: center; gap: 14px; }
.lv-bar-label { width: 60px; font-size: 12px; font-weight: 700; flex-shrink: 0; }
.lv-bar-track { flex: 1; height: 8px; background: rgba(255,255,255,.1); border-radius: 50px; overflow: hidden; }
.lv-bar-fill { height: 100%; border-radius: 50px; }
.lv-bar-pts { font-family: 'Sora', sans-serif; font-size: 12px; font-weight: 700; width: 64px; text-align: right; flex-shrink: 0; }

.bonus-list { display: flex; flex-direction: column; gap: 10px; }
.bonus-item { display: flex; justify-content: space-between; align-items: center; }
.bonus-name { font-size: 13px; color: rgba(255,255,255,.7); }
.bonus-pts { font-family: 'Sora', sans-serif; font-size: 13px; font-weight: 800; color: var(--g); }

/* ══════════════════ REDEEM ══════════════════ */
#redeem { background: var(--bg); }
.redeem-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-top: 60px; }
.reward-card {
  background: var(--white); border-radius: 20px; padding: 24px;
  text-align: center; box-shadow: 0 2px 16px rgba(0,0,0,.06);
  border: 1px solid var(--border); transition: all .2s;
}
.reward-card:hover { transform: translateY(-3px); box-shadow: 0 12px 40px rgba(46,204,64,.12); border-color: var(--glm); }
.reward-icon { width: 56px; height: 56px; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; }
.reward-icon svg { width: 28px; height: 28px; }
.reward-name { font-family: 'Sora', sans-serif; font-size: 15px; font-weight: 800; color: var(--text); margin-bottom: 6px; }
.reward-value { font-size: 13px; color: var(--muted); margin-bottom: 4px; }
.reward-pts { font-family: 'Sora', sans-serif; font-size: 13px; font-weight: 800; color: var(--gd); }

.convert-banner {
  background: linear-gradient(135deg, var(--y), #c9a000);
  border-radius: 20px; padding: 28px 36px;
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 48px; flex-wrap: wrap; gap: 16px;
}
.cb-left .cb-main { font-family: 'Sora', sans-serif; font-size: 28px; font-weight: 900; color: #1a1800; }
.cb-left .cb-sub { font-size: 13px; color: #5a4a00; font-weight: 600; margin-top: 4px; }
.cb-right { font-family: 'Sora', sans-serif; font-size: 22px; font-weight: 900; color: #1a1800; }

/* ══════════════════ DDC / TRACKING ══════════════════ */
#track { background: var(--white); }
.track-inner { display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center; }
.timeline { display: flex; flex-direction: column; gap: 0; margin-top: 36px; }
.tl-item { display: flex; gap: 16px; }
.tl-col { display: flex; flex-direction: column; align-items: center; width: 32px; flex-shrink: 0; }
.tl-dot { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.tl-dot svg { width: 16px; height: 16px; }
.tl-line { width: 2px; flex: 1; min-height: 28px; margin: 4px 0; }
.tl-done .tl-dot { background: var(--g); }
.tl-done .tl-dot svg { color: white; }
.tl-done .tl-line { background: var(--g); }
.tl-active .tl-dot { background: var(--y); }
.tl-active .tl-dot svg { color: #2a2000; }
.tl-active .tl-line { background: var(--border); }
.tl-pending .tl-dot { background: var(--border); }
.tl-pending .tl-dot svg { color: var(--muted); }
.tl-pending .tl-line { background: var(--border); }
.tl-body { flex: 1; padding-bottom: 28px; }
.tl-t { font-family: 'Sora', sans-serif; font-weight: 700; font-size: 15px; color: var(--text); }
.tl-d { font-size: 13px; color: var(--muted); margin-top: 4px; line-height: 1.6; }

.ddc-visual {
  background: linear-gradient(150deg, var(--gdk), #1a5c20);
  border-radius: 24px; padding: 36px; position: relative; overflow: hidden;
}
.ddc-visual::before {
  content: ''; position: absolute; width: 200px; height: 200px; border-radius: 50%;
  background: rgba(255,255,255,.04); top: -60px; right: -60px;
}
.ddc-title { font-family: 'Sora', sans-serif; font-size: 18px; font-weight: 800; color: white; margin-bottom: 24px; }
.ddc-flow { display: flex; flex-direction: column; gap: 14px; }
.ddc-row { display: flex; align-items: center; gap: 14px; }
.ddc-node {
  display: flex; align-items: center; gap: 10px;
  background: rgba(255,255,255,.1); border-radius: 12px; padding: 11px 16px; flex-shrink: 0;
}
.ddc-node svg { width: 18px; height: 18px; color: var(--gm); }
.ddc-node span { font-family: 'Sora', sans-serif; font-size: 13px; font-weight: 700; color: white; }
.ddc-arrow { flex: 1; display: flex; align-items: center; gap: 8px; }
.ddc-arrow-line { flex: 1; height: 1px; background: rgba(255,255,255,.15); }
.ddc-arrow-pts { font-family: 'Sora', sans-serif; font-size: 12px; font-weight: 800; color: var(--gm); white-space: nowrap; }
.ddc-arrow-none { font-family: 'Sora', sans-serif; font-size: 12px; font-weight: 800; color: rgba(255,255,255,.35); }
.ddc-dest { background: rgba(21,101,192,.3); border-radius: 12px; padding: 11px 16px; }
.ddc-dest span { font-family: 'Sora', sans-serif; font-size: 13px; font-weight: 700; color: white; }
.ddc-foot { font-size: 11px; color: rgba(255,255,255,.35); margin-top: 16px; }
.ddc-badge {
  background: var(--y); border-radius: 12px; padding: 14px 18px; margin-top: 20px;
  display: flex; align-items: center; justify-content: space-between;
}
.ddc-badge-main { font-family: 'Sora', sans-serif; font-size: 18px; font-weight: 900; color: #1a1800; }
.ddc-badge-sub { font-size: 11px; color: #5a4a00; font-weight: 600; }

/* ══════════════════ IMPACT ══════════════════ */
#impact { background: var(--bg); }
.impact-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-top: 60px; }
.impact-card {
  background: var(--white); border-radius: 20px; padding: 28px;
  box-shadow: 0 2px 16px rgba(0,0,0,.06); text-align: center; transition: all .2s;
}
.impact-card:hover { transform: translateY(-3px); box-shadow: 0 10px 36px rgba(46,204,64,.1); }
.impact-icon { width: 60px; height: 60px; border-radius: 18px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
.impact-icon svg { width: 30px; height: 30px; }
.impact-val { font-family: 'Sora', sans-serif; font-size: 36px; font-weight: 900; color: var(--text); }
.impact-label { font-size: 14px; color: var(--muted); font-weight: 600; margin-top: 6px; }
.impact-sub { font-size: 12px; color: var(--muted); margin-top: 6px; line-height: 1.5; }

.impact-cta {
  background: linear-gradient(135deg, var(--gdk), #1a5c20);
  border-radius: 24px; padding: 48px; margin-top: 48px; text-align: center;
}
.impact-cta h3 { font-family: 'Sora', sans-serif; font-size: 28px; font-weight: 800; color: white; margin-bottom: 10px; }
.impact-cta p { color: rgba(255,255,255,.65); font-size: 15px; margin-bottom: 28px; }

/* ══════════════════ APP CONNECT ══════════════════ */
#app-connect { background: linear-gradient(180deg, #f2f8f2 0%, #ffffff 100%); }
.connect-grid { display: grid; grid-template-columns: 1.05fr .95fr; gap: 28px; margin-top: 56px; align-items: start; }
.connect-card {
  background: var(--white); border: 1px solid var(--border); border-radius: 24px;
  padding: 26px; box-shadow: 0 12px 40px rgba(15,31,17,.06);
}
.connect-card-dark {
  background: linear-gradient(160deg, #0d2410 0%, #17431b 100%);
  color: white; border-color: rgba(255,255,255,.08);
}
.connect-title { font-family: 'Sora', sans-serif; font-size: 22px; font-weight: 800; margin-bottom: 10px; }
.connect-sub { font-size: 14px; line-height: 1.7; color: var(--muted); margin-bottom: 20px; }
.connect-card-dark .connect-sub { color: rgba(255,255,255,.68); }
.connect-tabs { display: flex; gap: 10px; margin-bottom: 18px; flex-wrap: wrap; }
.connect-tab {
  border: 1px solid var(--border); background: var(--bg); color: var(--text);
  border-radius: 999px; padding: 10px 16px; font-size: 13px; font-weight: 700; cursor: pointer;
}
.connect-tab.active { background: var(--g); color: white; border-color: var(--g); }
.connect-form { display: grid; gap: 14px; }
.connect-form.hidden { display: none; }
.connect-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.connect-input, .connect-select, .connect-textarea {
  width: 100%; border: 1px solid var(--border); background: #fbfdfb; border-radius: 14px;
  padding: 14px 15px; font: inherit; color: var(--text);
}
.connect-textarea { min-height: 104px; resize: vertical; }
.connect-btn {
  border: none; cursor: pointer; border-radius: 14px; padding: 14px 18px;
  font-family: 'Sora', sans-serif; font-size: 14px; font-weight: 700;
  background: var(--g); color: white; box-shadow: 0 8px 24px rgba(46,204,64,.24);
}
.connect-btn.secondary { background: #113716; color: white; box-shadow: none; }
.connect-status {
  margin-top: 16px; border-radius: 16px; padding: 14px 16px;
  background: #f5fbf6; border: 1px solid #d8ead9; color: #1f4d26; font-size: 13px; line-height: 1.6;
}
.connect-status.error { background: #fff3f2; border-color: #f3d0cc; color: #8f2e24; }
.connect-status.hidden { display: none; }
.connect-metrics { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin: 22px 0; }
.metric-box {
  background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.08);
  border-radius: 18px; padding: 18px;
}
.metric-label { font-size: 11px; color: rgba(255,255,255,.58); text-transform: uppercase; letter-spacing: .7px; }
.metric-value { font-family: 'Sora', sans-serif; font-size: 30px; font-weight: 900; color: white; margin-top: 6px; }
.connect-actions { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 18px; }
.connect-inline-btn {
  border: 1px solid rgba(255,255,255,.18); background: rgba(255,255,255,.08); color: white;
  border-radius: 12px; padding: 12px 14px; font-size: 13px; font-weight: 700; cursor: pointer;
}
.connect-list { display: grid; gap: 12px; margin-top: 20px; }
.connect-item {
  background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.08);
  border-radius: 16px; padding: 16px;
}
.connect-item-top { display: flex; justify-content: space-between; gap: 12px; align-items: flex-start; }
.connect-item-title { font-family: 'Sora', sans-serif; font-size: 14px; font-weight: 700; color: white; }
.connect-item-meta { font-size: 12px; color: rgba(255,255,255,.6); margin-top: 6px; }
.connect-pill {
  background: rgba(232,201,0,.15); color: #ffe784; border-radius: 999px; padding: 6px 10px;
  font-size: 11px; font-weight: 800; white-space: nowrap;
}
.connect-empty {
  border: 1px dashed rgba(255,255,255,.18); border-radius: 18px; padding: 18px;
  font-size: 13px; color: rgba(255,255,255,.65);
}

/* ══════════════════ PREMIUM ══════════════════ */
#premium { background: var(--white); }
.premium-inner { display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center; }
.premium-features { display: flex; flex-direction: column; gap: 16px; margin-top: 32px; }
.pf-row {
  display: flex; align-items: flex-start; gap: 16px;
  background: var(--bg); border-radius: 16px; padding: 18px; border: 1px solid var(--border);
  transition: all .2s;
}
.pf-row:hover { background: var(--white); border-color: var(--glm); box-shadow: 0 4px 20px rgba(46,204,64,.08); }
.pf-icon { width: 44px; height: 44px; background: var(--yl); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.pf-icon svg { width: 22px; height: 22px; color: var(--yd); }
.pf-t { font-family: 'Sora', sans-serif; font-size: 15px; font-weight: 700; color: var(--text); margin-bottom: 4px; }
.pf-d { font-size: 13px; color: var(--muted); line-height: 1.5; }

.plans-wrap { display: flex; flex-direction: column; gap: 16px; }
.plan-box {
  background: var(--bg); border: 2px solid var(--border); border-radius: 20px;
  padding: 24px; cursor: pointer; transition: all .2s;
}
.plan-box:hover, .plan-box.pop { border-color: var(--y); background: var(--yl); }
.plan-box.pop { position: relative; }
.plan-box-badge {
  position: absolute; top: -12px; right: 20px;
  background: var(--y); color: #1a1800; font-size: 11px; font-weight: 800;
  padding: 4px 12px; border-radius: 50px;
}
.plan-row { display: flex; justify-content: space-between; align-items: flex-start; }
.plan-name-txt { font-family: 'Sora', sans-serif; font-size: 18px; font-weight: 800; color: var(--text); }
.plan-sub-txt { font-size: 12px; color: var(--muted); margin-top: 3px; }
.plan-price-txt { font-family: 'Sora', sans-serif; font-size: 26px; font-weight: 900; color: var(--yd); }
.plan-per { font-size: 12px; color: var(--muted); font-weight: 500; }

/* ══════════════════ FAQ ══════════════════ */
#faq { background: var(--bg); }
.faq-inner { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; }
.faq-list { display: flex; flex-direction: column; gap: 12px; margin-top: 32px; }
.faq-item {
  background: var(--white); border-radius: 16px; overflow: hidden;
  border: 1px solid var(--border); cursor: pointer; transition: all .2s;
}
.faq-item:hover { border-color: var(--glm); }
.faq-q {
  display: flex; justify-content: space-between; align-items: center;
  padding: 18px 20px; font-family: 'Sora', sans-serif; font-size: 14px; font-weight: 700; color: var(--text);
}
.faq-q svg { width: 18px; height: 18px; color: var(--muted); transition: transform .2s; flex-shrink: 0; }
.faq-item.open .faq-q svg { transform: rotate(180deg); color: var(--g); }
.faq-a { display: none; padding: 0 20px 18px; font-size: 13px; color: var(--muted); line-height: 1.7; }
.faq-item.open .faq-a { display: block; }
.faq-visual {
  background: linear-gradient(150deg, var(--gdk), #1a5c20);
  border-radius: 24px; padding: 40px; display: flex; flex-direction: column; justify-content: center;
}
.faq-stat { margin-bottom: 28px; }
.faq-stat-val { font-family: 'Sora', sans-serif; font-size: 44px; font-weight: 900; color: white; letter-spacing: -2px; }
.faq-stat-lbl { font-size: 13px; color: rgba(255,255,255,.55); margin-top: 4px; }

/* ══════════════════ CTA ══════════════════ */
#cta {
  background: linear-gradient(160deg, #071a09 0%, #0d3312 40%, #1a6620 70%, #2ECC40 100%);
  padding: 120px 5%; text-align: center; position: relative; overflow: hidden;
}
#cta::before {
  content: '';
  position: absolute; inset: 0;
  background: radial-gradient(circle at 50% 50%, rgba(46,204,64,.15) 0%, transparent 70%);
}
.cta-inner { max-width: 700px; margin: 0 auto; position: relative; z-index: 1; }
.cta-title { font-size: clamp(32px, 5vw, 54px); color: white; margin-bottom: 18px; }
.cta-title span { color: var(--y); }
.cta-sub { font-size: 17px; color: rgba(255,255,255,.65); margin-bottom: 40px; line-height: 1.7; }
.cta-btns { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }
.cta-store {
  display: flex; align-items: center; gap: 12px;
  background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
  color: white; padding: 14px 24px; border-radius: 14px; text-decoration: none; transition: all .2s;
}
.cta-store:hover { background: rgba(255,255,255,.22); transform: translateY(-2px); }
.cta-store svg { width: 24px; height: 24px; }
.cta-store-sub { font-size: 10px; color: rgba(255,255,255,.6); font-weight: 500; }
.cta-store-name { font-family: 'Sora', sans-serif; font-size: 15px; font-weight: 700; }

/* ══════════════════ FOOTER ══════════════════ */
footer {
  background: var(--gdk); padding: 60px 5% 32px;
}
.footer-inner { max-width: 1200px; margin: 0 auto; }
.footer-top { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 48px; margin-bottom: 48px; }
.footer-brand .nav-logo { margin-bottom: 16px; text-decoration: none; }
.footer-brand .nav-logo-name { color: white; }
.footer-tagline { font-size: 13px; color: rgba(255,255,255,.45); line-height: 1.7; max-width: 260px; }
.footer-col h4 { font-family: 'Sora', sans-serif; font-size: 12px; font-weight: 800; color: rgba(255,255,255,.5); text-transform: uppercase; letter-spacing: .7px; margin-bottom: 16px; }
.footer-col a { display: block; font-size: 13px; color: rgba(255,255,255,.55); text-decoration: none; margin-bottom: 10px; transition: color .15s; }
.footer-col a:hover { color: white; }
.footer-bottom { border-top: 1px solid rgba(255,255,255,.07); padding-top: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
.footer-copy { font-size: 12px; color: rgba(255,255,255,.3); }
.footer-pts-badge {
  display: inline-flex; align-items: center; gap: 6px;
  background: rgba(46,204,64,.15); border: 1px solid rgba(46,204,64,.25);
  border-radius: 50px; padding: 5px 14px; font-size: 11px; font-weight: 700; color: var(--gm);
}

/* ══════════════════ RESPONSIVE ══════════════════ */
@media (max-width: 1024px) {
  .steps-grid { grid-template-columns: repeat(2, 1fr); }
  .features-grid { grid-template-columns: repeat(2, 1fr); }
  .redeem-grid { grid-template-columns: repeat(2, 1fr); }
  .impact-grid { grid-template-columns: repeat(2, 1fr); }
  .footer-top { grid-template-columns: 1fr 1fr; gap: 32px; }
}
@media (max-width: 768px) {
  nav { padding: 0 5%; }
  .nav-links, .nav-cta { display: none; }
  .nav-ham { display: flex; }
  .mob-menu {
    position: fixed; top: 68px; left: 0; right: 0; z-index: 99;
    background: white; border-bottom: 1px solid var(--border);
    padding: 20px 5%; display: none; flex-direction: column; gap: 16px;
  }
  .mob-menu.open { display: flex; }
  .mob-menu a { font-size: 15px; font-weight: 600; color: var(--text); text-decoration: none; padding: 8px 0; border-bottom: 1px solid var(--border); }
  .mob-menu a:last-child { border: none; background: var(--g); color: white; text-align: center; padding: 12px; border-radius: 12px; }
  .hero-inner { grid-template-columns: 1fr; gap: 48px; text-align: center; }
  .hero-phone-wrap { order: -1; }
  .hero-btns { justify-content: center; }
  .hero-stats { justify-content: center; }
  .phone-mockup { width: 240px; }
  .fb-1, .fb-2 { display: none; }
  .steps-grid { grid-template-columns: 1fr; }
  .step-connector { display: none; }
  .features-grid { grid-template-columns: 1fr; }
  .pts-inner { grid-template-columns: 1fr; gap: 40px; }
  .track-inner { grid-template-columns: 1fr; gap: 40px; }
  .connect-grid { grid-template-columns: 1fr; }
  .connect-row { grid-template-columns: 1fr; }
  .connect-metrics { grid-template-columns: 1fr 1fr; }
  .premium-inner { grid-template-columns: 1fr; gap: 40px; }
  .redeem-grid { grid-template-columns: repeat(2, 1fr); }
  .impact-grid { grid-template-columns: 1fr; }
  .faq-inner { grid-template-columns: 1fr; }
  .footer-top { grid-template-columns: 1fr; gap: 28px; }
  section { padding: 64px 5%; }
  .convert-banner { flex-direction: column; text-align: center; }
}
@media (max-width: 480px) {
  .redeem-grid { grid-template-columns: 1fr; }
  .cta-btns { flex-direction: column; align-items: center; }
  .hero-stats { flex-wrap: wrap; gap: 20px; }
  .connect-metrics { grid-template-columns: 1fr; }
}

/* ══════════════════ SCROLL ANIMATIONS ══════════════════ */
.fade-up {
  opacity: 0; transform: translateY(30px);
  transition: opacity .6s ease, transform .6s ease;
}
.fade-up.visible { opacity: 1; transform: translateY(0); }
</style>
</head>
<body>

<!-- ══════════ NAV ══════════ -->
<nav id="navbar">
  <a href="#" class="nav-logo">
    <div class="nav-logo-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M9 6V4h6v2"/></svg>
    </div>
    <span class="nav-logo-name">TrashDeal</span>
  </a>
  <div class="nav-links">
    <a href="#how">How It Works</a>
    <a href="#features">Features</a>
    <a href="#points">Points</a>
    <a href="#redeem">Rewards</a>
    
    <a href="#faq">FAQ</a>
  </div>
    <a href="#app-connect" class="nav-cta">Open Live Demo</a>
  <div class="nav-ham" onclick="toggleMenu()" id="ham">
    <span></span><span></span><span></span>
  </div>
</nav>
<div class="mob-menu" id="mobMenu">
  <a href="#how" onclick="toggleMenu()">How It Works</a>
  <a href="#features" onclick="toggleMenu()">Features</a>
  <a href="#points" onclick="toggleMenu()">Points System</a>
  <a href="#redeem" onclick="toggleMenu()">Rewards</a>
  
  <a href="#faq" onclick="toggleMenu()">FAQ</a>
  <a href="#app-connect" onclick="toggleMenu()">Open Live Demo</a>
</div>

<!-- ══════════ HERO ══════════ -->
<section class="hero" id="home">
  <div class="hero-inner">
    <div class="hero-text">
      <div class="hero-kicker">
        <div class="hero-kicker-dot"></div>
        <span>India's Waste Reward Platform</span>
      </div>
      <h1 class="display hero-title">Turn Your Waste<br>Into <span>Real Points</span></h1>
      <p class="hero-desc">Segregate waste, schedule pickups, earn points, and redeem rewards — all while making India cleaner. Aligned with Swachh Bharat Mission.</p>
      <div class="hero-btns">
          <a href="#app-connect" class="btn-hero-primary">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M9 6V4h6v2"/></svg>
          Get Started Free
        </a>
        <a href="#how" class="btn-hero-secondary">See How It Works</a>
      </div>
      <div class="hero-stats">
        <div>
          <div class="hero-stat-val">50K+</div>
          <div class="hero-stat-lbl">Active Users</div>
        </div>
        <div>
          <div class="hero-stat-val">1200+</div>
          <div class="hero-stat-lbl">Avg Monthly Points</div>
        </div>
        <div>
          <div class="hero-stat-val">1.2T</div>
          <div class="hero-stat-lbl">CO₂ Offset</div>
        </div>
      </div>
    </div>

    <!-- Phone mockup -->
    <div class="hero-phone-wrap">
      <!-- Float badge 1 -->
      <div class="float-badge fb-1">
        <div class="fb-icon" style="background:#e8f5ea;"><svg viewBox="0 0 24 24" fill="none" stroke="var(--g)" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
        <div><div class="fb-val">+158 pts</div><div class="fb-lbl">Organic pickup</div></div>
      </div>
      <!-- Float badge 2 -->
      <div class="float-badge fb-2">
        <div class="fb-icon" style="background:#fffad6;"><svg viewBox="0 0 24 24" fill="none" stroke="var(--yd)" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 102.13-9.36L1 10"/></svg></div>
        <div><div class="fb-val">Silver</div><div class="fb-lbl">Level unlocked</div></div>
      </div>

      <div class="phone-mockup">
        <div class="phone-screen">
          <div class="phone-sb">
            <span>9:41</span>
            <span class="phone-sb-brand">TrashDeal</span>
            <span>100%</span>
          </div>
          <div style="background:#f4f6f4;padding:0 0 8px;">
            <!-- Points hero card -->
            <div class="phone-hero-card">
              <div class="phc-lbl">Good Morning, Rahul</div>
              <div class="phc-val">1700</div>
              <div class="phc-sub">Total TrashDeal Points · Silver Member</div>
              <div class="phc-actions">
                <div class="phca"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg><span>Dispose</span></div>
                <div class="phca"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><span>Points</span></div>
                <div class="phca"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg><span>Redeem</span></div>
              </div>
            </div>
            <!-- Tracker card -->
            <div class="phone-tracker">
              <div class="pt-ring">
                <svg viewBox="0 0 52 52">
                  <circle fill="none" stroke="rgba(255,255,255,.2)" stroke-width="5" cx="26" cy="26" r="21"/>
                  <circle fill="none" stroke="#E8C900" stroke-width="5" cx="26" cy="26" r="21" stroke-linecap="round" stroke-dasharray="132" stroke-dashoffset="60"/>
                </svg>
                <div class="pt-ring-inner"><div class="pt-ring-val">12</div><div class="pt-ring-lbl">min away</div></div>
              </div>
              <div><div class="pt-info-t">Collector En Route</div><div class="pt-info-s">DDC-7 heading your way</div></div>
            </div>
            <!-- Stats -->
            <div class="phone-stats">
              <div class="ps"><div class="ps-v">48kg</div><div class="ps-l">Recycled</div></div>
              <div class="ps"><div class="ps-v">16</div><div class="ps-l">Pickups</div></div>
              <div class="ps"><div class="ps-v">32kg</div><div class="ps-l">CO₂ Saved</div></div>
            </div>
          </div>
          <!-- Nav -->
          <div class="phone-nav">
            <div class="pn-item on"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg><span>Home</span></div>
            <div class="pn-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg><span>Dispose</span></div>
            <div class="pn-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><span>Points</span></div>
            <div class="pn-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg><span>Redeem</span></div>
            <div class="pn-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg><span>Profile</span></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ HOW IT WORKS ══════════ -->
<section id="how">
  <div class="container">
    <div class="text-center fade-up">
      <div class="section-kicker">How It Works</div>
      <h2 class="heading section-title">Four simple steps to earning</h2>
      <p class="section-sub">From your doorstep to verified rewards, TrashDeal makes responsible waste disposal effortless, trackable, and fun.</p>
    </div>
    <div class="steps-grid">
      <div class="step-card fade-up">
        <div class="step-num">1</div>
        <div class="step-icon" style="background:#e8f5ea;"><svg viewBox="0 0 24 24" fill="none" stroke="var(--g)" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
        <div class="step-title">Segregate</div>
        <div class="step-desc">Separate your waste into Organic, Recyclable, and Inert categories at home using TrashDeal's guide.</div>
        <div class="step-connector"></div>
      </div>
      <div class="step-card fade-up" style="transition-delay:.1s;">
        <div class="step-num">2</div>
        <div class="step-icon" style="background:#fffad6;"><svg viewBox="0 0 24 24" fill="none" stroke="var(--yd)" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
        <div class="step-title">Schedule</div>
        <div class="step-desc">Book a pickup slot in the app. Track your DDC collector live on the map — know exactly when they arrive.</div>
        <div class="step-connector"></div>
      </div>
      <div class="step-card fade-up" style="transition-delay:.2s;">
        <div class="step-num">3</div>
        <div class="step-icon" style="background:#e3f2fd;"><svg viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
        <div class="step-title">Verify</div>
        <div class="step-desc">Our anti-cheat system verifies weight and category at the DDC using digital scales, QR scans, and photos.</div>
        <div class="step-connector"></div>
      </div>
      <div class="step-card fade-up" style="transition-delay:.3s;">
        <div class="step-num">4</div>
        <div class="step-icon" style="background:#e8f5ea;"><svg viewBox="0 0 24 24" fill="none" stroke="var(--g)" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
        <div class="step-title">Earn & Redeem</div>
        <div class="step-desc">Points are credited instantly. Redeem for groceries, Amazon vouchers, fuel coupons, bus passes, and more.</div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ FEATURES ══════════ -->
<section id="features">
  <div class="container">
    <div class="text-center fade-up">
      <div class="section-kicker">Features</div>
      <h2 class="heading section-title">Everything in one app</h2>
      <p class="section-sub">TrashDeal is built with every feature you need to manage waste responsibly and earn maximum rewards.</p>
    </div>
    <div class="features-grid">
      <div class="feat-card fade-up">
        <div class="feat-icon" style="background:#e8f5ea;"><svg viewBox="0 0 24 24" fill="none" stroke="var(--g)" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></div>
        <div class="feat-title">Live DDC Tracking</div>
        <div class="feat-desc">Track your waste collector in real-time on a live map. Get accurate ETAs so you're always ready for pickup.</div>
        <span class="feat-tag">Real-time GPS</span>
      </div>
      <div class="feat-card fade-up" style="transition-delay:.08s;">
        <div class="feat-icon" style="background:#fffad6;"><svg viewBox="0 0 24 24" fill="none" stroke="var(--yd)" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
        <div class="feat-title">Dynamic Points System</div>
        <div class="feat-desc">Base points per kg plus weekly bonuses, first-pickup bonuses, and clean-waste bonuses. Never stop earning.</div>
        <span class="feat-tag" style="background:var(--yl);color:var(--yd);">Smart Rewards</span>
      </div>
      <div class="feat-card fade-up" style="transition-delay:.16s;">
        <div class="feat-icon" style="background:#e3f2fd;"><svg viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
        <div class="feat-title">Smart Verification</div>
        <div class="feat-desc">3-step anti-cheat: digital weighing scale, QR code scan, and timestamped photo evidence at every DDC.</div>
        <span class="feat-tag" style="background:#e3f2fd;color:#1565C0;">Anti-Cheat</span>
      </div>
      <div class="feat-card fade-up" style="transition-delay:.0s;">
        <div class="feat-icon" style="background:#e8f5ea;"><svg viewBox="0 0 24 24" fill="none" stroke="var(--g)" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></div>
        <div class="feat-title">Level System</div>
        <div class="feat-desc">Progress from Bronze to Silver to Gold. Higher levels unlock better point conversion rates and exclusive perks.</div>
        <span class="feat-tag">Bronze → Silver → Gold</span>
      </div>
      <div class="feat-card fade-up" style="transition-delay:.08s;">
        <div class="feat-icon" style="background:#fffad6;"><svg viewBox="0 0 24 24" fill="none" stroke="var(--yd)" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg></div>
        <div class="feat-title">Weekly Challenges</div>
        <div class="feat-desc">Complete weekly goals — "Dispose 5 kg", "Recycle plastic" — and earn bonus points to level up faster.</div>
        <span class="feat-tag" style="background:var(--yl);color:var(--yd);">Gamified</span>
      </div>
      <div class="feat-card fade-up" style="transition-delay:.16s;">
        <div class="feat-icon" style="background:#fde8e8;"><svg viewBox="0 0 24 24" fill="none" stroke="#b71c1c" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></div>
        <div class="feat-title">Environmental Impact</div>
        <div class="feat-desc">Track your personal CO₂ saved, kg recycled, trees planted equivalent, and landfill diverted over time.</div>
        <span class="feat-tag" style="background:#fde8e8;color:#b71c1c;">Eco Dashboard</span>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ POINTS SYSTEM ══════════ -->
<section id="points">
  <div class="pts-inner container">
    <div class="pts-left fade-up">
      <div class="section-kicker">Points System</div>
      <h2 class="heading section-title">Earn more by<br>segregating better</h2>
      <p class="section-sub">Points are earned only by waste type, so the system stays simple, transparent, and easy to understand.</p>
      <div class="pts-table-wrap">
        <table class="pts-table">
          <thead>
            <tr><th>Waste Type</th><th>Points per kg</th></tr>
            </thead>
            <tbody>
              <tr>
                <td>Organic</td>
                <td class="pts-val">30 pts/kg</td>
              </tr>
              <tr>
                <td>Recyclable</td>
                <td class="pts-val">60 pts/kg</td>
              </tr>
              <tr>
                <td>Inert</td>
                <td style="color:rgba(255,255,255,.3);">0 pts/kg</td>
              </tr>
            </tbody>
        </table>
      </div>
    </div>

    <div class="pts-right fade-up" style="transition-delay:.15s;">
      <div class="pts-card">
          <div class="pts-card-title">Points Milestones</div>
          <div class="level-bars">
            <div class="lv-bar-row">
              <div class="lv-bar-label" style="color:#cd7f32;">Starter</div>
              <div class="lv-bar-track"><div class="lv-bar-fill" style="width:35%;background:#cd7f32;"></div></div>
              <div class="lv-bar-pts" style="color:#cd7f32;">250 pts</div>
            </div>
            <div class="lv-bar-row">
              <div class="lv-bar-label" style="color:#9E9E9E;">Saver</div>
              <div class="lv-bar-track"><div class="lv-bar-fill" style="width:68%;background:#9E9E9E;"></div></div>
              <div class="lv-bar-pts" style="color:var(--gm);">750 pts</div>
            </div>
            <div class="lv-bar-row">
              <div class="lv-bar-label" style="color:var(--y);">Champion</div>
              <div class="lv-bar-track"><div class="lv-bar-fill" style="width:100%;background:var(--y);"></div></div>
              <div class="lv-bar-pts" style="color:var(--y);">1500 pts</div>
            </div>
          </div>
        </div>
            <div class="lv-bar-pts" style="color:#cd7f32;">250 pts</div>
          </div>
          <div class="lv-bar-row">
            <div class="lv-bar-label" style="color:#9E9E9E;">Silver</div>
            <div class="lv-bar-track"><div class="lv-bar-fill" style="width:70%;background:#9E9E9E;"></div></div>
            <div class="lv-bar-pts" style="color:var(--gm);">750 pts</div>
          </div>
          <div class="lv-bar-row">
            <div class="lv-bar-label" style="color:var(--y);">Gold</div>
            <div class="lv-bar-track"><div class="lv-bar-fill" style="width:100%;background:var(--y);"></div></div>
            <div class="lv-bar-pts" style="color:var(--y);">1500 pts</div>
          </div>
        </div>
      </div>

      <div class="pts-card">
        <div class="pts-card-title">Bonus Points Available</div>
        <div class="bonus-list">
          <div class="bonus-item">
            <span class="bonus-name">Clean / sorted waste</span>
            <span class="bonus-pts">+5 pts/kg</span>
          </div>
          <div class="bonus-item">
            <span class="bonus-name">Weekly regular user</span>
            <span class="bonus-pts">+10 pts/kg</span>
          </div>
          <div class="bonus-item">
            <span class="bonus-name">First pickup of week</span>
            <span class="bonus-pts">+20 pts flat</span>
          </div>
          <div class="bonus-item">
            <span class="bonus-name">Weekly challenge complete</span>
            <span class="bonus-pts">+50–100 pts</span>
          </div>
        </div>
      </div>

      <div style="background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:16px;padding:20px;text-align:center;">
        <div style="font-size:12px;color:rgba(255,255,255,.45);font-weight:600;margin-bottom:8px;">Average Monthly Points</div>
        <div style="font-family:'Sora',sans-serif;font-size:38px;font-weight:900;color:var(--y);letter-spacing:-1.5px;">1200+</div>
        <div style="font-size:12px;color:rgba(255,255,255,.4);margin-top:4px;">Built from regular organic and recyclable pickups</div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ REDEEM ══════════ -->
<section id="redeem">
  <div class="container">
    <div class="text-center fade-up">
      <div class="section-kicker">Rewards</div>
      <h2 class="heading section-title">Redeem for real things</h2>
      <p class="section-sub">Redeem points for useful rewards, green perks, and community impact - no currency conversion needed.</p>
    </div>

      <div class="cb-right">Redeem rewards with points</div>
    </div>

    <div class="redeem-grid">
      <div class="reward-card fade-up">
        <div class="reward-icon" style="background:#e8f5ea;"><svg viewBox="0 0 24 24" fill="none" stroke="var(--g)" stroke-width="2"><path d="M4 8h20l-2 12H6L4 8z"/><path d="M9 8V6a5 5 0 0110 0v2"/></svg></div>
        <div class="reward-name">Rice 5 kg</div>
        <div class="reward-pts">300 points</div>
      </div>
      <div class="reward-card fade-up" style="transition-delay:.08s;">
        <div class="reward-icon" style="background:#fffad6;"><svg viewBox="0 0 24 24" fill="none" stroke="var(--yd)" stroke-width="2"><rect x="2" y="6" width="24" height="16" rx="3"/><line x1="2" y1="12" x2="26" y2="12"/></svg></div>
        <div class="reward-name">Amazon Voucher</div>
        <div class="reward-pts">500 points</div>
      </div>
      <div class="reward-card fade-up" style="transition-delay:.16s;">
        <div class="reward-icon" style="background:#e8f5ea;"><svg viewBox="0 0 24 24" fill="none" stroke="var(--g)" stroke-width="2"><path d="M12 22C7 22 3 18 3 12S7 2 12 2s9 4 9 10-4 10-9 10z"/></svg></div>
        <div class="reward-name">Plant a Tree</div>
        <div class="reward-pts">50 points</div>
      </div>
      <div class="reward-card fade-up" style="transition-delay:.24s;">
        <div class="reward-icon" style="background:#e3f2fd;"><svg viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2"><path d="M14 2C8.5 2 4 6.5 4 12s4.5 10 10 10 10-4.5 10-10S19.5 2 14 2z"/></svg></div>
        <div class="reward-name">Bus Pass 1 Month</div>
        <div class="reward-pts">1500 points</div>
      </div>
      <div class="reward-card fade-up" style="transition-delay:.0s;">
        <div class="reward-icon" style="background:#fde8e8;"><svg viewBox="0 0 24 24" fill="none" stroke="#b71c1c" stroke-width="2"><rect x="4" y="6" width="20" height="16" rx="2"/><path d="M10 6V4a2 2 0 014 0v2"/></svg></div>
        <div class="reward-name">Grocery Voucher</div>
        <div class="reward-pts">250 points</div>
      </div>
      <div class="reward-card fade-up" style="transition-delay:.08s;">
        <div class="reward-icon" style="background:#f0e8ff;"><svg viewBox="0 0 24 24" fill="none" stroke="#7B1FA2" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/></svg></div>
        <div class="reward-name">Fuel Coupon</div>
        <div class="reward-pts">500 points</div>
      </div>
      <div class="reward-card fade-up" style="transition-delay:.16s;">
        <div class="reward-icon" style="background:#fffad6;"><svg viewBox="0 0 24 24" fill="none" stroke="var(--yd)" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
        <div class="reward-name">Green Streak Booster</div>
        <div class="reward-pts">1000 points</div>
      </div>
      <div class="reward-card fade-up" style="transition-delay:.24s;">
        <div class="reward-icon" style="background:#e8f5ea;"><svg viewBox="0 0 24 24" fill="none" stroke="var(--g)" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.8 19.79 19.79 0 01.01 1.18 2 2 0 012 .01h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 14.92z"/></svg></div>
        <div class="reward-name">Mobile Recharge Pack</div>
        <div class="reward-pts">250 points</div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ TRACKING & DDC ══════════ -->
<section id="track">
  <div class="container">
    <div class="track-inner">
      <div class="fade-up">
        <div class="section-kicker">Live Tracking</div>
        <h2 class="heading section-title">Know exactly when your collector arrives</h2>
        <p class="section-sub">Real-time GPS tracking and a full pickup timeline — from scheduled to recycled, every step is visible.</p>

        <div class="timeline">
          <div class="tl-item tl-done">
            <div class="tl-col">
              <div class="tl-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></div>
              <div class="tl-line"></div>
            </div>
            <div class="tl-body"><div class="tl-t">Pickup Scheduled</div><div class="tl-d">Confirmed for your selected time slot</div></div>
          </div>
          <div class="tl-item tl-active">
            <div class="tl-col">
              <div class="tl-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/></svg></div>
              <div class="tl-line"></div>
            </div>
            <div class="tl-body"><div class="tl-t">Collector En Route</div><div class="tl-d">Live GPS — DDC-7 is 12 minutes away</div></div>
          </div>
          <div class="tl-item tl-pending">
            <div class="tl-col">
              <div class="tl-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
              <div class="tl-line"></div>
            </div>
            <div class="tl-body"><div class="tl-t">Verified at DDC</div><div class="tl-d">Weight, category, QR scan, photo — all checked</div></div>
          </div>
          <div class="tl-item tl-pending">
            <div class="tl-col">
              <div class="tl-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
              <div class="tl-line"></div>
            </div>
            <div class="tl-body"><div class="tl-t">Points Credited</div><div class="tl-d">Base + bonus points added to your wallet</div></div>
          </div>
          <div class="tl-item tl-pending">
            <div class="tl-col">
              <div class="tl-dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/></svg></div>
            </div>
            <div class="tl-body" style="padding-bottom:0;"><div class="tl-t">Processed at Recycling Unit</div><div class="tl-d">Your waste's full journey: Home → DDC → Recycler</div></div>
          </div>
        </div>
      </div>

      <div class="fade-up" style="transition-delay:.15s;">
        <div class="ddc-visual">
          <div class="ddc-title">Producer → DDC Flow</div>
          <div class="ddc-flow">
            <div class="ddc-row">
              <div class="ddc-node">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                <span>Producer</span>
              </div>
              <div class="ddc-arrow">
                <div class="ddc-arrow-line"></div>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="rgba(255,255,255,.3)"><polygon points="5 12 19 12 13 6"/><polygon points="5 12 19 12 13 18"/></svg>
              </div>
              <div class="ddc-dest"><span>DDC</span></div>
            </div>
            <div class="ddc-row" style="padding-left:16px;">
              <div style="width:28px;height:28px;border-radius:50%;background:#fde8e8;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#b71c1c" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              </div>
              <div class="ddc-arrow"><div class="ddc-arrow-line"></div><div class="ddc-arrow-none">Inert — 0 pts</div></div>
            </div>
            <div class="ddc-row" style="padding-left:16px;">
              <div style="width:28px;height:28px;border-radius:50%;background:rgba(46,204,64,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--gm)" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 102.13-9.36L1 10"/></svg>
              </div>
              <div class="ddc-arrow"><div class="ddc-arrow-line"></div><div class="ddc-arrow-pts">Recyclable — 60 pts/kg</div></div>
            </div>
            <div class="ddc-row" style="padding-left:16px;">
              <div style="width:28px;height:28px;border-radius:50%;background:rgba(46,204,64,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--gm)" stroke-width="2"><path d="M12 2C6.5 2 2 7 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2z"/></svg>
              </div>
              <div class="ddc-arrow"><div class="ddc-arrow-line"></div><div class="ddc-arrow-pts">Organic — 30 pts/kg</div></div>
            </div>
          </div>
          <div class="ddc-foot">*DDC = Decentralised Disposal Center</div>
          <div class="ddc-badge">
            <div><div class="ddc-badge-main">Points verified at pickup</div><div class="ddc-badge-sub">Rewards are based on waste type only</div></div>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1a1800" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ IMPACT ══════════ -->
<section id="impact">
  <div class="container">
    <div class="text-center fade-up">
      <div class="section-kicker">Environmental Impact</div>
      <h2 class="heading section-title">Real change, measurable results</h2>
      <p class="section-sub">Every kg you segregate contributes to a real environmental outcome you can track on your dashboard.</p>
    </div>
    <div class="impact-grid">
      <div class="impact-card fade-up">
        <div class="impact-icon" style="background:#e8f5ea;"><svg viewBox="0 0 24 24" fill="none" stroke="var(--g)" stroke-width="2"><path d="M12 22C7 22 3 18 3 12S7 2 12 2s9 4 9 10-4 10-9 10z"/></svg></div>
        <div class="impact-val" style="color:var(--g);">48 kg</div>
        <div class="impact-label">Plastic Recycled</div>
        <div class="impact-sub">Per active user/month on average across the platform</div>
      </div>
      <div class="impact-card fade-up" style="transition-delay:.1s;">
        <div class="impact-icon" style="background:#e3f2fd;"><svg viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2"><path d="M12 2v6M8 4l4-2 4 2"/><circle cx="12" cy="14" r="6"/></svg></div>
        <div class="impact-val" style="color:#1565C0;">32 kg</div>
        <div class="impact-label">CO₂ Saved</div>
        <div class="impact-sub">Carbon emissions offset through responsible waste disposal</div>
      </div>
      <div class="impact-card fade-up" style="transition-delay:.2s;">
        <div class="impact-icon" style="background:#fffad6;"><svg viewBox="0 0 24 24" fill="none" stroke="var(--yd)" stroke-width="2"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/></svg></div>
        <div class="impact-val" style="color:var(--yd);">33</div>
        <div class="impact-label">Trees Planted Equivalent</div>
        <div class="impact-sub">Based on CO₂ absorption equivalent of healthy trees</div>
      </div>
      <div class="impact-card fade-up" style="transition-delay:.0s;">
        <div class="impact-icon" style="background:#e0f7fa;"><svg viewBox="0 0 24 24" fill="none" stroke="#00838f" stroke-width="2"><path d="M12 2v8M8 6l4-4 4 4"/><path d="M5 12a7 7 0 0014 0"/></svg></div>
        <div class="impact-val" style="color:#00838f;">120 L</div>
        <div class="impact-label">Water Saved</div>
        <div class="impact-sub">Through recycling processes that reuse water-intensive materials</div>
      </div>
      <div class="impact-card fade-up" style="transition-delay:.1s;">
        <div class="impact-icon" style="background:#fde8e8;"><svg viewBox="0 0 24 24" fill="none" stroke="#b71c1c" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg></div>
        <div class="impact-val" style="color:#b71c1c;">22 kg</div>
        <div class="impact-label">Landfill Diverted</div>
        <div class="impact-sub">Waste kept out of overflowing municipal landfill sites</div>
      </div>
      <div class="impact-card fade-up" style="transition-delay:.2s;">
        <div class="impact-icon" style="background:#e8f5ea;"><svg viewBox="0 0 24 24" fill="none" stroke="var(--g)" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
        <div class="impact-val" style="color:var(--g);">16</div>
        <div class="impact-label">Successful Pickups</div>
        <div class="impact-sub">Verified and completed waste collection events per active user</div>
      </div>
    </div>

    <div class="impact-cta fade-up">
      <h3>India generates 25,940 tonnes of municipal waste per day</h3>
      <p>TrashDeal is building the infrastructure to make every household part of the solution — one pickup at a time.</p>
        <a href="#app-connect" class="btn-hero-primary" style="display:inline-flex;">Start Making an Impact</a>
    </div>
  </div>
</section>

<!-- ══════════ PREMIUM ══════════ -->


<section id="app-connect">
  <div class="container">
    <div class="text-center fade-up">
      <div class="section-kicker">Connected Frontend</div>
      <h2 class="heading section-title">Use the real backend from this page</h2>
      <p class="section-sub">Register or log in, then load your profile, points, rewards, and pickups directly from the Laravel API without leaving the frontend.</p>
    </div>

    <div class="connect-grid">
      <div class="connect-card fade-up">
        <div class="connect-title">Account Access</div>
        <p class="connect-sub">This panel talks to your live API routes at <strong>/api/*</strong>. Your token is stored in this browser only so you can test the full user flow.</p>

        <div class="connect-tabs">
          <button type="button" class="connect-tab active" data-auth-tab="login">Login</button>
          <button type="button" class="connect-tab" data-auth-tab="register">Register</button>
          <button type="button" class="connect-tab" data-auth-tab="pickup">Schedule Pickup</button>
        </div>

        <form id="loginForm" class="connect-form">
          <input class="connect-input" name="login" placeholder="Phone or email" required>
          <input class="connect-input" name="password" type="password" placeholder="Password" required>
          <button type="submit" class="connect-btn">Login and Sync Dashboard</button>
        </form>

        <form id="registerForm" class="connect-form hidden">
          <div class="connect-row">
            <input class="connect-input" name="name" placeholder="Full name" required>
            <input class="connect-input" name="phone" placeholder="Phone number" required>
          </div>
          <div class="connect-row">
            <input class="connect-input" name="email" type="email" placeholder="Email (optional)">
            <input class="connect-input" name="password" type="password" placeholder="Password" required>
          </div>
          <button type="submit" class="connect-btn">Create Account</button>
        </form>

        <form id="pickupForm" class="connect-form hidden">
          <div class="connect-row">
            <input class="connect-input" name="address" placeholder="Pickup address" required>
            <select class="connect-select" name="waste_type" required>
              <option value="organic">Organic</option>
              <option value="recyclable">Recyclable</option>
              <option value="e-waste">E-waste</option>
              <option value="inert">Inert</option>
              <option value="mixed">Mixed</option>
            </select>
          </div>
          <div class="connect-row">
            <input class="connect-input" name="scheduled_at" type="datetime-local" required>
            <input class="connect-input" name="estimated_weight_kg" type="number" min="0.1" step="0.1" placeholder="Estimated kg">
          </div>
          <textarea class="connect-textarea" name="notes" placeholder="Pickup notes"></textarea>
          <button type="submit" class="connect-btn">Schedule Pickup</button>
        </form>

        <div id="connectStatus" class="connect-status hidden"></div>
      </div>

      <div class="connect-card connect-card-dark fade-up" style="transition-delay:.12s;">
        <div class="connect-title">Live Points Dashboard</div>
        <p class="connect-sub">Load your current wallet, reward list, and recent pickups from the same backend that powers Postman and mobile clients.</p>

        <div class="connect-metrics">
          <div class="metric-box">
            <div class="metric-label">Current Points</div>
            <div class="metric-value" id="metricPoints">--</div>
          </div>
          <div class="metric-box">
            <div class="metric-label">Rewards Ready</div>
            <div class="metric-value" id="metricRewards">--</div>
          </div>
        </div>

        <div class="connect-actions">
          <button type="button" class="connect-inline-btn" id="loadDashboardBtn">Refresh Data</button>
          <button type="button" class="connect-inline-btn" id="loadProfileBtn">Load Profile</button>
          <button type="button" class="connect-inline-btn" id="logoutBtn">Logout</button>
        </div>

        <div class="connect-list" id="profileList">
          <div class="connect-empty">Log in to see your profile, points wallet, rewards, and pickups here.</div>
        </div>
        <div class="connect-list" id="rewardList"></div>
        <div class="connect-list" id="pickupList"></div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ FAQ ══════════ -->
<section id="faq">
  <div class="container">
    <div class="faq-inner">
      <div class="fade-up">
        <div class="section-kicker">FAQ</div>
        <h2 class="heading section-title">Common questions</h2>
        <div class="faq-list">
          <div class="faq-item">
            <div class="faq-q" onclick="toggleFaq(this)">
              How do I earn points?
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
            <div class="faq-a">Schedule a pickup in the app, segregate your waste correctly, and our DDC collector will come to your door. After weight verification at the DDC, points are automatically credited to your wallet.</div>
          </div>
          <div class="faq-item">
            <div class="faq-q" onclick="toggleFaq(this)">
              What is the DDC?
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
            <div class="faq-a">DDC stands for Decentralised Disposal Center. These are local waste processing hubs where your collected waste is weighed, categorised, and verified before being sent to recycling units. Every DDC has digital weighing scales and QR tracking.</div>
          </div>
          <div class="faq-item">
            <div class="faq-q" onclick="toggleFaq(this)">
              How does the anti-cheat system work?
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
            <div class="faq-a">Every pickup goes through 3 verification steps: (1) Digital weighing scale records exact kg, (2) DDC staff scan the collector's QR code to confirm genuine pickup, (3) Timestamped photos taken at both pickup and DDC entry. Points are only credited after all checks pass.</div>
          </div>
          <div class="faq-item">
            <div class="faq-q" onclick="toggleFaq(this)">
              How do I redeem points?
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
            <div class="faq-a">Go to the Redeem screen in the app. Choose from groceries, Amazon vouchers, fuel coupons, bus passes, or eco donations. Delivery or code is sent within 24–48 hours after redemption.</div>
          </div>
          <div class="faq-item">
            <div class="faq-q" onclick="toggleFaq(this)">
              What waste types are accepted?
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
            <div class="faq-a">Organic waste (kitchen, food, garden) earns 30 pts/kg. Recyclables (plastic, paper, metal, glass, e-waste) earn 60 pts/kg. Inert waste (construction debris, soil) earns 0 pts but is still collected responsibly.</div>
          </div>
          <div class="faq-item">
            <div class="faq-q" onclick="toggleFaq(this)">
              Is TrashDeal aligned with Swachh Bharat Mission?
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
            <div class="faq-a">Yes. TrashDeal is built in alignment with Swachh Bharat Mission 2.0 principles — promoting segregation at source, reducing landfill burden, and incentivising citizen participation through the points reward system.</div>
          </div>
        </div>
      </div>

      <div class="faq-visual fade-up" style="transition-delay:.15s;">
        <div class="faq-stat">
          <div class="faq-stat-val">1200+</div>
          <div class="faq-stat-lbl">Average household reward potential per month with regular sorting</div>
        </div>
        <div class="faq-stat">
          <div class="faq-stat-val">25,940T</div>
          <div class="faq-stat-lbl">Tonnes of municipal solid waste India generates daily</div>
        </div>
        <div class="faq-stat" style="margin-bottom:0;">
          <div class="faq-stat-val">60%</div>
          <div class="faq-stat-lbl">Of mixed waste points are missed when waste is not sorted at source</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ CTA ══════════ -->
<section id="cta">
  <div class="cta-inner">
    <div class="section-kicker" style="background:rgba(46,204,64,.2);color:var(--gm);">Get Started Today</div>
    <h2 class="display cta-title">Start turning <span>waste into points</span></h2>
    <p class="cta-sub">Join 50,000+ households already earning rewards for responsible waste disposal. Free to download, free to use.</p>
    <div class="cta-btns">
      <a href="#" class="cta-store">
        <svg viewBox="0 0 24 24" fill="white"><path d="M3 18.5v-13A1.5 1.5 0 014.91 4.1l13 6.5a1.5 1.5 0 010 2.8l-13 6.5A1.5 1.5 0 013 18.5z"/></svg>
        <div><div class="cta-store-sub">Download on the</div><div class="cta-store-name">App Store</div></div>
      </a>
      <a href="#" class="cta-store">
        <svg viewBox="0 0 24 24" fill="white"><path d="M3.18 23.76a2 2 0 002.76.74l10.09-5.83-2.89-2.89-9.96 7.98zm15.28-13.16L5.83 4.5A2 2 0 003 5.24v13.52a2 2 0 002.83.74l12.63-7.28a2 2 0 000-3.62zM3.18.24A2 2 0 003 1.26v21.48a2 2 0 00.18 1.02L14.06 12 3.18.24zm17.64 10.02l-2.63-1.52-3.17 3.17 3.17 3.17 2.66-1.54a2 2 0 000-3.28z"/></svg>
        <div><div class="cta-store-sub">Get it on</div><div class="cta-store-name">Google Play</div></div>
      </a>
    </div>
  </div>
</section>

<!-- ══════════ FOOTER ══════════ -->
<footer>
  <div class="footer-inner">
    <div class="footer-top">
      <div class="footer-brand">
        <a href="#" class="nav-logo" style="display:inline-flex;margin-bottom:14px;text-decoration:none;">
          <div class="nav-logo-icon"><svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M9 6V4h6v2"/></svg></div>
          <span class="nav-logo-name" style="color:white;">TrashDeal</span>
        </a>
        <div class="footer-tagline">Turn your waste into meaningful points. Aligned with Swachh Bharat Mission. Building a cleaner India - one pickup at a time.</div>
      </div>
      <div class="footer-col">
        <h4>Product</h4>
        <a href="#how">How It Works</a>
        <a href="#features">Features</a>
        <a href="#points">Points System</a>
        <a href="#redeem">Rewards</a>
        
      </div>
      <div class="footer-col">
        <h4>Company</h4>
        <a href="#">About Us</a>
        <a href="#">Blog</a>
        <a href="#">Careers</a>
        <a href="#">Press</a>
        <a href="#">Partners</a>
      </div>
      <div class="footer-col">
        <h4>Support</h4>
        <a href="#">Help Centre</a>
        <a href="#">Contact Us</a>
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Service</a>
        <a href="#">Swachh Bharat</a>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="footer-copy">© 2025 TrashDeal. All rights reserved. Made with care for India.</div>
      <div class="footer-pts-badge">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="var(--gm)"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        Earn points by doing good
      </div>
    </div>
  </div>
</footer>

<script>
// Mobile menu
function toggleMenu() {
  document.getElementById('mobMenu').classList.toggle('open');
}

// FAQ accordion
function toggleFaq(el) {
  var item = el.parentElement;
  var wasOpen = item.classList.contains('open');
  document.querySelectorAll('.faq-item').forEach(function(i){ i.classList.remove('open'); });
  if (!wasOpen) item.classList.add('open');
}

// Scroll animations
var observer = new IntersectionObserver(function(entries) {
  entries.forEach(function(e) {
    if (e.isIntersecting) {
      e.target.classList.add('visible');
      observer.unobserve(e.target);
    }
  });
}, { threshold: 0.12 });
document.querySelectorAll('.fade-up').forEach(function(el){ observer.observe(el); });

// Navbar scroll effect
window.addEventListener('scroll', function() {
  var nav = document.getElementById('navbar');
  if (window.scrollY > 60) {
    nav.style.boxShadow = '0 4px 24px rgba(0,0,0,.08)';
  } else {
    nav.style.boxShadow = 'none';
  }
});

// Plan card selection
document.querySelectorAll('.plan-box').forEach(function(p) {
  p.addEventListener('click', function() {
    document.querySelectorAll('.plan-box').forEach(function(x){ x.classList.remove('pop'); });
    p.classList.add('pop');
  });
});

// Close mobile menu on outside click
document.addEventListener('click', function(e) {
  var menu = document.getElementById('mobMenu');
  var ham = document.getElementById('ham');
  if (!menu.contains(e.target) && !ham.contains(e.target)) {
    menu.classList.remove('open');
  }
});

// Connected frontend demo
var authTokenKey = 'trashdeal_frontend_token';
var authUserKey = 'trashdeal_frontend_user';
var apiBase = window.location.origin + '/api';

function getStoredToken() {
  return localStorage.getItem(authTokenKey);
}

function setStoredAuth(token, user) {
  localStorage.setItem(authTokenKey, token);
  if (user) {
    localStorage.setItem(authUserKey, JSON.stringify(user));
  }
}

function clearStoredAuth() {
  localStorage.removeItem(authTokenKey);
  localStorage.removeItem(authUserKey);
}

function showConnectStatus(message, isError) {
  var status = document.getElementById('connectStatus');
  status.textContent = message;
  status.classList.remove('hidden', 'error');
  if (isError) {
    status.classList.add('error');
  }
}

function hideConnectStatus() {
  document.getElementById('connectStatus').classList.add('hidden');
}

async function apiRequest(path, options) {
  var token = getStoredToken();
  var requestOptions = options || {};
  var headers = Object.assign({
    'Accept': 'application/json'
  }, requestOptions.headers || {});

  if (token) {
    headers.Authorization = 'Bearer ' + token;
  }

  if (requestOptions.body && !(requestOptions.body instanceof FormData)) {
    headers['Content-Type'] = 'application/json';
    requestOptions.body = JSON.stringify(requestOptions.body);
  }

  requestOptions.headers = headers;

  var response = await fetch(apiBase + path, requestOptions);
  var data = {};

  try {
    data = await response.json();
  } catch (error) {
    data = { success: false, message: 'Unable to parse server response.' };
  }

  if (!response.ok) {
    var errorMessage = data.message || 'Request failed.';
    if (data.errors) {
      var firstGroup = Object.values(data.errors)[0];
      if (firstGroup && firstGroup[0]) {
        errorMessage = firstGroup[0];
      }
    }
    throw new Error(errorMessage);
  }

  return data;
}

function activateAuthTab(tabName) {
  document.querySelectorAll('[data-auth-tab]').forEach(function(button) {
    button.classList.toggle('active', button.getAttribute('data-auth-tab') === tabName);
  });

  document.getElementById('loginForm').classList.toggle('hidden', tabName !== 'login');
  document.getElementById('registerForm').classList.toggle('hidden', tabName !== 'register');
  document.getElementById('pickupForm').classList.toggle('hidden', tabName !== 'pickup');
}

function renderProfile(profile, points) {
  var list = document.getElementById('profileList');
  if (!profile) {
    list.innerHTML = '<div class="connect-empty">Log in to see your profile, points wallet, rewards, and pickups here.</div>';
    document.getElementById('metricPoints').textContent = '--';
    return;
  }

  document.getElementById('metricPoints').textContent = points;
  list.innerHTML = [
    '<div class="connect-item">',
      '<div class="connect-item-top">',
        '<div>',
          '<div class="connect-item-title">' + profile.name + '</div>',
          '<div class="connect-item-meta">' + (profile.email || profile.phone || 'TrashDeal user') + '</div>',
        '</div>',
        '<span class="connect-pill">' + profile.role + '</span>',
      '</div>',
      '<div class="connect-item-meta">Total Points: ' + points + '</div>',
      '<div class="connect-item-meta">Address: ' + (profile.address || 'Not added yet') + '</div>',
    '</div>'
  ].join('');
}

function renderRewards(rewards) {
  var list = document.getElementById('rewardList');
  document.getElementById('metricRewards').textContent = rewards.length;

  if (!rewards.length) {
    list.innerHTML = '<div class="connect-empty">No rewards available right now.</div>';
    return;
  }

  list.innerHTML = rewards.slice(0, 4).map(function(reward) {
    return [
      '<div class="connect-item">',
        '<div class="connect-item-top">',
          '<div>',
            '<div class="connect-item-title">' + reward.name + '</div>',
            '<div class="connect-item-meta">Reward ID: ' + reward.id + '</div>',
          '</div>',
          '<span class="connect-pill">' + reward.points_required + ' pts</span>',
        '</div>',
      '</div>'
    ].join('');
  }).join('');
}

function renderPickups(pickups) {
  var list = document.getElementById('pickupList');

  if (!pickups.length) {
    list.innerHTML = '<div class="connect-empty">No pickups yet. Schedule one from this page to test the backend.</div>';
    return;
  }

  list.innerHTML = pickups.slice(0, 4).map(function(pickup) {
    return [
      '<div class="connect-item">',
        '<div class="connect-item-top">',
          '<div>',
            '<div class="connect-item-title">' + pickup.waste_type + ' pickup</div>',
            '<div class="connect-item-meta">' + pickup.address + '</div>',
          '</div>',
          '<span class="connect-pill">' + pickup.status + '</span>',
        '</div>',
        '<div class="connect-item-meta">Scheduled: ' + (pickup.scheduled_at || 'Pending time') + '</div>',
      '</div>'
    ].join('');
  }).join('');
}

async function refreshDashboard() {
  var token = getStoredToken();
  if (!token) {
    renderProfile(null, '--');
    renderRewards([]);
    renderPickups([]);
    showConnectStatus('Login or register first to sync live backend data.', false);
    return;
  }

  try {
    var results = await Promise.all([
      apiRequest('/profile'),
      apiRequest('/points'),
      apiRequest('/rewards'),
      apiRequest('/pickups')
    ]);

    renderProfile(results[0].user, results[1].points);
    renderRewards(results[2].rewards || []);
    renderPickups((results[3].pickups && results[3].pickups.data) || []);
    hideConnectStatus();
  } catch (error) {
    if (error.message.toLowerCase().indexOf('unauth') !== -1) {
      clearStoredAuth();
    }
    showConnectStatus(error.message, true);
  }
}

document.querySelectorAll('[data-auth-tab]').forEach(function(button) {
  button.addEventListener('click', function() {
    activateAuthTab(button.getAttribute('data-auth-tab'));
    hideConnectStatus();
  });
});

document.getElementById('loginForm').addEventListener('submit', async function(event) {
  event.preventDefault();
  var formData = new FormData(event.target);

  try {
    var data = await apiRequest('/login', {
      method: 'POST',
      body: {
        login: formData.get('login'),
        password: formData.get('password')
      }
    });

    setStoredAuth(data.token, data.user);
    showConnectStatus('Logged in successfully. Loading your live dashboard now.', false);
    await refreshDashboard();
    activateAuthTab('pickup');
  } catch (error) {
    showConnectStatus(error.message, true);
  }
});

document.getElementById('registerForm').addEventListener('submit', async function(event) {
  event.preventDefault();
  var formData = new FormData(event.target);

  try {
    var data = await apiRequest('/register', {
      method: 'POST',
      body: {
        name: formData.get('name'),
        phone: formData.get('phone'),
        email: formData.get('email') || null,
        password: formData.get('password')
      }
    });

    setStoredAuth(data.token, data.user);
    showConnectStatus('Account created and connected to the backend.', false);
    await refreshDashboard();
    activateAuthTab('pickup');
  } catch (error) {
    showConnectStatus(error.message, true);
  }
});

document.getElementById('pickupForm').addEventListener('submit', async function(event) {
  event.preventDefault();
  var formData = new FormData(event.target);

  try {
    await apiRequest('/pickups', {
      method: 'POST',
      body: {
        address: formData.get('address'),
        waste_type: formData.get('waste_type'),
        scheduled_at: formData.get('scheduled_at'),
        estimated_weight_kg: formData.get('estimated_weight_kg') || null,
        notes: formData.get('notes') || null
      }
    });

    showConnectStatus('Pickup scheduled successfully. The dashboard is refreshing.', false);
    event.target.reset();
    await refreshDashboard();
  } catch (error) {
    showConnectStatus(error.message, true);
  }
});

document.getElementById('loadDashboardBtn').addEventListener('click', function() {
  refreshDashboard();
});

document.getElementById('loadProfileBtn').addEventListener('click', async function() {
  try {
    var profile = await apiRequest('/profile');
    var points = await apiRequest('/points');
    renderProfile(profile.user, points.points);
    showConnectStatus('Profile and points loaded from the backend.', false);
  } catch (error) {
    showConnectStatus(error.message, true);
  }
});

document.getElementById('logoutBtn').addEventListener('click', async function() {
  try {
    if (getStoredToken()) {
      await apiRequest('/logout', { method: 'POST' });
    }
  } catch (error) {
    // Clear local auth even if the token was already invalid.
  }

  clearStoredAuth();
  renderProfile(null, '--');
  renderRewards([]);
  renderPickups([]);
  showConnectStatus('Logged out from the live demo.', false);
  activateAuthTab('login');
});

refreshDashboard();
</script>
</body>
</html>





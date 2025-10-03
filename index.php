<?php
// index.php - Modern landing + quick login/register CTA
session_start();
// if user already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>LightSmartPay — Fast, Secure Payments</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root{
      --primary:#0066ff;
      --accent:#00b894;
      --muted:#6b7280;
      --bg:#f4f6fb;
    }
    body{font-family:Inter,ui-sans-serif,system-ui,Segoe UI,Roboto,Helvetica,Arial; background:var(--bg); color:#0f172a;}
    .hero{min-height:78vh; display:flex; align-items:center; padding:60px 20px;}
    .brand-logo{height:60px;}
    .cta-btn{padding:12px 22px; font-weight:700; border-radius:10px;}
    .feature {border-radius:12px; background:#fff; padding:18px; box-shadow:0 6px 18px rgba(15,23,42,0.06);}
    .footer {padding:30px 0; color:var(--muted); font-size:0.95rem;}
    .badge-commission{background:#fff5f5;color:#ef4444;padding:6px 10px;border-radius:999px;border:1px solid rgba(239,68,68,0.08); font-weight:700;}
    .nav-link {color:#0f172a; font-weight:600;}
    @media(min-width:980px){ .hero-left{padding-right:40px;} }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="assets/logo.png" alt="LightSmartPay" class="brand-logo me-2" onerror="this.onerror=null;this.src='https://via.placeholder.com/140x60?text=Logo'">
      <span style="font-weight:800; color:var(--primary)">LightSmartPay</span>
    </a>
    <div class="d-flex align-items-center gap-2">
      <a href="login.php" class="btn btn-outline-primary">Login</a>
      <a href="register.php" class="btn btn-primary">Get Started</a>
    </div>
  </div>
</nav>

<section class="hero">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6 hero-left">
        <h1 style="font-size:2.6rem; font-weight:800; line-height:1.05">Fast, secure airtime & data top-ups — <span style="color:var(--accent)">on your terms</span></h1>
        <p style="color:var(--muted); font-size:1.05rem; margin-top:18px">
          Buy airtime, data (SME / Corporate / Gifting / Awoof), cable subscriptions and fund wallets instantly.
          Low fees, instant delivery and built-in wallet to manage transactions.
        </p>

        <div class="d-flex gap-2 mt-4">
          <a href="register.php" class="btn btn-primary cta-btn">Create Account — It’s Free</a>
          <a href="buydata.php" class="btn btn-outline-dark cta-btn">Buy Data Now</a>
        </div>

        <div class="mt-4 d-flex gap-3 align-items-center">
          <span class="badge-commission">₦50 Funding Commission</span>
          <small class="text-muted">Commission applied when users fund wallet; set in platform settings.</small>
        </div>

        <div class="row gx-3 mt-5">
          <div class="col-6 mb-3">
            <div class="feature">
              <h5 style="margin:0; font-weight:700">Instant Delivery</h5>
              <p style="margin:0; color:var(--muted)">Fast MTN, Glo, Airtel & 9mobile delivery.</p>
            </div>
          </div>
          <div class="col-6 mb-3">
            <div class="feature">
              <h5 style="margin:0; font-weight:700">Secure Wallet</h5>
              <p style="margin:0; color:var(--muted)">Top up via Monnify — funds credited instantly.</p>
            </div>
          </div>
          <div class="col-6 mb-3">
            <div class="feature">
              <h5 style="margin:0; font-weight:700">Multiple Data Types</h5>
              <p style="margin:0; color:var(--muted)">SME, Corporate, Gifting, Awoof — all in one place.</p>
            </div>
          </div>
          <div class="col-6 mb-3">
            <div class="feature">
              <h5 style="margin:0; font-weight:700">Admin Control</h5>
              <p style="margin:0; color:var(--muted)">Manage users, transactions and settings from the admin panel.</p>
            </div>
          </div>
        </div>

      </div>

      <div class="col-lg-6 text-center">
        <!-- sample hero image / screenshot -->
        <img src="assets/hero-screenshot.png" alt="app-preview" class="img-fluid" style="max-width:540px;border-radius:18px; box-shadow:0 20px 50px rgba(2,6,23,0.08)" onerror="this.onerror=null;this.src='https://via.placeholder.com/540x350?text=Preview'">
      </div>
    </div>
  </div>
</section>

<section class="container mt-5">
  <div class="row">
    <div class="col-md-6">
      <div class="p-4 bg-white shadow-sm" style="border-radius:12px">
        <h4>How it works</h4>
        <ol style="color:var(--muted)">
          <li>Sign up and fund your wallet.</li>
          <li>Choose service (Airtime, Data, Cable) and confirm with PIN.</li>
          <li>Service delivered instantly and transaction recorded.</li>
        </ol>
        <a href="register.php" class="btn btn-primary">Start now</a>
      </div>
    </div>
    <div class="col-md-6">
      <div class="p-4 bg-white shadow-sm" style="border-radius:12px">
        <h4>Why choose LightSmartPay</h4>
        <ul style="color:var(--muted)">
          <li>Competitive prices and fast support.</li>
          <li>Multi-tier data plans (SME/Corporate/Gifting/Awoof).</li>
          <li>Admin dashboard to manage users & transactions.</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<footer class="footer mt-5">
  <div class="container d-flex justify-content-between align-items-center">
    <div>© <?php echo date('Y'); ?> LightSmartPay — Evylite Global Ventures</div>
    <div>Support: <a href="mailto:evyliteglobalsupport@gmail.com">evyliteglobalsupport@gmail.com</a></div>
  </div>
</footer>

</body>
</html>

<?php
$page_title = 'Sign In | VPMS';
$body_class = '';
include 'includes/auth-header.php';
?>

<div class="auth-split">

  <div class="auth-left">
    <a href="index.php" style="color:rgba(255,255,255,.85);font-size:14px">
      <i class="bi bi-arrow-left"></i> Back to Home
    </a>

    <div style="margin:auto 0">
      <p class="auth-kicker">SDG 17 · PARTNERSHIPS FOR THE GOALS</p>
      <h1 class="auth-head">
        Connecting people,<br>
        <span class="gold">amplifying impact</span>
      </h1>

      <div class="auth-stat">
        <span class="a-val">4,820</span>
        <span class="a-lab">Registered volunteers</span>
      </div>
      <div class="auth-stat">
        <span class="a-val">142</span>
        <span class="a-lab">Partner organisations</span>
      </div>
      <div class="auth-stat">
        <span class="a-val">38,640</span>
        <span class="a-lab">Verified volunteer hours</span>
      </div>
    </div>

    <p class="auth-foot">VPMS · Volunteer Partnership Management System</p>
  </div>

  <div class="auth-right">
    <div class="auth-form">
      <h2 class="auth-title">Welcome back</h2>
      <p class="auth-sub">Sign in to your VPMS account</p>

      <form action="dashboard.php" method="get">

        <div class="field">
          <label class="field-label" for="email">Email address</label>
          <input class="input-v" type="email" id="email" name="email" placeholder="you@organisation.org">
        </div>

        <div class="field mb-4">
          <div class="d-flex align-items-center">
            <label class="field-label mb-0" for="password">Password</label>
            <a class="link-green ms-auto" style="font-size:13px;font-weight:400" href="forgot-password.php">Forgot password?</a>
          </div>
          <input class="input-v mt-2" type="password" id="password" name="password" placeholder="••••••••">
        </div>

        <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Sign In</button>
      </form>

      <p class="text-center mt-4" style="font-size:13.5px;color:#6d7880">
        New to VPMS? <a class="link-green" href="register.php">Create an account</a>
      </p>
    </div>
  </div>

</div>

<?php include 'includes/auth-footer.php'; ?>

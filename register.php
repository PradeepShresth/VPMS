<?php
$page_title = 'Create Account | VPMS';
$body_class = 'auth-center';
include 'includes/auth-header.php';
?>

<div class="auth-card">

  <div class="auth-topbar">
    <a class="link-green" href="index.php"><i class="bi bi-arrow-left"></i> Back</a>
    <a class="link-green ms-auto" href="register-org.php">Register an Organization</a>
  </div>

  <h1 class="auth-title">Create your account</h1>
  <p class="auth-sub">Step 1 of 2 — Choose your role</p>

  <div class="steps">
    <span class="done"></span>
    <span></span>
  </div>

  <p class="section-label">I am joining as a...</p>

  <form action="register-step2.php" method="get">

    <label class="choice">
      <input type="radio" name="role" value="Volunteer" checked>
      <span class="choice-box d-block">
        <span class="choice-title">Volunteer</span>
        <span class="choice-note">Browse and apply for community opportunities</span>
      </span>
    </label>

    <label class="choice">
      <input type="radio" name="role" value="NGO Coordinator">
      <span class="choice-box d-block">
        <span class="choice-title">NGO Coordinator</span>
        <span class="choice-note">Manage projects and recruit volunteers</span>
      </span>
    </label>

    <label class="choice">
      <input type="radio" name="role" value="Corporate CSR Manager">
      <span class="choice-box d-block">
        <span class="choice-title">Corporate CSR Manager</span>
        <span class="choice-note">Lead employee volunteering programs</span>
      </span>
    </label>

    <label class="choice">
      <input type="radio" name="role" value="Community Field Officer">
      <span class="choice-box d-block">
        <span class="choice-title">Community Field Officer</span>
        <span class="choice-note">Validate attendance and verify hours</span>
      </span>
    </label>

    <label class="choice">
      <input type="radio" name="role" value="Sponsor / Donor">
      <span class="choice-box d-block">
        <span class="choice-title">Sponsor / Donor</span>
        <span class="choice-note">Track funded projects and outcomes</span>
      </span>
    </label>

    <button class="btn-v btn-green btn-block btn-lg-v mt-3" type="submit">Continue &rarr;</button>
  </form>

  <p class="text-center mt-4" style="font-size:13.5px;color:#6d7880">
    Already have an account? <a class="link-green" href="login.php">Sign in</a>
  </p>

</div>

<?php include 'includes/auth-footer.php'; ?>

<?php
$page_title = 'Register your Organisation | VPMS';
$body_class = 'auth-center';
include 'includes/auth-header.php';
?>

<div class="auth-card">

  <div class="auth-topbar">
    <a class="link-green" href="index.php"><i class="bi bi-arrow-left"></i> Back</a>
    <a class="link-green ms-auto" href="register.php">Register as an Individual</a>
  </div>

  <h1 class="auth-title">Register your Organisation</h1>
  <p class="auth-sub">Step 1 of 2 — Organisation details</p>

  <div class="steps">
    <span class="done"></span>
    <span></span>
  </div>

  <form action="register-org-step2.php" method="get">

    <div class="field">
      <label class="field-label" for="name">Organisation Name</label>
      <input class="input-v" type="text" id="name" name="name" placeholder="e.g. Green Future NGO">
    </div>

    <div class="field">
      <label class="field-label" for="type">Organisation Type</label>
      <select class="select-v" id="type" name="type">
        <option>NGO</option>
        <option>Corporate</option>
        <option>Community Group</option>
        <option>Government Body</option>
        <option>Sponsor / Donor</option>
        <option>Educational Institution</option>
      </select>
    </div>

    <div class="field">
      <label class="field-label" for="reg">Registration Number</label>
      <input class="input-v" type="text" id="reg" name="reg" placeholder="e.g. ROS-1234/2026">
    </div>

    <div class="field">
      <label class="field-label" for="country">Country</label>
      <input class="input-v" type="text" id="country" name="country" placeholder="e.g. Malaysia">
    </div>

    <div class="field">
      <label class="field-label" for="state">State/Region</label>
      <input class="input-v" type="text" id="state" name="state" placeholder="e.g. Selangor">
    </div>

    <div class="field">
      <label class="field-label" for="city">City</label>
      <input class="input-v" type="text" id="city" name="city" placeholder="e.g. Petaling Jaya">
    </div>

    <div class="field">
      <label class="field-label" for="address">Address</label>
      <input class="input-v" type="text" id="address" name="address" placeholder="e.g. 12, Jalan Gasing">
    </div>

    <div class="field mb-4">
      <label class="field-label" for="website">Website URL</label>
      <input class="input-v" type="url" id="website" name="website" placeholder="e.g. https://greenfuture.org">
    </div>

    <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Continue &rarr;</button>
  </form>

  <p class="text-center mt-4" style="font-size:13.5px;color:#6d7880">
    Already have an account? <a class="link-green" href="login.php">Sign in</a>
  </p>

</div>

<?php include 'includes/auth-footer.php'; ?>

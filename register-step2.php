<?php
$page_title = 'Create Account | VPMS';
$body_class = 'auth-center';
include 'includes/auth-header.php';
?>

<div class="auth-card">

  <div class="auth-topbar">
    <a class="link-green" href="register.php"><i class="bi bi-arrow-left"></i> Back</a>
    <a class="link-green ms-auto" href="register-org.php">Register an Organization</a>
  </div>

  <h1 class="auth-title">Create your account</h1>
  <p class="auth-sub">Step 2 of 2 — Your details</p>

  <div class="steps">
    <span class="done"></span>
    <span class="done"></span>
  </div>

  <form action="register-success.php" method="get">

    <div class="field">
      <label class="field-label" for="name">Full Name</label>
      <input class="input-v" type="text" id="name" name="name" placeholder="Amara Osei">
    </div>

    <div class="field">
      <label class="field-label" for="email">Email Address</label>
      <input class="input-v" type="email" id="email" name="email" placeholder="you@organisation.org">
    </div>

    <div class="field">
      <label class="field-label" for="org">Organisation / Affiliation</label>
      <input class="input-v" type="text" id="org" name="org" placeholder="Green Future NGO">
    </div>

    <div class="field">
      <label class="field-label" for="phone">Phone Number</label>
      <input class="input-v" type="tel" id="phone" name="phone" placeholder="+60 12-345 6789">
    </div>

    <div class="field">
      <label class="field-label" for="password">Password</label>
      <input class="input-v" type="password" id="password" name="password" placeholder="••••••••">
    </div>

    <div class="field mb-4">
      <label class="field-label" for="confirm">Confirm Password</label>
      <input class="input-v" type="password" id="confirm" name="confirm" placeholder="••••••••">
    </div>

    <div class="row g-3">
      <div class="col-5">
        <a class="btn-v btn-outline btn-block btn-lg-v" href="register.php">&larr; Back</a>
      </div>
      <div class="col-7">
        <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Submit Registration</button>
      </div>
    </div>
  </form>

  <p class="text-center mt-4" style="font-size:13.5px;color:#6d7880">
    Already have an account? <a class="link-green" href="login.php">Sign in</a>
  </p>

</div>

<?php include 'includes/auth-footer.php'; ?>

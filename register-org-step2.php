<?php
$page_title = 'Register your Organisation | VPMS';
$body_class = 'auth-center';
include 'includes/auth-header.php';
?>

<div class="auth-card">

  <div class="auth-topbar">
    <a class="link-green" href="register-org.php"><i class="bi bi-arrow-left"></i> Back</a>
    <a class="link-green ms-auto" href="register.php">Register as an Individual</a>
  </div>

  <h1 class="auth-title">Register your Organisation</h1>
  <p class="auth-sub">Step 2 of 2 — Contact person</p>

  <div class="steps">
    <span class="done"></span>
    <span class="done"></span>
  </div>

  <form action="register-success.php" method="get">

    <div class="field">
      <label class="field-label" for="contact">Contact Person Name</label>
      <input class="input-v" type="text" id="contact" name="contact" placeholder="e.g. Amara Osei">
    </div>

    <div class="field">
      <label class="field-label" for="role">Designation/Role</label>
      <input class="input-v" type="text" id="role" name="role" placeholder="e.g. NGO Coordinator">
    </div>

    <div class="field">
      <label class="field-label" for="email">Email Address</label>
      <input class="input-v" type="email" id="email" name="email" placeholder="you@organisation.org">
    </div>

    <div class="field">
      <label class="field-label" for="phone">Phone Number</label>
      <input class="input-v" type="tel" id="phone" name="phone" placeholder="e.g. +60 12-345 6789">
    </div>

    <div class="field">
      <label class="field-label" for="about">Organisation Description</label>
      <textarea class="textarea-v" id="about" name="about"
                placeholder="Briefly describe your focus areas and mission..."></textarea>
    </div>

    <div class="field mb-4">
      <span class="field-label">Upload Supporting Documents</span>
      <label class="dropzone d-block">
        <input type="file" name="documents[]" multiple hidden>
        <i class="bi bi-upload" style="font-size:18px"></i>
        <span class="dz-title">Click to upload or drag &amp; drop</span>
        <span class="dz-note">PDF, DOCX, or JPEG up to 10MB</span>
      </label>
    </div>

    <div class="row g-3">
      <div class="col-5">
        <a class="btn-v btn-outline btn-block btn-lg-v" href="register-org.php">&larr; Back</a>
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

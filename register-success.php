<?php
$page_title = 'Registration Submitted | VPMS';
$body_class = 'auth-center';
include 'includes/auth-header.php';
?>

<div class="auth-card">
  <div class="success-wrap" style="padding-top:170px">
    <span class="success-icon"><i class="bi bi-check-lg"></i></span>
    <h1 class="success-title">Registration submitted</h1>
    <p class="success-text">
      Your account request is under review. You will receive a confirmation email within
      1–2 business days once approved by the System Administrator.
    </p>
    <a class="btn-v btn-green" href="login.php">Go to Sign In</a>
  </div>
</div>

<?php include 'includes/auth-footer.php'; ?>

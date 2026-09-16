<?php
$page_title = 'Password Reset | VPMS';
$body_class = 'auth-center';
include 'includes/auth-header.php';
?>

<div class="auth-card">
  <div class="success-wrap" style="padding-top:110px">
    <span class="success-icon"><i class="bi bi-check-lg"></i></span>
    <h1 class="success-title">Password reset</h1>
    <p class="success-text">
      Your password has been changed. For security, you have been signed out of every other
      device where you were logged in.
    </p>
    <a class="btn-v btn-green" href="login.php">Go to Sign In</a>
  </div>
</div>

<?php include 'includes/auth-footer.php'; ?>

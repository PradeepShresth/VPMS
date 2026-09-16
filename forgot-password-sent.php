<?php
$page_title = 'Check Your Email | VPMS';
$body_class = 'auth-center';

$email = isset($_GET['email']) && $_GET['email'] != '' ? $_GET['email'] : 'your email address';

include 'includes/auth-header.php';
?>

<div class="auth-card">
  <div class="success-wrap" style="padding-top:60px">
    <span class="success-icon"><i class="bi bi-envelope-check"></i></span>
    <h1 class="success-title">Check your email</h1>
    <p class="success-text">
      If an account exists for <strong style="color:#16303c"><?php echo htmlspecialchars($email); ?></strong>,
      a password reset link is on its way. The link expires in 60 minutes.
    </p>

    <a class="btn-v btn-green" href="reset-password.php">Open Reset Link</a>

    <p class="mt-4" style="font-size:13px;color:#98a2aa">
      Prototype note: the button above stands in for the emailed link.
    </p>

    <div class="error-links" style="max-width:420px;margin:34px auto 0">
      Didn't get it? Check your spam folder, or
      <a class="link-green" href="forgot-password.php">try a different address</a>.
    </div>
  </div>
</div>

<?php include 'includes/auth-footer.php'; ?>

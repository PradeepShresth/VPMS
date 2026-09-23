<?php
$page_title = 'Check Your Email | VPMS';
$body_class = 'auth-center';

$email = isset($_GET['email']) && $_GET['email'] != '' ? $_GET['email'] : 'your email address';

$code = isset($_GET['code']) ? $_GET['code'] : '';

include 'includes/auth-header.php';
?>

<div class="auth-card">
  <div class="success-wrap" style="padding-top:60px">
    <span class="success-icon"><i class="bi bi-envelope-check"></i></span>
    <h1 class="success-title">Check your email</h1>
    <p class="success-text">
      If an account exists for <strong style="color:#16303c"><?php echo htmlspecialchars($email); ?></strong>,
      a password reset link is on its way.
    </p>

    <?php if ($code != '') { ?>
      <a class="btn-v btn-green" href="reset-password.php?code=<?php echo htmlspecialchars($code); ?>">
        Open Reset Link
      </a>

      <p class="mt-4" style="font-size:13px;color:#98a2aa">
        Prototype note: the button above stands in for the emailed link, because XAMPP does not
        send email. The code behind it is the real one stored against the account.
      </p>
    <?php } else { ?>
      <p class="mt-4" style="font-size:13px;color:#98a2aa">
        Prototype note: no email is actually sent. If that address has an account, the reset link
        appears on this page instead.
      </p>
    <?php } ?>

    <div class="error-links" style="max-width:420px;margin:34px auto 0">
      Didn't get it? Check your spam folder, or
      <a class="link-green" href="forgot-password.php">try a different address</a>.
    </div>
  </div>
</div>

<?php include 'includes/auth-footer.php'; ?>

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
      <?php if (isset($_GET['name'])) { ?>
        Thanks, <strong style="color:#16303c"><?php echo htmlspecialchars($_GET['name']); ?></strong> —
        <?php if (isset($_GET['org'])) { ?>
          <strong style="color:#16303c"><?php echo htmlspecialchars($_GET['org']); ?></strong>
          and your coordinator account have been created.
        <?php } else { ?>
          your account has been created.
        <?php } ?>
      <?php } ?>
      It is under review, and you will receive a confirmation email within
      1–2 business days once approved by the System Administrator.
    </p>
    <a class="btn-v btn-green" href="login.php">Go to Sign In</a>
  </div>
</div>

<?php include 'includes/auth-footer.php'; ?>

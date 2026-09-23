<?php
$page_title = 'Reset Password | VPMS';
$body_class = 'auth-center';

require 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    $find = $pdo->prepare('SELECT user_id FROM `user` WHERE email = ?');
    $find->execute(array($email));
    $account = $find->fetch();

    if ($account != false) {
        $code = bin2hex(random_bytes(16));

        $save = $pdo->prepare('UPDATE `user` SET reset_code = ? WHERE user_id = ?');
        $save->execute(array($code, $account['user_id']));

        header('Location: forgot-password-sent.php?email=' . urlencode($email) . '&code=' . $code);
        exit;
    }

    header('Location: forgot-password-sent.php?email=' . urlencode($email));
    exit;
}

include 'includes/auth-header.php';
?>

<div class="auth-card">

  <div class="auth-topbar">
    <a class="link-green" href="login.php"><i class="bi bi-arrow-left"></i> Back to Sign In</a>
  </div>

  <h1 class="auth-title">Forgot your password?</h1>
  <p class="auth-sub">
    Enter the email address on your VPMS account and we'll send you a link to choose a new password.
  </p>

  <form action="forgot-password.php" method="post">
    <div class="field mb-4">
      <label class="field-label" for="email">Email address</label>
      <input class="input-v" type="email" id="email" name="email" placeholder="you@organisation.org" required>
    </div>

    <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Send Reset Link</button>
  </form>

  <p class="text-center mt-4" style="font-size:13.5px;color:#6d7880">
    Remembered it? <a class="link-green" href="login.php">Sign in</a>
  </p>

</div>

<?php include 'includes/auth-footer.php'; ?>

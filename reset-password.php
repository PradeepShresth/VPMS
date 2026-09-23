<?php
$page_title = 'Choose a New Password | VPMS';
$body_class = 'auth-center';

require 'config/db.php';

if (isset($_POST['code'])) {
    $code = $_POST['code'];
} elseif (isset($_GET['code'])) {
    $code = $_GET['code'];
} else {
    $code = '';
}

$find = $pdo->prepare('SELECT user_id, email FROM `user` WHERE reset_code = ? AND reset_code != ?');
$find->execute(array($code, ''));
$account = $find->fetch();

$errors = array();

if ($account == false) {
    $errors[] = 'That reset link is not valid any more. Ask for a new one.';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $account != false) {
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if (strlen($password) < 8) {
        $errors[] = 'The password needs at least 8 characters.';
    }

    if ($password != $confirm) {
        $errors[] = 'The two passwords do not match.';
    }

    if (count($errors) == 0) {
        $save = $pdo->prepare('UPDATE `user` SET password_hash = ?, reset_code = NULL WHERE user_id = ?');
        $save->execute(array(password_hash($password, PASSWORD_DEFAULT), $account['user_id']));

        header('Location: reset-password-done.php');
        exit;
    }
}

include 'includes/auth-header.php';
?>

<div class="auth-card">

  <div class="auth-topbar">
    <a class="link-green" href="login.php"><i class="bi bi-arrow-left"></i> Back to Sign In</a>
  </div>

  <h1 class="auth-title">Choose a new password</h1>

  <?php if ($account != false) { ?>
    <p class="auth-sub">
      Resetting the password for
      <strong style="color:#16303c"><?php echo htmlspecialchars($account['email']); ?></strong>.
    </p>
  <?php } ?>

  <?php if (count($errors) > 0) { ?>
    <div class="notice mb-4" style="border-left-color:#c8504b;background:#fdf3f2">
      <?php foreach ($errors as $error) { ?>
        <p class="notice-text"><?php echo $error; ?></p>
      <?php } ?>
      <?php if ($account == false) { ?>
        <p class="notice-text mt-2">
          <a class="link-green" href="forgot-password.php">Request a new reset link</a>
        </p>
      <?php } ?>
    </div>
  <?php } ?>

  <?php if ($account != false) { ?>

  <form action="reset-password.php" method="post">
    <input type="hidden" name="code" value="<?php echo htmlspecialchars($code); ?>">

    <div class="field">
      <label class="field-label" for="password">New password</label>
      <input class="input-v" type="password" id="password" name="password" placeholder="••••••••" required>

      <div class="pw-meter"><span id="bar"></span></div>
      <p class="pw-label" id="label">Password strength — enter a password</p>

      <ul class="pw-rules">
        <li id="rule-length"><i class="bi bi-circle"></i>At least 8 characters</li>
        <li id="rule-upper"><i class="bi bi-circle"></i>One uppercase letter</li>
        <li id="rule-number"><i class="bi bi-circle"></i>One number</li>
        <li id="rule-symbol"><i class="bi bi-circle"></i>One symbol (! ? @ # ...)</li>
      </ul>
    </div>

    <div class="field mb-4">
      <label class="field-label" for="confirm">Confirm new password</label>
      <input class="input-v" type="password" id="confirm" name="confirm" placeholder="••••••••" required>
      <p class="pw-label" id="match"></p>
    </div>

    <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Reset Password</button>
  </form>

  <?php } ?>

</div>

<?php if ($account != false) { ?>
<script>
var password = document.getElementById('password');
var confirm = document.getElementById('confirm');
var bar = document.getElementById('bar');
var label = document.getElementById('label');
var match = document.getElementById('match');

function checkRule(id, passed) {
  var item = document.getElementById(id);
  var icon = item.querySelector('i');

  if (passed) {
    item.classList.add('ok');
    icon.className = 'bi bi-check-circle-fill';
    return 1;
  }

  item.classList.remove('ok');
  icon.className = 'bi bi-circle';
  return 0;
}

function update() {
  var value = password.value;
  var score = 0;

  score = score + checkRule('rule-length', value.length >= 8);
  score = score + checkRule('rule-upper', /[A-Z]/.test(value));
  score = score + checkRule('rule-number', /[0-9]/.test(value));
  score = score + checkRule('rule-symbol', /[^A-Za-z0-9]/.test(value));

  if (value.length == 0) {
    bar.style.width = '0';
    label.innerHTML = 'Password strength — enter a password';
  } else if (score <= 1) {
    bar.style.width = '25%';
    bar.style.background = '#c8504b';
    label.innerHTML = 'Password strength — Weak';
  } else if (score == 2) {
    bar.style.width = '50%';
    bar.style.background = '#e89c1c';
    label.innerHTML = 'Password strength — Fair';
  } else if (score == 3) {
    bar.style.width = '75%';
    bar.style.background = '#e89c1c';
    label.innerHTML = 'Password strength — Good';
  } else {
    bar.style.width = '100%';
    bar.style.background = '#16663e';
    label.innerHTML = 'Password strength — Strong';
  }

  if (confirm.value.length == 0) {
    match.innerHTML = '';
  } else if (confirm.value == value) {
    match.innerHTML = 'Passwords match';
    match.style.color = '#16663e';
  } else {
    match.innerHTML = 'Passwords do not match';
    match.style.color = '#c8504b';
  }
}

password.onkeyup = update;
confirm.onkeyup = update;
</script>
<?php } ?>

<?php include 'includes/auth-footer.php'; ?>

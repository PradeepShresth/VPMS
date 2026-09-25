<?php
$page_title = 'Sign In | VPMS';
$body_class = '';

require 'config/db.php';

session_start();

$error = '';
$email = '';

$count = $pdo->prepare('SELECT COUNT(*) FROM `user` WHERE role_id = ? AND status = ?');
$count->execute(array(1, 'active'));
$volunteers = $count->fetchColumn();

$count = $pdo->prepare('SELECT COUNT(*) FROM organization WHERE status = ?');
$count->execute(array('verified'));
$organizations = $count->fetchColumn();

$count = $pdo->prepare('SELECT COALESCE(SUM(hours_logged), 0) FROM event_volunteer WHERE attended = ?');
$count->execute(array(1));
$hours = $count->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($email == '' || $password == '') {
        $error = 'Enter your email address and password.';
    } else {
        $find = $pdo->prepare(
            'SELECT u.user_id, u.full_name, u.password_hash, u.status,
                    u.role_id, u.organization_id, r.name AS role_name
             FROM `user` u
             JOIN role r ON r.role_id = u.role_id
             WHERE u.email = ?'
        );
        $find->execute(array($email));
        $account = $find->fetch(PDO::FETCH_ASSOC);

        if ($account == false || password_verify($password, $account['password_hash']) == false) {
            // same message either way, so nobody can guess which emails exist
            $error = 'Those details do not match an account.';

        } elseif ($account['status'] == 'pending') {
            $error = 'Your account is still waiting for administrator approval.';

        } elseif ($account['status'] == 'suspended') {
            $error = 'This account has been suspended. Contact the administrator.';

        } else {
            // signed in - remember who they are and send them to the dashboard
            $_SESSION['user_id'] = $account['user_id'];
            $_SESSION['full_name'] = $account['full_name'];
            $_SESSION['role_name'] = $account['role_name'];
            $_SESSION['role_id'] = $account['role_id'];
            $_SESSION['organization_id'] = $account['organization_id'];

            header('Location: dashboard.php');
            exit;
        }
    }
}

include 'includes/auth-header.php';
?>

<div class="auth-split">

  <div class="auth-left">
    <a href="index.php" style="color:rgba(255,255,255,.85);font-size:14px">
      <i class="bi bi-arrow-left"></i> Back to Home
    </a>

    <div style="margin:auto 0">
      <p class="auth-kicker">SDG 17 · PARTNERSHIPS FOR THE GOALS</p>
      <h1 class="auth-head">
        Connecting people,<br>
        <span class="gold">amplifying impact</span>
      </h1>

      <div class="auth-stat">
        <span class="a-val"><?php echo $volunteers; ?></span>
        <span class="a-lab">Registered volunteers</span>
      </div>
      <div class="auth-stat">
        <span class="a-val"><?php echo $organizations; ?></span>
        <span class="a-lab">Partner organizations</span>
      </div>
      <div class="auth-stat">
        <span class="a-val"><?php echo $hours; ?></span>
        <span class="a-lab">Verified volunteer hours</span>
      </div>
    </div>

    <p class="auth-foot">VPMS · Volunteer Partnership Management System</p>
  </div>

  <div class="auth-right">
    <div class="auth-form">
      <h2 class="auth-title">Welcome back</h2>
      <p class="auth-sub">Sign in to your VPMS account</p>

      <?php if ($error != '') { ?>
        <div class="notice mb-4" style="border-left-color:#c8504b;background:#fdf3f2">
          <p class="notice-text"><?php echo $error; ?></p>
        </div>
      <?php } ?>

      <form action="login.php" method="post">

        <div class="field">
          <label class="field-label" for="email">Email address</label>
          <input class="input-v" type="email" id="email" name="email" placeholder="you@organization.org"
                 value="<?php echo htmlspecialchars($email); ?>">
        </div>

        <div class="field mb-4">
          <div class="d-flex align-items-center">
            <label class="field-label mb-0" for="password">Password</label>
            <a class="link-green ms-auto" style="font-size:13px;font-weight:400" href="forgot-password.php">Forgot password?</a>
          </div>
          <input class="input-v mt-2" type="password" id="password" name="password" placeholder="••••••••">
        </div>

        <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Sign In</button>
      </form>

      <p class="text-center mt-4" style="font-size:13.5px;color:#6d7880">
        New to VPMS? <a class="link-green" href="register.php">Create an account</a>
      </p>
    </div>
  </div>

</div>

<?php include 'includes/auth-footer.php'; ?>

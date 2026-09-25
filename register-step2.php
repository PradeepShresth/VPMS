<?php
$page_title = 'Create Account | VPMS';
$body_class = 'auth-center';

require 'config/db.php';

// Which role was picked on step 1? It arrives in the URL the first time,
// then in a hidden field when this page posts back to itself.
if (isset($_POST['role_id'])) {
    $role_id = $_POST['role_id'];
} elseif (isset($_GET['role'])) {
    $role_id = $_GET['role'];
} else {
    $role_id = 1;
}

// Look the role up. If it is not a real one, fall back to Volunteer.
$find_role = $pdo->prepare('SELECT name FROM role WHERE role_id = ? AND role_id <= 5');
$find_role->execute(array($role_id));
$role_name = $find_role->fetchColumn();

if ($role_name == false) {
    $role_id = 1;
    $role_name = 'Volunteer';
}

$find = $pdo->prepare('SELECT organization_id, name FROM organization WHERE status = ? ORDER BY name');
$find->execute(array('verified'));
$organizations = $find->fetchAll();

$join = 0;

$errors = array();
$name = '';
$email = '';
$organization = '';
$phone = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $organization = trim($_POST['org']);
    $phone = trim($_POST['phone']);
    $join = $_POST['join'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($name == '') {
        $errors[] = 'Enter your full name.';
    }

    if ($email == '') {
        $errors[] = 'Enter your email address.';
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
        $errors[] = 'That email address does not look right.';
    }

    if (strlen($password) < 8) {
        $errors[] = 'Your password needs at least 8 characters.';
    }

    if ($password != $confirm) {
        $errors[] = 'The two passwords do not match.';
    }

    // is somebody already using this email?
    if (count($errors) == 0) {
        $check = $pdo->prepare('SELECT user_id FROM `user` WHERE email = ?');
        $check->execute(array($email));

        if ($check->fetch()) {
            $errors[] = 'An account with that email already exists. Try signing in instead.';
        }
    }

    // all good - save the account and move on
    if (count($errors) == 0) {
        $save = $pdo->prepare(
            'INSERT INTO `user` (full_name, email, password_hash, phone, role_id, organization_name)
             VALUES (?, ?, ?, ?, ?, ?)'
        );

        $save->execute(array(
            $name,
            $email,
            password_hash($password, PASSWORD_DEFAULT),
            $phone,
            $role_id,
            $organization
        ));

        // being part of an organization is the organization's call, so this only asks
        if ($join > 0) {
            $ask = $pdo->prepare(
                'INSERT INTO membership_request (user_id, organization_id) VALUES (?, ?)'
            );
            $ask->execute(array($pdo->lastInsertId(), $join));
        }

        header('Location: register-success.php?name=' . urlencode($name));
        exit;
    }
}

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

  <p class="mb-4" style="font-size:13.5px;color:#6d7880">
    Joining as <strong style="color:#16663e"><?php echo $role_name; ?></strong>.
    <a class="link-green" href="register.php" style="font-weight:400">Change</a>
  </p>

  <?php if (count($errors) > 0) { ?>
    <div class="notice mb-4" style="border-left-color:#c8504b;background:#fdf3f2">
      <p class="notice-title" style="color:#c8504b">Please fix the following</p>
      <?php foreach ($errors as $error) { ?>
        <p class="notice-text"><?php echo $error; ?></p>
      <?php } ?>
    </div>
  <?php } ?>

  <form action="register-step2.php" method="post">
    <input type="hidden" name="role_id" value="<?php echo $role_id; ?>">

    <div class="field">
      <label class="field-label" for="name">Full Name</label>
      <input class="input-v" type="text" id="name" name="name" placeholder="Amara Osei"
             value="<?php echo htmlspecialchars($name); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="email">Email Address</label>
      <input class="input-v" type="email" id="email" name="email" placeholder="you@organization.org"
             value="<?php echo htmlspecialchars($email); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="org">Where you work or study</label>
      <input class="input-v" type="text" id="org" name="org" placeholder="Green Future NGO"
             value="<?php echo htmlspecialchars($organization); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="join">Are you part of an organization on VPMS? (optional)</label>
      <select class="select-v" id="join" name="join">
        <option value="0">Not part of one</option>
        <?php foreach ($organizations as $row) { ?>
          <option value="<?php echo $row['organization_id']; ?>"
            <?php if ($join == $row['organization_id']) echo 'selected'; ?>>
            <?php echo htmlspecialchars($row['name']); ?>
          </option>
        <?php } ?>
      </select>
      <p style="margin-top:6px;font-size:12.5px;color:#98a2aa">
        Their coordinator has to approve it before you are listed as part of them.
      </p>
    </div>

    <div class="field">
      <label class="field-label" for="phone">Phone Number</label>
      <input class="input-v" type="tel" id="phone" name="phone" placeholder="+60 12-345 6789"
             value="<?php echo htmlspecialchars($phone); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="password">Password</label>
      <input class="input-v" type="password" id="password" name="password" placeholder="At least 8 characters">
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

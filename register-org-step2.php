<?php
$page_title = 'Register your Organization | VPMS';
$body_class = 'auth-center';

require 'config/db.php';

session_start();

// step 1 must have been filled in first
if (!isset($_SESSION['new_org'])) {
    header('Location: register-org.php');
    exit;
}

$org = $_SESSION['new_org'];

$errors = array();
$contact = '';
$designation = '';
$email = '';
$phone = '';
$about = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $contact = trim($_POST['contact']);
    $designation = trim($_POST['designation']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $about = trim($_POST['about']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($contact == '') {
        $errors[] = 'Enter the contact person\'s name.';
    }

    if ($email == '') {
        $errors[] = 'Enter an email address.';
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
        $errors[] = 'That email address does not look right.';
    }

    if (strlen($password) < 8) {
        $errors[] = 'The password needs at least 8 characters.';
    }

    if ($password != $confirm) {
        $errors[] = 'The two passwords do not match.';
    }

    if (count($errors) == 0) {
        $check = $pdo->prepare('SELECT user_id FROM `user` WHERE email = ?');
        $check->execute(array($email));

        if ($check->fetch()) {
            $errors[] = 'An account with that email already exists. Try signing in instead.';
        }
    }

    if (count($errors) == 0) {

        // save the organization first, because the contact account points at it
        $save_org = $pdo->prepare(
            'INSERT INTO organization (name, type, registration_no, country, state, city, address, website, description)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );

        $save_org->execute(array(
            $org['name'], $org['type'], $org['reg'], $org['country'],
            $org['state'], $org['city'], $org['address'], $org['website'], $about
        ));

        $organization_id = $pdo->lastInsertId();

        // the contact person gets the role that matches what they registered
        if ($org['type'] == 'Sponsor / Donor') {
            $contact_role = 5;
        } else {
            $contact_role = 2;
        }

        $save_user = $pdo->prepare(
            'INSERT INTO `user` (full_name, email, password_hash, phone, role_id, organization_id, designation)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );

        $save_user->execute(array(
            $contact, $email, password_hash($password, PASSWORD_DEFAULT),
            $phone, $contact_role, $organization_id, $designation
        ));

        $point = $pdo->prepare('UPDATE organization SET contact_id = ? WHERE organization_id = ?');
        $point->execute(array($pdo->lastInsertId(), $organization_id));

        unset($_SESSION['new_org']);

        header('Location: register-success.php?name=' . urlencode($contact) . '&org=' . urlencode($org['name']));
        exit;
    }
}

include 'includes/auth-header.php';
?>

<div class="auth-card">

  <div class="auth-topbar">
    <a class="link-green" href="register-org.php"><i class="bi bi-arrow-left"></i> Back</a>
    <a class="link-green ms-auto" href="register.php">Register as an Individual</a>
  </div>

  <h1 class="auth-title">Register your Organization</h1>
  <p class="auth-sub">Step 2 of 2 — Contact person</p>

  <div class="steps">
    <span class="done"></span>
    <span class="done"></span>
  </div>

  <p class="mb-4" style="font-size:13.5px;color:#6d7880">
    Registering <strong style="color:#16663e"><?php echo htmlspecialchars($org['name']); ?></strong>
    (<?php echo htmlspecialchars($org['type']); ?>).
    <a class="link-green" href="register-org.php" style="font-weight:400">Change</a>
  </p>

  <?php if (count($errors) > 0) { ?>
    <div class="notice mb-4" style="border-left-color:#c8504b;background:#fdf3f2">
      <p class="notice-title" style="color:#c8504b">Please fix the following</p>
      <?php foreach ($errors as $error) { ?>
        <p class="notice-text"><?php echo $error; ?></p>
      <?php } ?>
    </div>
  <?php } ?>

  <form action="register-org-step2.php" method="post">

    <div class="field">
      <label class="field-label" for="contact">Contact Person Name</label>
      <input class="input-v" type="text" id="contact" name="contact" placeholder="e.g. Amara Osei"
             value="<?php echo htmlspecialchars($contact); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="designation">Designation/Role</label>
      <input class="input-v" type="text" id="designation" name="designation" placeholder="e.g. Programme Manager"
             value="<?php echo htmlspecialchars($designation); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="email">Email Address</label>
      <input class="input-v" type="email" id="email" name="email" placeholder="you@organization.org"
             value="<?php echo htmlspecialchars($email); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="phone">Phone Number</label>
      <input class="input-v" type="tel" id="phone" name="phone" placeholder="e.g. +60 12-345 6789"
             value="<?php echo htmlspecialchars($phone); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="password">Password</label>
      <input class="input-v" type="password" id="password" name="password" placeholder="At least 8 characters">
    </div>

    <div class="field">
      <label class="field-label" for="confirm">Confirm Password</label>
      <input class="input-v" type="password" id="confirm" name="confirm" placeholder="••••••••">
    </div>

    <div class="field">
      <label class="field-label" for="about">Organization Description</label>
      <textarea class="textarea-v" id="about" name="about"
                placeholder="Briefly describe your focus areas and mission..."><?php echo htmlspecialchars($about); ?></textarea>
    </div>

    <div class="field mb-4">
      <span class="field-label">Upload Supporting Documents</span>
      <label class="dropzone d-block">
        <input type="file" name="documents[]" multiple hidden>
        <i class="bi bi-upload" style="font-size:18px"></i>
        <span class="dz-title">Click to upload or drag &amp; drop</span>
        <span class="dz-note">PDF, DOCX, or JPEG up to 10MB — not stored yet</span>
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

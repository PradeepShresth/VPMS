<?php
$page_title = 'Register your Organization | VPMS';
$body_class = 'auth-center';

session_start();

$errors = array();

// keep whatever was typed before, so going Back does not wipe the form
$org = isset($_SESSION['new_org']) ? $_SESSION['new_org'] : array(
    'name' => '', 'type' => 'NGO', 'reg' => '', 'country' => '',
    'state' => '', 'city' => '', 'address' => '', 'website' => ''
);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $org['name'] = trim($_POST['name']);
    $org['type'] = $_POST['type'];
    $org['reg'] = trim($_POST['reg']);
    $org['country'] = trim($_POST['country']);
    $org['state'] = trim($_POST['state']);
    $org['city'] = trim($_POST['city']);
    $org['address'] = trim($_POST['address']);
    $org['website'] = trim($_POST['website']);

    if ($org['name'] == '') {
        $errors[] = 'Enter the organization name.';
    }

    if ($org['country'] == '') {
        $errors[] = 'Enter the country.';
    }

    if ($org['website'] != '' && filter_var($org['website'], FILTER_VALIDATE_URL) == false) {
        $errors[] = 'That website address does not look right. Include https://';
    }

    // nothing is saved yet - hold step 1 in the session and move to step 2
    if (count($errors) == 0) {
        $_SESSION['new_org'] = $org;
        header('Location: register-org-step2.php');
        exit;
    }
}

$types = array('NGO', 'Corporate', 'Community Group', 'Government Body', 'Sponsor / Donor', 'Educational Institution');

include 'includes/auth-header.php';
?>

<div class="auth-card">

  <div class="auth-topbar">
    <a class="link-green" href="index.php"><i class="bi bi-arrow-left"></i> Back</a>
    <a class="link-green ms-auto" href="register.php">Register as an Individual</a>
  </div>

  <h1 class="auth-title">Register your Organization</h1>
  <p class="auth-sub">Step 1 of 2 — Organization details</p>

  <div class="steps">
    <span class="done"></span>
    <span></span>
  </div>

  <?php if (count($errors) > 0) { ?>
    <div class="notice mb-4" style="border-left-color:#c8504b;background:#fdf3f2">
      <p class="notice-title" style="color:#c8504b">Please fix the following</p>
      <?php foreach ($errors as $error) { ?>
        <p class="notice-text"><?php echo $error; ?></p>
      <?php } ?>
    </div>
  <?php } ?>

  <form action="register-org.php" method="post">

    <div class="field">
      <label class="field-label" for="name">Organization Name</label>
      <input class="input-v" type="text" id="name" name="name" placeholder="e.g. Green Future NGO"
             value="<?php echo htmlspecialchars($org['name']); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="type">Organization Type</label>
      <select class="select-v" id="type" name="type">
        <?php foreach ($types as $type) { ?>
          <option <?php if ($org['type'] == $type) echo 'selected'; ?>><?php echo $type; ?></option>
        <?php } ?>
      </select>
    </div>

    <div class="field">
      <label class="field-label" for="reg">Registration Number</label>
      <input class="input-v" type="text" id="reg" name="reg" placeholder="e.g. ROS-1234/2026"
             value="<?php echo htmlspecialchars($org['reg']); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="country">Country</label>
      <input class="input-v" type="text" id="country" name="country" placeholder="e.g. Malaysia"
             value="<?php echo htmlspecialchars($org['country']); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="state">State/Region</label>
      <input class="input-v" type="text" id="state" name="state" placeholder="e.g. Selangor"
             value="<?php echo htmlspecialchars($org['state']); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="city">City</label>
      <input class="input-v" type="text" id="city" name="city" placeholder="e.g. Petaling Jaya"
             value="<?php echo htmlspecialchars($org['city']); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="address">Address</label>
      <input class="input-v" type="text" id="address" name="address" placeholder="e.g. 12, Jalan Gasing"
             value="<?php echo htmlspecialchars($org['address']); ?>">
    </div>

    <div class="field mb-4">
      <label class="field-label" for="website">Website URL</label>
      <input class="input-v" type="url" id="website" name="website" placeholder="e.g. https://greenfuture.org"
             value="<?php echo htmlspecialchars($org['website']); ?>">
    </div>

    <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Continue &rarr;</button>
  </form>

  <p class="text-center mt-4" style="font-size:13.5px;color:#6d7880">
    Already have an account? <a class="link-green" href="login.php">Sign in</a>
  </p>

</div>

<?php include 'includes/auth-footer.php'; ?>

<?php
$page_title = 'Edit Profile | VPMS';
$active = 'profile';

require 'includes/auth.php';
require 'config/db.php';

$find = $pdo->prepare('SELECT * FROM `user` WHERE user_id = ?');
$find->execute(array($_SESSION['user_id']));
$me = $find->fetch();

$errors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $me['full_name'] = trim($_POST['name']);
    $me['email'] = trim($_POST['email']);
    $me['phone'] = trim($_POST['phone']);
    $me['organisation_name'] = trim($_POST['org']);
    $me['bio'] = trim($_POST['bio']);

    if (isset($_POST['skills'])) {
        $me['skills'] = implode(', ', $_POST['skills']);
    } else {
        $me['skills'] = '';
    }

    if ($me['full_name'] == '') {
        $errors[] = 'Enter your full name.';
    }

    if (filter_var($me['email'], FILTER_VALIDATE_EMAIL) == false) {
        $errors[] = 'That email address does not look right.';
    }

    if (count($errors) == 0) {
        $check = $pdo->prepare('SELECT user_id FROM `user` WHERE email = ? AND user_id != ?');
        $check->execute(array($me['email'], $_SESSION['user_id']));

        if ($check->fetch()) {
            $errors[] = 'Another account already uses that email address.';
        }
    }

    if (count($errors) == 0) {
        $save = $pdo->prepare(
            'UPDATE `user`
                SET full_name = ?, email = ?, phone = ?, organisation_name = ?, skills = ?, bio = ?
              WHERE user_id = ?'
        );

        $save->execute(array(
            $me['full_name'],
            $me['email'],
            $me['phone'],
            $me['organisation_name'],
            $me['skills'],
            $me['bio'],
            $_SESSION['user_id']
        ));

        // the sidebar reads the name from the session, so keep it in step
        $_SESSION['full_name'] = $me['full_name'];

        header('Location: profile.php?saved=1');
        exit;
    }
}

$chosen = explode(',', $me['skills']);

foreach ($chosen as $key => $value) {
    $chosen[$key] = trim($value);
}

$parts = explode(' ', $me['full_name']);
$initials = strtoupper(substr($parts[0], 0, 1));

if (isset($parts[1])) {
    $initials = $initials . strtoupper(substr($parts[1], 0, 1));
}

include 'includes/app-header.php';
?>

<div class="col-mid mx-auto">

  <a class="back-link" href="profile.php"><i class="bi bi-arrow-left"></i> Back to Profile</a>

  <h1 class="page-title mb-4">Edit Profile</h1>

  <?php if (count($errors) > 0) { ?>
    <div class="notice mb-4" style="border-left-color:#c8504b;background:#fdf3f2">
      <p class="notice-title" style="color:#c8504b">Please fix the following</p>
      <?php foreach ($errors as $error) { ?>
        <p class="notice-text"><?php echo $error; ?></p>
      <?php } ?>
    </div>
  <?php } ?>

  <form action="profile-edit.php" method="post">

    <div class="card-v card-v-pad mb-4">
      <p class="section-label">Profile Photo</p>
      <div class="d-flex align-items-center gap-3">
        <span class="avatar-circle amber" style="width:56px;height:56px;font-size:18px"><?php echo $initials; ?></span>
        <div>
          <p style="font-size:13.5px;color:#6d7880;margin-bottom:0">
            Your initials are used across the platform.
          </p>
          <p style="margin-top:4px;font-size:12.5px;color:#98a2aa">Photo uploads are not part of this build.</p>
        </div>
      </div>
    </div>

    <div class="card-v card-v-pad mb-4">
      <p class="section-label">Personal Information</p>

      <div class="field">
        <label class="field-label" for="name">Full Name</label>
        <input class="input-v" type="text" id="name" name="name"
               value="<?php echo htmlspecialchars($me['full_name']); ?>">
      </div>

      <div class="row g-3">
        <div class="col-sm-6">
          <div class="field">
            <label class="field-label" for="email">Email Address</label>
            <input class="input-v" type="email" id="email" name="email"
                   value="<?php echo htmlspecialchars($me['email']); ?>">
          </div>
        </div>
        <div class="col-sm-6">
          <div class="field">
            <label class="field-label" for="phone">Phone Number</label>
            <input class="input-v" type="tel" id="phone" name="phone"
                   value="<?php echo htmlspecialchars($me['phone']); ?>">
          </div>
        </div>
      </div>

      <div class="field">
        <label class="field-label" for="org">Organisation / Affiliation</label>
        <input class="input-v" type="text" id="org" name="org"
               value="<?php echo htmlspecialchars($me['organisation_name']); ?>">
        <?php if ($me['organisation_id'] != '') { ?>
          <p style="margin-top:6px;font-size:12.5px;color:#98a2aa">
            Your account is linked to a registered organisation, which is what the platform shows.
          </p>
        <?php } ?>
      </div>

      <div class="field mb-0">
        <label class="field-label" for="bio">About you</label>
        <textarea class="textarea-v" id="bio" name="bio"><?php echo htmlspecialchars($me['bio']); ?></textarea>
      </div>
    </div>

    <div class="card-v card-v-pad mb-4">
      <p class="section-label">Skills &amp; Interests</p>
      <div class="check-grid" style="grid-template-columns:repeat(2,1fr)">
        <label class="check-v"><input type="checkbox" name="skills[]" value="Physical Fitness"
          <?php if (in_array('Physical Fitness', $chosen)) echo 'checked'; ?>> Physical Fitness</label>
        <label class="check-v"><input type="checkbox" name="skills[]" value="Teamwork"
          <?php if (in_array('Teamwork', $chosen)) echo 'checked'; ?>> Teamwork</label>
        <label class="check-v"><input type="checkbox" name="skills[]" value="Environmental Awareness"
          <?php if (in_array('Environmental Awareness', $chosen)) echo 'checked'; ?>> Environmental Awareness</label>
        <label class="check-v"><input type="checkbox" name="skills[]" value="First Aid"
          <?php if (in_array('First Aid', $chosen)) echo 'checked'; ?>> First Aid</label>
        <label class="check-v"><input type="checkbox" name="skills[]" value="Driving Licence"
          <?php if (in_array('Driving Licence', $chosen)) echo 'checked'; ?>> Driving Licence</label>
        <label class="check-v"><input type="checkbox" name="skills[]" value="Photography"
          <?php if (in_array('Photography', $chosen)) echo 'checked'; ?>> Photography</label>
      </div>
    </div>

    <div class="row g-3">
      <div class="col-5">
        <a class="btn-v btn-outline btn-block btn-lg-v" href="profile.php">Cancel</a>
      </div>
      <div class="col-7">
        <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Save Changes</button>
      </div>
    </div>
  </form>

</div>

<?php include 'includes/app-footer.php'; ?>

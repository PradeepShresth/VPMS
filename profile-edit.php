<?php
$page_title = 'Edit Profile | VPMS';
$active = 'profile';

require 'includes/auth.php';
require 'config/db.php';

$find = $pdo->prepare('SELECT * FROM `user` WHERE user_id = ?');
$find->execute(array($_SESSION['user_id']));
$me = $find->fetch();

$find = $pdo->prepare('SELECT organization_id, name FROM organization WHERE status = ? ORDER BY name');
$find->execute(array('verified'));
$organizations = $find->fetchAll();

$find = $pdo->prepare(
    'SELECT m.*, o.name FROM membership_request m
     JOIN organization o ON o.organization_id = m.organization_id
     WHERE m.user_id = ? AND m.status = ?'
);
$find->execute(array($_SESSION['user_id'], 'pending'));
$asked = $find->fetch();
// if this comes back with a row they already asked to join somewhere

$my_organization = '';

if ($me['organization_id'] != '') {
    $find = $pdo->prepare('SELECT name FROM organization WHERE organization_id = ?');
    $find->execute(array($me['organization_id']));
    $my_organization = $find->fetchColumn();
}

$errors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['join'])) {

    if ($_POST['join'] > 0 && $me['organization_id'] == '' && $asked == false) {
        $save = $pdo->prepare(
            'INSERT INTO membership_request (user_id, organization_id, designation) VALUES (?, ?, ?)'
        );
        $save->execute(array($_SESSION['user_id'], $_POST['join'], trim($_POST['position'])));
    }

    header('Location: profile-edit.php?asked=1');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $me['full_name'] = trim($_POST['name']);
    $me['email'] = trim($_POST['email']);
    $me['phone'] = trim($_POST['phone']);
    $me['organization_name'] = trim($_POST['org']);
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
                SET full_name = ?, email = ?, phone = ?, organization_name = ?, skills = ?, bio = ?
              WHERE user_id = ?'
        );

        $save->execute(array(
            $me['full_name'],
            $me['email'],
            $me['phone'],
            $me['organization_name'],
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
        <label class="field-label" for="org">Where you work or study</label>
        <input class="input-v" type="text" id="org" name="org" placeholder="e.g. TechCorp China"
               value="<?php echo htmlspecialchars($me['organization_name']); ?>">
        <p style="margin-top:6px;font-size:12.5px;color:#98a2aa">
          Just a note on your profile. To be listed as part of an organization on the platform,
          use the box below.
        </p>
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

  <div class="card-v card-v-pad mb-4">
    <p class="section-label">Organization on VPMS</p>

    <?php if ($me['organization_id'] != '') { ?>
      <p style="font-size:14px;color:#48545e">
        You are listed as part of <strong><?php echo htmlspecialchars($my_organization); ?></strong><?php
          if ($me['designation'] != '') { ?> &middot; <?php echo htmlspecialchars($me['designation']); ?><?php } ?>.
      </p>
      <p style="font-size:12.5px;color:#98a2aa">
        Their coordinator manages this. Ask them to remove you if it is no longer right.
      </p>

    <?php } elseif ($asked != false) { ?>
      <p style="font-size:14px;color:#48545e">
        Waiting on <strong><?php echo htmlspecialchars($asked['name']); ?></strong> to approve your request.
      </p>

    <?php } else { ?>
      <p style="font-size:13px;color:#6d7880">
        Ask an organization to list you as one of their people. Their coordinator decides.
      </p>

      <form action="profile-edit.php" method="post">
        <div class="row g-3 align-items-end">
          <div class="col-md-6">
            <label class="field-label" for="join">Organization</label>
            <select class="select-v" id="join" name="join">
              <option value="0">Choose one</option>
              <?php foreach ($organizations as $row) { ?>
                <option value="<?php echo $row['organization_id']; ?>">
                  <?php echo htmlspecialchars($row['name']); ?>
                </option>
              <?php } ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="field-label" for="position">Your position</label>
            <input class="input-v" type="text" id="position" name="position" placeholder="e.g. Volunteer">
          </div>
          <div class="col-md-2">
            <button class="btn-v btn-soft btn-block" type="submit">Ask</button>
          </div>
        </div>
      </form>
    <?php } ?>
  </div>

</div>

<?php include 'includes/app-footer.php'; ?>

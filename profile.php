<?php
$page_title = 'My Profile | VPMS';
$active = 'profile';

require 'includes/auth.php';
require 'config/db.php';

// the change password box at the bottom posts back to this page
$errors = array();
$changed = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current = $_POST['current'];
    $new = $_POST['new'];
    $confirm = $_POST['confirm'];

    $find = $pdo->prepare('SELECT password_hash FROM `user` WHERE user_id = ?');
    $find->execute(array($_SESSION['user_id']));
    $hash = $find->fetchColumn();

    if (password_verify($current, $hash) == false) {
        $errors[] = 'Your current password is not right.';
    }

    if (strlen($new) < 8) {
        $errors[] = 'The new password needs at least 8 characters.';
    }

    if ($new != $confirm) {
        $errors[] = 'The two new passwords do not match.';
    }

    if (count($errors) == 0) {
        $save = $pdo->prepare('UPDATE `user` SET password_hash = ? WHERE user_id = ?');
        $save->execute(array(password_hash($new, PASSWORD_DEFAULT), $_SESSION['user_id']));

        $changed = true;
    }
}

$find = $pdo->prepare(
    'SELECT u.full_name, u.email, u.phone, u.organization_name, u.designation,
            u.status, u.created_at, r.name AS role_name,
            o.name AS organization
     FROM `user` u
     JOIN role r ON r.role_id = u.role_id
     LEFT JOIN organization o ON o.organization_id = u.organization_id
     WHERE u.user_id = ?'
);
$find->execute(array($_SESSION['user_id']));
$me = $find->fetch();

// a real organization row wins over whatever they typed in when signing up
if ($me['organization'] != '') {
    $organization = $me['organization'];
} elseif ($me['organization_name'] != '') {
    $organization = $me['organization_name'];
} else {
    $organization = '';
}

$member_since = date('F Y', strtotime($me['created_at']));

$count = $pdo->prepare(
    'SELECT COALESCE(SUM(hours_logged), 0) FROM event_volunteer WHERE user_id = ? AND attended = 1'
);
$count->execute(array($_SESSION['user_id']));
$my_hours = $count->fetchColumn();

$count = $pdo->prepare('SELECT COUNT(*) FROM event_volunteer WHERE user_id = ? AND attended = 1');
$count->execute(array($_SESSION['user_id']));
$my_events = $count->fetchColumn();

$count = $pdo->prepare('SELECT COUNT(*) FROM application WHERE user_id = ? AND status = ?');
$count->execute(array($_SESSION['user_id'], 'accepted'));
$my_opportunities = $count->fetchColumn();

if ($_SESSION['organization_id'] != '') {
    $count = $pdo->prepare(
        'SELECT COUNT(*) FROM partnership
          WHERE requested_by = ? OR organization_id = ? OR partner_id = ?'
    );
    $count->execute(array($_SESSION['user_id'], $_SESSION['organization_id'], $_SESSION['organization_id']));
} else {
    $count = $pdo->prepare('SELECT COUNT(*) FROM partnership WHERE requested_by = ?');
    $count->execute(array($_SESSION['user_id']));
}

$my_partnerships = $count->fetchColumn();

include 'includes/app-header.php';
?>

<?php if (isset($_GET['saved'])) { ?>
  <div class="banner"><i class="bi bi-check-circle-fill"></i>Profile updated.</div>
<?php } elseif ($changed) { ?>
  <div class="banner"><i class="bi bi-check-circle-fill"></i>Password updated.</div>
<?php } ?>

<div class="head-row">
  <div class="flex-grow-1">
    <h1 class="page-title">My Profile</h1>
    <p class="page-sub">Your details, activity and password</p>
  </div>
  <a class="btn-v btn-green" href="profile-edit.php">Edit Profile</a>
</div>

<div class="card-v card-v-pad mb-4">
  <h2 style="font-family:'Fraunces',serif;font-size:25px;font-weight:400;margin-bottom:8px">
    <?php echo htmlspecialchars($me['full_name']); ?>
  </h2>
  <div class="d-flex flex-wrap gap-4" style="font-size:13.5px;color:#6d7880">
    <span><i class="bi bi-envelope me-2"></i><?php echo htmlspecialchars($me['email']); ?></span>
    <span><?php echo htmlspecialchars($me['role_name']); ?></span>
    <span>Member since <?php echo $member_since; ?></span>
    <?php if ($me['status'] != 'active') { ?>
      <span class="badge-v badge-pending"><?php echo $me['status']; ?></span>
    <?php } ?>
  </div>
</div>

<div class="row g-4 mb-4">

  <div class="col-lg-7">
    <div class="card-v card-v-pad h-100">
      <div class="d-flex align-items-center mb-4">
        <span style="font-family:'Fraunces',serif;font-size:19px">Personal Information</span>
        <a class="ms-auto link-green" style="font-size:13.5px" href="profile-edit.php">Edit Profile</a>
      </div>

      <div class="field">
        <label class="field-label" for="fullname">Full Name</label>
        <input class="input-v" type="text" id="fullname" value="<?php echo htmlspecialchars($me['full_name']); ?>" readonly>
      </div>

      <div class="field">
        <label class="field-label" for="email">Email Address</label>
        <input class="input-v" type="email" id="email" value="<?php echo htmlspecialchars($me['email']); ?>" readonly>
      </div>

      <div class="field">
        <label class="field-label" for="phone">Phone Number</label>
        <input class="input-v" type="tel" id="phone" value="<?php echo htmlspecialchars($me['phone']); ?>" readonly>
      </div>

      <?php if ($me['designation'] != '') { ?>
        <div class="field">
          <label class="field-label" for="designation">Designation / Role</label>
          <input class="input-v" type="text" id="designation" value="<?php echo htmlspecialchars($me['designation']); ?>" readonly>
        </div>
      <?php } ?>

      <div class="field mb-0">
        <label class="field-label" for="org">Organization</label>
        <?php if ($me['organization'] != '') { ?>
          <p style="font-size:14.5px">
            <a class="link-green" href="organization-details.php?id=<?php echo $_SESSION['organization_id']; ?>">
              <?php echo htmlspecialchars($me['organization']); ?>
            </a>
            <?php if ($me['designation'] != '') { ?>
              <span style="color:#6d7880"> &middot; <?php echo htmlspecialchars($me['designation']); ?></span>
            <?php } ?>
          </p>
        <?php } else { ?>
          <input class="input-v" type="text" id="org" value="<?php echo htmlspecialchars($organization); ?>" readonly>
          <p style="margin-top:6px;font-size:12.5px;color:#98a2aa">Not linked to an organization on VPMS.</p>
        <?php } ?>
      </div>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="card-v card-v-pad h-100">
      <p style="font-family:'Fraunces',serif;font-size:19px;margin-bottom:18px">Activity Summary</p>

      <div class="d-flex align-items-center gap-3 mb-3" style="padding:15px 18px;border-radius:8px;background:#eef4fa">
        <span class="flex-grow-1">
          <span class="d-block" style="font-size:14px;font-weight:600">Total Hours Volunteered</span>
          <span class="d-block" style="font-size:12.5px;color:#6d7880">Recorded attendance only</span>
        </span>
        <span style="font-family:'Fraunces',serif;font-size:23px;color:#16663e"><?php echo $my_hours; ?> hrs</span>
      </div>

      <div class="d-flex align-items-center gap-3 mb-3" style="padding:15px 18px;border-radius:8px;background:#eef4fa">
        <span class="flex-grow-1">
          <span class="d-block" style="font-size:14px;font-weight:600">Events Attended</span>
          <span class="d-block" style="font-size:12.5px;color:#6d7880">Across all organizations</span>
        </span>
        <span style="font-family:'Fraunces',serif;font-size:23px;color:#16663e"><?php echo $my_events; ?></span>
      </div>

      <div class="d-flex align-items-center gap-3 mb-3" style="padding:15px 18px;border-radius:8px;background:#eef4fa">
        <span class="flex-grow-1">
          <span class="d-block" style="font-size:14px;font-weight:600">Opportunities Joined</span>
          <span class="d-block" style="font-size:12.5px;color:#6d7880">Applications accepted</span>
        </span>
        <span style="font-family:'Fraunces',serif;font-size:23px;color:#16663e"><?php echo $my_opportunities; ?></span>
      </div>

      <div class="d-flex align-items-center gap-3" style="padding:15px 18px;border-radius:8px;background:#eef4fa">
        <span class="flex-grow-1">
          <span class="d-block" style="font-size:14px;font-weight:600">Partnerships Managed</span>
          <span class="d-block" style="font-size:12.5px;color:#6d7880">SDG 17 Core</span>
        </span>
        <span style="font-family:'Fraunces',serif;font-size:23px;color:#16663e"><?php echo $my_partnerships; ?></span>
      </div>
    </div>
  </div>
</div>

<div class="card-v card-v-pad">
  <p style="font-family:'Fraunces',serif;font-size:19px;margin-bottom:18px">Security &amp; Password</p>

  <?php if (count($errors) > 0) { ?>
    <div class="notice mb-4" style="border-left-color:#c8504b;background:#fdf3f2">
      <p class="notice-title" style="color:#c8504b">Password not changed</p>
      <?php foreach ($errors as $error) { ?>
        <p class="notice-text"><?php echo $error; ?></p>
      <?php } ?>
    </div>
  <?php } ?>

  <form action="profile.php" method="post">
    <div class="row g-3">
      <div class="col-md-4">
        <label class="field-label" for="current">Current Password</label>
        <input class="input-v" type="password" id="current" name="current" placeholder="••••••••">
      </div>
      <div class="col-md-4">
        <label class="field-label" for="new">New Password</label>
        <input class="input-v" type="password" id="new" name="new" placeholder="••••••••">
      </div>
      <div class="col-md-4">
        <label class="field-label" for="confirm">Confirm New Password</label>
        <input class="input-v" type="password" id="confirm" name="confirm" placeholder="••••••••">
      </div>
    </div>

    <div class="text-end mt-4">
      <button class="btn-v btn-green" type="submit">Update Password</button>
    </div>
  </form>
</div>

<?php include 'includes/app-footer.php'; ?>

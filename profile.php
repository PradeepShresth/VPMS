<?php
$page_title = 'My Profile | VPMS';
$active = 'profile';
include 'includes/app-header.php';
?>

<?php if (isset($_GET['name'])) { ?>
  <div class="banner"><i class="bi bi-check-circle-fill"></i>Profile updated.</div>
<?php } elseif (isset($_GET['current'])) { ?>
  <div class="banner"><i class="bi bi-check-circle-fill"></i>Password updated.</div>
<?php } ?>

<h1 class="page-title mb-4">My Profile</h1>

<div class="card-v card-v-pad mb-4">
  <h2 style="font-family:'Fraunces',serif;font-size:25px;font-weight:400;margin-bottom:8px">Pradeep Shrestha</h2>
  <div class="d-flex flex-wrap gap-4" style="font-size:13.5px;color:#6d7880">
    <span><i class="bi bi-envelope me-2"></i>admin@vpms.org</span>
    <span>System Administrator</span>
    <span>Member since Sept 2026</span>
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
        <input class="input-v" type="text" id="fullname" value="Pradeep Shrestha">
      </div>

      <div class="field">
        <label class="field-label" for="email">Email Address</label>
        <input class="input-v" type="email" id="email" value="admin@vpms.org">
      </div>

      <div class="field">
        <label class="field-label" for="phone">Phone Number</label>
        <input class="input-v" type="tel" id="phone" value="+60 12-345 6789">
      </div>

      <div class="field mb-0">
        <label class="field-label" for="org">Organisation / Affiliation</label>
        <input class="input-v" type="text" id="org" value="Green Future">
      </div>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="card-v card-v-pad h-100">
      <p style="font-family:'Fraunces',serif;font-size:19px;margin-bottom:18px">Activity Summary</p>

      <div class="d-flex align-items-center gap-3 mb-3" style="padding:15px 18px;border-radius:8px;background:#eef4fa">
        <span class="flex-grow-1">
          <span class="d-block" style="font-size:14px;font-weight:600">Total Hours Volunteered</span>
          <span class="d-block" style="font-size:12.5px;color:#6d7880">Approved logs only</span>
        </span>
        <span style="font-family:'Fraunces',serif;font-size:23px;color:#16663e">248 hrs</span>
      </div>

      <div class="d-flex align-items-center gap-3 mb-3" style="padding:15px 18px;border-radius:8px;background:#eef4fa">
        <span class="flex-grow-1">
          <span class="d-block" style="font-size:14px;font-weight:600">Events Attended</span>
          <span class="d-block" style="font-size:12.5px;color:#6d7880">Across Selangor &amp; KL</span>
        </span>
        <span style="font-family:'Fraunces',serif;font-size:23px;color:#16663e">32</span>
      </div>

      <div class="d-flex align-items-center gap-3 mb-3" style="padding:15px 18px;border-radius:8px;background:#eef4fa">
        <span class="flex-grow-1">
          <span class="d-block" style="font-size:14px;font-weight:600">Opportunities Joined</span>
          <span class="d-block" style="font-size:12.5px;color:#6d7880">Active involvement</span>
        </span>
        <span style="font-family:'Fraunces',serif;font-size:23px;color:#16663e">14</span>
      </div>

      <div class="d-flex align-items-center gap-3" style="padding:15px 18px;border-radius:8px;background:#eef4fa">
        <span class="flex-grow-1">
          <span class="d-block" style="font-size:14px;font-weight:600">Partnerships Managed</span>
          <span class="d-block" style="font-size:12.5px;color:#6d7880">SDG 17 Core</span>
        </span>
        <span style="font-family:'Fraunces',serif;font-size:23px;color:#16663e">24</span>
      </div>
    </div>
  </div>
</div>

<div class="card-v card-v-pad">
  <p style="font-family:'Fraunces',serif;font-size:19px;margin-bottom:18px">Security &amp; Password</p>

  <form action="profile.php" method="get">
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

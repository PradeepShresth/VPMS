<?php
$page_title = 'Edit Profile | VPMS';
$active = 'profile';
include 'includes/app-header.php';
?>

<div class="col-mid mx-auto">

  <a class="back-link" href="profile.php"><i class="bi bi-arrow-left"></i> Back to Profile</a>

  <h1 class="page-title mb-4">Edit Profile</h1>

  <form action="profile.php" method="get">

    <div class="card-v card-v-pad mb-4">
      <p class="section-label">Profile Photo</p>
      <div class="d-flex align-items-center gap-3">
        <span class="avatar-circle amber" style="width:56px;height:56px;font-size:18px">PS</span>
        <div>
          <button class="btn-v btn-soft btn-sm-v" type="button">Upload new photo</button>
          <p style="margin-top:8px;font-size:12.5px;color:#98a2aa">JPG or PNG, up to 2MB.</p>
        </div>
      </div>
    </div>

    <div class="card-v card-v-pad mb-4">
      <p class="section-label">Personal Information</p>

      <div class="field">
        <label class="field-label" for="name">Full Name</label>
        <input class="input-v" type="text" id="name" name="name" value="Pradeep Shrestha">
      </div>

      <div class="row g-3">
        <div class="col-sm-6">
          <div class="field">
            <label class="field-label" for="email">Email Address</label>
            <input class="input-v" type="email" id="email" name="email" value="admin@vpms.org">
          </div>
        </div>
        <div class="col-sm-6">
          <div class="field">
            <label class="field-label" for="phone">Phone Number</label>
            <input class="input-v" type="tel" id="phone" name="phone" value="+60 12-345 6789">
          </div>
        </div>
      </div>

      <div class="field">
        <label class="field-label" for="org">Organisation / Affiliation</label>
        <input class="input-v" type="text" id="org" name="org" value="Green Future">
      </div>

      <div class="field mb-0">
        <label class="field-label" for="bio">About you</label>
        <textarea class="textarea-v" id="bio" name="bio">Final-year student administrator for the VPMS platform, coordinating partner onboarding and SDG 17 impact reporting.</textarea>
      </div>
    </div>

    <div class="card-v card-v-pad mb-4">
      <p class="section-label">Skills &amp; Interests</p>
      <div class="check-grid" style="grid-template-columns:repeat(2,1fr)">
        <label class="check-v"><input type="checkbox" checked> Physical Fitness</label>
        <label class="check-v"><input type="checkbox" checked> Teamwork</label>
        <label class="check-v"><input type="checkbox"> Environmental Awareness</label>
        <label class="check-v"><input type="checkbox"> First Aid</label>
        <label class="check-v"><input type="checkbox"> Driving Licence</label>
        <label class="check-v"><input type="checkbox"> Photography</label>
      </div>
    </div>

    <div class="card-v card-v-pad mb-4">
      <p class="section-label">Notifications</p>
      <label class="check-v mb-2"><input type="checkbox" checked> Email me about upcoming events I am rostered for</label>
      <label class="check-v mb-2"><input type="checkbox" checked> Email me when an application status changes</label>
      <label class="check-v"><input type="checkbox"> Send me the weekly impact digest</label>
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

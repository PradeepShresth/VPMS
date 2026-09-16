<?php
$page_title = 'Edit Organisation | VPMS';
$active = 'organisations';
include 'includes/app-header.php';
?>

<div class="col-narrow mx-auto">

  <a class="back-link" href="organisation-details.php"><i class="bi bi-arrow-left"></i> Back to Organisation</a>

  <h1 class="page-title mb-4">Edit Organisation</h1>

  <form action="organisation-details.php" method="get">

    <div class="field">
      <label class="field-label" for="name">Organisation Name</label>
      <input class="input-v" type="text" id="name" name="name" value="Green Future NGO">
    </div>

    <div class="field">
      <label class="field-label" for="type">Organisation Type</label>
      <select class="select-v" id="type" name="type">
        <option selected>NGO</option>
        <option>Corporate</option>
        <option>Community Group</option>
        <option>Government Body</option>
        <option>Sponsor / Donor</option>
        <option>Educational Institution</option>
      </select>
    </div>

    <div class="field">
      <label class="field-label" for="reg">Registration Number</label>
      <input class="input-v" type="text" id="reg" name="reg" value="ROS-1234/2024">
    </div>

    <div class="row g-3">
      <div class="col-sm-6">
        <div class="field">
          <label class="field-label" for="country">Country</label>
          <input class="input-v" type="text" id="country" name="country" value="Malaysia">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="field">
          <label class="field-label" for="state">State/Region</label>
          <input class="input-v" type="text" id="state" name="state" value="Selangor">
        </div>
      </div>
    </div>

    <div class="field">
      <label class="field-label" for="city">City</label>
      <input class="input-v" type="text" id="city" name="city" value="Petaling Jaya">
    </div>

    <div class="field">
      <label class="field-label" for="address">Address</label>
      <input class="input-v" type="text" id="address" name="address" value="12, Jalan Gasing">
    </div>

    <div class="field">
      <label class="field-label" for="website">Website URL</label>
      <input class="input-v" type="url" id="website" name="website" value="https://greenfuture.org">
    </div>

    <div class="field mb-4">
      <label class="field-label" for="about">Organisation Description</label>
      <textarea class="textarea-v" id="about" name="about">An environmental NGO running river restoration, coastal clean-up and waste-management programmes across Selangor and Kuala Lumpur since 2014.</textarea>
    </div>

    <div class="row g-3">
      <div class="col-5">
        <a class="btn-v btn-outline btn-block btn-lg-v" href="organisation-details.php">Cancel</a>
      </div>
      <div class="col-7">
        <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Save Changes</button>
      </div>
    </div>
  </form>

</div>

<?php include 'includes/app-footer.php'; ?>

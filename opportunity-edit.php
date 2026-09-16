<?php
$page_title = 'Edit Opportunity | VPMS';
$active = 'opportunities';
include 'includes/app-header.php';
?>

<div class="col-narrow mx-auto">

  <a class="back-link" href="opportunity-details.php"><i class="bi bi-arrow-left"></i> Back to Opportunity</a>

  <h1 class="page-title mb-4">Edit Opportunity</h1>

  <form action="opportunity-details.php" method="get">

    <div class="field">
      <label class="field-label" for="title">Opportunity Title</label>
      <input class="input-v" type="text" id="title" name="title" value="Beach Clean-Up - Bondi Beach">
    </div>

    <div class="field">
      <label class="field-label" for="location">Location</label>
      <input class="input-v" type="text" id="location" name="location" value="Beach Bondi, Sydney">
    </div>

    <div class="field">
      <label class="field-label" for="date">Date</label>
      <input class="input-v" type="date" id="date" name="date" value="2026-09-14">
    </div>

    <div class="field">
      <label class="field-label" for="spots">Volunteer Spots</label>
      <input class="input-v" type="number" id="spots" name="spots" value="30">
      <p style="margin-top:8px;font-size:12.5px;color:#98a2aa">
        18 spots are already filled — the new total cannot be lower.
      </p>
    </div>

    <div class="field">
      <label class="field-label" for="category">Category</label>
      <select class="select-v" id="category" name="category">
        <option selected>Environment</option>
        <option>Education</option>
        <option>Food Security</option>
        <option>Health</option>
        <option>Advocacy</option>
        <option>Technology</option>
      </select>
    </div>

    <div class="field mb-4">
      <label class="field-label" for="description">Description</label>
      <textarea class="textarea-v" id="description" name="description">Join us for a full-day coastal cleanup. We provide gloves, bags, and refreshments. Volunteers earn 6 verified hours and a certificate of participation.</textarea>
    </div>

    <div class="row g-3">
      <div class="col-5">
        <a class="btn-v btn-outline btn-block btn-lg-v" href="opportunity-details.php">Cancel</a>
      </div>
      <div class="col-7">
        <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Save Changes</button>
      </div>
    </div>
  </form>

</div>

<?php include 'includes/app-footer.php'; ?>

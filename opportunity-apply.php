<?php
$page_title = 'Apply | VPMS';
$active = 'opportunities';
include 'includes/app-header.php';
?>

<div class="col-narrow mx-auto">

  <a class="back-link" href="opportunity-details.php?view=volunteer"><i class="bi bi-arrow-left"></i> Back to Opportunity</a>

  <h1 class="page-title">Apply for this Opportunity</h1>
  <p class="page-sub mb-4">Beach Clean-Up - Bondi Beach</p>

  <div class="card-v card-v-pad mb-4">
    <p class="section-label">Green Future NGO</p>
    <p class="mono" style="font-size:12.5px;color:#6d7880">
      14 September 2026 &nbsp;·&nbsp; 6 hrs &nbsp;·&nbsp; Beach Bondi, Sydney
    </p>
  </div>

  <form action="opportunity-applied.php" method="get">

    <div class="field">
      <label class="field-label" for="why">Why do you want to join?</label>
      <textarea class="textarea-v" id="why" name="why"
                placeholder="Tell the coordinator a little about your interest in this project..."></textarea>
    </div>

    <div class="field">
      <span class="field-label">Relevant skills</span>
      <div class="check-grid" style="grid-template-columns:repeat(2,1fr)">
        <label class="check-v"><input type="checkbox" name="skills[]"> Physical Fitness</label>
        <label class="check-v"><input type="checkbox" name="skills[]"> Teamwork</label>
        <label class="check-v"><input type="checkbox" name="skills[]"> Environmental Awareness</label>
        <label class="check-v"><input type="checkbox" name="skills[]"> First Aid</label>
        <label class="check-v"><input type="checkbox" name="skills[]"> Driving Licence</label>
        <label class="check-v"><input type="checkbox" name="skills[]"> Photography</label>
      </div>
    </div>

    <div class="field">
      <label class="field-label" for="time">Availability</label>
      <select class="select-v" id="time" name="time">
        <option>Full day (07:00 – 13:00)</option>
        <option>Morning only (07:00 – 10:00)</option>
        <option>Afternoon only (10:00 – 13:00)</option>
      </select>
    </div>

    <div class="field">
      <label class="field-label" for="contact">Emergency contact</label>
      <input class="input-v" type="text" id="contact" name="contact" placeholder="Name and phone number">
    </div>

    <div class="field mb-4">
      <label class="check-v">
        <input type="checkbox" required>
        I confirm the details above are accurate and I can attend on the date shown.
      </label>
    </div>

    <div class="row g-3">
      <div class="col-5">
        <a class="btn-v btn-outline btn-block btn-lg-v" href="opportunity-details.php?view=volunteer">Cancel</a>
      </div>
      <div class="col-7">
        <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Submit Application</button>
      </div>
    </div>
  </form>

</div>

<?php include 'includes/app-footer.php'; ?>

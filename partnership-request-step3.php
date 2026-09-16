<?php
$page_title = 'Request Partnership | VPMS';
$active = 'partnerships';
include 'includes/app-header.php';
?>

<div class="col-narrow mx-auto">

  <a class="back-link" href="partnership-request-step2.php"><i class="bi bi-arrow-left"></i> Back</a>

  <h1 class="page-title mb-4">Request Partnership</h1>

  <div class="steps">
    <span class="done"></span>
    <span class="done"></span>
    <span class="done"></span>
  </div>

  <p class="section-label">Step 3: Objectives &amp; Justification</p>

  <form action="partnership-requested.php" method="get">

    <div class="field">
      <label class="field-label" for="objectives">Partnership Objectives</label>
      <textarea class="textarea-v" id="objectives" name="objectives"
                placeholder="Describe the goals and expected outcomes of this partnership..."></textarea>
    </div>

    <div class="field mb-4">
      <label class="field-label" for="impact">Expected Impact</label>
      <textarea class="textarea-v" id="impact" name="impact"
                placeholder="Quantify the anticipated community benefit and SDG contributions..."></textarea>
    </div>

    <div class="row g-3">
      <div class="col-sm-5">
        <a class="btn-v btn-outline btn-block btn-lg-v" href="partnership-request-step2.php">&larr; Back</a>
      </div>
      <div class="col-sm-7">
        <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Submit Request</button>
      </div>
    </div>
  </form>

</div>

<?php include 'includes/app-footer.php'; ?>

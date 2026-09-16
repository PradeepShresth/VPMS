<?php
$page_title = 'Request Partnership | VPMS';
$active = 'partnerships';
include 'includes/app-header.php';
?>

<div class="col-narrow mx-auto">

  <a class="back-link" href="partnerships.php"><i class="bi bi-arrow-left"></i> Back</a>

  <h1 class="page-title mb-4">Request Partnership</h1>

  <div class="steps">
    <span class="done"></span>
    <span></span>
    <span></span>
  </div>

  <p class="section-label">Step 1: Select Partner Organisation</p>

  <form action="partnership-request-step2.php" method="get">

    <label class="choice">
      <input type="radio" name="partner" value="Green Future NGO">
      <span class="choice-box">
        <span class="choice-radio"></span>
        <span class="choice-title">Green Future NGO</span>
      </span>
    </label>

    <label class="choice">
      <input type="radio" name="partner" value="TechCorp China">
      <span class="choice-box">
        <span class="choice-radio"></span>
        <span class="choice-title">TechCorp China</span>
      </span>
    </label>

    <label class="choice">
      <input type="radio" name="partner" value="EcoMalaysia Foundation">
      <span class="choice-box">
        <span class="choice-radio"></span>
        <span class="choice-title">EcoMalaysia Foundation</span>
      </span>
    </label>

    <label class="choice">
      <input type="radio" name="partner" value="Befrienders KL">
      <span class="choice-box">
        <span class="choice-radio"></span>
        <span class="choice-title">Befrienders KL</span>
      </span>
    </label>

    <label class="choice">
      <input type="radio" name="partner" value="Global Impact Fund">
      <span class="choice-box">
        <span class="choice-radio"></span>
        <span class="choice-title">Global Impact Fund</span>
      </span>
    </label>

    <button class="btn-v btn-green btn-block btn-lg-v mt-3" type="submit">Continue &rarr;</button>
  </form>

</div>

<?php include 'includes/app-footer.php'; ?>

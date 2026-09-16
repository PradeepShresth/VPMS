<?php
$page_title = 'Beach Clean-Up | VPMS';
$active = 'opportunities';

// show the volunteer version of this page when ?view=volunteer is in the link
$volunteer_view = isset($_GET['view']);

include 'includes/app-header.php';
?>

<div class="col-mid mx-auto">

  <a class="back-link" href="opportunities.php"><i class="bi bi-arrow-left"></i> Back to Opportunities</a>

  <div class="d-flex align-items-start gap-3 mb-1">
    <h1 class="page-title flex-grow-1">Beach Clean-Up - Bondi Beach</h1>
    <span class="badge-v badge-navy mt-2">Open</span>
  </div>

  <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
    <p style="color:#16663e;font-size:14px">
      <i class="bi bi-arrow-left-right"></i> Green Future NGO · Beach Bondi, Sydney
    </p>
    <span class="ms-auto d-flex flex-wrap gap-3">
      <?php if ($volunteer_view) { ?>
        <a class="link-green" style="font-size:14px" href="opportunity-details.php">View as coordinator</a>
      <?php } else { ?>
        <a class="link-green" style="font-size:14px" href="opportunity-details.php?view=volunteer">View as volunteer</a>
        <a class="link-green" style="font-size:14px" href="event-create.php">Convert into an Event</a>
      <?php } ?>
    </span>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="info-tile">
        <span class="t-label">Date</span>
        <span class="t-value">14 September 2026</span>
      </div>
    </div>
    <div class="col-md-4">
      <div class="info-tile">
        <span class="t-label">Hours Required</span>
        <span class="t-value">6 hrs</span>
      </div>
    </div>
    <div class="col-md-4">
      <div class="info-tile">
        <span class="t-label">Category</span>
        <span class="t-value">Environment</span>
      </div>
    </div>
  </div>

  <div class="card-v card-v-pad mb-4">
    <p class="section-label">Volunteer Spots</p>
    <div class="d-flex align-items-center gap-3 mb-2">
      <div class="bar flex-grow-1"><span style="width:60%"></span></div>
      <span class="mono" style="font-size:13px;color:#6d7880;white-space:nowrap">18/30 filled</span>
    </div>
    <p style="font-size:13px;color:#6d7880">12 spots remaining · 18 applications received</p>
  </div>

  <p class="section-label">About this Opportunity</p>
  <p class="mb-4" style="color:#48545e;font-size:14.5px;line-height:1.7">
    Join us for a full-day coastal cleanup. We provide gloves, bags, and refreshments.
    Volunteers earn 6 verified hours and a certificate of participation.
  </p>

  <p class="section-label">Skills Required</p>
  <div class="d-flex flex-wrap gap-2 mb-4">
    <span class="chip">Physical Fitness</span>
    <span class="chip">Teamwork</span>
    <span class="chip">Environmental Awareness</span>
  </div>

  <div class="row g-3">
    <?php if ($volunteer_view) { ?>
      <div class="col-sm-6">
        <a class="btn-v btn-green btn-block" href="opportunity-apply.php">Apply Now</a>
      </div>
      <div class="col-sm-6">
        <a class="btn-v btn-outline btn-block" href="opportunities.php">
          <i class="bi bi-bookmark"></i> Save for later
        </a>
      </div>
    <?php } else { ?>
      <div class="col-sm-6">
        <a class="btn-v btn-green btn-block" href="opportunity-edit.php">Edit Opportunity</a>
      </div>
      <div class="col-sm-6">
        <a class="btn-v btn-outline btn-block" href="opportunity-applications.php">Manage Applications (18)</a>
      </div>
    <?php } ?>
  </div>

</div>

<?php include 'includes/app-footer.php'; ?>

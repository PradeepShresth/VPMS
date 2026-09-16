<?php
$page_title = 'Partnership Details | VPMS';
$active = 'partnerships';

// active, pending or expired - comes from the link on the partnerships page
$status = isset($_GET['status']) ? $_GET['status'] : 'active';

include 'includes/app-header.php';
?>

<div class="col-mid mx-auto">

  <a class="back-link" href="partnerships.php"><i class="bi bi-arrow-left"></i> Back</a>

  <div class="d-flex align-items-start gap-3 mb-1">

    <?php if ($status == 'active') { ?>
      <h1 class="page-title flex-grow-1">Green Future NGO</h1>
      <span class="badge-v badge-navy mt-2">Active</span>
    <?php } elseif ($status == 'pending') { ?>
      <h1 class="page-title flex-grow-1">TechCorp China</h1>
      <span class="badge-v badge-pending mt-2">Pending</span>
    <?php } else { ?>
      <h1 class="page-title flex-grow-1">TechCorp China</h1>
      <span class="mt-2" style="font-size:13.5px;color:#6d7880">Expired</span>
    <?php } ?>

  </div>

  <p class="mb-4" style="color:#16663e;font-size:14px">
    <i class="bi bi-arrow-left-right"></i>
    <?php echo $status == 'active' ? 'TechCorp China' : 'Samaj Sewa Kitchen'; ?>
  </p>

  <div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
      <div class="info-tile left">
        <span class="t-label">Type</span>
        <span class="t-value">
          <?php if ($status == 'active') { echo 'Corporate-NGO'; }
                elseif ($status == 'pending') { echo 'Corporate-Community'; }
                else { echo 'NGO-Government'; } ?>
        </span>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="info-tile left">
        <span class="t-label">Start Date</span>
        <span class="t-value">
          <?php if ($status == 'active') { echo '15 Jan 2025'; }
                elseif ($status == 'pending') { echo '1 Jun 2026'; }
                else { echo '1 Jul 2024'; } ?>
        </span>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="info-tile left">
        <span class="t-label">End Date</span>
        <span class="t-value">
          <?php if ($status == 'expired') { echo '30 Jun 2025'; } else { echo '31 Dec 2026'; } ?>
        </span>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="info-tile left">
        <span class="t-label">SDG Goals</span>
        <span class="t-value">
          <?php if ($status == 'active') { echo 'SDG 13, SDG 15, SDG 17'; }
                elseif ($status == 'pending') { echo 'SDG 2, SDG 17'; }
                else { echo 'SDG 14, SDG 15'; } ?>
        </span>
      </div>
    </div>
  </div>

  <div class="card-v card-v-pad mb-4">
    <p class="section-label">Reported Impact</p>
    <p style="font-size:14px;color:#6d7880">
      <?php if ($status == 'active') { echo '3,200 trees planted, 450 volunteers mobilised'; }
            elseif ($status == 'pending') { echo 'Pending activation'; }
            else { echo '12ha mangrove restored'; } ?>
    </p>
  </div>

  <?php if ($status == 'pending') { ?>
    <div class="row g-3">
      <div class="col-sm-6">
        <a class="btn-v btn-green btn-block btn-lg-v" href="partnerships.php?done=1">Approve Partnership</a>
      </div>
      <div class="col-sm-6">
        <a class="btn-v btn-red btn-block btn-lg-v" href="partnerships.php?done=1">Reject</a>
      </div>
    </div>
  <?php } elseif ($status == 'active') { ?>
    <a class="btn-v btn-soft btn-block btn-lg-v" href="partnership-request-step2.php">Renew Agreement</a>
  <?php } ?>

</div>

<?php include 'includes/app-footer.php'; ?>

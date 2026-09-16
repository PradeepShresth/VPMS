<?php
$page_title = 'Organisation Details | VPMS';
$active = 'organisations';

// verified or pending - comes from the link on the organisations page
$status = isset($_GET['status']) ? $_GET['status'] : 'verified';
$pending = ($status == 'pending');

include 'includes/app-header.php';
?>

<div class="col-mid mx-auto">

  <a class="back-link" href="organisations.php"><i class="bi bi-arrow-left"></i> Back</a>

  <div class="d-flex align-items-start gap-3 mb-1">
    <h1 class="page-title flex-grow-1">
      <?php echo $pending ? 'Blue Shore Initiative' : 'Green Future NGO'; ?>
    </h1>
    <?php if ($pending) { ?>
      <span class="badge-v badge-pending mt-2">Pending</span>
    <?php } else { ?>
      <span class="badge-v badge-navy mt-2">Verified</span>
    <?php } ?>
  </div>

  <p class="mb-4" style="color:#16663e;font-size:14px">
    <?php echo $pending ? 'NGO · Bangsar South, KL' : 'NGO · Petaling Jaya, Selangor'; ?>
  </p>

  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="info-tile">
        <span class="t-label">Members</span>
        <span class="t-value serif"><?php echo $pending ? '320' : '124'; ?></span>
      </div>
    </div>
    <div class="col-md-4">
      <div class="info-tile">
        <span class="t-label">Active Projects</span>
        <span class="t-value serif"><?php echo $pending ? '3' : '18'; ?></span>
      </div>
    </div>
    <div class="col-md-4">
      <div class="info-tile">
        <span class="t-label">Member Since</span>
        <span class="t-value serif"><?php echo $pending ? 'July 2026' : 'January 2024'; ?></span>
      </div>
    </div>
  </div>

  <div class="card-v card-v-pad mb-4">
    <p class="section-label">Verification Documents</p>

    <div class="doc-row">
      <i class="bi bi-file-earmark-text" style="color:#6d7880"></i>
      SSM Registration Certificate
      <a class="link-green ms-auto" style="font-size:13.5px" href="#">Download</a>
    </div>

    <div class="doc-row">
      <i class="bi bi-file-earmark-text" style="color:#6d7880"></i>
      Annual Report 2025
      <a class="link-green ms-auto" style="font-size:13.5px" href="#">Download</a>
    </div>

    <div class="doc-row mb-0">
      <i class="bi bi-file-earmark-text" style="color:#6d7880"></i>
      Audited Financial Statement
      <a class="link-green ms-auto" style="font-size:13.5px" href="#">Download</a>
    </div>
  </div>

  <?php if ($pending) { ?>
    <div class="row g-3">
      <div class="col-sm-6">
        <a class="btn-v btn-green btn-block btn-lg-v" href="organisations.php?done=1">Approve Organisation</a>
      </div>
      <div class="col-sm-6">
        <a class="btn-v btn-red btn-block btn-lg-v" href="organisations.php?done=1">Reject</a>
      </div>
    </div>
  <?php } else { ?>
    <a class="btn-v btn-soft btn-block btn-lg-v" href="organisation-edit.php">Edit Details</a>
  <?php } ?>

</div>

<?php include 'includes/app-footer.php'; ?>

<?php
$page_title = 'Review Applications | VPMS';
$active = 'opportunities';
include 'includes/app-header.php';
?>

<a class="back-link" href="dashboard.php"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>

<?php if (isset($_GET['done'])) { ?>
  <div class="banner"><i class="bi bi-check-circle-fill"></i>Request handled. The applicant has been notified.</div>
<?php } ?>

<div class="head-row">
  <div class="flex-grow-1">
    <h1 class="page-title">Review Applications</h1>
    <p class="page-sub">5 items awaiting review</p>
  </div>
</div>

<div class="d-flex flex-wrap gap-2 mb-4">
  <button class="pill active">All</button>
  <button class="pill">Volunteer applications</button>
  <button class="pill">Account requests</button>
  <button class="pill">Organisation requests</button>
</div>

<div class="card-v card-v-pad mb-3">
  <div class="d-flex flex-wrap align-items-start gap-3">
    <span class="avatar-circle">A</span>
    <span class="flex-grow-1 min-w-0">
      <span class="d-flex flex-wrap align-items-center gap-2 mb-1">
        <span class="row-title">Aruna Tamang</span>
        <span class="chip" style="font-size:12px">Volunteer application</span>
      </span>
      <span class="row-meta d-block">Beach Clean-Up – Bondi Beach</span>
      <span class="mono d-block mt-1" style="font-size:12px;color:#98a2aa">Green Future NGO &nbsp;·&nbsp; submitted 02 Sept</span>
    </span>
    <span class="d-flex gap-2">
      <a class="btn-v btn-green btn-sm-v" href="applications-review.php?done=1">Approve</a>
      <a class="btn-v btn-outline btn-sm-v" href="applications-review.php?done=1">Reject</a>
    </span>
  </div>
</div>

<div class="card-v card-v-pad mb-3">
  <div class="d-flex flex-wrap align-items-start gap-3">
    <span class="avatar-circle">R</span>
    <span class="flex-grow-1 min-w-0">
      <span class="d-flex flex-wrap align-items-center gap-2 mb-1">
        <span class="row-title">Raj Kumar</span>
        <span class="chip" style="font-size:12px">Volunteer application</span>
      </span>
      <span class="row-meta d-block">Beach Clean-Up – Bondi Beach</span>
      <span class="mono d-block mt-1" style="font-size:12px;color:#98a2aa">Green Future NGO &nbsp;·&nbsp; submitted 02 Sept</span>
    </span>
    <span class="d-flex gap-2">
      <a class="btn-v btn-green btn-sm-v" href="applications-review.php?done=1">Approve</a>
      <a class="btn-v btn-outline btn-sm-v" href="applications-review.php?done=1">Reject</a>
    </span>
  </div>
</div>

<div class="card-v card-v-pad mb-3">
  <div class="d-flex flex-wrap align-items-start gap-3">
    <span class="avatar-circle">A</span>
    <span class="flex-grow-1 min-w-0">
      <span class="d-flex flex-wrap align-items-center gap-2 mb-1">
        <span class="row-title">Ahmad Faris</span>
        <span class="chip" style="font-size:12px">Account request</span>
      </span>
      <span class="row-meta d-block">Registering as Volunteer</span>
      <span class="mono d-block mt-1" style="font-size:12px;color:#98a2aa">— &nbsp;·&nbsp; submitted 12 Sept</span>
    </span>
    <span class="d-flex gap-2">
      <a class="btn-v btn-green btn-sm-v" href="applications-review.php?done=1">Approve</a>
      <a class="btn-v btn-outline btn-sm-v" href="applications-review.php?done=1">Reject</a>
    </span>
  </div>
</div>

<div class="card-v card-v-pad mb-3">
  <div class="d-flex flex-wrap align-items-start gap-3">
    <span class="avatar-circle">B</span>
    <span class="flex-grow-1 min-w-0">
      <span class="d-flex flex-wrap align-items-center gap-2 mb-1">
        <span class="row-title">Blue Shore Initiative</span>
        <span class="chip" style="font-size:12px">Organisation request</span>
      </span>
      <span class="row-meta d-block">NGO verification — 3 documents attached</span>
      <span class="mono d-block mt-1" style="font-size:12px;color:#98a2aa">— &nbsp;·&nbsp; submitted 14 Sept</span>
    </span>
    <span class="d-flex gap-2">
      <a class="btn-v btn-green btn-sm-v" href="applications-review.php?done=1">Approve</a>
      <a class="btn-v btn-outline btn-sm-v" href="applications-review.php?done=1">Reject</a>
    </span>
  </div>
</div>

<div class="card-v card-v-pad mb-3">
  <div class="d-flex flex-wrap align-items-start gap-3">
    <span class="avatar-circle">P</span>
    <span class="flex-grow-1 min-w-0">
      <span class="d-flex flex-wrap align-items-center gap-2 mb-1">
        <span class="row-title">Preethi S.</span>
        <span class="chip" style="font-size:12px">Volunteer application</span>
      </span>
      <span class="row-meta d-block">Food Bank Sorting &amp; Distribution</span>
      <span class="mono d-block mt-1" style="font-size:12px;color:#98a2aa">Food Foundation &nbsp;·&nbsp; submitted 28 Aug</span>
    </span>
    <span class="d-flex gap-2">
      <a class="btn-v btn-green btn-sm-v" href="applications-review.php?done=1">Approve</a>
      <a class="btn-v btn-outline btn-sm-v" href="applications-review.php?done=1">Reject</a>
    </span>
  </div>
</div>

<?php include 'includes/app-footer.php'; ?>

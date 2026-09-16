<?php
$page_title = 'Partnerships | VPMS';
$active = 'partnerships';
include 'includes/app-header.php';
?>

<?php if (isset($_GET['done'])) { ?>
  <div class="banner"><i class="bi bi-check-circle-fill"></i>Partnership updated. Both parties have been notified.</div>
<?php } ?>

<div class="head-row">
  <div class="flex-grow-1">
    <h1 class="page-title">Partnerships</h1>
    <p class="page-sub">5 total partnerships on the platform</p>
  </div>
  <a class="btn-v btn-green" href="partnership-request.php">+ Request Partnership</a>
</div>

<div class="d-flex flex-wrap gap-2 mb-4">
  <button class="pill active">All</button>
  <button class="pill">Active</button>
  <button class="pill">Pending</button>
  <button class="pill">Expired</button>
  <button class="pill">Terminated</button>
</div>

<a class="card-v card-v-pad d-block mb-3" href="partnership-details.php?status=active">
  <div class="d-flex align-items-start gap-3 mb-2">
    <span style="font-size:15.5px;font-weight:600">
      Green Future NGO
      <i class="bi bi-arrow-left-right mx-2" style="color:#16663e;font-size:14px"></i>
      TechCorp China
    </span>
    <span class="badge-v badge-navy ms-auto">Active</span>
  </div>
  <div class="d-flex flex-wrap align-items-center gap-3 mb-2">
    <span class="mono" style="font-size:12.5px;color:#6d7880">Corporate-NGO &nbsp;·&nbsp; Jan 2025 - Dec 2026</span>
    <span class="d-flex flex-wrap gap-2 ms-auto">
      <span class="chip chip-green">SDG 13</span>
      <span class="chip chip-green">SDG 15</span>
      <span class="chip chip-green">SDG 17</span>
    </span>
  </div>
  <p style="font-size:13.5px;color:#6d7880">3,200 trees planted, 450 volunteers mobilised</p>
</a>

<a class="card-v card-v-pad d-block mb-3" href="partnership-details.php?status=pending">
  <div class="d-flex align-items-start gap-3 mb-2">
    <span style="font-size:15.5px;font-weight:600">
      TechCorp China
      <i class="bi bi-arrow-left-right mx-2" style="color:#16663e;font-size:14px"></i>
      Lions Club
    </span>
    <span class="badge-v badge-pending ms-auto">Pending</span>
  </div>
  <div class="d-flex flex-wrap align-items-center gap-3 mb-2">
    <span class="mono" style="font-size:12.5px;color:#6d7880">Corporate-Community &nbsp;·&nbsp; Jun 2026 - Dec 2026</span>
    <span class="d-flex flex-wrap gap-2 ms-auto">
      <span class="chip chip-green">SDG 2</span>
      <span class="chip chip-green">SDG 17</span>
    </span>
  </div>
  <p style="font-size:13.5px;color:#6d7880">Pending activation</p>
</a>

<a class="card-v card-v-pad d-block mb-3" href="partnership-details.php?status=expired">
  <div class="d-flex align-items-start gap-3 mb-2">
    <span style="font-size:15.5px;font-weight:600">
      TechCorp China
      <i class="bi bi-arrow-left-right mx-2" style="color:#16663e;font-size:14px"></i>
      Lions Club
    </span>
    <span class="badge-v badge-grey ms-auto">Expired</span>
  </div>
  <div class="d-flex flex-wrap align-items-center gap-3 mb-2">
    <span class="mono" style="font-size:12.5px;color:#6d7880">Corporate-Community &nbsp;·&nbsp; Jun 2026 - Dec 2026</span>
    <span class="d-flex flex-wrap gap-2 ms-auto">
      <span class="chip chip-green">SDG 2</span>
      <span class="chip chip-green">SDG 17</span>
    </span>
  </div>
  <p style="font-size:13.5px;color:#6d7880">Pending activation</p>
</a>

<?php include 'includes/app-footer.php'; ?>

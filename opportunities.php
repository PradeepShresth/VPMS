<?php
$page_title = 'Opportunities | VPMS';
$active = 'opportunities';
include 'includes/app-header.php';
?>

<div class="head-row">
  <div class="flex-grow-1">
    <h1 class="page-title">Volunteer Opportunities</h1>
    <p class="page-sub">6 opportunities available</p>
  </div>
  <a class="btn-v btn-green" href="opportunity-create.php">+ Post Opportunity</a>
</div>

<!-- search and filters -->
<div class="d-flex flex-wrap align-items-center gap-3 mb-4">
  <input type="search" class="input-v" style="flex:1 1 320px;max-width:600px" placeholder="Search opportunities...">
  <div class="d-flex flex-wrap gap-2">
    <button class="pill active">All</button>
    <button class="pill">Environment</button>
    <button class="pill">Education</button>
    <button class="pill">Food Security</button>
    <button class="pill">Health</button>
    <button class="pill">Advocacy</button>
    <button class="pill">Technology</button>
  </div>
</div>

<div class="row g-3">

  <div class="col-xl-5 col-lg-6">
    <a class="card-v card-v-pad d-block h-100" href="opportunity-details.php">
      <div class="d-flex align-items-center gap-2 mb-3">
        <span class="chip">Environment</span>
        <span class="badge-v badge-navy ms-auto">Open</span>
      </div>

      <h2 class="row-title mb-1" style="font-size:16px">Beach Clean-Up – Bondi Beach</h2>
      <p class="row-meta mb-3" style="color:#16663e">Green Future NGO · Bondi Beach, Sydney</p>
      <p class="mono mb-3" style="font-size:12.5px;color:#6d7880">14 Sept &nbsp;·&nbsp; 6h</p>

      <div class="bar-row">
        <span style="color:#6d7880">Spots filled</span>
        <span class="count">18/30</span>
      </div>
      <div class="bar mb-3"><span style="width:60%"></span></div>

      <div class="d-flex flex-wrap gap-2">
        <span class="chip" style="font-size:12px">Physical Fitness</span>
        <span class="chip" style="font-size:12px">Teamwork</span>
        <span class="chip" style="font-size:12px">+1</span>
      </div>
    </a>
  </div>

  <div class="col-xl-5 col-lg-6">
    <a class="card-v card-v-pad d-block h-100" href="opportunity-details.php">
      <div class="d-flex align-items-center gap-2 mb-3">
        <span class="chip">Environment</span>
        <span class="badge-v badge-navy ms-auto">Open</span>
      </div>

      <h2 class="row-title mb-1" style="font-size:16px">Food Bank Sorting &amp; Distribution</h2>
      <p class="row-meta mb-3" style="color:#16663e">Food Foundation · Kapan, Kathmandu</p>
      <p class="mono mb-3" style="font-size:12.5px;color:#6d7880">12 Oct &nbsp;·&nbsp; 5h</p>

      <div class="bar-row">
        <span style="color:#6d7880">Spots filled</span>
        <span class="count">40/40</span>
      </div>
      <div class="bar full mb-3"><span style="width:100%"></span></div>

      <div class="d-flex flex-wrap gap-2">
        <span class="chip" style="font-size:12px">Physical Fitness</span>
        <span class="chip" style="font-size:12px">Environmental Interest</span>
      </div>
    </a>
  </div>

</div>

<?php include 'includes/app-footer.php'; ?>

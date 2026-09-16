<?php
$page_title = 'Reports & Analytics | VPMS';
$active = 'reports';
include 'includes/app-header.php';
?>

<?php if (isset($_GET['type'])) { ?>
  <div class="banner"><i class="bi bi-check-circle-fill"></i>Report queued — it will appear below once processing finishes.</div>
<?php } ?>

<div class="head-row">
  <div class="flex-grow-1">
    <h1 class="page-title">Reports &amp; Analytics</h1>
    <p class="page-sub">Generate, download, and manage impact reports</p>
  </div>
  <a class="btn-v btn-green" href="report-generate.php">+ Generate Report</a>
</div>

<div class="row g-3 mb-4">
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <span class="stat-value">38,640</span>
      <span class="stat-label">Volunteer Hours</span>
      <span class="stat-note">Q3 2026</span>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <span class="stat-value">67</span>
      <span class="stat-label">Projects Active</span>
      <span class="stat-note">Across 7 categories</span>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <span class="stat-value">24</span>
      <span class="stat-label">Partnerships</span>
      <span class="stat-note">Active agreements</span>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <span class="stat-value">82,400</span>
      <span class="stat-label">Beneficiaries</span>
      <span class="stat-note">Lives impacted</span>
    </div>
  </div>
</div>

<p class="section-label">Recent Reports</p>

<div class="list-card mb-4">

  <div class="list-row">
    <span class="row-icon"><i class="bi bi-bar-chart-fill"></i></span>
    <span class="flex-grow-1">
      <span class="row-title d-block">Volunteer Activity Report — Q3 2026</span>
      <span class="row-meta mono d-block">UC45 &nbsp;·&nbsp; 2026-08-20 &nbsp;·&nbsp; 2.4 MB</span>
    </span>
    <button class="btn-v btn-soft btn-sm-v">Download</button>
  </div>

  <div class="list-row">
    <span class="row-icon"><i class="bi bi-bar-chart-fill"></i></span>
    <span class="flex-grow-1">
      <span class="row-title d-block">Partnership Performance Report — H1 2026</span>
      <span class="row-meta mono d-block">UC46 &nbsp;·&nbsp; 2026-08-15 &nbsp;·&nbsp; 3.1 MB</span>
    </span>
    <button class="btn-v btn-soft btn-sm-v">Download</button>
  </div>

  <div class="list-row">
    <span class="row-icon"><i class="bi bi-bar-chart-fill"></i></span>
    <span class="flex-grow-1">
      <span class="row-title d-block">Community Impact Report — July 2026</span>
      <span class="row-meta mono d-block">UC48 &nbsp;·&nbsp; 2026-08-01 &nbsp;·&nbsp; 5.7 MB</span>
    </span>
    <button class="btn-v btn-soft btn-sm-v">Download</button>
  </div>

  <div class="list-row">
    <span class="row-icon"><i class="bi bi-bar-chart-fill"></i></span>
    <span class="flex-grow-1">
      <span class="row-title d-block">Sponsor Contribution Report — FY2026</span>
      <span class="row-meta mono d-block">UC49 &nbsp;·&nbsp; 2026-07-31 &nbsp;·&nbsp; 1.8 MB</span>
    </span>
    <button class="btn-v btn-soft btn-sm-v">Download</button>
  </div>

  <div class="list-row">
    <span class="row-icon"><i class="bi bi-bar-chart-fill"></i></span>
    <span class="flex-grow-1">
      <span class="row-title d-block">Project Progress Report — Green Future NGO</span>
      <span class="row-meta mono d-block">UC47 &nbsp;·&nbsp; Processing... &nbsp;·&nbsp; —</span>
    </span>
    <span class="badge-v badge-pending">Processing</span>
  </div>

</div>

<div class="card-v card-v-pad">
  <p class="section-label">Volunteer Hours — 2026</p>

  <div class="mini-chart">
    <div class="mini-col">
      <div class="mini-val">2.8k</div>
      <div class="mini-bar" style="height:53px"></div>
      <div class="mini-lab">Jan</div>
    </div>
    <div class="mini-col">
      <div class="mini-val">3.2k</div>
      <div class="mini-bar" style="height:61px"></div>
      <div class="mini-lab">Feb</div>
    </div>
    <div class="mini-col">
      <div class="mini-val">3.6k</div>
      <div class="mini-bar" style="height:68px"></div>
      <div class="mini-lab">Mar</div>
    </div>
    <div class="mini-col">
      <div class="mini-val">4.1k</div>
      <div class="mini-bar" style="height:78px"></div>
      <div class="mini-lab">Apr</div>
    </div>
    <div class="mini-col">
      <div class="mini-val">3.8k</div>
      <div class="mini-bar" style="height:72px"></div>
      <div class="mini-lab">May</div>
    </div>
    <div class="mini-col">
      <div class="mini-val">4.4k</div>
      <div class="mini-bar" style="height:83px"></div>
      <div class="mini-lab">Jun</div>
    </div>
    <div class="mini-col">
      <div class="mini-val">5.2k</div>
      <div class="mini-bar" style="height:99px"></div>
      <div class="mini-lab">Jul</div>
    </div>
    <div class="mini-col">
      <div class="mini-val">5.8k</div>
      <div class="mini-bar" style="height:110px"></div>
      <div class="mini-lab">Aug</div>
    </div>
  </div>
</div>

<?php include 'includes/app-footer.php'; ?>

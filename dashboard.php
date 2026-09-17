<?php
$page_title = 'Dashboard | VPMS';
$active = 'dashboard';

require 'includes/auth.php';

include 'includes/app-header.php';
?>

<div class="mb-4">
  <h1 class="page-title">Good morning, Dr.</h1>
  <p class="page-sub">Platform overview — all systems operational.</p>
</div>

<!-- top numbers -->
<div class="row g-3 mb-4">
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <span class="stat-value">4,820</span>
      <span class="stat-label">Total Volunteers</span>
      <span class="stat-note">+124 this month</span>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <span class="stat-value">142</span>
      <span class="stat-label">Organisations</span>
      <span class="stat-note">8 pending verification</span>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <span class="stat-value">24</span>
      <span class="stat-label">Active Partnerships</span>
      <span class="stat-note">3 pending approval</span>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <span class="stat-value">67</span>
      <span class="stat-label">Active Projects</span>
      <span class="stat-note">Across 7 modules</span>
    </div>
  </div>
</div>

<div class="row g-4 mb-4">

  <!-- recent opportunities -->
  <div class="col-lg-7">
    <div class="d-flex align-items-center mb-3">
      <span class="section-label mb-0">Recent Opportunities</span>
      <a class="ms-auto link-green" style="font-size:13.5px" href="opportunities.php">View all &rarr;</a>
    </div>

    <div class="list-card h-100">
      <a class="list-row" href="opportunity-details.php">
        <span class="row-icon"><i class="bi bi-diamond"></i></span>
        <span class="flex-grow-1 min-w-0">
          <span class="row-title d-block">Beach Clean-Up Drive — Bondi Junction</span>
          <span class="row-meta d-block" style="color:#16663e">Green Future NGO · Bronte Road, Bondi Junction</span>
        </span>
        <span class="text-end">
          <span class="badge-v badge-navy d-block mb-2">Open</span>
          <span class="mono" style="font-size:12.5px;color:#6d7880">12/30 spots</span>
        </span>
      </a>
      <a class="list-row" href="opportunity-details.php">
        <span class="row-icon"><i class="bi bi-diamond"></i></span>
        <span class="flex-grow-1 min-w-0">
          <span class="row-title d-block">Food Bank Sorting &amp; Distribution</span>
          <span class="row-meta d-block" style="color:#16663e">Kalanki Community Kitchen · Patan, Lalitpur</span>
        </span>
        <span class="text-end">
          <span class="badge-v badge-navy d-block mb-2">Open</span>
          <span class="mono" style="font-size:12.5px;color:#6d7880">20/25 spots</span>
        </span>
      </a>
      <div class="list-row" style="min-height:74px"></div>
    </div>
  </div>

  <!-- upcoming events -->
  <div class="col-lg-5">
    <div class="d-flex align-items-center mb-3">
      <span class="section-label mb-0">Upcoming Events</span>
      <a class="ms-auto link-green" style="font-size:13.5px" href="events.php">View all &rarr;</a>
    </div>

    <a class="card-v card-v-pad d-block mb-3" href="event-details.php">
      <div class="d-flex align-items-start gap-2 mb-2">
        <span class="row-title">Food Distribution in Manang</span>
        <span class="badge-v badge-blue ms-auto">Upcoming</span>
      </div>
      <p class="mono mb-3" style="font-size:12.5px;color:#6d7880">7 Sept &nbsp;·&nbsp; 07:30 AM</p>
      <div class="d-flex align-items-center gap-3">
        <div class="bar flex-grow-1"><span style="width:88%"></span></div>
        <span class="mono" style="font-size:12.5px;color:#6d7880">22/25</span>
      </div>
    </a>

    <a class="card-v card-v-pad d-block mb-3" href="event-details.php">
      <div class="d-flex align-items-start gap-2 mb-2">
        <span class="row-title">Cleaning Riverbank</span>
        <span class="badge-v badge-blue ms-auto">Upcoming</span>
      </div>
      <p class="mono mb-3" style="font-size:12.5px;color:#6d7880">13 Sept &nbsp;·&nbsp; 09:00 AM</p>
      <div class="d-flex align-items-center gap-3">
        <div class="bar flex-grow-1"><span style="width:67%"></span></div>
        <span class="mono" style="font-size:12.5px;color:#6d7880">8/12</span>
      </div>
    </a>
  </div>
</div>

<div class="row g-4 mb-4">

  <!-- partnerships -->
  <div class="col-lg-6">
    <div class="d-flex align-items-center mb-3">
      <span class="section-label mb-0">Active Partnerships</span>
      <a class="ms-auto link-green" style="font-size:13.5px" href="partnerships.php">View all &rarr;</a>
    </div>

    <div class="list-card h-100">
      <a class="list-row align-items-start" href="partnership-details.php">
        <span class="flex-grow-1">
          <span class="row-title d-block">Green Future NGO</span>
          <span class="row-meta d-block" style="color:#16663e">
            <i class="bi bi-arrow-left-right"></i> TechCorp China
          </span>
          <span class="d-flex flex-wrap gap-2 mt-2">
            <span class="chip chip-mono">SDG 13</span>
            <span class="chip chip-mono">SDG 15</span>
            <span class="chip chip-mono">SDG 17</span>
          </span>
        </span>
        <span class="badge-v badge-navy">Active</span>
      </a>

      <a class="list-row align-items-start" href="partnership-details.php">
        <span class="flex-grow-1">
          <span class="row-title d-block">UN Bagmati Office</span>
          <span class="row-meta d-block" style="color:#16663e">
            <i class="bi bi-arrow-left-right"></i> Global Impact Fund
          </span>
          <span class="d-flex flex-wrap gap-2 mt-2">
            <span class="chip chip-mono">SDG 4</span>
            <span class="chip chip-mono">SDG 17</span>
          </span>
        </span>
        <span class="badge-v badge-navy">Active</span>
      </a>

      <div class="list-row" style="min-height:120px"></div>
    </div>
  </div>

  <!-- sdg goals -->
  <div class="col-lg-6">
    <div class="d-flex align-items-center mb-3">
      <span class="section-label mb-0">SDG Goals Covered</span>
      <a class="ms-auto link-green" style="font-size:13.5px" href="impact.php">SDG Dashboard &rarr;</a>
    </div>

    <div class="card-v card-v-pad h-100">
      <div class="sdg-grid mb-3" style="grid-template-columns:repeat(4,1fr)">
        <div class="sdg-tile" style="background:#e5243b"><span class="s-word">SDG</span><span class="s-num">01</span></div>
        <div class="sdg-tile" style="background:#dda63a"><span class="s-word">SDG</span><span class="s-num">02</span></div>
        <div class="sdg-tile" style="background:#4c9f38"><span class="s-word">SDG</span><span class="s-num">03</span></div>
        <div class="sdg-tile" style="background:#c5192d"><span class="s-word">SDG</span><span class="s-num">04</span></div>
        <div class="sdg-tile" style="background:#3f7e44"><span class="s-word">SDG</span><span class="s-num">13</span></div>
        <div class="sdg-tile" style="background:#0a97d9"><span class="s-word">SDG</span><span class="s-num">14</span></div>
        <div class="sdg-tile" style="background:#56c02b"><span class="s-word">SDG</span><span class="s-num">15</span></div>
        <div class="sdg-tile" style="background:#19486a"><span class="s-word">SDG</span><span class="s-num">17</span></div>
      </div>
      <p class="text-center" style="font-size:12.5px;color:#6d7880">
        Platform activities contribute to 8 of 17 Global Goals
      </p>
    </div>
  </div>
</div>

<!-- quick actions -->
<div class="card-v card-v-pad">
  <p class="section-label">Quick Actions</p>
  <div class="d-flex flex-wrap gap-2">
    <a class="btn-v btn-green" href="users.php">Manage Users</a>
    <a class="btn-v btn-soft" href="applications-review.php">Review Applications</a>
    <a class="btn-v btn-soft" href="organisations.php">Approve Organisations</a>
    <a class="btn-v btn-soft" href="report-generate.php">Generate Report</a>
  </div>
</div>

<?php include 'includes/app-footer.php'; ?>

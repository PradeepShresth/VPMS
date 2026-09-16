<?php
$page_title = 'Events & Attendance | VPMS';
$active = 'events';
include 'includes/app-header.php';
?>

<div class="head-row">
  <div class="flex-grow-1">
    <h1 class="page-title">Events &amp; Attendance</h1>
    <p class="page-sub">5 total events</p>
  </div>
  <a class="btn-v btn-green" href="event-create.php">+ Create Event</a>
</div>

<!-- happening today -->
<p class="section-label mt-4">
  <span style="display:inline-block;width:9px;height:9px;margin-right:6px;border-radius:50%;border:2px solid #98a2aa"></span>
  Live Now
</p>

<div class="row g-3">
  <div class="col-xl-5 col-lg-6">
    <a class="card-v card-v-pad d-block" href="event-details.php">
      <div class="d-flex align-items-center gap-2 mb-3">
        <span class="chip">Disaster Relief</span>
        <span class="badge-v badge-navy ms-auto">Ongoing</span>
      </div>
      <h2 class="row-title mb-1" style="font-size:16px">Emergency Food Distribution – Flood Relief</h2>
      <p class="row-meta mb-3" style="color:#16663e">Green Future NGO · Rockdale, Sydney</p>
      <p class="mono mb-3" style="font-size:12.5px;color:#6d7880">30 Aug &nbsp;·&nbsp; 06:00 AM</p>
      <div class="bar-row">
        <span style="color:#6d7880">Volunteers</span>
        <span class="count">45/40</span>
      </div>
      <div class="bar over"><span style="width:100%"></span></div>
    </a>
  </div>
</div>

<!-- coming up -->
<p class="section-label mt-4">Upcoming Events</p>

<div class="row g-3">
  <div class="col-xl-5 col-lg-6">
    <a class="card-v card-v-pad d-block" href="event-details.php">
      <div class="d-flex align-items-center gap-2 mb-3">
        <span class="chip">Community Service</span>
        <span class="badge-v badge-blue ms-auto">Upcoming</span>
      </div>
      <h2 class="row-title mb-1" style="font-size:16px">Emergency Food Distribution – Flood Relief</h2>
      <p class="row-meta mb-3" style="color:#16663e">Green Future NGO · Rockdale, Sydney</p>
      <p class="mono mb-3" style="font-size:12.5px;color:#6d7880">7 Sept &nbsp;·&nbsp; 07:30 AM</p>
      <div class="bar-row">
        <span style="color:#6d7880">Volunteers</span>
        <span class="count">22/25</span>
      </div>
      <div class="bar"><span style="width:88%"></span></div>
    </a>
  </div>
</div>

<!-- finished -->
<p class="section-label mt-4">Past Events</p>

<div class="row g-3">
  <div class="col-xl-5 col-lg-6">
    <a class="card-v card-v-pad d-block" href="event-details.php">
      <div class="d-flex align-items-center gap-2 mb-3">
        <span class="chip">Technology</span>
        <span class="badge-v badge-grey ms-auto">Completed</span>
      </div>
      <h2 class="row-title mb-1" style="font-size:16px">Emergency Food Distribution – Flood Relief</h2>
      <p class="row-meta mb-3" style="color:#16663e">Green Future NGO · Rockdale, Sydney</p>
      <p class="mono mb-3" style="font-size:12.5px;color:#6d7880">22 Aug &nbsp;·&nbsp; 08:00 AM</p>
      <div class="bar-row">
        <span style="color:#6d7880">Volunteers</span>
        <span class="count">30/30</span>
      </div>
      <div class="bar over"><span style="width:100%"></span></div>
    </a>
  </div>
</div>

<?php include 'includes/app-footer.php'; ?>

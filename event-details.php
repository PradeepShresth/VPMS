<?php
$page_title = 'Event Details | VPMS';
$active = 'events';
include 'includes/app-header.php';
?>

<div class="col-mid mx-auto">

  <a class="back-link" href="events.php"><i class="bi bi-arrow-left"></i> Back</a>

  <div class="d-flex align-items-start gap-3 mb-1">
    <h1 class="page-title flex-grow-1">Emergency Food Distribution - Flood Relief</h1>
    <span class="badge-v badge-blue mt-2">Upcoming</span>
  </div>

  <p class="mb-4" style="color:#16663e;font-size:14px">Green Future NGO · Rockdale, Sydney</p>

  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="info-tile">
        <span class="t-label">Date</span>
        <span class="t-value">Monday, 7 September</span>
      </div>
    </div>
    <div class="col-md-4">
      <div class="info-tile">
        <span class="t-label">Time</span>
        <span class="t-value">07:30 AM</span>
      </div>
    </div>
    <div class="col-md-4">
      <div class="info-tile">
        <span class="t-label">Category</span>
        <span class="t-value">Community Service</span>
      </div>
    </div>
  </div>

  <div class="card-v card-v-pad mb-4">
    <div class="d-flex align-items-center mb-2">
      <span class="section-label mb-0">Volunteer Roster</span>
      <span class="mono ms-auto" style="font-size:13px;color:#6d7880">22/25</span>
    </div>

    <div class="bar mb-3"><span style="width:88%"></span></div>

    <div class="d-flex flex-wrap gap-2">
      <span class="chip">Aruna</span>
      <span class="chip">Raj Kumar</span>
      <span class="chip">Anil</span>
      <span class="chip">Ahmad Faris</span>
      <span class="chip">Preethi S.</span>
      <span class="chip">Lim Wei Jie</span>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-sm-6">
      <a class="btn-v btn-green btn-block" href="event-attendance.php">Record Attendance</a>
    </div>
    <div class="col-sm-6">
      <a class="btn-v btn-soft btn-block" href="event-volunteers.php">Manage Volunteers</a>
    </div>
  </div>

</div>

<?php include 'includes/app-footer.php'; ?>

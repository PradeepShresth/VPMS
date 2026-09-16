<?php
$page_title = 'Record Attendance | VPMS';
$active = 'events';
include 'includes/app-header.php';
?>

<div class="col-narrow mx-auto">

  <a class="back-link" href="event-details.php"><i class="bi bi-arrow-left"></i> Back to Event</a>

  <h1 class="page-title">Record Attendance</h1>
  <p class="page-sub mb-4">Gotong-Royong Chow Kit 2026</p>

  <div class="notice mb-4">
    <p class="notice-title">QR Scan Mode Active</p>
    <p class="notice-text">
      Tap a volunteer to mark present — in production, this would scan their VPMS QR badge.
    </p>
  </div>

  <button class="card-v card-v-pad d-flex align-items-center gap-3 w-100 mb-3 roster-row" style="text-align:left">
    <span class="avatar-circle grey">A</span>
    <span style="font-size:14.5px;font-weight:500">Aruna</span>
    <span class="ms-auto tick" style="display:none;color:#16663e;font-size:18px"><i class="bi bi-check-circle-fill"></i></span>
  </button>

  <button class="card-v card-v-pad d-flex align-items-center gap-3 w-100 mb-3 roster-row" style="text-align:left">
    <span class="avatar-circle grey">R</span>
    <span style="font-size:14.5px;font-weight:500">Raj Kumar</span>
    <span class="ms-auto tick" style="display:none;color:#16663e;font-size:18px"><i class="bi bi-check-circle-fill"></i></span>
  </button>

  <button class="card-v card-v-pad d-flex align-items-center gap-3 w-100 mb-3 roster-row" style="text-align:left">
    <span class="avatar-circle grey">A</span>
    <span style="font-size:14.5px;font-weight:500">Anil</span>
    <span class="ms-auto tick" style="display:none;color:#16663e;font-size:18px"><i class="bi bi-check-circle-fill"></i></span>
  </button>

  <button class="card-v card-v-pad d-flex align-items-center gap-3 w-100 mb-3 roster-row" style="text-align:left">
    <span class="avatar-circle grey">A</span>
    <span style="font-size:14.5px;font-weight:500">Ahmad Faris</span>
    <span class="ms-auto tick" style="display:none;color:#16663e;font-size:18px"><i class="bi bi-check-circle-fill"></i></span>
  </button>

  <button class="card-v card-v-pad d-flex align-items-center gap-3 w-100 mb-3 roster-row" style="text-align:left">
    <span class="avatar-circle grey">P</span>
    <span style="font-size:14.5px;font-weight:500">Preethi S.</span>
    <span class="ms-auto tick" style="display:none;color:#16663e;font-size:18px"><i class="bi bi-check-circle-fill"></i></span>
  </button>

  <button class="card-v card-v-pad d-flex align-items-center gap-3 w-100 mb-3 roster-row" style="text-align:left">
    <span class="avatar-circle grey">L</span>
    <span style="font-size:14.5px;font-weight:500">Lim Wei Jie</span>
    <span class="ms-auto tick" style="display:none;color:#16663e;font-size:18px"><i class="bi bi-check-circle-fill"></i></span>
  </button>

  <a class="btn-v btn-green btn-block btn-lg-v" id="submitBtn" href="attendance-submitted.php?marked=0">
    Submit Attendance (<span id="count">0</span>/6)
  </a>

</div>

<script>
// tap a row to mark that volunteer present
var rows = document.querySelectorAll('.roster-row');
var count = document.getElementById('count');
var submitBtn = document.getElementById('submitBtn');
var marked = 0;

for (var i = 0; i < rows.length; i++) {
  rows[i].onclick = function () {
    var tick = this.querySelector('.tick');

    if (tick.style.display == 'none') {
      tick.style.display = 'inline';
      this.style.borderColor = '#16663e';
      marked = marked + 1;
    } else {
      tick.style.display = 'none';
      this.style.borderColor = '';
      marked = marked - 1;
    }

    count.innerHTML = marked;
    submitBtn.href = 'attendance-submitted.php?marked=' + marked;
  };
}
</script>

<?php include 'includes/app-footer.php'; ?>

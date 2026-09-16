<?php
$page_title = 'Manage Volunteers | VPMS';
$active = 'events';
include 'includes/app-header.php';
?>

<a class="back-link" href="event-details.php"><i class="bi bi-arrow-left"></i> Back to Event</a>

<?php if (isset($_GET['done'])) { ?>
  <div class="banner"><i class="bi bi-check-circle-fill"></i>Roster updated.</div>
<?php } ?>

<div class="head-row">
  <div class="flex-grow-1">
    <h1 class="page-title">Manage Volunteers</h1>
    <p class="page-sub">Emergency Food Distribution - Flood Relief</p>
  </div>
</div>

<div class="card-v card-v-pad mb-4">
  <div class="d-flex align-items-center mb-2">
    <span class="section-label mb-0">Roster capacity</span>
    <span class="mono ms-auto" style="font-size:13px;color:#6d7880">22/25</span>
  </div>
  <div class="bar"><span style="width:88%"></span></div>
</div>

<div class="card-v card-v-pad mb-4">
  <p class="section-label">Add a volunteer</p>
  <form class="d-flex flex-wrap gap-3" action="event-volunteers.php" method="get">
    <input class="input-v" style="flex:1 1 280px" type="search" name="q"
           placeholder="Search registered volunteers by name or email">
    <input type="hidden" name="done" value="1">
    <button class="btn-v btn-green" type="submit">+ Add to Roster</button>
  </form>
</div>

<div class="list-card">
  <div class="table-responsive">
    <table class="table-v">
      <tr>
        <th>Volunteer</th>
        <th>Role</th>
        <th>Contact</th>
        <th>Status</th>
        <th></th>
      </tr>

      <tr>
        <td>
          <span class="d-flex align-items-center gap-3">
            <span class="avatar-circle grey">A</span>
            <span class="fw-bold">Aruna</span>
          </span>
        </td>
        <td class="td-muted">Team Lead</td>
        <td class="td-muted mono" style="font-size:12.5px">aruna.t@mail.com</td>
        <td><span class="badge-v badge-navy">Confirmed</span></td>
        <td class="text-end">
          <a class="btn-v btn-outline btn-sm-v" href="event-volunteers.php?done=1">Remove</a>
        </td>
      </tr>

      <tr>
        <td>
          <span class="d-flex align-items-center gap-3">
            <span class="avatar-circle grey">R</span>
            <span class="fw-bold">Raj Kumar</span>
          </span>
        </td>
        <td class="td-muted">Volunteer</td>
        <td class="td-muted mono" style="font-size:12.5px">raj.k@mail.com</td>
        <td><span class="badge-v badge-navy">Confirmed</span></td>
        <td class="text-end">
          <a class="btn-v btn-outline btn-sm-v" href="event-volunteers.php?done=1">Remove</a>
        </td>
      </tr>

      <tr>
        <td>
          <span class="d-flex align-items-center gap-3">
            <span class="avatar-circle grey">A</span>
            <span class="fw-bold">Anil</span>
          </span>
        </td>
        <td class="td-muted">Volunteer</td>
        <td class="td-muted mono" style="font-size:12.5px">anil.d@mail.com</td>
        <td><span class="badge-v badge-navy">Confirmed</span></td>
        <td class="text-end">
          <a class="btn-v btn-outline btn-sm-v" href="event-volunteers.php?done=1">Remove</a>
        </td>
      </tr>

      <tr>
        <td>
          <span class="d-flex align-items-center gap-3">
            <span class="avatar-circle grey">A</span>
            <span class="fw-bold">Ahmad Faris</span>
          </span>
        </td>
        <td class="td-muted">Volunteer</td>
        <td class="td-muted mono" style="font-size:12.5px">ahmad.f@mail.com</td>
        <td><span class="badge-v badge-navy">Confirmed</span></td>
        <td class="text-end">
          <a class="btn-v btn-outline btn-sm-v" href="event-volunteers.php?done=1">Remove</a>
        </td>
      </tr>

      <tr>
        <td>
          <span class="d-flex align-items-center gap-3">
            <span class="avatar-circle grey">P</span>
            <span class="fw-bold">Preethi S.</span>
          </span>
        </td>
        <td class="td-muted">Volunteer</td>
        <td class="td-muted mono" style="font-size:12.5px">preethi.s@mail.com</td>
        <td><span class="badge-v badge-pending">Waitlist</span></td>
        <td class="text-end">
          <span class="d-flex gap-2 justify-content-end">
            <a class="btn-v btn-green btn-sm-v" href="event-volunteers.php?done=1">Confirm</a>
            <a class="btn-v btn-outline btn-sm-v" href="event-volunteers.php?done=1">Remove</a>
          </span>
        </td>
      </tr>

      <tr>
        <td>
          <span class="d-flex align-items-center gap-3">
            <span class="avatar-circle grey">L</span>
            <span class="fw-bold">Lim Wei Jie</span>
          </span>
        </td>
        <td class="td-muted">Volunteer</td>
        <td class="td-muted mono" style="font-size:12.5px">lim.wj@mail.com</td>
        <td><span class="badge-v badge-pending">Waitlist</span></td>
        <td class="text-end">
          <span class="d-flex gap-2 justify-content-end">
            <a class="btn-v btn-green btn-sm-v" href="event-volunteers.php?done=1">Confirm</a>
            <a class="btn-v btn-outline btn-sm-v" href="event-volunteers.php?done=1">Remove</a>
          </span>
        </td>
      </tr>

    </table>
  </div>
</div>

<?php include 'includes/app-footer.php'; ?>

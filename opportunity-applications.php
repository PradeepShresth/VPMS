<?php
$page_title = 'Applications | VPMS';
$active = 'opportunities';
include 'includes/app-header.php';
?>

<a class="back-link" href="opportunity-details.php"><i class="bi bi-arrow-left"></i> Back to Opportunity</a>

<?php if (isset($_GET['done'])) { ?>
  <div class="banner"><i class="bi bi-check-circle-fill"></i>Application updated. The volunteer has been notified.</div>
<?php } ?>

<div class="head-row">
  <div class="flex-grow-1">
    <h1 class="page-title">Applications</h1>
    <p class="page-sub">Beach Clean-Up - Bondi Beach · 6 applications</p>
  </div>
</div>

<div class="d-flex flex-wrap gap-2 mb-4">
  <button class="pill active">All</button>
  <button class="pill">Pending</button>
  <button class="pill">Accepted</button>
  <button class="pill">Rejected</button>
</div>

<div class="list-card">
  <div class="table-responsive">
    <table class="table-v">
      <tr>
        <th>Volunteer</th>
        <th>Applied</th>
        <th>Hours logged</th>
        <th>Skills</th>
        <th>Status</th>
        <th></th>
      </tr>

      <tr>
        <td>
          <span class="d-flex align-items-center gap-3">
            <span class="avatar-circle">A</span>
            <span>
              <span class="d-block fw-bold">Aruna Tamang</span>
              <span class="d-block" style="font-size:12.5px;color:#6d7880">aruna.t@mail.com</span>
            </span>
          </span>
        </td>
        <td class="mono td-muted" style="font-size:13px">02 Sept</td>
        <td class="mono" style="font-size:13px">128 hrs</td>
        <td>
          <span class="chip" style="font-size:12px">Teamwork</span>
          <span class="chip" style="font-size:12px">First Aid</span>
        </td>
        <td><span class="badge-v badge-pending">Pending</span></td>
        <td class="text-end">
          <span class="d-flex gap-2 justify-content-end">
            <a class="btn-v btn-green btn-sm-v" href="opportunity-applications.php?done=1">Accept</a>
            <a class="btn-v btn-outline btn-sm-v" href="opportunity-applications.php?done=1">Reject</a>
          </span>
        </td>
      </tr>

      <tr>
        <td>
          <span class="d-flex align-items-center gap-3">
            <span class="avatar-circle">R</span>
            <span>
              <span class="d-block fw-bold">Raj Kumar</span>
              <span class="d-block" style="font-size:12.5px;color:#6d7880">raj.k@mail.com</span>
            </span>
          </span>
        </td>
        <td class="mono td-muted" style="font-size:13px">02 Sept</td>
        <td class="mono" style="font-size:13px">64 hrs</td>
        <td><span class="chip" style="font-size:12px">Physical Fitness</span></td>
        <td><span class="badge-v badge-pending">Pending</span></td>
        <td class="text-end">
          <span class="d-flex gap-2 justify-content-end">
            <a class="btn-v btn-green btn-sm-v" href="opportunity-applications.php?done=1">Accept</a>
            <a class="btn-v btn-outline btn-sm-v" href="opportunity-applications.php?done=1">Reject</a>
          </span>
        </td>
      </tr>

      <tr>
        <td>
          <span class="d-flex align-items-center gap-3">
            <span class="avatar-circle">A</span>
            <span>
              <span class="d-block fw-bold">Anil Dangi</span>
              <span class="d-block" style="font-size:12.5px;color:#6d7880">anil.d@mail.com</span>
            </span>
          </span>
        </td>
        <td class="mono td-muted" style="font-size:13px">01 Sept</td>
        <td class="mono" style="font-size:13px">212 hrs</td>
        <td>
          <span class="chip" style="font-size:12px">Teamwork</span>
          <span class="chip" style="font-size:12px">Team Lead</span>
        </td>
        <td><span class="badge-v badge-navy">Accepted</span></td>
        <td class="text-end td-muted">—</td>
      </tr>

      <tr>
        <td>
          <span class="d-flex align-items-center gap-3">
            <span class="avatar-circle">A</span>
            <span>
              <span class="d-block fw-bold">Ahmad Faris</span>
              <span class="d-block" style="font-size:12.5px;color:#6d7880">ahmad.f@mail.com</span>
            </span>
          </span>
        </td>
        <td class="mono td-muted" style="font-size:13px">31 Aug</td>
        <td class="mono" style="font-size:13px">46 hrs</td>
        <td><span class="chip" style="font-size:12px">Environmental Awareness</span></td>
        <td><span class="badge-v badge-navy">Accepted</span></td>
        <td class="text-end td-muted">—</td>
      </tr>

      <tr>
        <td>
          <span class="d-flex align-items-center gap-3">
            <span class="avatar-circle">P</span>
            <span>
              <span class="d-block fw-bold">Preethi S.</span>
              <span class="d-block" style="font-size:12.5px;color:#6d7880">preethi.s@mail.com</span>
            </span>
          </span>
        </td>
        <td class="mono td-muted" style="font-size:13px">30 Aug</td>
        <td class="mono" style="font-size:13px">18 hrs</td>
        <td><span class="chip" style="font-size:12px">Physical Fitness</span></td>
        <td><span class="badge-v badge-grey">Rejected</span></td>
        <td class="text-end td-muted">—</td>
      </tr>

      <tr>
        <td>
          <span class="d-flex align-items-center gap-3">
            <span class="avatar-circle">L</span>
            <span>
              <span class="d-block fw-bold">Lim Wei Jie</span>
              <span class="d-block" style="font-size:12.5px;color:#6d7880">lim.wj@mail.com</span>
            </span>
          </span>
        </td>
        <td class="mono td-muted" style="font-size:13px">29 Aug</td>
        <td class="mono" style="font-size:13px">92 hrs</td>
        <td><span class="chip" style="font-size:12px">Teamwork</span></td>
        <td><span class="badge-v badge-navy">Accepted</span></td>
        <td class="text-end td-muted">—</td>
      </tr>

    </table>
  </div>
</div>

<?php include 'includes/app-footer.php'; ?>

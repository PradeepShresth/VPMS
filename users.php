<?php
$page_title = 'Users | VPMS';
$active = 'organisations';
include 'includes/app-header.php';
?>

<a class="back-link" href="dashboard.php"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>

<?php if (isset($_GET['done'])) { ?>
  <div class="banner"><i class="bi bi-check-circle-fill"></i>User account updated.</div>
<?php } ?>

<div class="head-row">
  <div class="flex-grow-1">
    <h1 class="page-title">Users</h1>
    <p class="page-sub">4,820 registered users · 1 awaiting approval</p>
  </div>
  <a class="btn-v btn-green" href="applications-review.php">Review Applications</a>
</div>

<div class="d-flex flex-wrap align-items-center gap-3 mb-4">
  <input type="search" class="input-v" style="flex:1 1 300px;max-width:520px" placeholder="Search users by name or email...">
  <div class="d-flex flex-wrap gap-2">
    <button class="pill active">All</button>
    <button class="pill">Volunteer</button>
    <button class="pill">NGO Coordinator</button>
    <button class="pill">CSR Manager</button>
    <button class="pill">Field Officer</button>
    <button class="pill">Sponsor</button>
    <button class="pill">Administrator</button>
  </div>
</div>

<div class="list-card">
  <div class="table-responsive">
    <table class="table-v">
      <tr>
        <th>User</th>
        <th>Role</th>
        <th>Organisation</th>
        <th>Joined</th>
        <th>Status</th>
        <th></th>
      </tr>

      <tr>
        <td>
          <span class="d-flex align-items-center gap-3">
            <span class="avatar-circle">P</span>
            <span>
              <span class="d-block fw-bold">Pradeep Shrestha</span>
              <span class="d-block" style="font-size:12.5px;color:#6d7880">admin@vpms.org</span>
            </span>
          </span>
        </td>
        <td style="color:#16663e">System Administrator</td>
        <td class="td-muted">VPMS</td>
        <td class="td-muted mono" style="font-size:12.5px">2026-09-01</td>
        <td><span class="badge-v badge-navy">Active</span></td>
        <td class="text-end"><a class="btn-v btn-outline btn-sm-v" href="users.php?done=1">Suspend</a></td>
      </tr>

      <tr>
        <td>
          <span class="d-flex align-items-center gap-3">
            <span class="avatar-circle">P</span>
            <span>
              <span class="d-block fw-bold">Prasidha Neupane</span>
              <span class="d-block" style="font-size:12.5px;color:#6d7880">prasidha@gfuture.org</span>
            </span>
          </span>
        </td>
        <td style="color:#16663e">NGO Coordinator</td>
        <td class="td-muted">Green Future NGO</td>
        <td class="td-muted mono" style="font-size:12.5px">2026-08-14</td>
        <td><span class="badge-v badge-navy">Active</span></td>
        <td class="text-end"><a class="btn-v btn-outline btn-sm-v" href="users.php?done=1">Suspend</a></td>
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
        <td style="color:#16663e">Volunteer</td>
        <td class="td-muted">—</td>
        <td class="td-muted mono" style="font-size:12.5px">2026-08-02</td>
        <td><span class="badge-v badge-navy">Active</span></td>
        <td class="text-end"><a class="btn-v btn-outline btn-sm-v" href="users.php?done=1">Suspend</a></td>
      </tr>

      <tr>
        <td>
          <span class="d-flex align-items-center gap-3">
            <span class="avatar-circle">R</span>
            <span>
              <span class="d-block fw-bold">Ram Dhakal</span>
              <span class="d-block" style="font-size:12.5px;color:#6d7880">ram.d@techcorp.cn</span>
            </span>
          </span>
        </td>
        <td style="color:#16663e">Corporate CSR Manager</td>
        <td class="td-muted">TechCorp China</td>
        <td class="td-muted mono" style="font-size:12.5px">2026-07-21</td>
        <td><span class="badge-v badge-navy">Active</span></td>
        <td class="text-end"><a class="btn-v btn-outline btn-sm-v" href="users.php?done=1">Suspend</a></td>
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
        <td style="color:#16663e">Community Field Officer</td>
        <td class="td-muted">Green Future NGO</td>
        <td class="td-muted mono" style="font-size:12.5px">2026-07-08</td>
        <td><span class="badge-v badge-navy">Active</span></td>
        <td class="text-end"><a class="btn-v btn-outline btn-sm-v" href="users.php?done=1">Suspend</a></td>
      </tr>

      <tr>
        <td>
          <span class="d-flex align-items-center gap-3">
            <span class="avatar-circle">S</span>
            <span>
              <span class="d-block fw-bold">Sita Rai</span>
              <span class="d-block" style="font-size:12.5px;color:#6d7880">sita.r@impact.org</span>
            </span>
          </span>
        </td>
        <td style="color:#16663e">Sponsor / Donor</td>
        <td class="td-muted">Global Impact Fund</td>
        <td class="td-muted mono" style="font-size:12.5px">2026-06-30</td>
        <td><span class="badge-v badge-navy">Active</span></td>
        <td class="text-end"><a class="btn-v btn-outline btn-sm-v" href="users.php?done=1">Suspend</a></td>
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
        <td style="color:#16663e">Volunteer</td>
        <td class="td-muted">—</td>
        <td class="td-muted mono" style="font-size:12.5px">2026-09-12</td>
        <td><span class="badge-v badge-pending">Pending</span></td>
        <td class="text-end"><a class="btn-v btn-green btn-sm-v" href="users.php?done=1">Approve</a></td>
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
        <td style="color:#16663e">Volunteer</td>
        <td class="td-muted">—</td>
        <td class="td-muted mono" style="font-size:12.5px">2026-05-19</td>
        <td><span class="badge-v badge-grey">Suspended</span></td>
        <td class="text-end"><a class="btn-v btn-green btn-sm-v" href="users.php?done=1">Restore</a></td>
      </tr>

    </table>
  </div>
</div>

<?php include 'includes/app-footer.php'; ?>

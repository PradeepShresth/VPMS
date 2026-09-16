<?php
$page_title = 'Organisations | VPMS';
$active = 'organisations';
include 'includes/app-header.php';
?>

<?php if (isset($_GET['done'])) { ?>
  <div class="banner"><i class="bi bi-check-circle-fill"></i>Organisation updated. The contact person has been notified.</div>
<?php } ?>

<div class="head-row">
  <div class="flex-grow-1">
    <h1 class="page-title">Organisations</h1>
    <p class="page-sub">7 organisations on the platform</p>
  </div>
  <span class="badge-v badge-pending mt-2">1 pending approval</span>
</div>

<div class="d-flex flex-wrap align-items-center gap-3 mb-4">
  <input type="search" class="input-v" style="flex:1 1 320px;max-width:740px" placeholder="Search organisations...">
  <div class="d-flex flex-wrap gap-2">
    <button class="pill active">All</button>
    <button class="pill">NGO</button>
    <button class="pill">Corporate</button>
    <button class="pill">Community</button>
    <button class="pill">Pending</button>
    <button class="pill">Verified</button>
  </div>
</div>

<div class="list-card">
  <div class="table-responsive">
    <table class="table-v">
      <tr>
        <th>Organisation</th>
        <th>Type</th>
        <th>Location</th>
        <th>Members</th>
        <th>Projects</th>
        <th>Status</th>
        <th></th>
      </tr>

      <tr>
        <td>
          <span class="d-flex align-items-center gap-3">
            <span class="avatar-square">G</span>
            <span class="fw-bold">Green Future NGO</span>
          </span>
        </td>
        <td style="color:#16663e">NGO</td>
        <td class="td-muted">Petaling Jaya, Selangor</td>
        <td>124</td>
        <td>18</td>
        <td><span class="badge-v badge-navy">Verified</span></td>
        <td class="text-end">
          <a class="link-green" style="font-size:13.5px" href="organisation-details.php?status=verified">View &rarr;</a>
        </td>
      </tr>

      <tr>
        <td>
          <span class="d-flex align-items-center gap-3">
            <span class="avatar-square">T</span>
            <span class="fw-bold">TechCorp China</span>
          </span>
        </td>
        <td style="color:#16663e">Corporate</td>
        <td class="td-muted">KL Sentral, Kuala Lumpur</td>
        <td>890</td>
        <td>7</td>
        <td><span class="badge-v badge-navy">Verified</span></td>
        <td class="text-end">
          <a class="link-green" style="font-size:13.5px" href="organisation-details.php?status=verified">View &rarr;</a>
        </td>
      </tr>

      <tr>
        <td>
          <span class="d-flex align-items-center gap-3">
            <span class="avatar-square">B</span>
            <span class="fw-bold">Blue Shore Initiative</span>
          </span>
        </td>
        <td style="color:#16663e">NGO</td>
        <td class="td-muted">Bangsar South, KL</td>
        <td>320</td>
        <td>3</td>
        <td><span class="badge-v badge-pending">Pending</span></td>
        <td class="text-end">
          <a class="link-green" style="font-size:13.5px" href="organisation-details.php?status=pending">View &rarr;</a>
        </td>
      </tr>

    </table>
  </div>

  <div style="min-height:240px"></div>
</div>

<?php include 'includes/app-footer.php'; ?>

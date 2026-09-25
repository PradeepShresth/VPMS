<?php
$page_title = 'Organizations | VPMS';
$active = 'organizations';

require 'includes/auth.php';
require 'config/db.php';

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'All';

$sql = 'SELECT o.organization_id, o.name, o.type, o.city, o.state, o.status,
               (SELECT COUNT(*) FROM `user` u WHERE u.organization_id = o.organization_id) AS members,
               (SELECT COUNT(*) FROM opportunity p WHERE p.organization_id = o.organization_id) AS projects
        FROM organization o
        WHERE 1 = 1';
$values = array();

if ($search != '') {
    $sql = $sql . ' AND o.name LIKE ?';
    $values[] = '%' . $search . '%';
}

if ($filter == 'NGO' || $filter == 'Corporate' || $filter == 'Community') {
    $sql = $sql . ' AND o.type = ?';
    $values[] = $filter;
} elseif ($filter == 'Pending' || $filter == 'Verified') {
    $sql = $sql . ' AND o.status = ?';
    $values[] = strtolower($filter);
}

$sql = $sql . ' ORDER BY o.name';

$find = $pdo->prepare($sql);
$find->execute($values);
$organizations = $find->fetchAll();

$count = $pdo->query('SELECT COUNT(*) FROM organization');
$total = $count->fetchColumn();

$count = $pdo->prepare('SELECT COUNT(*) FROM organization WHERE status = ?');
$count->execute(array('pending'));
$waiting = $count->fetchColumn();

include 'includes/app-header.php';
?>

<?php if (isset($_GET['done'])) { ?>
  <div class="banner"><i class="bi bi-check-circle-fill"></i>Organization updated. The contact person has been notified.</div>
<?php } ?>

<div class="head-row">
  <div class="flex-grow-1">
    <h1 class="page-title">Organizations</h1>
    <p class="page-sub"><?php echo $total; ?> organizations on the platform</p>
  </div>
  <?php if ($waiting > 0) { ?>
    <span class="badge-v badge-pending mt-2"><?php echo $waiting; ?> pending approval</span>
  <?php } ?>
</div>

<form class="d-flex flex-wrap align-items-center gap-3 mb-3" action="organizations.php" method="get">
  <input type="search" class="input-v" style="flex:1 1 320px;max-width:740px" name="q"
         placeholder="Search organizations..." value="<?php echo htmlspecialchars($search); ?>">
  <input type="hidden" name="filter" value="<?php echo htmlspecialchars($filter); ?>">
  <button class="btn-v btn-soft" type="submit">Search</button>
</form>

<div class="d-flex flex-wrap gap-2 mb-4">
  <a class="pill <?php if ($filter == 'All') echo 'active'; ?>" href="organizations.php?q=<?php echo urlencode($search); ?>&filter=All">All</a>
  <a class="pill <?php if ($filter == 'NGO') echo 'active'; ?>" href="organizations.php?q=<?php echo urlencode($search); ?>&filter=NGO">NGO</a>
  <a class="pill <?php if ($filter == 'Corporate') echo 'active'; ?>" href="organizations.php?q=<?php echo urlencode($search); ?>&filter=Corporate">Corporate</a>
  <a class="pill <?php if ($filter == 'Community') echo 'active'; ?>" href="organizations.php?q=<?php echo urlencode($search); ?>&filter=Community">Community</a>
  <a class="pill <?php if ($filter == 'Pending') echo 'active'; ?>" href="organizations.php?q=<?php echo urlencode($search); ?>&filter=Pending">Pending</a>
  <a class="pill <?php if ($filter == 'Verified') echo 'active'; ?>" href="organizations.php?q=<?php echo urlencode($search); ?>&filter=Verified">Verified</a>
</div>

<?php if (count($organizations) == 0) { ?>

  <div class="card-v card-v-pad text-center">
    <p class="row-title mb-2">No organizations yet</p>
    <p style="font-size:14px;color:#6d7880">
      Organizations appear here once somebody registers one from the sign-up page.
    </p>
  </div>

<?php } else { ?>

<div class="list-card">
  <div class="table-responsive">
    <table class="table-v">
      <tr>
        <th>Organization</th>
        <th>Type</th>
        <th>Location</th>
        <th>Members</th>
        <th>Projects</th>
        <th>Status</th>
        <th></th>
      </tr>

      <?php foreach ($organizations as $row) { ?>

        <?php
        $where = $row['city'];

        if ($row['state'] != '') {
            if ($where != '') {
                $where = $where . ', ' . $row['state'];
            } else {
                $where = $row['state'];
            }
        }

        if ($where == '') {
            $where = '—';
        }
        ?>

        <tr>
          <td>
            <span class="d-flex align-items-center gap-3">
              <span class="avatar-square"><?php echo strtoupper(substr($row['name'], 0, 1)); ?></span>
              <span class="fw-bold"><?php echo htmlspecialchars($row['name']); ?></span>
            </span>
          </td>
          <td style="color:#16663e"><?php echo htmlspecialchars($row['type']); ?></td>
          <td class="td-muted"><?php echo htmlspecialchars($where); ?></td>
          <td><?php echo $row['members']; ?></td>
          <td><?php echo $row['projects']; ?></td>
          <td>
            <?php if ($row['status'] == 'verified') { ?>
              <span class="badge-v badge-navy">Verified</span>
            <?php } elseif ($row['status'] == 'pending') { ?>
              <span class="badge-v badge-pending">Pending</span>
            <?php } else { ?>
              <span class="badge-v badge-grey">Rejected</span>
            <?php } ?>
          </td>
          <td class="text-end">
            <a class="link-green" style="font-size:13.5px"
               href="organization-details.php?id=<?php echo $row['organization_id']; ?>">View &rarr;</a>
          </td>
        </tr>
      <?php } ?>

    </table>
  </div>
</div>

<?php } ?>

<?php include 'includes/app-footer.php'; ?>

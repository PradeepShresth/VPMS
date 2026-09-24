<?php
$page_title = 'Users | VPMS';
$active = 'users';

require 'includes/auth.php';
require 'config/db.php';

if ($_SESSION['role_id'] != 6) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];

    if ($status == 'active' || $status == 'suspended') {
        $update = $pdo->prepare('UPDATE `user` SET status = ? WHERE user_id = ?');
        $update->execute(array($status, $_POST['user_id']));
    }

    header('Location: users.php?done=1');
    exit;
}

$search = isset($_GET['q']) ? trim($_GET['q']) : '';   // 0 in the role filter means show everyone
$role = isset($_GET['role']) ? $_GET['role'] : '0';

$sql = 'SELECT u.user_id, u.full_name, u.email, u.status, u.created_at, u.organisation_name,
               r.name AS role_name, org.name AS organisation
        FROM `user` u
        JOIN role r ON r.role_id = u.role_id
        LEFT JOIN organisation org ON org.organisation_id = u.organisation_id
        WHERE 1 = 1';
$values = array();

if ($search != '') {
    $sql = $sql . ' AND (u.full_name LIKE ? OR u.email LIKE ?)';
    $values[] = '%' . $search . '%';
    $values[] = '%' . $search . '%';
}

if ($role != '0') {
    $sql = $sql . ' AND u.role_id = ?';
    $values[] = $role;
}

$sql = $sql . ' ORDER BY u.created_at DESC';

$find = $pdo->prepare($sql);
$find->execute($values);
$users = $find->fetchAll();

$count = $pdo->query('SELECT COUNT(*) FROM `user`');
$total = $count->fetchColumn();

$count = $pdo->prepare('SELECT COUNT(*) FROM `user` WHERE status = ?');
$count->execute(array('pending'));
$waiting = $count->fetchColumn();

include 'includes/app-header.php';
?>

<a class="back-link" href="dashboard.php"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>

<?php if (isset($_GET['done'])) { ?>
  <div class="banner"><i class="bi bi-check-circle-fill"></i>User account updated.</div>
<?php } ?>

<div class="head-row">
  <div class="flex-grow-1">
    <h1 class="page-title">Users</h1>
    <p class="page-sub">
      <?php echo $total; ?> registered users · <?php echo $waiting; ?> awaiting approval
    </p>
  </div>
  <a class="btn-v btn-green" href="applications-review.php">Review Applications</a>
</div>

<form class="d-flex flex-wrap align-items-center gap-3 mb-3" action="users.php" method="get">
  <input type="search" class="input-v" style="flex:1 1 300px;max-width:520px" name="q"
         placeholder="Search users by name or email..." value="<?php echo htmlspecialchars($search); ?>">
  <input type="hidden" name="role" value="<?php echo htmlspecialchars($role); ?>">
  <button class="btn-v btn-soft" type="submit">Search</button>
</form>

<div class="d-flex flex-wrap gap-2 mb-4">
  <a class="pill <?php if ($role == '0') echo 'active'; ?>" href="users.php?q=<?php echo urlencode($search); ?>&role=0">All</a>
  <a class="pill <?php if ($role == '1') echo 'active'; ?>" href="users.php?q=<?php echo urlencode($search); ?>&role=1">Volunteer</a>
  <a class="pill <?php if ($role == '2') echo 'active'; ?>" href="users.php?q=<?php echo urlencode($search); ?>&role=2">NGO Coordinator</a>
  <a class="pill <?php if ($role == '3') echo 'active'; ?>" href="users.php?q=<?php echo urlencode($search); ?>&role=3">CSR Manager</a>
  <a class="pill <?php if ($role == '4') echo 'active'; ?>" href="users.php?q=<?php echo urlencode($search); ?>&role=4">Field Officer</a>
  <a class="pill <?php if ($role == '5') echo 'active'; ?>" href="users.php?q=<?php echo urlencode($search); ?>&role=5">Sponsor</a>
  <a class="pill <?php if ($role == '6') echo 'active'; ?>" href="users.php?q=<?php echo urlencode($search); ?>&role=6">Administrator</a>
</div>

<?php if (count($users) == 0) { ?>

  <div class="card-v card-v-pad text-center">
    <p class="row-title mb-2">No users match</p>
    <p style="font-size:14px;color:#6d7880">Try a different search or filter.</p>
  </div>

<?php } else { ?>

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

      <?php foreach ($users as $row) { ?>

        <?php
        if ($row['organisation'] != '') {
            $organisation = $row['organisation'];
        } elseif ($row['organisation_name'] != '') {
            $organisation = $row['organisation_name'];
        } else {
            $organisation = '—';
        }
        ?>

        <tr>
          <td>
            <span class="d-flex align-items-center gap-3">
              <span class="avatar-circle"><?php echo strtoupper(substr($row['full_name'], 0, 1)); ?></span>
              <span>
                <span class="d-block fw-bold"><?php echo htmlspecialchars($row['full_name']); ?></span>
                <span class="d-block" style="font-size:12.5px;color:#6d7880"><?php echo htmlspecialchars($row['email']); ?></span>
              </span>
            </span>
          </td>
          <td style="color:#16663e"><?php echo htmlspecialchars($row['role_name']); ?></td>
          <td class="td-muted"><?php echo htmlspecialchars($organisation); ?></td>
          <td class="td-muted mono" style="font-size:12.5px"><?php echo date('Y-m-d', strtotime($row['created_at'])); ?></td>
          <td>
            <?php if ($row['status'] == 'active') { ?>
              <span class="badge-v badge-navy">Active</span>
            <?php } elseif ($row['status'] == 'pending') { ?>
              <span class="badge-v badge-pending">Pending</span>
            <?php } else { ?>
              <span class="badge-v badge-grey">Suspended</span>
            <?php } ?>
          </td>
          <td class="text-end">
            <?php if ($row['user_id'] == $_SESSION['user_id']) { ?>
              <span class="td-muted">That is you</span>
            <?php } elseif ($row['status'] == 'active') { ?>
              <form action="users.php" method="post">
                <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                <input type="hidden" name="status" value="suspended">
                <button class="btn-v btn-outline btn-sm-v" type="submit">Suspend</button>
              </form>
            <?php } elseif ($row['status'] == 'pending') { ?>
              <form action="users.php" method="post">
                <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                <input type="hidden" name="status" value="active">
                <button class="btn-v btn-green btn-sm-v" type="submit">Approve</button>
              </form>
            <?php } else { ?>
              <form action="users.php" method="post">
                <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                <input type="hidden" name="status" value="active">
                <button class="btn-v btn-green btn-sm-v" type="submit">Restore</button>
              </form>
            <?php } ?>
          </td>
        </tr>
      <?php } ?>

    </table>
  </div>
</div>

<?php } ?>

<?php include 'includes/app-footer.php'; ?>

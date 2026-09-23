<?php
$page_title = 'Review Applications | VPMS';
$active = 'opportunities';

require 'includes/auth.php';
require 'config/db.php';

$is_admin = ($_SESSION['role_id'] == 6);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kind = $_POST['kind'];
    $decision = $_POST['decision'];

    if ($kind == 'application') {
        if ($decision == 'approve') {
            $status = 'accepted';
        } else {
            $status = 'rejected';
        }

        $update = $pdo->prepare('UPDATE application SET status = ? WHERE application_id = ?');
        $update->execute(array($status, $_POST['row_id']));
    }

    if ($kind == 'account' && $is_admin) {
        if ($decision == 'approve') {
            $status = 'active';
        } else {
            $status = 'suspended';
        }

        $update = $pdo->prepare('UPDATE `user` SET status = ? WHERE user_id = ?');
        $update->execute(array($status, $_POST['row_id']));
    }

    if ($kind == 'organisation' && $is_admin) {
        if ($decision == 'approve') {
            $status = 'verified';
        } else {
            $status = 'rejected';
        }

        $update = $pdo->prepare('UPDATE organisation SET status = ? WHERE organisation_id = ?');
        $update->execute(array($status, $_POST['row_id']));
    }

    header('Location: applications-review.php?done=1');
    exit;
}

$show = isset($_GET['show']) ? $_GET['show'] : 'all';

$sql = 'SELECT a.application_id, a.created_at, u.full_name, o.title,
               org.name AS organisation, poster.organisation_name
        FROM application a
        JOIN `user` u ON u.user_id = a.user_id
        JOIN opportunity o ON o.opportunity_id = a.opportunity_id
        JOIN `user` poster ON poster.user_id = o.created_by
        LEFT JOIN organisation org ON org.organisation_id = o.organisation_id
        WHERE a.status = ?';
$values = array('pending');

if (!$is_admin) {
    $sql = $sql . ' AND o.created_by = ?';
    $values[] = $_SESSION['user_id'];
}

$sql = $sql . ' ORDER BY a.created_at DESC';

$find = $pdo->prepare($sql);
$find->execute($values);
$applications = $find->fetchAll();

$accounts = array();
$organisations = array();

if ($is_admin) {
    $find = $pdo->prepare(
        'SELECT u.user_id, u.full_name, u.created_at, r.name AS role_name
         FROM `user` u
         JOIN role r ON r.role_id = u.role_id
         WHERE u.status = ?
         ORDER BY u.created_at DESC'
    );
    $find->execute(array('pending'));
    $accounts = $find->fetchAll();

    $find = $pdo->prepare(
        'SELECT organisation_id, name, type, city, created_at
         FROM organisation
         WHERE status = ?
         ORDER BY created_at DESC'
    );
    $find->execute(array('pending'));
    $organisations = $find->fetchAll();
}

$waiting = count($applications) + count($accounts) + count($organisations);

include 'includes/app-header.php';
?>

<a class="back-link" href="dashboard.php"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>

<?php if (isset($_GET['done'])) { ?>
  <div class="banner"><i class="bi bi-check-circle-fill"></i>Request handled. The applicant has been notified.</div>
<?php } ?>

<div class="head-row">
  <div class="flex-grow-1">
    <h1 class="page-title">Review Applications</h1>
    <p class="page-sub"><?php echo $waiting; ?> items awaiting review</p>
  </div>
</div>

<div class="d-flex flex-wrap gap-2 mb-4">
  <a class="pill <?php if ($show == 'all') echo 'active'; ?>" href="applications-review.php?show=all">All</a>
  <a class="pill <?php if ($show == 'applications') echo 'active'; ?>" href="applications-review.php?show=applications">Volunteer applications</a>
  <?php if ($is_admin) { ?>
    <a class="pill <?php if ($show == 'accounts') echo 'active'; ?>" href="applications-review.php?show=accounts">Account requests</a>
    <a class="pill <?php if ($show == 'organisations') echo 'active'; ?>" href="applications-review.php?show=organisations">Organisation requests</a>
  <?php } ?>
</div>

<?php if ($waiting == 0) { ?>
  <div class="card-v card-v-pad text-center">
    <p class="row-title mb-2">Nothing waiting</p>
    <p style="font-size:14px;color:#6d7880">Every request has been dealt with.</p>
  </div>
<?php } ?>

<?php if ($show == 'all' || $show == 'applications') { ?>
  <?php foreach ($applications as $row) { ?>

    <?php
    if ($row['organisation'] != '') {
        $posted_by = $row['organisation'];
    } elseif ($row['organisation_name'] != '') {
        $posted_by = $row['organisation_name'];
    } else {
        $posted_by = '—';
    }
    ?>

    <div class="card-v card-v-pad mb-3">
      <div class="d-flex flex-wrap align-items-start gap-3">
        <span class="avatar-circle"><?php echo strtoupper(substr($row['full_name'], 0, 1)); ?></span>
        <span class="flex-grow-1 min-w-0">
          <span class="d-flex flex-wrap align-items-center gap-2 mb-1">
            <span class="row-title"><?php echo htmlspecialchars($row['full_name']); ?></span>
            <span class="chip" style="font-size:12px">Volunteer application</span>
          </span>
          <span class="row-meta d-block"><?php echo htmlspecialchars($row['title']); ?></span>
          <span class="mono d-block mt-1" style="font-size:12px;color:#98a2aa">
            <?php echo htmlspecialchars($posted_by); ?>
            &nbsp;·&nbsp; submitted <?php echo date('d M', strtotime($row['created_at'])); ?>
          </span>
        </span>
        <span class="d-flex gap-2">
          <form action="applications-review.php" method="post">
            <input type="hidden" name="kind" value="application">
            <input type="hidden" name="row_id" value="<?php echo $row['application_id']; ?>">
            <input type="hidden" name="decision" value="approve">
            <button class="btn-v btn-green btn-sm-v" type="submit">Approve</button>
          </form>
          <form action="applications-review.php" method="post">
            <input type="hidden" name="kind" value="application">
            <input type="hidden" name="row_id" value="<?php echo $row['application_id']; ?>">
            <input type="hidden" name="decision" value="reject">
            <button class="btn-v btn-outline btn-sm-v" type="submit">Reject</button>
          </form>
        </span>
      </div>
    </div>

  <?php } ?>
<?php } ?>

<?php if ($show == 'all' || $show == 'accounts') { ?>
  <?php foreach ($accounts as $row) { ?>
    <div class="card-v card-v-pad mb-3">
      <div class="d-flex flex-wrap align-items-start gap-3">
        <span class="avatar-circle"><?php echo strtoupper(substr($row['full_name'], 0, 1)); ?></span>
        <span class="flex-grow-1 min-w-0">
          <span class="d-flex flex-wrap align-items-center gap-2 mb-1">
            <span class="row-title"><?php echo htmlspecialchars($row['full_name']); ?></span>
            <span class="chip" style="font-size:12px">Account request</span>
          </span>
          <span class="row-meta d-block">Registering as <?php echo htmlspecialchars($row['role_name']); ?></span>
          <span class="mono d-block mt-1" style="font-size:12px;color:#98a2aa">
            — &nbsp;·&nbsp; submitted <?php echo date('d M', strtotime($row['created_at'])); ?>
          </span>
        </span>
        <span class="d-flex gap-2">
          <form action="applications-review.php" method="post">
            <input type="hidden" name="kind" value="account">
            <input type="hidden" name="row_id" value="<?php echo $row['user_id']; ?>">
            <input type="hidden" name="decision" value="approve">
            <button class="btn-v btn-green btn-sm-v" type="submit">Approve</button>
          </form>
          <form action="applications-review.php" method="post">
            <input type="hidden" name="kind" value="account">
            <input type="hidden" name="row_id" value="<?php echo $row['user_id']; ?>">
            <input type="hidden" name="decision" value="reject">
            <button class="btn-v btn-outline btn-sm-v" type="submit">Reject</button>
          </form>
        </span>
      </div>
    </div>
  <?php } ?>
<?php } ?>

<?php if ($show == 'all' || $show == 'organisations') { ?>
  <?php foreach ($organisations as $row) { ?>
    <div class="card-v card-v-pad mb-3">
      <div class="d-flex flex-wrap align-items-start gap-3">
        <span class="avatar-circle"><?php echo strtoupper(substr($row['name'], 0, 1)); ?></span>
        <span class="flex-grow-1 min-w-0">
          <span class="d-flex flex-wrap align-items-center gap-2 mb-1">
            <span class="row-title"><?php echo htmlspecialchars($row['name']); ?></span>
            <span class="chip" style="font-size:12px">Organisation request</span>
          </span>
          <span class="row-meta d-block">
            <?php echo htmlspecialchars($row['type']); ?> verification
            <?php if ($row['city'] != '') { ?> — <?php echo htmlspecialchars($row['city']); ?><?php } ?>
          </span>
          <span class="mono d-block mt-1" style="font-size:12px;color:#98a2aa">
            — &nbsp;·&nbsp; submitted <?php echo date('d M', strtotime($row['created_at'])); ?>
          </span>
        </span>
        <span class="d-flex gap-2">
          <a class="btn-v btn-soft btn-sm-v" href="organisation-details.php?id=<?php echo $row['organisation_id']; ?>">View</a>
          <form action="applications-review.php" method="post">
            <input type="hidden" name="kind" value="organisation">
            <input type="hidden" name="row_id" value="<?php echo $row['organisation_id']; ?>">
            <input type="hidden" name="decision" value="approve">
            <button class="btn-v btn-green btn-sm-v" type="submit">Approve</button>
          </form>
          <form action="applications-review.php" method="post">
            <input type="hidden" name="kind" value="organisation">
            <input type="hidden" name="row_id" value="<?php echo $row['organisation_id']; ?>">
            <input type="hidden" name="decision" value="reject">
            <button class="btn-v btn-outline btn-sm-v" type="submit">Reject</button>
          </form>
        </span>
      </div>
    </div>
  <?php } ?>
<?php } ?>

<?php include 'includes/app-footer.php'; ?>

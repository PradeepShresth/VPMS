<?php
$active = 'organisations';

require 'includes/auth.php';
require 'config/db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role_id'] == 6) {
    if ($_POST['decision'] == 'approve') {
        $status = 'verified';
    } else {
        $status = 'rejected';
    }

    $update = $pdo->prepare('UPDATE organisation SET status = ? WHERE organisation_id = ?');
    $update->execute(array($status, $id));

    header('Location: organisations.php?done=1');
    exit;
}

$find = $pdo->prepare('SELECT * FROM organisation WHERE organisation_id = ?');
$find->execute(array($id));
$organisation = $find->fetch();

if ($organisation == false) {
    header('Location: organisations.php');
    exit;
}

$page_title = $organisation['name'] . ' | VPMS';

$count = $pdo->prepare('SELECT COUNT(*) FROM `user` WHERE organisation_id = ?');
$count->execute(array($id));
$members = $count->fetchColumn();

$count = $pdo->prepare('SELECT COUNT(*) FROM opportunity WHERE organisation_id = ?');
$count->execute(array($id));
$projects = $count->fetchColumn();

$where = $organisation['city'];

if ($organisation['state'] != '') {
    if ($where != '') {
        $where = $where . ', ' . $organisation['state'];
    } else {
        $where = $organisation['state'];
    }
}

$can_edit = ($_SESSION['role_id'] == 6 || $_SESSION['organisation_id'] == $id);

include 'includes/app-header.php';
?>

<div class="col-mid mx-auto">

  <a class="back-link" href="organisations.php"><i class="bi bi-arrow-left"></i> Back</a>

  <?php if (isset($_GET['saved'])) { ?>
    <div class="banner"><i class="bi bi-check-circle-fill"></i>Organisation details saved.</div>
  <?php } ?>

  <div class="d-flex align-items-start gap-3 mb-1">
    <h1 class="page-title flex-grow-1"><?php echo htmlspecialchars($organisation['name']); ?></h1>
    <?php if ($organisation['status'] == 'verified') { ?>
      <span class="badge-v badge-navy mt-2">Verified</span>
    <?php } elseif ($organisation['status'] == 'pending') { ?>
      <span class="badge-v badge-pending mt-2">Pending</span>
    <?php } else { ?>
      <span class="badge-v badge-grey mt-2">Rejected</span>
    <?php } ?>
  </div>

  <p class="mb-4" style="color:#16663e;font-size:14px">
    <?php echo htmlspecialchars($organisation['type']); ?>
    <?php if ($where != '') { ?> · <?php echo htmlspecialchars($where); ?><?php } ?>
  </p>

  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="info-tile">
        <span class="t-label">Members</span>
        <span class="t-value serif"><?php echo $members; ?></span>
      </div>
    </div>
    <div class="col-md-4">
      <div class="info-tile">
        <span class="t-label">Opportunities Posted</span>
        <span class="t-value serif"><?php echo $projects; ?></span>
      </div>
    </div>
    <div class="col-md-4">
      <div class="info-tile">
        <span class="t-label">Member Since</span>
        <span class="t-value serif"><?php echo date('F Y', strtotime($organisation['created_at'])); ?></span>
      </div>
    </div>
  </div>

  <div class="card-v card-v-pad mb-4">
    <p class="section-label">Registration Details</p>

    <div class="doc-row">
      <i class="bi bi-hash" style="color:#6d7880"></i>
      Registration number
      <span class="ms-auto mono" style="font-size:13px;color:#6d7880">
        <?php if ($organisation['registration_no'] != '') {
            echo htmlspecialchars($organisation['registration_no']);
        } else {
            echo 'not provided';
        } ?>
      </span>
    </div>

    <div class="doc-row">
      <i class="bi bi-geo-alt" style="color:#6d7880"></i>
      Address
      <span class="ms-auto" style="font-size:13px;color:#6d7880">
        <?php if ($organisation['address'] != '') {
            echo htmlspecialchars($organisation['address']);
        } else {
            echo 'not provided';
        } ?>
      </span>
    </div>

    <div class="doc-row mb-0">
      <i class="bi bi-globe" style="color:#6d7880"></i>
      Website
      <span class="ms-auto" style="font-size:13px;color:#6d7880">
        <?php if ($organisation['website'] != '') { ?>
          <a class="link-green" href="<?php echo htmlspecialchars($organisation['website']); ?>">
            <?php echo htmlspecialchars($organisation['website']); ?>
          </a>
        <?php } else { ?>
          not provided
        <?php } ?>
      </span>
    </div>
  </div>

  <?php if ($organisation['description'] != '') { ?>
    <p class="section-label">About</p>
    <p class="mb-4" style="color:#48545e;font-size:14.5px;line-height:1.7">
      <?php echo nl2br(htmlspecialchars($organisation['description'])); ?>
    </p>
  <?php } ?>

  <?php if ($organisation['status'] == 'pending' && $_SESSION['role_id'] == 6) { ?>
    <div class="row g-3">
      <div class="col-sm-6">
        <form action="organisation-details.php?id=<?php echo $id; ?>" method="post">
          <input type="hidden" name="decision" value="approve">
          <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Approve Organisation</button>
        </form>
      </div>
      <div class="col-sm-6">
        <form action="organisation-details.php?id=<?php echo $id; ?>" method="post">
          <input type="hidden" name="decision" value="reject">
          <button class="btn-v btn-red btn-block btn-lg-v" type="submit">Reject</button>
        </form>
      </div>
    </div>
  <?php } elseif ($can_edit) { ?>
    <a class="btn-v btn-soft btn-block btn-lg-v" href="organisation-edit.php?id=<?php echo $id; ?>">Edit Details</a>
  <?php } ?>

</div>

<?php include 'includes/app-footer.php'; ?>

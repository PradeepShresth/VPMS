<?php
$active = 'partnerships';

require 'includes/auth.php';
require 'config/db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$find = $pdo->prepare(
    'SELECT p.*, asked.name AS asked_by, partner.name AS partner_name
     FROM partnership p
     LEFT JOIN organisation asked ON asked.organisation_id = p.organisation_id
     LEFT JOIN organisation partner ON partner.organisation_id = p.partner_id
     WHERE p.partnership_id = ?'
);
$find->execute(array($id));
$partnership = $find->fetch();

if ($partnership == false) {
    header('Location: partnerships.php');
    exit;
}

$page_title = 'Partnership Details | VPMS';

$is_admin = ($_SESSION['role_id'] == 6);

$is_party = ($_SESSION['organisation_id'] == $partnership['organisation_id']
          || $_SESSION['organisation_id'] == $partnership['partner_id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['decision']) && $is_admin) {
        if ($_POST['decision'] == 'approve') {
            $status = 'active';
        } else {
            $status = 'rejected';
        }

        $update = $pdo->prepare('UPDATE partnership SET status = ? WHERE partnership_id = ?');
        $update->execute(array($status, $id));

        header('Location: partnerships.php?done=1');
        exit;
    }

    if (isset($_POST['end']) && ($is_admin || $is_party)) {
        $update = $pdo->prepare('UPDATE partnership SET end_date = ?, status = ? WHERE partnership_id = ?');
        $update->execute(array($_POST['end'], 'active', $id));

        header('Location: partnership-details.php?id=' . $id . '&saved=1');
        exit;
    }

    if (isset($_POST['reported']) && ($is_admin || $is_party)) {
        $update = $pdo->prepare('UPDATE partnership SET reported_impact = ? WHERE partnership_id = ?');
        $update->execute(array(trim($_POST['reported']), $id));

        header('Location: partnership-details.php?id=' . $id . '&saved=1');
        exit;
    }
}

$today = date('Y-m-d');
$status = $partnership['status'];

if ($status == 'active' && $partnership['end_date'] != '' && $partnership['end_date'] < $today) {
    $status = 'expired';
}

include 'includes/app-header.php';
?>

<div class="col-mid mx-auto">

  <a class="back-link" href="partnerships.php"><i class="bi bi-arrow-left"></i> Back</a>

  <?php if (isset($_GET['saved'])) { ?>
    <div class="banner"><i class="bi bi-check-circle-fill"></i>Partnership updated.</div>
  <?php } ?>

  <div class="d-flex align-items-start gap-3 mb-1">
    <h1 class="page-title flex-grow-1"><?php echo htmlspecialchars($partnership['asked_by']); ?></h1>
    <?php if ($status == 'active') { ?>
      <span class="badge-v badge-navy mt-2">Active</span>
    <?php } elseif ($status == 'pending') { ?>
      <span class="badge-v badge-pending mt-2">Pending</span>
    <?php } elseif ($status == 'expired') { ?>
      <span class="mt-2" style="font-size:13.5px;color:#6d7880">Expired</span>
    <?php } else { ?>
      <span class="badge-v badge-grey mt-2">Rejected</span>
    <?php } ?>
  </div>

  <p class="mb-4" style="color:#16663e;font-size:14px">
    <i class="bi bi-arrow-left-right"></i> <?php echo htmlspecialchars($partnership['partner_name']); ?>
  </p>

  <div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
      <div class="info-tile left">
        <span class="t-label">Type</span>
        <span class="t-value"><?php echo htmlspecialchars($partnership['type']); ?></span>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="info-tile left">
        <span class="t-label">Start Date</span>
        <span class="t-value"><?php echo date('j M Y', strtotime($partnership['start_date'])); ?></span>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="info-tile left">
        <span class="t-label">End Date</span>
        <span class="t-value"><?php echo date('j M Y', strtotime($partnership['end_date'])); ?></span>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="info-tile left">
        <span class="t-label">SDG Goals</span>
        <span class="t-value">
          <?php
          $goals = explode(',', $partnership['sdg_goals']);
          $labels = array();

          foreach ($goals as $goal) {
              $goal = trim($goal);
              if ($goal != '') {
                  $labels[] = 'SDG ' . $goal;
              }
          }

          if (count($labels) > 0) {
              echo htmlspecialchars(implode(', ', $labels));
          } else {
              echo 'None recorded';
          }
          ?>
        </span>
      </div>
    </div>
  </div>

  <?php if ($partnership['objectives'] != '') { ?>
    <p class="section-label">Objectives</p>
    <p class="mb-4" style="color:#48545e;font-size:14.5px;line-height:1.7">
      <?php echo nl2br(htmlspecialchars($partnership['objectives'])); ?>
    </p>
  <?php } ?>

  <?php if ($partnership['expected_impact'] != '') { ?>
    <p class="section-label">Expected Impact</p>
    <p class="mb-4" style="color:#48545e;font-size:14.5px;line-height:1.7">
      <?php echo nl2br(htmlspecialchars($partnership['expected_impact'])); ?>
    </p>
  <?php } ?>

  <div class="card-v card-v-pad mb-4">
    <p class="section-label">Reported Impact</p>

    <?php if ($is_admin || $is_party) { ?>
      <form action="partnership-details.php?id=<?php echo $id; ?>" method="post">
        <div class="field mb-3">
          <input class="input-v" type="text" name="reported"
                 placeholder="e.g. 3,200 trees planted, 450 volunteers mobilised"
                 value="<?php echo htmlspecialchars($partnership['reported_impact']); ?>">
        </div>
        <button class="btn-v btn-soft btn-sm-v" type="submit">Save Reported Impact</button>
      </form>
    <?php } else { ?>
      <p style="font-size:14px;color:#6d7880">
        <?php
        if ($partnership['reported_impact'] != '') {
            echo htmlspecialchars($partnership['reported_impact']);
        } else {
            echo 'Nothing reported yet.';
        }
        ?>
      </p>
    <?php } ?>
  </div>

  <?php if ($status == 'pending' && $is_admin) { ?>
    <div class="row g-3">
      <div class="col-sm-6">
        <form action="partnership-details.php?id=<?php echo $id; ?>" method="post">
          <input type="hidden" name="decision" value="approve">
          <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Approve Partnership</button>
        </form>
      </div>
      <div class="col-sm-6">
        <form action="partnership-details.php?id=<?php echo $id; ?>" method="post">
          <input type="hidden" name="decision" value="reject">
          <button class="btn-v btn-red btn-block btn-lg-v" type="submit">Reject</button>
        </form>
      </div>
    </div>
  <?php } elseif (($status == 'active' || $status == 'expired') && ($is_admin || $is_party)) { ?>
    <div class="card-v card-v-pad">
      <p class="section-label">Renew Agreement</p>
      <form class="d-flex flex-wrap gap-3 align-items-end" action="partnership-details.php?id=<?php echo $id; ?>" method="post">
        <div class="field mb-0" style="flex:1 1 220px">
          <label class="field-label" for="end">New end date</label>
          <input class="input-v" type="date" id="end" name="end"
                 value="<?php echo htmlspecialchars($partnership['end_date']); ?>" required>
        </div>
        <button class="btn-v btn-green" type="submit">Renew</button>
      </form>
    </div>
  <?php } ?>

</div>

<?php include 'includes/app-footer.php'; ?>

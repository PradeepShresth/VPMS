<?php
$page_title = 'Partnerships | VPMS';
$active = 'partnerships';

require 'includes/auth.php';
require 'config/db.php';

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

$sql = 'SELECT p.*, asked.name AS asked_by, partner.name AS partner_name,
               (SELECT COUNT(*) FROM opportunity o WHERE o.partnership_id = p.partnership_id) AS projects,
               (SELECT COALESCE(SUM(ev.hours_logged), 0)
                  FROM event_volunteer ev
                  JOIN event e ON e.event_id = ev.event_id
                  JOIN opportunity o ON o.opportunity_id = e.opportunity_id
                 WHERE o.partnership_id = p.partnership_id AND ev.attended = 1) AS hours
        FROM partnership p
        LEFT JOIN organisation asked ON asked.organisation_id = p.organisation_id
        LEFT JOIN organisation partner ON partner.organisation_id = p.partner_id
        WHERE 1 = 1';
$values = array();

if ($filter != 'all') {
    $sql = $sql . ' AND p.status = ?';
    $values[] = $filter;
}

$sql = $sql . ' ORDER BY p.created_at DESC';

$find = $pdo->prepare($sql);
$find->execute($values);
$partnerships = $find->fetchAll();

$count = $pdo->query('SELECT COUNT(*) FROM partnership');
$total = $count->fetchColumn();

$today = date('Y-m-d');

include 'includes/app-header.php';
?>

<?php if (isset($_GET['done'])) { ?>
  <div class="banner"><i class="bi bi-check-circle-fill"></i>Partnership updated. Both parties have been notified.</div>
<?php } ?>

<div class="head-row">
  <div class="flex-grow-1">
    <h1 class="page-title">Partnerships</h1>
    <p class="page-sub"><?php echo $total; ?> total partnerships on the platform</p>
  </div>
  <?php if ($_SESSION['role_id'] == 2 || $_SESSION['role_id'] == 3 || $_SESSION['role_id'] == 6) { ?>
    <a class="btn-v btn-green" href="partnership-request.php">+ Request Partnership</a>
  <?php } ?>
</div>

<div class="d-flex flex-wrap gap-2 mb-4">
  <a class="pill <?php if ($filter == 'all') echo 'active'; ?>" href="partnerships.php?filter=all">All</a>
  <a class="pill <?php if ($filter == 'active') echo 'active'; ?>" href="partnerships.php?filter=active">Active</a>
  <a class="pill <?php if ($filter == 'pending') echo 'active'; ?>" href="partnerships.php?filter=pending">Pending</a>
  <a class="pill <?php if ($filter == 'expired') echo 'active'; ?>" href="partnerships.php?filter=expired">Expired</a>
  <a class="pill <?php if ($filter == 'rejected') echo 'active'; ?>" href="partnerships.php?filter=rejected">Rejected</a>
</div>

<?php if (count($partnerships) == 0) { ?>

  <div class="card-v card-v-pad text-center">
    <p class="row-title mb-2">No partnerships here</p>
    <p style="font-size:14px;color:#6d7880">
      Request one and it will appear here once an administrator approves it.
    </p>
  </div>

<?php } ?>

<?php foreach ($partnerships as $row) { ?>

  <?php
  $status = $row['status'];

  if ($status == 'active' && $row['end_date'] != '' && $row['end_date'] < $today) {
      $status = 'expired';
  }
  ?>

  <a class="card-v card-v-pad d-block mb-3" href="partnership-details.php?id=<?php echo $row['partnership_id']; ?>">
    <div class="d-flex align-items-start gap-3 mb-2">
      <span style="font-size:15.5px;font-weight:600">
        <?php echo htmlspecialchars($row['asked_by']); ?>
        <i class="bi bi-arrow-left-right mx-2" style="color:#16663e;font-size:14px"></i>
        <?php echo htmlspecialchars($row['partner_name']); ?>
      </span>
      <?php if ($status == 'active') { ?>
        <span class="badge-v badge-navy ms-auto">Active</span>
      <?php } elseif ($status == 'pending') { ?>
        <span class="badge-v badge-pending ms-auto">Pending</span>
      <?php } elseif ($status == 'expired') { ?>
        <span class="badge-v badge-grey ms-auto">Expired</span>
      <?php } else { ?>
        <span class="badge-v badge-grey ms-auto">Rejected</span>
      <?php } ?>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-3 mb-2">
      <span class="mono" style="font-size:12.5px;color:#6d7880">
        <?php echo htmlspecialchars($row['type']); ?>
        &nbsp;·&nbsp;
        <?php echo date('M Y', strtotime($row['start_date'])); ?> -
        <?php echo date('M Y', strtotime($row['end_date'])); ?>
      </span>
      <span class="d-flex flex-wrap gap-2 ms-auto">
        <?php
        $goals = explode(',', $row['sdg_goals']);
        foreach ($goals as $goal) {
            $goal = trim($goal);
            if ($goal != '') { ?>
              <span class="chip chip-green">SDG <?php echo htmlspecialchars($goal); ?></span>
        <?php }
        } ?>
      </span>
    </div>
    <p style="font-size:13.5px;color:#6d7880">
      <?php
      if ($status == 'pending') {
          echo 'Pending activation';
      } elseif ($row['projects'] > 0) {
          echo $row['projects'] . ' opportunities · ' . $row['hours'] . ' hours verified';
      } else {
          echo 'No work filed under this agreement yet';
      }
      ?>
    </p>
  </a>

<?php } ?>

<?php include 'includes/app-footer.php'; ?>

<?php
$page_title = 'Reports & Analytics | VPMS';
$active = 'reports';

require 'includes/auth.php';
require 'config/db.php';

$count = $pdo->prepare('SELECT COALESCE(SUM(hours_logged), 0) FROM event_volunteer WHERE attended = ?');
$count->execute(array(1));
$hours = $count->fetchColumn();

$count = $pdo->prepare('SELECT COUNT(*) FROM opportunity WHERE status = ?');
$count->execute(array('open'));
$projects = $count->fetchColumn();

$count = $pdo->query('SELECT COUNT(DISTINCT category) FROM opportunity');
$categories = $count->fetchColumn();

$count = $pdo->prepare('SELECT COUNT(*) FROM partnership WHERE status = ?');
$count->execute(array('active'));
$partnerships = $count->fetchColumn();

$count = $pdo->prepare('SELECT COUNT(*) FROM event_volunteer WHERE attended = ?');
$count->execute(array(1));
$attendances = $count->fetchColumn();

$find = $pdo->query(
    'SELECT r.*, u.full_name
     FROM report r
     JOIN `user` u ON u.user_id = r.generated_by
     ORDER BY r.created_at DESC'
);
$reports = $find->fetchAll();

$find = $pdo->prepare(
    'SELECT MONTH(e.event_date) AS month_number, COALESCE(SUM(ev.hours_logged), 0) AS hours
     FROM event_volunteer ev
     JOIN event e ON e.event_id = ev.event_id
     WHERE ev.attended = 1 AND YEAR(e.event_date) = ?
     GROUP BY MONTH(e.event_date)
     ORDER BY month_number'
);
$find->execute(array(date('Y')));
$months = $find->fetchAll();

// the busiest month becomes the full height bar and the rest scale off it
$tallest = 0;

foreach ($months as $row) {
    if ($row['hours'] > $tallest) {
        $tallest = $row['hours'];
    }
}

include 'includes/app-header.php';
?>

<?php if (isset($_GET['generated'])) { ?>
  <div class="banner"><i class="bi bi-check-circle-fill"></i>Report generated. Download it from the list below.</div>
<?php } ?>

<div class="head-row">
  <div class="flex-grow-1">
    <h1 class="page-title">Reports &amp; Analytics</h1>
    <p class="page-sub">Generate, download, and manage impact reports</p>
  </div>
  <?php if ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 4) { ?>
    <a class="btn-v btn-green" href="report-generate.php">+ Generate Report</a>
  <?php } ?>
</div>

<div class="row g-3 mb-4">
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <span class="stat-value"><?php echo $hours; ?></span>
      <span class="stat-label">Volunteer Hours</span>
      <span class="stat-note">Verified attendance</span>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <span class="stat-value"><?php echo $projects; ?></span>
      <span class="stat-label">Projects Active</span>
      <span class="stat-note">Across <?php echo $categories; ?> categories</span>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <span class="stat-value"><?php echo $partnerships; ?></span>
      <span class="stat-label">Partnerships</span>
      <span class="stat-note">Active agreements</span>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <span class="stat-value"><?php echo $attendances; ?></span>
      <span class="stat-label">Attendances Logged</span>
      <span class="stat-note">Across all events</span>
    </div>
  </div>
</div>

<p class="section-label">Recent Reports</p>

<div class="list-card mb-4">

  <?php if (count($reports) == 0) { ?>
    <div class="list-row">
      <span style="font-size:14px;color:#6d7880">
        No reports yet. Use Generate Report and it will appear here, ready to download.
      </span>
    </div>
  <?php } ?>

  <?php foreach ($reports as $row) { ?>
    <div class="list-row">
      <span class="row-icon"><i class="bi bi-bar-chart-fill"></i></span>
      <span class="flex-grow-1">
        <span class="row-title d-block"><?php echo htmlspecialchars($row['title']); ?></span>
        <span class="row-meta mono d-block">
          <?php echo htmlspecialchars($row['period']); ?>
          &nbsp;·&nbsp; <?php echo date('Y-m-d', strtotime($row['created_at'])); ?>
          &nbsp;·&nbsp; <?php echo htmlspecialchars($row['full_name']); ?>
        </span>
      </span>
      <?php if ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 4) { ?>
        <a class="btn-v btn-soft btn-sm-v" href="report-download.php?id=<?php echo $row['report_id']; ?>">Download</a>
      <?php } ?>
    </div>
  <?php } ?>

</div>

<div class="card-v card-v-pad">
  <p class="section-label">Volunteer Hours — <?php echo date('Y'); ?></p>

  <?php if (count($months) == 0) { ?>
    <p style="font-size:14px;color:#6d7880">
      No attendance has been recorded this year, so there is nothing to chart yet.
    </p>
  <?php } else { ?>
    <div class="mini-chart">
      <?php foreach ($months as $row) { ?>

        <?php
        if ($tallest > 0) {
            $height = round($row['hours'] / $tallest * 110);
        } else {
            $height = 0;
        }

        if ($height < 4) {
            $height = 4;
        }
        ?>

        <div class="mini-col">
          <div class="mini-val"><?php echo $row['hours']; ?></div>
          <div class="mini-bar" style="height:<?php echo $height; ?>px"></div>
          <div class="mini-lab"><?php echo date('M', mktime(0, 0, 0, $row['month_number'], 1)); ?></div>
        </div>
      <?php } ?>
    </div>
  <?php } ?>
</div>

<?php include 'includes/app-footer.php'; ?>

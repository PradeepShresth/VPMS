<?php
$active = 'events';

require 'includes/auth.php';
require 'config/db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$find = $pdo->prepare(
    'SELECT e.*, org.name AS organisation, u.organisation_name
     FROM event e
     LEFT JOIN organisation org ON org.organisation_id = e.organisation_id
     LEFT JOIN `user` u ON u.user_id = e.created_by
     WHERE e.event_id = ?'
);
$find->execute(array($id));
$event = $find->fetch();

if ($event == false) {
    header('Location: events.php');
    exit;
}

$page_title = $event['title'] . ' | VPMS';

$list = $pdo->prepare(
    'SELECT ev.status, ev.attended, u.full_name
     FROM event_volunteer ev
     JOIN `user` u ON u.user_id = ev.user_id
     WHERE ev.event_id = ?
     ORDER BY u.full_name'
);
$list->execute(array($id));
$roster = $list->fetchAll();

$joined = 0;

foreach ($roster as $person) {
    if ($person['status'] == 'confirmed') {
        $joined = $joined + 1;
    }
}

if ($event['volunteers_needed'] > 0) {
    $percent = round($joined / $event['volunteers_needed'] * 100);
} else {
    $percent = 0;
}

$today = date('Y-m-d');

if ($event['event_date'] == $today) {
    $when = 'Ongoing';
} elseif ($event['event_date'] > $today) {
    $when = 'Upcoming';
} else {
    $when = 'Completed';
}

if ($event['organisation'] != '') {
    $run_by = $event['organisation'];
} else {
    $run_by = $event['organisation_name'];
}

$can_manage = ($event['created_by'] == $_SESSION['user_id'] || $_SESSION['role_id'] == 4 || $_SESSION['role_id'] == 6);

include 'includes/app-header.php';
?>

<div class="col-mid mx-auto">

  <a class="back-link" href="events.php"><i class="bi bi-arrow-left"></i> Back</a>

  <?php if (isset($_GET['saved'])) { ?>
    <div class="banner"><i class="bi bi-check-circle-fill"></i>Attendance recorded.</div>
  <?php } ?>

  <div class="d-flex align-items-start gap-3 mb-1">
    <h1 class="page-title flex-grow-1"><?php echo htmlspecialchars($event['title']); ?></h1>
    <?php if ($when == 'Ongoing') { ?>
      <span class="badge-v badge-navy mt-2">Ongoing</span>
    <?php } elseif ($when == 'Upcoming') { ?>
      <span class="badge-v badge-blue mt-2">Upcoming</span>
    <?php } else { ?>
      <span class="badge-v badge-grey mt-2">Completed</span>
    <?php } ?>
  </div>

  <p class="mb-4" style="color:#16663e;font-size:14px">
    <?php echo htmlspecialchars($run_by); ?> · <?php echo htmlspecialchars($event['location']); ?>
  </p>

  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="info-tile">
        <span class="t-label">Date</span>
        <span class="t-value"><?php echo date('l, j F', strtotime($event['event_date'])); ?></span>
      </div>
    </div>
    <div class="col-md-4">
      <div class="info-tile">
        <span class="t-label">Time</span>
        <span class="t-value"><?php echo date('h:i A', strtotime($event['event_time'])); ?></span>
      </div>
    </div>
    <div class="col-md-4">
      <div class="info-tile">
        <span class="t-label">Category</span>
        <span class="t-value"><?php echo htmlspecialchars($event['category']); ?></span>
      </div>
    </div>
  </div>

  <div class="card-v card-v-pad mb-4">
    <div class="d-flex align-items-center mb-2">
      <span class="section-label mb-0">Volunteer Roster</span>
      <span class="mono ms-auto" style="font-size:13px;color:#6d7880">
        <?php echo $joined; ?>/<?php echo $event['volunteers_needed']; ?>
      </span>
    </div>

    <div class="bar mb-3"><span style="width:<?php echo min($percent, 100); ?>%"></span></div>

    <?php if (count($roster) == 0) { ?>
      <p style="font-size:13.5px;color:#6d7880">Nobody on the roster yet.</p>
    <?php } else { ?>
      <div class="d-flex flex-wrap gap-2">
        <?php foreach ($roster as $person) { ?>
          <span class="chip">
            <?php echo htmlspecialchars($person['full_name']); ?>
            <?php if ($person['attended'] == 1) { ?>
              <i class="bi bi-check-circle-fill" style="color:#16663e"></i>
            <?php } ?>
          </span>
        <?php } ?>
      </div>
    <?php } ?>
  </div>

  <?php if ($can_manage) { ?>
    <div class="row g-3">
      <div class="col-sm-6">
        <a class="btn-v btn-green btn-block" href="event-attendance.php?id=<?php echo $id; ?>">Record Attendance</a>
      </div>
      <div class="col-sm-6">
        <a class="btn-v btn-soft btn-block" href="event-volunteers.php?id=<?php echo $id; ?>">Manage Volunteers</a>
      </div>
    </div>
  <?php } ?>

</div>

<?php include 'includes/app-footer.php'; ?>

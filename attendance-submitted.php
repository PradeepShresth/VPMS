<?php
$page_title = 'Attendance Recorded | VPMS';
$active = 'events';

require 'includes/auth.php';
require 'config/db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$marked = isset($_GET['marked']) ? $_GET['marked'] : 0;

$find = $pdo->prepare('SELECT title FROM event WHERE event_id = ?');
$find->execute(array($id));
$event = $find->fetch();

if ($event == false) {
    header('Location: events.php');
    exit;
}

$count = $pdo->prepare('SELECT COUNT(*) FROM event_volunteer WHERE event_id = ? AND status = ?');
$count->execute(array($id, 'confirmed'));
$total = $count->fetchColumn();

$sum = $pdo->prepare('SELECT COALESCE(SUM(hours_logged), 0) FROM event_volunteer WHERE event_id = ?');
$sum->execute(array($id));
$hours = $sum->fetchColumn();

include 'includes/app-header.php';
?>

<a class="back-link" href="event-details.php?id=<?php echo $id; ?>"><i class="bi bi-arrow-left"></i> Back to Event</a>

<div class="success-wrap">
  <span class="success-icon"><i class="bi bi-check-lg"></i></span>
  <h1 class="success-title">Attendance Recorded</h1>
  <p class="success-text">
    <?php echo $marked; ?> of <?php echo $total; ?> volunteers were marked present for
    <strong><?php echo htmlspecialchars($event['title']); ?></strong>.
    That logged <?php echo $hours; ?> volunteer hours towards the platform totals.
  </p>
  <div class="d-flex justify-content-center gap-2">
    <a class="btn-v btn-green" href="event-details.php?id=<?php echo $id; ?>">Back to Event</a>
    <a class="btn-v btn-soft" href="events.php">All Events</a>
  </div>
</div>

<?php include 'includes/app-footer.php'; ?>

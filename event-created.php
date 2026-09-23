<?php
$page_title = 'Event Created | VPMS';
$active = 'events';

require 'includes/auth.php';
require 'config/db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$find = $pdo->prepare('SELECT title FROM event WHERE event_id = ?');
$find->execute(array($id));
$event = $find->fetch();

if ($event == false) {
    header('Location: events.php');
    exit;
}

$count = $pdo->prepare('SELECT COUNT(*) FROM event_volunteer WHERE event_id = ?');
$count->execute(array($id));
$roster = $count->fetchColumn();

include 'includes/app-header.php';
?>

<a class="back-link" href="events.php"><i class="bi bi-arrow-left"></i> Back to Events</a>

<div class="success-wrap">
  <span class="success-icon"><i class="bi bi-check-lg"></i></span>
  <h1 class="success-title">Event Created</h1>
  <p class="success-text">
    <strong><?php echo htmlspecialchars($event['title']); ?></strong> is now listed under Events.
    <?php if ($roster > 0) { ?>
      <?php echo $roster; ?> volunteers were added to the roster from the opportunity.
    <?php } else { ?>
      Add volunteers to the roster from the screen below.
    <?php } ?>
  </p>
  <div class="d-flex justify-content-center gap-2">
    <a class="btn-v btn-green" href="event-volunteers.php?id=<?php echo $id; ?>">Add Volunteers</a>
    <a class="btn-v btn-soft" href="events.php">Back to Events</a>
  </div>
</div>

<?php include 'includes/app-footer.php'; ?>

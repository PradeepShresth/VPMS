<?php
$page_title = 'Attendance Recorded | VPMS';
$active = 'events';

$marked = isset($_GET['marked']) ? $_GET['marked'] : 0;

include 'includes/app-header.php';
?>

<a class="back-link" href="event-details.php"><i class="bi bi-arrow-left"></i> Back to Event</a>

<div class="success-wrap">
  <span class="success-icon"><i class="bi bi-check-lg"></i></span>
  <h1 class="success-title">Attendance Recorded</h1>
  <p class="success-text">
    <?php echo $marked; ?> of 6 volunteers were marked present for Gotong-Royong Chow Kit 2026.
    Their hours are queued for verification by a Community Field Officer.
  </p>
  <div class="d-flex justify-content-center gap-2">
    <a class="btn-v btn-green" href="event-details.php">Back to Event</a>
    <a class="btn-v btn-soft" href="events.php">All Events</a>
  </div>
</div>

<?php include 'includes/app-footer.php'; ?>

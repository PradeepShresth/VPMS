<?php
$page_title = 'Event Created | VPMS';
$active = 'events';
include 'includes/app-header.php';
?>

<a class="back-link" href="events.php"><i class="bi bi-arrow-left"></i> Back to Events</a>

<div class="success-wrap">
  <span class="success-icon"><i class="bi bi-check-lg"></i></span>
  <h1 class="success-title">Event Created</h1>
  <p class="success-text">
    The event is now listed under Upcoming Events. Invite volunteers from the roster screen,
    or convert an existing opportunity into this event.
  </p>
  <div class="d-flex justify-content-center gap-2">
    <a class="btn-v btn-green" href="event-volunteers.php">Add Volunteers</a>
    <a class="btn-v btn-soft" href="events.php">Back to Events</a>
  </div>
</div>

<?php include 'includes/app-footer.php'; ?>

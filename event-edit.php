<?php
$page_title = 'Edit Event | VPMS';
$active = 'events';

require 'includes/auth.php';
require 'config/db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$find = $pdo->prepare('SELECT * FROM event WHERE event_id = ?');
$find->execute(array($id));
$event = $find->fetch();

if ($event == false) {
    header('Location: events.php');
    exit;
}

if ($event['created_by'] != $_SESSION['user_id'] && $_SESSION['role_id'] != 6) {
    header('Location: event-details.php?id=' . $id);
    exit;
}

$errors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event['title'] = trim($_POST['title']);
    $event['location'] = trim($_POST['location']);
    $event['event_date'] = $_POST['date'];
    $event['event_time'] = $_POST['time'];
    $event['volunteers_needed'] = $_POST['needed'];
    $event['category'] = $_POST['category'];

    if ($event['title'] == '') {
        $errors[] = 'Give the event a title.';
    }

    if ($event['event_date'] == '') {
        $errors[] = 'Pick the date it takes place.';
    }

    if ($event['event_time'] == '') {
        $errors[] = 'Pick a start time.';
    }

    if (count($errors) == 0) {
        $save = $pdo->prepare(
            'UPDATE event
                SET title = ?, location = ?, event_date = ?, event_time = ?,
                    volunteers_needed = ?, category = ?
              WHERE event_id = ?'
        );

        $save->execute(array(
            $event['title'],
            $event['location'],
            $event['event_date'],
            $event['event_time'],
            $event['volunteers_needed'],
            $event['category'],
            $id
        ));

        header('Location: event-details.php?id=' . $id . '&saved=1');
        exit;
    }
}

include 'includes/app-header.php';
?>

<div class="col-narrow mx-auto">

  <a class="back-link" href="event-details.php?id=<?php echo $id; ?>">
    <i class="bi bi-arrow-left"></i> Back to Event
  </a>

  <h1 class="page-title mb-4">Edit Event</h1>

  <?php if (count($errors) > 0) { ?>
    <div class="notice mb-4" style="border-left-color:#c8504b;background:#fdf3f2">
      <p class="notice-title" style="color:#c8504b">Please fix the following</p>
      <?php foreach ($errors as $error) { ?>
        <p class="notice-text"><?php echo $error; ?></p>
      <?php } ?>
    </div>
  <?php } ?>

  <form action="event-edit.php?id=<?php echo $id; ?>" method="post">

    <div class="field">
      <label class="field-label" for="title">Event Title</label>
      <input class="input-v" type="text" id="title" name="title"
             value="<?php echo htmlspecialchars($event['title']); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="location">Location</label>
      <input class="input-v" type="text" id="location" name="location"
             value="<?php echo htmlspecialchars($event['location']); ?>">
    </div>

    <div class="row g-3">
      <div class="col-sm-6">
        <div class="field">
          <label class="field-label" for="date">Date</label>
          <input class="input-v" type="date" id="date" name="date"
                 value="<?php echo htmlspecialchars($event['event_date']); ?>">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="field">
          <label class="field-label" for="time">Time</label>
          <input class="input-v" type="time" id="time" name="time"
                 value="<?php echo htmlspecialchars($event['event_time']); ?>">
        </div>
      </div>
    </div>

    <div class="field">
      <label class="field-label" for="needed">Volunteers Needed</label>
      <input class="input-v" type="number" id="needed" name="needed"
             value="<?php echo htmlspecialchars($event['volunteers_needed']); ?>">
    </div>

    <div class="field mb-4">
      <label class="field-label" for="category">Category</label>
      <select class="select-v" id="category" name="category">
        <option <?php if ($event['category'] == 'Community Service') echo 'selected'; ?>>Community Service</option>
        <option <?php if ($event['category'] == 'Environment') echo 'selected'; ?>>Environment</option>
        <option <?php if ($event['category'] == 'Education') echo 'selected'; ?>>Education</option>
        <option <?php if ($event['category'] == 'Food Security') echo 'selected'; ?>>Food Security</option>
        <option <?php if ($event['category'] == 'Health') echo 'selected'; ?>>Health</option>
        <option <?php if ($event['category'] == 'Disaster Relief') echo 'selected'; ?>>Disaster Relief</option>
        <option <?php if ($event['category'] == 'Technology') echo 'selected'; ?>>Technology</option>
      </select>
    </div>

    <div class="row g-3">
      <div class="col-5">
        <a class="btn-v btn-outline btn-block btn-lg-v" href="event-details.php?id=<?php echo $id; ?>">Cancel</a>
      </div>
      <div class="col-7">
        <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Save Changes</button>
      </div>
    </div>
  </form>

</div>

<?php include 'includes/app-footer.php'; ?>

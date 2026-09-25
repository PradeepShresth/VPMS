<?php
$page_title = 'Create Event | VPMS';
$active = 'events';

require 'includes/auth.php';
require 'config/db.php';

if ($_SESSION['role_id'] != 2 && $_SESSION['role_id'] != 6) {
    header('Location: events.php');
    exit;
}

// the form starts empty, unless an opportunity is being converted
$errors = array();
$title = '';
$location = '';
$date = '';
$time = '';
$needed = 25;
$category = 'Community Service';

$opportunity_id = isset($_GET['opportunity_id']) ? $_GET['opportunity_id'] : 0;
$opportunity = false;

if ($opportunity_id > 0) {
    $find = $pdo->prepare('SELECT * FROM opportunity WHERE opportunity_id = ?');
    $find->execute(array($opportunity_id));
    $opportunity = $find->fetch();

    if ($opportunity != false) {
        $title = $opportunity['title'];
        $location = $opportunity['location'];
        $date = $opportunity['opportunity_date'];
        $needed = $opportunity['spots'];
        $category = $opportunity['category'];
    }
}

// who would come across if it were converted right now
$taken_count = 0;
$waiting_count = 0;

if ($opportunity != false) {
    $count = $pdo->prepare('SELECT COUNT(*) FROM application WHERE opportunity_id = ? AND status = ?');
    $count->execute(array($opportunity_id, 'accepted'));
    $taken_count = $count->fetchColumn();

    $count->execute(array($opportunity_id, 'pending'));
    $waiting_count = $count->fetchColumn();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $location = trim($_POST['location']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $needed = $_POST['needed'];
    $category = $_POST['category'];
    $opportunity_id = $_POST['opportunity_id'];

    if ($title == '') {
        $errors[] = 'Give the event a title.';
    }

    if ($date == '') {
        $errors[] = 'Pick the date it takes place.';
    }

    if ($time == '') {
        $errors[] = 'Pick a start time.';
    }

    if (count($errors) == 0) {
        if ($opportunity_id > 0) {
            $linked = $opportunity_id;
        } else {
            $linked = null;
        }

        $save = $pdo->prepare(
            'INSERT INTO event (title, opportunity_id, organization_id, created_by, location,
                                event_date, event_time, volunteers_needed, category)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );

        $save->execute(array(
            $title,
            $linked,
            $_SESSION['organization_id'],
            $_SESSION['user_id'],
            $location,
            $date,
            $time,
            $needed,
            $category
        ));

        $event_id = $pdo->lastInsertId();

        if ($linked != null) {
            $add = $pdo->prepare(
                'INSERT INTO event_volunteer (event_id, user_id, status) VALUES (?, ?, ?)'
            );

            // whoever was already accepted for the opportunity goes straight on the roster
            $accepted = $pdo->prepare(
                'SELECT user_id FROM application WHERE opportunity_id = ? AND status = ?'
            );
            $accepted->execute(array($linked, 'accepted'));
            $taken = $accepted->fetchAll();

            foreach ($taken as $volunteer) {
                $add->execute(array($event_id, $volunteer['user_id'], 'confirmed'));
            }

            // the ones still waiting on an answer can come along on the waitlist
            if (isset($_POST['waitlist_pending'])) {
                $waiting = $pdo->prepare(
                    'SELECT user_id FROM application WHERE opportunity_id = ? AND status = ?'
                );
                $waiting->execute(array($linked, 'pending'));

                foreach ($waiting->fetchAll() as $volunteer) {
                    $add->execute(array($event_id, $volunteer['user_id'], 'waitlist'));
                }
            }

            // the work is scheduled now, so it stops taking applications.
            // Reopen on the opportunity page puts it back if that was too early.
            $close = $pdo->prepare('UPDATE opportunity SET status = ? WHERE opportunity_id = ?');
            $close->execute(array('closed', $linked));
        }

        header('Location: event-created.php?id=' . $event_id);
        exit;
    }
}

include 'includes/app-header.php';
?>

<div class="col-narrow mx-auto">

  <a class="back-link" href="events.php"><i class="bi bi-arrow-left"></i> Back</a>

  <h1 class="page-title mb-4">Create Event</h1>

  <?php if ($opportunity != false) { ?>
    <div class="notice mb-4">
      <p class="notice-title">Converting an opportunity</p>
      <p class="notice-text">
        <strong><?php echo htmlspecialchars($opportunity['title']); ?></strong> has
        <?php echo $taken_count; ?> accepted
        <?php if ($waiting_count > 0) { ?>
          and <?php echo $waiting_count; ?> still waiting on an answer.
        <?php } else { ?>
          and nobody waiting on an answer.
        <?php } ?>
      </p>
      <p class="notice-text">
        <?php if ($taken_count > 0) { ?>
          The accepted volunteers go on the roster and the opportunity stops taking applications.
        <?php } else { ?>
          Nobody has been accepted yet, so the roster starts empty. The opportunity still stops
          taking applications &mdash; reopen it from its own page if you need more.
        <?php } ?>
      </p>
    </div>
  <?php } ?>

  <?php if (count($errors) > 0) { ?>
    <div class="notice mb-4" style="border-left-color:#c8504b;background:#fdf3f2">
      <p class="notice-title" style="color:#c8504b">Please fix the following</p>
      <?php foreach ($errors as $error) { ?>
        <p class="notice-text"><?php echo $error; ?></p>
      <?php } ?>
    </div>
  <?php } ?>

  <form action="event-create.php" method="post">
    <input type="hidden" name="opportunity_id" value="<?php echo $opportunity_id; ?>">

    <?php if ($waiting_count > 0) { ?>
      <div class="field">
        <label class="check-v">
          <input type="checkbox" name="waitlist_pending" checked>
          Also add the <?php echo $waiting_count; ?> undecided
          <?php if ($waiting_count == 1) { ?>applicant<?php } else { ?>applicants<?php } ?>
          to the roster as waitlist
        </label>
      </div>
    <?php } ?>

    <div class="field">
      <label class="field-label" for="title">Event Title</label>
      <input class="input-v" type="text" id="title" name="title" placeholder="Gotong-Royong Chow Kit"
             value="<?php echo htmlspecialchars($title); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="location">Location</label>
      <input class="input-v" type="text" id="location" name="location" placeholder="Chow Kit, Kuala Lumpur"
             value="<?php echo htmlspecialchars($location); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="date">Date</label>
      <input class="input-v" type="date" id="date" name="date" value="<?php echo htmlspecialchars($date); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="time">Time</label>
      <input class="input-v" type="time" id="time" name="time" value="<?php echo htmlspecialchars($time); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="needed">Volunteers Needed</label>
      <input class="input-v" type="number" id="needed" name="needed" value="<?php echo htmlspecialchars($needed); ?>">
    </div>

    <div class="field mb-4">
      <label class="field-label" for="category">Category</label>
      <select class="select-v" id="category" name="category">
        <option <?php if ($category == 'Community Service') echo 'selected'; ?>>Community Service</option>
        <option <?php if ($category == 'Environment') echo 'selected'; ?>>Environment</option>
        <option <?php if ($category == 'Education') echo 'selected'; ?>>Education</option>
        <option <?php if ($category == 'Food Security') echo 'selected'; ?>>Food Security</option>
        <option <?php if ($category == 'Health') echo 'selected'; ?>>Health</option>
        <option <?php if ($category == 'Disaster Relief') echo 'selected'; ?>>Disaster Relief</option>
        <option <?php if ($category == 'Technology') echo 'selected'; ?>>Technology</option>
      </select>
    </div>

    <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Create Event</button>
  </form>

</div>

<?php include 'includes/app-footer.php'; ?>

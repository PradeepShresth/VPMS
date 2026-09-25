<?php
$page_title = 'Record Attendance | VPMS';
$active = 'events';

require 'includes/auth.php';
require 'config/db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$find = $pdo->prepare(
    'SELECT e.*, o.hours_required
     FROM event e
     LEFT JOIN opportunity o ON o.opportunity_id = e.opportunity_id
     WHERE e.event_id = ?'
);
$find->execute(array($id));
$event = $find->fetch();

if ($event == false) {
    header('Location: events.php');
    exit;
}

// this event is ours to run if we own it, or if we are partnered with whoever does
$manages = false;

if ($_SESSION['role_id'] == 6 || $event['created_by'] == $_SESSION['user_id']) {
    $manages = true;
} elseif (($_SESSION['role_id'] == 2 || $_SESSION['role_id'] == 4)
       && $event['organization_id'] != '' && $_SESSION['organization_id'] != '') {

    if ($event['organization_id'] == $_SESSION['organization_id']) {
        $manages = true;
    } else {
        $together = $pdo->prepare(
            'SELECT partnership_id FROM partnership
              WHERE status = ?
                AND ((organization_id = ? AND partner_id = ?)
                  OR (organization_id = ? AND partner_id = ?))'
        );
        $together->execute(array('active',
            $_SESSION['organization_id'], $event['organization_id'],
            $event['organization_id'], $_SESSION['organization_id']));

        if ($together->fetch() != false) {
            $manages = true;
        }
    }
}

if (!$manages) {
    header('Location: event-details.php?id=' . $id);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // start by clearing the whole roster, then mark the ones that were ticked
    $clear = $pdo->prepare('UPDATE event_volunteer SET attended = 0, hours_logged = 0 WHERE event_id = ?');
    $clear->execute(array($id));

    $marked = 0;

    if (isset($_POST['present'])) {
        $mark = $pdo->prepare(
            'UPDATE event_volunteer SET attended = 1, hours_logged = ?
              WHERE event_volunteer_id = ? AND event_id = ?'
        );

        foreach ($_POST['present'] as $event_volunteer_id) {
            // each volunteer has their own box, because half a day is not a full day
            $hours = $_POST['hours'][$event_volunteer_id];

            if ($hours < 0 || $hours == '') {
                $hours = 0;
            }

            $mark->execute(array($hours, $event_volunteer_id, $id));
            $marked = $marked + 1;
        }
    }

    header('Location: attendance-submitted.php?id=' . $id . '&marked=' . $marked);
    exit;
}

// the availability comes from what they ticked when they applied
$list = $pdo->prepare(
    'SELECT ev.event_volunteer_id, ev.attended, ev.hours_logged, u.full_name,
            (SELECT a.availability FROM application a
              WHERE a.user_id = ev.user_id AND a.opportunity_id = ?) AS availability
     FROM event_volunteer ev
     JOIN `user` u ON u.user_id = ev.user_id
     WHERE ev.event_id = ? AND ev.status = ?
     ORDER BY u.full_name'
);
$list->execute(array($event['opportunity_id'], $id, 'confirmed'));
$roster = $list->fetchAll();

if ($event['hours_required'] > 0) {
    $default_hours = $event['hours_required'];
} else {
    $default_hours = 4;
}

include 'includes/app-header.php';
?>

<div class="col-narrow mx-auto">

  <a class="back-link" href="event-details.php?id=<?php echo $id; ?>"><i class="bi bi-arrow-left"></i> Back to Event</a>

  <h1 class="page-title">Record Attendance</h1>
  <p class="page-sub mb-4"><?php echo htmlspecialchars($event['title']); ?></p>

  <div class="notice mb-4">
    <p class="notice-title">QR Scan Mode Active</p>
    <p class="notice-text">
      Tap a volunteer to mark present — in production, this would scan their VPMS QR badge.
    </p>
  </div>

  <?php if (count($roster) == 0) { ?>

    <div class="card-v card-v-pad text-center">
      <p class="row-title mb-2">Nobody to mark</p>
      <p style="font-size:14px;color:#6d7880">
        Confirm volunteers on the roster first, then come back to record attendance.
      </p>
      <a class="btn-v btn-soft mt-3" href="event-volunteers.php?id=<?php echo $id; ?>">Manage Volunteers</a>
    </div>

  <?php } else { ?>

  <form action="event-attendance.php?id=<?php echo $id; ?>" method="post">

    <?php foreach ($roster as $person) { ?>

      <?php
      // somebody who only signed up for half the day earns half the hours
      if (strpos($person['availability'], 'Morning') !== false
       || strpos($person['availability'], 'Afternoon') !== false) {
          $their_hours = round($default_hours / 2);
      } else {
          $their_hours = $default_hours;
      }

      // once attendance has been recorded, show what was actually credited
      if ($person['attended'] == 1) {
          $their_hours = $person['hours_logged'];
      }

      if ($person['availability'] != '') {
          $when = $person['availability'];
      } else {
          $when = 'No availability given';
      }
      ?>

      <div class="card-v card-v-pad d-flex flex-wrap align-items-center gap-3 w-100 mb-3 roster-row">
        <label class="d-flex align-items-center gap-3 flex-grow-1" style="cursor:pointer;margin:0">
          <input type="checkbox" name="present[]" value="<?php echo $person['event_volunteer_id']; ?>"
                 style="display:none" <?php if ($person['attended'] == 1) echo 'checked'; ?>>
          <span class="avatar-circle grey"><?php echo strtoupper(substr($person['full_name'], 0, 1)); ?></span>
          <span>
            <span class="d-block" style="font-size:14.5px;font-weight:500">
              <?php echo htmlspecialchars($person['full_name']); ?>
            </span>
            <span class="d-block" style="font-size:12.5px;color:#6d7880">
              <?php echo htmlspecialchars($when); ?>
            </span>
          </span>
          <span class="tick" style="color:#16663e;font-size:18px"><i class="bi bi-check-circle-fill"></i></span>
        </label>

        <span class="d-flex align-items-center gap-2">
          <input class="input-v" type="number" style="width:82px"
                 name="hours[<?php echo $person['event_volunteer_id']; ?>]"
                 value="<?php echo $their_hours; ?>">
          <span style="font-size:13px;color:#6d7880">hrs</span>
        </span>
      </div>
    <?php } ?>

    <p class="mb-4" style="font-size:13px;color:#6d7880">
      Hours start from the <?php echo $default_hours; ?> this work is worth, halved for anyone who
      only signed up for part of the day. Change any of them if somebody left early or stayed on.
    </p>

    <button class="btn-v btn-green btn-block btn-lg-v" type="submit">
      Submit Attendance (<span id="count">0</span>/<?php echo count($roster); ?>)
    </button>
  </form>

  <script>
  // tap a row to mark that volunteer present
  var rows = document.querySelectorAll('.roster-row');
  var count = document.getElementById('count');

  function refresh() {
    var marked = 0;

    for (var i = 0; i < rows.length; i++) {
      var box = rows[i].querySelector('input');
      var tick = rows[i].querySelector('.tick');

      if (box.checked) {
        tick.style.display = 'inline';
        rows[i].style.borderColor = '#16663e';
        marked = marked + 1;
      } else {
        tick.style.display = 'none';
        rows[i].style.borderColor = '';
      }
    }

    count.innerHTML = marked;
  }

  for (var i = 0; i < rows.length; i++) {
    rows[i].querySelector('input').onchange = refresh;
  }

  refresh();
  </script>

  <?php } ?>

</div>

<?php include 'includes/app-footer.php'; ?>

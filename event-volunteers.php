<?php
$page_title = 'Manage Volunteers | VPMS';
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
    if (isset($_POST['add'])) {
        $check = $pdo->prepare('SELECT event_volunteer_id FROM event_volunteer WHERE event_id = ? AND user_id = ?');
        $check->execute(array($id, $_POST['add']));

        if ($check->fetch() == false) {
            $add = $pdo->prepare('INSERT INTO event_volunteer (event_id, user_id, status) VALUES (?, ?, ?)');
            $add->execute(array($id, $_POST['add'], 'confirmed'));
        }
    }

    if (isset($_POST['confirm'])) {
        $update = $pdo->prepare('UPDATE event_volunteer SET status = ? WHERE event_volunteer_id = ? AND event_id = ?');
        $update->execute(array('confirmed', $_POST['confirm'], $id));
    }

    if (isset($_POST['remove'])) {
        $remove = $pdo->prepare('DELETE FROM event_volunteer WHERE event_volunteer_id = ? AND event_id = ?');
        $remove->execute(array($_POST['remove'], $id));
    }

    // everyone the coordinator already accepted on the opportunity, in one go
    if (isset($_POST['add_all'])) {
        $accepted = $pdo->prepare(
            'SELECT user_id FROM application WHERE opportunity_id = ? AND status = ?'
        );
        $accepted->execute(array($event['opportunity_id'], 'accepted'));

        $check = $pdo->prepare(
            'SELECT event_volunteer_id FROM event_volunteer WHERE event_id = ? AND user_id = ?'
        );
        $add = $pdo->prepare('INSERT INTO event_volunteer (event_id, user_id, status) VALUES (?, ?, ?)');

        foreach ($accepted->fetchAll() as $volunteer) {
            $check->execute(array($id, $volunteer['user_id']));

            if ($check->fetch() == false) {
                $add->execute(array($id, $volunteer['user_id'], 'confirmed'));
            }
        }
    }

    header('Location: event-volunteers.php?id=' . $id . '&done=1');
    exit;
}

$list = $pdo->prepare(
    'SELECT ev.*, u.full_name, u.email, r.name AS role_name
     FROM event_volunteer ev
     JOIN `user` u ON u.user_id = ev.user_id
     JOIN role r ON r.role_id = u.role_id
     WHERE ev.event_id = ?
     ORDER BY ev.status, u.full_name'
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

// people who applied to the opportunity but are not on the roster yet
$applicants = array();

if ($event['opportunity_id'] != '') {
    $find = $pdo->prepare(
        'SELECT a.status, u.user_id, u.full_name, u.email
         FROM application a
         JOIN `user` u ON u.user_id = a.user_id
         WHERE a.opportunity_id = ?
           AND u.user_id NOT IN (SELECT user_id FROM event_volunteer WHERE event_id = ?)
         ORDER BY a.status, u.full_name'
    );
    $find->execute(array($event['opportunity_id'], $id));
    $applicants = $find->fetchAll();
}

$waiting_accepted = 0;

foreach ($applicants as $person) {
    if ($person['status'] == 'accepted') {
        $waiting_accepted = $waiting_accepted + 1;
    }
}

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$matches = array();

if ($search != '') {
    $look = $pdo->prepare(
        'SELECT u.user_id, u.full_name, u.email, r.name AS role_name
         FROM `user` u
         JOIN role r ON r.role_id = u.role_id
         WHERE u.status = ? AND (u.full_name LIKE ? OR u.email LIKE ?)
           AND u.user_id NOT IN (SELECT user_id FROM event_volunteer WHERE event_id = ?)
         ORDER BY u.full_name'
    );
    $look->execute(array('active', '%' . $search . '%', '%' . $search . '%', $id));
    $matches = $look->fetchAll();
}

include 'includes/app-header.php';
?>

<a class="back-link" href="event-details.php?id=<?php echo $id; ?>"><i class="bi bi-arrow-left"></i> Back to Event</a>

<?php if (isset($_GET['done'])) { ?>
  <div class="banner"><i class="bi bi-check-circle-fill"></i>Roster updated.</div>
<?php } ?>

<div class="head-row">
  <div class="flex-grow-1">
    <h1 class="page-title">Manage Volunteers</h1>
    <p class="page-sub"><?php echo htmlspecialchars($event['title']); ?></p>
  </div>
</div>

<div class="card-v card-v-pad mb-4">
  <div class="d-flex align-items-center mb-2">
    <span class="section-label mb-0">Roster capacity</span>
    <span class="mono ms-auto" style="font-size:13px;color:#6d7880">
      <?php echo $joined; ?>/<?php echo $event['volunteers_needed']; ?>
    </span>
  </div>
  <div class="bar"><span style="width:<?php echo min($percent, 100); ?>%"></span></div>
</div>

<?php if (count($applicants) > 0) { ?>
  <div class="card-v card-v-pad mb-4">
    <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
      <span class="section-label mb-0">Applied to the opportunity, not on the roster</span>
      <?php if ($waiting_accepted > 0) { ?>
        <form class="ms-auto" action="event-volunteers.php?id=<?php echo $id; ?>" method="post">
          <input type="hidden" name="add_all" value="1">
          <button class="btn-v btn-green btn-sm-v" type="submit">
            Add all <?php echo $waiting_accepted; ?> accepted
          </button>
        </form>
      <?php } ?>
    </div>

    <?php foreach ($applicants as $person) { ?>
      <div class="d-flex align-items-center gap-3 mb-2">
        <span class="avatar-circle grey"><?php echo strtoupper(substr($person['full_name'], 0, 1)); ?></span>
        <span class="flex-grow-1">
          <span class="d-block fw-bold"><?php echo htmlspecialchars($person['full_name']); ?></span>
          <span class="d-block" style="font-size:12.5px;color:#6d7880"><?php echo htmlspecialchars($person['email']); ?></span>
        </span>
        <?php if ($person['status'] == 'accepted') { ?>
          <span class="badge-v badge-green">Accepted</span>
        <?php } elseif ($person['status'] == 'pending') { ?>
          <span class="badge-v badge-pending">Undecided</span>
        <?php } else { ?>
          <span class="badge-v badge-grey">Rejected</span>
        <?php } ?>
        <form action="event-volunteers.php?id=<?php echo $id; ?>" method="post">
          <input type="hidden" name="add" value="<?php echo $person['user_id']; ?>">
          <button class="btn-v btn-outline btn-sm-v" type="submit">+ Add</button>
        </form>
      </div>
    <?php } ?>
  </div>
<?php } ?>

<div class="card-v card-v-pad mb-4">
  <p class="section-label">Add a volunteer</p>
  <form class="d-flex flex-wrap gap-3" action="event-volunteers.php" method="get">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <input class="input-v" style="flex:1 1 280px" type="search" name="q"
           placeholder="Search registered volunteers by name or email"
           value="<?php echo htmlspecialchars($search); ?>">
    <button class="btn-v btn-green" type="submit">Search</button>
  </form>

  <?php if ($search != '') { ?>
    <div class="mt-3">
      <?php if (count($matches) == 0) { ?>
        <p style="font-size:13.5px;color:#6d7880">Nobody active matches that, or they are already on the roster.</p>
      <?php } ?>

      <?php foreach ($matches as $person) { ?>
        <div class="d-flex align-items-center gap-3 mb-2">
          <span class="avatar-circle grey"><?php echo strtoupper(substr($person['full_name'], 0, 1)); ?></span>
          <span class="flex-grow-1">
            <span class="d-block fw-bold"><?php echo htmlspecialchars($person['full_name']); ?></span>
            <span class="d-block" style="font-size:12.5px;color:#6d7880">
              <?php echo htmlspecialchars($person['role_name']); ?> · <?php echo htmlspecialchars($person['email']); ?>
            </span>
          </span>
          <form action="event-volunteers.php?id=<?php echo $id; ?>" method="post">
            <input type="hidden" name="add" value="<?php echo $person['user_id']; ?>">
            <button class="btn-v btn-green btn-sm-v" type="submit">+ Add to Roster</button>
          </form>
        </div>
      <?php } ?>
    </div>
  <?php } ?>
</div>

<?php if (count($roster) == 0) { ?>

  <div class="card-v card-v-pad text-center">
    <p class="row-title mb-2">The roster is empty</p>
    <p style="font-size:14px;color:#6d7880">Search above to add registered volunteers to this event.</p>
  </div>

<?php } else { ?>

<div class="list-card">
  <div class="table-responsive">
    <table class="table-v">
      <tr>
        <th>Volunteer</th>
        <th>Role</th>
        <th>Contact</th>
        <th>Status</th>
        <th></th>
      </tr>

      <?php foreach ($roster as $person) { ?>
        <tr>
          <td>
            <span class="d-flex align-items-center gap-3">
              <span class="avatar-circle grey"><?php echo strtoupper(substr($person['full_name'], 0, 1)); ?></span>
              <span class="fw-bold"><?php echo htmlspecialchars($person['full_name']); ?></span>
            </span>
          </td>
          <td class="td-muted"><?php echo htmlspecialchars($person['role_in_event']); ?></td>
          <td class="td-muted mono" style="font-size:12.5px"><?php echo htmlspecialchars($person['email']); ?></td>
          <td>
            <?php if ($person['status'] == 'confirmed') { ?>
              <span class="badge-v badge-navy">Confirmed</span>
            <?php } else { ?>
              <span class="badge-v badge-pending">Waitlist</span>
            <?php } ?>
          </td>
          <td class="text-end">
            <span class="d-flex gap-2 justify-content-end">
              <?php if ($person['status'] != 'confirmed') { ?>
                <form action="event-volunteers.php?id=<?php echo $id; ?>" method="post">
                  <input type="hidden" name="confirm" value="<?php echo $person['event_volunteer_id']; ?>">
                  <button class="btn-v btn-green btn-sm-v" type="submit">Confirm</button>
                </form>
              <?php } ?>
              <form action="event-volunteers.php?id=<?php echo $id; ?>" method="post">
                <input type="hidden" name="remove" value="<?php echo $person['event_volunteer_id']; ?>">
                <button class="btn-v btn-outline btn-sm-v" type="submit">Remove</button>
              </form>
            </span>
          </td>
        </tr>
      <?php } ?>

    </table>
  </div>
</div>

<?php } ?>

<?php include 'includes/app-footer.php'; ?>

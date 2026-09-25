<?php
$active = 'events';

require 'includes/auth.php';
require 'config/db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$find = $pdo->prepare(
    'SELECT e.*, org.name AS organization, u.organization_name,
            o.title AS opportunity_title, o.description AS opportunity_description,
            o.skills AS opportunity_skills, o.sdg_goals AS opportunity_goals,
            o.hours_required, o.partnership_id,
            a.name AS partner_one, b.name AS partner_two
     FROM event e
     LEFT JOIN organization org ON org.organization_id = e.organization_id
     LEFT JOIN `user` u ON u.user_id = e.created_by
     LEFT JOIN opportunity o ON o.opportunity_id = e.opportunity_id
     LEFT JOIN partnership p ON p.partnership_id = o.partnership_id
     LEFT JOIN organization a ON a.organization_id = p.organization_id
     LEFT JOIN organization b ON b.organization_id = p.partner_id
     WHERE e.event_id = ?'
);
$find->execute(array($id));
$event = $find->fetch();

if ($event == false) {
    header('Location: events.php');
    exit;
}

$page_title = $event['title'] . ' | VPMS';

// hours already logged here feed the partnership and SDG totals, so the event
// cannot simply be thrown away once attendance has been taken
$count = $pdo->prepare('SELECT COUNT(*) FROM event_volunteer WHERE event_id = ? AND attended = 1');
$count->execute(array($id));
$marked_present = $count->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {

    if ($event['created_by'] == $_SESSION['user_id'] || $_SESSION['role_id'] == 6) {
        if ($marked_present == 0) {
            $clear = $pdo->prepare('DELETE FROM event_volunteer WHERE event_id = ?');
            $clear->execute(array($id));

            $remove = $pdo->prepare('DELETE FROM event WHERE event_id = ?');
            $remove->execute(array($id));

            header('Location: events.php?deleted=1');
            exit;
        }
    }

    header('Location: event-details.php?id=' . $id);
    exit;
}

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

if ($event['organization'] != '') {
    $run_by = $event['organization'];
} else {
    $run_by = $event['organization_name'];
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

$can_manage = $manages;

// money a sponsor put behind the opportunity this event came from
$sponsorships = array();
$sponsored = 0;

if ($event['opportunity_id'] != '') {
    $find = $pdo->prepare(
        'SELECT s.amount, s.note, s.status, u.full_name, o.name AS sponsor_organization
         FROM sponsorship s
         JOIN `user` u ON u.user_id = s.sponsor_id
         LEFT JOIN organization o ON o.organization_id = s.organization_id
         WHERE s.opportunity_id = ?
         ORDER BY s.created_at'
    );
    $find->execute(array($event['opportunity_id']));
    $sponsorships = $find->fetchAll();

    foreach ($sponsorships as $row) {
        if ($row['status'] == 'accepted') {
            $sponsored = $sponsored + $row['amount'];
        }
    }
}

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
      <span class="badge-v badge-green mt-2">Ongoing</span>
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

  <?php if ($event['opportunity_id'] != '') { ?>

    <a class="card-v card-v-pad d-block mb-4" href="opportunity-details.php?id=<?php echo $event['opportunity_id']; ?>">
      <p class="section-label mb-1">From the opportunity</p>
      <p style="font-size:14.5px;font-weight:600">
        <?php echo htmlspecialchars($event['opportunity_title']); ?>
        &nbsp;·&nbsp; <?php echo $event['hours_required']; ?> hrs
      </p>
    </a>

    <?php if ($event['partnership_id'] != '') { ?>
      <a class="card-v card-partner card-v-pad d-block mb-4" href="partnership-details.php?id=<?php echo $event['partnership_id']; ?>">
        <p class="section-label mb-1">Part of a partnership</p>
        <p style="font-size:14.5px;font-weight:600">
          <?php echo htmlspecialchars($event['partner_one']); ?>
          <i class="bi bi-arrow-left-right mx-2" style="color:#16663e;font-size:13px"></i>
          <?php echo htmlspecialchars($event['partner_two']); ?>
        </p>
        <p style="font-size:13px;color:#6d7880">Hours recorded here count towards this agreement.</p>
      </a>
    <?php } ?>

    <?php if ($sponsored > 0 || ($manages && count($sponsorships) > 0)) { ?>
      <div class="card-v card-sponsor card-v-pad mb-4">
        <div class="d-flex align-items-center mb-2">
          <span class="section-label mb-0">Sponsorship</span>
          <?php if ($sponsored > 0) { ?>
            <span class="ms-auto mono" style="font-size:13px;color:#16663e">
              $<?php echo number_format($sponsored, 2); ?> accepted
            </span>
          <?php } ?>
        </div>

        <?php foreach ($sponsorships as $row) { ?>
          <?php if ($row['status'] == 'accepted' || $manages) { ?>
            <div class="d-flex flex-wrap align-items-center gap-3 mb-2">
              <span class="flex-grow-1" style="font-size:14px">
                <strong>$<?php echo number_format($row['amount'], 2); ?></strong>
                from
                <?php if ($row['sponsor_organization'] != '') { ?>
                  <?php echo htmlspecialchars($row['sponsor_organization']); ?>
                <?php } else { ?>
                  <?php echo htmlspecialchars($row['full_name']); ?>
                <?php } ?>
                <?php if ($row['note'] != '') { ?>
                  <span style="color:#6d7880">&middot; <?php echo htmlspecialchars($row['note']); ?></span>
                <?php } ?>
              </span>
              <?php if ($row['status'] == 'accepted') { ?>
                <span class="badge-v badge-green">Accepted</span>
              <?php } elseif ($row['status'] == 'pending') { ?>
                <span class="badge-v badge-pending">Offered</span>
              <?php } else { ?>
                <span class="badge-v badge-grey">Declined</span>
              <?php } ?>
            </div>
          <?php } ?>
        <?php } ?>

        <?php if ($manages) { ?>
          <p style="font-size:12.5px;color:#98a2aa">
            Offers are accepted or declined on
            <a class="link-green" href="opportunity-details.php?id=<?php echo $event['opportunity_id']; ?>">the opportunity</a>.
          </p>
        <?php } ?>
      </div>
    <?php } ?>

    <p class="section-label">About this Work</p>
    <p class="mb-4" style="color:#48545e;font-size:14.5px;line-height:1.7">
      <?php
      if ($event['opportunity_description'] != '') {
          echo nl2br(htmlspecialchars($event['opportunity_description']));
      } else {
          echo 'No description was added on the opportunity.';
      }
      ?>
    </p>

    <?php if ($event['opportunity_skills'] != '') { ?>
      <p class="section-label">Skills Required</p>
      <div class="d-flex flex-wrap gap-2 mb-4">
        <?php
        $skills = explode(',', $event['opportunity_skills']);
        foreach ($skills as $skill) {
            $skill = trim($skill);
            if ($skill != '') { ?>
              <span class="chip"><?php echo htmlspecialchars($skill); ?></span>
        <?php }
        } ?>
      </div>
    <?php } ?>

    <?php if ($event['opportunity_goals'] != '') { ?>
      <p class="section-label">SDG Goals</p>
      <div class="d-flex flex-wrap gap-2 mb-4">
        <?php
        $goals = explode(',', $event['opportunity_goals']);
        foreach ($goals as $goal) {
            $goal = trim($goal);
            if ($goal != '') { ?>
              <span class="chip chip-mono">SDG <?php echo htmlspecialchars($goal); ?></span>
        <?php }
        } ?>
      </div>
    <?php } ?>

  <?php } ?>

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

  <?php if ($event['created_by'] == $_SESSION['user_id'] || $_SESSION['role_id'] == 6) { ?>
    <div class="row g-3 mt-1">
      <div class="col-sm-6">
        <a class="btn-v btn-outline btn-block" href="event-edit.php?id=<?php echo $id; ?>">Edit Event</a>
      </div>
      <div class="col-sm-6">
        <?php if ($marked_present == 0) { ?>
          <form action="event-details.php?id=<?php echo $id; ?>" method="post"
                onsubmit="return confirm('Delete this event and its roster?')">
            <input type="hidden" name="delete" value="1">
            <button class="btn-v btn-outline btn-block" type="submit">Delete Event</button>
          </form>
        <?php } else { ?>
          <span class="btn-v btn-block" style="color:#98a2aa;border:1px solid #e9e5dd;cursor:not-allowed">
            Delete Event
          </span>
        <?php } ?>
      </div>
    </div>

    <?php if ($marked_present > 0) { ?>
      <p class="mt-3" style="font-size:13px;color:#6d7880">
        This event cannot be deleted because attendance has been recorded for
        <?php echo $marked_present; ?>
        <?php if ($marked_present == 1) { ?>volunteer<?php } else { ?>volunteers<?php } ?>.
        Those hours count towards the partnership totals and the volunteers' own records, so the
        event stays as part of the history.
      </p>
    <?php } ?>
  <?php } ?>

</div>

<?php include 'includes/app-footer.php'; ?>

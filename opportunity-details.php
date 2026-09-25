<?php
$active = 'opportunities';

require 'includes/auth.php';
require 'config/db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$find = $pdo->prepare(
    'SELECT o.*, org.name AS organization, u.organization_name,
            a.name AS partner_one, b.name AS partner_two
     FROM opportunity o
     LEFT JOIN organization org ON org.organization_id = o.organization_id
     LEFT JOIN `user` u ON u.user_id = o.created_by
     LEFT JOIN partnership p ON p.partnership_id = o.partnership_id
     LEFT JOIN organization a ON a.organization_id = p.organization_id
     LEFT JOIN organization b ON b.organization_id = p.partner_id
     WHERE o.opportunity_id = ?'
);
$find->execute(array($id));
$opportunity = $find->fetch();

if ($opportunity == false) {
    header('Location: opportunities.php');
    exit;
}

$page_title = $opportunity['title'] . ' | VPMS';

// how many applied and how many got in, for the spots bar
$count = $pdo->prepare('SELECT COUNT(*) FROM application WHERE opportunity_id = ?');
$count->execute(array($id));
$applications = $count->fetchColumn();

$count = $pdo->prepare('SELECT COUNT(*) FROM application WHERE opportunity_id = ? AND status = ?');
$count->execute(array($id, 'accepted'));
$filled = $count->fetchColumn();

if ($opportunity['spots'] > 0) {
    $percent = round($filled / $opportunity['spots'] * 100);
} else {
    $percent = 0;
}

if ($percent > 100) {
    $percent = 100;
}

$remaining = $opportunity['spots'] - $filled;

if ($remaining < 0) {
    $remaining = 0;
}

// whoever posted it, any coordinator of the organization behind it, and the administrator
$is_owner = ($opportunity['created_by'] == $_SESSION['user_id'] || $_SESSION['role_id'] == 6);

if ($_SESSION['role_id'] == 2 && $opportunity['organization_id'] != ''
 && $opportunity['organization_id'] == $_SESSION['organization_id']) {
    $is_owner = true;
}

// a coordinator of an organization partnered with the one behind this work
// manages it too, because a partnership is joint work
if (!$is_owner && $_SESSION['role_id'] == 2 && $opportunity['organization_id'] != ''
 && $_SESSION['organization_id'] != '') {
    $together = $pdo->prepare(
        'SELECT partnership_id FROM partnership
          WHERE status = ?
            AND ((organization_id = ? AND partner_id = ?)
              OR (organization_id = ? AND partner_id = ?))'
    );
    $together->execute(array('active',
        $_SESSION['organization_id'], $opportunity['organization_id'],
        $opportunity['organization_id'], $_SESSION['organization_id']));

    if ($together->fetch() != false) {
        $is_owner = true;
    }
}


// applications nobody ever decided on, which closing would otherwise bury
$count = $pdo->prepare('SELECT COUNT(*) FROM application WHERE opportunity_id = ? AND status = ?');
$count->execute(array($id, 'pending'));
$undecided = $count->fetchColumn();

// hours logged against this work, and money a sponsor has put on it
$count = $pdo->prepare(
    'SELECT COUNT(*) FROM event_volunteer ev
       JOIN event e ON e.event_id = ev.event_id
      WHERE e.opportunity_id = ? AND ev.attended = 1'
);
$count->execute(array($id));
$marked_present = $count->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete']) && $is_owner) {

    $count = $pdo->prepare('SELECT COUNT(*) FROM sponsorship WHERE opportunity_id = ? AND status = ?');
    $count->execute(array($id, 'accepted'));
    $paid_for = $count->fetchColumn();

    if ($marked_present == 0 && $paid_for == 0) {
        // everything pointing at it has to let go first
        $clear = $pdo->prepare('UPDATE event SET opportunity_id = NULL WHERE opportunity_id = ?');
        $clear->execute(array($id));

        $clear = $pdo->prepare('DELETE FROM sponsorship WHERE opportunity_id = ?');
        $clear->execute(array($id));

        $clear = $pdo->prepare('DELETE FROM application WHERE opportunity_id = ?');
        $clear->execute(array($id));

        $remove = $pdo->prepare('DELETE FROM opportunity WHERE opportunity_id = ?');
        $remove->execute(array($id));

        header('Location: opportunities.php?deleted=1');
        exit;
    }

    header('Location: opportunity-details.php?id=' . $id);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reopen']) && $is_owner) {
    $open = $pdo->prepare('UPDATE opportunity SET status = ? WHERE opportunity_id = ?');
    $open->execute(array('open', $id));

    header('Location: opportunity-details.php?id=' . $id . '&reopened=1');
    exit;
}

// people who run it can flip to see what a volunteer sees
$volunteer_view = !$is_owner;

if (isset($_GET['view'])) {
    $volunteer_view = ($_GET['view'] == 'volunteer');
}

$count = $pdo->prepare('SELECT COUNT(*) FROM event WHERE opportunity_id = ?');
$count->execute(array($id));
$converted = $count->fetchColumn();

$mine = $pdo->prepare('SELECT status FROM application WHERE opportunity_id = ? AND user_id = ?');
$mine->execute(array($id, $_SESSION['user_id']));
$my_application = $mine->fetch();

if ($opportunity['organization'] != '') {
    $posted_by = $opportunity['organization'];
} else {
    $posted_by = $opportunity['organization_name'];
}

// a volunteer account can apply to anything; everybody else can apply to work
// their own organization is not behind, because they would be managing it instead
$can_apply = true;

// nobody volunteers for work they posted themselves
if ($opportunity['created_by'] == $_SESSION['user_id']) {
    $can_apply = false;
}

if ($_SESSION['role_id'] != 1 && $_SESSION['organization_id'] != ''
 && $opportunity['organization_id'] != '') {

    if ($opportunity['organization_id'] == $_SESSION['organization_id']) {
        $can_apply = false;
    } else {
        $together = $pdo->prepare(
            'SELECT partnership_id FROM partnership
              WHERE status = ?
                AND ((organization_id = ? AND partner_id = ?)
                  OR (organization_id = ? AND partner_id = ?))'
        );
        $together->execute(array('active',
            $_SESSION['organization_id'], $opportunity['organization_id'],
            $opportunity['organization_id'], $_SESSION['organization_id']));

        if ($together->fetch() != false) {
            $can_apply = false;
        }
    }
}

// a sponsor offers, the organization running the work decides
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['offer']) && $_SESSION['role_id'] == 5) {
    $amount = $_POST['amount'];

    if ($amount > 0) {
        $save = $pdo->prepare(
            'INSERT INTO sponsorship (opportunity_id, sponsor_id, organization_id, amount, note)
             VALUES (?, ?, ?, ?, ?)'
        );
        $save->execute(array($id, $_SESSION['user_id'], $_SESSION['organization_id'],
                             $amount, trim($_POST['note'])));
    }

    header('Location: opportunity-details.php?id=' . $id . '&offered=1');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sponsorship_id']) && $is_owner) {
    if ($_POST['sponsor_decision'] == 'accept') {
        $answer = 'accepted';
    } else {
        $answer = 'declined';
    }

    $update = $pdo->prepare(
        'UPDATE sponsorship SET status = ? WHERE sponsorship_id = ? AND opportunity_id = ?'
    );
    $update->execute(array($answer, $_POST['sponsorship_id'], $id));

    header('Location: opportunity-details.php?id=' . $id . '&sponsor=1');
    exit;
}

$find = $pdo->prepare(
    'SELECT s.*, u.full_name, o.name AS sponsor_organization
     FROM sponsorship s
     JOIN `user` u ON u.user_id = s.sponsor_id
     LEFT JOIN organization o ON o.organization_id = s.organization_id
     WHERE s.opportunity_id = ?
     ORDER BY s.created_at'
);
$find->execute(array($id));
$sponsorships = $find->fetchAll();

$sponsored = 0;
$my_offer = false;

foreach ($sponsorships as $row) {
    if ($row['status'] == 'accepted') {
        $sponsored = $sponsored + $row['amount'];
    }

    if ($row['sponsor_id'] == $_SESSION['user_id']) {
        $my_offer = $row;
    }
}

include 'includes/app-header.php';
?>

<div class="col-mid mx-auto">

  <a class="back-link" href="opportunities.php"><i class="bi bi-arrow-left"></i> Back to Opportunities</a>

  <?php if (isset($_GET['saved'])) { ?>
    <div class="banner"><i class="bi bi-check-circle-fill"></i>Opportunity updated.</div>
  <?php } elseif (isset($_GET['reopened'])) { ?>
    <div class="banner"><i class="bi bi-check-circle-fill"></i>Opportunity reopened. Volunteers can apply again.</div>
  <?php } ?>

  <div class="d-flex align-items-start gap-3 mb-1">
    <h1 class="page-title flex-grow-1"><?php echo htmlspecialchars($opportunity['title']); ?></h1>
    <?php if ($my_application != false && $my_application['status'] == 'pending') { ?>
      <span class="badge-v badge-pending mt-2">Applied</span>
    <?php } elseif ($my_application != false && $my_application['status'] == 'accepted') { ?>
      <span class="badge-v badge-green mt-2">Accepted</span>
    <?php } elseif ($my_application != false) { ?>
      <span class="badge-v badge-grey mt-2">Not selected</span>
    <?php } elseif ($opportunity['status'] == 'open') { ?>
      <span class="badge-v badge-navy mt-2">Open</span>
    <?php } else { ?>
      <span class="badge-v badge-red mt-2">Closed</span>
    <?php } ?>
  </div>

  <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
    <p style="color:#16663e;font-size:14px">
      <i class="bi bi-arrow-left-right"></i>
      <?php echo htmlspecialchars($posted_by); ?> · <?php echo htmlspecialchars($opportunity['location']); ?>
    </p>
    <?php if ($is_owner) { ?>
      <span class="ms-auto d-flex flex-wrap gap-3">
        <?php if ($volunteer_view) { ?>
          <a class="link-green" style="font-size:14px" href="opportunity-details.php?id=<?php echo $id; ?>">View as coordinator</a>
        <?php } else { ?>
          <a class="link-green" style="font-size:14px" href="opportunity-details.php?id=<?php echo $id; ?>&view=volunteer">View as volunteer</a>
          <?php if ($converted > 0) { ?>
            <span style="font-size:14px;color:#98a2aa">Already an event</span>
          <?php } else { ?>
            <a class="link-green" style="font-size:14px" href="event-create.php?opportunity_id=<?php echo $id; ?>">Convert into an Event</a>
          <?php } ?>
        <?php } ?>
      </span>
    <?php } ?>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="info-tile">
        <span class="t-label">Date</span>
        <span class="t-value"><?php echo date('j F Y', strtotime($opportunity['opportunity_date'])); ?></span>
      </div>
    </div>
    <div class="col-md-4">
      <div class="info-tile">
        <span class="t-label">Hours Required</span>
        <span class="t-value"><?php echo $opportunity['hours_required']; ?> hrs</span>
      </div>
    </div>
    <div class="col-md-4">
      <div class="info-tile">
        <span class="t-label">Category</span>
        <span class="t-value"><?php echo htmlspecialchars($opportunity['category']); ?></span>
      </div>
    </div>
  </div>

  <div class="card-v card-v-pad mb-4">
    <p class="section-label">Volunteer Spots</p>
    <div class="d-flex align-items-center gap-3 mb-2">
      <div class="bar flex-grow-1"><span style="width:<?php echo $percent; ?>%"></span></div>
      <span class="mono" style="font-size:13px;color:#6d7880;white-space:nowrap">
        <?php echo $filled; ?>/<?php echo $opportunity['spots']; ?> filled
      </span>
    </div>
    <p style="font-size:13px;color:#6d7880">
      <?php echo $remaining; ?> spots remaining · <?php echo $applications; ?> applications received
    </p>
  </div>

  <?php if ($opportunity['partnership_id'] != '') { ?>
    <a class="card-v card-partner card-v-pad d-block mb-4" href="partnership-details.php?id=<?php echo $opportunity['partnership_id']; ?>">
      <p class="section-label mb-1">Part of a partnership</p>
      <p style="font-size:14.5px;font-weight:600">
        <?php echo htmlspecialchars($opportunity['partner_one']); ?>
        <i class="bi bi-arrow-left-right mx-2" style="color:#16663e;font-size:13px"></i>
        <?php echo htmlspecialchars($opportunity['partner_two']); ?>
      </p>
      <p style="font-size:13px;color:#6d7880">Hours logged here count towards this agreement.</p>
    </a>
  <?php } ?>

  <?php if ($sponsored > 0 || $_SESSION['role_id'] == 5 || ($is_owner && count($sponsorships) > 0)) { ?>
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
        <?php if ($is_owner || $row['sponsor_id'] == $_SESSION['user_id'] || $row['status'] == 'accepted') { ?>
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
                <span style="color:#6d7880">· <?php echo htmlspecialchars($row['note']); ?></span>
              <?php } ?>
            </span>

            <?php if ($row['status'] == 'pending' && $is_owner) { ?>
              <span class="d-flex gap-2">
                <form action="opportunity-details.php?id=<?php echo $id; ?>" method="post">
                  <input type="hidden" name="sponsorship_id" value="<?php echo $row['sponsorship_id']; ?>">
                  <input type="hidden" name="sponsor_decision" value="accept">
                  <button class="btn-v btn-green btn-sm-v" type="submit">Accept</button>
                </form>
                <form action="opportunity-details.php?id=<?php echo $id; ?>" method="post">
                  <input type="hidden" name="sponsorship_id" value="<?php echo $row['sponsorship_id']; ?>">
                  <input type="hidden" name="sponsor_decision" value="decline">
                  <button class="btn-v btn-outline btn-sm-v" type="submit">Decline</button>
                </form>
              </span>
            <?php } elseif ($row['status'] == 'accepted') { ?>
              <span class="badge-v badge-green">Accepted</span>
            <?php } elseif ($row['status'] == 'pending') { ?>
              <span class="badge-v badge-pending">Offered</span>
            <?php } else { ?>
              <span class="badge-v badge-grey">Declined</span>
            <?php } ?>
          </div>
        <?php } ?>
      <?php } ?>

      <?php if ($_SESSION['role_id'] == 5 && $my_offer == false) { ?>
        <form class="row g-3 align-items-end mt-2" action="opportunity-details.php?id=<?php echo $id; ?>" method="post">
          <input type="hidden" name="offer" value="1">
          <div class="col-sm-4">
            <label class="field-label" for="amount">Amount to sponsor</label>
            <input class="input-v" type="number" step="0.01" id="amount" name="amount" placeholder="5000">
          </div>
          <div class="col-sm-5">
            <label class="field-label" for="note">Note (optional)</label>
            <input class="input-v" type="text" id="note" name="note" placeholder="What it is for">
          </div>
          <div class="col-sm-3">
            <button class="btn-v btn-green btn-block" type="submit">Offer</button>
          </div>
        </form>
      <?php } ?>
    </div>
  <?php } ?>

  <p class="section-label">About this Opportunity</p>
  <p class="mb-4" style="color:#48545e;font-size:14.5px;line-height:1.7">
    <?php
    if ($opportunity['description'] != '') {
        echo nl2br(htmlspecialchars($opportunity['description']));
    } else {
        echo 'No description was added for this opportunity.';
    }
    ?>
  </p>

  <?php if ($opportunity['skills'] != '') { ?>
    <p class="section-label">Skills Required</p>
    <div class="d-flex flex-wrap gap-2 mb-4">
      <?php
      $skills = explode(',', $opportunity['skills']);
      foreach ($skills as $skill) {
          $skill = trim($skill);
          if ($skill != '') { ?>
            <span class="chip"><?php echo htmlspecialchars($skill); ?></span>
      <?php }
      } ?>
    </div>
  <?php } ?>

  <?php if ($opportunity['sdg_goals'] != '') { ?>
    <p class="section-label">SDG Goals</p>
    <div class="d-flex flex-wrap gap-2 mb-4">
      <?php
      $goals = explode(',', $opportunity['sdg_goals']);
      foreach ($goals as $goal) {
          $goal = trim($goal);
          if ($goal != '') { ?>
            <span class="chip chip-mono">SDG <?php echo htmlspecialchars($goal); ?></span>
      <?php }
      } ?>
    </div>
  <?php } ?>

  <?php if ($is_owner && !$volunteer_view && $opportunity['status'] != 'open' && $undecided > 0) { ?>
    <div class="notice mb-4" style="border-left-color:#c8504b;background:#fdf3f2">
      <p class="notice-title" style="color:#c8504b">
        <?php echo $undecided; ?>
        <?php if ($undecided == 1) { ?>application was<?php } else { ?>applications were<?php } ?>
        never decided
      </p>
      <p class="notice-text">
        This opportunity is closed, but those volunteers are still waiting on an answer.
        <a class="link-green" href="opportunity-applications.php?id=<?php echo $id; ?>&show=pending">Review them now</a>.
      </p>
    </div>
  <?php } ?>

  <div class="row g-3">
    <?php if ($volunteer_view) { ?>

      <?php if ($my_application != false) { ?>
        <div class="col-12">
          <div class="notice">
            <p class="notice-title">You have already applied</p>
            <p class="notice-text">
              Your application is <strong><?php echo $my_application['status']; ?></strong>.
              <?php if ($my_application['status'] == 'pending') { ?>
                The coordinator will respond in the Communications hub.
              <?php } ?>
            </p>
          </div>
        </div>
      <?php } elseif ($opportunity['status'] != 'open') { ?>
        <div class="col-12">
          <div class="notice">
            <p class="notice-title">Applications are closed</p>
            <p class="notice-text">This opportunity is no longer accepting volunteers.</p>
          </div>
        </div>
      <?php } elseif (!$can_apply) { ?>
        <div class="col-12">
          <div class="notice">
            <?php if ($opportunity['created_by'] == $_SESSION['user_id']) { ?>
              <p class="notice-title">You posted this one</p>
            <?php } else { ?>
              <p class="notice-title">This is your organization's work</p>
            <?php } ?>
            <p class="notice-text">
              You manage this one rather than volunteering for it. Add yourself to the event roster
              if you are taking part on the day.
            </p>
          </div>
        </div>
      <?php } else { ?>
        <div class="col-sm-6">
          <a class="btn-v btn-green btn-block" href="opportunity-apply.php?id=<?php echo $id; ?>">Apply Now</a>
        </div>
        <div class="col-sm-6">
          <a class="btn-v btn-outline btn-block" href="opportunities.php">
            <i class="bi bi-bookmark"></i> Back to list
          </a>
        </div>
      <?php } ?>

    <?php } elseif ($opportunity['status'] == 'open') { ?>

      <div class="col-sm-6">
        <a class="btn-v btn-green btn-block" href="opportunity-edit.php?id=<?php echo $id; ?>">Edit Opportunity</a>
      </div>
      <div class="col-sm-6">
        <a class="btn-v btn-outline btn-block" href="opportunity-applications.php?id=<?php echo $id; ?>">
          Manage Applications (<?php echo $applications; ?>)
        </a>
      </div>

    <?php } else { ?>

      <!-- nothing here is the main action any more, so reopening leads -->
      <div class="col-sm-4">
        <form action="opportunity-details.php?id=<?php echo $id; ?>" method="post">
          <input type="hidden" name="reopen" value="1">
          <button class="btn-v btn-green btn-block" type="submit">Reopen Opportunity</button>
        </form>
      </div>
      <div class="col-sm-4">
        <a class="btn-v btn-outline btn-block" href="opportunity-edit.php?id=<?php echo $id; ?>">Edit Opportunity</a>
      </div>
      <div class="col-sm-4">
        <a class="btn-v btn-outline btn-block" href="opportunity-applications.php?id=<?php echo $id; ?>">
          Manage Applications (<?php echo $applications; ?>)
        </a>
      </div>

    <?php } ?>
  </div>

  <?php if ($is_owner && !$volunteer_view) { ?>
    <div class="row g-3 mt-1">
      <div class="col-sm-6">
        <a class="btn-v btn-soft btn-block" href="opportunity-create.php?copy=<?php echo $id; ?>">Duplicate for another date</a>
      </div>
      <div class="col-sm-6">
        <?php if ($marked_present == 0 && $sponsored == 0) { ?>
          <form action="opportunity-details.php?id=<?php echo $id; ?>" method="post"
                onsubmit="return confirm('Delete this opportunity and its applications?')">
            <input type="hidden" name="delete" value="1">
            <button class="btn-v btn-outline btn-block" type="submit">Delete Opportunity</button>
          </form>
        <?php } else { ?>
          <span class="btn-v btn-block" style="color:#98a2aa;border:1px solid #e9e5dd;cursor:not-allowed">
            Delete Opportunity
          </span>
        <?php } ?>
      </div>
    </div>

    <p class="mt-2" style="font-size:12.5px;color:#98a2aa">
      Duplicating opens a new form with these details filled in. Nothing is created until you publish it.
      <?php if ($marked_present > 0) { ?>
        This one cannot be deleted because hours have been logged against it.
      <?php } elseif ($sponsored > 0) { ?>
        This one cannot be deleted because a sponsor has money on it.
      <?php } ?>
    </p>
  <?php } ?>

</div>

<?php include 'includes/app-footer.php'; ?>

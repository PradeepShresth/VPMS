<?php
$active = 'opportunities';

require 'includes/auth.php';
require 'config/db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$find = $pdo->prepare(
    'SELECT o.*, org.name AS organisation, u.organisation_name
     FROM opportunity o
     LEFT JOIN organisation org ON org.organisation_id = o.organisation_id
     LEFT JOIN `user` u ON u.user_id = o.created_by
     WHERE o.opportunity_id = ?'
);
$find->execute(array($id));
$opportunity = $find->fetch();

if ($opportunity == false) {
    header('Location: opportunities.php');
    exit;
}

$page_title = $opportunity['title'] . ' | VPMS';

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

$is_owner = ($opportunity['created_by'] == $_SESSION['user_id'] || $_SESSION['role_id'] == 6);

$volunteer_view = !$is_owner;

if (isset($_GET['view'])) {
    $volunteer_view = ($_GET['view'] == 'volunteer');
}

$mine = $pdo->prepare('SELECT status FROM application WHERE opportunity_id = ? AND user_id = ?');
$mine->execute(array($id, $_SESSION['user_id']));
$my_application = $mine->fetch();

if ($opportunity['organisation'] != '') {
    $posted_by = $opportunity['organisation'];
} else {
    $posted_by = $opportunity['organisation_name'];
}

include 'includes/app-header.php';
?>

<div class="col-mid mx-auto">

  <a class="back-link" href="opportunities.php"><i class="bi bi-arrow-left"></i> Back to Opportunities</a>

  <?php if (isset($_GET['saved'])) { ?>
    <div class="banner"><i class="bi bi-check-circle-fill"></i>Opportunity updated.</div>
  <?php } ?>

  <div class="d-flex align-items-start gap-3 mb-1">
    <h1 class="page-title flex-grow-1"><?php echo htmlspecialchars($opportunity['title']); ?></h1>
    <?php if ($opportunity['status'] == 'open') { ?>
      <span class="badge-v badge-navy mt-2">Open</span>
    <?php } else { ?>
      <span class="badge-v badge-grey mt-2">Closed</span>
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
          <a class="link-green" style="font-size:14px" href="event-create.php?opportunity_id=<?php echo $id; ?>">Convert into an Event</a>
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

    <?php } else { ?>
      <div class="col-sm-6">
        <a class="btn-v btn-green btn-block" href="opportunity-edit.php?id=<?php echo $id; ?>">Edit Opportunity</a>
      </div>
      <div class="col-sm-6">
        <a class="btn-v btn-outline btn-block" href="opportunity-applications.php?id=<?php echo $id; ?>">
          Manage Applications (<?php echo $applications; ?>)
        </a>
      </div>
    <?php } ?>
  </div>

</div>

<?php include 'includes/app-footer.php'; ?>

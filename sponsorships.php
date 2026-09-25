<?php
$page_title = 'My Sponsorships | VPMS';
$active = 'sponsorships';

require 'includes/auth.php';
require 'config/db.php';

// what my money paid for, and what came out of it
$find = $pdo->prepare(
    'SELECT s.sponsorship_id, s.amount, s.status, s.note, s.created_at,
            o.opportunity_id, o.title, o.opportunity_date, o.status AS opportunity_status,
            org.name AS organization,
            (SELECT COUNT(*) FROM event e WHERE e.opportunity_id = o.opportunity_id) AS events,
            (SELECT COUNT(DISTINCT ev.user_id) FROM event_volunteer ev
               JOIN event e ON e.event_id = ev.event_id
              WHERE e.opportunity_id = o.opportunity_id AND ev.attended = 1) AS volunteers,
            (SELECT COALESCE(SUM(ev.hours_logged), 0) FROM event_volunteer ev
               JOIN event e ON e.event_id = ev.event_id
              WHERE e.opportunity_id = o.opportunity_id AND ev.attended = 1) AS hours
     FROM sponsorship s
     JOIN opportunity o ON o.opportunity_id = s.opportunity_id
     LEFT JOIN organization org ON org.organization_id = o.organization_id
     WHERE s.sponsor_id = ?
     ORDER BY s.created_at DESC'
);
$find->execute(array($_SESSION['user_id']));
$sponsorships = $find->fetchAll();

$given = 0;
$volunteers = 0;
$hours = 0;
$waiting = 0;

foreach ($sponsorships as $row) {
    if ($row['status'] == 'accepted') {
        $given = $given + $row['amount'];
        $volunteers = $volunteers + $row['volunteers'];
        $hours = $hours + $row['hours'];
    }

    if ($row['status'] == 'pending') {
        $waiting = $waiting + 1;
    }
}

// what an hour of volunteering cost, which is the number a donor asks about
if ($hours > 0) {
    $per_hour = round($given / $hours, 2);
} else {
    $per_hour = 0;
}

include 'includes/app-header.php';
?>

<div class="head-row">
  <div class="flex-grow-1">
    <h1 class="page-title">My Sponsorships</h1>
    <p class="page-sub">What you funded, and what it produced</p>
  </div>
  <a class="btn-v btn-green" href="opportunities.php">Find work to sponsor</a>
</div>

<div class="row g-3 mb-4">
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <span class="stat-value">$<?php echo number_format($given, 0); ?></span>
      <span class="stat-label">Sponsored</span>
      <span class="stat-note"><?php echo $waiting; ?> awaiting a decision</span>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <span class="stat-value"><?php echo $volunteers; ?></span>
      <span class="stat-label">Volunteers Mobilised</span>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <span class="stat-value"><?php echo $hours; ?></span>
      <span class="stat-label">Hours Delivered</span>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <span class="stat-value">$<?php echo $per_hour; ?></span>
      <span class="stat-label">Cost per Hour</span>
      <span class="stat-note">Across accepted sponsorships</span>
    </div>
  </div>
</div>

<?php if (count($sponsorships) == 0) { ?>

  <div class="card-v card-v-pad text-center">
    <p class="row-title mb-2">You have not sponsored anything yet</p>
    <p style="font-size:14px;color:#6d7880">
      Open an opportunity and offer an amount. The organization running it decides whether to accept.
    </p>
  </div>

<?php } ?>

<?php foreach ($sponsorships as $row) { ?>
  <a class="card-v card-v-pad d-block mb-3" href="opportunity-details.php?id=<?php echo $row['opportunity_id']; ?>">
    <div class="d-flex flex-wrap align-items-start gap-3 mb-2">
      <span class="flex-grow-1">
        <span class="row-title d-block"><?php echo htmlspecialchars($row['title']); ?></span>
        <span class="row-meta d-block" style="color:#16663e">
          <?php echo htmlspecialchars($row['organization']); ?>
          &nbsp;·&nbsp; <?php echo date('j M Y', strtotime($row['opportunity_date'])); ?>
        </span>
      </span>
      <span class="text-end">
        <span class="d-block" style="font-family:'Fraunces',serif;font-size:22px;color:#16663e">
          $<?php echo number_format($row['amount'], 2); ?>
        </span>
        <?php if ($row['status'] == 'accepted') { ?>
          <span class="badge-v badge-green">Accepted</span>
        <?php } elseif ($row['status'] == 'pending') { ?>
          <span class="badge-v badge-pending">Awaiting decision</span>
        <?php } else { ?>
          <span class="badge-v badge-grey">Declined</span>
        <?php } ?>
      </span>
    </div>

    <?php if ($row['status'] == 'accepted') { ?>
      <p class="mono" style="font-size:12.5px;color:#6d7880">
        <?php echo $row['events']; ?> events
        &nbsp;·&nbsp; <?php echo $row['volunteers']; ?> volunteers
        &nbsp;·&nbsp; <?php echo $row['hours']; ?> hours delivered
      </p>
    <?php } elseif ($row['note'] != '') { ?>
      <p style="font-size:13.5px;color:#6d7880"><?php echo htmlspecialchars($row['note']); ?></p>
    <?php } ?>
  </a>
<?php } ?>

<?php include 'includes/app-footer.php'; ?>

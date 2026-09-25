<?php
$page_title = 'Dashboard | VPMS';
$active = 'dashboard';

require 'includes/auth.php';
require 'config/db.php';

// the four big numbers at the top
$count = $pdo->prepare('SELECT COUNT(*) FROM `user` WHERE role_id = ?');
$count->execute(array(1));
$volunteers = $count->fetchColumn();

$count = $pdo->prepare('SELECT COUNT(*) FROM `user` WHERE created_at >= ?');
$count->execute(array(date('Y-m-01')));
$joined_this_month = $count->fetchColumn();

$count = $pdo->query('SELECT COUNT(*) FROM organization');
$organizations = $count->fetchColumn();

$count = $pdo->prepare('SELECT COUNT(*) FROM organization WHERE status = ?');
$count->execute(array('pending'));
$organizations_waiting = $count->fetchColumn();

$count = $pdo->prepare('SELECT COUNT(*) FROM partnership WHERE status = ?');
$count->execute(array('active'));
$partnerships_active = $count->fetchColumn();

$count = $pdo->prepare('SELECT COUNT(*) FROM partnership WHERE status = ?');
$count->execute(array('pending'));
$partnerships_waiting = $count->fetchColumn();

$count = $pdo->prepare('SELECT COUNT(*) FROM opportunity WHERE status = ?');
$count->execute(array('open'));
$projects = $count->fetchColumn();

$count = $pdo->query('SELECT COUNT(DISTINCT category) FROM opportunity');
$categories = $count->fetchColumn();

$find = $pdo->query(
    'SELECT o.opportunity_id, o.title, o.location, o.spots, o.status,
            org.name AS organization, u.organization_name,
            (SELECT COUNT(*) FROM application a
              WHERE a.opportunity_id = o.opportunity_id AND a.status = \'accepted\') AS filled
     FROM opportunity o
     LEFT JOIN organization org ON org.organization_id = o.organization_id
     LEFT JOIN `user` u ON u.user_id = o.created_by
     ORDER BY o.created_at DESC
     LIMIT 3'
);
$opportunities = $find->fetchAll();   // newest three, for the list below

$find = $pdo->prepare(
    'SELECT e.event_id, e.title, e.event_date, e.event_time, e.volunteers_needed,
            (SELECT COUNT(*) FROM event_volunteer ev
              WHERE ev.event_id = e.event_id AND ev.status = \'confirmed\') AS joined
     FROM event e
     WHERE e.event_date >= ?
     ORDER BY e.event_date
     LIMIT 3'
);
$find->execute(array(date('Y-m-d')));
$events = $find->fetchAll();

$find = $pdo->prepare(
    'SELECT p.partnership_id, p.sdg_goals, asked.name AS asked_by, partner.name AS partner_name
     FROM partnership p
     LEFT JOIN organization asked ON asked.organization_id = p.organization_id
     LEFT JOIN organization partner ON partner.organization_id = p.partner_id
     WHERE p.status = ?
     ORDER BY p.created_at DESC
     LIMIT 3'
);
$find->execute(array('active'));
$partnerships = $find->fetchAll();

// sdg_goals is saved as text like 13,17 so it has to be split up again
$goals_covered = array();

$find = $pdo->query('SELECT sdg_goals FROM opportunity WHERE sdg_goals != \'\'');

foreach ($find->fetchAll() as $row) {
    foreach (explode(',', $row['sdg_goals']) as $goal) {
        $goal = trim($goal);
        if ($goal != '' && !in_array($goal, $goals_covered)) {
            $goals_covered[] = $goal;
        }
    }
}

$find = $pdo->query('SELECT sdg_goals FROM partnership WHERE sdg_goals != \'\'');

foreach ($find->fetchAll() as $row) {
    foreach (explode(',', $row['sdg_goals']) as $goal) {
        $goal = trim($goal);
        if ($goal != '' && !in_array($goal, $goals_covered)) {
            $goals_covered[] = $goal;
        }
    }
}

sort($goals_covered);

$hour = date('G');

if ($hour < 12) {
    $greeting = 'Good morning';
} elseif ($hour < 18) {
    $greeting = 'Good afternoon';
} else {
    $greeting = 'Good evening';
}

$first_name = explode(' ', $_SESSION['full_name']);

include 'includes/app-header.php';
?>

<div class="mb-4">
  <h1 class="page-title"><?php echo $greeting; ?>, <?php echo htmlspecialchars($first_name[0]); ?>.</h1>
  <p class="page-sub">Platform overview — all systems operational.</p>
</div>

<!-- top numbers -->
<div class="row g-3 mb-4">
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <span class="stat-value"><?php echo $volunteers; ?></span>
      <span class="stat-label">Total Volunteers</span>
      <span class="stat-note">+<?php echo $joined_this_month; ?> this month</span>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <span class="stat-value"><?php echo $organizations; ?></span>
      <span class="stat-label">Organizations</span>
      <span class="stat-note"><?php echo $organizations_waiting; ?> pending verification</span>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <span class="stat-value"><?php echo $partnerships_active; ?></span>
      <span class="stat-label">Active Partnerships</span>
      <span class="stat-note"><?php echo $partnerships_waiting; ?> pending approval</span>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <span class="stat-value"><?php echo $projects; ?></span>
      <span class="stat-label">Open Opportunities</span>
      <span class="stat-note">Across <?php echo $categories; ?> categories</span>
    </div>
  </div>
</div>

<div class="row g-4 mb-4">

  <!-- recent opportunities -->
  <div class="col-lg-7">
    <div class="d-flex align-items-center mb-3">
      <span class="section-label mb-0">Recent Opportunities</span>
      <a class="ms-auto link-green" style="font-size:13.5px" href="opportunities.php">View all &rarr;</a>
    </div>

    <div class="list-card">
      <?php if (count($opportunities) == 0) { ?>
        <div class="list-row">
          <span style="font-size:14px;color:#6d7880">Nothing posted yet.</span>
        </div>
      <?php } ?>

      <?php foreach ($opportunities as $row) { ?>

        <?php
        if ($row['organization'] != '') {
            $posted_by = $row['organization'];
        } else {
            $posted_by = $row['organization_name'];
        }
        ?>

        <a class="list-row" href="opportunity-details.php?id=<?php echo $row['opportunity_id']; ?>">
          <span class="row-icon"><i class="bi bi-diamond"></i></span>
          <span class="flex-grow-1 min-w-0">
            <span class="row-title d-block"><?php echo htmlspecialchars($row['title']); ?></span>
            <span class="row-meta d-block" style="color:#16663e">
              <?php echo htmlspecialchars($posted_by); ?> · <?php echo htmlspecialchars($row['location']); ?>
            </span>
          </span>
          <span class="text-end">
            <?php if ($row['status'] == 'open') { ?>
              <span class="badge-v badge-navy d-block mb-2">Open</span>
            <?php } else { ?>
              <span class="badge-v badge-red d-block mb-2">Closed</span>
            <?php } ?>
            <span class="mono" style="font-size:12.5px;color:#6d7880">
              <?php echo $row['filled']; ?>/<?php echo $row['spots']; ?> spots
            </span>
          </span>
        </a>
      <?php } ?>

      <div class="list-row" style="min-height:74px"></div>
    </div>
  </div>

  <!-- upcoming events -->
  <div class="col-lg-5">
    <div class="d-flex align-items-center mb-3">
      <span class="section-label mb-0">Upcoming Events</span>
      <a class="ms-auto link-green" style="font-size:13.5px" href="events.php">View all &rarr;</a>
    </div>

    <?php if (count($events) == 0) { ?>
      <div class="card-v card-v-pad">
        <p style="font-size:14px;color:#6d7880">Nothing scheduled yet.</p>
      </div>
    <?php } ?>

    <?php foreach ($events as $row) { ?>

      <?php
      if ($row['volunteers_needed'] > 0) {
          $percent = round($row['joined'] / $row['volunteers_needed'] * 100);
      } else {
          $percent = 0;
      }
      ?>

      <a class="card-v card-v-pad d-block mb-3" href="event-details.php?id=<?php echo $row['event_id']; ?>">
        <div class="d-flex align-items-start gap-2 mb-2">
          <span class="row-title"><?php echo htmlspecialchars($row['title']); ?></span>
          <?php if ($row['event_date'] == date('Y-m-d')) { ?>
            <span class="badge-v badge-green ms-auto">Ongoing</span>
          <?php } else { ?>
            <span class="badge-v badge-blue ms-auto">Upcoming</span>
          <?php } ?>
        </div>
        <p class="mono mb-3" style="font-size:12.5px;color:#6d7880">
          <?php echo date('j M', strtotime($row['event_date'])); ?>
          &nbsp;·&nbsp; <?php echo date('h:i A', strtotime($row['event_time'])); ?>
        </p>
        <div class="d-flex align-items-center gap-3">
          <div class="bar flex-grow-1"><span style="width:<?php echo min($percent, 100); ?>%"></span></div>
          <span class="mono" style="font-size:12.5px;color:#6d7880">
            <?php echo $row['joined']; ?>/<?php echo $row['volunteers_needed']; ?>
          </span>
        </div>
      </a>
    <?php } ?>
  </div>
</div>

<div class="row g-4 mb-4">

  <!-- partnerships -->
  <div class="col-lg-6">
    <div class="d-flex align-items-center mb-3">
      <span class="section-label mb-0">Active Partnerships</span>
      <a class="ms-auto link-green" style="font-size:13.5px" href="partnerships.php">View all &rarr;</a>
    </div>

    <div class="list-card">
      <?php if (count($partnerships) == 0) { ?>
        <div class="list-row">
          <span style="font-size:14px;color:#6d7880">No active partnerships yet.</span>
        </div>
      <?php } ?>

      <?php foreach ($partnerships as $row) { ?>
        <a class="list-row align-items-start" href="partnership-details.php?id=<?php echo $row['partnership_id']; ?>">
          <span class="flex-grow-1">
            <span class="row-title d-block"><?php echo htmlspecialchars($row['asked_by']); ?></span>
            <span class="row-meta d-block" style="color:#16663e">
              <i class="bi bi-arrow-left-right"></i> <?php echo htmlspecialchars($row['partner_name']); ?>
            </span>
            <span class="d-flex flex-wrap gap-2 mt-2">
              <?php
              foreach (explode(',', $row['sdg_goals']) as $goal) {
                  $goal = trim($goal);
                  if ($goal != '') { ?>
                    <span class="chip chip-mono">SDG <?php echo htmlspecialchars($goal); ?></span>
              <?php }
              } ?>
            </span>
          </span>
          <span class="badge-v badge-navy">Active</span>
        </a>
      <?php } ?>

      <div class="list-row" style="min-height:120px"></div>
    </div>
  </div>

  <!-- sdg goals -->
  <div class="col-lg-6">
    <div class="d-flex align-items-center mb-3">
      <span class="section-label mb-0">SDG Goals Covered</span>
      <a class="ms-auto link-green" style="font-size:13.5px" href="impact.php">SDG Dashboard &rarr;</a>
    </div>

    <div class="card-v card-v-pad">
      <?php if (count($goals_covered) == 0) { ?>
        <p style="font-size:14px;color:#6d7880">
          No SDG goals recorded yet. Tick them when posting an opportunity or requesting a partnership.
        </p>
      <?php } else { ?>
        <div class="sdg-grid mb-3" style="grid-template-columns:repeat(4,1fr)">
          <?php foreach ($goals_covered as $goal) { ?>

            <?php
            if ($goal == 1) { $colour = '#e5243b'; }
            elseif ($goal == 2) { $colour = '#dda63a'; }
            elseif ($goal == 3) { $colour = '#4c9f38'; }
            elseif ($goal == 4) { $colour = '#c5192d'; }
            elseif ($goal == 13) { $colour = '#3f7e44'; }
            elseif ($goal == 14) { $colour = '#0a97d9'; }
            elseif ($goal == 15) { $colour = '#56c02b'; }
            else { $colour = '#19486a'; }
            ?>

            <div class="sdg-tile" style="background:<?php echo $colour; ?>">
              <span class="s-word">SDG</span>
              <span class="s-num"><?php echo str_pad($goal, 2, '0', STR_PAD_LEFT); ?></span>
            </div>
          <?php } ?>
        </div>
        <p class="text-center" style="font-size:12.5px;color:#6d7880">
          Platform activities contribute to <?php echo count($goals_covered); ?> of 17 Global Goals
        </p>
      <?php } ?>
    </div>
  </div>
</div>

<!-- quick actions -->
<?php if ($_SESSION['role_id'] == 6) { ?>
  <div class="card-v card-v-pad">
    <p class="section-label">Quick Actions</p>
    <div class="d-flex flex-wrap gap-2">
      <a class="btn-v btn-green" href="users.php">Manage Users</a>
      <a class="btn-v btn-soft" href="applications-review.php">Review Applications</a>
      <a class="btn-v btn-soft" href="organizations.php">Approve Organizations</a>
      <a class="btn-v btn-soft" href="report-generate.php">Generate Report</a>
    </div>
  </div>
<?php } else { ?>
  <div class="card-v card-v-pad">
    <p class="section-label">Quick Actions</p>
    <div class="d-flex flex-wrap gap-2">
      <a class="btn-v btn-green" href="opportunities.php">Browse Opportunities</a>
      <a class="btn-v btn-soft" href="events.php">My Events</a>
      <a class="btn-v btn-soft" href="messages.php">Communications</a>
      <a class="btn-v btn-soft" href="profile.php">My Profile</a>
    </div>
  </div>
<?php } ?>

<?php include 'includes/app-footer.php'; ?>

<?php
$page_title = 'SDG 17 Impact | VPMS';
$active = 'impact';

require 'includes/auth.php';
require 'config/db.php';

$count = $pdo->prepare('SELECT COUNT(*) FROM `user` WHERE role_id = ?');
$count->execute(array(1));
$volunteers = $count->fetchColumn();

$count = $pdo->prepare('SELECT COALESCE(SUM(hours_logged), 0) FROM event_volunteer WHERE attended = ?');
$count->execute(array(1));
$hours = $count->fetchColumn();

$count = $pdo->query('SELECT COUNT(*) FROM event');
$events = $count->fetchColumn();

$count = $pdo->query('SELECT COUNT(DISTINCT country) FROM organization WHERE country != \'\'');
$countries = $count->fetchColumn();

$count = $pdo->query('SELECT COUNT(*) FROM organization');
$organizations = $count->fetchColumn();

$count = $pdo->prepare('SELECT COUNT(*) FROM partnership WHERE status = ?');
$count->execute(array('active'));
$partnerships = $count->fetchColumn();

// one opportunity can list a few goals, so count each number separately
$projects_for_goal = array();
$volunteers_for_goal = array();
$hours_for_goal = array();

$find = $pdo->query(
    'SELECT o.sdg_goals,
            (SELECT COUNT(*) FROM application a
              WHERE a.opportunity_id = o.opportunity_id AND a.status = \'accepted\') AS accepted,
            (SELECT COALESCE(SUM(ev.hours_logged), 0)
               FROM event_volunteer ev
               JOIN event e ON e.event_id = ev.event_id
              WHERE e.opportunity_id = o.opportunity_id AND ev.attended = 1) AS hours
     FROM opportunity o
     WHERE o.sdg_goals != \'\''
);

foreach ($find->fetchAll() as $row) {
    foreach (explode(',', $row['sdg_goals']) as $goal) {
        $goal = trim($goal);

        if ($goal != '') {
            if (!isset($projects_for_goal[$goal])) {
                $projects_for_goal[$goal] = 0;
                $volunteers_for_goal[$goal] = 0;
                $hours_for_goal[$goal] = 0;
            }

            $projects_for_goal[$goal] = $projects_for_goal[$goal] + 1;
            $volunteers_for_goal[$goal] = $volunteers_for_goal[$goal] + $row['accepted'];
            $hours_for_goal[$goal] = $hours_for_goal[$goal] + $row['hours'];
        }
    }
}

$find = $pdo->query('SELECT sdg_goals FROM partnership WHERE sdg_goals != \'\'');

foreach ($find->fetchAll() as $row) {
    foreach (explode(',', $row['sdg_goals']) as $goal) {
        $goal = trim($goal);

        if ($goal != '') {
            if (!isset($projects_for_goal[$goal])) {
                $projects_for_goal[$goal] = 0;
                $volunteers_for_goal[$goal] = 0;
                $hours_for_goal[$goal] = 0;
            }

            $projects_for_goal[$goal] = $projects_for_goal[$goal] + 1;
        }
    }
}

ksort($projects_for_goal);

$covered = count($projects_for_goal);

$busiest = 0;

foreach ($projects_for_goal as $goal => $projects) {
    if ($projects > $busiest) {
        $busiest = $projects;
    }
}

include 'includes/app-header.php';
?>

<span class="badge-v badge-green mb-3">
  <i class="bi bi-record-circle-fill me-2" style="font-size:9px"></i>UN Sustainable Development Goal 17
</span>

<h1 class="page-title">Partnerships for the Goals</h1>
<p class="page-sub mb-4" style="max-width:700px">
  VPMS tracks real-time contributions to the UN 2030 Agenda. The platform currently contributes to
  <strong style="color:#16663e"><?php echo $covered; ?> of 17 SDGs</strong> through its partner network.
</p>

<div class="row g-3 mb-4">
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <span class="stat-value"><?php echo $volunteers; ?></span>
      <span class="stat-label">Total Volunteers</span>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <span class="stat-value"><?php echo $hours; ?></span>
      <span class="stat-label">Volunteer Hours</span>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <span class="stat-value" style="color:#e08a1e"><?php echo $events; ?></span>
      <span class="stat-label">Events Held</span>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="stat-card">
      <span class="stat-value" style="color:#2b8fc9"><?php echo $countries; ?></span>
      <span class="stat-label">Countries Reached</span>
    </div>
  </div>
</div>

<!-- all 17 goals, faded ones are not covered yet -->
<p class="section-label">SDG Coverage Map</p>

<div class="sdg-grid mb-2">
  <div class="sdg-tile <?php if (!isset($projects_for_goal[1])) echo 'faded'; ?>" style="background:#e5243b"><span class="s-word">SDG</span><span class="s-num">01</span></div>
  <div class="sdg-tile <?php if (!isset($projects_for_goal[2])) echo 'faded'; ?>" style="background:#dda63a"><span class="s-word">SDG</span><span class="s-num">02</span></div>
  <div class="sdg-tile <?php if (!isset($projects_for_goal[3])) echo 'faded'; ?>" style="background:#4c9f38"><span class="s-word">SDG</span><span class="s-num">03</span></div>
  <div class="sdg-tile <?php if (!isset($projects_for_goal[4])) echo 'faded'; ?>" style="background:#c5192d"><span class="s-word">SDG</span><span class="s-num">04</span></div>
  <div class="sdg-tile faded" style="background:#ff3a21"><span class="s-word">SDG</span><span class="s-num">05</span></div>
  <div class="sdg-tile faded" style="background:#26bde2"><span class="s-word">SDG</span><span class="s-num">06</span></div>
  <div class="sdg-tile faded" style="background:#fcc30b"><span class="s-word">SDG</span><span class="s-num">07</span></div>
  <div class="sdg-tile faded" style="background:#a21942"><span class="s-word">SDG</span><span class="s-num">08</span></div>
  <div class="sdg-tile faded" style="background:#fd6925"><span class="s-word">SDG</span><span class="s-num">09</span></div>
  <div class="sdg-tile faded" style="background:#dd1367"><span class="s-word">SDG</span><span class="s-num">10</span></div>
  <div class="sdg-tile faded" style="background:#fd9d24"><span class="s-word">SDG</span><span class="s-num">11</span></div>
  <div class="sdg-tile faded" style="background:#bf8b2e"><span class="s-word">SDG</span><span class="s-num">12</span></div>
  <div class="sdg-tile <?php if (!isset($projects_for_goal[13])) echo 'faded'; ?>" style="background:#3f7e44"><span class="s-word">SDG</span><span class="s-num">13</span></div>
  <div class="sdg-tile <?php if (!isset($projects_for_goal[14])) echo 'faded'; ?>" style="background:#0a97d9"><span class="s-word">SDG</span><span class="s-num">14</span></div>
  <div class="sdg-tile <?php if (!isset($projects_for_goal[15])) echo 'faded'; ?>" style="background:#56c02b"><span class="s-word">SDG</span><span class="s-num">15</span></div>
  <div class="sdg-tile faded" style="background:#00689d"><span class="s-word">SDG</span><span class="s-num">16</span></div>
  <div class="sdg-tile <?php if (!isset($projects_for_goal[17])) echo 'faded'; ?>" style="background:#19486a"><span class="s-word">SDG</span><span class="s-num">17</span></div>
</div>

<p class="mb-4" style="font-size:12.5px;color:#6d7880">
  Filled = active · Faded = not yet addressed
</p>

<p class="section-label">Active SDG Contributions</p>

<?php if ($covered == 0) { ?>

  <div class="card-v card-v-pad text-center">
    <p class="row-title mb-2">Nothing recorded yet</p>
    <p style="font-size:14px;color:#6d7880">
      Tick SDG goals when posting an opportunity or requesting a partnership, and they show up here.
    </p>
  </div>

<?php } ?>

<?php foreach ($projects_for_goal as $goal => $projects) { ?>

  <?php
  if ($goal == 1) { $name = 'No Poverty'; $colour = '#e5243b'; }
  elseif ($goal == 2) { $name = 'Zero Hunger'; $colour = '#dda63a'; }
  elseif ($goal == 3) { $name = 'Good Health &amp; Well-Being'; $colour = '#4c9f38'; }
  elseif ($goal == 4) { $name = 'Quality Education'; $colour = '#c5192d'; }
  elseif ($goal == 13) { $name = 'Climate Action'; $colour = '#3f7e44'; }
  elseif ($goal == 14) { $name = 'Life Below Water'; $colour = '#0a97d9'; }
  elseif ($goal == 15) { $name = 'Life on Land'; $colour = '#56c02b'; }
  elseif ($goal == 17) { $name = 'Partnerships for the Goals'; $colour = '#19486a'; }
  else { $name = 'Global Goal ' . $goal; $colour = '#6d7880'; }

  if ($busiest > 0) {
      $percent = round($projects / $busiest * 100);
  } else {
      $percent = 0;
  }
  ?>

  <div class="card-v card-v-pad mb-3">
    <div class="d-flex align-items-center gap-3">
      <span class="sdg-mini" style="background:<?php echo $colour; ?>">
        <span class="s-word">SDG</span>
        <span class="s-num"><?php echo str_pad($goal, 2, '0', STR_PAD_LEFT); ?></span>
      </span>
      <div class="flex-grow-1 min-w-0">
        <p class="row-title mb-2"><?php echo $name; ?></p>
        <div class="bar"><span style="width:<?php echo $percent; ?>%;background:<?php echo $colour; ?>"></span></div>
      </div>
      <span class="mono text-end" style="font-size:12.5px;color:#6d7880;white-space:nowrap">
        <?php echo $volunteers_for_goal[$goal]; ?> volunteers<br>
        <?php echo $hours_for_goal[$goal]; ?> hours
      </span>
      <span class="text-end" style="min-width:64px">
        <span class="d-block" style="font-family:'Fraunces',serif;font-size:22px;color:<?php echo $colour; ?>">
          <?php echo $projects; ?>
        </span>
        <span class="d-block mono" style="font-size:11.5px;color:#6d7880">projects</span>
      </span>
    </div>
  </div>

<?php } ?>

<div class="focus-card mt-4">
  <p style="font-size:13px;color:rgba(255,255,255,.7);margin-bottom:8px">Focus Goal</p>
  <h2 class="focus-title mb-2">SDG 17: Partnerships for the Goals</h2>
  <p class="mb-4" style="color:rgba(255,255,255,.82);font-size:14px;line-height:1.6">
    Strengthen the means of implementation and revitalise the global partnership for sustainable development.
  </p>

  <div class="row g-3">
    <div class="col-6 col-lg-3">
      <div class="focus-stat">
        <span class="f-val"><?php echo $partnerships; ?></span>
        <span class="f-lab">Active Partnerships</span>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="focus-stat">
        <span class="f-val"><?php echo $organizations; ?></span>
        <span class="f-lab">Organizations</span>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="focus-stat">
        <span class="f-val"><?php echo $countries; ?></span>
        <span class="f-lab">Countries</span>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="focus-stat">
        <span class="f-val"><?php echo $covered; ?>/17</span>
        <span class="f-lab">SDGs Covered</span>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/app-footer.php'; ?>

<?php
$page_title = 'Events & Attendance | VPMS';
$active = 'events';

require 'includes/auth.php';
require 'config/db.php';

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$category = isset($_GET['category']) ? $_GET['category'] : 'All';

$sql = 'SELECT e.event_id, e.title, e.location, e.event_date, e.event_time, e.volunteers_needed,
               e.category, org.name AS organization, u.organization_name,
               (SELECT COUNT(*) FROM event_volunteer ev
                 WHERE ev.event_id = e.event_id AND ev.status = \'confirmed\') AS joined
        FROM event e
        LEFT JOIN organization org ON org.organization_id = e.organization_id
        LEFT JOIN `user` u ON u.user_id = e.created_by
        WHERE 1 = 1';
$values = array();

if ($search != '') {
    $sql = $sql . ' AND (e.title LIKE ? OR e.location LIKE ?)';
    $values[] = '%' . $search . '%';
    $values[] = '%' . $search . '%';
}

if ($category != 'All') {
    $sql = $sql . ' AND e.category = ?';
    $values[] = $category;
}

$sql = $sql . ' ORDER BY e.event_date DESC, e.event_time DESC';

$find = $pdo->prepare($sql);
$find->execute($values);
$events = $find->fetchAll();

// nothing marks an event finished, the date does it
$today = date('Y-m-d');
$live = array();
$upcoming = array();
$past = array();

foreach ($events as $row) {
    if ($row['event_date'] == $today) {
        $live[] = $row;
    } elseif ($row['event_date'] > $today) {
        $upcoming[] = $row;
    } else {
        $past[] = $row;
    }
}

$can_create = ($_SESSION['role_id'] == 2 || $_SESSION['role_id'] == 6);

include 'includes/app-header.php';
?>

<?php if (isset($_GET['deleted'])) { ?>
  <div class="banner"><i class="bi bi-check-circle-fill"></i>Event deleted.</div>
<?php } ?>

<div class="head-row">
  <div class="flex-grow-1">
    <h1 class="page-title">Events &amp; Attendance</h1>
    <p class="page-sub">
      <?php if ($search != '' || $category != 'All') { ?>
        <?php echo count($events); ?> events match
      <?php } else { ?>
        <?php echo count($events); ?> total events
      <?php } ?>
    </p>
  </div>
  <?php if ($can_create) { ?>
    <a class="btn-v btn-green" href="event-create.php">+ Create Event</a>
  <?php } ?>
</div>

<form class="d-flex flex-wrap align-items-center gap-3 mb-4" action="events.php" method="get">
  <input type="search" class="input-v" style="flex:1 1 320px;max-width:600px" name="q"
         placeholder="Search events by title or location..." value="<?php echo htmlspecialchars($search); ?>">
  <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
  <button class="btn-v btn-soft" type="submit">Search</button>
  <?php if ($search != '' || $category != 'All') { ?>
    <a class="link-green" style="font-size:13.5px" href="events.php">Clear</a>
  <?php } ?>
</form>

<?php if (count($events) == 0) { ?>

  <div class="card-v card-v-pad text-center mt-4">
    <?php if ($search != '' || $category != 'All') { ?>
      <p class="row-title mb-2">No events match</p>
      <p style="font-size:14px;color:#6d7880">
        Try a different search, or <a class="link-green" href="events.php">show every event</a>.
      </p>
    <?php } else { ?>
      <p class="row-title mb-2">No events yet</p>
      <p style="font-size:14px;color:#6d7880">
        <?php if ($can_create) { ?>
          Create an event, or open an opportunity and convert it into one.
        <?php } else { ?>
          Nothing has been scheduled yet. Check back soon.
        <?php } ?>
      </p>
    <?php } ?>
  </div>

<?php } ?>

<?php if (count($live) > 0) { ?>

  <!-- happening today -->
  <p class="section-label mt-4">
    <span style="display:inline-block;width:9px;height:9px;margin-right:6px;border-radius:50%;border:2px solid #98a2aa"></span>
    Live Now
  </p>

  <div class="row g-3">
    <?php foreach ($live as $row) { ?>

      <?php
      if ($row['volunteers_needed'] > 0) {
          $percent = round($row['joined'] / $row['volunteers_needed'] * 100);
      } else {
          $percent = 0;
      }

      if ($row['organization'] != '') {
          $run_by = $row['organization'];
      } else {
          $run_by = $row['organization_name'];
      }
      ?>

      <div class="col-xl-5 col-lg-6">
        <a class="card-v card-v-pad d-block" href="event-details.php?id=<?php echo $row['event_id']; ?>">
          <div class="d-flex align-items-center gap-2 mb-3">
            <span class="chip"><?php echo htmlspecialchars($row['category']); ?></span>
            <span class="badge-v badge-green ms-auto">Ongoing</span>
          </div>
          <h2 class="row-title mb-1" style="font-size:16px"><?php echo htmlspecialchars($row['title']); ?></h2>
          <p class="row-meta mb-3" style="color:#16663e">
            <?php echo htmlspecialchars($run_by); ?> · <?php echo htmlspecialchars($row['location']); ?>
          </p>
          <p class="mono mb-3" style="font-size:12.5px;color:#6d7880">
            <?php echo date('j M', strtotime($row['event_date'])); ?>
            &nbsp;·&nbsp; <?php echo date('h:i A', strtotime($row['event_time'])); ?>
          </p>
          <div class="bar-row">
            <span style="color:#6d7880">Volunteers</span>
            <span class="count"><?php echo $row['joined']; ?>/<?php echo $row['volunteers_needed']; ?></span>
          </div>
          <div class="bar <?php if ($percent >= 100) echo 'over'; ?>">
            <span style="width:<?php echo min($percent, 100); ?>%"></span>
          </div>
        </a>
      </div>

    <?php } ?>
  </div>

<?php } ?>

<?php if (count($upcoming) > 0) { ?>

  <!-- coming up -->
  <p class="section-label mt-4">Upcoming Events</p>

  <div class="row g-3">
    <?php foreach ($upcoming as $row) { ?>

      <?php
      if ($row['volunteers_needed'] > 0) {
          $percent = round($row['joined'] / $row['volunteers_needed'] * 100);
      } else {
          $percent = 0;
      }

      if ($row['organization'] != '') {
          $run_by = $row['organization'];
      } else {
          $run_by = $row['organization_name'];
      }
      ?>

      <div class="col-xl-5 col-lg-6">
        <a class="card-v card-v-pad d-block" href="event-details.php?id=<?php echo $row['event_id']; ?>">
          <div class="d-flex align-items-center gap-2 mb-3">
            <span class="chip"><?php echo htmlspecialchars($row['category']); ?></span>
            <span class="badge-v badge-blue ms-auto">Upcoming</span>
          </div>
          <h2 class="row-title mb-1" style="font-size:16px"><?php echo htmlspecialchars($row['title']); ?></h2>
          <p class="row-meta mb-3" style="color:#16663e">
            <?php echo htmlspecialchars($run_by); ?> · <?php echo htmlspecialchars($row['location']); ?>
          </p>
          <p class="mono mb-3" style="font-size:12.5px;color:#6d7880">
            <?php echo date('j M', strtotime($row['event_date'])); ?>
            &nbsp;·&nbsp; <?php echo date('h:i A', strtotime($row['event_time'])); ?>
          </p>
          <div class="bar-row">
            <span style="color:#6d7880">Volunteers</span>
            <span class="count"><?php echo $row['joined']; ?>/<?php echo $row['volunteers_needed']; ?></span>
          </div>
          <div class="bar <?php if ($percent >= 100) echo 'over'; ?>">
            <span style="width:<?php echo min($percent, 100); ?>%"></span>
          </div>
        </a>
      </div>

    <?php } ?>
  </div>

<?php } ?>

<?php if (count($past) > 0) { ?>

  <!-- finished -->
  <p class="section-label mt-4">Past Events</p>

  <div class="row g-3">
    <?php foreach ($past as $row) { ?>

      <?php
      if ($row['volunteers_needed'] > 0) {
          $percent = round($row['joined'] / $row['volunteers_needed'] * 100);
      } else {
          $percent = 0;
      }

      if ($row['organization'] != '') {
          $run_by = $row['organization'];
      } else {
          $run_by = $row['organization_name'];
      }
      ?>

      <div class="col-xl-5 col-lg-6">
        <a class="card-v card-v-pad d-block" href="event-details.php?id=<?php echo $row['event_id']; ?>">
          <div class="d-flex align-items-center gap-2 mb-3">
            <span class="chip"><?php echo htmlspecialchars($row['category']); ?></span>
            <span class="badge-v badge-grey ms-auto">Completed</span>
          </div>
          <h2 class="row-title mb-1" style="font-size:16px"><?php echo htmlspecialchars($row['title']); ?></h2>
          <p class="row-meta mb-3" style="color:#16663e">
            <?php echo htmlspecialchars($run_by); ?> · <?php echo htmlspecialchars($row['location']); ?>
          </p>
          <p class="mono mb-3" style="font-size:12.5px;color:#6d7880">
            <?php echo date('j M', strtotime($row['event_date'])); ?>
            &nbsp;·&nbsp; <?php echo date('h:i A', strtotime($row['event_time'])); ?>
          </p>
          <div class="bar-row">
            <span style="color:#6d7880">Volunteers</span>
            <span class="count"><?php echo $row['joined']; ?>/<?php echo $row['volunteers_needed']; ?></span>
          </div>
          <div class="bar <?php if ($percent >= 100) echo 'over'; ?>">
            <span style="width:<?php echo min($percent, 100); ?>%"></span>
          </div>
        </a>
      </div>

    <?php } ?>
  </div>

<?php } ?>

<?php include 'includes/app-footer.php'; ?>

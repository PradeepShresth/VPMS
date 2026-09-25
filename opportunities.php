<?php
$page_title = 'Opportunities | VPMS';
$active = 'opportunities';

require 'includes/auth.php';
require 'config/db.php';

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$category = isset($_GET['category']) ? $_GET['category'] : 'All';

$sql = 'SELECT o.opportunity_id, o.title, o.location, o.opportunity_date, o.hours_required,
               o.spots, o.category, o.skills, o.status,
               org.name AS organization, u.organization_name,
               (SELECT COUNT(*) FROM application a
                 WHERE a.opportunity_id = o.opportunity_id AND a.status = \'accepted\') AS filled,
               (SELECT mine.status FROM application mine
                 WHERE mine.opportunity_id = o.opportunity_id AND mine.user_id = ?) AS my_status
        FROM opportunity o
        LEFT JOIN organization org ON org.organization_id = o.organization_id
        LEFT JOIN `user` u ON u.user_id = o.created_by
        WHERE 1 = 1';
// the search box and the pills each add a bit onto the query
$values = array($_SESSION['user_id']);

if ($search != '') {
    $sql = $sql . ' AND (o.title LIKE ? OR o.location LIKE ?)';
    $values[] = '%' . $search . '%';
    $values[] = '%' . $search . '%';
}

if ($category != 'All') {
    $sql = $sql . ' AND o.category = ?';
    $values[] = $category;
}

$sql = $sql . ' ORDER BY o.created_at DESC';

$find = $pdo->prepare($sql);
$find->execute($values);
$opportunities = $find->fetchAll();

$can_post = ($_SESSION['role_id'] == 2 || $_SESSION['role_id'] == 6);

include 'includes/app-header.php';
?>

<?php if (isset($_GET['deleted'])) { ?>
  <div class="banner"><i class="bi bi-check-circle-fill"></i>Opportunity deleted.</div>
<?php } ?>

<div class="head-row">
  <div class="flex-grow-1">
    <h1 class="page-title">Volunteer Opportunities</h1>
    <p class="page-sub"><?php echo count($opportunities); ?> opportunities available</p>
  </div>
  <?php if ($can_post) { ?>
    <a class="btn-v btn-green" href="opportunity-create.php">+ Post Opportunity</a>
  <?php } ?>
</div>

<!-- search and filters -->
<form class="d-flex flex-wrap align-items-center gap-3 mb-4" action="opportunities.php" method="get">
  <input type="search" class="input-v" style="flex:1 1 320px;max-width:600px" name="q"
         placeholder="Search opportunities..." value="<?php echo htmlspecialchars($search); ?>">
  <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
  <button class="btn-v btn-soft" type="submit">Search</button>
</form>

<div class="d-flex flex-wrap gap-2 mb-4">
  <a class="pill <?php if ($category == 'All') echo 'active'; ?>"
     href="opportunities.php?q=<?php echo urlencode($search); ?>&category=All">All</a>
  <a class="pill <?php if ($category == 'Environment') echo 'active'; ?>"
     href="opportunities.php?q=<?php echo urlencode($search); ?>&category=Environment">Environment</a>
  <a class="pill <?php if ($category == 'Education') echo 'active'; ?>"
     href="opportunities.php?q=<?php echo urlencode($search); ?>&category=Education">Education</a>
  <a class="pill <?php if ($category == 'Food Security') echo 'active'; ?>"
     href="opportunities.php?q=<?php echo urlencode($search); ?>&category=Food Security">Food Security</a>
  <a class="pill <?php if ($category == 'Health') echo 'active'; ?>"
     href="opportunities.php?q=<?php echo urlencode($search); ?>&category=Health">Health</a>
  <a class="pill <?php if ($category == 'Advocacy') echo 'active'; ?>"
     href="opportunities.php?q=<?php echo urlencode($search); ?>&category=Advocacy">Advocacy</a>
  <a class="pill <?php if ($category == 'Technology') echo 'active'; ?>"
     href="opportunities.php?q=<?php echo urlencode($search); ?>&category=Technology">Technology</a>
</div>

<?php if (count($opportunities) == 0) { ?>

  <div class="card-v card-v-pad text-center">
    <p class="row-title mb-2">No opportunities yet</p>
    <p style="font-size:14px;color:#6d7880">
      <?php if ($can_post) { ?>
        Post the first one and it will show up here for volunteers to apply to.
      <?php } else { ?>
        Nothing has been posted yet. Check back soon.
      <?php } ?>
    </p>
  </div>

<?php } else { ?>

<div class="row g-3">

  <?php foreach ($opportunities as $row) { ?>

    <?php
    if ($row['spots'] > 0) {
        $percent = round($row['filled'] / $row['spots'] * 100);
    } else {
        $percent = 0;
    }

    if ($percent > 100) {
        $percent = 100;
    }

    if ($row['organization'] != '') {
        $posted_by = $row['organization'];
    } else {
        $posted_by = $row['organization_name'];
    }
    ?>

    <div class="col-xl-5 col-lg-6">
      <a class="card-v card-v-pad d-block h-100" href="opportunity-details.php?id=<?php echo $row['opportunity_id']; ?>">
        <div class="d-flex align-items-center gap-2 mb-3">
          <span class="chip"><?php echo htmlspecialchars($row['category']); ?></span>
          <?php if ($row['my_status'] == 'pending') { ?>
            <span class="badge-v badge-pending ms-auto">Applied</span>
          <?php } elseif ($row['my_status'] == 'accepted') { ?>
            <span class="badge-v badge-green ms-auto">Accepted</span>
          <?php } elseif ($row['my_status'] == 'rejected') { ?>
            <span class="badge-v badge-grey ms-auto">Not selected</span>
          <?php } elseif ($row['status'] == 'open') { ?>
            <span class="badge-v badge-navy ms-auto">Open</span>
          <?php } else { ?>
            <span class="badge-v badge-red ms-auto">Closed</span>
          <?php } ?>
        </div>

        <h2 class="row-title mb-1" style="font-size:16px"><?php echo htmlspecialchars($row['title']); ?></h2>
        <p class="row-meta mb-3" style="color:#16663e">
          <?php echo htmlspecialchars($posted_by); ?> · <?php echo htmlspecialchars($row['location']); ?>
        </p>
        <p class="mono mb-3" style="font-size:12.5px;color:#6d7880">
          <?php echo date('j M', strtotime($row['opportunity_date'])); ?>
          &nbsp;·&nbsp; <?php echo $row['hours_required']; ?>h
        </p>

        <div class="bar-row">
          <span style="color:#6d7880">Spots filled</span>
          <span class="count"><?php echo $row['filled']; ?>/<?php echo $row['spots']; ?></span>
        </div>
        <div class="bar <?php if ($percent >= 100) echo 'full'; ?> mb-3">
          <span style="width:<?php echo $percent; ?>%"></span>
        </div>

        <div class="d-flex flex-wrap gap-2">
          <?php
          $skills = explode(',', $row['skills']);
          foreach ($skills as $skill) {
              $skill = trim($skill);
              if ($skill != '') { ?>
                <span class="chip" style="font-size:12px"><?php echo htmlspecialchars($skill); ?></span>
          <?php }
          } ?>
        </div>
      </a>
    </div>

  <?php } ?>

</div>

<?php } ?>

<?php include 'includes/app-footer.php'; ?>

<?php
// Temporary page to check the database connection works.
// Delete this once the real pages are reading from the database.

$page_title = 'Database Test | VPMS';
$body_class = 'auth-center';

require 'config/db.php';

// if we got this far the connection worked, so read a few details back
$version = $pdo->query('SELECT VERSION()')->fetchColumn();
$dbname_check = $pdo->query('SELECT DATABASE()')->fetchColumn();
$tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);

include 'includes/auth-header.php';
?>

<div class="auth-card">

  <h1 class="auth-title">Database connected</h1>
  <p class="auth-sub">PHP is talking to MySQL through <code>config/db.php</code>.</p>

  <div class="card-v card-v-pad mb-4">

    <div class="doc-row">
      <i class="bi bi-check-circle-fill" style="color:#16663e"></i>
      Connection
      <span class="ms-auto mono" style="font-size:12.5px">OK</span>
    </div>

    <div class="doc-row">
      <i class="bi bi-database" style="color:#6d7880"></i>
      Database
      <span class="ms-auto mono" style="font-size:12.5px"><?php echo $dbname_check; ?></span>
    </div>

    <div class="doc-row">
      <i class="bi bi-hdd" style="color:#6d7880"></i>
      MySQL version
      <span class="ms-auto mono" style="font-size:12.5px"><?php echo $version; ?></span>
    </div>

    <div class="doc-row mb-0">
      <i class="bi bi-table" style="color:#6d7880"></i>
      Tables
      <span class="ms-auto mono" style="font-size:12.5px"><?php echo count($tables); ?></span>
    </div>

  </div>

  <?php if (count($tables) == 0) { ?>
    <div class="notice mb-4">
      <p class="notice-title">No tables yet</p>
      <p class="notice-text">
        The database is empty, which is expected at this stage. Tables come next.
      </p>
    </div>
  <?php } else { ?>
    <p class="section-label">Tables in this database</p>
    <div class="card-v card-v-pad mb-4">
      <?php foreach ($tables as $table) { ?>
        <span class="chip chip-mono me-2 mb-2"><?php echo $table; ?></span>
      <?php } ?>
    </div>
  <?php } ?>

  <a class="btn-v btn-green btn-block btn-lg-v" href="index.php">Back to Home</a>

</div>

<?php include 'includes/auth-footer.php'; ?>

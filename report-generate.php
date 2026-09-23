<?php
$page_title = 'Generate Report | VPMS';
$active = 'reports';

require 'includes/auth.php';
require 'config/db.php';

$find = $pdo->query('SELECT organisation_id, name FROM organisation ORDER BY name');
$organisations = $find->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type'];
    $from = $_POST['from'];
    $to = $_POST['to'];

    if ($from != '' && $to != '') {
        $period = date('j M Y', strtotime($from)) . ' - ' . date('j M Y', strtotime($to));
    } elseif ($from != '') {
        $period = 'From ' . date('j M Y', strtotime($from));
    } else {
        $period = 'All time';
    }

    if ($_POST['org'] != '0') {
        $find = $pdo->prepare('SELECT name FROM organisation WHERE organisation_id = ?');
        $find->execute(array($_POST['org']));
        $title = $type . ' — ' . $find->fetchColumn();
    } else {
        $title = $type . ' — All organisations';
    }

    $save = $pdo->prepare(
        'INSERT INTO report (title, report_type, period, generated_by) VALUES (?, ?, ?, ?)'
    );
    $save->execute(array($title, $type, $period, $_SESSION['user_id']));

    header('Location: reports.php?generated=1');
    exit;
}

include 'includes/app-header.php';
?>

<div class="col-narrow mx-auto">

  <a class="back-link" href="reports.php"><i class="bi bi-arrow-left"></i> Back to Reports</a>

  <h1 class="page-title">Generate Report</h1>
  <p class="page-sub mb-4">Build a certified impact report for partners, donors or regulators.</p>

  <form action="report-generate.php" method="post">

    <div class="field">
      <label class="field-label" for="type">Report Type</label>
      <select class="select-v" id="type" name="type">
        <option>Volunteer Activity Report</option>
        <option>Partnership Performance Report</option>
        <option>Community Impact Report</option>
        <option>Sponsor Contribution Report</option>
        <option>Project Progress Report</option>
        <option>SDG Contribution Summary</option>
      </select>
    </div>

    <div class="row g-3">
      <div class="col-sm-6">
        <div class="field">
          <label class="field-label" for="from">From</label>
          <input class="input-v" type="date" id="from" name="from">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="field">
          <label class="field-label" for="to">To</label>
          <input class="input-v" type="date" id="to" name="to">
        </div>
      </div>
    </div>

    <div class="field">
      <label class="field-label" for="org">Organisation</label>
      <select class="select-v" id="org" name="org">
        <option value="0">All organisations</option>
        <?php foreach ($organisations as $row) { ?>
          <option value="<?php echo $row['organisation_id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
        <?php } ?>
      </select>
    </div>

    <div class="field">
      <span class="field-label">Include sections</span>
      <div class="check-grid" style="grid-template-columns:repeat(2,1fr)">
        <label class="check-v"><input type="checkbox" checked> Executive summary</label>
        <label class="check-v"><input type="checkbox" checked> Volunteer hours breakdown</label>
        <label class="check-v"><input type="checkbox" checked> Event attendance log</label>
        <label class="check-v"><input type="checkbox"> Partnership status</label>
        <label class="check-v"><input type="checkbox"> SDG contribution map</label>
        <label class="check-v"><input type="checkbox"> Financial utilisation</label>
      </div>
    </div>

    <div class="field mb-4">
      <p class="notice-text" style="color:#6d7880">
        Reports download as CSV, which opens in Excel.
      </p>
    </div>

    <div class="row g-3">
      <div class="col-5">
        <a class="btn-v btn-outline btn-block btn-lg-v" href="reports.php">Cancel</a>
      </div>
      <div class="col-7">
        <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Generate Report</button>
      </div>
    </div>
  </form>

</div>

<?php include 'includes/app-footer.php'; ?>

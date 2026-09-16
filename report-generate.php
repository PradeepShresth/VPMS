<?php
$page_title = 'Generate Report | VPMS';
$active = 'reports';
include 'includes/app-header.php';
?>

<div class="col-narrow mx-auto">

  <a class="back-link" href="reports.php"><i class="bi bi-arrow-left"></i> Back to Reports</a>

  <h1 class="page-title">Generate Report</h1>
  <p class="page-sub mb-4">Build a certified impact report for partners, donors or regulators.</p>

  <form action="reports.php" method="get">

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
        <option>All organisations</option>
        <option>Green Future NGO</option>
        <option>TechCorp China</option>
        <option>Food Foundation</option>
        <option>Global Impact Fund</option>
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
      <span class="field-label">Output format</span>
      <div class="d-flex flex-wrap gap-3">
        <label class="check-v"><input type="radio" name="format" value="pdf" checked> PDF</label>
        <label class="check-v"><input type="radio" name="format" value="csv"> CSV</label>
        <label class="check-v"><input type="radio" name="format" value="xlsx"> Excel (XLSX)</label>
      </div>
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

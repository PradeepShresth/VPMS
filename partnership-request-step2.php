<?php
$page_title = 'Request Partnership | VPMS';
$active = 'partnerships';

require 'includes/auth.php';
require 'config/db.php';

// a partnership is asked for by an organization, so a volunteer has no business here
if ($_SESSION['role_id'] != 2 && $_SESSION['role_id'] != 6) {
    header('Location: partnerships.php');
    exit;
}

// step 1 sent the partner across, keep hold of it for step 3
$partner_id = isset($_POST['partner_id']) ? $_POST['partner_id'] : 0;

$find = $pdo->prepare('SELECT name FROM organization WHERE organization_id = ?');
$find->execute(array($partner_id));
$partner = $find->fetch();

if ($partner == false) {
    header('Location: partnership-request.php');
    exit;
}

include 'includes/app-header.php';
?>

<div class="col-narrow mx-auto">

  <a class="back-link" href="partnership-request.php"><i class="bi bi-arrow-left"></i> Back</a>

  <h1 class="page-title mb-4">Request Partnership</h1>

  <div class="steps">
    <span class="done"></span>
    <span class="done"></span>
    <span></span>
  </div>

  <p class="section-label">Step 2: Partnership Details</p>

  <p class="mb-4" style="font-size:13.5px;color:#6d7880">
    Partnering with <strong style="color:#16663e"><?php echo htmlspecialchars($partner['name']); ?></strong>.
  </p>

  <form action="partnership-request-step3.php" method="post">
    <input type="hidden" name="partner_id" value="<?php echo $partner_id; ?>">

    <div class="field">
      <label class="field-label" for="type">Partnership Type</label>
      <select class="select-v" id="type" name="type">
        <option>Corporate-NGO</option>
        <option>Corporate-Community</option>
        <option>NGO-Government</option>
        <option>NGO-NGO</option>
        <option>Sponsor-NGO</option>
      </select>
    </div>

    <div class="field">
      <label class="field-label" for="start">Start Date</label>
      <input class="input-v" type="date" id="start" name="start" required>
    </div>

    <div class="field">
      <label class="field-label" for="end">End Date</label>
      <input class="input-v" type="date" id="end" name="end" required>
    </div>

    <div class="field mb-4">
      <span class="field-label">SDG Goals (select all that apply)</span>
      <div class="check-grid">
        <label class="check-v"><input type="checkbox" name="sdg[]" value="1"> SDG 1</label>
        <label class="check-v"><input type="checkbox" name="sdg[]" value="2"> SDG 2</label>
        <label class="check-v"><input type="checkbox" name="sdg[]" value="3"> SDG 3</label>
        <label class="check-v"><input type="checkbox" name="sdg[]" value="4"> SDG 4</label>
        <label class="check-v"><input type="checkbox" name="sdg[]" value="13"> SDG 13</label>
        <label class="check-v"><input type="checkbox" name="sdg[]" value="14"> SDG 14</label>
        <label class="check-v"><input type="checkbox" name="sdg[]" value="15"> SDG 15</label>
        <label class="check-v"><input type="checkbox" name="sdg[]" value="17"> SDG 17</label>
      </div>
    </div>

    <div class="row g-3">
      <div class="col-sm-5">
        <a class="btn-v btn-outline btn-block btn-lg-v" href="partnership-request.php">&larr; Back</a>
      </div>
      <div class="col-sm-7">
        <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Continue &rarr;</button>
      </div>
    </div>
  </form>

</div>

<?php include 'includes/app-footer.php'; ?>

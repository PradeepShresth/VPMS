<?php
$page_title = 'Request Partnership | VPMS';
$active = 'partnerships';

require 'includes/auth.php';
require 'config/db.php';

// steps 1 and 2 arrive here in hidden fields, nothing is saved until the end
$partner_id = isset($_POST['partner_id']) ? $_POST['partner_id'] : 0;
$type = isset($_POST['type']) ? $_POST['type'] : '';
$start = isset($_POST['start']) ? $_POST['start'] : '';
$end = isset($_POST['end']) ? $_POST['end'] : '';

if (isset($_POST['sdg'])) {
    $sdg = implode(',', $_POST['sdg']);
} elseif (isset($_POST['sdg_goals'])) {
    $sdg = $_POST['sdg_goals'];
} else {
    $sdg = '';
}

$find = $pdo->prepare('SELECT name FROM organisation WHERE organisation_id = ?');
$find->execute(array($partner_id));
$partner = $find->fetch();

if ($partner == false) {
    header('Location: partnership-request.php');
    exit;
}

if (isset($_POST['objectives'])) {
    $save = $pdo->prepare(
        'INSERT INTO partnership (organisation_id, partner_id, type, start_date, end_date,
                                  sdg_goals, objectives, expected_impact, requested_by)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );

    $save->execute(array(
        $_SESSION['organisation_id'],
        $partner_id,
        $type,
        $start,
        $end,
        $sdg,
        trim($_POST['objectives']),
        trim($_POST['impact']),
        $_SESSION['user_id']
    ));

    header('Location: partnership-requested.php?id=' . $pdo->lastInsertId());
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
    <span class="done"></span>
  </div>

  <p class="section-label">Step 3: Objectives &amp; Justification</p>

  <p class="mb-4" style="font-size:13.5px;color:#6d7880">
    <strong style="color:#16663e"><?php echo htmlspecialchars($partner['name']); ?></strong>
    · <?php echo htmlspecialchars($type); ?>
    · <?php echo htmlspecialchars($start); ?> to <?php echo htmlspecialchars($end); ?>
  </p>

  <form action="partnership-request-step3.php" method="post">
    <input type="hidden" name="partner_id" value="<?php echo $partner_id; ?>">
    <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
    <input type="hidden" name="start" value="<?php echo htmlspecialchars($start); ?>">
    <input type="hidden" name="end" value="<?php echo htmlspecialchars($end); ?>">
    <input type="hidden" name="sdg_goals" value="<?php echo htmlspecialchars($sdg); ?>">

    <div class="field">
      <label class="field-label" for="objectives">Partnership Objectives</label>
      <textarea class="textarea-v" id="objectives" name="objectives" required
                placeholder="Describe the goals and expected outcomes of this partnership..."></textarea>
    </div>

    <div class="field mb-4">
      <label class="field-label" for="impact">Expected Impact</label>
      <textarea class="textarea-v" id="impact" name="impact"
                placeholder="Quantify the anticipated community benefit and SDG contributions..."></textarea>
    </div>

    <div class="row g-3">
      <div class="col-sm-5">
        <a class="btn-v btn-outline btn-block btn-lg-v" href="partnership-request.php">&larr; Start again</a>
      </div>
      <div class="col-sm-7">
        <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Submit Request</button>
      </div>
    </div>
  </form>

</div>

<?php include 'includes/app-footer.php'; ?>

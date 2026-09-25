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

$my_organization = $_SESSION['organization_id'];

// do not offer my own organization as the partner
if ($my_organization != '') {
    $find = $pdo->prepare(
        'SELECT organization_id, name, type FROM organization
         WHERE status = ? AND organization_id != ? ORDER BY name'
    );
    $find->execute(array('verified', $my_organization));
} else {
    $find = $pdo->prepare('SELECT organization_id, name, type FROM organization WHERE status = ? ORDER BY name');
    $find->execute(array('verified'));
}

$partners = $find->fetchAll();

include 'includes/app-header.php';
?>

<div class="col-narrow mx-auto">

  <a class="back-link" href="partnerships.php"><i class="bi bi-arrow-left"></i> Back</a>

  <h1 class="page-title mb-4">Request Partnership</h1>

  <div class="steps">
    <span class="done"></span>
    <span></span>
    <span></span>
  </div>

  <p class="section-label">Step 1: Select Partner Organization</p>

  <?php if ($my_organization == '') { ?>

    <div class="notice mb-4">
      <p class="notice-title">Your account is not linked to an organization</p>
      <p class="notice-text">
        Partnership requests are made between two organizations. Register an organization, or ask an
        administrator to link your account to one, then come back here.
      </p>
    </div>

  <?php } elseif (count($partners) == 0) { ?>

    <div class="card-v card-v-pad text-center">
      <p class="row-title mb-2">No organizations to partner with yet</p>
      <p style="font-size:14px;color:#6d7880">
        Only verified organizations can be chosen. Once another one is verified it will appear here.
      </p>
    </div>

  <?php } else { ?>

    <form action="partnership-request-step2.php" method="post">

      <?php foreach ($partners as $row) { ?>
        <label class="choice">
          <input type="radio" name="partner_id" value="<?php echo $row['organization_id']; ?>" required>
          <span class="choice-box">
            <span class="choice-radio"></span>
            <span class="choice-title"><?php echo htmlspecialchars($row['name']); ?></span>
          </span>
        </label>
      <?php } ?>

      <button class="btn-v btn-green btn-block btn-lg-v mt-3" type="submit">Continue &rarr;</button>
    </form>

  <?php } ?>

</div>

<?php include 'includes/app-footer.php'; ?>

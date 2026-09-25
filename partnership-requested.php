<?php
$page_title = 'Request Submitted | VPMS';
$active = 'partnerships';

require 'includes/auth.php';
require 'config/db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$find = $pdo->prepare(
    'SELECT partner.name AS partner_name
     FROM partnership p
     LEFT JOIN organization partner ON partner.organization_id = p.partner_id
     WHERE p.partnership_id = ?'
);
$find->execute(array($id));
$partnership = $find->fetch();

if ($partnership == false) {
    header('Location: partnerships.php');
    exit;
}

include 'includes/app-header.php';
?>

<a class="back-link" href="partnerships.php"><i class="bi bi-arrow-left"></i> Back</a>

<div class="success-wrap">
  <span class="success-icon"><i class="bi bi-record-circle-fill" style="font-size:20px"></i></span>
  <h1 class="success-title">Request Submitted</h1>
  <p class="success-text">
    Your partnership request with
    <strong><?php echo htmlspecialchars($partnership['partner_name']); ?></strong>
    has been sent. It stays pending until the System Administrator approves it.
  </p>
  <div class="d-flex justify-content-center gap-2">
    <a class="btn-v btn-green" href="partnership-details.php?id=<?php echo $id; ?>">View Request</a>
    <a class="btn-v btn-soft" href="partnerships.php">Back to Partnerships</a>
  </div>
</div>

<?php include 'includes/app-footer.php'; ?>

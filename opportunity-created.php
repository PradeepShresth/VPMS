<?php
$page_title = 'Opportunity Created | VPMS';
$active = 'opportunities';

require 'includes/auth.php';
require 'config/db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$find = $pdo->prepare('SELECT title FROM opportunity WHERE opportunity_id = ?');
$find->execute(array($id));
$opportunity = $find->fetch();

if ($opportunity == false) {
    header('Location: opportunities.php');
    exit;
}

include 'includes/app-header.php';
?>

<a class="back-link" href="opportunities.php"><i class="bi bi-arrow-left"></i> Back to Opportunities</a>

<div class="success-wrap">
  <span class="success-icon"><i class="bi bi-check-lg"></i></span>
  <h1 class="success-title">Opportunity Created</h1>
  <p class="success-text">
    <strong><?php echo htmlspecialchars($opportunity['title']); ?></strong> is now published and
    volunteers can apply to it.
  </p>
  <div class="d-flex justify-content-center gap-2">
    <a class="btn-v btn-green" href="opportunity-details.php?id=<?php echo $id; ?>">View Opportunity</a>
    <a class="btn-v btn-soft" href="opportunities.php">Back to Opportunities</a>
  </div>
</div>

<?php include 'includes/app-footer.php'; ?>

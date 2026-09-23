<?php
$page_title = 'Application Submitted | VPMS';
$active = 'opportunities';

require 'includes/auth.php';
require 'config/db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$find = $pdo->prepare(
    'SELECT o.title, org.name AS organisation, u.organisation_name
     FROM opportunity o
     LEFT JOIN organisation org ON org.organisation_id = o.organisation_id
     LEFT JOIN `user` u ON u.user_id = o.created_by
     WHERE o.opportunity_id = ?'
);
$find->execute(array($id));
$opportunity = $find->fetch();

if ($opportunity == false) {
    header('Location: opportunities.php');
    exit;
}

if ($opportunity['organisation'] != '') {
    $posted_by = $opportunity['organisation'];
} else {
    $posted_by = $opportunity['organisation_name'];
}

include 'includes/app-header.php';
?>

<a class="back-link" href="opportunities.php"><i class="bi bi-arrow-left"></i> Back to Opportunities</a>

<div class="success-wrap">
  <span class="success-icon"><i class="bi bi-check-lg"></i></span>
  <h1 class="success-title">Application Submitted</h1>
  <p class="success-text">
    <?php echo htmlspecialchars($posted_by); ?> will review your application for
    <strong><?php echo htmlspecialchars($opportunity['title']); ?></strong> and respond in the
    Communications hub.
  </p>
  <div class="d-flex justify-content-center gap-2">
    <a class="btn-v btn-green" href="opportunities.php">Browse More Opportunities</a>
    <a class="btn-v btn-soft" href="profile.php">My Activity</a>
  </div>
</div>

<?php include 'includes/app-footer.php'; ?>

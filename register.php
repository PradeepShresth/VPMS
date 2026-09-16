<?php
$page_title = 'Create Account | VPMS';
$body_class = 'auth-center';

require 'config/db.php';

// the five roles people can sign themselves up as (not System Administrator)
$roles = $pdo->query('SELECT role_id, name, description FROM role WHERE role_id <= 5 ORDER BY role_id')->fetchAll();

include 'includes/auth-header.php';
?>

<div class="auth-card">

  <div class="auth-topbar">
    <a class="link-green" href="index.php"><i class="bi bi-arrow-left"></i> Back</a>
    <a class="link-green ms-auto" href="register-org.php">Register an Organization</a>
  </div>

  <h1 class="auth-title">Create your account</h1>
  <p class="auth-sub">Step 1 of 2 — Choose your role</p>

  <div class="steps">
    <span class="done"></span>
    <span></span>
  </div>

  <p class="section-label">I am joining as a...</p>

  <form action="register-step2.php" method="get">

    <?php
    // the values are role_id numbers from the role table
    foreach ($roles as $role) {
    ?>
      <label class="choice">
        <input type="radio" name="role" value="<?php echo $role['role_id']; ?>"
               <?php if ($role['role_id'] == 1) echo 'checked'; ?>>
        <span class="choice-box d-block">
          <span class="choice-title"><?php echo $role['name']; ?></span>
          <span class="choice-note"><?php echo $role['description']; ?></span>
        </span>
      </label>
    <?php } ?>

    <button class="btn-v btn-green btn-block btn-lg-v mt-3" type="submit">Continue &rarr;</button>
  </form>

  <p class="text-center mt-4" style="font-size:13.5px;color:#6d7880">
    Already have an account? <a class="link-green" href="login.php">Sign in</a>
  </p>

</div>

<?php include 'includes/auth-footer.php'; ?>

<?php
$page_title = 'Create Opportunity | VPMS';
$active = 'opportunities';

require 'includes/auth.php';
require 'config/db.php';

if ($_SESSION['role_id'] != 2 && $_SESSION['role_id'] != 3 && $_SESSION['role_id'] != 6) {
    header('Location: opportunities.php');
    exit;
}

$errors = array();
$title = '';
$location = '';
$date = '';
$hours = 6;
$spots = 20;
$category = 'Environment';
$description = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $location = trim($_POST['location']);
    $date = $_POST['date'];
    $hours = $_POST['hours'];
    $spots = $_POST['spots'];
    $category = $_POST['category'];
    $description = trim($_POST['description']);

    if ($title == '') {
        $errors[] = 'Give the opportunity a title.';
    }

    if ($date == '') {
        $errors[] = 'Pick the date it takes place.';
    }

    if ($spots < 1) {
        $errors[] = 'There has to be at least one volunteer spot.';
    }

    if (isset($_POST['skills'])) {
        $skills = implode(', ', $_POST['skills']);
    } else {
        $skills = '';
    }

    if (isset($_POST['sdg'])) {
        $sdg = implode(',', $_POST['sdg']);
    } else {
        $sdg = '';
    }

    if (count($errors) == 0) {
        $save = $pdo->prepare(
            'INSERT INTO opportunity (title, organisation_id, created_by, location, opportunity_date,
                                      hours_required, spots, category, skills, sdg_goals, description)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );

        $save->execute(array(
            $title,
            $_SESSION['organisation_id'],
            $_SESSION['user_id'],
            $location,
            $date,
            $hours,
            $spots,
            $category,
            $skills,
            $sdg,
            $description
        ));

        header('Location: opportunity-created.php?id=' . $pdo->lastInsertId());
        exit;
    }
}

include 'includes/app-header.php';
?>

<div class="col-narrow mx-auto">

  <a class="back-link" href="opportunities.php"><i class="bi bi-arrow-left"></i> Back to Opportunities</a>

  <h1 class="page-title mb-4">Create Opportunity</h1>

  <?php if (count($errors) > 0) { ?>
    <div class="notice mb-4" style="border-left-color:#c8504b;background:#fdf3f2">
      <p class="notice-title" style="color:#c8504b">Please fix the following</p>
      <?php foreach ($errors as $error) { ?>
        <p class="notice-text"><?php echo $error; ?></p>
      <?php } ?>
    </div>
  <?php } ?>

  <form action="opportunity-create.php" method="post">

    <div class="field">
      <label class="field-label" for="title">Opportunity Title</label>
      <input class="input-v" type="text" id="title" name="title" placeholder="Beach Clean-Up Drive"
             value="<?php echo htmlspecialchars($title); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="location">Location</label>
      <input class="input-v" type="text" id="location" name="location" placeholder="Port Dickson, Negeri Sembilan"
             value="<?php echo htmlspecialchars($location); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="date">Date</label>
      <input class="input-v" type="date" id="date" name="date" value="<?php echo htmlspecialchars($date); ?>">
    </div>

    <div class="row g-3">
      <div class="col-sm-6">
        <div class="field">
          <label class="field-label" for="hours">Hours Required</label>
          <input class="input-v" type="number" id="hours" name="hours" value="<?php echo htmlspecialchars($hours); ?>">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="field">
          <label class="field-label" for="spots">Volunteer Spots</label>
          <input class="input-v" type="number" id="spots" name="spots" value="<?php echo htmlspecialchars($spots); ?>">
        </div>
      </div>
    </div>

    <div class="field">
      <label class="field-label" for="category">Category</label>
      <select class="select-v" id="category" name="category">
        <option <?php if ($category == 'Environment') echo 'selected'; ?>>Environment</option>
        <option <?php if ($category == 'Education') echo 'selected'; ?>>Education</option>
        <option <?php if ($category == 'Food Security') echo 'selected'; ?>>Food Security</option>
        <option <?php if ($category == 'Health') echo 'selected'; ?>>Health</option>
        <option <?php if ($category == 'Advocacy') echo 'selected'; ?>>Advocacy</option>
        <option <?php if ($category == 'Technology') echo 'selected'; ?>>Technology</option>
        <option <?php if ($category == 'Community Service') echo 'selected'; ?>>Community Service</option>
      </select>
    </div>

    <div class="field">
      <span class="field-label">Skills Required</span>
      <div class="check-grid" style="grid-template-columns:repeat(2,1fr)">
        <label class="check-v"><input type="checkbox" name="skills[]" value="Physical Fitness"> Physical Fitness</label>
        <label class="check-v"><input type="checkbox" name="skills[]" value="Teamwork"> Teamwork</label>
        <label class="check-v"><input type="checkbox" name="skills[]" value="Environmental Awareness"> Environmental Awareness</label>
        <label class="check-v"><input type="checkbox" name="skills[]" value="First Aid"> First Aid</label>
        <label class="check-v"><input type="checkbox" name="skills[]" value="Driving Licence"> Driving Licence</label>
        <label class="check-v"><input type="checkbox" name="skills[]" value="Photography"> Photography</label>
      </div>
    </div>

    <div class="field">
      <span class="field-label">SDG Goals this contributes to</span>
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

    <div class="field mb-4">
      <label class="field-label" for="description">Description</label>
      <textarea class="textarea-v" id="description" name="description"
                placeholder="Describe the opportunity, activities, and what volunteers can expect..."><?php echo htmlspecialchars($description); ?></textarea>
    </div>

    <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Publish Opportunity</button>
  </form>

</div>

<?php include 'includes/app-footer.php'; ?>

<?php
$page_title = 'Create Opportunity | VPMS';
$active = 'opportunities';

require 'includes/auth.php';
require 'config/db.php';

if ($_SESSION['role_id'] != 2 && $_SESSION['role_id'] != 6) {
    header('Location: opportunities.php');
    exit;
}

$partnership_id = 0;

// active agreements this organization is part of, so the work can be filed under one
if ($_SESSION['role_id'] == 6) {
    $find = $pdo->prepare(
        'SELECT p.partnership_id, a.name AS asked_by, b.name AS partner_name
         FROM partnership p
         LEFT JOIN organization a ON a.organization_id = p.organization_id
         LEFT JOIN organization b ON b.organization_id = p.partner_id
         WHERE p.status = ?
         ORDER BY a.name'
    );
    $find->execute(array('active'));
} else {
    $find = $pdo->prepare(
        'SELECT p.partnership_id, a.name AS asked_by, b.name AS partner_name
         FROM partnership p
         LEFT JOIN organization a ON a.organization_id = p.organization_id
         LEFT JOIN organization b ON b.organization_id = p.partner_id
         WHERE p.status = ? AND (p.organization_id = ? OR p.partner_id = ?)
         ORDER BY a.name'
    );
    $find->execute(array('active', $_SESSION['organization_id'], $_SESSION['organization_id']));
}

$partnerships = $find->fetchAll();

$errors = array();
$title = '';
$location = '';
$date = '';
$hours = 6;
$spots = 20;
$category = 'Environment';
$description = '';

// "Duplicate" sends the old one here so the form opens filled in
$copy_of = false;
$copied_skills = array();
$copied_goals = array();

if (isset($_GET['copy'])) {
    $find = $pdo->prepare('SELECT * FROM opportunity WHERE opportunity_id = ?');
    $find->execute(array($_GET['copy']));
    $copy_of = $find->fetch();
}

if ($copy_of != false) {
    $title = $copy_of['title'];
    $location = $copy_of['location'];
    $date = $copy_of['opportunity_date'];
    $hours = $copy_of['hours_required'];
    $spots = $copy_of['spots'];
    $category = $copy_of['category'];
    $description = $copy_of['description'];
    $partnership_id = $copy_of['partnership_id'];

    foreach (explode(',', $copy_of['skills']) as $one) {
        $copied_skills[] = trim($one);
    }

    foreach (explode(',', $copy_of['sdg_goals']) as $one) {
        $copied_goals[] = trim($one);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $location = trim($_POST['location']);
    $date = $_POST['date'];
    $hours = $_POST['hours'];
    $spots = $_POST['spots'];
    $category = $_POST['category'];
    $description = trim($_POST['description']);
    $partnership_id = $_POST['partnership_id'];

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
        $copied_skills = $_POST['skills'];
    } else {
        $skills = '';
    }

    if (isset($_POST['sdg'])) {
        $sdg = implode(',', $_POST['sdg']);
        $copied_goals = $_POST['sdg'];
    } else {
        $sdg = '';
    }

    if ($partnership_id > 0) {
        $under = $partnership_id;
    } else {
        $under = null;
    }

    if (count($errors) == 0) {
        $save = $pdo->prepare(
            'INSERT INTO opportunity (title, organization_id, partnership_id, created_by, location,
                                      opportunity_date, hours_required, spots, category, skills,
                                      sdg_goals, description)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );

        $save->execute(array(
            $title,
            $_SESSION['organization_id'],
            $under,
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

  <?php if ($copy_of != false) { ?>
    <div class="notice mb-4">
      <p class="notice-title">Copied from <?php echo htmlspecialchars($copy_of['title']); ?></p>
      <p class="notice-text">
        Nothing has been created yet. Change the date or anything else, then publish it.
      </p>
    </div>
  <?php } ?>

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
      <label class="field-label" for="partnership_id">Run under a partnership</label>
      <select class="select-v" id="partnership_id" name="partnership_id">
        <option value="0">Not part of a partnership</option>
        <?php foreach ($partnerships as $row) { ?>
          <option value="<?php echo $row['partnership_id']; ?>"
            <?php if ($partnership_id == $row['partnership_id']) echo 'selected'; ?>>
            <?php echo htmlspecialchars($row['asked_by']); ?> &amp; <?php echo htmlspecialchars($row['partner_name']); ?>
          </option>
        <?php } ?>
      </select>
      <?php if (count($partnerships) == 0) { ?>
        <p style="margin-top:6px;font-size:12.5px;color:#98a2aa">
          Your organization has no active partnerships yet, so this stays as standalone work.
        </p>
      <?php } ?>
    </div>

    <div class="field">
      <span class="field-label">Skills Required</span>
      <div class="check-grid" style="grid-template-columns:repeat(2,1fr)">
        <label class="check-v"><input type="checkbox" name="skills[]" value="Physical Fitness"
          <?php if (in_array('Physical Fitness', $copied_skills)) echo 'checked'; ?>> Physical Fitness</label>
        <label class="check-v"><input type="checkbox" name="skills[]" value="Teamwork"
          <?php if (in_array('Teamwork', $copied_skills)) echo 'checked'; ?>> Teamwork</label>
        <label class="check-v"><input type="checkbox" name="skills[]" value="Environmental Awareness"
          <?php if (in_array('Environmental Awareness', $copied_skills)) echo 'checked'; ?>> Environmental Awareness</label>
        <label class="check-v"><input type="checkbox" name="skills[]" value="First Aid"
          <?php if (in_array('First Aid', $copied_skills)) echo 'checked'; ?>> First Aid</label>
        <label class="check-v"><input type="checkbox" name="skills[]" value="Driving Licence"
          <?php if (in_array('Driving Licence', $copied_skills)) echo 'checked'; ?>> Driving Licence</label>
        <label class="check-v"><input type="checkbox" name="skills[]" value="Photography"
          <?php if (in_array('Photography', $copied_skills)) echo 'checked'; ?>> Photography</label>
      </div>
    </div>

    <div class="field">
      <span class="field-label">SDG Goals this contributes to</span>
      <div class="check-grid">
        <label class="check-v"><input type="checkbox" name="sdg[]" value="1"
          <?php if (in_array('1', $copied_goals)) echo 'checked'; ?>> SDG 1</label>
        <label class="check-v"><input type="checkbox" name="sdg[]" value="2"
          <?php if (in_array('2', $copied_goals)) echo 'checked'; ?>> SDG 2</label>
        <label class="check-v"><input type="checkbox" name="sdg[]" value="3"
          <?php if (in_array('3', $copied_goals)) echo 'checked'; ?>> SDG 3</label>
        <label class="check-v"><input type="checkbox" name="sdg[]" value="4"
          <?php if (in_array('4', $copied_goals)) echo 'checked'; ?>> SDG 4</label>
        <label class="check-v"><input type="checkbox" name="sdg[]" value="13"
          <?php if (in_array('13', $copied_goals)) echo 'checked'; ?>> SDG 13</label>
        <label class="check-v"><input type="checkbox" name="sdg[]" value="14"
          <?php if (in_array('14', $copied_goals)) echo 'checked'; ?>> SDG 14</label>
        <label class="check-v"><input type="checkbox" name="sdg[]" value="15"
          <?php if (in_array('15', $copied_goals)) echo 'checked'; ?>> SDG 15</label>
        <label class="check-v"><input type="checkbox" name="sdg[]" value="17"
          <?php if (in_array('17', $copied_goals)) echo 'checked'; ?>> SDG 17</label>
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

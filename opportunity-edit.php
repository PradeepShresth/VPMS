<?php
$page_title = 'Edit Opportunity | VPMS';
$active = 'opportunities';

require 'includes/auth.php';
require 'config/db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$find = $pdo->prepare('SELECT * FROM opportunity WHERE opportunity_id = ?');
$find->execute(array($id));
$opportunity = $find->fetch();

if ($opportunity == false) {
    header('Location: opportunities.php');
    exit;
}

if ($opportunity['created_by'] != $_SESSION['user_id'] && $_SESSION['role_id'] != 6) {
    header('Location: opportunity-details.php?id=' . $id);
    exit;
}

$errors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    // the events and applications have to let go of it first or the foreign keys complain
    $clear = $pdo->prepare('UPDATE event SET opportunity_id = NULL WHERE opportunity_id = ?');
    $clear->execute(array($id));

    $clear = $pdo->prepare('DELETE FROM application WHERE opportunity_id = ?');
    $clear->execute(array($id));

    $remove = $pdo->prepare('DELETE FROM opportunity WHERE opportunity_id = ?');
    $remove->execute(array($id));

    header('Location: opportunities.php?deleted=1');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $opportunity['title'] = trim($_POST['title']);
    $opportunity['location'] = trim($_POST['location']);
    $opportunity['opportunity_date'] = $_POST['date'];
    $opportunity['hours_required'] = $_POST['hours'];
    $opportunity['spots'] = $_POST['spots'];
    $opportunity['category'] = $_POST['category'];
    $opportunity['status'] = $_POST['status'];
    $opportunity['description'] = trim($_POST['description']);

    if (isset($_POST['skills'])) {
        $opportunity['skills'] = implode(', ', $_POST['skills']);
    } else {
        $opportunity['skills'] = '';
    }

    if (isset($_POST['sdg'])) {
        $opportunity['sdg_goals'] = implode(',', $_POST['sdg']);
    } else {
        $opportunity['sdg_goals'] = '';
    }

    if ($opportunity['title'] == '') {
        $errors[] = 'Give the opportunity a title.';
    }

    if ($opportunity['opportunity_date'] == '') {
        $errors[] = 'Pick the date it takes place.';
    }

    if (count($errors) == 0) {
        $save = $pdo->prepare(
            'UPDATE opportunity
                SET title = ?, location = ?, opportunity_date = ?, hours_required = ?, spots = ?,
                    category = ?, skills = ?, sdg_goals = ?, description = ?, status = ?
              WHERE opportunity_id = ?'
        );

        $save->execute(array(
            $opportunity['title'],
            $opportunity['location'],
            $opportunity['opportunity_date'],
            $opportunity['hours_required'],
            $opportunity['spots'],
            $opportunity['category'],
            $opportunity['skills'],
            $opportunity['sdg_goals'],
            $opportunity['description'],
            $opportunity['status'],
            $id
        ));

        header('Location: opportunity-details.php?id=' . $id . '&saved=1');
        exit;
    }
}

$chosen_skills = explode(',', $opportunity['skills']);
$chosen_goals = explode(',', $opportunity['sdg_goals']);

foreach ($chosen_skills as $key => $value) {
    $chosen_skills[$key] = trim($value);
}

foreach ($chosen_goals as $key => $value) {
    $chosen_goals[$key] = trim($value);
}

include 'includes/app-header.php';
?>

<div class="col-narrow mx-auto">

  <a class="back-link" href="opportunity-details.php?id=<?php echo $id; ?>">
    <i class="bi bi-arrow-left"></i> Back to Opportunity
  </a>

  <h1 class="page-title mb-4">Edit Opportunity</h1>

  <?php if (count($errors) > 0) { ?>
    <div class="notice mb-4" style="border-left-color:#c8504b;background:#fdf3f2">
      <p class="notice-title" style="color:#c8504b">Please fix the following</p>
      <?php foreach ($errors as $error) { ?>
        <p class="notice-text"><?php echo $error; ?></p>
      <?php } ?>
    </div>
  <?php } ?>

  <form action="opportunity-edit.php?id=<?php echo $id; ?>" method="post">

    <div class="field">
      <label class="field-label" for="title">Opportunity Title</label>
      <input class="input-v" type="text" id="title" name="title"
             value="<?php echo htmlspecialchars($opportunity['title']); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="location">Location</label>
      <input class="input-v" type="text" id="location" name="location"
             value="<?php echo htmlspecialchars($opportunity['location']); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="date">Date</label>
      <input class="input-v" type="date" id="date" name="date"
             value="<?php echo htmlspecialchars($opportunity['opportunity_date']); ?>">
    </div>

    <div class="row g-3">
      <div class="col-sm-6">
        <div class="field">
          <label class="field-label" for="hours">Hours Required</label>
          <input class="input-v" type="number" id="hours" name="hours"
                 value="<?php echo htmlspecialchars($opportunity['hours_required']); ?>">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="field">
          <label class="field-label" for="spots">Volunteer Spots</label>
          <input class="input-v" type="number" id="spots" name="spots"
                 value="<?php echo htmlspecialchars($opportunity['spots']); ?>">
        </div>
      </div>
    </div>

    <div class="field">
      <label class="field-label" for="category">Category</label>
      <select class="select-v" id="category" name="category">
        <option <?php if ($opportunity['category'] == 'Environment') echo 'selected'; ?>>Environment</option>
        <option <?php if ($opportunity['category'] == 'Education') echo 'selected'; ?>>Education</option>
        <option <?php if ($opportunity['category'] == 'Food Security') echo 'selected'; ?>>Food Security</option>
        <option <?php if ($opportunity['category'] == 'Health') echo 'selected'; ?>>Health</option>
        <option <?php if ($opportunity['category'] == 'Advocacy') echo 'selected'; ?>>Advocacy</option>
        <option <?php if ($opportunity['category'] == 'Technology') echo 'selected'; ?>>Technology</option>
        <option <?php if ($opportunity['category'] == 'Community Service') echo 'selected'; ?>>Community Service</option>
      </select>
    </div>

    <div class="field">
      <label class="field-label" for="status">Status</label>
      <select class="select-v" id="status" name="status">
        <option value="open" <?php if ($opportunity['status'] == 'open') echo 'selected'; ?>>Open for applications</option>
        <option value="closed" <?php if ($opportunity['status'] == 'closed') echo 'selected'; ?>>Closed</option>
      </select>
    </div>

    <div class="field">
      <span class="field-label">Skills Required</span>
      <div class="check-grid" style="grid-template-columns:repeat(2,1fr)">
        <label class="check-v"><input type="checkbox" name="skills[]" value="Physical Fitness"
          <?php if (in_array('Physical Fitness', $chosen_skills)) echo 'checked'; ?>> Physical Fitness</label>
        <label class="check-v"><input type="checkbox" name="skills[]" value="Teamwork"
          <?php if (in_array('Teamwork', $chosen_skills)) echo 'checked'; ?>> Teamwork</label>
        <label class="check-v"><input type="checkbox" name="skills[]" value="Environmental Awareness"
          <?php if (in_array('Environmental Awareness', $chosen_skills)) echo 'checked'; ?>> Environmental Awareness</label>
        <label class="check-v"><input type="checkbox" name="skills[]" value="First Aid"
          <?php if (in_array('First Aid', $chosen_skills)) echo 'checked'; ?>> First Aid</label>
        <label class="check-v"><input type="checkbox" name="skills[]" value="Driving Licence"
          <?php if (in_array('Driving Licence', $chosen_skills)) echo 'checked'; ?>> Driving Licence</label>
        <label class="check-v"><input type="checkbox" name="skills[]" value="Photography"
          <?php if (in_array('Photography', $chosen_skills)) echo 'checked'; ?>> Photography</label>
      </div>
    </div>

    <div class="field">
      <span class="field-label">SDG Goals this contributes to</span>
      <div class="check-grid">
        <label class="check-v"><input type="checkbox" name="sdg[]" value="1"
          <?php if (in_array('1', $chosen_goals)) echo 'checked'; ?>> SDG 1</label>
        <label class="check-v"><input type="checkbox" name="sdg[]" value="2"
          <?php if (in_array('2', $chosen_goals)) echo 'checked'; ?>> SDG 2</label>
        <label class="check-v"><input type="checkbox" name="sdg[]" value="3"
          <?php if (in_array('3', $chosen_goals)) echo 'checked'; ?>> SDG 3</label>
        <label class="check-v"><input type="checkbox" name="sdg[]" value="4"
          <?php if (in_array('4', $chosen_goals)) echo 'checked'; ?>> SDG 4</label>
        <label class="check-v"><input type="checkbox" name="sdg[]" value="13"
          <?php if (in_array('13', $chosen_goals)) echo 'checked'; ?>> SDG 13</label>
        <label class="check-v"><input type="checkbox" name="sdg[]" value="14"
          <?php if (in_array('14', $chosen_goals)) echo 'checked'; ?>> SDG 14</label>
        <label class="check-v"><input type="checkbox" name="sdg[]" value="15"
          <?php if (in_array('15', $chosen_goals)) echo 'checked'; ?>> SDG 15</label>
        <label class="check-v"><input type="checkbox" name="sdg[]" value="17"
          <?php if (in_array('17', $chosen_goals)) echo 'checked'; ?>> SDG 17</label>
      </div>
    </div>

    <div class="field mb-4">
      <label class="field-label" for="description">Description</label>
      <textarea class="textarea-v" id="description" name="description"><?php echo htmlspecialchars($opportunity['description']); ?></textarea>
    </div>

    <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Save Changes</button>
  </form>

  <form class="mt-3" action="opportunity-edit.php?id=<?php echo $id; ?>" method="post"
        onsubmit="return confirm('Delete this opportunity and all of its applications?')">
    <input type="hidden" name="delete" value="1">
    <button class="btn-v btn-outline btn-block" type="submit">Delete Opportunity</button>
  </form>

</div>

<?php include 'includes/app-footer.php'; ?>

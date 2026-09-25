<?php
$page_title = 'Edit Organisation | VPMS';
$active = 'organisations';

require 'includes/auth.php';
require 'config/db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$find = $pdo->prepare('SELECT * FROM organisation WHERE organisation_id = ?');
$find->execute(array($id));
$organisation = $find->fetch();

if ($organisation == false) {
    header('Location: organisations.php');
    exit;
}

if ($_SESSION['role_id'] != 6 && $_SESSION['organisation_id'] != $id) {
    header('Location: organisation-details.php?id=' . $id);
    exit;
}

$errors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $organisation['name'] = trim($_POST['name']);
    $organisation['type'] = $_POST['type'];
    $organisation['registration_no'] = trim($_POST['reg']);
    $organisation['country'] = trim($_POST['country']);
    $organisation['state'] = trim($_POST['state']);
    $organisation['city'] = trim($_POST['city']);
    $organisation['address'] = trim($_POST['address']);
    $organisation['website'] = trim($_POST['website']);
    $organisation['description'] = trim($_POST['about']);

    if ($organisation['name'] == '') {
        $errors[] = 'The organisation needs a name.';
    }

    if (count($errors) == 0) {
        $save = $pdo->prepare(
            'UPDATE organisation
                SET name = ?, type = ?, registration_no = ?, country = ?, state = ?,
                    city = ?, address = ?, website = ?, description = ?
              WHERE organisation_id = ?'
        );

        $save->execute(array(
            $organisation['name'],
            $organisation['type'],
            $organisation['registration_no'],
            $organisation['country'],
            $organisation['state'],
            $organisation['city'],
            $organisation['address'],
            $organisation['website'],
            $organisation['description'],
            $id
        ));

        header('Location: organisation-details.php?id=' . $id . '&saved=1');
        exit;
    }
}

include 'includes/app-header.php';
?>

<div class="col-narrow mx-auto">

  <a class="back-link" href="organisation-details.php?id=<?php echo $id; ?>">
    <i class="bi bi-arrow-left"></i> Back to Organisation
  </a>

  <h1 class="page-title mb-4">Edit Organisation</h1>

  <?php if (count($errors) > 0) { ?>
    <div class="notice mb-4" style="border-left-color:#c8504b;background:#fdf3f2">
      <p class="notice-title" style="color:#c8504b">Please fix the following</p>
      <?php foreach ($errors as $error) { ?>
        <p class="notice-text"><?php echo $error; ?></p>
      <?php } ?>
    </div>
  <?php } ?>

  <form action="organisation-edit.php?id=<?php echo $id; ?>" method="post">

    <div class="field">
      <label class="field-label" for="name">Organisation Name</label>
      <input class="input-v" type="text" id="name" name="name"
             value="<?php echo htmlspecialchars($organisation['name']); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="type">Organisation Type</label>
      <select class="select-v" id="type" name="type">
        <option <?php if ($organisation['type'] == 'NGO') echo 'selected'; ?>>NGO</option>
        <option <?php if ($organisation['type'] == 'Corporate') echo 'selected'; ?>>Corporate</option>
        <option <?php if ($organisation['type'] == 'Community Group') echo 'selected'; ?>>Community Group</option>
        <option <?php if ($organisation['type'] == 'Government Body') echo 'selected'; ?>>Government Body</option>
        <option <?php if ($organisation['type'] == 'Sponsor / Donor') echo 'selected'; ?>>Sponsor / Donor</option>
        <option <?php if ($organisation['type'] == 'Educational Institution') echo 'selected'; ?>>Educational Institution</option>
      </select>
    </div>

    <div class="field">
      <label class="field-label" for="reg">Registration Number</label>
      <input class="input-v" type="text" id="reg" name="reg"
             value="<?php echo htmlspecialchars($organisation['registration_no']); ?>">
    </div>

    <div class="row g-3">
      <div class="col-sm-6">
        <div class="field">
          <label class="field-label" for="country">Country</label>
          <input class="input-v" type="text" id="country" name="country"
                 value="<?php echo htmlspecialchars($organisation['country']); ?>">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="field">
          <label class="field-label" for="state">State/Region</label>
          <input class="input-v" type="text" id="state" name="state"
                 value="<?php echo htmlspecialchars($organisation['state']); ?>">
        </div>
      </div>
    </div>

    <div class="field">
      <label class="field-label" for="city">City</label>
      <input class="input-v" type="text" id="city" name="city"
             value="<?php echo htmlspecialchars($organisation['city']); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="address">Address</label>
      <input class="input-v" type="text" id="address" name="address"
             value="<?php echo htmlspecialchars($organisation['address']); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="website">Website URL</label>
      <input class="input-v" type="url" id="website" name="website"
             value="<?php echo htmlspecialchars($organisation['website']); ?>">
    </div>

    <div class="field mb-4">
      <label class="field-label" for="about">Organisation Description</label>
      <textarea class="textarea-v" id="about" name="about"><?php echo htmlspecialchars($organisation['description']); ?></textarea>
    </div>

    <div class="row g-3">
      <div class="col-5">
        <a class="btn-v btn-outline btn-block btn-lg-v" href="organisation-details.php?id=<?php echo $id; ?>">Cancel</a>
      </div>
      <div class="col-7">
        <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Save Changes</button>
      </div>
    </div>
  </form>

</div>

<?php include 'includes/app-footer.php'; ?>

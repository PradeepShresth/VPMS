<?php
$page_title = 'Edit Organization | VPMS';
$active = 'organizations';

require 'includes/auth.php';
require 'config/db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$find = $pdo->prepare('SELECT * FROM organization WHERE organization_id = ?');
$find->execute(array($id));
$organization = $find->fetch();

if ($organization == false) {
    header('Location: organizations.php');
    exit;
}

// admin, or the coordinator of this exact organization. nobody else
if ($_SESSION['role_id'] != 6
 && !($_SESSION['role_id'] == 2 && $_SESSION['organization_id'] == $id)) {
    header('Location: organization-details.php?id=' . $id);
    exit;
}

$errors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $organization['name'] = trim($_POST['name']);
    $organization['type'] = $_POST['type'];
    $organization['registration_no'] = trim($_POST['reg']);
    $organization['country'] = trim($_POST['country']);
    $organization['state'] = trim($_POST['state']);
    $organization['city'] = trim($_POST['city']);
    $organization['address'] = trim($_POST['address']);
    $organization['website'] = trim($_POST['website']);
    $organization['description'] = trim($_POST['about']);

    if ($organization['name'] == '') {
        $errors[] = 'The organization needs a name.';
    }

    if (count($errors) == 0) {
        $save = $pdo->prepare(
            'UPDATE organization
                SET name = ?, type = ?, registration_no = ?, country = ?, state = ?,
                    city = ?, address = ?, website = ?, description = ?
              WHERE organization_id = ?'
        );

        $save->execute(array(
            $organization['name'],
            $organization['type'],
            $organization['registration_no'],
            $organization['country'],
            $organization['state'],
            $organization['city'],
            $organization['address'],
            $organization['website'],
            $organization['description'],
            $id
        ));

        header('Location: organization-details.php?id=' . $id . '&saved=1');
        exit;
    }
}

include 'includes/app-header.php';
?>

<div class="col-narrow mx-auto">

  <a class="back-link" href="organization-details.php?id=<?php echo $id; ?>">
    <i class="bi bi-arrow-left"></i> Back to Organization
  </a>

  <h1 class="page-title mb-4">Edit Organization</h1>

  <?php if (count($errors) > 0) { ?>
    <div class="notice mb-4" style="border-left-color:#c8504b;background:#fdf3f2">
      <p class="notice-title" style="color:#c8504b">Please fix the following</p>
      <?php foreach ($errors as $error) { ?>
        <p class="notice-text"><?php echo $error; ?></p>
      <?php } ?>
    </div>
  <?php } ?>

  <form action="organization-edit.php?id=<?php echo $id; ?>" method="post">

    <div class="field">
      <label class="field-label" for="name">Organization Name</label>
      <input class="input-v" type="text" id="name" name="name"
             value="<?php echo htmlspecialchars($organization['name']); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="type">Organization Type</label>
      <select class="select-v" id="type" name="type">
        <option <?php if ($organization['type'] == 'NGO') echo 'selected'; ?>>NGO</option>
        <option <?php if ($organization['type'] == 'Corporate') echo 'selected'; ?>>Corporate</option>
        <option <?php if ($organization['type'] == 'Community Group') echo 'selected'; ?>>Community Group</option>
        <option <?php if ($organization['type'] == 'Government Body') echo 'selected'; ?>>Government Body</option>
        <option <?php if ($organization['type'] == 'Sponsor / Donor') echo 'selected'; ?>>Sponsor / Donor</option>
        <option <?php if ($organization['type'] == 'Educational Institution') echo 'selected'; ?>>Educational Institution</option>
      </select>
    </div>

    <div class="field">
      <label class="field-label" for="reg">Registration Number</label>
      <input class="input-v" type="text" id="reg" name="reg"
             value="<?php echo htmlspecialchars($organization['registration_no']); ?>">
    </div>

    <div class="row g-3">
      <div class="col-sm-6">
        <div class="field">
          <label class="field-label" for="country">Country</label>
          <input class="input-v" type="text" id="country" name="country"
                 value="<?php echo htmlspecialchars($organization['country']); ?>">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="field">
          <label class="field-label" for="state">State/Region</label>
          <input class="input-v" type="text" id="state" name="state"
                 value="<?php echo htmlspecialchars($organization['state']); ?>">
        </div>
      </div>
    </div>

    <div class="field">
      <label class="field-label" for="city">City</label>
      <input class="input-v" type="text" id="city" name="city"
             value="<?php echo htmlspecialchars($organization['city']); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="address">Address</label>
      <input class="input-v" type="text" id="address" name="address"
             value="<?php echo htmlspecialchars($organization['address']); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="website">Website URL</label>
      <input class="input-v" type="url" id="website" name="website"
             value="<?php echo htmlspecialchars($organization['website']); ?>">
    </div>

    <div class="field mb-4">
      <label class="field-label" for="about">Organization Description</label>
      <textarea class="textarea-v" id="about" name="about"><?php echo htmlspecialchars($organization['description']); ?></textarea>
    </div>

    <div class="row g-3">
      <div class="col-5">
        <a class="btn-v btn-outline btn-block btn-lg-v" href="organization-details.php?id=<?php echo $id; ?>">Cancel</a>
      </div>
      <div class="col-7">
        <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Save Changes</button>
      </div>
    </div>
  </form>

</div>

<?php include 'includes/app-footer.php'; ?>

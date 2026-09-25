<?php
$page_title = 'Apply | VPMS';
$active = 'opportunities';

require 'includes/auth.php';
require 'config/db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$find = $pdo->prepare(
    'SELECT o.*, org.name AS organization, u.organization_name
     FROM opportunity o
     LEFT JOIN organization org ON org.organization_id = o.organization_id
     LEFT JOIN `user` u ON u.user_id = o.created_by
     WHERE o.opportunity_id = ?'
);
$find->execute(array($id));
$opportunity = $find->fetch();

if ($opportunity == false) {
    header('Location: opportunities.php');
    exit;
}

// a volunteer account can apply to anything; everybody else can apply to work
// their own organization is not behind, because they would be managing it instead
$can_apply = true;

// nobody volunteers for work they posted themselves
if ($opportunity['created_by'] == $_SESSION['user_id']) {
    $can_apply = false;
}

if ($_SESSION['role_id'] != 1 && $_SESSION['organization_id'] != ''
 && $opportunity['organization_id'] != '') {

    if ($opportunity['organization_id'] == $_SESSION['organization_id']) {
        $can_apply = false;
    } else {
        $together = $pdo->prepare(
            'SELECT partnership_id FROM partnership
              WHERE status = ?
                AND ((organization_id = ? AND partner_id = ?)
                  OR (organization_id = ? AND partner_id = ?))'
        );
        $together->execute(array('active',
            $_SESSION['organization_id'], $opportunity['organization_id'],
            $opportunity['organization_id'], $_SESSION['organization_id']));

        if ($together->fetch() != false) {
            $can_apply = false;
        }
    }
}

if (!$can_apply) {
    header('Location: opportunity-details.php?id=' . $id);
    exit;
}

$mine = $pdo->prepare('SELECT application_id FROM application WHERE opportunity_id = ? AND user_id = ?');
$mine->execute(array($id, $_SESSION['user_id']));

if ($mine->fetch() != false) {
    header('Location: opportunity-details.php?id=' . $id . '&view=volunteer');
    exit;
}

// the answers the volunteer types in
$errors = array();
$why = '';
$availability = '';
$contact = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $why = trim($_POST['why']);
    $availability = $_POST['time'];
    $contact = trim($_POST['contact']);

    if (isset($_POST['skills'])) {
        $skills = implode(', ', $_POST['skills']);
    } else {
        $skills = '';
    }

    if ($why == '') {
        $errors[] = 'Tell the coordinator why you want to join.';
    }

    if ($contact == '') {
        $errors[] = 'Add an emergency contact.';
    }

    if (count($errors) == 0) {
        $save = $pdo->prepare(
            'INSERT INTO application (opportunity_id, user_id, why, skills, availability, emergency_contact)
             VALUES (?, ?, ?, ?, ?, ?)'
        );

        $save->execute(array($id, $_SESSION['user_id'], $why, $skills, $availability, $contact));

        header('Location: opportunity-applied.php?id=' . $id);
        exit;
    }
}

if ($opportunity['organization'] != '') {
    $posted_by = $opportunity['organization'];
} else {
    $posted_by = $opportunity['organization_name'];
}

include 'includes/app-header.php';
?>

<div class="col-narrow mx-auto">

  <a class="back-link" href="opportunity-details.php?id=<?php echo $id; ?>&view=volunteer">
    <i class="bi bi-arrow-left"></i> Back to Opportunity
  </a>

  <h1 class="page-title">Apply for this Opportunity</h1>
  <p class="page-sub mb-4"><?php echo htmlspecialchars($opportunity['title']); ?></p>

  <div class="card-v card-v-pad mb-4">
    <p class="section-label"><?php echo htmlspecialchars($posted_by); ?></p>
    <p class="mono" style="font-size:12.5px;color:#6d7880">
      <?php echo date('j F Y', strtotime($opportunity['opportunity_date'])); ?>
      &nbsp;·&nbsp; <?php echo $opportunity['hours_required']; ?> hrs
      &nbsp;·&nbsp; <?php echo htmlspecialchars($opportunity['location']); ?>
    </p>
  </div>

  <?php if (count($errors) > 0) { ?>
    <div class="notice mb-4" style="border-left-color:#c8504b;background:#fdf3f2">
      <p class="notice-title" style="color:#c8504b">Please fix the following</p>
      <?php foreach ($errors as $error) { ?>
        <p class="notice-text"><?php echo $error; ?></p>
      <?php } ?>
    </div>
  <?php } ?>

  <form action="opportunity-apply.php?id=<?php echo $id; ?>" method="post">

    <div class="field">
      <label class="field-label" for="why">Why do you want to join?</label>
      <textarea class="textarea-v" id="why" name="why"
                placeholder="Tell the coordinator a little about your interest in this project..."><?php echo htmlspecialchars($why); ?></textarea>
    </div>

    <div class="field">
      <span class="field-label">Relevant skills</span>
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
      <label class="field-label" for="time">Availability</label>
      <select class="select-v" id="time" name="time">
        <option>Full day</option>
        <option>Morning only</option>
        <option>Afternoon only</option>
      </select>
    </div>

    <div class="field">
      <label class="field-label" for="contact">Emergency contact</label>
      <input class="input-v" type="text" id="contact" name="contact" placeholder="Name and phone number"
             value="<?php echo htmlspecialchars($contact); ?>">
    </div>

    <div class="field mb-4">
      <label class="check-v">
        <input type="checkbox" required>
        I confirm the details above are accurate and I can attend on the date shown.
      </label>
    </div>

    <div class="row g-3">
      <div class="col-5">
        <a class="btn-v btn-outline btn-block btn-lg-v" href="opportunity-details.php?id=<?php echo $id; ?>&view=volunteer">Cancel</a>
      </div>
      <div class="col-7">
        <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Submit Application</button>
      </div>
    </div>
  </form>

</div>

<?php include 'includes/app-footer.php'; ?>

<?php
$page_title = 'Post Announcement | VPMS';
$active = 'messages';

require 'includes/auth.php';
require 'config/db.php';

// volunteers can join a discussion but not broadcast to the whole network
if ($_SESSION['role_id'] != 2 && $_SESSION['role_id'] != 6) {
    header('Location: messages.php');
    exit;
}

$errors = array();
$title = '';
$audience = 'Everyone on the platform';
$body = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $audience = $_POST['audience'];
    $body = trim($_POST['body']);

    if (isset($_POST['pin'])) {
        $pinned = 1;
    } else {
        $pinned = 0;
    }

    if ($title == '') {
        $errors[] = 'Give the announcement a title.';
    }

    if ($body == '') {
        $errors[] = 'Write the announcement itself.';
    }

    if (count($errors) == 0) {
        $save = $pdo->prepare(
            'INSERT INTO announcement (title, body, audience, pinned, posted_by) VALUES (?, ?, ?, ?, ?)'
        );
        $save->execute(array($title, $body, $audience, $pinned, $_SESSION['user_id']));

        header('Location: messages.php?posted=1');
        exit;
    }
}

include 'includes/app-header.php';
?>

<div class="col-narrow mx-auto">

  <a class="back-link" href="messages.php"><i class="bi bi-arrow-left"></i> Back to Communications</a>

  <h1 class="page-title">Post Announcement</h1>
  <p class="page-sub mb-4">Broadcast to the network. Announcements appear at the top of Communications.</p>

  <?php if (count($errors) > 0) { ?>
    <div class="notice mb-4" style="border-left-color:#c8504b;background:#fdf3f2">
      <p class="notice-title" style="color:#c8504b">Please fix the following</p>
      <?php foreach ($errors as $error) { ?>
        <p class="notice-text"><?php echo $error; ?></p>
      <?php } ?>
    </div>
  <?php } ?>

  <form action="announcement-new.php" method="post">

    <div class="field">
      <label class="field-label" for="title">Announcement Title</label>
      <input class="input-v" type="text" id="title" name="title"
             placeholder="e.g. New Volunteer Opportunity: Bondi Beach Clean-Up"
             value="<?php echo htmlspecialchars($title); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="audience">Audience</label>
      <select class="select-v" id="audience" name="audience">
        <option <?php if ($audience == 'Everyone on the platform') echo 'selected'; ?>>Everyone on the platform</option>
        <option <?php if ($audience == 'Volunteers only') echo 'selected'; ?>>Volunteers only</option>
        <option <?php if ($audience == 'Organization Coordinators') echo 'selected'; ?>>Organization Coordinators</option>
        <option <?php if ($audience == 'Community Field Officers') echo 'selected'; ?>>Community Field Officers</option>
        <option <?php if ($audience == 'Sponsors / Donors') echo 'selected'; ?>>Sponsors / Donors</option>
        <option <?php if ($audience == 'Partner organizations') echo 'selected'; ?>>Partner organizations</option>
      </select>
    </div>

    <div class="field">
      <label class="field-label" for="body">Message</label>
      <textarea class="textarea-v" id="body" name="body" style="min-height:150px"
                placeholder="Write the announcement..."><?php echo htmlspecialchars($body); ?></textarea>
    </div>

    <div class="field mb-4">
      <label class="check-v mb-2"><input type="checkbox" name="pin"> Pin to the top of the Announcements tab</label>
      <label class="check-v"><input type="checkbox" name="email" checked> Also send as an email notification</label>
    </div>

    <div class="row g-3">
      <div class="col-5">
        <a class="btn-v btn-outline btn-block btn-lg-v" href="messages.php">Cancel</a>
      </div>
      <div class="col-7">
        <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Publish Announcement</button>
      </div>
    </div>
  </form>

</div>

<?php include 'includes/app-footer.php'; ?>

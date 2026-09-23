<?php
$page_title = 'Start a Discussion | VPMS';
$active = 'messages';

require 'includes/auth.php';
require 'config/db.php';

$errors = array();
$topic = 'Volunteer Experience Sharing';
$title = '';
$body = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $topic = $_POST['topic'];
    $title = trim($_POST['title']);
    $body = trim($_POST['body']);

    if ($title == '') {
        $errors[] = 'Give the discussion a title.';
    }

    if ($body == '') {
        $errors[] = 'Write the first message.';
    }

    if (count($errors) == 0) {
        $save = $pdo->prepare(
            'INSERT INTO discussion (topic, title, body, started_by) VALUES (?, ?, ?, ?)'
        );
        $save->execute(array($topic, $title, $body, $_SESSION['user_id']));

        header('Location: discussion-thread.php?id=' . $pdo->lastInsertId() . '&posted=1');
        exit;
    }
}

include 'includes/app-header.php';
?>

<div class="col-narrow mx-auto">

  <a class="back-link" href="messages.php"><i class="bi bi-arrow-left"></i> Back to Communications</a>

  <h1 class="page-title">Start a Discussion</h1>
  <p class="page-sub mb-4">Open a thread for volunteers and partners across the network.</p>

  <?php if (count($errors) > 0) { ?>
    <div class="notice mb-4" style="border-left-color:#c8504b;background:#fdf3f2">
      <p class="notice-title" style="color:#c8504b">Please fix the following</p>
      <?php foreach ($errors as $error) { ?>
        <p class="notice-text"><?php echo $error; ?></p>
      <?php } ?>
    </div>
  <?php } ?>

  <form action="discussion-new.php" method="post">

    <div class="field">
      <label class="field-label" for="topic">Topic</label>
      <select class="select-v" id="topic" name="topic">
        <option <?php if ($topic == 'Volunteer Experience Sharing') echo 'selected'; ?>>Volunteer Experience Sharing</option>
        <option <?php if ($topic == 'Project Coordination') echo 'selected'; ?>>Project Coordination</option>
        <option <?php if ($topic == 'Partnership Ideas') echo 'selected'; ?>>Partnership Ideas</option>
        <option <?php if ($topic == 'Training &amp; Skills') echo 'selected'; ?>>Training &amp; Skills</option>
        <option <?php if ($topic == 'Platform Feedback') echo 'selected'; ?>>Platform Feedback</option>
        <option <?php if ($topic == 'General') echo 'selected'; ?>>General</option>
      </select>
    </div>

    <div class="field">
      <label class="field-label" for="title">Discussion Title</label>
      <input class="input-v" type="text" id="title" name="title"
             placeholder="e.g. Tips for first-time tree planting volunteers"
             value="<?php echo htmlspecialchars($title); ?>">
    </div>

    <div class="field">
      <label class="field-label" for="body">Your Message</label>
      <textarea class="textarea-v" id="body" name="body" style="min-height:150px"
                placeholder="Share your experience, question or suggestion..."><?php echo htmlspecialchars($body); ?></textarea>
    </div>

    <div class="field mb-4">
      <label class="check-v">
        <input type="checkbox" name="notify" checked>
        Notify me when someone replies
      </label>
    </div>

    <div class="row g-3">
      <div class="col-5">
        <a class="btn-v btn-outline btn-block btn-lg-v" href="messages.php">Cancel</a>
      </div>
      <div class="col-7">
        <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Post Discussion</button>
      </div>
    </div>
  </form>

</div>

<?php include 'includes/app-footer.php'; ?>

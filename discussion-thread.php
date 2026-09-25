<?php
$page_title = 'Discussion | VPMS';
$active = 'messages';

require 'includes/auth.php';
require 'config/db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // empty replies just bounce back, no error needed
    $reply = trim($_POST['reply']);

    if ($reply != '') {
        $save = $pdo->prepare('INSERT INTO discussion_reply (discussion_id, user_id, body) VALUES (?, ?, ?)');
        $save->execute(array($id, $_SESSION['user_id'], $reply));
    }

    header('Location: discussion-thread.php?id=' . $id . '&replied=1');
    exit;
}

$find = $pdo->prepare(
    'SELECT d.*, u.full_name, r.name AS role_name
     FROM discussion d
     JOIN `user` u ON u.user_id = d.started_by
     JOIN role r ON r.role_id = u.role_id
     WHERE d.discussion_id = ?'
);
$find->execute(array($id));
$discussion = $find->fetch();

if ($discussion == false) {
    header('Location: messages.php');
    exit;
}

$find = $pdo->prepare(
    'SELECT dr.*, u.full_name, r.name AS role_name
     FROM discussion_reply dr
     JOIN `user` u ON u.user_id = dr.user_id
     JOIN role r ON r.role_id = u.role_id
     WHERE dr.discussion_id = ?
     ORDER BY dr.created_at'
);
$find->execute(array($id));
$replies = $find->fetchAll();

include 'includes/app-header.php';
?>

<div class="col-mid mx-auto">

  <a class="back-link" href="messages.php"><i class="bi bi-arrow-left"></i> Back to Communications</a>

  <?php if (isset($_GET['posted'])) { ?>
    <div class="banner"><i class="bi bi-check-circle-fill"></i>Discussion posted to the network.</div>
  <?php } elseif (isset($_GET['replied'])) { ?>
    <div class="banner"><i class="bi bi-check-circle-fill"></i>Reply posted.</div>
  <?php } ?>

  <div class="card-v card-v-pad mb-3">
    <div class="d-flex align-items-center gap-3 mb-3">
      <span class="chip" style="background:#e4efe8;color:#16663e"><?php echo htmlspecialchars($discussion['topic']); ?></span>
      <span class="mono" style="font-size:12.5px;color:#6d7880">
        <?php echo date('j M Y, H:i', strtotime($discussion['created_at'])); ?>
      </span>
    </div>

    <p class="row-title mb-2"><?php echo htmlspecialchars($discussion['title']); ?></p>

    <p class="mb-3" style="font-size:14.5px;color:#6d7880;line-height:1.7">
      <?php echo nl2br(htmlspecialchars($discussion['body'])); ?>
    </p>

    <div class="d-flex align-items-center gap-2">
      <span class="avatar-circle" style="width:26px;height:26px;font-size:11px">
        <?php echo strtoupper(substr($discussion['full_name'], 0, 1)); ?>
      </span>
      <span style="font-size:13.5px;font-weight:600"><?php echo htmlspecialchars($discussion['full_name']); ?></span>
      <span style="font-size:13px;color:#6d7880">· <?php echo htmlspecialchars($discussion['role_name']); ?></span>
    </div>
  </div>

  <p class="section-label"><?php echo count($replies); ?> replies</p>

  <?php if (count($replies) > 0) { ?>
    <div class="list-card mb-3">
      <?php foreach ($replies as $row) { ?>
        <div class="list-row align-items-start">
          <span class="avatar-circle"><?php echo strtoupper(substr($row['full_name'], 0, 1)); ?></span>
          <span class="flex-grow-1">
            <span class="d-flex flex-wrap align-items-center gap-2 mb-1">
              <span style="font-size:13.5px;font-weight:600"><?php echo htmlspecialchars($row['full_name']); ?></span>
              <span style="font-size:12.5px;color:#6d7880">· <?php echo htmlspecialchars($row['role_name']); ?></span>
              <span class="mono ms-auto" style="font-size:12px;color:#98a2aa">
                <?php echo date('j M H:i', strtotime($row['created_at'])); ?>
              </span>
            </span>
            <span class="d-block" style="font-size:14px;color:#48545e;line-height:1.6">
              <?php echo nl2br(htmlspecialchars($row['body'])); ?>
            </span>
          </span>
        </div>
      <?php } ?>
    </div>
  <?php } ?>

  <div class="card-v card-v-pad">
    <form action="discussion-thread.php?id=<?php echo $id; ?>" method="post">
      <div class="field">
        <textarea class="textarea-v input-soft" name="reply" style="min-height:90px"
                  placeholder="Add your reply..."></textarea>
      </div>
      <div class="text-end">
        <button class="btn-v btn-green" type="submit">Post Reply</button>
      </div>
    </form>
  </div>

</div>

<?php include 'includes/app-footer.php'; ?>

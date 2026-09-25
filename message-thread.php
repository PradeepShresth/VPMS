<?php
$page_title = 'Message | VPMS';
$active = 'messages';

require 'includes/auth.php';
require 'config/db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$find = $pdo->prepare(
    'SELECT t.*, one.full_name AS name_one, two.full_name AS name_two,
            role_one.name AS role_one, role_two.name AS role_two,
            org_one.name AS org_one, org_two.name AS org_two,
            one.organization_name AS typed_one, two.organization_name AS typed_two
     FROM thread t
     JOIN `user` one ON one.user_id = t.user_one
     JOIN `user` two ON two.user_id = t.user_two
     JOIN role role_one ON role_one.role_id = one.role_id
     JOIN role role_two ON role_two.role_id = two.role_id
     LEFT JOIN organization org_one ON org_one.organization_id = one.organization_id
     LEFT JOIN organization org_two ON org_two.organization_id = two.organization_id
     WHERE t.thread_id = ?'
);
$find->execute(array($id));
$thread = $find->fetch();

if ($thread == false) {
    header('Location: messages.php');
    exit;
}

if ($thread['user_one'] != $_SESSION['user_id'] && $thread['user_two'] != $_SESSION['user_id']) {
    header('Location: messages.php');
    exit;
}

if ($thread['user_one'] == $_SESSION['user_id']) {
    $other_name = $thread['name_two'];
    $other_role = $thread['role_two'];
    $other_org = $thread['org_two'];
    $other_typed = $thread['typed_two'];
    $other_id = $thread['user_two'];
} else {
    $other_name = $thread['name_one'];
    $other_role = $thread['role_one'];
    $other_org = $thread['org_one'];
    $other_typed = $thread['typed_one'];
    $other_id = $thread['user_one'];
}

if ($other_org == '') {
    $other_org = $other_typed;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reply = trim($_POST['reply']);

    if ($reply != '') {
        $save = $pdo->prepare('INSERT INTO message (thread_id, sender_id, body) VALUES (?, ?, ?)');
        $save->execute(array($id, $_SESSION['user_id'], $reply));
    }

    header('Location: message-thread.php?id=' . $id . '&sent=1');
    exit;
}

$update = $pdo->prepare('UPDATE message SET is_read = 1 WHERE thread_id = ? AND sender_id = ?');
$update->execute(array($id, $other_id));

$find = $pdo->prepare('SELECT * FROM message WHERE thread_id = ? ORDER BY created_at');
$find->execute(array($id));
$messages = $find->fetchAll();

include 'includes/app-header.php';
?>

<div class="col-mid mx-auto">

  <a class="back-link" href="messages.php"><i class="bi bi-arrow-left"></i> Back to Communications</a>

  <?php if (isset($_GET['sent'])) { ?>
    <div class="banner"><i class="bi bi-check-circle-fill"></i>Message sent.</div>
  <?php } ?>

  <div class="card-v">

    <div class="card-head">
      <span class="avatar-circle"><?php echo strtoupper(substr($other_name, 0, 1)); ?></span>
      <span>
        <span class="d-block" style="font-size:14.5px;font-weight:600"><?php echo htmlspecialchars($other_name); ?></span>
        <span class="d-block" style="font-size:12.5px;color:#6d7880">
          <?php echo htmlspecialchars($other_role); ?>
          <?php if ($other_org != '') { ?> · <?php echo htmlspecialchars($other_org); ?><?php } ?>
        </span>
      </span>
    </div>

    <div style="padding:16px 22px;border-bottom:1px solid #f0ede6">
      <p class="row-title"><?php echo htmlspecialchars($thread['subject']); ?></p>
    </div>

    <div class="card-v-pad">

      <?php foreach ($messages as $row) { ?>
        <div class="bubble-row <?php if ($row['sender_id'] == $_SESSION['user_id']) echo 'mine'; ?>">
          <div>
            <div class="bubble"><?php echo nl2br(htmlspecialchars($row['body'])); ?></div>
            <div class="bubble-time"><?php echo date('j M H:i', strtotime($row['created_at'])); ?></div>
          </div>
        </div>
      <?php } ?>

    </div>

    <form class="composer" action="message-thread.php?id=<?php echo $id; ?>" method="post">
      <input class="input-v input-soft" type="text" name="reply" placeholder="Write a reply...">
      <button class="btn-v btn-green" type="submit"><i class="bi bi-send"></i></button>
    </form>

  </div>

</div>

<?php include 'includes/app-footer.php'; ?>

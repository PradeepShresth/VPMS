<?php
$page_title = 'Communications | VPMS';
$active = 'messages';

require 'includes/auth.php';
require 'config/db.php';

$errors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $to = trim($_POST['to']);
    $subject = trim($_POST['subject']);
    $body = trim($_POST['body']);

    $find = $pdo->prepare('SELECT user_id FROM `user` WHERE email = ? OR full_name = ?');
    $find->execute(array($to, $to));
    $person = $find->fetch();

    if ($person == false) {
        $errors[] = 'Nobody on the platform matches "' . $to . '". Use their full name or email address.';
    } elseif ($person['user_id'] == $_SESSION['user_id']) {
        $errors[] = 'You cannot send a message to yourself.';
    } elseif ($subject == '' || $body == '') {
        $errors[] = 'Fill in both the subject and the message.';
    } else {
        // is there already a conversation with this person about this subject?
        $find = $pdo->prepare(
            'SELECT thread_id FROM thread
              WHERE subject = ?
                AND ((user_one = ? AND user_two = ?) OR (user_one = ? AND user_two = ?))'
        );
        $find->execute(array(
            $subject,
            $_SESSION['user_id'], $person['user_id'],
            $person['user_id'], $_SESSION['user_id']
        ));
        $thread = $find->fetch();

        if ($thread != false) {
            $thread_id = $thread['thread_id'];
        } else {
            $save = $pdo->prepare('INSERT INTO thread (subject, user_one, user_two) VALUES (?, ?, ?)');
            $save->execute(array($subject, $_SESSION['user_id'], $person['user_id']));
            $thread_id = $pdo->lastInsertId();
        }

        $save = $pdo->prepare('INSERT INTO message (thread_id, sender_id, body) VALUES (?, ?, ?)');
        $save->execute(array($thread_id, $_SESSION['user_id'], $body));

        header('Location: message-thread.php?id=' . $thread_id . '&sent=1');
        exit;
    }
}

$find = $pdo->query(
    'SELECT a.*, u.full_name, r.name AS role_name
     FROM announcement a
     JOIN `user` u ON u.user_id = a.posted_by
     JOIN role r ON r.role_id = u.role_id
     ORDER BY a.pinned DESC, a.created_at DESC'
);
$announcements = $find->fetchAll();

$find = $pdo->prepare(
    'SELECT t.thread_id, t.subject, t.user_one, t.user_two,
            one.full_name AS name_one, two.full_name AS name_two,
            (SELECT m.body FROM message m WHERE m.thread_id = t.thread_id
              ORDER BY m.created_at DESC LIMIT 1) AS last_body,
            (SELECT m.created_at FROM message m WHERE m.thread_id = t.thread_id
              ORDER BY m.created_at DESC LIMIT 1) AS last_time,
            (SELECT COUNT(*) FROM message m WHERE m.thread_id = t.thread_id
              AND m.sender_id != ? AND m.is_read = 0) AS unread
     FROM thread t
     JOIN `user` one ON one.user_id = t.user_one
     JOIN `user` two ON two.user_id = t.user_two
     WHERE t.user_one = ? OR t.user_two = ?
     ORDER BY last_time DESC'
);
$find->execute(array($_SESSION['user_id'], $_SESSION['user_id'], $_SESSION['user_id']));
$threads = $find->fetchAll();

$unread = 0;

foreach ($threads as $row) {
    $unread = $unread + $row['unread'];
}

$find = $pdo->query(
    'SELECT d.*, u.full_name, r.name AS role_name,
            (SELECT COUNT(*) FROM discussion_reply dr WHERE dr.discussion_id = d.discussion_id) AS replies
     FROM discussion d
     JOIN `user` u ON u.user_id = d.started_by
     JOIN role r ON r.role_id = u.role_id
     ORDER BY d.created_at DESC'
);
$discussions = $find->fetchAll();

include 'includes/app-header.php';
?>

<?php if (isset($_GET['posted'])) { ?>
  <div class="banner"><i class="bi bi-check-circle-fill"></i>Announcement published to the network.</div>
<?php } elseif (isset($_GET['sent'])) { ?>
  <div class="banner"><i class="bi bi-check-circle-fill"></i>Message sent.</div>
<?php } ?>

<div class="head-row">
  <div class="flex-grow-1">
    <h1 class="page-title">Communications</h1>
    <p class="page-sub">Messages, announcements, and community discussions</p>
  </div>
  <button class="btn-v btn-green" id="composeBtn">+ Compose</button>
</div>

<!-- new message box, hidden until Compose is clicked -->
<div class="card-v card-v-pad mb-4" id="composeBox"
     style="<?php if (count($errors) == 0) echo 'display:none;'; ?>border-color:#16663e">
  <div class="d-flex align-items-center mb-3">
    <span class="section-label mb-0">New Message</span>
    <button class="ms-auto" id="closeBtn" style="border:0;background:none;font-size:17px;cursor:pointer">
      <i class="bi bi-x-lg"></i>
    </button>
  </div>

  <?php if (count($errors) > 0) { ?>
    <div class="notice mb-3" style="border-left-color:#c8504b;background:#fdf3f2">
      <?php foreach ($errors as $error) { ?>
        <p class="notice-text"><?php echo htmlspecialchars($error); ?></p>
      <?php } ?>
    </div>
  <?php } ?>

  <form action="messages.php" method="post">
    <div class="field"><input class="input-v input-soft" type="text" name="to" placeholder="To: (name or email)"></div>
    <div class="field"><input class="input-v input-soft" type="text" name="subject" placeholder="Subject"></div>
    <div class="field mb-3"><textarea class="textarea-v input-soft" name="body" placeholder="Write your message..."></textarea></div>

    <div class="d-flex justify-content-end gap-2">
      <button class="btn-v btn-outline btn-sm-v" type="button" id="cancelBtn">Cancel</button>
      <button class="btn-v btn-green btn-sm-v" type="submit">Send</button>
    </div>
  </form>
</div>

<!-- tabs -->
<div class="tab-row">
  <button class="tab-btn active" onclick="showTab('announcements', this)">Announcements</button>
  <button class="tab-btn" onclick="showTab('messages', this)">
    Messages
    <?php if ($unread > 0) { ?><span class="tab-count"><?php echo $unread; ?></span><?php } ?>
  </button>
  <button class="tab-btn" onclick="showTab('discussions', this)">Discussions</button>
</div>

<div id="announcements">

  <?php if (count($announcements) == 0) { ?>
    <div class="card-v card-v-pad text-center mb-3">
      <p class="row-title mb-2">No announcements yet</p>
      <p style="font-size:14px;color:#6d7880">Anything broadcast to the network shows up here.</p>
    </div>
  <?php } ?>

  <?php foreach ($announcements as $row) { ?>
    <div class="card-v card-v-pad mb-3">
      <div class="d-flex align-items-start gap-3 mb-2">
        <span class="row-title flex-grow-1">
          <?php if ($row['pinned'] == 1) { ?>
            <i class="bi bi-pin-angle-fill" style="color:#e89c1c"></i>
          <?php } ?>
          <?php echo htmlspecialchars($row['title']); ?>
        </span>
        <span class="mono" style="font-size:12.5px;color:#6d7880">
          <?php echo date('j M, H:i', strtotime($row['created_at'])); ?>
        </span>
      </div>
      <p class="mb-3" style="font-size:14px;color:#6d7880;line-height:1.65">
        <?php echo nl2br(htmlspecialchars($row['body'])); ?>
      </p>
      <div class="d-flex align-items-center gap-2">
        <span class="avatar-circle" style="width:26px;height:26px;font-size:11px">
          <?php echo strtoupper(substr($row['full_name'], 0, 1)); ?>
        </span>
        <span style="font-size:13.5px;font-weight:600"><?php echo htmlspecialchars($row['full_name']); ?></span>
        <span style="font-size:13px;color:#6d7880">· <?php echo htmlspecialchars($row['role_name']); ?></span>
        <span class="chip ms-auto" style="font-size:12px"><?php echo htmlspecialchars($row['audience']); ?></span>
      </div>
    </div>
  <?php } ?>

  <?php if ($_SESSION['role_id'] == 2 || $_SESSION['role_id'] == 3 || $_SESSION['role_id'] == 6) { ?>
    <a class="btn-v btn-block card-v" href="announcement-new.php"
       style="padding:16px;background:#fbfaf8;color:#16663e;font-weight:500">
      + New Announcement
    </a>
  <?php } ?>
</div>

<div id="messages" style="display:none">

  <?php if (count($threads) == 0) { ?>
    <div class="card-v card-v-pad text-center">
      <p class="row-title mb-2">No messages yet</p>
      <p style="font-size:14px;color:#6d7880">Use Compose to write to anyone registered on the platform.</p>
    </div>
  <?php } else { ?>

  <div class="list-card">
    <?php foreach ($threads as $row) { ?>

      <?php
      if ($row['user_one'] == $_SESSION['user_id']) {
          $other = $row['name_two'];
      } else {
          $other = $row['name_one'];
      }
      ?>

      <a class="list-row" href="message-thread.php?id=<?php echo $row['thread_id']; ?>">
        <span class="avatar-circle"><?php echo strtoupper(substr($other, 0, 1)); ?></span>
        <span class="flex-grow-1 min-w-0">
          <span class="row-title d-block"><?php echo htmlspecialchars($other); ?></span>
          <span class="row-meta d-block"><?php echo htmlspecialchars($row['subject']); ?></span>
        </span>
        <span class="text-end">
          <span class="mono d-block" style="font-size:12.5px;color:#6d7880">
            <?php echo date('j M H:i', strtotime($row['last_time'])); ?>
          </span>
          <?php if ($row['unread'] > 0) { ?>
            <span class="d-inline-block mt-2" style="width:7px;height:7px;border-radius:50%;background:#e89c1c"></span>
          <?php } ?>
        </span>
      </a>
    <?php } ?>
  </div>

  <?php } ?>
</div>

<div id="discussions" style="display:none">

  <?php if (count($discussions) == 0) { ?>
    <div class="card-v card-v-pad text-center mb-3">
      <p class="row-title mb-2">No discussions yet</p>
      <p style="font-size:14px;color:#6d7880">Start one and the whole network can reply.</p>
    </div>
  <?php } ?>

  <?php foreach ($discussions as $row) { ?>
    <a class="card-v card-v-pad d-block mb-3" href="discussion-thread.php?id=<?php echo $row['discussion_id']; ?>">
      <div class="d-flex align-items-center gap-3 mb-3">
        <span class="chip" style="background:#e4efe8;color:#16663e"><?php echo htmlspecialchars($row['topic']); ?></span>
        <span class="mono" style="font-size:12.5px;color:#6d7880">
          <?php echo date('j M, H:i', strtotime($row['created_at'])); ?>
        </span>
        <span class="mono ms-auto" style="font-size:12.5px;color:#6d7880"><?php echo $row['replies']; ?> replies</span>
      </div>
      <p class="row-title mb-2"><?php echo htmlspecialchars($row['title']); ?></p>
      <p class="mb-3" style="font-size:14px;color:#6d7880;line-height:1.65">
        <?php echo nl2br(htmlspecialchars($row['body'])); ?>
      </p>
      <div class="d-flex align-items-center gap-2">
        <span class="avatar-circle" style="width:26px;height:26px;font-size:11px">
          <?php echo strtoupper(substr($row['full_name'], 0, 1)); ?>
        </span>
        <span style="font-size:13.5px;font-weight:600"><?php echo htmlspecialchars($row['full_name']); ?></span>
        <span style="font-size:13px;color:#6d7880">· <?php echo htmlspecialchars($row['role_name']); ?></span>
      </div>
    </a>
  <?php } ?>

  <a class="btn-v btn-block card-v" href="discussion-new.php"
     style="padding:16px;background:#fbfaf8;color:#16663e;font-weight:500">
    + Start a Discussion
  </a>
</div>

<script>
// switch between the three tabs
function showTab(name, button) {
  document.getElementById('announcements').style.display = 'none';
  document.getElementById('messages').style.display = 'none';
  document.getElementById('discussions').style.display = 'none';
  document.getElementById(name).style.display = 'block';

  var buttons = document.querySelectorAll('.tab-btn');
  for (var i = 0; i < buttons.length; i++) {
    buttons[i].classList.remove('active');
  }
  button.classList.add('active');
}

// show and hide the compose box
var composeBox = document.getElementById('composeBox');
document.getElementById('composeBtn').onclick = function () { composeBox.style.display = 'block'; };
document.getElementById('closeBtn').onclick = function () { composeBox.style.display = 'none'; };
document.getElementById('cancelBtn').onclick = function () { composeBox.style.display = 'none'; };
</script>

<?php include 'includes/app-footer.php'; ?>

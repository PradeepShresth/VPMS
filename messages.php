<?php
$page_title = 'Communications | VPMS';
$active = 'messages';
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
<div class="card-v card-v-pad mb-4" id="composeBox" style="display:none;border-color:#16663e">
  <div class="d-flex align-items-center mb-3">
    <span class="section-label mb-0">New Message</span>
    <button class="ms-auto" id="closeBtn" style="border:0;background:none;font-size:17px;cursor:pointer">
      <i class="bi bi-x-lg"></i>
    </button>
  </div>

  <div class="field"><input class="input-v input-soft" type="text" placeholder="To: (name or email)"></div>
  <div class="field"><input class="input-v input-soft" type="text" placeholder="Subject"></div>
  <div class="field mb-3"><textarea class="textarea-v input-soft" placeholder="Write your message..."></textarea></div>

  <div class="d-flex justify-content-end gap-2">
    <button class="btn-v btn-outline btn-sm-v" id="cancelBtn">Cancel</button>
    <a class="btn-v btn-green btn-sm-v" href="messages.php?sent=1">Send</a>
  </div>
</div>

<!-- tabs -->
<div class="tab-row">
  <button class="tab-btn active" onclick="showTab('announcements', this)">Announcements</button>
  <button class="tab-btn" onclick="showTab('messages', this)">Messages <span class="tab-count">1</span></button>
  <button class="tab-btn" onclick="showTab('discussions', this)">Discussions</button>
</div>

<div id="announcements">
  <div class="card-v card-v-pad mb-3">
    <div class="d-flex align-items-start gap-3 mb-2">
      <span class="row-title flex-grow-1">New Volunteer Opportunity: Bondi Beach Clean-Up</span>
      <span class="mono" style="font-size:12.5px;color:#6d7880">Yesterday</span>
    </div>
    <p class="mb-3" style="font-size:14px;color:#6d7880;line-height:1.65">
      Green Future NGO has just published our bondi beach cleanup event. 18 spots remain.
      Apply before 10 September — priority given to returning volunteers.
    </p>
    <div class="d-flex align-items-center gap-2">
      <span class="avatar-circle" style="width:26px;height:26px;font-size:11px">P</span>
      <span style="font-size:13.5px;font-weight:600">Prasidha Neupane</span>
      <span style="font-size:13px;color:#6d7880">· NGO Coordinator</span>
    </div>
  </div>

  <a class="btn-v btn-block card-v" href="announcement-new.php"
     style="padding:16px;background:#fbfaf8;color:#16663e;font-weight:500">
    + New Announcement
  </a>
</div>

<div id="messages" style="display:none">
  <div class="list-card">
    <a class="list-row" href="message-thread.php">
      <span class="avatar-circle">R</span>
      <span class="flex-grow-1">
        <span class="row-title d-block">Ram Dhakal</span>
        <span class="row-meta d-block">Re: Volunteer application for Beach Clean-Up — approved!</span>
      </span>
      <span class="text-end">
        <span class="mono d-block" style="font-size:12.5px;color:#6d7880">10:24 AM</span>
        <span class="d-inline-block mt-2" style="width:7px;height:7px;border-radius:50%;background:#e89c1c"></span>
      </span>
    </a>
    <div class="list-row" style="min-height:40px"></div>
  </div>
</div>

<div id="discussions" style="display:none">
  <a class="card-v card-v-pad d-block mb-3" href="discussion-thread.php">
    <div class="d-flex align-items-center gap-3 mb-3">
      <span class="chip" style="background:#e4efe8;color:#16663e">Volunteer Experience Sharing</span>
      <span class="mono" style="font-size:12.5px;color:#6d7880">1 hour ago</span>
    </div>
    <p class="mb-3" style="font-size:14px;color:#6d7880;line-height:1.65">
      "Just completed my second tree planting session in Kogarah. The team was fantastic and
      the impact data in the app really motivates you. Highly recommend to all first-timers!"
    </p>
    <div class="d-flex align-items-center gap-2">
      <span class="avatar-circle" style="width:26px;height:26px;font-size:11px">A</span>
      <span style="font-size:13.5px;font-weight:600">Aruna</span>
      <span style="font-size:13px;color:#6d7880">· Volunteer</span>
    </div>
  </a>

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

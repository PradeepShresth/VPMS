<?php
$page_title = 'Discussion | VPMS';
$active = 'messages';
include 'includes/app-header.php';
?>

<div class="col-mid mx-auto">

  <a class="back-link" href="messages.php"><i class="bi bi-arrow-left"></i> Back to Communications</a>

  <?php if (isset($_GET['title'])) { ?>
    <div class="banner"><i class="bi bi-check-circle-fill"></i>Discussion posted to the network.</div>
  <?php } elseif (isset($_GET['replied'])) { ?>
    <div class="banner"><i class="bi bi-check-circle-fill"></i>Reply posted.</div>
  <?php } ?>

  <div class="card-v card-v-pad mb-3">
    <div class="d-flex align-items-center gap-3 mb-3">
      <span class="chip" style="background:#e4efe8;color:#16663e">Volunteer Experience Sharing</span>
      <span class="mono" style="font-size:12.5px;color:#6d7880">1 hour ago</span>
    </div>

    <p class="mb-3" style="font-size:14.5px;color:#6d7880;line-height:1.7">
      "Just completed my second tree planting session in Kogarah. The team was fantastic and the
      impact data in the app really motivates you. Highly recommend to all first-timers!"
    </p>

    <div class="d-flex align-items-center gap-2">
      <span class="avatar-circle" style="width:26px;height:26px;font-size:11px">A</span>
      <span style="font-size:13.5px;font-weight:600">Aruna</span>
      <span style="font-size:13px;color:#6d7880">· Volunteer</span>
    </div>
  </div>

  <p class="section-label">3 replies</p>

  <div class="list-card mb-3">

    <div class="list-row align-items-start">
      <span class="avatar-circle">R</span>
      <span class="flex-grow-1">
        <span class="d-flex flex-wrap align-items-center gap-2 mb-1">
          <span style="font-size:13.5px;font-weight:600">Raj Kumar</span>
          <span style="font-size:12.5px;color:#6d7880">· Volunteer</span>
          <span class="mono ms-auto" style="font-size:12px;color:#98a2aa">48 minutes ago</span>
        </span>
        <span class="d-block" style="font-size:14px;color:#48545e;line-height:1.6">
          Completely agree. Seeing the verified hours appear on your profile the same evening makes
          it feel worthwhile.
        </span>
      </span>
    </div>

    <div class="list-row align-items-start">
      <span class="avatar-circle">P</span>
      <span class="flex-grow-1">
        <span class="d-flex flex-wrap align-items-center gap-2 mb-1">
          <span style="font-size:13.5px;font-weight:600">Prasidha Neupane</span>
          <span style="font-size:12.5px;color:#6d7880">· NGO Coordinator</span>
          <span class="mono ms-auto" style="font-size:12px;color:#98a2aa">25 minutes ago</span>
        </span>
        <span class="d-block" style="font-size:14px;color:#48545e;line-height:1.6">
          Thanks Aruna! We are running the same session in Rockdale next month — I will post the
          opportunity here first so this group gets priority.
        </span>
      </span>
    </div>

    <div class="list-row align-items-start">
      <span class="avatar-circle">A</span>
      <span class="flex-grow-1">
        <span class="d-flex flex-wrap align-items-center gap-2 mb-1">
          <span style="font-size:13.5px;font-weight:600">Anil</span>
          <span style="font-size:12.5px;color:#6d7880">· Field Officer</span>
          <span class="mono ms-auto" style="font-size:12px;color:#98a2aa">9 minutes ago</span>
        </span>
        <span class="d-block" style="font-size:14px;color:#48545e;line-height:1.6">
          Reminder for first-timers: bring closed shoes and a water bottle. We supply gloves and
          tools on site.
        </span>
      </span>
    </div>

  </div>

  <div class="card-v card-v-pad">
    <form action="discussion-thread.php" method="get">
      <input type="hidden" name="replied" value="1">
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

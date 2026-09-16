<?php
$page_title = 'Start a Discussion | VPMS';
$active = 'messages';
include 'includes/app-header.php';
?>

<div class="col-narrow mx-auto">

  <a class="back-link" href="messages.php"><i class="bi bi-arrow-left"></i> Back to Communications</a>

  <h1 class="page-title">Start a Discussion</h1>
  <p class="page-sub mb-4">Open a thread for volunteers and partners across the network.</p>

  <form action="discussion-thread.php" method="get">

    <div class="field">
      <label class="field-label" for="topic">Topic</label>
      <select class="select-v" id="topic" name="topic">
        <option>Volunteer Experience Sharing</option>
        <option>Project Coordination</option>
        <option>Partnership Ideas</option>
        <option>Training &amp; Skills</option>
        <option>Platform Feedback</option>
        <option>General</option>
      </select>
    </div>

    <div class="field">
      <label class="field-label" for="title">Discussion Title</label>
      <input class="input-v" type="text" id="title" name="title"
             placeholder="e.g. Tips for first-time tree planting volunteers">
    </div>

    <div class="field">
      <label class="field-label" for="body">Your Message</label>
      <textarea class="textarea-v" id="body" name="body" style="min-height:150px"
                placeholder="Share your experience, question or suggestion..."></textarea>
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

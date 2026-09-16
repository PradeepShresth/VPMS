<?php
$page_title = 'Post Announcement | VPMS';
$active = 'messages';
include 'includes/app-header.php';
?>

<div class="col-narrow mx-auto">

  <a class="back-link" href="messages.php"><i class="bi bi-arrow-left"></i> Back to Communications</a>

  <h1 class="page-title">Post Announcement</h1>
  <p class="page-sub mb-4">Broadcast to the network. Announcements appear at the top of Communications.</p>

  <form action="messages.php" method="get">
    <input type="hidden" name="posted" value="1">

    <div class="field">
      <label class="field-label" for="title">Announcement Title</label>
      <input class="input-v" type="text" id="title" name="title"
             placeholder="e.g. New Volunteer Opportunity: Bondi Beach Clean-Up">
    </div>

    <div class="field">
      <label class="field-label" for="audience">Audience</label>
      <select class="select-v" id="audience" name="audience">
        <option>Everyone on the platform</option>
        <option>Volunteers only</option>
        <option>NGO Coordinators</option>
        <option>Corporate CSR Managers</option>
        <option>Community Field Officers</option>
        <option>Sponsors / Donors</option>
        <option>Partner organisations</option>
      </select>
    </div>

    <div class="field">
      <label class="field-label" for="body">Message</label>
      <textarea class="textarea-v" id="body" name="body" style="min-height:150px"
                placeholder="Write the announcement..."></textarea>
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

<?php
$page_title = 'Create Event | VPMS';
$active = 'events';
include 'includes/app-header.php';
?>

<div class="col-narrow mx-auto">

  <a class="back-link" href="events.php"><i class="bi bi-arrow-left"></i> Back</a>

  <h1 class="page-title mb-4">Create Event</h1>

  <form action="event-created.php" method="get">

    <div class="field">
      <label class="field-label" for="title">Event Title</label>
      <input class="input-v" type="text" id="title" name="title" placeholder="Gotong-Royong Chow Kit">
    </div>

    <div class="field">
      <label class="field-label" for="location">Location</label>
      <input class="input-v" type="text" id="location" name="location" placeholder="Chow Kit, Kuala Lumpur">
    </div>

    <div class="field">
      <label class="field-label" for="date">Date</label>
      <input class="input-v" type="date" id="date" name="date">
    </div>

    <div class="field">
      <label class="field-label" for="time">Time</label>
      <input class="input-v" type="time" id="time" name="time">
    </div>

    <div class="field">
      <label class="field-label" for="needed">Volunteers Needed</label>
      <input class="input-v" type="number" id="needed" name="needed" value="25">
    </div>

    <div class="field mb-4">
      <label class="field-label" for="category">Category</label>
      <select class="select-v" id="category" name="category">
        <option>Community Service</option>
        <option>Environment</option>
        <option>Education</option>
        <option>Food Security</option>
        <option>Health</option>
        <option>Disaster Relief</option>
        <option>Technology</option>
      </select>
    </div>

    <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Create Event</button>
  </form>

</div>

<?php include 'includes/app-footer.php'; ?>

<?php
$page_title = 'Create Opportunity | VPMS';
$active = 'opportunities';
include 'includes/app-header.php';
?>

<div class="col-narrow mx-auto">

  <a class="back-link" href="opportunities.php"><i class="bi bi-arrow-left"></i> Back to Opportunities</a>

  <h1 class="page-title mb-4">Create Opportunity</h1>

  <form action="opportunity-created.php" method="get">

    <div class="field">
      <label class="field-label" for="title">Opportunity Title</label>
      <input class="input-v" type="text" id="title" name="title" placeholder="Beach Clean-Up Drive">
    </div>

    <div class="field">
      <label class="field-label" for="location">Location</label>
      <input class="input-v" type="text" id="location" name="location" placeholder="Port Dickson, Negeri Sembilan">
    </div>

    <div class="field">
      <label class="field-label" for="date">Date</label>
      <input class="input-v" type="date" id="date" name="date">
    </div>

    <div class="field">
      <label class="field-label" for="spots">Volunteer Spots</label>
      <input class="input-v" type="number" id="spots" name="spots" value="20">
    </div>

    <div class="field">
      <label class="field-label" for="category">Category</label>
      <select class="select-v" id="category" name="category">
        <option>Environment</option>
        <option>Education</option>
        <option>Food Security</option>
        <option>Health</option>
        <option>Advocacy</option>
        <option>Technology</option>
        <option>Community Service</option>
      </select>
    </div>

    <div class="field mb-4">
      <label class="field-label" for="description">Description</label>
      <textarea class="textarea-v" id="description" name="description"
                placeholder="Describe the opportunity, activities, and what volunteers can expect..."></textarea>
    </div>

    <button class="btn-v btn-green btn-block btn-lg-v" type="submit">Publish Opportunity</button>
  </form>

</div>

<?php include 'includes/app-footer.php'; ?>

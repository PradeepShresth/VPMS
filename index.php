<?php
$page_title = 'VPMS | Volunteer Partnership Management System';
$nav_simple = true;

require 'config/db.php';

$count = $pdo->prepare('SELECT COUNT(*) FROM `user` WHERE role_id = ? AND status = ?');
$count->execute(array(1, 'active'));
$volunteers = $count->fetchColumn();

$count = $pdo->prepare('SELECT COUNT(*) FROM organization WHERE status = ?');
$count->execute(array('verified'));
$organizations = $count->fetchColumn();

$count = $pdo->prepare('SELECT COALESCE(SUM(hours_logged), 0) FROM event_volunteer WHERE attended = ?');
$count->execute(array(1));
$hours = $count->fetchColumn();

$count = $pdo->query('SELECT COUNT(*) FROM event');
$events = $count->fetchColumn();

include 'includes/header.php';
?>

<!-- hero -->
<header class="hero">
  <div class="wrap">
    <div class="row gx-5 gy-5 align-items-center">

      <div class="col-lg-7">
        <span class="sdg-badge"><span class="dot"></span>Supporting UN Sustainable Development Goal 17</span>

        <h1 class="hero-title">
          Volunteer Today<br>
          <span class="gold">Transform Tomorrow</span>
        </h1>

        <p class="hero-lead">
          A centralised platform connecting NGOs, corporations, volunteers, and sponsors
          to create measurable community impact – together.
        </p>

        <div class="d-flex flex-wrap gap-3">
          <a class="btn btn-amber btn-big" href="home.php">Get Started</a>
        </div>
      </div>

      <!-- student project card -->
      <div class="col-lg-5">
        <div class="project-card">
          <div class="d-flex align-items-start gap-3 mb-4">
            <img src="assets/img/win.png" alt="WIN Education" class="win-logo">
            <div>
              <p class="project-kicker mb-1">This is a&nbsp; student project</p>
              <p class="project-note mb-0">
                A collaborative project by students from Wentworth Institute of Higher Education.
              </p>
            </div>
          </div>

          <h2 class="team-heading">Team Members</h2>

          <ul class="team-list list-unstyled mb-0">
            <li class="team-row d-flex align-items-center gap-3">
              <img class="avatar" src="assets/img/team/pradeep.png" alt="Pradeep Shrestha">
              <span>
                <span class="team-name d-block">Pradeep Shrestha</span>
                <span class="team-id d-block">988584</span>
              </span>
            </li>
            <li class="team-row d-flex align-items-center gap-3">
              <img class="avatar" src="assets/img/team/anil.jpeg" alt="Anil Dangi">
              <span>
                <span class="team-name d-block">Anil Dangi</span>
                <span class="team-id d-block">983642</span>
              </span>
            </li>
            <li class="team-row d-flex align-items-center gap-3">
              <img class="avatar" src="assets/img/team/aruna.jpeg" alt="Aruna Tamang">
              <span>
                <span class="team-name d-block">Aruna Tamang</span>
                <span class="team-id d-block">987749</span>
              </span>
            </li>
          </ul>
        </div>
      </div>

    </div>
  </div>
</header>

<!-- stats -->
<section class="stats" id="impact">
  <div class="stats-inner">
    <div class="row g-0">
      <div class="col-6 col-md-3 stat">
        <span class="stat-value"><?php echo $volunteers; ?></span>
        <span class="stat-label">Active Volunteers</span>
      </div>
      <div class="col-6 col-md-3 stat">
        <span class="stat-value"><?php echo $organizations; ?></span>
        <span class="stat-label">Partner Organizations</span>
      </div>
      <div class="col-6 col-md-3 stat">
        <span class="stat-value"><?php echo $hours; ?></span>
        <span class="stat-label">Volunteer Hours</span>
      </div>
      <div class="col-6 col-md-3 stat">
        <span class="stat-value"><?php echo $events; ?></span>
        <span class="stat-label">Events Run</span>
      </div>
    </div>
  </div>
</section>

<!-- modules -->
<section class="section section-paper" id="platform">
  <div class="wrap-narrow">
    <p class="eyebrow">PLATFORM MODULES</p>
    <h2 class="section-title mb-5">
      Everything your partnership<br class="d-none d-md-inline"> ecosystem needs
    </h2>

    <div class="row g-4">
      <div class="col-md-6 col-lg-4">
        <div class="module-card h-100">
          <span class="module-icon"><i class="bi bi-diamond"></i></span>
          <h3 class="module-title">Volunteer Opportunities</h3>
          <p class="module-text mb-0">Browse and apply for community projects matched to your skills and schedule.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="module-card h-100">
          <span class="module-icon"><i class="bi bi-record-circle-fill"></i></span>
          <h3 class="module-title">Partnership Management</h3>
          <p class="module-text mb-0">Forge lasting alliances between NGOs, corporations, and government bodies.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="module-card h-100">
          <span class="module-icon"><i class="bi bi-clock"></i></span>
          <h3 class="module-title">Event &amp; Attendance</h3>
          <p class="module-text mb-0">Coordinate events, track attendance, and validate volunteer hours in real time.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="module-card h-100">
          <span class="module-icon"><i class="bi bi-circle-half"></i></span>
          <h3 class="module-title">SDG 17 Impact Dashboard</h3>
          <p class="module-text mb-0">Measure and visualise your organization’s contribution to the Global Goals.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="module-card h-100">
          <span class="module-icon"><i class="bi bi-file-earmark-text"></i></span>
          <h3 class="module-title">Reporting &amp; Analytics</h3>
          <p class="module-text mb-0">Generate certified impact reports for partners, donors, and regulators.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="module-card h-100">
          <span class="module-icon"><i class="bi bi-square-fill"></i></span>
          <h3 class="module-title">Communication Hub</h3>
          <p class="module-text mb-0">Announce, discuss, and collaborate across your entire partner network.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- roles -->
<section class="section section-sand" id="roles">
  <div class="wrap-narrow text-center">
    <p class="eyebrow">BUILT FOR EVERYONE</p>
    <h2 class="section-title mb-5">Five roles, one platform</h2>

    <div class="row g-4 text-start">
      <div class="col-md-6 col-lg-4">
        <div class="role-card h-100">
          <span class="role-label">Role 01</span>
          <h3 class="role-title">System Administrator</h3>
          <p class="role-text mb-0">Manage users, approvals, and platform security.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="role-card h-100">
          <span class="role-label">Role 02</span>
          <h3 class="role-title">Organization Coordinator</h3>
          <p class="role-text mb-0">Run your organization, its opportunities and its volunteers.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="role-card h-100">
          <span class="role-label">Role 03</span>
          <h3 class="role-title">Volunteer</h3>
          <p class="role-text mb-0">Browse, apply, and track your impact.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="role-card h-100">
          <span class="role-label">Role 04</span>
          <h3 class="role-title">Community Field Officer</h3>
          <p class="role-text mb-0">Validate attendance and verify hours on-site.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="role-card h-100">
          <span class="role-label">Role 05</span>
          <h3 class="role-title">Sponsor / Donor</h3>
          <p class="role-text mb-0">Track funded projects and partnership outcomes.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- call to action -->
<section class="cta text-center">
  <div class="wrap-narrow">
    <h2 class="cta-title">Ready to make your impact?</h2>
    <p class="cta-text">Join <?php echo $organizations; ?> organizations already building meaningful partnerships on VPMS.</p>
  </div>
</section>

<?php include 'includes/footer.php'; ?>

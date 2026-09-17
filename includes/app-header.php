<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Who is signed in. Pages that do not check for a login yet fall back to the
// demo administrator, so the prototype still renders.
$user_name = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : 'Pradeep Shrestha';
$user_role = isset($_SESSION['role_name']) ? $_SESSION['role_name'] : 'System Administrator';

// first letter of the first two words, e.g. "Aruna Tamang" -> "AT"
$name_parts = explode(' ', $user_name);
$user_initials = strtoupper(substr($name_parts[0], 0, 1));
if (isset($name_parts[1])) {
    $user_initials .= strtoupper(substr($name_parts[1], 0, 1));
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $page_title; ?></title>

  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,400;9..144,500&family=IBM+Plex+Mono&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="assets/css/app.css" rel="stylesheet">
</head>
<body>

<button class="menu-btn" id="menuBtn"><i class="bi bi-list"></i></button>
<div class="backdrop" id="backdrop"></div>

<div class="sidebar" id="sidebar">

  <div class="sidebar-top">
    <span class="logo">V</span>
    <span>
      <span class="brand-name">VPMS</span>
      <span class="brand-sub">SDG 17 Platform</span>
    </span>
  </div>

  <div class="sidebar-menu">
    <a class="side-link <?php if ($active == 'dashboard') echo 'active'; ?>" href="dashboard.php">
      <i class="bi bi-grid-1x2"></i> Dashboard
    </a>
    <a class="side-link <?php if ($active == 'opportunities') echo 'active'; ?>" href="opportunities.php">
      <i class="bi bi-diamond"></i> Opportunities
    </a>
    <a class="side-link <?php if ($active == 'events') echo 'active'; ?>" href="events.php">
      <i class="bi bi-clock-history"></i> Events
    </a>
    <a class="side-link <?php if ($active == 'partnerships') echo 'active'; ?>" href="partnerships.php">
      <i class="bi bi-record-circle"></i> Partnerships
    </a>
    <a class="side-link <?php if ($active == 'organisations') echo 'active'; ?>" href="organisations.php">
      <i class="bi bi-book"></i> Organisations
    </a>
    <a class="side-link <?php if ($active == 'messages') echo 'active'; ?>" href="messages.php">
      <i class="bi bi-chat-square-fill"></i> Messages
    </a>
    <a class="side-link <?php if ($active == 'reports') echo 'active'; ?>" href="reports.php">
      <i class="bi bi-file-earmark"></i> Reports
    </a>
    <a class="side-link <?php if ($active == 'impact') echo 'active'; ?>" href="impact.php">
      <i class="bi bi-circle-half"></i> SDG 17 Impact
    </a>
  </div>

  <div class="sidebar-bottom">
    <a class="side-user <?php if ($active == 'profile') echo 'active'; ?>" href="profile.php">
      <span class="initials"><?php echo $user_initials; ?></span>
      <span>
        <span class="user-name"><?php echo htmlspecialchars($user_name); ?></span>
        <span class="user-role"><?php echo htmlspecialchars($user_role); ?></span>
      </span>
    </a>
    <a class="side-out" href="logout.php"><i class="bi bi-arrow-right"></i> Sign Out</a>
  </div>

</div>

<div class="content">

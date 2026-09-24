<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $page_title; ?></title>

  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,300;9..144,400;9..144,500;9..144,600&family=IBM+Plex+Mono&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="assets/css/style.css?v=2" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg site-nav">
  <div class="wrap d-flex align-items-center w-100">

    <a class="navbar-brand d-flex align-items-center gap-2 me-auto" href="index.php">
      <span class="logo">V</span>
      <span class="brand-name">VPMS</span>
      <span class="brand-sub d-none d-md-inline">Volunteer Partnership Management System</span>
    </a>

    <?php if (!isset($nav_simple)) { ?>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse flex-grow-0" id="menu">
        <ul class="navbar-nav align-items-lg-center">
          <li class="nav-item"><a class="nav-link" href="login.php">Sign In</a></li>
          <li class="nav-item ms-lg-4 mt-2 mt-lg-0">
            <a class="btn btn-amber btn-sm" href="register.php">Get Started</a>
          </li>
        </ul>
      </div>
    <?php } ?>

  </div>
</nav>

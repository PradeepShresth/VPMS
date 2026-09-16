<?php
// Apache shows this page for any address that does not exist, so all the links
// below start with /VPMS/ instead of being relative.
http_response_code(404);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Page Not Found | VPMS</title>

  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,400;9..144,500&family=IBM+Plex+Mono&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="/VPMS/assets/css/app.css" rel="stylesheet">
</head>
<body>

<div class="error-page">
  <div style="max-width:560px">

    <a class="d-inline-flex align-items-center gap-2 mb-4" href="/VPMS/index.php">
      <span class="logo">V</span>
      <span style="font-size:16px;font-weight:700">VPMS</span>
    </a>

    <p class="error-code">404</p>
    <h1 class="error-title">We couldn't find that page</h1>
    <p class="error-text">
      The page may have been moved, renamed, or it never existed. Check the address,
      or head back to somewhere familiar.
    </p>

    <p class="mono mb-4" style="font-size:12.5px;color:#98a2aa;word-break:break-all">
      <?php if (isset($_SERVER['REQUEST_URI'])) echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>
    </p>

    <div class="d-flex flex-wrap justify-content-center gap-2">
      <a class="btn-v btn-green" href="/VPMS/index.php">Back to Home</a>
      <a class="btn-v btn-soft" href="/VPMS/dashboard.php">Go to Dashboard</a>
    </div>

    <div class="error-links">
      Looking for
      <a class="link-green" href="/VPMS/opportunities.php">Opportunities</a>,
      <a class="link-green" href="/VPMS/events.php">Events</a>,
      <a class="link-green" href="/VPMS/partnerships.php">Partnerships</a> or
      <a class="link-green" href="/VPMS/login.php">Sign In</a>?
    </div>

  </div>
</div>

</body>
</html>

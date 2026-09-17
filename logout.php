<?php
// Sign out: empty the session, throw the cookie away, go back to the landing page.

session_start();
$_SESSION = array();

if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

session_destroy();

header('Location: index.php');
exit;

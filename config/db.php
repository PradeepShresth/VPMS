<?php

// $host = 'localhost';
// $dbname = 'vpms';
// $username = 'root';
// $password = '';

$host = 'localhost';
$dbname = 'db-2026s2g';
$username = 'db-2026s2g';
$password = 'Y6Qvn7psW2ollSZ39isH';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

    // show errors as exceptions instead of failing silently
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // fetch rows as associative arrays, so $row['name'] works
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

<?php
// include functions file
require_once __DIR__ . '/../functions.php';

try {
  // Unset the session variables, close the database connection, and delete the cookie if set
  if (isset(
    $_SESSION['authenticated'],
    $_SESSION['user_id'],
    $_SESSION['username'],
    $_SESSION['role']
  )) {
    session_unset();
    unset($db);
    setcookie('remember_me', '', time() - 3600, '/');
  }
  // User signed out successfully
  redirect('index.php'); // Redirect to index page
  exit();
} catch (Exception $e) {
  $_SESSION['errorsession'] = $e->getMessage();
  redirect('index.php'); // Redirect to index page
  exit();
}

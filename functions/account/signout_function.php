<?php
// include functions file
require_once __DIR__ . '/../functions.php';

  try {
    // Unset the session variables
    session_unset();
    // Destroy the session
    session_destroy();
    // Close the database connection
    unset($db);
    // User signed out successfully
    redirect('index.php'); // Redirect to index page
    exit();
  } catch (Exception $e) {
    $_SESSION['errorsession'] = $e->getMessage();
    redirect('index.php'); // Redirect to index page
    exit();
  }

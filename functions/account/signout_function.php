<?php
// include functions file
include_once './../functions.php';

// Function to signOut the user
if (isset($_POST['signout'])) {
  try {
    // Unset the session variables
    session_unset();
    // Destroy the session
    session_destroy();
    // Close the database connection
    unset($db);
    // User signed out successfully
    redirect('index.php');
    exit();
  } catch (Exception $e) {
    $_SESSION['errorsession'] = $e->getMessage();
    redirect('index.php');
    exit();
  }
}

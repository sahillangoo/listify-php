<?php
// Include the auth functions file
include_once './auth_functions.php';
// Include the database connection file
include_once './../db_connect.php';
// Function to signOut the user
if (isset($_POST['signout'])) {
  // Unset the session variables
  session_unset();
  // Destroy the session
  session_destroy();
  // Close the database connectionW
  unset($db);
  // User signed out successfully
  redirect('index.php');
  exit();
}

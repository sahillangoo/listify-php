<?php
// Start the session and check if the user is logged in as an admin
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header('Location: login.php');
  exit;
}

// Connect to the database
require_once 'db_connect.php';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the username from the form
  $username = $_POST['username'];

  // Check if the user exists in the database
  $stmt = $db->prepare('SELECT * FROM users WHERE username = ?');
  $stmt->execute([$username]);
  $user = $stmt->fetch();

  if ($user) {
    // Update the user's status to blocked
    $stmt = $db->prepare('UPDATE users SET status = "inactive" WHERE id = ?');
    $stmt->execute([$user['id']]);

    // Display a success message
    echo 'User ' . $username . ' has been blocked.';
  } else {
    // Display an error message
    echo 'User ' . $username . ' does not exist.';
  }
}
?>

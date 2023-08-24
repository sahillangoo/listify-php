<?php
/*
  *This file will handle the update password request and update the user's password in the database

*/

// include functions file
require_once __DIR__ . '/../functions.php';


// Check if the user is logged in and has a valid session
if (!isset($_SESSION['user_id'])) {
  $_SESSION['errorsession'] = "Please sign in to update your password";
  redirect('signin.php');
}

// Check if the request method is POST and the update_password parameter is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
  $user_id = $_SESSION['user_id'];
  $current_password = $_POST['current_password'];
  $new_password = $_POST['new_password'];
  $confirm_password = $_POST['confirm_password'];

  // Check if the user exists in the database
  try {
    $sql = 'SELECT * FROM users WHERE id = :user_id';
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
      $_SESSION['errorsession'] = "User not found";
      redirect('account.php');
    } else {
      // Verify the current password
      if (!password_verify($current_password, $result['password'])) {
        $_SESSION['errorsession'] = "Incorrect password";
        redirect('account.php');
      }

      // Check if the new password and confirm password match
      if ($new_password !== $confirm_password) {
        $_SESSION['errorsession'] = "New password and confirm password do not match";
        redirect('account.php');
      }

      // Update the user's password in the database
      try {
        $sql = 'UPDATE users SET password = :password WHERE id = :user_id';
        $stmt = $db->prepare($sql);
        $hashedPassword = hashPassword($password);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        $_SESSION['successsession'] = "Your password has been updated successfully";
        redirect('account.php');
      } catch (PDOException $e) {
        $_SESSION['errorsession'] = $e->getMessage();
        redirect('account.php');
      } finally {
        $stmt = null;
        $db = null;
      }
    }
  } catch (PDOException $e) {
    $_SESSION['errorsession'] = $e->getMessage();
    redirect('account.php');
  } finally {
    $stmt = null;
  }
} else {
  $_SESSION['errorsession'] = "Invalid request";
  redirect('account.php');
}

<?php
/*
  *This file will handle the update user profile request and update the user profile in the database

*/

// Include the database connection file
include_once './../db_connect.php';
// Include the functions file
include_once './../functions.php';

// Check if the user is logged in and has a valid session
if (!isset($_SESSION['user_id'])) {
  $_SESSION['errorsession'] = "Please sign in to update your profile";
  redirect('signin.php');
}

// Check if the request method is POST and the update_profile parameter is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
  $user_id = $_SESSION['user_id'];
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];

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
      // Verify the password
      if (!password_verify($password, $result['password'])) {
        $_SESSION['errorsession'] = "Incorrect password";
        redirect('account.php');
      }

      // Check if the email already exists in the database
      try {
        $sql = 'SELECT * FROM users WHERE email = :email AND id != :user_id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
          $_SESSION['errorsession'] = "Email already exists";
          redirect('account.php');
        } else {
          // Check if the username already exists in the database
          try {
            $sql = 'SELECT * FROM users WHERE username = :username AND id != :user_id';
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
              $_SESSION['errorsession'] = "Username already exists";
              redirect('account.php');
            } else {
              // Update the user profile in the database
              try {
                $sql = 'UPDATE users SET name = :name, email = :email WHERE id = :user_id';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->execute();

                $_SESSION['successsession'] = "Your profile has been updated successfully";
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
        }
      } catch (PDOException $e) {
        $_SESSION['errorsession'] = $e->getMessage();
        redirect('account.php');
      } finally {
        $stmt = null;
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

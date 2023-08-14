<?php
/*
  *This file will contain Authentication helper functions that will be used throughout the application
  List of functions:
    hashPassword() - Hash the password using bcrypt algorithm with a cost of 10
    verifyPassword() - Verify the password
    signIn() - signIn the user
*/

// Include the database connection file
include_once './../db_connect.php';

// Site Url
const BASE_URL = 'http://localhost:3000/';
// function to set location
function redirect($url)
{
  header('Location: ' . BASE_URL . $url);
  exit();
}

// Function to hash the password using PASSWORD_ARGON2ID algorithm with a cost of 12 memory cost of 2048 and time cost of 4 and returns the hashed password as a string or FALSE on failure.
function hashPassword($password): string
{
  $options = [
    'cost' => 12,
    'memory_cost' => 2048,
    'time_cost' => 4,
  ];
  return password_hash($password, PASSWORD_ARGON2ID, $options);
}

// Function to verify the password
function verifyPassword($password, $hashedPassword): bool
{
  return password_verify($password, $hashedPassword);
}

// Function to signIn the user
function signIn($email, $password): void
{
  try {
    global $db;
    // Check if the email is registered
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
      // Email doesn't exist, display a generic error message
      throw new Exception('Incorrect Username or Password');
    } else {
      // Check if the password matches
      $validPassword = verifyPassword($password, $user['password']);
      if ($validPassword) {
        // Password is correct, so start a new session
        session_start();
        // Store data in session variables
        $_SESSION["loggedin"] = true;
        $_SESSION["user_id"] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION["email"] = $user['email'];
        $_SESSION['phone'] = $user['phone'];
        $_SESSION['profile_image'] = $user['profile_image'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_since'] = $user['user_since'];

        // Redirect to the home page if role is user else redirect to the admin dashboard
        if ($_SESSION['role'] === 'user') {
          redirect('index.php');
        } else {
          redirect('admin/dashboard.php');
        }
      } else {
        // Password is not valid, display a generic error message
        throw new Exception('Incorrect Username or Password');
      }
    }
  } catch (Exception $e) {
    // send message to form page with error message
    $_SESSION['errorsession'] = $e->getMessage();
    redirect('signin.php');
    exit();
  } finally {
    $db = null;
  }
}

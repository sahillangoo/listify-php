<?php
/*
Authentication file for Signup for the Webapp
*/

// Include the file
include_once '../config/db_connect.php';
include_once './auth_functions.php';

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isLoggedIn()) {
  header('Location: ../public/index.php');
  exit;
}

// Function to check and process user signup data
function processSignupForm($username, $email, $phone, $password): void
{
  // Check if any field is empty
  if (empty($username) || empty($email) || empty($phone) || empty($password)) {
    throw new Exception("All fields are required.");
  }

  // Check if the username is valid only letters and numbers
  if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
    throw new Exception("Invalid username.");
  }

  // Check if the email is valid
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    throw new Exception("Invalid email address.");
  }

  // Check if the phone number is valid (You can add more specific checks if needed)
  if (!preg_match('/^\d{10,}$/', $phone)) {
    throw new Exception("Invalid phone number.");
  }

  // Check if the password is < 8 and > 18 characters and contains at least one lowercase letter, one uppercase letter, one number, and one special character
  if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,18}$/", $password)) {
    throw new Exception("Password must be 8-18 characters long, contain letters, numbers and special characters.");
  }
}

// Assuming the form data is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $password = $_POST['password'];


try {
  // Call the function to check and process the data
  processSignupForm($username, $email, $phone, $password);
  // If all checks pass, create the user
  createUser($username, $email, $phone, $password);
  // Redirect to index page after successful registration
  header("Location: index.php");
  exit();
} catch (Exception $e) {
  // If any exception occurs, redirect back to signup page with the error message
  header("Location: signup.php?error=" . urlencode($e->getMessage()));
  exit();
} catch (Throwable $e) {
  // Handle any other error
  header("Location: signup.php?error=" . urlencode($e->getMessage()));
  exit();
} finally {
  // Close the connection
  $pdo = null;
  }
}

?>

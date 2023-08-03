<?php
/*
Authentication file for signIn for the Webapp
*/

// Include the auth functions file
include_once './auth_functions.php';

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isLoggedIn()) {
  header('Location: ../public/index.php');
  exit;
}

// Function to check and validate user signin data
function processSigningForm($email, $password): void
{
  // Check if any field is empty
  if (empty($email) || empty($password)) {
    throw new Exception("All fields are required.");
  }

  // Check if the email is valid
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    throw new Exception("Invalid email address.");
  }
}

// Assuming the form data is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  try {
    // Call the function to check and process the data
    processSigningForm($email, $password);

    // If all checks pass, SigIn
    signIn($email, $password);

    // Redirect to index page after successful registration
    header("Location: index.php");
    exit();
  } catch (PDOException $e) {
    // If any database exception occurs, redirect back to signup page with the error message
    header("Location: signing.php?error=" . urlencode("Something went wrong. Please try again later."));
    exit();
  } catch (Exception $e) {
    // If any exception occurs, redirect back to signup page with the error message
    header("Location: signing.php?error=" . urlencode($e->getMessage()));
    exit();
  } finally {
    // Close the connection
    $pdo = null;


  }
}

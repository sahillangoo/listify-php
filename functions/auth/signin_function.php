<?php
/*
* Authentication file for signIn for the Webapp
*/

// Include the auth functions file
include_once './auth_functions.php';

// Assuming the form data is submitted via POST
if (isset($_POST['signin'])) {
  // Get the form data
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Check if any field is empty
  if (empty($email) || empty($password)) {
    $_SESSION['errorsession'] = "All Fields are Necessary.";
    redirect('signin.php');
    exit();
  }

  // Check if the email is valid
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['errorsession'] = "Check your email.";
    redirect('signin.php');
    exit();
  }
  // If all checks pass, SigIn
  signIn($email, $password);
}

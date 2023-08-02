<?php
/*
Authentication file for SignIn and Signup for the Webapp
*/

// Include the file
include_once '../config/db_connect.php';
include_once '../function/auth_functions.php';

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isLoggedIn()) {
  header('Location: ../public/index.php');
  exit;
}

// This function will check the data when the user submits the signup form and will create the user if the data is valid and not empty and will set the session when the user is registered and redirect the user to the index page with the session data set and if the data is invalid or empty it will throw an exception with the error message and will redirect the user to the signup page with the error message will use try catch and finally, Data: username, email, phone, password, confirm_password












/*
try {
  if (isset($_POST['signup'])) {
    // Check if the data is empty
    if (empty($susername) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
      echo "All fields are required!";
    } else {
      // Check if the email is valid
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
      } else {
        // Check if the phone number is valid
        if (!preg_match("/^[0-9]{10}$/", $phone)) {
          echo "Invalid phone number!";
        } else {
          // Check if the password is valid and strong must contain at least 1 lowercase, 1 uppercase, 1 numeric and 1 special character
          if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/", $password)) {
            echo "Password must contain at least 1 lowercase, 1 uppercase, 1 numeric and 1 special character!";
          } else {
            // Check if the confirm password is valid and strong must contain at least 1 lowercase, 1 uppercase, 1 numeric and 1 special character
            if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/", $confirm_password)) {
              echo "Confirm password must contain at least 1 lowercase, 1 uppercase, 1 numeric and 1 special character!";
            } else {
              // Check if the passwords match
              if ($password !== $confirm_password) {
                echo "Passwords do not match!";
              } else {
                // Create the user
                createUser($pdo, $susername, $email, $phone, $password);
              }
            }
          }
        }
      }
    }
  }
} catch (Exception $e) {
  echo $e->getMessage();
} finally {
  // set session when user is registered
  $_SESSION['user_id'] = $user_id;
  $_SESSION['username'] = $username;
  $_SESSION['email'] = $email;
  $_SESSION['phone'] = $phone;
  $_SESSION['role'] = $role;
  header('Location: ../public/index.php');
  exit;
}
*/

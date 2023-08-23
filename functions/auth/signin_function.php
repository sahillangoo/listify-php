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
        $_SESSION['successsession'] = "You have successfully logged in.";
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

<?php
/*
* Authentication file for Signup for the Webapp
*/

// Include the auth functions file
include_once './auth_functions.php';

// Check if the is admin or user anf is alredy logged in then redirect to respective pages
if (isLoggedIn()) {
  if ($_SESSION['user_type'] === 'admin') {
    header("Location: ./../../admin/index.php");
    exit();
  } else {
    header("Location: ./../../index.php");
    exit();
  }
}

// Assuming the form data is submitted via POST
// check if the form is submitted
if (isset($_POST['signup'])) {
  // check if all fields are filled
  if (empty($_POST["username"]) || empty($_POST["email"]) || empty($_POST["phone"]) || empty($_POST["password"]) || empty($_POST["terms"])) {
    $_SESSION['errorsession'] = "All Fields are Necessary.";
    header('location: ./../../signin.php');
    exit();
  }
  // Get the form data
  $username = $_POST['username'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $password = $_POST['password'];

  // Check and process the form data
  // Check if the username is valid only letters and numbers
  if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
    $_SESSION['errorsession'] = "Username must contain only letters or number.";
    header('location: ./../../signin.php');
    exit();
  }

  // Check if the email is valid
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['errorsession'] = "Invalid Email.";
    header('location: ./../../signin.php');
    exit();
  }

  // Check if the phone number is valid (You can add more specific checks if needed)
  if (!preg_match('/^\d{10,}$/', $phone)) {
    $_SESSION['errorsession'] = "Invalid phone number.";
    header('location: ./../../signin.php');
    exit();
  }

  // Check if the password is < 8 and > 18 characters and contains at least one lowercase letter, one uppercase letter, one number, and one special character
  if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,18}$/", $password)) {
    throw new Exception("Password must be 8-18 characters long, contain letters, numbers and special characters.");
    $_SESSION['errorsession'] = "Password must be 8-18 characters long, contain letters, numbers and special characters.";
    header('location: ./../../signin.php');
    exit();
  }

  try {
    // Check if the username is already registered
    $sql = "SELECT COUNT(username) AS num FROM users WHERE username = :username";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row['num'] > 0) {
      throw new Exception('That username already exists!');
    }

    // Check if the email is already registered
    $sql = "SELECT COUNT(email) AS num FROM users WHERE email = :email";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row['num'] > 0) {
      throw new Exception('That email already exists!');
    }

    // Check if the phone number is already registered
    $sql = "SELECT COUNT(phone) AS num FROM users WHERE phone = :phone";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':phone', $phone);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row['num'] > 0) {
      throw new Exception('That phone number already exists!');
    }

    // Set the role
    $role = 'user';

    // Hash the password
    $hashedPassword = hashPassword($password);

    // Set the timestamp
    $created_at = date('Y-m-d H:i:s');

    // Insert the user into the database
    $sql = "INSERT INTO users (username, email, phone, password, role, user_since) VALUES (:username, :email, :phone, :password, :role, :created_at)";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':phone', $phone);
    $stmt->bindValue(':password', $hashedPassword);
    $stmt->bindValue(':role', $role);
    $stmt->bindValue(':user_since', $created_at);
    $stmt->execute();
    //error handling
    if ($stmt->rowCount() == 0) {
      throw new Exception('Could not register you in database - please try again later.');
    }
    // send message to form page with success message
    $_SESSION['successsession'] = "You have been registered successfully.";
    header('location: ./../../signin.php');
    exit;
  } catch (Exception $e) {
    // send message to form page with error message
    $_SESSION['errorsession'] = $e->getMessage();
    header('location: ./../../signin.php');
    exit;
  } finally {
    // Close the connection
    $db = null;
    // Set session variables
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
    $_SESSION['phone'] = $phone;
    $_SESSION['role'] = $role;
    $_SESSION['user_since'] = $created_at;

    // Redirect to the home page
    header("Location: ./../../index.php");
    exit();
  }
}

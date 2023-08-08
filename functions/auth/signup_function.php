<?php

/*
* Authentication file for Signup for the Webapp

    file content
      include the auth functions file
      file location variables
      check if the form is submitted
      check if all fields are filled
      get the form data
      check and process the form data
      check if the username is valid only letters and numbers
      check if the email is valid
      check if the phone is valid
      check if the password is valid
      check if the terms is checked
      check if the email is already registered
      check if the username is already registered
      insert the user into the database
      redirect the user to the signin page
      catch the exception and show error message

  Author: SahilLangoo
  lastModified: 7/8/2023
    */

// ! error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the auth functions file
include_once './auth_functions.php';

// file location variables
$signinPage = "./../../signin.php";
$indexPage = "./../../index.php";

// Assuming the form data is submitted via POST
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
    $_SESSION['errorsession'] = "Username must contain only letters or numbers.";
    header('location: ./../../signin.php');
    exit();
  }

  // Check if the email is valid
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['errorsession'] = "Invalid Email. Please enter a valid email.";
    header('location: ./../../signin.php');
    exit();
  }

  // Check if the phone number is valid (You can add more specific checks if needed)
  if (!preg_match('/^\d{10,}$/', $phone)) {
    $_SESSION['errorsession'] = "Invalid phone number. Please enter a 10-digit phone number.";
    header('location: ./../../signin.php');
    exit();
  }

  // Check if the password is < 8 and > 18 characters and contains at least one lowercase letter, one uppercase letter, one number, and one special character
  if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,18}$/", $password)) {
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
    if ($stmt->errorCode() !== '00000') {
      throw new Exception('Cannot check username!');
    }
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row['num'] > 0) {
      throw new Exception('That username is already taken!');
    }

    // Check if the email is already registered
    $sql = "SELECT COUNT(email) AS num FROM users WHERE email = :email";
    $stmt = $db->prepare($sql);
    $stmt->bindValue('email', $email);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row === false) {
      throw new Exception('Could not retrieve email count!');
    }
    $num = $row['num'];
    error_log("num is $num");
    if ($num > 0) {
      throw new Exception('That email is already registered!');
    }

    // Check if the phone number is already registered
    $sql = "SELECT COUNT(phone) AS num FROM users WHERE phone = :phone";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':phone', $phone);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
      throw new Exception('Could not determine if the phone number already exists!');
    }
    if ($row['num'] > 0) {
      throw new Exception('This phone number already exists!');
    }

    // profile pic from Dice Bear
    $profile_pic = "https://api.dicebear.com/6.x/micah/svg?seed=$username&flip=true&background=%230000ff&radius=50&margin=10&baseColor=f9c9b6";

    // Set the role
    $role = 'user';

    // Hash the password
    $hashedPassword = hashPassword($password);

    // Set the timestamp
    $created_at = date('Y-m-d H:i:s');

    // Insert the user into the database
    $sql = "INSERT INTO users (username, email, phone, password, profile_image, role, user_since) VALUES (:username, :email, :phone, :password, :profile_image, :role, :user_since)";
    $stmt = $db->prepare($sql);
    $stmt->bindValue('username', $username);
    $stmt->bindValue('email', $email);
    $stmt->bindValue('phone', $phone);
    $stmt->bindValue('password', $hashedPassword);
    $stmt->bindValue('profile_image', $profile_pic);
    $stmt->bindValue('role', $role);
    $stmt->bindValue('user_since', $created_at);
    $stmt->execute();
    //error handling
    if ($stmt->rowCount() == 0) {
      throw new Exception('Could not register you in database - please try again at a later time.');
    }
    // send message to form page with success message
    $_SESSION['successsession'] = "You have been registered successfully.";
    header("Location: ./../../index.php");
    exit;
  } catch (Exception $e) {
    // send message to form page with error message
    $_SESSION['errorsession'] = $e->getMessage();
    header('location: ./../../signin.php');
    exit;
  } finally {
    // unset db
    $db = null;

    // Set session variables
    $_SESSION["loggedin"] = true;
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

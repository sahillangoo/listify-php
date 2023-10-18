<?php

/**
 * File: signup_function.php
 * Description: Signup function for the user registration page
 * Author: SahilLangoo
 * Last modified: 15/10/2023
 */

// Include functions file
require_once __DIR__ . '/../functions.php';

// Check if the form data is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
  try {
    // Use CSRF protection on this form
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'], $csrf_token)) {
      // Reset the CSRF token
      unset($_SESSION['csrf_token']);
      throw new Exception('CSRF token validation failed.');
    }

    // Check if all fields are filled
    $required_fields = ['username', 'email', 'phone', 'password', 'terms'];
    foreach ($required_fields as $field) {
      if (empty($_POST[$field])) {
        throw new Exception('All fields are necessary.');
      }
    }

    // Get the form data
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $password = clean($_POST['password']);
    $terms = sanitize($_POST['terms']);

    // Check if the username is valid
    if (!preg_match('/^(?<username>[a-z0-9._-]{6,20})$/i', $username)) {
      throw new Exception('Username must be 6-20 characters long, contain letters and numbers, and must not contain spaces, special characters, or emoji.');
    }

    // Check if the email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL, 255)) {
      throw new Exception('Invalid email. Please enter a valid email.');
    }

    // Check if the phone number is valid
    if (!preg_match('/^\d{10,}$/', $phone)) {
      throw new Exception('Invalid phone number. Please enter a 10-digit phone number.');
    }

    // Check if the password is valid
    if (preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#\-_@$!%*?&+~|{}:;<>.\/])[A-Za-z\d#\-_@$!%*?&+~|{}:;<>.\/]{8,18}$/', $password) !== 1) {
      throw new Exception('Password must be 8-18 characters long, contain letters, numbers, and special characters, and must not contain spaces or emoji.');
    }

    // Check if the username, email, and phone number are already registered
    $stmt = $db->prepare('SELECT COUNT(username), COUNT(email), COUNT(phone) FROM users WHERE username = :username OR email = :email OR phone = :phone');
    $stmt->execute([':username' => $username, ':email' => $email, ':phone' => $phone]);
    $counts = $stmt->fetch(PDO::FETCH_NUM);
    if (isset($counts[0]) && $counts[0] > 0) {
      throw new Exception('That username is already taken!');
    }
    if (isset($counts[1]) && $counts[1] > 0
    ) {
      throw new Exception('That email is already registered!');
    }
    if (isset($counts[2]) && $counts[2] > 0
    ) {
      throw new Exception('That phone number already exists!');
    }

    // Generate a unique profile picture for the user using the Dice Bear API
    $profile_pic = "https://api.dicebear.com/7.x/micah/svg?seed=$username&backgroundColor=b6e3f4,c0aede,d1d4f9&backgroundType=gradientLinear,solid&radius=40&size=80&flip=true&margin=10&baseColor=ac6651,f9c9b6&randomizeIds=false&earringsProbability=0&glasses=round,square&mouth=laughing,nervous,smile,smirk,surprised&hair=dannyPhantom,fonze,full,pixie&hairProbability=100
    ";

    // Hash the password
    $hashedPassword = hashPassword($password);
    // Set the timestamp
    $created_at = date('Y-m-d H:i:s');
    // Generate a unique verification code
    $verification_token = bin2hex(random_bytes(16));

    // Insert the user into the database with the verification code
    $stmt = $db->prepare('INSERT INTO users (username, email, phone, password, profile_image, verification_token) VALUES (:username, :email, :phone, :password, :profile_image, :verification_token)');
    $stmt->execute([
      ':username' => $username, ':email' => $email, ':phone' => $phone, ':password' => $hashedPassword, ':profile_image' => $profile_pic, ':verification_token' => $verification_token
    ]);

    // Check if the user was successfully inserted into the database
    if ($stmt->rowCount() == 0) {
      throw new Exception('Could not register you in database - please try again at a later time.');
    }

    // Get the user ID from the database
    $user_id = $db->lastInsertId();

    // Set session variables
    $_SESSION['authenticated'] = true;
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
    $_SESSION['phone'] = $phone;
    $_SESSION['profile_image'] = $profile_pic;
    $_SESSION['role'] = $role;
    $_SESSION['user_since'] = $created_at;
    $_SESSION['verified'] = false;


    // Send verification email to the user
    $to = $email;
    $subject = 'Verify your email address form Listify';
    $message = '
    Hey ' . $username . ',<br><br>
    Thank you for signing up on Listify! Please click on the following link to verify your email address: <a href="' . BASE_URL . '/functions/account/verify.php?email=' . $email . '&code=' . $verification_token . '">Verify Email</a><br><br>
    If you did not sign up on Listify, please ignore this email.<br><br>
    Thanks,<br>
    Listify Team
    ';
    $url = trim(BASE_URL, "http://");
    $url = explode("/", $url);
    $url = $url[0];
    $headers = [
      'MIME-Version: 1.0',
      'Content-type: text/html; charset=iso-8859-1',
      'From: noreply@' . $url,
      'Reply-To: noreply@' . $url,
      'X-Mailer: PHP/' . phpversion()
    ];

    // Mail it
    if (!mail(
      $to,
      $subject,
      $message,
      implode("\r\n", $headers)
    )) {
      throw new Exception('Could not send verification email - please try again at a later time.');
    }

    // Redirect to the home page
    redirect('index.php');
  } catch (PDOException $e) {
    error_log($e->getMessage());
    // Send message to form page with error message
    $_SESSION['errorsession'] = $e->getMessage();
    redirect('signup.php?clear');
    exit();
  } catch (Exception $e) {
    // Log the exception
    error_log($e->getMessage());
    // Send message to form page with error message
    $_SESSION['errorsession'] = $e->getMessage();
    redirect('signup.php?clear');
    exit();
  } finally {
    // Unset db
    $db = null;
  }
}

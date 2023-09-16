<?php
/**
 * Authentication file for Signup for the Webapp
 * Author: SahilLangoo
 * Last modified: 23/8/2023
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
    $username = clean($_POST['username']);
    $email = clean($_POST['email']);
    $phone = clean($_POST['phone']);
    $password = clean($_POST['password']);

    // Check if the username is valid
    if (!preg_match('/^(?<username>[a-z0-9._-]{6,20})$/i', $username)) {
      throw new Exception('Username must be 6-20 characters long, contain letters and numbers, and must not contain spaces, special characters, or emoji.');
    }

    // Check if the email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      throw new Exception('Invalid email. Please enter a valid email.');
    }

    // Check if the phone number is valid
    if (!preg_match('/^\d{10,}$/', $phone)) {
      throw new Exception('Invalid phone number. Please enter a 10-digit phone number.');
    }

    // Check if the password is valid
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#\-_@$!%*?&+~|{}:;<>\/])[A-Za-z\d#\-_@$!%*?&+~|{}:;<>\/]{8,18}$/', $password)) {
      throw new Exception('Password must be 8-18 characters long, contain letters, numbers, and special characters, and must not contain spaces or emoji.');
    }

    // Check if the username, email, and phone number are already registered
    $stmt = $db->prepare('SELECT COUNT(username), COUNT(email), COUNT(phone) FROM users WHERE username = :username OR email = :email OR phone = :phone');
    $stmt->execute([':username' => $username, ':email' => $email, ':phone' => $phone]);
    $counts = $stmt->fetch(PDO::FETCH_NUM);
    if ($counts[0] > 0) {
      throw new Exception('That username is already taken!');
    }
    if ($counts[1] > 0) {
      throw new Exception('That email is already registered!');
    }
    if ($counts[2] > 0) {
      throw new Exception('This phone number already exists!');
    }

    // Generate a unique profile picture for the user using the Dice Bear API
    $profile_pic = "https://api.dicebear.com/6.x/micah/svg?seed=$username&flip=true&background=%230000ff&radius=50&margin=10&baseColor=f9c9b6";

    // Set the role
    $role = 'user';

    // Hash the password
    $hashedPassword = hashPassword($password);

    // Set the timestamp
    $created_at = date('Y-m-d H:i:s');

    // Insert the user into the database
    $stmt = $db->prepare('INSERT INTO users (username, email, phone, password, profile_image, role, user_since) VALUES (:username, :email, :phone, :password, :profile_image, :role, :user_since)');
    $stmt->execute([':username' => $username, ':email' => $email, ':phone' => $phone, ':password' => $hashedPassword, ':profile_image' => $profile_pic, ':role' => $role, ':user_since' => $created_at]);

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

    // Redirect to the home page
    redirect('index.php');
  } catch (PDOException $e) {
    // Log the exception
    error_log($e->getMessage());
    // Send message to form page with error message
    $_SESSION['errorsession'] = 'An error occurred while processing your request. Please try again later.';
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

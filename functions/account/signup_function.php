<?php

/*
* Authentication file for Signup for the Webapp
  Author: SahilLangoo
  lastModified: 23/8/2023
*/

// include functions file
require_once __DIR__ . '/../functions.php';

// Assuming the form data is submitted via POST
if (isset($_POST['signup'])) {

  // Use CSRF protection on this form
  $csrf_token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
  try {
    if (!hash_equals($_SESSION['csrf_token'], $csrf_token)) {
      // Reset the CSRF token
      unset($_SESSION['csrf_token']);
      // send message to form page with error message
      throw new Exception('CSRF token validation failed.');
    }
  } catch (Exception $e) {
    $_SESSION['errorsession'] = $e->getMessage();
    redirect('signup.php');
    exit();
  }

  // check if all fields are filled
  $required_fields = array("username", "email", "phone", "password", "terms");
  foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
      $_SESSION['errorsession'] = "All fields are necessary.";
      redirect('signup.php?clear');
      exit();
    }
  }
  try {
    // Get the form data
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $password = $_POST['password'];

    // Check and process the form data
    // Check if the username is valid only letters and numbers
    if (!preg_match("/^(?=.*[a-z])[a-z0-9]{3,20}$/i", $username)) {
      $_SESSION['errorsession'] = "Username must contain only letters or numbers and be between 3 to 20 characters long, and must contain at least one letter.";
      redirect('signup.php?clear');
      exit();
    }
    // Check if the email is valid
    if (!filter_var(
      $email,
      FILTER_VALIDATE_EMAIL
    )) {
      $_SESSION['errorsession'] = "Invalid email. Please enter a valid email.";
      redirect('signup.php?clear');
      exit();
    }

    // Check if the phone number is valid (You can add more specific checks if needed)
    if (!preg_match('/^\d{10,}$/', $phone)) {
      $_SESSION['errorsession'] = "Invalid phone number. Please enter a 10-digit phone number.";
      redirect('signup.php?clear');
      exit();
    }

    // Check if the password is valid
    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,18}$/", $password)) {
      $_SESSION['errorsession'] = "Password must be 8-18 characters long, contain letters, numbers and special characters.";
      redirect('signup.php?clear');
      exit();
    }

    // Check if the username, email, and phone number are already registered
    $stmt = $db->prepare("SELECT COUNT(username), COUNT(email), COUNT(phone) FROM users WHERE username = :username OR email = :email OR phone = :phone");
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(
      ':email',
      $email
    );
    $stmt->bindValue(
      ':phone',
      $phone
    );
    $stmt->execute();
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
    $sql = "INSERT INTO users (username, email, phone, password, profile_image, role, user_since) VALUES (:username, :email, :phone, :password, :profile_image, :role, :user_since)";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':phone', $phone);
    $stmt->bindValue(':password', $hashedPassword);
    $stmt->bindValue(':profile_image', $profile_pic);
    $stmt->bindValue(':role', $role);
    $stmt->bindValue(':user_since', $created_at);
    $stmt->execute();

    // Check if the user was successfully inserted into the database
    if ($stmt->rowCount() == 0) {
      throw new Exception('Could not register you in database - please try again at a later time.');
    }

    // Get the user ID from the database
    $user_id = $db->lastInsertId();

    // Set session variables
    $_SESSION["authenticated"] = true;
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
    // log the exception
    error_log($e->getMessage());
    // send message to form page with error message
    $_SESSION['errorsession'] = 'An error occurred while processing your request. Please try again later.';
    redirect('signup.php?clear');
    exit();
  } catch (Exception $e) {
    // log the exception
    error_log($e->getMessage());
    // send message to form page with error message
    $_SESSION['errorsession'] = $e->getMessage();
    redirect('signin.php');
    exit();
  } finally {
    // unset db
    $db = null;
  }
}

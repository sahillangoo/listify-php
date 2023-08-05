<?php
/*
*This file will contain Authentication helper functions that will be used throughout the application
List of functions:
  isLoggedIn() - Check if the user is logged in
  hashPassword() - Hash the password using bcrypt algorithm with a cost of 10
  checkUser() - Check if the username,email,phone is already registered
  createUser() - Create a new user in the database
  verifyPassword() - Verify the password
  signIn() - signIn the user
  signOut() - signOut the user
*/

// Include the database connection file
$var = require_once __DIR__ . '/../db_connect.php';

// Function to check if the user is logged in
function isLoggedIn(): bool
{
  if (isset($_SESSION['user_id']) && is_int($_SESSION['user_id'])) {
    return true;
  } else {
    return false;
  }
}

// Function to hash the password using PASSWORD_ARGON2ID algorithm with a cost of 12 memory cost of 2048 and time cost of 4 and returns the hashed password as a string or FALSE on failure.

function hashPassword($password): string
{
  $options = [
    'cost' => 12,
    'memory_cost' => 2048,
    'time_cost' => 4,
  ];
  return password_hash($password, PASSWORD_ARGON2ID, $options);
}

// Function to Check if the username,email,phone is already registered
function checkUser($username, $email, $phone): void
{
  try {
    global $db;

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
  } catch (Exception $e) {
    echo $e->getMessage();
    // send message to form page with error message
    header("Location: ./../../my-account.php?error=" . $e->getMessage());
    exit;
  }
}

// function to create user in the database if user doent exists then if role is user go to index.php if role is admin go to admin.php
function createUser($username, $email, $phone, $password, $role = 'user'): void
{
  try {
    global $db;

    // Check if the username, email, phone is already registered if not then create a new user
    checkUser($username, $email, $phone);

    // Hash the password
    $hashedPassword = hashPassword($password);

    // Set the timestamp
    $created_at = date('Y-m-d H:i:s');

    // Insert the user into the database
    $sql = "INSERT INTO users (username, email, phone, password, role, created_at) VALUES (:username, :email, :phone, :password, :role, :created_at)";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':phone', $phone);
    $stmt->bindValue(':password', $hashedPassword);
    $stmt->bindValue(':role', $role);
    $stmt->bindValue(':created_at', $created_at);
    $stmt->execute();

    // User created successfully
    if ($role == 'user') {
      header('Location: ./../../index.php');
      exit;
    } else if ($role == 'admin') {
      header('Location: ./../../admin/index.php');
      exit;
    }
  } catch (Exception $e) {
    echo $e->getMessage();
    // send message to form page with error message
    header("Location: ./../../my-account.php?error=" . $e->getMessage());
  }
}

// Function to verify the password
function verifyPassword($password, $hashedPassword): bool
{
  return password_verify($password, $hashedPassword);
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
      throw new Exception('That email address is not registered!');
    } else {
      // Check if the password matches
      $validPassword = verifyPassword($password, $user['password']);
      if ($validPassword) {
        // Set the session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['phone'] = $user['phone'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['created_at'] = $user['created_at'];

        // User signed in successfully
        header('Location: ./../../index.php');
        exit;
      } else {
        throw new Exception('Incorrect password!');
      }
    }
  } catch (Exception $e) {
    echo $e->getMessage();
    // send message to form page with error message
    header("Location: ./../../my-account.php?error=" . $e->getMessage());
  }
}

// Function to signOut the user
function signOut(): void
{
  // Unset the session variables
  session_unset();
  // Destroy the session
  session_destroy();
  // User signed out successfully
  header('Location: ./../../index.php');
  echo "<script>alert('You have successfully logged out!')</script>";
  exit;
}

// Function to generate a random token
function generateToken($length = 50)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $token = '';
    for ($i = 0; $i < $length; $i++) {
        $token .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $token;
}

// Function to initiate the password reset process
function initiatePasswordReset($email)
{
  global $db;

  // Check if the email exists in the database
  $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception("Email not found.");
    }

    // Generate a random token
    $token = generateToken();

    // Set the expiration time (e.g., 1 hour from now)
    $expirationTime = date('Y-m-d H:i:s', strtotime('+2 hour'));

  // Insert the token into the database
  $stmt = $db->prepare("INSERT INTO password_reset_tokens (user_id, token, expiration_time) VALUES (:user_id, :token, :expiration_time)");
    $stmt->bindParam(':user_id', $user['id']);
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':expiration_time', $expirationTime);
    $stmt->execute();

    // Send an email to the user with the password reset link containing the token
    $resetLink = "http://listify.com/reset_password.php?token=" . urlencode($token);
    // Here, you should send the email to the user with the reset link

    return true;
}

// Function to reset the password
function resetPassword($token, $newPassword)
{
  global $db;

  // Check if the token exists in the database and is not expired
  $stmt = $db->prepare("SELECT user_id, expiration_time FROM password_reset_tokens WHERE token = :token AND expiration_time > NOW()");
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $tokenData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tokenData) {
        throw new Exception("Invalid or expired token.");
    }

    // Update the user's password in the database
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
  $stmt = $db->prepare("UPDATE users SET password = :password WHERE id = :user_id");
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':user_id', $tokenData['user_id']);
    $stmt->execute();

  // Delete the used token from the database
  $stmt = $db->prepare("DELETE FROM password_reset_tokens WHERE token = :token");
    $stmt->bindParam(':token', $token);
    $stmt->execute();

    return true;
}
?>

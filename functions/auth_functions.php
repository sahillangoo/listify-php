<?php
/*
*This file will contain Authentication helper functions that will be used throughout the application
List of functions:
  isLoggedIn() - Check if the user is logged in
  hashPassword() - Hash the password using bcrypt algorithm with a cost of 10
  createUser() - Create a new user in the database with username, email, phone, password and set user to defualt role
  verifyPassword() - Verify the password
  signIn() - signIn the user
  signOut() - signOut the user
*/

// start the session
session_start();
// Include the database connection file
include_once 'config/db_connect.php';

// Function to check if the user is logged in
function isLoggedIn(): bool
{
  if (isset($_SESSION['user_id']) && is_int($_SESSION['user_id'])) {
    return true;
  } else {
    return false;
  }
}

// Function to hash the password using bcrypt algorithm with a cost of 10
function hashPassword($password): string
{
  return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
}

// Function to Check is user Registered then Create a new user in the database with username, email, phone, password and set user to defualt role
function createUser($username, $email, $phone, $password): void
{
  try {
    global $PDO;
    // Check if the email is already registered
    $sql = "SELECT COUNT(email) AS num FROM users WHERE email = :email";
    $stmt = $PDO->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row['num'] > 0) {
      throw new Exception('That email address or phone number already exists!');
    }

    // Check if the phone number is already registered
    $sql = "SELECT COUNT(phone) AS num FROM users WHERE phone = :phone";
    $stmt = $PDO->prepare($sql);
    $stmt->bindValue(':phone', $phone);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row['num'] > 0) {
      throw new Exception('That phone number already exists!');
    }
  } catch (Exception $e) {
    echo $e->getMessage();
  } finally {
    try {
      // Hash the password
      $password = hashPassword($password);
      // Set the default role
      $role = 'user';
      // Set the timestamp
      $created_at = date('Y-m-d H:i:s');

      // Insert the user into the database
      $sql = "INSERT INTO users (username, email, phone, password, role, created_at) VALUES (:username, :email, :phone, :password, :role, :created_at)";
      $stmt = $PDO->prepare($sql);
      $stmt->bindValue(':username', $username);
      $stmt->bindValue(':email', $email);
      $stmt->bindValue(':phone', $phone);
      $stmt->bindValue(':password', $password);
      $stmt->bindValue(':role', $role);
      $stmt->bindValue(':created_at', $created_at);
      $result = $stmt->execute();

      if ($result) {
        // Get the user id
        $user_id = $PDO->lastInsertId();

        // Set the session variables
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['phone'] = $phone;
        $_SESSION['role'] = $role;
        $_SESSION['created_at'] = $created_at;

        // Redirect to the dashboard
        header('location: index.php');
      } else {
        // Show an error message
        $error_message = 'Something went wrong. Please try again.';
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    } finally {
      unset($PDO);
    }
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
    global $PDO;
    // Check if the email is registered
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $PDO->prepare($sql);
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
        header('Location: ../public/index.php');
        exit;
      } else {
        throw new Exception('Incorrect password!');
      }
    }
  } catch (Exception $e) {
    echo $e->getMessage();
  } finally {
    unset($PDO);
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
  header('Location: ../public/index.php');
  echo "<script>alert('You have successfully logged out!')</script>";
  exit;
}

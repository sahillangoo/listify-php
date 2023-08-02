<?php
/*
*This file will contain Authentication helper functions that will be used throughout the application
List of functions:
  isLoggedIn() - Check if the user is logged in
  hashPassword() - Hash the password using bcrypt algorithm with a cost of 10
  createUser() - Create a new user in the database with username, email, phone, password and set user to defualt role
  verifyPassword() - Verify the password
*/

// Include the database connection file
include_once 'config/db_connect.php';

// Function to check if the user is logged in
function isLoggedIn()
{
  if (isset($_SESSION['user_id'])) {
    return true;
  } else {
    return false;
  }
}

// Function to hash the password using bcrypt algorithm with a cost of 10
function hashPassword($password)
{
  $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
  return $hash;
}

// Function to Check is user Registered then Create a new user in the database with username, email, phone, password and set user to defualt role
function createUser($pdo, $username, $email, $phone, $password, $role, $timestamp)
{
  try {
    // Check if the user is already registered
    $sql = "SELECT COUNT(email) AS num FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row['num'] > 0) {
      throw new Exception('That email address or phone number already exists!');
    }

    // Check if the phone number is already registered
    $sql = "SELECT COUNT(phone) AS num FROM users WHERE phone = :phone";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':phone', $phone);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row['num'] > 0) {
      throw new Exception('That phone number already exists!');
    }

    // Check if the username is already registered
    $sql = "SELECT COUNT(username) AS num FROM users WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row['num'] > 0) {
      throw new Exception('That username already exists!');
    }
  } catch (Exception $e) {
    echo $e->getMessage();
  } finally {
    try {
      // Hash the password
      $password = hashPassword($password);

      // Insert the user into the database
      $sql = "INSERT INTO users (username, email, phone, password, role, created_at) VALUES (:username, :email, :phone, :password, :role, :created_at)";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':username', $username);
      $stmt->bindValue(':email', $email);
      $stmt->bindValue(':phone', $phone);
      $stmt->bindValue(':password', $password);
      $stmt->bindValue(':role', $role);
      $stmt->bindValue(':created_at', $timestamp);
      $result = $stmt->execute();

      if ($result) {
        // Get the user id
        $user_id = $pdo->lastInsertId();

        // Set the session variables
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['phone'] = $phone;
        $_SESSION['role'] = $role;
        $_SESSION['created_at'] = $timestamp;

        // User created successfully
        header('Location: ../public/index.php');
        exit;
      }
    } catch (Exception $e) {
      echo $e->getMessage();
    } finally {
      unset($pdo);
    }
  }
}

// Function to verify the password
function verifyPassword($password, $hash)
{
  if (password_verify($password, $hash)) {
    return true;
  } else {
    return false;
  }
}

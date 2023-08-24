<?php
// This file contains the functions that will be used in the application
// Start the session if it has not already started
$sessionStarted = session_start();
if (!$sessionStarted) {
  throw new \RuntimeException('Session could not be started');
}
// Include DB file
require_once __DIR__ . '/../functions/db_connect.php';
// Site URL
const BASE_URL = 'http://localhost:3000/';
// Function to set location
function redirect($url)
{
  header('Location: ' . BASE_URL . $url);
}
function sanitize($data): string
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return strip_tags($data);
}
// Generate a CSRF token and store it in the user's session
if (!isset($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}
function hashPassword($password): string
{
  $options = [
    'cost' => 12,
    'memory_cost' => 2048,
    'time_cost' => 4,
  ];
  return password_hash($password, PASSWORD_ARGON2ID, $options);
}
// check https function
function check_https()
{
  if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
    return false;
  }
  return true;
}

// Function to check if the user is logged in
function isAuthenticated(): bool
{
  return !empty($_SESSION["authenticated"]) && $_SESSION["authenticated"] === true;
}
// function to check if the user role is admin
function isAdmin(): bool
{
  return isset($_SESSION["role"]) && $_SESSION["role"] === 'admin';
}

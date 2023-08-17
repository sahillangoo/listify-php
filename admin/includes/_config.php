<?php
// ! error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// turn on output buffering
ob_start();

// Site Url
const BASE_URL = 'http://localhost:3000/';

// function to set location
function redirect($url)
{
  header('Location: ' . BASE_URL . $url);
  exit();
}

//start session
if (!isset($_SESSION)) {
  session_start();
}

// Function to check if the user is logged
function isLoggedIn(): bool
{
  return isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true;
}


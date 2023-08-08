<?php

// Define your routes here
$routes = [
  '/' => 'index.php',
  '/signin' => 'signin.php',
  '/signup' => 'signup.php',
  '/dashboard' => 'admin/index.php',
  '/404' => '404.php',
];

// Get the current URI
$uri = $_SERVER['REQUEST_URI'];

// Check if the route exists
if (array_key_exists($uri, $routes)) {
  // Get the file path
  $file = $routes[$uri];

  // Check if the file exists
  if (file_exists($file)) {
    // Include the file
    require $file;
  } else {
    // File not found
    header('HTTP/1.0 404 Not Found');
    include '404.php';
  }
} else {
  // Route not found
  header('HTTP/1.0 404 Not Found');
  include '404.php';
}

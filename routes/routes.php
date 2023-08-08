<?php

// start session
session_start();

// Define your routes
$routes = [
  '/' => 'index.php',
  '/about' => 'about.php',
  '/contact' => 'contact.php',
  '/signin' => __DIR__ . '/signin.php',
];

// Get the requested URL
$requestUrl = $_SERVER['REQUEST_URI'];

// Check if the requested URL is in the routes array
if (array_key_exists($requestUrl, $routes)) {
  // If it is, redirect to the corresponding file
  require($routes[$requestUrl]);
  exit;
} else {
  // If it's not, redirect to a 404 page
  header('HTTP/1.0 404 Not Found');
  include('./../404.php');
  exit;
}

<?php

/**
 * Sign out function for the Webapp
 *
 * Author: SahilLangoo
 * Last modified: 23/8/2023
 */

require_once __DIR__ . '/../functions.php';

try {
  // Unset the session variables
  session_unset();

  // Close the database connection
  unset($db);

  // Delete the remember me cookie if set
  setcookie('remember_me', '', time() - 3600, '/');

  // Redirect to index page
  redirect('index.php');
} catch (Exception $e) {
  // Set error message in session and redirect to index page
  $_SESSION['errorsession'] = $e->getMessage();
  redirect('index.php');
}


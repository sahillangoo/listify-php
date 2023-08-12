<?php

/*
* Database connection Using PDO (PHP Data Object)
* Database Variables
Database : MySQL (MariaDB)
Database Name : listify_db

TODO on Production:
  PROD for production
  DEV for development
*/

// ! error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Timezone Configuration
date_default_timezone_set('Asia/Kolkata');

// const('ENV', 'PROD');
const ENV = 'DEV';
const DB_HOST = 'localhost';
if (ENV === 'PROD') {
    define('DB_NAME', 'db_name');
    define('DB_USER', 'db_user');
    define('DB_PASS', 'db_pass');
} else {
    define('DB_NAME', 'listify_db');
    define('DB_USER', 'root');
    define('DB_PASS', '');
}

//Function to connect to the database using PDO
function connectToDB()
{
  try {
    // Create a new PDO instance
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
      PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
    ];
      return new PDO($dsn, DB_USER, DB_PASS, $options);
  } catch (Exception $e) {
    error_log($e->getMessage());
    exit('Something weird happened with DB || Is DB server turned on?'); //something a user can understand
  }
}
// to use db
$db = connectToDB();

//start session
if (!isset($_SESSION)) {
  session_start();
}

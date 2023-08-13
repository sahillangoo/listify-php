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

$ENV = $_ENV['ENV'] ?? 'DEV';
if ($ENV === 'PROD') {
  $DB_HOST = $_ENV['AZURE_MYSQL_HOST'];
  $DB_PORT = $_ENV['AZURE_MYSQL_PORT'];
  $DB_NAME = $_ENV['AZURE_MYSQL_DBNAME'];
  $DB_USER = $_ENV['AZURE_MYSQL_USERNAME'];
  $DB_PASS = $_ENV['AZURE_MYSQL_PASSWORD'];
} else {
  define('DB_HOST', 'localhost');
  define('DB_PORT', '3306');
  define('DB_NAME', 'listify_db');
  define('DB_USER', 'root');
  define('DB_PASS', '');
}

//Function to connect to the database using PDO
function connectToDB()
{
  try {
    // Create a new PDO instance
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
      PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
    ];
    //Create instance of PDO class
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    //Return the PDO instance
    return $pdo;
  } catch (PDOException $e) {
    // If there is an error with the connection, stop the script and display the error.
    exit('Something weird happened with DB || Is DB server turned on?'); //something a user can understand
  }
}
// to use db
$db = connectToDB();

//start session
if (!isset($_SESSION)) {
  session_start();
}

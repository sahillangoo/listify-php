<?php

/*
* Database connection settings
Database Name : listify_db
*/

/* ! The code `ini_set('display_errors', 1); ini_set('display_startup_errors', 1);
error_reporting(E_ALL);` is used to configure the error reporting settings in PHP. */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/* The line `date_default_timezone_set('Asia/Kolkata');` is used to set the default timezone for date
and time functions in PHP. In this case, it sets the timezone to "Asia/Kolkata", which corresponds
to the Indian Standard Time (IST). This ensures that any date and time operations in the PHP script
will use the correct timezone. */
date_default_timezone_set('Asia/Kolkata');
// Site URL
const BASE_URL = 'http://localhost:3000/';
/* The code block you provided is used to configure the database connection based on the environment. */
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

/**
 * The function connects to a MySQL database using PDO and returns the PDO instance.
 *
 * @return an instance of the PDO class, which represents a connection to the database.
 */
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

/* The line ` = connectToDB();` is calling the `connectToDB()` function and assigning its return
value to the variable ``. */
$db = connectToDB();

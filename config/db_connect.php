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

// Timezone Configuration
date_default_timezone_set('Asia/Kolkata');

//Function to connect to the database using PDO
function connectToDB()
{
  try {
    // Create a new PDO instance
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Enable error handling
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Set default fetch mode to associative array
      PDO::ATTR_EMULATE_PREPARES => false, // Disable prepared statement emulation
    ];
      return new PDO($dsn, DB_USER, DB_PASS, $options);
  } catch (PDOException $e) {
    // Handle any errors that occur during the connection
    die("Database connection failed: " . $e->getMessage());
  }
}


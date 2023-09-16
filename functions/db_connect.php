<?php

/*
 * Database connection settings
 * Database Name : listify_db
 */

// Configure error reporting settings in PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set the default timezone for date and time functions in PHP
date_default_timezone_set('Asia/Kolkata');

// Define the base URL for the site
const BASE_URL = 'http://localhost:3000/';

/**
 * Connects to a MySQL database using PDO and returns the PDO instance.
 *
 * @return PDO An instance of the PDO class, which represents a connection to the database.
 * @throws PDOException If the connection to the database fails.
 */
function connectToDB(): PDO
{
  // Configure the database connection based on the environment
  $ENV = $_ENV['ENV'] ?? 'DEV';
  $DB_HOST = 'localhost';
  $DB_PORT = '3306';
  $DB_NAME = 'listify_db';
  $DB_USER = 'root';
  $DB_PASS = '';

  if ($ENV === 'PROD') {
    $DB_HOST = $_ENV['AZURE_MYSQL_HOST'];
    $DB_PORT = $_ENV['AZURE_MYSQL_PORT'];
    $DB_NAME = $_ENV['AZURE_MYSQL_DBNAME'];
    $DB_USER = $_ENV['AZURE_MYSQL_USERNAME'];
    $DB_PASS = $_ENV['AZURE_MYSQL_PASSWORD'];
  }

  $dsn = "mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_NAME;charset=utf8mb4";
  $options = [
    PDO::ATTR_EMULATE_PREPARES => false, // turn off emulation mode for "real" prepared statements
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // turn on errors in the form of exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // make the default fetch be an associative array
  ];

  try {
    return new PDO($dsn, $DB_USER, $DB_PASS, $options);
  } catch (PDOException $e) {
    die('Error connecting to the database server: ' . $e->getMessage());
  }
}

// Connect to the database
$db = connectToDB();

// Connect to the database
$db = connectToDB();

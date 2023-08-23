<?php
/*
  *This file will contain Authentication helper functions that will be used throughout the application
  List of functions:
    hashPassword() - Hash the password using bcrypt algorithm with a cost of 10
    verifyPassword() - Verify the password
    signIn() - signIn the user
*/

// Include the database connection file
include_once './../db_connect.php';

// Function to hash the password using PASSWORD_ARGON2ID algorithm with a cost of 12 memory cost of 2048 and time cost of 4 and returns the hashed password as a string or FALSE on failure.
function hashPassword($password): string
{
  $options = [
    'cost' => 12,
    'memory_cost' => 2048,
    'time_cost' => 4,
  ];
  return password_hash($password, PASSWORD_ARGON2ID, $options);
}

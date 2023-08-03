<?php
// Include the auth functions file
include_once './auth_functions.php';

// Function to reset the password
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$token = $_POST['token'];
$newPassword = $_POST['new_password'];

try {
resetPassword($token, $newPassword);
echo "Password reset successfully.";
} catch (Exception $e) {
echo "Error: " . $e->getMessage();
}
}

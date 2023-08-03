<?php
// Include the auth functions file
include_once './auth_functions.php';

// Function to initiate the password reset
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    try {
        initiatePasswordReset($email);
        echo "An email with instructions for password reset has been sent.";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

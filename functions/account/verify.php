<?php
// Include functions file
require_once __DIR__ . '/../functions.php';

try {
  // Check if email and code parameters are set
  if (!isset($_GET['email'], $_GET['code'])) {
    throw new Exception('Invalid verification link.');
  }

  $email = clean($_GET['email']);
  $code = clean($_GET['code']);

  // Check if email and code are valid
  if (empty($email) || empty($code)) {
    throw new Exception('Invalid verification link.');
  }

  // Check if the email is valid
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    throw new Exception('Invalid email. Please enter a valid email.');
  }

  // Check if the email exists in the database
  $sql = "SELECT * FROM users WHERE email = ?";
  $stmt = $db->prepare($sql);
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    throw new Exception('Invalid verification link.');
  }

  // Check if the email is already verified
  if ($user['verified'] === '1') {
    throw new Exception('Email already verified.');
  }

  // Check if the code is valid
  if ($code !== $user['code']) {
    throw new Exception('Invalid verification link.');
  }

  // Update the user's verified status
  $sql = "UPDATE users SET verified = '1' WHERE email = ?";
  $stmt = $db->prepare($sql);
  $stmt->execute([$email]);

  // Set the success message
  $_SESSION['successsession'] = 'Email verified successfully. You can now login.';
  redirect('account.php');
  exit();
} catch (Exception $e) {
  $_SESSION['errorsession'] = $e->getMessage();
  redirect('login.php');
  exit();
}

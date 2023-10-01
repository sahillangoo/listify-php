<?php
/*
 * This file handles the delete listing request and deletes the listing and its reviews from the database.
 */

require_once __DIR__ . '/../functions.php';

// Check if the user is logged in
if (!isAuthenticated()) {
  $_SESSION['errorsession'] = 'You need to login to delete a listing';
  redirect('signin.php');
  exit;
}

// Check if the user is active
if ($_SESSION['status'] === 'inactive') {
  $_SESSION['errorsession'] = 'You are not active. Please contact admin to activate your account.';
  redirect('signin.php');
  exit;
}

// Get the user id from the session
$user_id = sanitize($_SESSION['user_id']);

// Get the listing id from the request
$listing_id = isset($_GET['listing_id']) ? sanitize($_GET['listing_id']) : null;

if (!$listing_id) {
  $_SESSION['errorsession'] = "Invalid request";
  redirect('account.php');
  exit;
}

try {
  $db->beginTransaction();

  // Check if the user owns the listing
  $sql = 'SELECT * FROM listings WHERE id = :listing_id AND user_id = :user_id';
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':listing_id', $listing_id);
  $stmt->bindParam(':user_id', $user_id);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$result) {
    $_SESSION['errorsession'] = "You don't have permission to delete this listing";
    redirect('account.php');
    exit;
  }

  // Delete the reviews associated with the listing
  $sql = 'DELETE FROM reviews WHERE listing_id = :listing_id';
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':listing_id', $listing_id);
  $stmt->execute();

  // Delete the listing
  $sql = 'DELETE FROM listings WHERE id = :listing_id';
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':listing_id', $listing_id);
  $stmt->execute();

  $db->commit();

  // Delete the image from the uploads folder
  $image_path = __DIR__ . '/../../uploads/business_images/' . $result['image'];
  if (file_exists($image_path)) {
    unlink($image_path);
  } else {
    // Log the error message
    $log_file = __DIR__ . '/../../logs/error_logs.txt';
    $log_message = "File not found: " . $image_path . "\n";
    file_put_contents($log_file, $log_message, FILE_APPEND);
  }

  $_SESSION['successsession'] = "The listing has been deleted successfully";
  redirect('account.php');
  exit;
} catch (PDOException $e) {
  $db->rollBack();
  $_SESSION['errorsession'] = "An error occurred while deleting the listing: " . $e->getMessage();
  redirect('account.php');
  exit;
}
?>

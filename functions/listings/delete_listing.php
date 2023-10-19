<?php

/**
 * File: delete_listing.php
 * Description: Deletes a listing from the database
 * Author: SahilLangoo
 * Last modified: 15/10/2023
 */

require_once __DIR__ . '/../functions.php';

// Check if the user is logged in and active
if (!isAuthenticated()) {
  $_SESSION['errorsession'] = 'You need to login to delete a listing';
  redirect('signin.php');
  exit;
} elseif ($_SESSION['status'] === 'inactive') {
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
    throw new Exception("You don't have permission to delete this listing");
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

  // Delete the image from the uploads folder
  if ($result['image']) {
    $image_name = $result['image'];
    $image_path = __DIR__ . '/../../uploads/business_images/' . $image_name;
    if (file_exists($image_path)) {
      if (unlink($image_path)) {
        // File deleted successfully
      } else {
        throw new Exception("Failed to delete the image");
      }
    } else {
      // Log the error message
      $log_file = __DIR__ . '/../../logs/error_logs.txt';
      $log_message = "File not found: " . $image_path . "\n";
      file_put_contents($log_file, $log_message, FILE_APPEND);
    }
  }

  $db->commit();

  $_SESSION['successsession'] = "The listing has been deleted successfully";
  redirect('account.php');
  exit;
} catch (PDOException $e) {
  $db->rollBack();
  $_SESSION['errorsession'] = "An error occurred while deleting the listing: " . $e->getMessage();
  redirect('account.php');
  exit;
} catch (Exception $e) {
  $db->rollBack();
  $_SESSION['errorsession'] = $e->getMessage();
  redirect('account.php');
  exit;
}


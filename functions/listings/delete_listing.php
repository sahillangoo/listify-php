<?php
/*
  *This file will handle the delete listing request and delete the listing and its reviews from the database

*/

// Include the database connection file
include_once './../db_connect.php';
// Include the functions file
include_once './../functions.php';

// Check if the user is logged in and has a valid session
if (!isset($_SESSION['user_id'])) {
  $_SESSION['errorsession'] = "Please sign in to delete a listing";
  redirect('signin.php');
}

// Check if the request method is POST and the delete_listing parameter is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_listing'])) {
  $user_id = $_SESSION['user_id'];
  $listing_id = $_POST['listing_id'];

  // Check if the user owns the listing
  try {
    $sql = 'SELECT * FROM listings WHERE id = :listing_id AND user_id = :user_id';
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':listing_id', $listing_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
      $_SESSION['errorsession'] = "You don't have permission to delete this listing";
      redirect('account.php');
    } else {
      // Delete the listing and its reviews from the database
      try {
        $db->beginTransaction();

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

        $_SESSION['successsession'] = "The listing has been deleted successfully";
        redirect('account.php');
      } catch (PDOException $e) {
        $db->rollBack();
        $_SESSION['errorsession'] = $e->getMessage();
        redirect('account.php');
      } finally {
        $stmt = null;
        $db = null;
      }
    }
  } catch (PDOException $e) {
    $_SESSION['errorsession'] = $e->getMessage();
    redirect('account.php');
  } finally {
    $stmt = null;
  }
} else {
  $_SESSION['errorsession'] = "Invalid request";
  redirect('account.php');
}

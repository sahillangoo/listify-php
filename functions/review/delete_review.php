<?php
/*
  *This file will handle the delete review request and delete the review from the database

*/
// include functions file
require_once __DIR__ . '/../functions.php';


// Check if the user is logged in and has a valid session
if (!isset($_SESSION['user_id'])) {
  $_SESSION['errorsession'] = "Please sign in to delete a review";
  redirect('signin.php');
}

// Check if the request method is POST and the delete_review parameter is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_review'])) {
  $user_id = $_SESSION['user_id'];
  $review_id = $_POST['review_id'];

  // Check if the user has a review with the given ID
  try {
    $sql = 'SELECT * FROM reviews WHERE id = :review_id AND user_id = :user_id';
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':review_id', $review_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
      $_SESSION['errorsession'] = "You don't have permission to delete this review";
      redirect('account.php');
    } else {
      // Delete the review from the database
      try {
        $sql = 'DELETE FROM reviews WHERE id = :review_id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':review_id', $review_id);
        $stmt->execute();

        $_SESSION['successsession'] = "The review has been deleted successfully";
        redirect('account.php');
      } catch (PDOException $e) {
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

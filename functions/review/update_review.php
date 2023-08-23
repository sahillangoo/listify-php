<?php
/*
  *This file will handle the update review request and update the review in the database

*/

// Include the database connection file
include_once './../db_connect.php';
// Include the functions file
include_once './../functions.php';

// Check if the user is logged in and has a valid session
if (!isset($_SESSION['user_id'])) {
  $_SESSION['errorsession'] = "Please sign in to update a review";
  redirect('signin.php');
}

// Check if the request method is POST and the update_review parameter is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_review'])) {
  $user_id = $_SESSION['user_id'];
  $review_id = $_POST['review_id'];
  $rating = $_POST['rating'];
  $comment = $_POST['comment'];

  // Check if the user has a review with the given ID
  try {
    $sql = 'SELECT * FROM reviews WHERE id = :review_id AND user_id = :user_id';
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':review_id', $review_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
      $_SESSION['errorsession'] = "You don't have permission to update this review";
      redirect('account.php');
    } else {
      // Update the review in the database
      try {
        $sql = 'UPDATE reviews SET rating = :rating, comment = :comment WHERE id = :review_id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);
        $stmt->bindParam(':review_id', $review_id);
        $stmt->execute();

        $_SESSION['successsession'] = "The review has been updated successfully";
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

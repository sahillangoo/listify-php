<?php
// include DB file
include_once '../db_connect.php';
// include config file
include_once './includes/_config.php';
// Insert the review into the database
$stmt = $db->prepare('INSERT INTO reviews (comment, user_id, business_id) VALUES (?, ?, ?)');
if ($stmt->execute([$_POST['comment'], $_SESSION['user_id'], $_POST['business_id']])) {
  // Redirect back to the listing page
  header('Location: get_listing.php?id=' . $_POST['business_id']);
} else {
  // Redirect back to the listing page
  header('Location: get_listing.php?id=' . $_POST['business_id'] . '&error=' . urlencode('There was an error while saving your review. Please try again later.'));
}

// Exit the script
exit;

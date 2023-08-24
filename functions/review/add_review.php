<?php
// include functions file
require_once __DIR__ . '/../functions.php';


if (isset($_POST['review'])) {
  // get the form data
  $rating = sanitize($_POST['rating']);
  $review = sanitize($_POST['review']);
  $listing_id = sanitize($_POST['listing_id']);
  $user_id = sanitize($_SESSION['user_id']);
  $createdAt = date('Y-m-d H:i:s');
  // Check for empty fields
  if (empty($rating) || empty($review)) {
    $_SESSION['errorsession'] = "Please fill out all fields.";
    redirect('listing.php?listing=' . $listing_id);
    exit();
  }
  // Validate rating and review
  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    $_SESSION['errorsession'] = "Rating must be a number between 1 and 5.";
    redirect('listing.php?listing=' . $listing_id);
    exit();
  }
  if (strlen($review) > 150) {
    $_SESSION['errorsession'] = "Review must be 150 characters or less.";
    redirect('listing.php?listing=' . $listing_id);
    exit();
  }
  // Check if the user has already left a review on this listing
  $stmt = $db->prepare("SELECT * FROM reviews WHERE user_id=:user_id AND listing_id=:listing_id");
  $stmt->bindParam(':user_id', $user_id);
  $stmt->bindParam(':listing_id', $listing_id);
  $stmt->execute();
  $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if (count($reviews) > 0) {
    $_SESSION['errorsession'] = "You have already left a review on this listing.";
    redirect('listing.php?listing=' . $listing_id);
    exit();
  }

  // Insert review into database using PDO
  $stmt = $db->prepare("INSERT INTO reviews (rating, comment, createdAt, user_id, listing_id) VALUES (:rating, :comment, :createdAt, :user_id, :listing_id)");
  $stmt->bindParam(':rating', $rating);
  $stmt->bindParam(':comment', $review);
  $stmt->bindParam(':createdAt', $createdAt);
  $stmt->bindParam(':user_id', $user_id);
  $stmt->bindParam(':listing_id', $listing_id);
  $stmt->execute();
  // Redirect to the listing page
  redirect('listing.php?listing=' . $listing_id);
  exit();
}

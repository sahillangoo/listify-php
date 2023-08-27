<?php
// Get Featured Listings API
require_once '../functions/functions.php';

try {
  // Prepare SQL statement
  $sql = "SELECT l.user_id, l.businessName, l.description, l.category, l.featured, l.active, l.city, l.displayImage, COUNT(r.id) AS reviewsCount, AVG(r.rating) AS avg_rating, l.createdAt, l.updatedAt, u.username, COUNT(r.id) AS reviews_count
    FROM listings l
    JOIN users u ON l.user_id = u.id
    LEFT JOIN reviews r ON l.id = r.listing_id
    WHERE l.active = 1
    GROUP BY l.id";

  // Execute SQL statement
  $stmt = $db->prepare($sql);
  $stmt->execute();

  // Fetch results
  $featured_listings = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Format results
  $formatted_listings = array();
  foreach ($featured_listings as $listing) {
    $formatted_listing = array(
      'user_id' => $listing['user_id'],
      'businessName' => $listing['businessName'],
      'description' => $listing['description'],
      'category' => $listing['category'],
      'featured' => $listing['featured'],
      'active' => $listing['active'],
      'city' => $listing['city'],
      'displayImage' => $listing['displayImage'],
      'reviewsCount' => $listing['reviewsCount'],
      'avg_rating' => $listing['avg_rating'],
      'createdAt' => $listing['createdAt'],
      'updatedAt' => $listing['updatedAt'],
      'username' => $listing['username'],
      'reviews_count' => $listing['reviews_count']
    );
    array_push($formatted_listings, $formatted_listing);
  }

  // Return JSON response
  header('Content-Type: application/json');
  echo json_encode($formatted_listings);
} catch (PDOException $e) {
  // Return error
  header('Content-Type: application/json', true, 500);
  echo json_encode(array('error' => $e->getMessage()));
  exit;
}

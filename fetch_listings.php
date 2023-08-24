<?php
// Get Listing API
// include functions file
require_once './functions/functions.php';

try {
  // Set default values for pagination
  $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
  $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
  $offset = ($page - 1) * $limit;

  // Fetch the listings with their user, average rating, and number of reviews
  $stmt = $db->prepare('SELECT l.*, AVG(r.rating) AS rating, COUNT(r.id) AS reviewsCount, u.username
                        FROM listings l
                        LEFT JOIN reviews r ON l.id = r.listing_id
                        LEFT JOIN users u ON l.user_id = u.id
                        GROUP BY l.id
                        LIMIT :limit OFFSET :offset');
  $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
  $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
  $stmt->execute();
  $listings = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Return the listings in JSON format
  header('Content-Type: application/json');
  echo json_encode($listings);
} catch (PDOException $e) {
  // Log the error message
  error_log($e->getMessage());

  // Return error message
  header('Content-Type: application/json');
  echo json_encode(['error' => 'An error occurred while fetching the listings.']);
}

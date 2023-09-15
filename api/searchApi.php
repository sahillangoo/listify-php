<?php
// include functions file
include_once './../functions/functions.php';

try {
  // Get search query from GET parameter, if it exists
  if (isset($_GET['q'])) {
    $searchQuery = $_GET['q'];
  } else {
    // Return error message if no search query is provided
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'No search query provided'));
    exit;
  }

  // Make sure search query is not empty
  if (empty(sanitize($searchQuery))) {
    // Return error message if search query is empty
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Search query is empty must enter a search query'));
    exit;
  }
  // strlen($searchQuery) < 3
  if (strlen($searchQuery) < 3) {
    // Return error message if search query is too short
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Search query too short, must be at least 3 characters long'));
    exit;
  }

  // Sanitize search query parameter
  $searchQuery = preg_replace('/[^a-zA-Z0-9\s]/', '', $searchQuery);

  $stmt = $db->prepare('SELECT l.id, l.businessName, l.category, l.city, l.address, AVG(r.rating) AS avg_rating, COUNT(r.id) AS reviews_count
  FROM listings l
  LEFT JOIN reviews r ON l.id = r.listing_id
  WHERE l.businessName LIKE :searchQuery
  GROUP BY l.id
  LIMIT 6');

  // Bind search query parameter
  $stmt->bindValue(':searchQuery', '%' . $searchQuery . '%', PDO::PARAM_STR);

  // Execute SQL statement
  $stmt->execute();

  // Fetch search results and return as JSON objects
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
  if (count($results) === 0) {
    // Return error message if no search results are found
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'No listings found'));
  } else {
    header('Content-Type: application/json');
    echo json_encode($results);
  }
} catch (PDOException $e) {
  // Handle database exceptions and return error message
  header('Content-Type: application/json');
  echo json_encode(array('error' => $e->getMessage()));
} catch (Exception $e) {
  // Handle other exceptions and return error message
  header('Content-Type: application/json');
  echo json_encode(array('error' => $e->getMessage()));
}

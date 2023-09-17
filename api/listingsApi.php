<?php
/*
Path: api/listingsApi.php

File that handles the AJAX request for fetching listings from the database and returning them as JSON data to the client side.

*/

// Include functions file
require_once './../functions/functions.php';

try {
  // Get the current page number from the query string
  $page = $_GET['page'] ?? 1;

  // Set the number of listings per page
  $limit = 12;

  // Calculate the offset based on the current page number and limit
  $offset = ($page - 1) * $limit;

  // Set the default sorting option to featured
  $sort = 'featured';

  // Check if a sorting option is specified in the GET request
  if (isset($_GET['featured'])) {
    $sort = 'featured DESC';
  } elseif (isset($_GET['most_rated'])) {
    $sort = 'avg_rating DESC';
  } elseif (isset($_GET['most_reviewed'])) {
    $sort = 'reviews_count DESC';
  }

  // Check if a category is specified in the GET request
  $category = $_GET['category'] ?? null;

  // Check if a city is specified in the GET request
  $city = $_GET['city'] ?? null;

  // Check if a search query is specified in the GET request
  $searchQuery = $_GET['query'] ?? null;

  if ($searchQuery) {
    // Sanitize search query parameter
    $searchQuery = sanitize($searchQuery);

    // Make sure search query is not empty or too short
    if (empty($searchQuery)) {
      throw new Exception('Search query is empty. Must enter a search query.');
    } elseif (strlen($searchQuery) < 3) {
      throw new Exception('Search query too short. Must be at least 3 characters long.');
    }

    // Fetch the listings from the database based on search query
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
  } else {
    // Fetch the listings from the database based on category, city, and sorting options
    $sql = "SELECT l.id, l.businessName, SUBSTRING(l.description, 1, 120) AS description, l.category, l.featured, l.city, l.displayImage, u.username AS user, COALESCE(AVG(r.rating), 0) AS avg_rating, COUNT(r.id) AS reviews_count
            FROM listings l
            JOIN users u ON l.user_id = u.id
            LEFT JOIN reviews r ON l.id = r.listing_id
            WHERE l.active = 1";
    if ($category) {
      $sql .= " AND l.category = :category";
    }
    if ($city) {
      $sql .= " AND l.city = :city";
    }
    $sql .= " GROUP BY l.id
              ORDER BY $sort
              LIMIT :limit OFFSET :offset";
    $stmt = $db->prepare($sql);
    if ($category) {
      $stmt->bindParam(':category', $category);
    }
    if ($city) {
      $stmt->bindParam(':city', $city);
    }
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
  }

  $stmt->execute();
  $listings = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Get the total number of listings
  if ($searchQuery) {
    $sql = "SELECT COUNT(*) FROM listings WHERE businessName LIKE :searchQuery";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':searchQuery', '%' . $searchQuery . '%', PDO::PARAM_STR);
  } else {
    $sql = "SELECT COUNT(*) FROM listings WHERE active = 1";
    if ($category) {
      $sql .= " AND category = :category";
    }
    if ($city) {
      $sql .= " AND city = :city";
    }
    $stmt = $db->prepare($sql);
    if ($category) {
      $stmt->bindParam(':category', $category);
    }
    if ($city) {
      $stmt->bindParam(':city', $city);
    }
  }
  $stmt->execute();
  $total = $stmt->fetchColumn();

  // Format the listings as JSON
  $output = array(
    'total' => $total,
    'listings' => $listings
  );
  $json_output = json_encode($output);

  // Output the JSON data
  header('Content-Type: application/json');
  echo $json_output;
} catch (PDOException $e) {
  // Handle database errors
  http_response_code(500);
  echo json_encode(array('error' => 'Database error: ' . $e->getMessage()));
} catch (Throwable $e) {
  // Handle other errors
  http_response_code(400);
  echo json_encode(array('error' => 'Error: ' . $e->getMessage()));
}

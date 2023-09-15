<?php
// include functions file
include_once './../functions/functions.php';

// Get the current page number from the query string
$page = isset($_GET['page']) ? $_GET['page'] : 1;

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

// Fetch the listings from the database
$stmt = $db->prepare("
  SELECT l.id, l.businessName, SUBSTRING(l.description, 1, 120) AS description, l.category, l.featured, l.city, l.displayImage, u.username AS user, COALESCE(AVG(r.rating), 0) AS avg_rating, COUNT(r.id) AS reviews_count
  FROM listings l
  JOIN users u ON l.user_id = u.id
  LEFT JOIN reviews r ON l.id = r.listing_id
  WHERE l.active = 1
  GROUP BY l.id
  ORDER BY $sort
  LIMIT :limit OFFSET :offset
");
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$listings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total number of listings
$stmt = $db->prepare("SELECT COUNT(*) FROM listings WHERE active = 1");
$stmt->execute();
$total = $stmt->fetchColumn();

// Format the listings as JSON
$output = array(
  'total' => $total,
  'listings' => array()
);
foreach ($listings as $listing) {
  $output['listings'][] = array(
    'id' => $listing['id'],
    'businessName' => $listing['businessName'],
    'description' => $listing['description'],
    'category' => $listing['category'],
    'featured' => $listing['featured'],
    'city' => $listing['city'],
    'displayImage' => $listing['displayImage'],
    'user' => $listing['user'],
    'avg_rating' => $listing['avg_rating'],
    'reviews_count' => $listing['reviews_count']
  );
}
$json_output = json_encode($output);

// Output the JSON data
header('Content-Type: application/json');
echo $json_output;

// test url with sort options: http://localhost:8888/api/listingsApi.php?featured=1

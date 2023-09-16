<?php
/*
functions/functions.php
File Contents:
1. redirect()
2. clean()
3. sanitize()
4. hashPassword()
5. checkHttps()
6. isAuthenticated()
7. isAdmin()
8. getFeaturedListings()
9. displayListing()
10. getUserListings()
11. getUserReviews()

*/

// Start the session if it has not already started
session_start();

// Include DB file
require_once __DIR__ . '/../functions/db_connect.php';

/**
 * Redirects the user to the specified URL.
 *
 * @param string $url The URL to redirect to.
 * @return void
 */
function redirect(string $url): void
{
  header('Location: ' . BASE_URL . $url);
  exit;
}

/**
 * Cleans the specified data by removing extra spaces, backslashes, and HTML tags.
 *
 * @param string $data The data to clean.
 * @return string The cleaned data.
 */
function clean(string $data): string
{
  return strip_tags(stripslashes(trim($data)));
}

/**
 * Sanitizes the specified data by removing extra spaces, backslashes, and HTML tags, and converting special characters to HTML entities.
 *
 * @param string $data The data to sanitize.
 * @return string The sanitized data.
 */
function sanitize(string $data): string
{
  return htmlspecialchars(clean($data));
}

// Generate a CSRF token and store it in the user's session
if (!isset($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}

/**
 * Hashes the specified password using the Argon2id algorithm.
 *
 * @param string $password The password to hash.
 * @return string The hashed password.
 */
function hashPassword(string $password): string
{
  $options = [
    'cost' => 12,
    'memory_cost' => 2048,
    'time_cost' => 4,
  ];
  return password_hash($password, PASSWORD_ARGON2ID, $options);
}

/**
 * Checks if the current request is using HTTPS.
 *
 * @return bool True if the request is using HTTPS, false otherwise.
 */
function checkHttps(): bool
{
  return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
}

/**
 * Checks if the user is authenticated.
 *
 * @return bool True if the user is authenticated, false otherwise.
 */
function isAuthenticated(): bool
{
  return !empty($_SESSION["authenticated"]) && $_SESSION["authenticated"] === true;
}

/**
 * Checks if the user is an admin.
 *
 * @return bool True if the user is an admin, false otherwise.
 */
function isAdmin(): bool
{
  return isset($_SESSION["role"]) && $_SESSION["role"] === 'admin';
}

/**
 * Retrieves the featured listings for the current page and the total number of featured listings from the database.
 *
 * @param PDO $db The database connection.
 * @param int $maxPerPage The maximum number of listings per page.
 * @param int $offset The offset of the current page.
 * @return array An array containing the featured listings for the current page and the total number of featured listings.
 */
function getFeaturedListings(PDO $db, int $maxPerPage, int $offset): array
{
  $stmt = $db->prepare("SELECT SQL_CALC_FOUND_ROWS l.id, l.user_id, l.businessName, l.description, l.category, l.featured, l.active, l.city, l.displayImage, COUNT(r.id) AS reviewsCount, AVG(r.rating) AS avg_rating, l.createdAt, l.updatedAt, u.username, COUNT(r.id) AS reviews_count
      FROM listings l
      JOIN users u ON l.user_id = u.id
      LEFT JOIN reviews r ON l.id = r.listing_id
      WHERE l.active = 1 AND l.featured = 1
      GROUP BY l.id
      ORDER BY l.createdAt DESC
      LIMIT :maxPerPage OFFSET :offset");
  $stmt->bindParam(':maxPerPage', $maxPerPage, PDO::PARAM_INT);
  $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
  $stmt->execute();
  $listings = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $stmt = $db->prepare("SELECT FOUND_ROWS()");
  $stmt->execute();
  $total = $stmt->fetchColumn();

  return ['listings' => $listings, 'total' => $total];
}

/**
 * Displays the specified listing.
 *
 * @param array $listing The listing to display.
 * @return void
 */
function displayListing(array $listing): void
{
  echo <<<HTML
          <div class="col-md-3 col-lg-3 mb-4">
            <div class="card card-frame">
              <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                <a href="./listing.php?listing={$listing['id']}" class="d-block">
                  <img src="./uploads/business_images/{$listing['displayImage']}" class="img-fluid border-radius-lg move-on-hover" alt="{$listing['businessName']}" loading="lazy">
                </a>
              </div>
              <div class="card-body pt-2">
                <div class="d-flex justify-content-between align-items-center my-2">
                  <span class="text-uppercase text-xxs font-weight-bold"><i class="fa-solid fa-shop"></i> {$listing['category']}</span>
                  <span class="text-uppercase text-xxs font-weight-bold "><i class="fa-solid fa-location-dot"></i> {$listing['city']}</span>
                </div>
                <div class="d-flex justify-content-between ">
                  <a href="./listing.php?listing={$listing['id']}" class="card-title h6 d-block text-gradient text-primary font-weight-bold ">{$listing['businessName']}</a>
                  <span class="text-gradient text-warning text-uppercase text-xs mt-1"><i class="fa-solid fa-star"></i> {$listing['avg_rating']} ({$listing['reviews_count']})</span>
                </div>
                <p class="card-description text-sm mb-3" id="truncate" >{$listing['description']}</p>
                <p class="mb-2 text-xxs font-weight-bolder text-warning text-gradient text-uppercase"><span>By―</span> {$listing['username']}</p>
                <div class="d-flex justify-content-start my-2">
                  <a href="./listing.php?listing={$listing['id']}" class="text-primary text-sm icon-move-right">View details <i class="fas fa-arrow-right text-sm" aria-hidden="true"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        HTML;
}

/**
 * Retrieves the listings for the specified user.
 *
 * @param PDO $db The database connection.
 * @param int $user_id The ID of the user.
 * @return array The listings for the specified user.
 */
function getUserListings(PDO $db, int $user_id): array
{
  $listings_sql = "SELECT l.*, u.username FROM listings l JOIN users u ON l.user_id = u.id WHERE l.active = 1 AND user_id = :user_id ORDER BY l.createdAt DESC";
  $reviews_sql = "SELECT listing_id, COUNT(id) AS reviews_count, AVG(rating) AS avg_rating FROM reviews WHERE listing_id IN (SELECT id FROM listings WHERE user_id = :user_id) GROUP BY listing_id";

  $listings_stmt = $db->prepare($listings_sql);
  $listings_stmt->execute(['user_id' => $user_id]);
  $listings = $listings_stmt->fetchAll();

  $reviews_stmt = $db->prepare($reviews_sql);
  $reviews_stmt->execute(['user_id' => $user_id]);
  $reviews = $reviews_stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_UNIQUE);

  foreach ($listings as &$listing) {
    $listing['reviews_count'] = $reviews[$listing['id']]['reviews_count'] ?? 0;
    $listing['avg_rating'] = $reviews[$listing['id']]['avg_rating'] ?? null;
  }

  return $listings;
}

/**
 * Retrieves the reviews for the listings of the specified user.
 *
 * @param PDO $db The database connection.
 * @param int $user_id The ID of the user.
 * @return array The reviews for the listings of the specified user.
 */
function getUserReviews(PDO $db, int $user_id): array
{
  $sql = "SELECT r.*, l.businessName, l.displayImage FROM reviews r JOIN listings l ON r.listing_id = l.id WHERE l.user_id = :user_id ORDER BY r.createdAt DESC";
  $stmt = $db->prepare($sql);
  $stmt->execute(['user_id' => $user_id]);
  return $stmt->fetchAll();
}

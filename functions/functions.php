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
 * @param string|null $data The data to clean.
 * @return string The cleaned data.
 */
function clean(?string $data): string
{
  if ($data === null) {
    return '';
  }
  // encoding
  $data = mb_convert_encoding($data, 'UTF-8', 'UTF-8');
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
 * Retrieves the listings for the specified user.
 *
 * @param PDO $db The database connection.
 * @param int $user_id The ID of the user.
 * @return array The listings for the specified user.
 */
function getUserListings(PDO $db, int $user_id): array
{
  // Query to retrieve listings for the specified user
  $listings_sql = "SELECT l.*, u.username FROM listings l JOIN users u ON l.user_id = u.id WHERE user_id = :user_id ORDER BY l.createdAt ASC";
  // Query to retrieve reviews for listings of the specified user
  $reviews_sql = "SELECT listing_id, COUNT(id) AS reviews_count, AVG(rating) AS avg_rating FROM reviews WHERE listing_id IN (SELECT id FROM listings WHERE user_id = :user_id) GROUP BY listing_id";

  // Prepare and execute the listings query
  $listings_stmt = $db->prepare($listings_sql);
  $listings_stmt->execute(['user_id' => $user_id]);
  $listings = $listings_stmt->fetchAll();

  // Prepare and execute the reviews query
  $reviews_stmt = $db->prepare($reviews_sql);
  $reviews_stmt->execute(['user_id' => $user_id]);
  $reviews = $reviews_stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_UNIQUE);

  // Loop through the listings and add reviews count and average rating
  foreach ($listings as &$listing) {
    $listing['reviews_count'] = $reviews[$listing['id']]['reviews_count'] ?? 0;
    $listing['avg_rating'] = $reviews[$listing['id']]['avg_rating'] ?? null;
  }

  return $listings;
}

/**
 * Displays the specified listing.
 *
 * @param array $listing The listing to display.
 * @return void
 */
function displayListing(array $listing): void
{
  // Check if the listing is inactive
  $inactive = '';
  if ($listing['active'] === 0) {
    $inactive = '<span class="text-danger font-weight-bolder text-xs"> (Inactive)</span>';
  }
  echo <<<HTML
          <div class="col-6 col-md-3 col-lg-3 mb-4">
                <div class="card card-frame">
                  <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                    <a href="./listing.php?listing={$listing['id']}" class="d-block">
                      <img src="./uploads/business_images/{$listing['displayImage']}" class="img-fluid border-radius-lg move-on-hover" alt="{$listing['displayImage']}" loading="lazy">
                    </a>
                  </div>
                  <div class="card-body pt-2">
                    <div class="d-flex justify-content-between align-items-center my-md-2">
                      <span class="text-capitalize text-xs font-weight-bold text-info text-gradient"><i class="fa-solid fa-shop"></i> {$listing['category']}</span>
                      <span class="text-capitalize text-xs font-weight-bold text-info text-gradient"><i class="fa-solid fa-location-dot"></i> {$listing['city']}</span>
                    </div>
                    <span class="d-md-none text-gradient text-warning text-uppercase text-xxs my-md-2"><i class="fa-solid fa-star"></i> {$listing['avg_rating']} ({$listing['reviews_count']})</span>
                    $inactive
                    <div class="d-flex justify-content-between ">
                      <a href="./listing.php?listing={$listing['id']}" class="card-title h6 d-block text-gradient text-primary font-weight-bold ">{$listing['businessName']}
                        </a>
                      <span class="d-none d-md-block text-gradient text-warning text-uppercase text-xs mt-1"><i class="fa-solid fa-star"></i> {$listing['avg_rating']} ({$listing['reviews_count']})</span>
                    </div>
                    <div class="d-flex justify-content-between my-md-2">
                      <a href="./listing.php?listing={$listing['id']}" class="text-primary text-gradient text-xs icon-move-right ">view details <i class="fas fa-arrow-right " aria-hidden="true"></i>
                      </a>

                      <a href="./update-listing.php?id={$listing['id']}" class="btn btn-rounded btn-info btn-icon-only shadow move-on-hover"><i class="fa-solid fa-file-pen"></i></a>

                      <button
                        type="button"
                        class="deleteListingButton btn btn-rounded btn-danger btn-icon-only shadow move-on-hover"
                        data-listing-id="{$listing['id']}">
                        <i class="fa-solid fa-trash"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
        HTML;
}

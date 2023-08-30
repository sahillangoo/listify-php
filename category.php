<?php
// include functions file
include_once './functions/functions.php';

/**
 * Retrieves the slug listings for the current page and the total number of slug listings from the database.
 *
 * @param PDO $db The database connection.
 * @param string $slug The slug of the category.
 * @param int $maxPerPage The maximum number of listings per page.
 * @param int $offset The offset of the current page.
 * @return array An array containing the slug listings for the current page and the total number of slug listings.
 */
function getSlugListings(PDO $db, string $slug, int $maxPerPage, int $offset): array
{
  $stmt = $db->prepare("SELECT SQL_CALC_FOUND_ROWS l.id, l.user_id, l.businessName, l.description, l.category, l.featured, l.active, l.city, l.displayImage, COUNT(r.id) AS reviewsCount, AVG(r.rating) AS avg_rating, l.createdAt, l.updatedAt, u.username, COUNT(r.id) AS reviews_count
    FROM listings l
    JOIN users u ON l.user_id = u.id
    LEFT JOIN reviews r ON l.id = r.listing_id
    WHERE l.active = 1 AND l.category = :slug
    GROUP BY l.id
    ORDER BY l.createdAt ASC
    LIMIT :maxPerPage OFFSET :offset");
  $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
  $stmt->bindParam(':maxPerPage', $maxPerPage, PDO::PARAM_INT);
  $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
  $stmt->execute();
  $listings = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $stmt = $db->prepare("SELECT FOUND_ROWS()");
  $stmt->execute();
  $total = $stmt->fetchColumn();

  return ['listings' => $listings, 'total' => $total];
}

/*
  slug Page
  If this page gets slug id from the url, it will show the slug details to the user else it will redirect to the home page
*/
// check if the slug id is set in the url
if (!isset($_GET['slug']) || empty($_GET['slug']) || !is_string($_GET['slug']) || strlen($_GET['slug']) > 255 || !preg_match('/^[a-zA-Z0-9-]+$/', $_GET['slug'])) {
  redirect('404.php');
  exit();
} else {
  $slug = sanitize($_GET['slug']);
  $stmt = $db->prepare('SELECT COUNT(*) as count FROM listings WHERE category = :slug');
  $stmt->bindParam(':slug', $_GET['slug'], PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$result || $result['count'] == 0) {
    redirect('404.php');
    exit();
  }
}

?>
<!DOCTYPE html>
<html lang="en" itemscope itemtype="https://schema.org/WebPage">

<head>
  <title><?php ?> </title>
  <?php include_once './includes/_head.php'; ?>
</head>

<body class="index-page">
  <?php
  // include the header file
  include_once './includes/_navbar.php';
  ?>
  <div class="wrapper">
    <!-- ========== Start slug Listing Grid ========== -->
    <section class="py-5">
      <div class="container my-5">
        <h2 class="text-center text-capitalize"><?php echo $slug; ?> Business Listings</h2>
        <p class="text-center ">
          Here are the list of all the Businesses in <span class="text-primary text-capitalize"><?php echo $slug; ?></span> category.
        </p>
        <div class="row">
          <?php
          // Define the maximum number of slug listings per page
          $maxSlugListingsPerPage = 12;

          // Get the current page number from the query string
          $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

          // Calculate the offset based on the current page number and the number of listings per page
          $offset = ($currentPage - 1) * $maxSlugListingsPerPage;

          // Retrieve the slug listings for the current page and the total number of slug listings from the database
          $slugListings = getSlugListings($db, $slug, $maxSlugListingsPerPage, $offset);
          $totalSlugListings = $slugListings['total'];

          // Calculate the total number of pages based on the total number of slug listings and the number of listings per page
          $totalPages = ceil($totalSlugListings / $maxSlugListingsPerPage);

          // Loop through the slug listings and display each listing
          foreach ($slugListings['listings'] as $listing) {
            displayListing($listing);
          }

          // Check if there are any listings
          $hasListings = !empty($slugListings['listings']);
          if (!$hasListings) {
            // Display a message if there are no listings
            echo '<div class="col-md-12 text-center my-5"><p class="lead text-muted">No slug listings found</p></div>';
          }

          // Display the pagination links
          if ($totalPages > 1) {
            echo '<div class="col-md-12 text-center my-5">';
            echo '<ul class="pagination">';
            for ($i = 1; $i <= $totalPages; $i++) {
              $active = $i === $currentPage ? ' active' : '';
              echo '<li class="page-item' . $active . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
            }
            echo '</ul>';
            echo '</div>';
          }
          ?>
        </div>
      </div>
    </section>
    <!-- ========== End slug Listing Grid ========== -->
  </div>

  <?php
  // include the footer file
  include_once './includes/_footer.php';
  ?>
</body>

</html>

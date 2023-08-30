<?php
// include functions file
include_once './functions/functions.php';

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
 * Retrieves the recent activity listings from the database.
 *
 * @param PDO $db The database connection.
 * @return array An array containing the recent activity listings.
 */
function getRecentListings(PDO $db): array
{
  $stmt = $db->prepare("SELECT l.*, COUNT(r.id) AS reviews_count, AVG(r.rating) AS avg_rating, u.username FROM listings l LEFT JOIN reviews r ON l.id = r.listing_id JOIN users u ON l.user_id = u.id WHERE l.active = 1 GROUP BY l.id ORDER BY l.createdAt DESC LIMIT 8");
  $stmt->execute();
  $listings = $stmt->fetchAll(PDO::FETCH_ASSOC);

  return $listings;
}

?>
<!DOCTYPE html>
<html lang="en" itemscope itemtype="https://schema.org/WebPage">

<head>
  <title>Listify - Comprehansive Listing App</title>
  <?php include_once './includes/_head.php'; ?>
</head>

<body class="index-page">
  <?php
  /*
  Todo - fix text and add images
  */
  // include the header file
  include_once './includes/_navbar.php';
  ?>
  <!-- ========== Start Hero ========== -->
  <header class="header-2">
    <div class="page-header min-vh-100 relative" style="background-image: url('./assets/img/curved-images/curved.jpg')">
      <div class="container px-4 text-center">
        <div class="row">
          <div class="col-lg-2">
          </div>
          <div class="col-lg-8 text-center mx-auto">
            <p class="text-white text-sm mb-5">Search for a business by name, city, or category</p>
            <h2 class="text-white pt-3 mt-n5">Search Across Various Business</h2>
            <p class="lead text-white text-sm mt-3">"Bringing order to the digital chaos"</p>
            <div class="input-group my-4">
              <span class="input-group-text"><i class="fas fa-search" aria-hidden="true"></i></span>
              <label for="search-input" class="visually-hidden">Search for Business</label>
              <input class="form-control" id="search-input" name="search" placeholder="Search for Business" type="text">
              <button class="btn btn-outline-secondary" type="button" id="clear-search-input">
                <i class="fas fa-times" aria-hidden="true"></i>
                <span class="visually-hidden">Clear search input</span>
              </button>
            </div>
            <div class="list-group text-center align-items-center" id="search-results">
              <div id="search-spinner" class="spinner-border text-primary d-none" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>
          </div>
          <div class="col-lg-2"></div>
        </div>

        <div class="row justify-content-center">
          <p class="lead text-white text-capitalize font-weight-light my-3">Browse our top categories</p>

          <div class="col-auto text-center move-on-hover">
            <a href="./category.php?slug=restaurant">
              <div class="bg-white rounded-3 p-3">
                <img src="assets/img/svgs/icons8_restaurant.svg" alt="Restaurants" height="50px">
                <p class="text-primary font-weight-bold text-xs">Restaurants</p>
              </div>
            </a>
          </div>
          <div class="col-auto text-center move-on-hover">
            <a href="./category.php?slug=hospital">
              <div class=" bg-white rounded-3 p-3">
                <img src="assets/img/svgs/icons8_hospital_3.svg" alt="Hospitals" height="50px">
                <p class="text-primary font-weight-bold text-xs">Hospitals</p>
              </div>
            </a>
          </div>
          <div class="col-auto text-center move-on-hover">
            <a href="./category.php?slug=pharmacy">
              <div class="bg-white rounded-3 p-3">
                <img src="assets/img/svgs/icons8_pharmacy_shop.svg" alt="Pharmacy" height="50px">
                <p class="text-primary font-weight-bold text-xs">Pharmacys</p>
              </div>
            </a>
          </div>
          <div class="col-auto text-center move-on-hover">
            <a href="./category.php?slug=education">
              <div class="bg-white rounded-3 p-3">
                <img src="assets/img/svgs/icons8_school.svg" alt="Education" height="50px">
                <p class="text-primary font-weight-bold text-xs">Education</p>
              </div>
            </a>
          </div>
          <div class="col-auto text-center move-on-hover">
            <a href="./category.php?slug=atm">
              <div class="bg-white rounded-3 p-3">
                <img src="assets/img/svgs/icons8_atm.svg" alt="ATMs" height="50px">
                <p class="text-primary font-weight-bold text-xs">ATMs</p>
              </div>
            </a>
          </div>
          <div class="col-auto text-center move-on-hover">
            <a href="./category.php?slug=bank">
              <div class="bg-white rounded-3 p-3">
                <img src="assets/img/svgs/icons8_euro_bank_building_2.svg" alt="Banks" height="50px">
                <p class="text-primary font-weight-bold text-xs">Banks</p>
              </div>
            </a>
          </div>
        </div>
      </div>
      <div class="position-absolute w-100 z-index-1 bottom-0">
        <svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 40" preserveAspectRatio="none" shape-rendering="auto">
          <defs>
            <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
          </defs>
          <g class="moving-waves">
            <use xlink:href="#gentle-wave" x="48" y="-1" fill="rgba(255,255,255,0.40" />
            <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.35)" />
            <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.25)" />
            <use xlink:href="#gentle-wave" x="48" y="8" fill="rgba(255,255,255,0.20)" />
            <use xlink:href="#gentle-wave" x="48" y="13" fill="rgba(255,255,255,0.15)" />
            <use xlink:href="#gentle-wave" x="48" y="16" fill="rgba(255,255,255,0.95" />
          </g>
        </svg>
      </div>
    </div>
  </header>
  <!-- ========== End Hero ========== -->

  <!-- ========== Start featured Listing Grid ========== -->
  <section class="py-5">
    <div class="container my-5">
      <h2 class="text-center">Featured Business Listings</h2>
      <p class="text-center">Listify is a comprehensive business listing app that allows you to list your business and get reviews from your customers.</p>
      <div class="row">
        <?php
        // Define the maximum number of featured listings per page
        $maxFeaturedListingsPerPage = 8;

        // Get the current page number from the query string
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Calculate the offset based on the current page number and the number of listings per page
        $offset = ($currentPage - 1) * $maxFeaturedListingsPerPage;

        // Retrieve the featured listings for the current page and the total number of featured listings from the database
        $featuredListings = getFeaturedListings($db, $maxFeaturedListingsPerPage, $offset);
        $totalFeaturedListings = $featuredListings['total'];

        // Calculate the total number of pages based on the total number of featured listings and the number of listings per page
        $totalPages = ceil($totalFeaturedListings / $maxFeaturedListingsPerPage);

        // Loop through the featured listings and display each listing
        foreach ($featuredListings['listings'] as $listing) {
          displayListing($listing);
        }

        // Check if there are any listings
        $hasListings = !empty($featuredListings['listings']);
        if (!$hasListings) {
          // Display a message if there are no listings
          echo '<div class="col-md-12 text-center my-5"><p class="lead text-muted">No featured listings found</p></div>';
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
  <!-- ========== End featured Listing Grid ========== -->

  <!-- ========== Start Recent Activity ========== -->
  <section class="py-5">
    <div class="container my-5">
      <h2 class="text-center">Recent Activity</h2>
      <div class="row">
        <?php
        // Retrieve the recent activity listings from the database
        $recentListings = getRecentListings($db);

        // Loop through the recent activity listings and display each listing
        foreach ($recentListings as $listing) {
          displayListing($listing);
        }

        // Check if there are any listings
        $hasListings = !empty($recentListings);
        if (!$hasListings) {
          // Display a message if there are no listings
          echo '<div class="col-md-12 text-center my-5"><p class="lead text-muted">No recent activity found</p></div>';
        }
        ?>
      </div>
    </div>
  </section>
  <!-- ========== End Recent Activity ========== -->

  <!-- ========== Start Why Choose Listify ========== -->

  <section id="why-choose-listify" class="py-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <h2 class="mb-4">Why Choose Listify?</h2>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-3">
          <div class="card mb-4">
            <div class="card-body">
              <h5 class="card-title">Easy to Use</h5>
              <p class="card-text">Listify is designed to be user-friendly and easy to navigate, so you can quickly and easily find what you're looking for.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="card mb-4">
            <div class="card-body">
              <h5 class="card-title">Detailed Listings</h5>
              <p class="card-text">Our listings are comprehensive and detailed, so you can get all the information you need about a business before making a decision.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="card mb-4">
            <div class="card-body">
              <h5 class="card-title">Free to Use</h5>
              <p class="card-text">Listify is completely free to use, so you can save money while finding the best businesses in your area.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="card mb-4">
            <div class="card-body">
              <h5 class="card-title">Support</h5>
              <p class="card-text">Our support team is available 24/7 to help you with any questions or issues you may have while using Listify.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ========== End Why Choose Listify ========== -->

  <!-- ========== Start Testimonials ========== -->

  <section id="testimonials" class="py-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <h2 class="mb-4">What Our Customers Say</h2>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-4">
          <div class="card mb-4">
            <div class="card-body">
              <p class="card-text">"Listify has helped me grow my business by reaching more customers and getting more reviews. Highly recommended!"</p>
              <h5 class="card-title">John Doe</h5>
              <p class="card-subtitle">Owner, John's Pizza</p>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card mb-4">
            <div class="card-body">
              <p class="card-text">"Listify is the best business listing app out there. It's easy to use, affordable, and has helped me get more customers."</p>
              <h5 class="card-title">Jane Smith</h5>
              <p class="card-subtitle">Owner, Jane's Flowers</p>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card mb-4">
            <div class="card-body">
              <p class="card-text">"Listify has been a game-changer for my business. I've seen a significant increase in traffic and sales since I started using it."</p>
              <h5 class="card-title">Mike Johnson</h5>
              <p class="card-subtitle">Owner, Mike's Auto Repair</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ========== End Testimonials ========== -->

  <!-- ========== Start How to List ========== -->

  <section id="how-to-list" class="py-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <h2 class="mb-4">How to List Your Business</h2>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-4">
          <div class="card mb-4">
            <div class="card-body">
              <h5 class="card-title">Step 1</h5>
              <p class="card-text">Create an account on Listify.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card mb-4">
            <div class="card-body">
              <h5 class="card-title">Step 2</h5>
              <p class="card-text">Fill out the business listing form with your business details.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card mb-4">
            <div class="card-body">
              <h5 class="card-title">Step 3</h5>
              <p class="card-text">Submit your business listing for review.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ========== End How to List ========== -->

  <!-- ========== Start Team ========== -->

  <section id="team" class="bg-light">
    <div class="container py-5">
      <div class="row">
        <div class="col-lg-12 text-center">
          <h2 class="mb-4">Our Team</h2>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <div class="card mb-4">
            <img class="card-img-top" src="https://via.placeholder.com/350x200" alt="Sahil Ahmad">
            <div class="card-body">
              <h5 class="card-title">Sahil Ahmad</h5>
              <p class="card-text">Lead Developer</p>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="card mb-4">
            <img class="card-img-top placeholder-glow" src="https://via.placeholder.com/350x200" alt="Farah Niyazi">
            <div class="card-body">
              <h5 class="card-title">Farah Niyazi</h5>
              <p class="card-text">UI/UX Designer</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ========== End Team ========== -->

  <!-- ========== Start About ========== -->

  <section id="about" class="py-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <h2 class="mb-4">About Listify</h2>
          <p>Listify is a comprehensive business listing app that allows you to list your business and get reviews from your customers. Our mission is to help small businesses grow by providing them with a platform to showcase their products and services to a wider audience.</p>
          <p>With Listify, you can create a business listing in just a few minutes and start getting reviews from your customers. Our app is easy to use and comes with a range of features to help you manage your business listing and engage with your customers.</p>
        </div>
        <div class="col-lg-6">
          <img src="about.jpg" alt="About Listify" class="img-fluid">
        </div>
      </div>
    </div>
  </section>

  <!-- ========== End About ========== -->
  <!-- ========== Start CTA ========== -->

  <div class="container-fluid bg-gradient-primary">
    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
          <h2 class="text-white">List your business today</h2>
          <p class="lead text-white mt-3">Listify is a comprehensive business listing app that allows you to list your business and get reviews from your customers.</p>
          <a href="./add-listing.php" class="btn bg-white text-primary mt-3">Add your business</a>
        </div>
      </div>
    </div>
  </div>
  <!-- ========== End CTA ========== -->

  <!-- ========== Start Footer ========== -->
  <?php
  // include the footer file
  include_once './includes/_footer.php';
  ?>
  <!-- ========== End Footer ========== -->

  <!-- ========== Start Scripts ========== -->
  <script type="text/javascript" async>
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
    const searchSpinner = document.getElementById('search-spinner');

    function debounce(func, delay) {
      let timeoutId;
      return function(...args) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
          func.apply(null, args);
        }, delay);
      };
    }

    function displaySearchResults(results) {
      searchResults.innerHTML = '';
      if (Array.isArray(results) && results.length === 0 && searchInput.value.length >= 3) {
        searchResults.innerHTML = `<div class="bg-gradient-white rounded mt-n3">
                  <p class="text-bolder lead text-sm text-info text-center p-2">No results found for this query</p>
                </div>`;
      } else if (Array.isArray(results)) {
        results.slice(0, 8).forEach(result => {
          const resultElement = document.createElement('a');
          resultElement.href = `./listing.php?listing=${result.id}`;
          resultElement.classList.add('list-group-item', 'list-group-item-action');
          resultElement.innerHTML = `
                    <div class="d-flex w-100 justify-content-between align-items-center">
                      <h5 class="text-gradient text-primary font-weight-bold h5 mb-1">${result.businessName}</h5>
                      <span class="text-body-secondary text-gradient text-warning text-uppercase text-xs mt-1"><i class="fa-solid fa-star"></i> ${result.avg_rating ?? 0} (${result.reviews_count})</span>
                      <span class="text-body-secondary text-capitalize text-xs font-weight-bold"><i class="fa-solid fa-shop"></i> ${result.category}</span>
                      <span class="text-body-secondary text-capitalize text-xs font-weight-bold "><i class="fa-solid fa-location-dot"></i> ${result.address}, ${result.city}</span>
                    </div>
                  `;
          searchResults.appendChild(resultElement);
        });
      } else if (results.error) {
        searchResults.innerHTML = `<div class="bg-gradient-white rounded mt-n3">
                <p class="text-bolder lead text-sm text-info text-center p-2">${results.error}</p>
                </div>`;
      }
      searchResults.classList.toggle('d-none', results.length === 0);
      searchSpinner.classList.add('d-none');
    }

    function search() {
      const searchQuery = searchInput.value;
      searchSpinner.classList.remove('d-none');
      fetch(`search.php?q=${searchQuery}`)
        .then(response => response.json())
        .then(data => displaySearchResults(data))
        .catch(error => console.error(error));
    }

    searchInput.addEventListener('input', debounce(search, 500));

    const clearSearchInputButton = document.getElementById('clear-search-input');

    clearSearchInputButton.addEventListener('click', () => {
      searchInput.value = '';
    });
  </script>
  <!-- ========== End Scripts ========== -->

</body>

</html>

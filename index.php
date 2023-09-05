<?php
// include functions file
include_once './functions/functions.php';

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

          <div class="col-lg-7 text-center mx-auto">
            <h2 class="text-white">Search Across Various Business</h2>
            <p class="lead text-white text-sm">"Bringing order to the digital chaos"</p>
            <div class="input-group my-4">
              <span class="input-group-text"><i class="fas fa-search" aria-hidden="true"></i></span>
              <label for="search-input" class="visually-hidden">Search for Business</label>
              <input class="form-control" id="search-input" name="search" placeholder="Search for a business by name" type="text">
              <button class="btn btn-bg-outline-while mb-0" id="clear-search-input" type="button"><i class="fas fa-times text" aria-hidden="true"></i></button>
              <div class="invalid-feedback" id="search-feedback"></div>
            </div>
            <div class="list-group text-center align-items-center" id="search-results">
              <div id="search-spinner" class="spinner-border text-primary d-none" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>
          </div>
        </div>
        <div class="row justify-content-center">

          <p class="col-12 lead text-white text-capitalize font-font-weight-normal my-2">Browse our top categories</p>
          <div class="col-auto text-center move-on-hover mb-2">
            <a href="./category.php?slug=restaurant">
              <div class="d-flex flex-column bg-primary rounded-3 p-2">
                <img src="assets/img/svgs/icons8_restaurant.svg" alt="Restaurants" height="50px">
                <p class="text-white font-weight-bold text-xs">Restaurants</p>
              </div>
            </a>
          </div>
          <div class="col-auto text-center move-on-hover mb-2">
            <a href="./category.php?slug=hospital">
              <div class="d-flex flex-column bg-primary rounded-3 p-2">
                <img src="assets/img/svgs/icons8_hospital_3.svg" alt="Hospitals" height="50px">
                <p class="text-white font-weight-bold text-xs">Hospitals</p>
              </div>
            </a>
          </div>

          <div class="col-auto text-center move-on-hover mb-2">
            <a href="./category.php?slug=pharmacy">
              <div class="d-flex flex-column bg-primary rounded-3 p-2">
                <img src="assets/img/svgs/icons8_pharmacy_shop.svg" alt="Pharmacy" height="50px">
                <span class="text-white font-weight-bold text-xs">Pharmacys</p>
              </div>
            </a>
          </div>
          <div class="col-auto text-center move-on-hover mb-2">
            <a href="./category.php?slug=education">
              <div class="d-flex flex-column bg-primary rounded-3 p-2">
                <img src="assets/img/svgs/icons8_school.svg" alt="Education" height="50px">
                <p class="text-white font-weight-bold text-xs">Education</p>
              </div>
            </a>
          </div>
          <div class="col-auto text-center move-on-hover mb-2">
            <a href="./category.php?slug=atm">
              <div class="d-flex flex-column bg-primary rounded-3 p-2">
                <img src="assets/img/svgs/icons8_atm.svg" alt="ATMs" height="50px">
                <p class="text-white font-weight-bold text-xs">ATMs</p>
              </div>
            </a>
          </div>
          <div class="col-auto text-center move-on-hover mb-2">
            <a href="./category.php?slug=bank">
              <div class="d-flex flex-column bg-primary rounded-3 p-2">
                <img src="assets/img/svgs/icons8_euro_bank_building_2.svg" alt="Banks" height="50px">
                <p class="text-white font-weight-bold text-xs">Banks</p>
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
      <p class="text-center">The are some of our latest listings </p>
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
          <div class="card mb-4 move-on-hover">
            <div class="card-body">
              <h5 class="card-title text-center text-gradient text-primary text-bolder">Easy to Use</h5>
              <p class="card-text text-center">Listify is designed to be user-friendly and easy to navigate, so you can quickly and easily find what you're looking for.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="card mb-4 move-on-hover">
            <div class="card-body">
              <h5 class="card-title text-center text-gradient text-primary text-bolder">Detailed Listings</h5>
              <p class="card-text text-center">Our listings are comprehensive and detailed, so you can get all the information you need about a business before making a decision.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="card mb-4 move-on-hover">
            <div class="card-body">
              <h5 class="card-title text-center text-gradient text-primary text-bolder">Free to Use</h5>
              <p class="card-text text-center">Listify is completely free to use, so you can save money while finding the best businesses in your area.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="card mb-4 move-on-hover">
            <div class="card-body">
              <h5 class="card-title text-center text-gradient text-primary text-bolder">Support</h5>
              <p class="card-text text-center">Our support team is available 24/7 to help you with any questions or issues you may have while using Listify.</p>
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
      <div class="row mt-6">
        <div class="col-lg-4 col-md-8">
          <div class="card card-plain move-on-hover">
            <div class="card-body">
              <div class="author">
                <div class="name">
                  <h6 class="mb-0 font-weight-bolder">Nick Willever</h6>
                  <div class="stats">
                    <i class="far fa-clock" aria-hidden="true"></i> 1 day ago
                  </div>
                </div>
              </div>
              <p class="mt-4">"Listify has helped me grow my business by reaching more customers and getting more reviews. Highly recommended!"</p>
              <div class="rating mt-3">
                <i class="fas fa-star" aria-hidden="true"></i>
                <i class="fas fa-star" aria-hidden="true"></i>
                <i class="fas fa-star" aria-hidden="true"></i>
                <i class="fas fa-star" aria-hidden="true"></i>
                <i class="fas fa-star" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-8 ms-md-auto">
          <div class="card bg-gradient-primary move-on-hover">
            <div class="card-body">
              <div class="author align-items-center">
                <div class="name">
                  <h6 class="text-white mb-0 font-weight-bolder">Shailesh Kushwaha</h6>
                  <div class="stats text-white">
                    <i class="far fa-clock" aria-hidden="true"></i> 1 week ago
                  </div>
                </div>
              </div>
              <p class="mt-4 text-white">"Listify is the best business listing app out there. It's easy to use, affordable, and has helped me get more customers."</p>
              <div class="rating mt-3">
                <i class="fas fa-star text-white" aria-hidden="true"></i>
                <i class="fas fa-star text-white" aria-hidden="true"></i>
                <i class="fas fa-star text-white" aria-hidden="true"></i>
                <i class="fas fa-star text-white" aria-hidden="true"></i>
                <i class="fas fa-star text-white" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-8">
          <div class="card card-plain move-on-hover">
            <div class="card-body">
              <div class="author">
                <div class="name">
                  <h6 class="mb-0 font-weight-bolder">Samuel Kamuli</h6>
                  <div class="stats">
                    <i class="far fa-clock" aria-hidden="true"></i> 3 weeks ago
                  </div>
                </div>
              </div>
              <p class="mt-4">"Listify has been a game-changer for my business. I've seen a significant increase in traffic and sales since I started using it."</p>
              <div class="rating mt-3">
                <i class="fas fa-star" aria-hidden="true"></i>
                <i class="fas fa-star" aria-hidden="true"></i>
                <i class="fas fa-star" aria-hidden="true"></i>
                <i class="fas fa-star" aria-hidden="true"></i>
                <i class="fas fa-star" aria-hidden="true"></i>
              </div>
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
          <div class="card mb-4 bg-gradient-primary move-on-hover">
            <div class="card-body">
              <h5 class="card-title text-white text-center">Sahil Ahmad</h5>
              <p class="card-text text-white text-center">Lead Developer</p>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="card mb-4  bg-gradient-info move-on-hover">
            <div class="card-body">
              <h5 class="card-title text-white text-center">Farah Niyazi</h5>
              <p class="card-text text-white text-center">Lead Designer</p>
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
          <img src="assets/img/john-schnobrich-2FPjlAyMQTA-unsplash.jpg" alt="About Listify" class="img-fluid rounded-3">
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
  <?php include_once './includes/_footer.php';  ?>
  <!-- ========== End Footer ========== -->
  <script src="./assets/js/search.js"></script>
</body>

</html>

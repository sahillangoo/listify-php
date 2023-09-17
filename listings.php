<?php
// Path: listings.php
include_once './functions/functions.php';
?>
<!DOCTYPE html>
<html lang="en" itemscope itemtype="https://schema.org/WebPage">

<head>
  <title>All Listings</title>
  <?php include_once './includes/_head.php'; ?>
  <script src="./assets/js/listings.js" type="module"></script>
</head>

<body class="index-page">
  <?php
  // include the header file
  include_once './includes/_navbar.php';
  ?>

  <!-- ========== Start Heading ========== -->
  <section class="pt-5 ">
    <div class="container">
      <div class="row">
        <div class="col-md-12 pt-5">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
              <li class="breadcrumb-item"><a href="./categories.php">Categories</a></li>
            </ol>
          </nav>
          <div class="heading text-center">
            <h2 class="h2 text-gradient text-primary ">All Listings</h2>
            <p class="lead text-secondary text-sm">"Bringing order to the digital chaos"</p>
          </div>
        </div>

        <div class="col-lg-6 text-center mx-auto">
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search" aria-hidden="true"></i></span>
            <label for="search-input" class="visually-hidden">Search for Business</label>
            <input class="form-control" id="search-input" name="search" placeholder="Search for a business by name" type="text">
            <button class="btn btn-icon-only btn-bg-outline-light bg-transparent rounded text-dark shadow-none mb-0" id="clear-search-input" type="button"><i class="fas fa-times text" aria-hidden="true"></i></button>
            <div class="invalid-feedback" id="search-feedback"></div>
          </div>
          <div class="list-group text-center align-items-center" id="search-results">
            <div id="search-spinner" class="spinner-border text-primary d-none" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ========== End Heading ========== -->


  <!-- ========== Start listing grid with pagination  ========== -->
  <section class="pt-5">
    <div class="container">
      <div class="row">
        <p class="lead text-secondary text-sm text-center text-bolder">Listing Filters:</p>
        <div class="col-md-12 d-inline-flex justify-content-between align-items-center flex-wrap mb-3 gap-3 flex-md-nowrap flex-lg-nowrap flex-xl-nowrap flex-xxl-nowrap">
          <!-- sort dropdown -->
          <div class="input-group input-group-sm">
            <span class="input-group-text">
              Sort by:
            </span>
            <select class="form-select form-select-sm" aria-label="sortDropdown" id="sortDropdown">
              <option selected value="featured">Featured</option>
              <option value="most_rated">Top Rated</option>
              <option value="most_reviewed">Most Reviewed</option>
            </select>
          </div>
          <!-- categories filter dropdown -->
          <div class="input-group input-group-sm">
            <span class="input-group-text">
              Filter category:
            </span>
            <select class="form-select form-select-sm" aria-label="filterCategory" id="filteCategory">
              <option value="">Select a category!</option>
              <option value="restaurant">Restaurant</option>
              <option value="hospital">Hospital</option>
              <option value="pharmacy">Pharmacy Store</option>
              <option value="education">Education</option>
              <option value="bank">Bank</option>
              <option value="atm">ATM</option>
            </select>
          </div>
          <div class="input-group input-group-sm">
            <span class="input-group-text">
              Filter city:
            </span>
            <select class="form-select form-select-sm" aria-label="cityDropdown" id="cityDropdown">
              <option value="">Select a City!</option>
              <option value="srinagar">Srinagar</option>
              <option value="anantnag">Anantnag</option>
              <option value="bandipora">Bandipora</option>
              <option value="baramulla">Baramulla</option>
              <option value="budgam">Budgam</option>
              <option value="ganderbal">Ganderbal</option>
              <option value="kulgam">Kulgam</option>
              <option value="kupwara">Kupwara</option>
              <option value="pulwama">Pulwama</option>
              <option value="shopian">Shopian</option>
            </select>
          </div>
          <!-- clear filters -->
          <button class="btn btn-icon-only btn-bg-outline-light bg-transparent rounded text-dark shadow-none mb-0 text-bolder" id="clear-filters"><i class="fa-solid fa-xmark"></i></button>
        </div>


        <div class="col-md-12">
          <!-- no of listings  -->
          <div class="text-secondary text-sm text-center text-bolder">Showing <span id="listings-count">0</span> of <span id="total-listings">0</span> listings</div>
          </span>
          <div class="row" id="listings">
            <!-- listings will be displayed here -->
          </div>
          <div class="alert alert-danger d-none" id="error-message" role="alert">
            Error fetching listings. Please try again later.
          </div>
          <div class="d-flex justify-content-center my-5" id="loading-spinner">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>
          <nav aria-label="Listings pagination" class="d-flex justify-content-center">
            <ul class="pagination pagination-primary" id="listings-pagination">
              <!-- pagination links will be displayed here -->
            </ul>
          </nav>
        </div>
      </div>
    </div>

  </section>


  <!-- ========== End listing grid with pagination  ========== -->


  <?php
  // include the footer file
  include_once './includes/_footer.php';
  ?>
</body>

</html>

<?php
// Path: listings.php
include_once './functions/functions.php';

// check for catagory category in the url
if (!isset($_GET['category']) || empty($_GET['category']) || !is_string($_GET['category']) || strlen($_GET['category']) > 255 || !preg_match('/^[a-zA-Z]+$/', $_GET['category'])) {
  $category = 'All';
} else {
  $category = sanitize($_GET['category']);
}
?>
<!DOCTYPE html>
<html lang="en" itemscope itemtype="https://schema.org/WebPage">

<head>
  <title>
    Listify Businesses Listings
  </title>
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
              <li class="breadcrumb-item text-capitalize"><?php echo $category; ?> Businesses Listings</li>
            </ol>
          </nav>
          <div class="heading text-center">
            <h2 class="h2 text-capitalize"><?php echo $category; ?> Businesses Listings</h2>
            <p class="lead text-secondary text-sm ">"Search from <span class="text-primary"><?php echo $category; ?></span> businesses listings"</p>
          </div>
        </div>

        <div class="col-10 col-md-6 text-center mx-auto">
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search" aria-hidden="true"></i></span>
            <input class="form-control" id="search-input" name="search" placeholder="Search for a business by name" type="search">
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
  <section class="pt-3">
    <div class="container">
      <div class="row">
        <p class="lead text-secondary text-sm text-center text-bolder">Listing Filters:</p>
        <div class="col-6 col-md-12 d-inline-flex justify-content-between align-items-center flex-wrap mb-3 gap-3 flex-md-nowrap flex-lg-nowrap flex-xl-nowrap flex-xxl-nowrap">
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
            <select class="form-select form-select-sm" aria-label="filterCategory" id="filterCategory">
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
            <select class="form-select form-select-sm " aria-label="cityDropdown" id="cityDropdown">
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
          <button class="btn btn-bg-outline-dark rounded text-dark mb-0 text-bolder" id="clear-filters"><i class="fa-solid fa-xmark"></i> Clear</button>
        </div>


        <div class="col-md-12">
          <!-- Number of listings -->
          <div class="text-dark text-md text-center font-weight-bold">
            Showing <span id="listings-count">0</span> of <span id="total-listings">0</span> listings
          </div>
          <div class="row" id="listings">
            <!-- Listings will be displayed here -->
          </div>
          <!-- Error message -->
          <div class="alert alert-danger d-none" id="error-message" role="alert">
            Error fetching listings. Please try again later.
          </div>
          <!-- Loading spinner -->
          <div class="d-flex justify-content-center my-5" id="loading-spinner">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading listings...</span>
            </div>
          </div>
          <!-- Pagination links -->
          <nav aria-label="Listings pagination" class="d-flex justify-content-center">
            <ul class="pagination pagination-primary" id="listings-pagination">
              <!-- Pagination links will be displayed here -->
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

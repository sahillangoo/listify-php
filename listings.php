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
            <button class="btn  btn-transparent rounded mb-0" id="clear-search-input" type="button"><i class="fas fa-times text" aria-hidden="true"></i></button>
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
        <div class="col-auto d-inline-flex justify-content-center align-items-center mb-3 gap-3 flex-wrap filter-dropdowns">

          <!-- filter dropdown -->
          <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" id="filterDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Filter Listings
            </button>
            <ul class="dropdown-menu" aria-labelledby="filterDropdown">
              <li><button class="dropdown-item" type="button" data-filter="featured">Featured</button></li>
              <li><button class="dropdown-item" type="button" data-filter="most_rated">Top Rated</button></li>
              <li><button class="dropdown-item" type="button" data-filter="most_reviewed">Most Reviewed</button></li>
            </ul>
          </div>
          <!-- select city dropdown -->
          <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" id="cityDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Select City
            </button>
            <ul class="dropdown-menu" aria-labelledby="cityDropdown">
              <li><button class="dropdown-item" type="button">Srinagar</button></li>
              <li><button class="dropdown-item" type="button">Anantnag</button></li>
              <li><button class="dropdown-item" type="button">Baramulla</button></li>
              <li><button class="dropdown-item" type="button">Budgam</button></li>
              <li><button class="dropdown-item" type="button">Bandipora</button></li>
              <li><button class="dropdown-item" type="button">Pulwama</button></li>
              <li><button class="dropdown-item" type="button">Kupwara</button></li>
              <li><button class="dropdown-item" type="button">Kulgam</button></li>
              <li><button class="dropdown-item" type="button">Shopian</button></li>
              <li><button class="dropdown-item" type="button">Ganderbal</button></li>
            </ul>
          </div>
          <span>
            <!-- no of listings  -->
            <span class="text-secondary text-sm">Showing <span id="listings-count">0</span> of <span id="total-listings">0</span> listings</span>
          </span>
          
        </div>
        <div class="col-md-12">
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

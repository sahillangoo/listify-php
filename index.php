<?php
// include functions file
include_once './functions/functions.php';
?>
<!DOCTYPE html>
<html lang="en" itemscope itemtype="https://schema.org/WebPage">

<head>
  <title>Listify - Comprehansive Listing App</title>
  <?php include_once './includes/_head.php'; ?>
  <!-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script> -->
  <script async type="text/javascript">
    fetch('api/featured-listings.php')
      .then(response => response.json())
      .then(listings => {
        // Loop through the listings and display them on the page
        listings.forEach(listing => {
          // Create a new element to display the listing
          const listingElement = document.createElement('div');
          listingElement.classList.add('col-md-3', 'p-3', 'listing');

          // Add the listing details to the element
          listingElement.innerHTML = `
        <div class="card card-frame">
          <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
            <a href="./view-listing.php?listing=${listing.id}" class="d-block">
              <img src="./uploads/business_images/${listing.displayImage}" class="img-fluid border-radius-lg move-on-hover" alt="${listing.title}">
            </a>
          </div>
          <div class="card-body pt-2">
            <div class="d-flex justify-content-between align-items-center my-2">
              <span class="text-uppercase text-xxs font-weight-bold"><i class="fa-solid fa-shop"></i> ${listing.category}</span>
              <span class="text-uppercase text-xxs font-weight-bold "><i class="fa-solid fa-location-dot"></i> ${listing.city}</span>
            </div>
            <div class="d-flex justify-content-between ">
              <a href="./view-listing.php?listing=${listing.id}" class="card-title h6 d-block text-gradient text-primary font-weight-bold ">${listing.businessName.slice(0, 120)}${listing.businessName.length > 15 ? '...' : ''}

              </a>
              <span class="text-gradient text-warning text-uppercase text-xs mt-1"><i class="fa-solid fa-star"></i> ${listing.avg_rating ?? 0} (${listing.reviews_count})</span>
            </div>
            <p class="card-description text-sm mb-3">${listing.description.slice(0, 120)}${listing.description.length > 150 ? '...' : ''}</p>
            <p class="mb-2 text-xxs font-weight-bolder text-warning text-gradient text-uppercase"><span>By―</span> ${listing.username}</p>
            <div class="d-flex justify-content-start my-2">
              <a href="/view-listing.php?listing=${listing.id}" class="text-primary text-sm icon-move-right">View details <i class="fas fa-arrow-right text-sm" aria-hidden="true"></i>
              </a>
            </div>
          </div>
        </div>
          `;

          // Add the listing element to the listingElement class
          document.getElementById('listingElement').appendChild(listingElement);
        });
      })
      .catch(error => {
        console.error('Error fetching listings:', error);
      });
  </script>
</head>

<body class="index-page">
  <?php
  /*
  Todo - Remove template code create home page for listing site
  */
  // include the header file
  include_once './includes/_navbar.php';
  ?>
  <!-- ========== Start Hero ========== -->
  <header class="header-2">
    <div class="page-header min-vh-90 relative" style="background-image: url('./assets/img/curved-images/curved.jpg')">
      <div class="container px-4 text-center">
        <div class="row ">
          <div class="col-lg-2">
          </div>
          <div class="col-lg-8 text-center mx-auto">
            <h2 class="text-white pt-3 mt-n5">Search Across Various Business</h2>
            <p class="lead text-white text-sm mt-3">"Bringing order to the digital chaos"</p>
            <div class="input-group my-4">
              <span class="input-group-text"><i class="fas fa-search" aria-hidden="true"></i></span>
              <label for="search-input" class="visually-hidden">Search for Business</label>
              <input class="form-control" id="search-input" name="search" placeholder="Search for Business" type="text">
              <button type="button" class="btn bg-gradient-primary mb-0" data-bs-toggle="tooltip" data-bs-placement="right" title="Go" aria-label="Search">Search <i class="fa-solid fa-arrow-right"></i></button> </span>
            </div>
          </div>
          <div class="col-lg-2"></div>
        </div>

        <div class="row justify-content-center">
          <p class="lead text-white text-capitalize font-weight-light my-3">Browse our top categories</p>

          <div class="col-auto text-center move-on-hover">
            <div class="bg-white rounded-3 p-3">
              <img src="assets/img/svgs/icons8_restaurant.svg" alt="Restaurants" height="50px">
              <p class="text-primary font-weight-bold text-xs">Restaurants</p>
            </div>
          </div>
          <div class="col-auto text-center move-on-hover">
            <div class="bg-white rounded-3 p-3">
              <img src="assets/img/svgs/icons8_hospital_3.svg" alt="Hospitals" height="50px">
              <p class="text-primary font-weight-bold text-xs">Hospitals</p>
            </div>
          </div>
          <div class="col-auto text-center move-on-hover">
            <div class="bg-white rounded-3 p-3">
              <img src="assets/img/svgs/icons8_pharmacy_shop.svg" alt="Pharmacy" height="50px">
              <p class="text-primary font-weight-bold text-xs">Pharmacy</p>
            </div>
          </div>
          <div class="col-auto text-center move-on-hover">
            <div class="bg-white rounded-3 p-3">
              <img src="assets/img/svgs/icons8_school.svg" alt="Schools" height="50px">
              <p class="text-primary font-weight-bold text-xs">Schools</p>
            </div>
          </div>
          <div class="col-auto text-center move-on-hover">
            <div class="bg-white rounded-3 p-3">
              <img src="assets/img/svgs/icons8_atm.svg" alt="ATMs" height="50px">
              <p class="text-primary font-weight-bold text-xs">ATMs</p>
            </div>
          </div>
          <div class="col-auto text-center move-on-hover">
            <div class="bg-white rounded-3 p-3">
              <img src="assets/img/svgs/icons8_euro_bank_building_2.svg" alt="Banks" height="50px">
              <p class="text-primary font-weight-bold text-xs">Banks</p>
            </div>
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
      <div class="row" id="listingElement"></div>
    </div>
  </section>
  <!-- ========== End featured Listing Grid ========== -->

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

</body>

</html>

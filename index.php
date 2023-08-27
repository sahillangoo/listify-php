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
  <!-- -------- START HEADER ------- -->
  <header>
    <div class="page-header min-vh-100">
      <div class="oblique position-absolute top-0 h-100 d-md-block d-none">
        <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url(./assets/img/curved-images/curved11.jpg)"></div>
      </div>
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-6 col-md-7 d-flex justify-content-center flex-column">
            <h1 class="mb-4">"Bringing order to the digital chaos"</h1>
            <h1 class="text-gradient text-primary">~ Listify is the answer.</h1>
            <p class="lead pe-5 me-5">Say goodbye to mundane lists and hello to Listify – the dynamic web app that turns your everyday lists into engaging narratives. </p>
            <div class="buttons">
              <a href="signin.php">
                <button type="button" class="btn bg-gradient-primary mt-4">Get Started</button></a>
              <button type="button" class="btn text-primary shadow-none mt-4">Read more</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
  <!-- -------- END HEADER ------- -->

  <!-- ========== Start Listing Grid ========== -->
  <div class="container my-5">
    <h3 class="text-center">Featured Business Listings</h3>
    <p class="text-center">Listify is a comprehensive business listing app that allows you to list your business and get reviews from your customers.</p>
    <div class="row" id="listingElement"></div>
  </div>
  <!-- ========== End Listing Grid ========== -->


  <?php
  // include the footer file
  include_once './includes/_footer.php';
  ?>

</body>

</html>

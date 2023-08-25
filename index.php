<?php
// include functions file
include_once './functions/functions.php';
?>
<!DOCTYPE html>
<html lang="en" itemscope itemtype="https://schema.org/WebPage">

<head>
  <title>Listify - Comprehansive Listing App</title>
  <?php include_once './includes/_head.php'; ?>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="index-page">
  <?php
  /*
  Todo - Remove template code create home page for listing site
  */
  // include the header file
  include_once './includes/_navbar.php';
  ?>
  <!-- -------- START HEADER 1 w/ text and image on right ------- -->
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
  <!-- -------- END HEADER 1 w/ text and image on right ------- -->

  <!-- ========== Start Listing Grid ========== -->
  <!-- bootstrap grid for business listings with heading -->
  <div class="container my-5">
    <h3 class="text-center">Featured Business Listings</h3>
    <!-- description -->
    <p class="text-center">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.</p>
    <div class="nav-wrapper position-relative end-0">
      <ul class="nav nav-pills nav-fill p-1" role="tablist">
      <li class="nav-item">
          <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#profile-tabs-icons" role="tab" aria-controls="preview" aria-selected="true">
            <i class="ni ni-badge text-sm me-2"></i> My Profile
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#profile-tabs-icons" role="tab" aria-controls="preview" aria-selected="true">
            <i class="ni ni-badge text-sm me-2"></i> My Profile
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#profile-tabs-icons" role="tab" aria-controls="preview" aria-selected="true">
            <i class="ni ni-badge text-sm me-2"></i> My Profile
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#profile-tabs-icons" role="tab" aria-controls="preview" aria-selected="true">
            <i class="ni ni-badge text-sm me-2"></i> My Profile
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#dashboard-tabs-icons" role="tab" aria-controls="code" aria-selected="false">
            <i class="ni ni-laptop text-sm me-2"></i> Dashboard
          </a>
        </li>
      </ul>
    </div>

    <div class="row">
      <div class="col-md-3 p-3">
        <div class="card">
          <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
            <a href="./listing.php?listing=1" class="d-block">
              <img src="./uploads/business_images/default.jpg" class="img-fluid border-radius-lg">
            </a>
          </div>
          <div class="card-body pt-2">
            <div class="d-flex justify-content-between">
              <span class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2"><i class="fa-solid fa-shop"></i> Category 1</span>
              <span class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2"><i class="fa-solid fa-location-dot"></i> City 1</span>
            </div>
            <div class="d-flex justify-content-between">
              <a href="./listing.php?listing=1" class="card-title h5 d-block text-primary ">
                Business 1
              </a>
              <span class="text-gradient text-warning text-uppercase text-xs my-2"><i class="fa-solid fa-star"></i> 4.5</span>
            </div>
            <p class="card-description text-sm mb-2">Description of Business 1</p>
            <a href="./listing.php?listing=1" class=" text-sm my-2">View More</a>
            <div class="d-flex justify-content-between mt-3">
              <a href="https://api.whatsapp.com/send?phone=1234567890&text=Hey!" class="btn btn-slack btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-whatsapp"></i></span>
              </a>
              <a href="https://www.facebook.com/1234567890/" class="btn btn-facebook btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-facebook"></i></span>
              </a>
              <a href="https://www.instagram.com/1234567890/?hl=en" class="btn btn-instagram btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-instagram"></i></span>
              </a>
              <a href="https://www.example.com" class="btn btn-linkedin btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-solid fa-globe"></i></span>
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 p-3">
        <div class="card">
          <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
            <a href="./listing.php?listing=2" class="d-block">
              <img src="./uploads/business_images/default.jpg" class="img-fluid border-radius-lg">
            </a>
          </div>
          <div class="card-body pt-2">
            <div class="d-flex justify-content-between">
              <span class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2"><i class="fa-solid fa-shop"></i> Category 2</span>
              <span class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2"><i class="fa-solid fa-location-dot"></i> City 2</span>
            </div>
            <div class="d-flex justify-content-between">
              <a href="./listing.php?listing=2" class="card-title h5 d-block text-primary ">
                Business 2
              </a>
              <span class="text-gradient text-warning text-uppercase text-xs my-2"><i class="fa-solid fa-star"></i> 4.0</span>
            </div>
            <p class="card-description text-sm mb-2">Description of Business 2</p>
            <a href="./listing.php?listing=2" class=" text-sm my-2">View More</a>
            <div class="d-flex justify-content-between mt-3">
              <a href="https://api.whatsapp.com/send?phone=1234567890&text=Hey!" class="btn btn-slack btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-whatsapp"></i></span>
              </a>
              <a href="https://www.facebook.com/1234567890/" class="btn btn-facebook btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-facebook"></i></span>
              </a>
              <a href="https://www.instagram.com/1234567890/?hl=en" class="btn btn-instagram btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-instagram"></i></span>
              </a>
              <a href="https://www.example.com" class="btn btn-linkedin btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-solid fa-globe"></i></span>
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 p-3">
        <div class="card">
          <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
            <a href="./listing.php?listing=3" class="d-block">
              <img src="./uploads/business_images/default.jpg" class="img-fluid border-radius-lg">
            </a>
          </div>
          <div class="card-body pt-2">
            <div class="d-flex justify-content-between">
              <span class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2"><i class="fa-solid fa-shop"></i> Category 3</span>
              <span class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2"><i class="fa-solid fa-location-dot"></i> City 3</span>
            </div>
            <div class="d-flex justify-content-between">
              <a href="./listing.php?listing=3" class="card-title h5 d-block text-primary ">
                Business 3
              </a>
              <span class="text-gradient text-warning text-uppercase text-xs my-2"><i class="fa-solid fa-star"></i> 3.5</span>
            </div>
            <p class="card-description text-sm mb-2">Description of Business 3</p>
            <a href="./listing.php?listing=3" class=" text-sm my-2">View More</a>
            <div class="d-flex justify-content-between mt-3">
              <a href="https://api.whatsapp.com/send?phone=1234567890&text=Hey!" class="btn btn-slack btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-whatsapp"></i></span>
              </a>
              <a href="https://www.facebook.com/1234567890/" class="btn btn-facebook btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-facebook"></i></span>
              </a>
              <a href="https://www.instagram.com/1234567890/?hl=en" class="btn btn-instagram btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-instagram"></i></span>
              </a>
              <a href="https://www.example.com" class="btn btn-linkedin btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-solid fa-globe"></i></span>
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 p-3">
        <div class="card">
          <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
            <a href="./listing.php?listing=3" class="d-block">
              <img src="./uploads/business_images/default.jpg" class="img-fluid border-radius-lg">
            </a>
          </div>
          <div class="card-body pt-2">
            <div class="d-flex justify-content-between">
              <span class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2"><i class="fa-solid fa-shop"></i> Category 3</span>
              <span class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2"><i class="fa-solid fa-location-dot"></i> City 3</span>
            </div>
            <div class="d-flex justify-content-between">
              <a href="./listing.php?listing=3" class="card-title h5 d-block text-primary ">
                Business 3
              </a>
              <span class="text-gradient text-warning text-uppercase text-xs my-2"><i class="fa-solid fa-star"></i> 3.5</span>
            </div>
            <p class="card-description text-sm mb-2">Description of Business 3</p>
            <a href="./listing.php?listing=3" class=" text-sm my-2">View More</a>
            <div class="d-flex justify-content-between mt-3">
              <a href="https://api.whatsapp.com/send?phone=1234567890&text=Hey!" class="btn btn-slack btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-whatsapp"></i></span>
              </a>
              <a href="https://www.facebook.com/1234567890/" class="btn btn-facebook btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-facebook"></i></span>
              </a>
              <a href="https://www.instagram.com/1234567890/?hl=en" class="btn btn-instagram btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-instagram"></i></span>
              </a>
              <a href="https://www.example.com" class="btn btn-linkedin btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-solid fa-globe"></i></span>
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 p-3">
        <div class="card">
          <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
            <a href="./listing.php?listing=3" class="d-block">
              <img src="./uploads/business_images/default.jpg" class="img-fluid border-radius-lg">
            </a>
          </div>
          <div class="card-body pt-2">
            <div class="d-flex justify-content-between">
              <span class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2"><i class="fa-solid fa-shop"></i> Category 3</span>
              <span class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2"><i class="fa-solid fa-location-dot"></i> City 3</span>
            </div>
            <div class="d-flex justify-content-between">
              <a href="./listing.php?listing=3" class="card-title h5 d-block text-primary ">
                Business 3
              </a>
              <span class="text-gradient text-warning text-uppercase text-xs my-2"><i class="fa-solid fa-star"></i> 3.5</span>
            </div>
            <p class="card-description text-sm mb-2">Description of Business 3</p>
            <a href="./listing.php?listing=3" class=" text-sm my-2">View More</a>
            <div class="d-flex justify-content-between mt-3">
              <a href="https://api.whatsapp.com/send?phone=1234567890&text=Hey!" class="btn btn-slack btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-whatsapp"></i></span>
              </a>
              <a href="https://www.facebook.com/1234567890/" class="btn btn-facebook btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-facebook"></i></span>
              </a>
              <a href="https://www.instagram.com/1234567890/?hl=en" class="btn btn-instagram btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-instagram"></i></span>
              </a>
              <a href="https://www.example.com" class="btn btn-linkedin btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-solid fa-globe"></i></span>
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 p-3">
        <div class="card">
          <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
            <a href="./listing.php?listing=3" class="d-block">
              <img src="./uploads/business_images/default.jpg" class="img-fluid border-radius-lg">
            </a>
          </div>
          <div class="card-body pt-2">
            <div class="d-flex justify-content-between">
              <span class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2"><i class="fa-solid fa-shop"></i> Category 3</span>
              <span class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2"><i class="fa-solid fa-location-dot"></i> City 3</span>
            </div>
            <div class="d-flex justify-content-between">
              <a href="./listing.php?listing=3" class="card-title h5 d-block text-primary ">
                Business 3
              </a>
              <span class="text-gradient text-warning text-uppercase text-xs my-2"><i class="fa-solid fa-star"></i> 3.5</span>
            </div>
            <p class="card-description text-sm mb-2">Description of Business 3</p>
            <a href="./listing.php?listing=3" class=" text-sm my-2">View More</a>
            <div class="d-flex justify-content-between mt-3">
              <a href="https://api.whatsapp.com/send?phone=1234567890&text=Hey!" class="btn btn-slack btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-whatsapp"></i></span>
              </a>
              <a href="https://www.facebook.com/1234567890/" class="btn btn-facebook btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-facebook"></i></span>
              </a>
              <a href="https://www.instagram.com/1234567890/?hl=en" class="btn btn-instagram btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-instagram"></i></span>
              </a>
              <a href="https://www.example.com" class="btn btn-linkedin btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-solid fa-globe"></i></span>
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 p-3">
        <div class="card">
          <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
            <a href="./listing.php?listing=3" class="d-block">
              <img src="./uploads/business_images/default.jpg" class="img-fluid border-radius-lg">
            </a>
          </div>
          <div class="card-body pt-2">
            <div class="d-flex justify-content-between">
              <span class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2"><i class="fa-solid fa-shop"></i> Category 3</span>
              <span class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2"><i class="fa-solid fa-location-dot"></i> City 3</span>
            </div>
            <div class="d-flex justify-content-between">
              <a href="./listing.php?listing=3" class="card-title h5 d-block text-primary ">
                Business 3
              </a>
              <span class="text-gradient text-warning text-uppercase text-xs my-2"><i class="fa-solid fa-star"></i> 3.5</span>
            </div>
            <p class="card-description text-sm mb-2">Description of Business 3</p>
            <a href="./listing.php?listing=3" class=" text-sm my-2">View More</a>
            <div class="d-flex justify-content-between mt-3">
              <a href="https://api.whatsapp.com/send?phone=1234567890&text=Hey!" class="btn btn-slack btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-whatsapp"></i></span>
              </a>
              <a href="https://www.facebook.com/1234567890/" class="btn btn-facebook btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-facebook"></i></span>
              </a>
              <a href="https://www.instagram.com/1234567890/?hl=en" class="btn btn-instagram btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-instagram"></i></span>
              </a>
              <a href="https://www.example.com" class="btn btn-linkedin btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-solid fa-globe"></i></span>
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 p-3">
        <div class="card">
          <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
            <a href="./listing.php?listing=3" class="d-block">
              <img src="./uploads/business_images/default.jpg" class="img-fluid border-radius-lg">
            </a>
          </div>
          <div class="card-body pt-2">
            <div class="d-flex justify-content-between">
              <span class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2"><i class="fa-solid fa-shop"></i> Category 3</span>
              <span class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2"><i class="fa-solid fa-location-dot"></i> City 3</span>
            </div>
            <div class="d-flex justify-content-between">
              <a href="./listing.php?listing=3" class="card-title h5 d-block text-primary ">
                Business 3
              </a>
              <span class="text-gradient text-warning text-uppercase text-xs my-2"><i class="fa-solid fa-star"></i> 3.5</span>
            </div>
            <p class="card-description text-sm mb-2">Description of Business 3</p>
            <a href="./listing.php?listing=3" class=" text-sm my-2">View More</a>
            <div class="d-flex justify-content-between mt-3">
              <a href="https://api.whatsapp.com/send?phone=1234567890&text=Hey!" class="btn btn-slack btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-whatsapp"></i></span>
              </a>
              <a href="https://www.facebook.com/1234567890/" class="btn btn-facebook btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-facebook"></i></span>
              </a>
              <a href="https://www.instagram.com/1234567890/?hl=en" class="btn btn-instagram btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-instagram"></i></span>
              </a>
              <a href="https://www.example.com" class="btn btn-linkedin btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-solid fa-globe"></i></span>
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 p-3">
        <div class="card">
          <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
            <a href="./listing.php?listing=3" class="d-block">
              <img src="./uploads/business_images/default.jpg" class="img-fluid border-radius-lg">
            </a>
          </div>
          <div class="card-body pt-2">
            <div class="d-flex justify-content-between">
              <span class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2"><i class="fa-solid fa-shop"></i> Category 3</span>
              <span class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2"><i class="fa-solid fa-location-dot"></i> City 3</span>
            </div>
            <div class="d-flex justify-content-between">
              <a href="./listing.php?listing=3" class="card-title h5 d-block text-primary ">
                Business 3
              </a>
              <span class="text-gradient text-warning text-uppercase text-xs my-2"><i class="fa-solid fa-star"></i> 3.5</span>
            </div>
            <p class="card-description text-sm mb-2">Description of Business 3</p>
            <a href="./listing.php?listing=3" class=" text-sm my-2">View More</a>
            <div class="d-flex justify-content-between mt-3">
              <a href="https://api.whatsapp.com/send?phone=1234567890&text=Hey!" class="btn btn-slack btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-whatsapp"></i></span>
              </a>
              <a href="https://www.facebook.com/1234567890/" class="btn btn-facebook btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-facebook"></i></span>
              </a>
              <a href="https://www.instagram.com/1234567890/?hl=en" class="btn btn-instagram btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-instagram"></i></span>
              </a>
              <a href="https://www.example.com" class="btn btn-linkedin btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-solid fa-globe"></i></span>
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 p-3">
        <div class="card">
          <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
            <a href="./listing.php?listing=2" class="d-block">
              <img src="./uploads/business_images/default.jpg" class="img-fluid border-radius-lg">
            </a>
          </div>
          <div class="card-body pt-2">
            <div class="d-flex justify-content-between">
              <span class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2"><i class="fa-solid fa-shop"></i> Category 2</span>
              <span class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2"><i class="fa-solid fa-location-dot"></i> City 2</span>
            </div>
            <div class="d-flex justify-content-between">
              <a href="./listing.php?listing=2" class="card-title h5 d-block text-primary ">
                Business 2
              </a>
              <span class="text-gradient text-warning text-uppercase text-xs my-2"><i class="fa-solid fa-star"></i> 4.0</span>
            </div>
            <p class="card-description text-sm mb-2">Description of Business 2</p>
            <a href="./listing.php?listing=2" class=" text-sm my-2">View More</a>
            <div class="d-flex justify-content-between mt-3">
              <a href="https://api.whatsapp.com/send?phone=1234567890&text=Hey!" class="btn btn-slack btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-whatsapp"></i></span>
              </a>
              <a href="https://www.facebook.com/1234567890/" class="btn btn-facebook btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-facebook"></i></span>
              </a>
              <a href="https://www.instagram.com/1234567890/?hl=en" class="btn btn-instagram btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-instagram"></i></span>
              </a>
              <a href="https://www.example.com" class="btn btn-linkedin btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-solid fa-globe"></i></span>
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 p-3">
        <div class="card">
          <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
            <a href="./listing.php?listing=2" class="d-block">
              <img src="./uploads/business_images/default.jpg" class="img-fluid border-radius-lg">
            </a>
          </div>
          <div class="card-body pt-2">
            <div class="d-flex justify-content-between">
              <span class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2"><i class="fa-solid fa-shop"></i> Category 2</span>
              <span class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2"><i class="fa-solid fa-location-dot"></i> City 2</span>
            </div>
            <div class="d-flex justify-content-between">
              <a href="./listing.php?listing=2" class="card-title h5 d-block text-primary ">
                Business 2
              </a>
              <span class="text-gradient text-warning text-uppercase text-xs my-2"><i class="fa-solid fa-star"></i> 4.0</span>
            </div>
            <p class="card-description text-sm mb-2">Description of Business 2</p>
            <a href="./listing.php?listing=2" class=" text-sm my-2">View More</a>
            <div class="d-flex justify-content-between mt-3">
              <a href="https://api.whatsapp.com/send?phone=1234567890&text=Hey!" class="btn btn-slack btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-whatsapp"></i></span>
              </a>
              <a href="https://www.facebook.com/1234567890/" class="btn btn-facebook btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-facebook"></i></span>
              </a>
              <a href="https://www.instagram.com/1234567890/?hl=en" class="btn btn-instagram btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-instagram"></i></span>
              </a>
              <a href="https://www.example.com" class="btn btn-linkedin btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-solid fa-globe"></i></span>
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 p-3">
        <div class="card">
          <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
            <a href="./listing.php?listing=2" class="d-block">
              <img src="./uploads/business_images/default.jpg" class="img-fluid border-radius-lg">
            </a>
          </div>
          <div class="card-body pt-2">
            <div class="d-flex justify-content-between">
              <span class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2"><i class="fa-solid fa-shop"></i> Category 2</span>
              <span class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2"><i class="fa-solid fa-location-dot"></i> City 2</span>
            </div>
            <div class="d-flex justify-content-between">
              <a href="./listing.php?listing=2" class="card-title h5 d-block text-primary ">
                Business 2
              </a>
              <span class="text-gradient text-warning text-uppercase text-xs my-2"><i class="fa-solid fa-star"></i> 4.0</span>
            </div>
            <p class="card-description text-sm mb-2">Description of Business 2</p>
            <a href="./listing.php?listing=2" class=" text-sm my-2">View More</a>
            <div class="d-flex justify-content-between mt-3">
              <a href="https://api.whatsapp.com/send?phone=1234567890&text=Hey!" class="btn btn-slack btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-whatsapp"></i></span>
              </a>
              <a href="https://www.facebook.com/1234567890/" class="btn btn-facebook btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-facebook"></i></span>
              </a>
              <a href="https://www.instagram.com/1234567890/?hl=en" class="btn btn-instagram btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-brands fa-instagram"></i></span>
              </a>
              <a href="https://www.example.com" class="btn btn-linkedin btn-icon-only rounded-circle" target="_blank">
                <span class="btn-inner--icon"><i class="fa-solid fa-globe"></i></span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- ========== End Listing Grid ========== -->


  <?php
  // include the footer file
  include_once './includes/_footer.php';
  ?>

</body>

</html>

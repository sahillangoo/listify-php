<?php
// include functions file
include_once './functions/functions.php';

/*
  Listing Page
  If this page gets listing id from the url, it will show the listing details to the user else it will redirect to the home page
  */
// check if the listing id is set in the url
if (!isset($_GET['listing']) || empty($_GET['listing']) || strlen($_GET['listing']) > 255 || !preg_match('/^[0-9]+$/', $_GET['listing'])) {
  redirect('404.php');
  exit();
} else {
  $listing = sanitize($_GET['listing']);
  $stmt = $db->prepare('SELECT l.*, r.review, AVG(r.rating) AS avg_rating, COUNT(r.id) AS reviews_count, u.username FROM listings l LEFT JOIN reviews r ON l.id = r.listing_id LEFT JOIN users u ON l.user_id = u.id WHERE l.id = :id GROUP BY l.id');
  $stmt->bindParam(':id', $_GET['listing'], PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$result) {
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
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <style>
    #mapid {
      height: 400px;
    }

    .custom-radius {
      stroke: #3388ff;
      stroke-opacity: 0.8;
      stroke-width: 2;
      fill: #3388ff;
      fill-opacity: 0.2;
    }
  </style>

  <?php
  // Initialize the test coordinates
  $lat = $result['latitude'];
  $lng = $result['longitude'];

  // Output the JavaScript code using heredoc
  echo <<<EOT
  <script>
  function initMap() {
    // The location of the test coordinates
    const testCoords = { lat: $lat, lng: $lng };
    // The map, centered at the test coordinates
    const map = L.map('mapid').setView(testCoords, 13);
    // The marker, positioned at the test coordinates
    L.marker(testCoords).addTo(map);
    // The radius, positioned at the test coordinates
    const radius = L.circle(testCoords, {
      color: 'blue',
      fillColor: '#3388ff',
      fillOpacity: 0.2,
      radius: 200, // in meters
    }).addTo(map);
    // The tile layer, using OpenStreetMap's standard tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
      maxZoom: 18,
    }).addTo(map);
  }
  initMap();
  </script>
  EOT;
  ?>
</head>

<body class="index-page" onload="initMap()">
  <?php
  // include the header file
  include_once './includes/_navbar.php';
  ?>
  <!-- Display the listing details using Bootstrap 5 -->
  <div class="container my-5">
    <!-- breadcrumb -->
    <div class="row pt-5 overflow-hidden">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
          <li class="breadcrumb-item"><a href="./categories.php">Categories</a></li>
          <li class="breadcrumb-item text-capitalize"><a href="./categories.php?slug=<?php echo $result['category']; ?>"><?php echo $result['category']; ?></a></li>
          <li class="breadcrumb-item active text-capitalize" aria-current="page"><?php echo $result['businessName']; ?></li>
        </ol>
      </nav>
    </div>
    <div class="row">
      <div class="col-md-6">
        <img src="./uploads/business_images/<?php echo $result['displayImage']; ?>" class="img-fluid rounded move-on-hover border " alt="<?php echo $result['businessName']; ?>">

      </div>

      <div class="col-md-6">
        <div class="card-body pt-2">
          <h2 class="card-title h2 text-center text-gradient text-primary font-weight-bolder "><?php echo $result['businessName']; ?></h2>

          <div class="d-flex justify-content-between align-items-center my-2">
            <span class="text-capitalize text-sm font-weight-bold"><i class="fa-solid fa-shop"></i> <?php echo $result['category']; ?></span>
            <!-- Display the rating stars -->
            <span class="text-gradient text-warning text-sm">
              <?php
              $rating = $result['avg_rating'];
              for ($i = 0; $i < 5; $i++) {
                if ($i < $rating) {
                  echo '<i class="fas fa-star"></i>';
                } else {
                  echo '<i class="far fa-star"></i>';
                }
              }
              ?>
              <!-- Display the average rating -->
              <span class="text-sm">(<?php echo $result['reviews_count']; ?>)</span>
            </span>
            <span class="text-sm font-weight-bold"><i class="fa-regular fa-clock"></i> Since:
              <?php $createdAt = strtotime($result['createdAt']);
              $formattedCreatedAt = date('j, F y', $createdAt);
              echo $formattedCreatedAt; ?>
            </span>

          </div>
          <!-- description -->
          <p class="card-description mt-3"><?php echo $result['description']; ?></p>

          <h4 class="h4 text-center text-bolder">
            Contact Details
          </h4>
          <!-- contact detailes -->
          <div class="d-flex flex-wrap justify-content-between mx-4">

            <span class="font-weight-bold text-primary text-gradient mt-3">
              <i class="fa-regular fa-user"></i> @<?php echo $result['username']; ?>
            </span>

            <a href="tel:<?php echo $result['phoneNumber']; ?>" class="font-weight-bold mt-3">
              <i class="fa-solid fa-phone"></i> +91 <?php echo $result['phoneNumber']; ?>
            </a>

            <a href="mailto:<?php echo $result['email']; ?>" class="font-weight-bold mt-3">
              <i class="fa-regular fa-envelope"></i> <?php echo $result['email']; ?> </a>

            <span class="text-capitalize font-weight-bold my-3"><i class="fa-solid fa-location-dot"></i> <?php echo $result['address'] . ', ' . $result['city'] . ' - ' . $result['pincode']; ?></span>


            <a href="<?php echo $result['website']; ?>" class="btn btn-github btn-simple btn-lg mb-0 mt-2 " target="_blank">
              <i class="fa-solid fa-globe"></i></a>

            <a href="https://facebook.com/<?php echo $result['facebookId']; ?>" class="btn btn-facebook btn-simple btn-lg mb-0 mt-2 " target="_blank">
              <i class="fa-brands fa-facebook" aria-hidden="true"></i></a>

            <a href="https://instagram.com/<?php echo $result['instagramId']; ?>" class="btn btn-instagram btn-simple btn-lg mb-0 mt-2" target="_blank"><i class="fa-brands fa-instagram"></i></a>

          </div>

        </div>
      </div>

      <div class="col-md-12">
        <!-- wide business map -->
        <h4 class="h3 text-center text-gradient text-primary font-weight-bolder mt-5">Location</h4>
        <p class="text-center text-sm">The location is approximate and might not be accurate.</p>
        <!-- map leaflet -->
        <div class="my-3 rounded" id="mapid"></div>
      </div>
    </div>
    <div class="col-md-12">
      <!-- business reviews -->
      <h4 class="h3 text-center text-gradient text-primary font-weight-bolder mt-5">Reviews</h4>
      <p class="text-center text-sm">The reviews are based on the user experience.</p>
      <!-- reviews with pagination -->
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
              <p class="mt-4">"<?php echo $result['review']; ?>"</p>
              <div class="rating mt-3">
                <?php
                $rating = $result['avg_rating'];
                for ($i = 0; $i < 5; $i++) {
                  if ($i < $rating) {
                    echo '<i class="fas fa-star"></i>';
                  } else {
                    echo '<i class="far fa-star"></i>';
                  }
                }
                ?>
                <!-- Display the average rating -->
                <span class="text-sm">(<?php echo $result['reviews_count']; ?>)</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php
  // include the footer file
  include_once './includes/_footer.php';
  ?>
</body>

</html>

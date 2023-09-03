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
  $stmt = $db->prepare('SELECT * FROM listings WHERE id = :id');
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
  $lat = 34.08468475517784;
  $lng = 74.79748543611667;

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
          <li class="breadcrumb-item active text-capitalize" aria-current="page"><?php echo $result['category']; ?></li>
        </ol>
      </nav>
    </div>
    <div class="row">
      <div class="col-md-6">
        <img src="./uploads/business_images/<?php echo $result['displayImage']; ?>" class="img-fluid rounded move-on-hover border " alt="<?php echo $result['businessName']; ?>">
      </div>
      <div class="col-md-6">
        <h2 class="h2 text-gradient text-primary"><?php echo $result['businessName']; ?></h2>
        <!-- rating -->
        <p class="text-gradient text-warning text-3xl animate gradient-animation">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <span class="text-sm">(1)</span>
        </p>


        <p class="text-muted"><?php echo $result['category']; ?></p>
        <p class="text-muted"><?php echo $result['createdAt']; ?></p>
        <p class="text-muted"><?php echo $result['updatedAt']; ?></p>
        <p class="text-muted"><?php echo $result['featured']; ?></p>
        <p class="text-muted"><?php echo $result['active']; ?></p>
        <p class="text-muted"><?php echo $result['user_id']; ?></p>

        <p class="text-muted"><?php echo $result['reviews_count']; ?></p>
        <p class="text-muted"><?php echo $result['username']; ?></p>
        <p class="text-muted"><?php echo $result['businessName']; ?></p>
        <p class="text-muted"><?php echo $result['description']; ?></p>
        <p class="text-muted"><?php echo $result['phoneNumber']; ?></p>
        <p class="text-muted"><?php echo $result['address']; ?></p>
        <p class="text-muted"><?php echo $result['city']; ?></p>
        <p class="text-muted"><?php echo $result['state']; ?></p>
        <p class="text-muted"><?php echo $result['zip']; ?></p>
        <p class="text-muted"><?php echo $result['country']; ?></p>
        <p class="text-muted"><?php echo $result['website']; ?></p>
        <p class="text-muted"><?php echo $result['facebook']; ?></p>
        <p class="text-muted"><?php echo $result['twitter']; ?></p>
        <p class="text-muted"><?php echo $result['instagram']; ?></p>

        <p><?php echo $result['description']; ?></p>
        <p>Contact: <?php echo $result['phoneNumber']; ?></p>
        <p>Address: <?php echo $result['address']; ?></p>
        <p>City: <?php echo $result['city']; ?></p>

        <!-- map leaflet -->
        <div id="mapid"></div>


      </div>
    </div>
  </div>

  <div class="container">
    <div class="row">
      <!-- review cards -->
      <?php
      // Display the reviews
      $stmt = $db->prepare('SELECT * FROM reviews WHERE listing_id = ?');
      $stmt->execute([$result['id']]);
      $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (count($reviews) > 0) {
        echo '<h2>Reviews</h2>';
        foreach ($reviews as $review) {
          echo '<p>';
          for ($i = 0; $i < $review['rating']; $i++) {
            echo '<i class="fa fa-star" style="color: #ffc800;"></i>';
          }
          echo ' ' . $review['rating'] . '-Stars';
          echo '</p>';
          echo '<p>' . $review['review'] . '</p>';
          echo '<p>' . $review['createdAt'] . '</p>';
          // Get the user details who posted the review
          $stmt = $db->prepare('SELECT * FROM users WHERE id = ?');
          $stmt->execute([$review['user_id']]);
          $user = $stmt->fetch(PDO::FETCH_ASSOC);
          echo '<p>Posted by: ' . $user['username'] . '</p>';
        }
      } else {
        echo '<p>No reviews yet.</p>';
      }
      ?>
    </div>
  </div>

  <?php
  if (isAuthenticated()) {
    // Check if the user has already posted a review
    $stmt = $db->prepare('SELECT * FROM reviews WHERE user_id = ? AND listing_id = ?');
    $stmt->execute([$_SESSION['user_id'], $result['id']]);
    $review = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($review) {
      echo '<p class="text-sm text-center">You have already posted a review.</p>';
    } else {
  ?>
      <div class="container">
        <div class="row mt-5">
          <div class="col-md-6 offset-md-3">
            <?php include_once('./functions/dialog.php'); ?>
            <h3>Add a Review</h3>
            <form id="review-form" name="review" action="./functions/review/add_review.php" method="post">
              <input type="hidden" name="listing_id" value="<?php echo $listing; ?>">
              <div class="mb-3">
                <label for="rating" class="form-label">Rating</label>
                <select class="form-select" name="rating" id="rating" required>
                  <option value="">Select a rating</option>
                  <option value="1">1 star</option>
                  <option value="2">2 stars</option>
                  <option value="3">3 stars</option>
                  <option value="4">4 stars</option>
                  <option value="5">5 stars</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="review" class="form-label">Review</label>
                <textarea class="form-control" id="review" name="review" rows="5" maxlength="150" required></textarea>
              </div>
              <button id="submit-btn" type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Submit</button>
            </form>
          </div>
        </div>
      </div>
  <?php
    }
  } else {
    echo '<p class="text-sm text-center">Please <a href="./signin.php">login</a> to post a review.</p>';
  }
  ?>

  <script>
    const form = document.getElementById('review-form');
    const rating = form.elements['rating'];
    const review = form.elements['review'];

    form.addEventListener('submit', (event) => {
      event.preventDefault();

      if (!rating.value) {
        alert('Please select a rating.');
        return;
      }

      if (rating.value < 1 || rating.value >= 5) {
        alert('Please select a rating between 1 and 5.');
        return;
      }

      if (!review.value) {
        alert('Please enter a review.');
        return;
      }

      if (review.value.length < 10 || review.value.length > 150) {
        alert('Please enter a review between 10 and 150 characters.');
        return;
      }

      form.submit();
    });
  </script>

  <?php
  // include the footer file
  include_once './includes/_footer.php';
  ?>
</body>

</html>

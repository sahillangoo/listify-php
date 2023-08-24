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
  <?php
  // Function to get all listings from the database
  function getListings()
  {
    global $db;
    $listings = [];
    try {
      $sql = "SELECT * FROM listings";
      $stmt = $db->prepare($sql);
      $stmt->execute();
      $listings = $stmt->fetchAll();
      $stmt->closeCursor();
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
    return $listings;
  }
  // Function to get listings rating from the database
  function getRating($listing_id)
  {
    global $db;
    $rating = [];
    try {
      $sql = "SELECT AVG(rating) FROM reviews WHERE listing_id = ?";
      $stmt = $db->prepare($sql);
      $stmt->execute([$listing_id]);
      $rating = $stmt->fetch();
      $stmt->closeCursor();
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
    return $rating;
  }

  // Get the listings from the database
  $listings = getListings();

  ?>

  <div class="container">
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php if (!empty($listings)) : ?>
        <?php foreach ($listings as $listing) : ?>
          <div class="col">
            <div class="card h-100">
              <img src="./uploads/business_images/<?php echo htmlspecialchars($listing['displayImage']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($listing['businessName']); ?>">
              <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($listing['businessName']); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars($listing['description']); ?></p>
                <!-- get rating -->
                <p class="card-text"><small class="text-muted"><?php $rating = getRating($id);
                                                                echo htmlspecialchars($rating['AVG(rating)']); ?></small></p>
              </div>
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><?php echo htmlspecialchars($listing['category']); ?></li>
                <li class="list-group-item"><?php echo htmlspecialchars($listing['address']); ?></li>
              </ul>
              <div class="card-footer">
                <a href="#" class="btn btn-primary">View Listing</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else : ?>
        <p>No listings found.</p>
      <?php endif; ?>
    </div>
  </div>

  <div class="container my-5">
    <div class="row">
      <div class="col-4">
        <!-- listing card -->
        <div class="card">
          <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
            <a href="./listing.php?listing=<?php echo htmlspecialchars($listing['id']); ?>" class="d-block">
              <img src="./uploads/business_images/<?php echo htmlspecialchars($listing['displayImage']); ?>" class="img-fluid border-radius-lg">
            </a>
          </div>

          <div class="card-body pt-2">
            <div class="d-flex justify-content-between">
              <span class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2"><i class="fa-solid fa-shop"></i> <?php echo htmlspecialchars($listing['category']); ?></span>
              <span class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2"><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($listing['city']); ?></span>
            </div>
            <div class="d-flex justify-content-between">
              <a href="./listing.php?listing=<?php echo htmlspecialchars($listing['id']); ?>" class="card-title h5 d-block text-primary ">
                <?php echo htmlspecialchars($listing['businessName']); ?>
              </a>
              <span class="text-gradient text-warning text-uppercase text-xs my-2"><i class="fa-solid fa-star"></i> <?php echo htmlspecialchars($listing['rating']); ?></span>
            </div>
            <p class="card-description text-sm mb-2"><?php echo htmlspecialchars($listing['description']); ?> </p>
            <a href="./listing.php?listing=<?php echo htmlspecialchars($listing['id']); ?>" class=" text-sm my-2">View More</a>

            <!-- social media buttons whatsapp facebook instagran and website if not null  -->
            <div class="d-flex justify-content-between mt-3">
              <?php if (!empty($listing['whatsapp'])) : ?>
                <a href="https://api.whatsapp.com/send?phone=<?php echo htmlspecialchars($listing['whatsapp']); ?>&text=Hey!" class="btn btn-slack btn-icon-only rounded-circle" target="_blank">
                  <span class="btn-inner--icon"><i class="fa-brands fa-whatsapp"></i></span>
                </a>
              <?php endif; ?>
              <?php if (!empty($listing['facebookId'])) : ?>
                <a href="https://www.facebook.com/<?php echo htmlspecialchars($listing['facebookId']); ?>/" class="btn btn-facebook btn-icon-only rounded-circle" target="_blank">
                  <span class="btn-inner--icon"><i class="fa-brands fa-facebook"></i></span>
                </a>
              <?php endif; ?>
              <?php if (!empty($listing['instagramId'])) : ?>
                <a href="https://www.instagram.com/<?php echo htmlspecialchars($listing['instagramId']); ?>/?hl=en" class="btn btn-instagram btn-icon-only rounded-circle" target="_blank">
                  <span class="btn-inner--icon"><i class="fa-brands fa-instagram"></i></span>
                </a>
              <?php endif; ?>
              <?php if (!empty($listing['website'])) : ?>
                <a href="<?php echo htmlspecialchars($listing['website']); ?>?listify" class="btn btn-linkedin btn-icon-only rounded-circle" target="_blank">
                  <span class="btn-inner--icon"><i class="fa-solid fa-globe"></i></span>
                </a>
              <?php endif; ?>
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
  <script type="text/javascript">
    const text = document.querySelector('.card-description').textContent;
    const truncatedText = text.length > 150 ? text.substring(0, 150) + '...' : text;
    document.querySelector('.card-description').textContent = truncatedText;
  </script>
</body>

</html>

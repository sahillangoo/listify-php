<!DOCTYPE html>
<html lang="en" itemscope itemtype="https://schema.org/WebPage">

<head>
  <title>Listify - Comprehansive Listing App</title>
  <?php
  // include DB file
  include_once './functions/db_connect.php';
  // include config file
  include_once './includes/_config.php';
  // include the head file
  include_once './includes/_head.php';
  // Fetch all listings from the database
  try {
    $sql = "SELECT * FROM listings";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $listings = $stmt->fetchAll();
  } catch (PDOException $e) {
    echo $e->getMessage();
  } finally {
    $stmt = null;
  }
  ?>
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


  <div class="container">
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php foreach ($listings as $listing) : ?>
        <div class="col">
          <div class="card h-100">
            <img src="./uploads/business_images/<?php echo $listing['displayImage']; ?>" class="card-img-top" alt="<?php echo $listing['businessName']; ?>">
            <div class="card-body">
              <h5 class="card-title"><?php echo $listing['businessName']; ?></h5>
              <p class="card-text"><?php echo $listing['description']; ?></p>
            </div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item"><?php echo $listing['category']; ?></li>
              <li class="list-group-item"><?php echo $listing['address']; ?></li>
            </ul>
            <div class="card-footer">
              <a href="#" class="btn btn-primary">View Listing</a>
              <a href="#" class="btn btn-primary">Contact Business</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <?php
  // include the footer file
  include_once './includes/_footer.php';
  ?>
</body>

</html>

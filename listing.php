<!DOCTYPE html>
<html lang="en" itemscope itemtype="https://schema.org/WebPage">

<head>
  <title><?php ?> </title>
  <?php
  // include DB file
  include_once './functions/db_connect.php';
  // include config file
  include_once './includes/_config.php';
  // include the head file
  include_once './includes/_head.php';

  /*
  Listing Page
  If this page gets listing id from the url, it will show the listing details to the user else it will redirect to the home page
  */
  // check if the listing id is set in the url
  if (!isset($_GET['listing'])) {
    redirect('index.php');
    exit();
  } else {
    // Show the listing details to the user
    $listing = $_GET['listing'];
    // get the listing details from the database
    // Prepare the SQL statement
    $stmt = $db->prepare('SELECT * FROM listings WHERE id = :id');
    // Bind the parameter
    $stmt->bindParam(':id', $_GET['listing'], PDO::PARAM_INT);
    // Execute the query
    $stmt->execute();
    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    // Check if the listing is found in the database
    if (!$result) {
      redirect('404.php');
      exit();
    }
  }


  ?>
</head>

<body class="index-page">
  <?php
  // include the header file
  include_once './includes/_navbar.php';
  ?>
  <!-- Display the listing details using Bootstrap 5 -->
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <img src="./uploads/business_images/<?php echo $result['displayImage']; ?>" class="img-fluid" alt="<?php echo $result['businessName']; ?>">
      </div>
      <div class="col-md-6">
        <h1><?php echo $result['businessName']; ?></h1>
        <p><?php echo $result['description']; ?></p>
        <p>Contact: <?php echo $result['phoneNumber']; ?></p>
        <p>Address: <?php echo $result['address']; ?></p>
        <p>City: <?php echo $result['city']; ?></p>
        
      </div>
    </div>
  </div>



  <?php
  // include the footer file
  include_once './includes/_footer.php';
  ?>
</body>

</html>

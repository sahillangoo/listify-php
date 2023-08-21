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
  // Check if the user is signed in
  if (isset($_SESSION['user_id'])) {
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
        echo '<p>' . $review['comment'] . '</p>';
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
    // Display the review form
    echo '<h2>Leave a review</h2>';
    echo '<form method="post" action="./functions/review/submit_review.php">';
    echo '<input type="hidden" name="listing_id" value="' . $result['id'] . '">';
    echo '<textarea name="text"></textarea>';
    echo '<input class="btn btn-sm btn-primary" type="submit" value="Submit">';
    echo '</form>';
  } else {
    // Display a message telling the user to sign in
    echo '<p>You must be signed in to leave a review.</p>';
  }
  ?>



  <?php
  // include the footer file
  include_once './includes/_footer.php';
  ?>
</body>

</html>

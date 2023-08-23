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
  if (isLoggedIn()) {
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

<!DOCTYPE html>
<html lang="en" itemscope itemtype="https://schema.org/WebPage">

<head>
  <title>
    My Account - Listify
  </title>
  <?php
  // todo - remove template code and redesign the page
  // include config file
  include_once './includes/_config.php';
  // include the database connection file
  include_once './functions/db_connect.php';
  // include the head file
  include_once './includes/_head.php';

  //  check if the user is logged in or not
  if (!isLoggedIn()) {
    redirect('signin.php');
    exit;
  }
  // convert user_since to a readable format
  $user_since = date('d M Y', strtotime($_SESSION['user_since']));
  // get the user id from the session
  $user_id = $_SESSION['user_id'];
  // Fetch user's listings from the database
  try {
    $sql = "SELECT * FROM listings WHERE user_id = :user_id";
    $stmt = $db->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    $listings = $stmt->fetchAll();
  } catch (PDOException $e) {
    echo $e->getMessage();
  } finally {
    $stmt = null;
  }
  ?>
</head>

<body class="blog-author bg-gray-100">
  <!-- Navbar Light -->
  <?php
  // include the header file
  include_once './includes/_navbar.php';  ?>
  <!-- End Navbar -->
  <section class="py-sm-7 py-2 position-relative">
    <div class="container">
      <div class="row">
        <!-- breadcrumb -->
        <div class="container py-2 mt-3 overflow-hidden">
          <div class="row">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
                <li class="breadcrumb-item">My Account</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
      <?php include_once('./functions/dialog.php'); ?>
      <div class="col-12 mx-auto">
        <div class="row py-lg-7 py-2">
          <div class="col-lg-3 col-md-5 position-relative my-auto">
            <img class="img border-radius-lg max-width-200 w-100 position-relative z-index-2" src="<?php echo $_SESSION['profile_image']; ?>" alt="user">
          </div>
          <div class="col-lg-7 col-md-7 z-index-2 position-relative px-md-2 px-sm-5 mt-sm-0 mt-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <h4 class="mb-0">@<?php echo $_SESSION['username']; ?></h4>
              <div class="d-block">
                <form action="#" method="post">
                  <button href="#" type="submit" name="editprofile" value="editprofile" class="btn btn-sm btn-outline-info text-nowrap mb-0">Edit Profile</button>
                </form>
              </div>
            </div>
            <div class="row">
              <div class="col-auto">
                <span class="h6">Phone: </span>
                <span><?php echo $_SESSION['phone']; ?></span>
              </div>
              <div class="col-auto">
                <span class="h6">Email ID: </span>
                <span><?php echo $_SESSION['email']; ?></span>
              </div>
              <div class="col-auto">
                <span class="h6">User Since: </span>
                <span><?php echo $user_since; ?></span>
              </div>
            </div>
            <div class="row">
              <?php if (empty($listings)) : ?>
                <div class="col-auto">
                  <a href="./create-listing.php" class="btn btn-sm btn-outline-info text-nowrap mb-0">Create Listing</a>
                </div>
              <?php endif; ?>
              <?php if (!empty($listings)) : ?>
                <div class="col-auto">
                  <button href="#" type="submit"  class="btn btn-sm btn-outline-info text-nowrap mb-0">Update Listing</button>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="py-3">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <h3 class="mb-5">Listing</h3>
        </div>
      </div>
      <!-- if the user has no listings, display a message -->
      <?php if (empty($listings)) : ?>
        <div class="alert alert-info" role="alert">
          You have no listings yet. <a href="./create-listing.php">Create a listing</a>
        </div>
      <?php else : ?>
        <!-- if the user has listings  -->
        <div class="row">
          <?php foreach ($listings as $listing) : ?>
            <div class="col-lg-3 col-sm-6">
              <div class="card mb-4">
                <img src="./uploads/business_images/<?php echo $listing['displayImage']; ?>" class="card-img-top" alt="...">
                <div class="card-body">
                  <h5 class="card-title"><?php echo $listing['businessName']; ?></h5>
                  <p class="card-text"><?php echo $listing['description']; ?></p>
                  <a href="#" class="btn btn-primary">Read More</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>
  <?php
  // include the footer file
  include_once './includes/_footer.php';
  ?>
</body>

</html>

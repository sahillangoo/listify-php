<?php
// include functions file
include_once './functions/functions.php';
//  check if the user is logged in or not
if (!isAuthenticated()) {
  redirect('signin.php');
  exit;
}
// If the user is not not active
if ($_SESSION['status'] === 'inactive') {
  $_SESSION['errorsession'] = 'You are not active. Please contact admin to activate your account.';
  redirect('signin.php');
  exit;
}

// get the user id from the session
if (isset($_SESSION['user_id'])) {
  $user_id = sanitize($_SESSION['user_id']);
}

// Fetch user's listings from the database
try {
  $listings = getUserListings($db, $user_id);
} catch (PDOException $e) {
  error_log($e->getMessage());
}
// convert user_since to a readable format
$user_since = date('d M Y', strtotime($_SESSION['user_since']));
?>
<!DOCTYPE html>
<html lang="en" itemscope itemtype="https://schema.org/WebPage">

<head>
  <title>
    My Account - Listify
  </title>
  <?php
  // include the head file
  include_once './includes/_head.php';
  ?>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const deleteButtons = document.getElementsByClassName('deleteListingButton');

      for (let i = 0; i < deleteButtons.length; i++) {
        deleteButtons[i].addEventListener('click', () => {
          // confirm the user wants to delete the listing
          const confirmDelete = confirm('Are you sure you want to delete this listing?');
          if (confirmDelete) {
            const listingId = deleteButtons[i].getAttribute('data-listing-id');
            window.location.href = `./functions/listings/delete_listing.php?listing_id=${listingId}`;
          }
        });
      }
    });
  </script>
</head>

<body class="blog-author bg-gray-100">

  <!-- Navbar -->
  <?php include_once './includes/_navbar.php'; ?>

  <!-- Header -->
  <section class="py-5 position-relative">
    <div class="container">
      <!-- breadcrumb -->
      <div class="row py-5 overflow-hidden">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
            <li class="breadcrumb-item">My Account</li>
          </ol>
        </nav>
      </div>
      <div class="row">
        <?php include_once('./functions/dialog.php'); ?>
        <div class="col-12 mx-auto">
          <div class="row ">
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
                <div class="col-auto">
                  <a href="./add-listing.php" class="btn btn-sm btn-outline-info text-nowrap mb-0">Create Listing</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  </section>
  <!-- End Header -->

  <!-- Listings -->
  <section class="py-3">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h3 class="h3 text-center text-primary text-gradient">Your Business Listings</h3>
          <p class="text-center">
            Your business listings are displayed below. You can <span class="text-primary text-gradient">edit or delete</span> them.
          </p>
        </div>

        <!-- if the user has no listings, display a message -->
        <?php if (empty($listings)) : ?>
          <div class="alert alert-info" role="alert">
            You have no listings yet. <a href="./add-listing.php">Create a listing</a>
          </div>

        <?php else : ?>
          <!-- if the user has listings  -->
          <?php
          foreach ($listings as $listing) {
            displayListing($listing);
          }
          ?>
        <?php endif; ?>
      </div>
  </section>
  <!-- End Listings -->

  <!-- Footer -->
  <?php
  // include the footer file
  include_once './includes/_footer.php';
  ?>
</body>

</html>

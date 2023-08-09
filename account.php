<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">

<head>
  <title>
    My Account - Listify
  </title>
  <?php
  // include config file
  include_once './includes/_config.php';
  // include the head file
  include_once './includes/_head.php';

  //  check if the user is logged in or not
  if (!isLoggedIn()) {
    redirect('signin.php');
    exit;
  }
  // convert user_since to a readable format
  $user_since = date('d M Y', strtotime($_SESSION['user_since']));

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
        <div class="col-12 mx-auto">
          <div class="row py-lg-7 py-2">
            <div class="col-lg-3 col-md-5 position-relative my-auto">
              <img class="img border-radius-lg max-width-200 w-100 position-relative z-index-2" src="<?php echo $_SESSION['profile_image']; ?>" alt="user">
            </div>
            <div class="col-lg-7 col-md-7 z-index-2 position-relative px-md-2 px-sm-5 mt-sm-0 mt-4">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="mb-0"><?php echo $_SESSION['username']; ?></h4>
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
                    <button href="#" type="submit" name="editprofile" value="editprofile" class="btn btn-sm btn-outline-info text-nowrap mb-0">Create Listing</button>
                </div>
                <div class="col-auto">
                    <button href="#" type="submit" name="editprofile" value="editprofile" class="btn btn-sm btn-outline-info text-nowrap mb-0">Update Listing</button>
                </div>
              </div>
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
      <div class="row">
        <div class="col-lg-3 col-sm-6">
          <div class="card card-plain card-blog">
            <div class="card-image border-radius-lg position-relative">
              <a href="javascript:;">
                <img class="w-100 border-radius-lg move-on-hover shadow" src="https://raw.githubusercontent.com/creativetimofficial/public-assets/master/soft-ui-design-system/assets/img/color-bags.jpg">
              </a>
            </div>
            <div class="card-body px-0">
              <h5>
                <a href="javascript:;" class="text-dark font-weight-bold">Rover raised $65 million</a>
              </h5>
              <p>
                Finding temporary housing for your dog should be as easy as
                renting an Airbnb. That’s the idea behind Rover ...
              </p>
              <a href="javascript:;" class="text-info icon-move-right">Read More
                <i class="fas fa-arrow-right text-sm"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <?php
  // include the footer file
  include_once './includes/_footer.php';
  ?>
</body>

</html>

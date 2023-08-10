<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">

<head>
  <title>
    Create Listing - Listify
  </title>
  <?php
  // include config file
  include_once './includes/_config.php';
  // include the databse connection file
  include_once './functions/db_connect.php';
  // include the head file
  include_once './includes/_head.php';

  //  check if the user is logged in or not
  if (!isLoggedIn()) {
    redirect('signin.php');
    exit;
  }
  // get the user id from the session
  $user_id = $_SESSION['id'];
  // check users listing
  try {
    $sql = "SELECT * FROM listings WHERE user_id = :user_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $listings = $stmt->fetchAll();
    // check if the user has a listing
    if (count($listings) > 0) {
      redirect('account.php');
      exit;
    }
  } catch (PDOException $e) {
    echo $e->getMessage();
  }








  ?>
</head>

<body class="blog-author bg-gray-100">
  <!-- Navbar Light -->
  <?php
  // include the header file
  include_once './includes/_navbar.php';  ?>
  <!-- End Navbar -->

  <!-- create listing form -->
  <section class="py-sm-7 py-2 position-relative">
    <div class="container">
      <div class="row">
        <!-- breadcrumb -->
        <div class="container py-2 mt-3 overflow-hidden">
          <div class="row">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="./account.php">My Account</a></li>
                <li class="breadcrumb-item">Create Listing</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
      <div class="col-lg-8 mx-auto d-flex justify-content-center flex-column">
        <h3 class="font-weight-bolder text-primary text-gradient text-center">Create New Listing</h3>
        <p class="my-2 text-primary text-gradient text-sm mx-auto">
          Enter your business details below to create a new listing.
        </p>
        <form role="form" id="create_listing" method="post" autocomplete="off" action="./functions/listings/create_listing.php" name="signin" class="needs-validation" novalidate>
          <div class="card-body">
            <div class="row">
              <div class="mb-4">
                <label>Business Name</label>
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="Business Name" name="business_name" require>
                </div>
              </div>
              <div class="form-group mb-4">
                <label>Description</label>
                <textarea name="description" class="form-control" id="description" placeholder="About your Business" rows="2" require></textarea>
              </div>
              <div class="col-md-6">
                <label>Category</label>
                <div class="input-group mb-4">
                  <input class="form-control" placeholder="" aria-label="First Name..." type="text">
                </div>
              </div>
              <div class="col-md-6 ps-2">
                <label>Last Name</label>
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="" aria-label="Last Name...">
                </div>
              </div>
            </div>
            <div class="mb-4">
              <label>Email Address</label>
              <div class="input-group">
                <input type="email" class="form-control" placeholder="">
              </div>
            </div>
            <div class="form-group mb-4">
              <label>Your message</label>
              <textarea name="message" class="form-control" id="message" rows="4"></textarea>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-check form-switch mb-4">
                  <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" checked="">
                  <label class="form-check-label" for="flexSwitchCheckDefault">I agree to the <a href="javascript:;" class="text-dark"><u>Terms and Conditions</u></a>.</label>
                </div>
              </div>
              <div class="col-md-12">
                <button type="submit" class="btn bg-gradient-dark w-100">Send Message</button>
              </div>
            </div>
          </div>
        </form>
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

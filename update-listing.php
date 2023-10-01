<?php
// include functions file
include_once './functions/functions.php';

// get listing id from the URL
$listing_id = $_GET['id'];

// check if the listing id is set in the url and is not empty and is a number and is not more than 255 characters long and is an integer value and is greater than 0 else redirect to account page with error message

if (!isset($_GET['id']) || empty($_GET['id']) || strlen($_GET['id']) > 255 || !preg_match('/^[0-9]+$/', $_GET['id']) || $_GET['id'] < 1) {
  redirect('account.php', 'Something went wrong. Please try again.', 'error');
  exit();
} else {
  // get the listing id from the url
  $listing_id = sanitize($_GET['id']);

  // get the listing details from the database
  $stmt = $db->prepare('SELECT * FROM listings WHERE id = :id');
  $stmt->bindParam(':id', $listing_id, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  // check if the listing exists in the database
  if (!$result) {
    redirect('account.php', 'Something went wrong. Please try again.', 'error');
    exit();
  }
}

// check if the user is authenticate else redirect to account page with error message
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || $_SESSION['user_id'] != $result['user_id']) {
  redirect('account.php', 'Something went wrong. Please try again.', 'error');
  exit();
}

?>
<!DOCTYPE html>
<html lang="en" itemscope itemtype="https://schema.org/WebPage">

<head>
  <title>
    Create Listing - Listify
  </title>
  <?php
  // include the head file
  include_once './includes/_head.php';
  ?>
</head>

<body class="blog-author bg-gray-100">
  <!-- Navbar Light -->
  <?php


  // include the header file
  include_once './includes/_navbar.php';  ?>
  <!-- End Navbar -->

  <!-- create listing form -->
  <section class="py-5 position-relative">
    <div class="container">
      <!-- breadcrumb -->
      <div class="row py-5 overflow-hidden">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="./account.php">My Account</a></li>
            <li class="breadcrumb-item active" aria-current="page">Update Listing</li>
          </ol>
        </nav>
      </div>
      <div class="row">
        <div class="col-md-8 mx-auto d-flex justify-content-center flex-column">
          <h3 class="font-weight-bolder text-primary text-gradient text-center">Update Listing</h3>
          <p class="my-2 mx-auto text-center">
            Enter your business details below to <span class="text-primary text-gradient">update</span> listing.
          </p>
          <?php include_once('./functions/dialog.php'); ?>
          <div class="card-body">

            <form role="form" id="create_listing" method="post" autocomplete="on" action="./functions/listings/process_listing.php" enctype="multipart/form-data" name="create_listing" class="needs-validation" novalidate>

              <div class="row">

                <div class="col-md-6 ps-2 mb-3 ">
                  <input type="text" class="form-control d-none" name="listing_id" id="listing_id" value="<?php echo $listing_id; ?>" disabled>
                  <label for="businessName">
                    <h6 class="h6 font-weight-bolder text-primary text-gradient">Business Name</h6>
                  </label>
                  <input type="text" class="form-control has-validation" placeholder="Business Name" name="businessName" aria-label="Business Name" aria-describedby="Business Name" data-bs-toggle="tooltip" data-bs-placement="left" title="Enter your business name, must contain combination of letters & numbers between 4-30 characters" required value="<?php echo $result['businessName']; ?>">
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                  <div class="invalid-feedback">
                    Invalid business name or length must contain combination of letters & numbers between 4-30 characters
                  </div>
                </div>

                <div class="col-md-6 ps-2 mb-3">
                  <label for="category">
                    <h6 class="h6 font-weight-bolder text-primary text-gradient">Category</h6>
                  </label>
                  <select class="form-control has-validation" list="category" id="category" name="category" aria-label="category" aria-describedby="category" data-bs-toggle="tooltip" data-bs-placement="right" title="Which category suits your business?" required>
                    <option value="">Select a category!</option>
                    <option value="restaurant">Restaurant</option>
                    <option value="hospital">Hospital</option>
                    <option value="pharmacy">Pharmacy Store</option>
                    <option value="education">Education</option>
                    <option value="bank">Bank</option>
                    <option value="atm">ATM</option>
                  </select>
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                  <div class="invalid-feedback">
                    Please select a category for your business.
                  </div>
                </div>

                <div class="mb-3">
                  <label for="description">
                    <h6 class="h6 font-weight-bolder text-primary text-gradient">Description</h6>
                  </label>
                  <textarea name="description" class="form-control has-validation" id="description" placeholder="About your Business" rows="3" aria-label="description" aria-describedby="description" data-bs-toggle="tooltip" data-bs-placement="right" title="Enter business description of 999 words" required><?php echo $result['description']; ?></textarea>
                  <div class="valid-feedback">
                    <span id="counter"> </span><br>
                    Looks good!
                  </div>
                  <div class="invalid-feedback">
                    Invalid description text or length must be between 10 to 999 words only!
                  </div>
                </div>

                <div class="col-md-6 ps-2 mb-3">
                  <input type="text" class="form-control d-none" name="latitude" id="latitude" disabled>
                  <input type="text" class="form-control d-none" name="longitude" id="longitude" disabled>
                  <label for="address">
                    <h6 class="h6 font-weight-bolder text-primary text-gradient">Address</h6>
                  </label>
                  <input type="text" class="form-control has-validation" placeholder="Address" id="address" name="address" aria-label="address" aria-describedby="address" data-bs-toggle="tooltip" data-bs-placement="left" title="Enter your business address must contain combination of letters & numbers & -,_between 8-30 characters" required value="<?php echo $result['address']; ?>">
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                  <div class="invalid-feedback">
                    Invalid address or length must contain combination of letters, numbers, -,_ & between 8-30 characters
                  </div>
                </div>

                <div class="col-md-6 ps-2 mb-3">
                  <label for="city">
                    <h6 class="h6 font-weight-bolder text-primary text-gradient">City</h6>
                  </label>
                  <select class="form-control has-validation" id="city" name="city" aria-label="city" aria-describedby="city" data-bs-toggle="tooltip" data-bs-placement="right" title="Enter city name for your business." required>
                    <option value="">Select a City!</option>
                    <option value="srinagar">Srinagar</option>
                    <option value="anantnag">Anantnag</option>
                    <option value="bandipora">Bandipora</option>
                    <option value="baramulla">Baramulla</option>
                    <option value="budgam">Budgam</option>
                    <option value="ganderbal">Ganderbal</option>
                    <option value="kulgam">Kulgam</option>
                    <option value="kupwara">Kupwara</option>
                    <option value="pulwama">Pulwama</option>
                    <option value="shopian">Shopian</option>
                  </select>
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                  <div class="invalid-feedback">Please select a city for your business.</div>
                </div>

                <div class="col-md-6 ps-2 mb-3 ">
                  <label for="pincode">
                    <h6 class="h6 font-weight-bolder text-primary text-gradient">Pincode</h6>
                  </label>
                  <input type="number" class="form-control has-validation no-arrow" placeholder="Business Pincode" id="pincode" name="pincode" aria-label="pincode" aria-describedby="pincode" data-bs-toggle="tooltip" data-bs-placement="left" title="Enter your pincode for your business." required value="<?php echo $result['pincode']; ?>">
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                  <div class="invalid-feedback">Invalid pincode or length must be 6 digit numbers</div>
                </div>
                <style>
                  .no-arrow::-webkit-inner-spin-button,
                  .no-arrow::-webkit-outer-spin-button {
                    -webkit-appearance: none;
                    margin: 0;
                  }
                </style>

                <div class="col-md-6 ps-2 mb-3">
                  <label for="state">
                    <h6 class="h6 font-weight-bolder text-primary text-gradient">State</h6>
                  </label>
                  <input type="tel" class="form-control " placeholder="Business Phone +91" id="phone" name="phone" aria-label="phone" aria-describedby="phone" data-bs-toggle="tooltip" data-bs-placement="right" title="Enter 10 digit business phone number " required value="<?php echo $result['phoneNumber']; ?>">
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                  <div class="invalid-feedback">
                    Invalid phone number or length must be 10 digit numbers
                  </div>
                </div>
                <div class="col-md-6 ps-2 mb-3">
                  <label for="email">
                    <h6 class="h6 font-weight-bolder text-primary text-gradient">Business Email</h6>
                  </label>
                  <input type="email" class="form-control" placeholder="Business Email" id="email" name="email" aria-label="Email" aria-describedby="email" data-bs-toggle="tooltip" data-bs-placement="left" title="Enter your business email" required value="<?php echo $result['email']; ?>">
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                  <div class="invalid-feedback">Please enter valid E-mail.</div>
                </div>

                <div class="col-md-6 ps-2 mb-3">
                  <label for="whatsapp">
                    <h6 class="h6 font-weight-bolder text-primary text-gradient">WhatsApp Number</h6>
                  </label>
                  <input type="tel" class="form-control" placeholder="WhatsApp Number +91" id="whatsapp" name="whatsapp" aria-label="whatsapp" aria-describedby="whatsapp" data-bs-toggle="tooltip" data-bs-placement="right" title="Enter 10 digit business whatsapp number" value="<?php echo $result['whatsapp']; ?>">
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                  <div class="invalid-feedback">Invalid WhatsApp phone number or length must be 10 digit numbers</div>
                </div>

                <div class="col-md-6 ps-2 mb-3">
                  <label for="instagram_id">
                    <h6 class="h6 font-weight-bolder text-primary text-gradient">Instagram ID</h6>
                  </label>
                  <input type="text" class="form-control" placeholder="Instagram ID" id="instagram_id" name="instagramId" aria-label="instagram_id" aria-describedby="instagram_id" data-bs-toggle="tooltip" data-bs-placement="left" title="Enter business instagram username" value="<?php echo $result['instagramId']; ?>">
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                  <div class="invalid-feedback">Please enter valid instagram usernam.</div>
                </div>

                <div class="col-md-6 ps-2 mb-3">
                  <label for="facebook_id">
                    <h6 class="h6 font-weight-bolder text-primary text-gradient">Facebook ID</h6>
                  </label>
                  <input type="text" class="form-control" placeholder="Facebook ID" id="facebook_id" name="facebookId" aria-label="facebook_id" aria-describedby="facebook_id" data-bs-toggle="tooltip" data-bs-placement="right" title="Enter business facebook usename" value="<?php echo $result['facebookId']; ?>">
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                  <div class="invalid-feedback">Please enter valid facebook usename.</div>
                </div>

                <div class="col-md-6 ps-2 mb-3">
                  <label for="Website">
                    <h6 class="h6 font-weight-bolder text-primary text-gradient">Webiste Link</h6>
                  </label>
                  <input type="url" class="form-control" placeholder="Your Website Link" id="website" name="website" aria-label="website" aria-describedby="website" data-bs-toggle="tooltip" data-bs-placement="right" title="Enter your business website link" value="<?php echo $result['website']; ?>">
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                  <div class="invalid-feedback">Please enter valid website.</div>
                </div>

                <div class="col-md-6 ps-2 mb-3">
                  <label for="display_image">
                    <h6 class="h6 font-weight-bolder text-primary text-gradient">Display Image</h6>
                  </label>
                  <input class="form-control" type="file" id="business_image" name="displayImage" accept="image/*" aria-label="display_image" aria-describedby="display_image" data-bs-toggle="tooltip" data-bs-placement="right" title="Choose file">
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                  <div class="invalid-feedback">Uploaded Image Error</div>
                </div>

                <div class="mb-3">
                  <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                    <label class="form-check-label" for="terms">I agree to the
                      <i class="fa-solid fa-info-circle"></i>
                      <a href="./privacy.php" class="text-info text-gradient font-weight-bold" target="_blank">Privacy Policy</a> &amp;
                      <a href="./terms.php" class="text-info text-gradient font-weight-bold" target="_blank">Terms of Use</a>.</label>
                  </div>
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                  <div class="invalid-feedback">
                    You must agree before submitting.
                  </div>
                </div>

                <div class="mb-3">
                  <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                  <button type="submit" name="create_listing" class="btn bg-gradient-primary w-100"><i class="fas fa-lock"></i> Create Listing</button>
                </div>

              </div>
            </form>
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

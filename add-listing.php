<?php
// include functions file
include_once './functions/functions.php';

//  check if the user is logged in or not
if (!isAuthenticated()) {
  redirect('signin.php');
  exit;
}
// get the user id from the session
$user_id = $_SESSION['user_id'];
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
<!DOCTYPE html>
<html lang="en" itemscope itemtype="https://schema.org/WebPage">

<head>
  <title>
    Create Listing - Listify
  </title>
  <?php
  // todo fix labels add validation
  // include the head file
  include_once './includes/_head.php';
  ?>
</head>

<body class="blog-author bg-gray-100">
  <!-- Navbar Light -->
  <?php
  // echo session
  // echo '<pre>';
  // print_r($_SESSION);
  // echo '</pre>';

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
            <li class="breadcrumb-item active" aria-current="page">Create Listing</li>
          </ol>
        </nav>
      </div>
      <div class="row">
        <div class="col-lg-8 mx-auto d-flex justify-content-center flex-column">
          <h3 class="font-weight-bolder text-primary text-gradient text-center">Create New Listing</h3>
          <p class="my-2 text-primary text-gradient text-sm mx-auto text-center">
            Enter your business details below to create a new listing.
          </p>
          <?php include_once('./functions/dialog.php'); ?>
          <div class="card-body">

            <form role="form" id="create_listing" method="post" autocomplete="on" action="./functions/listings/process_listing.php" enctype="multipart/form-data" name="create_listing" class="needs-validation" novalidate>
              <div class="row">

                <div class="col-md-6 ps-2 mb-3 ">
                  <input type="text" class="form-control has-validation" placeholder="Business Name" name="businessName" aria-label="Email" aria-describedby="business_name" data-bs-toggle="tooltip" data-bs-placement="left" title="Enter Business name" required>
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                  <div class="invalid-feedback">Please enter valid E-mail.</div>
                </div>

                <div class="col-md-6 ps-2 mb-3">
                  <select class="form-control has-validation" list="category" id="category" name="category" aria-label="category" aria-describedby="category" data-bs-toggle="tooltip" data-bs-placement="right" title="Which category suits your business" required>
                    <option value="">Select a category</option>
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
                  <div class="invalid-feedback">Please enter valid category.</div>
                </div>

                <div class="mb-3">
                  <textarea name="description" class="form-control has-validation" id="description" placeholder="About your Business" rows="2" aria-label="Email" aria-describedby="business_name" data-bs-toggle="tooltip" data-bs-placement="right" title="Enter Business description" required></textarea>
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                  <div class="invalid-feedback">Please enter valid E-mail.</div>
                </div>

                <div class="col-md-6 ps-2 mb-3">
                  <input type="text" class="form-control d-none" name="latitude" id="latitude" disabled>
                  <input type="text" class="form-control d-none" name="longitude" id="longitude" disabled>
                  <input type="text" class="form-control has-validation" placeholder="Address" id="address" name="address" aria-label="address" aria-describedby="business_name" data-bs-toggle="tooltip" data-bs-placement="right" title="Enter address" required>
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                  <div class="invalid-feedback">Please enter valid E-mail.</div>
                </div>

                <div class="col-md-6 ps-2 mb-3">
                  <select class="form-control has-validation" id="city" name="city" aria-label="Email" aria-describedby="business_name" data-bs-toggle="tooltip" data-bs-placement="right" title="Enter city name" required>
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
                  <div class="invalid-feedback">Please enter valid E-mail.</div>
                </div>
                <div class="col-md-6 ps-2 mb-3 ">
                  <input type="text" class="form-control has-validation" placeholder="Pincode" id="pincode" name="pincode" aria-label="Email" aria-describedby="business_name" data-bs-toggle="tooltip" data-bs-placement="right" title="Enter pincode" required>
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                  <div class="invalid-feedback">Please enter valid E-mail.</div>
                </div>

                <div class="col-md-6 ps-2 mb-3">
                  <input type="text" class="form-control " placeholder="Phone +91" id="phone_number" name="phoneNumber" aria-label="Email" aria-describedby="business_name" data-bs-toggle="tooltip" data-bs-placement="right" title="Enter phone number " required>
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                  <div class="invalid-feedback">Please enter valid E-mail.</div>
                </div>
                <div class="col-md-6 ps-2 mb-3">
                  <input type="email" class="form-control" placeholder="Email" id="email" name="email" aria-label="Email" aria-describedby="business_name" data-bs-toggle="tooltip" data-bs-placement="right" title="Enter your Email" required>
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                  <div class="invalid-feedback">Please enter valid E-mail.</div>
                </div>

                <div class="col-md-6 ps-2 mb-3">
                  <input type="tel" class="form-control" placeholder="WhatsApp Number" id="whatsapp" name="whatsapp" aria-label="Email" aria-describedby="business_name" data-bs-toggle="tooltip" data-bs-placement="right" title="Enter whatsapp number">
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                  <div class="invalid-feedback">Please enter valid E-mail.</div>
                </div>

                <div class="col-md-6 ps-2 mb-3">
                  <input type="text" class="form-control" placeholder="Instagram ID" id="instagram_id" name="instagramId" aria-label="instagram_id" aria-describedby="instagram_id" data-bs-toggle="tooltip" data-bs-placement="right" title="Enter Instagram ID">
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                  <div class="invalid-feedback">Please enter valid E-mail.</div>
                </div>

                <div class="col-md-6 ps-2 mb-3">
                  <input type="text" class="form-control" placeholder="Facebook ID" id="facebook_id" name="facebookId" aria-label="facebook_id" aria-describedby="facebook_id" data-bs-toggle="tooltip" data-bs-placement="right" title="Enter Facebook ID">
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                  <div class="invalid-feedback">Please enter valid E-mail.</div>
                </div>

                <div class="col-md-6 ps-2 mb-3">
                  <input type="text" class="form-control" placeholder="Your Website Link" id="website" name="website" aria-label="website" aria-describedby="website" data-bs-toggle="tooltip" data-bs-placement="right" title="Enter Link">
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                  <div class="invalid-feedback">Please enter valid website.</div>
                </div>

                <div class="col-md-6 ps-2 mb-3">
                  <input class="form-control" type="file" id="business_image" name="displayImage" accept="image/*" aria-label="display_image" aria-describedby="display_image" data-bs-toggle="tooltip" data-bs-placement="right" title="Choose file" required>
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                  <div class="invalid-feedback">Please enter valid display_image.</div>
                </div>

                <div class="mb-3">
                  <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="terms" id="terms">
                    <label class="form-check-label" for="terms">I agree to the <a href="javascript:;" class="text-dark">Privacy Policy</a> and <a href="javascript:;" class="text-dark">Terms and Conditions</a>.</label>
                  </div>
                </div>

                <div class="mb-3">
                  <button type="submit" name="create_listing" class="btn bg-gradient-primary w-100"><i class="fas fa-lock"></i> Create Listing</button>
                </div>

              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>




  <script type="text/javascript">
    (() => {
      'use strict'
      document.addEventListener("DOMContentLoaded", function(event) {
        const options = {
          enableHighAccuracy: true,
          timeout: 5000,
          maximumAge: 0
        };
        navigator.geolocation.getCurrentPosition(
          function(position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;
            document.getElementById("latitude").value = latitude;
            document.getElementById("longitude").value = longitude;
            console.log(latitude, longitude);
          },
          function(error) {
            if (error.code === error.PERMISSION_DENIED) {
              alert("Enable Geolocation permission for Location.");
            }
            console.log(error);
          },
          options
        );
      });
      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      const forms = document.querySelectorAll('.needs-validation')

      // Loop over them and prevent submission
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }

          form.classList.add('was-validated')
        }, false)
      })
    })()
  </script>
  <?php
  // include the footer file
  include_once './includes/_footer.php';
  ?>
</body>

</html>
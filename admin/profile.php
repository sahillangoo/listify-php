<?php
// include functions file
require_once __DIR__ . '/../functions/functions.php';
//  check if the user is logged in and is an admin
if (!isAuthenticated() || !isAdmin()) {
  redirect('signin.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- include head file -->
  <?php
  include_once('./includes/_head.php');
  ?>
</head>

<body class="g-sidenav-show  bg-gray-100">
  <!-- include aside file -->
  <?php include_once('./includes/_aside.php'); ?>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- include Navbar file -->
    <?php include_once('./includes/_navbar.php'); ?>
    <div class="container-fluid">
      <div class="page-header min-height-300 border-radius-xl mt-4" style="background-image: url('./assets/img/curved-images/curved0.jpg'); background-position-y: 50%;">
        <span class="mask bg-gradient-primary opacity-6"></span>
      </div>
      <div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">
        <div class="row gx-4">
          <div class="col-auto">
            <div class="avatar avatar-xl position-relative">
              <img src="
              <?php echo sanitize($_SESSION['profile_image']) ?>" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
            </div>
          </div>
          <div class="col-auto my-auto">
            <div class="h-100">
              <h5 class="mb-1">
                <?php
                echo sanitize($_SESSION['username']);
                ?>
              </h5>
              <p class="mb-0 font-weight-bold text-primary text-sm">
                <?php
                echo sanitize($_SESSION['role']);
                ?>
              </p>
              <p class="mb-0 font-weight-bold text-sm">
                Admin Since:
                <?php
                echo sanitize($_SESSION['user_since']);
                ?>
              </p>
            </div>
          </div>

        </div>
      </div>
    </div>

    <?php include './includes/_footer.php'; ?>
    </div>
  </main>


  <!-- include footer file -->
  <?php include './includes/_scripts.php'; ?>

</body>

</html>

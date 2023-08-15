<!DOCTYPE html>
<html lang="en">

<head>
  <!-- include head file -->
  <?php
  include_once('../includes/_config.php');
  include_once('./includes/_head.php');
  include_once('../functions/db_connect.php');
  //  check if the user is logged in or not
  if (!isLoggedIn()) {
    redirect('signin.php');
    exit;
  }
  // function to check if the user role is admin
  function isAdmin(): bool
  {
    return isset($_SESSION["role"]) && $_SESSION["role"] === 'admin';
  }
  // check if user is admin
  if (!isAdmin()) {
    redirect('index.php');
    exit;
  }


  ?>
</head>

<body class="g-sidenav-show  bg-gray-100">
  <!-- include aside file -->
  <?php include_once('./includes/_aside.php'); ?>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- include Navbar file -->
    <?php include_once('./includes/_navbar.php'); ?>

    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Number of Users</p>
                    <h5 class="font-weight-bolder mb-0">
                      <?php
                      try {
                        // Execute a SELECT query to retrieve the number of users
                        $query = "SELECT COUNT(*) as count FROM users";
                        $stmt = $db->query($query);
                        $result = $stmt->fetch();
                        echo $result['count'];
                      } catch (PDOException $ex) {
                        echo "An error occured: " . $ex->getMessage();
                      }
                      ?>
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                    <i class="ni ni-users text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Number of Listings</p>
                    <h5 class="font-weight-bolder mb-0">
                      <?php
                      // Execute a SELECT query to retrieve the number of users
                      $query = "SELECT COUNT(*) as count FROM listings";
                      $stmt = $db->query($query);
                      $result = $stmt->fetch();
                      echo $result['count'];
                      ?>
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                    <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


      <?php include './includes/_footer.php'; ?>
    </div>
  </main>


  <!-- include theme config file -->
  <?php include './includes/_theme_config.php'; ?>

  <!-- include footer file -->
  <?php include './includes/_scripts.php'; ?>

</body>

</html>

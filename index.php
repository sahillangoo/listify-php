<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">

<head>
  <title>Listify - Comprehansive Listing App</title>
  <?php
  // include config file
  include_once './includes/_config.php';
  // include the head file
  include_once './includes/_head.php';
  ?>
</head>

<body class="index-page">
  <?php
  // include the header file
  include_once './includes/_navbar.php';
  // check if the user is logged in echo loggedIn();
  if (isLoggedIn()) {
    echo "User is logged in";
  } else {
    echo "User is not logged in";
  }
  //output user details
  echo "<pre>";
  print_r($_SESSION);
  echo "</pre>";

  // siginout
  if (isset($_POST['signout'])) {
    session_destroy();
    header('location: ./index.php');
    exit();
  }
  ?>
  <!-- -------- START HEADER 1 w/ text and image on right ------- -->
  <header>
    <div class="page-header min-vh-100">
      <div class="oblique position-absolute top-0 h-100 d-md-block d-none">
        <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url(./assets/img/curved-images/curved11.jpg)"></div>
      </div>
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-6 col-md-7 d-flex justify-content-center flex-column">
            <h1 class="text-gradient text-primary">Your Work With</h1>
            <h1 class="mb-4">Soft Design System</h1>
            <p class="lead pe-5 me-5">The time is now for it be okay to be great. People in this world shun people for being nice. </p>
            <div class="buttons">
              <button type="button" class="btn bg-gradient-primary mt-4">Get Started</button>
              <button type="button" class="btn text-primary shadow-none mt-4">Read more</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
  <!-- -------- END HEADER 1 w/ text and image on right ------- -->

  <!-- -------- START Features w/ icons and text on left & gradient title and text on right -------- -->
  <div class="container">
    <div class="row py-4">
      <div class="col-lg-6">
        <h3 class="text-gradient text-primary mb-0 mt-2">Read More About Us</h3>
        <h3>The most important</h3>
        <p>Pain is what we go through as we become older. We get insulted by others, lose trust for those others. We get back stabbed by friends. It becomes harder for us to give others a hand.</p>
        <a href="javascript:;" class="text-primary icon-move-right">More about us
          <i class="fas fa-arrow-right text-sm ms-1"></i>
        </a>
      </div>
      <div class="col-lg-6 mt-lg-0 mt-5 ps-lg-0 ps-0">
        <div class="p-3 info-horizontal">
          <div class="icon icon-shape rounded-circle bg-gradient-primary shadow text-center">
            <i class="fas fa-ship opacity-10"></i>
          </div>
          <div class="description ps-3">
            <p class="mb-0">It becomes harder for us to give others a hand. <br> We get our heart broken by people we love.</p>
          </div>
        </div>

        <div class="p-3 info-horizontal">
          <div class="icon icon-shape rounded-circle bg-gradient-primary shadow text-center">
            <i class="fas fa-handshake opacity-10"></i>
          </div>
          <div class="description ps-3">
            <p class="mb-0">As we live, our hearts turn colder. <br>Cause pain is what we go through as we become older.</p>
          </div>
        </div>
        <div class="p-3 info-horizontal">
          <div class="icon icon-shape rounded-circle bg-gradient-primary shadow text-center">
            <i class="fas fa-hourglass opacity-10"></i>
          </div>
          <div class="description ps-3">
            <p class="mb-0">When we lose family over time. <br> What else could rust the heart more over time? Blackgold.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- -------- END Features w/ icons and text on left & gradient title and text on right -------- -->



  <?php
  // include the footer file
  include_once './includes/_footer.php';
  ?>
</body>

</html>

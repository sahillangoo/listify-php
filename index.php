<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">

<head>
  <title>Listify - Comprehansive Listing App</title>
  <?php

  // turn on error reporting
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  // include routes
  require_once  './routes/routes.php';

  // include the head file
  include_once './includes/_head.php';
  // start session
  session_start();
  // turn on output buffering
  ob_start();
  ?>
</head>

<body class="index-page">
  <?php
  // include the header file
  include_once './includes/_header.php';

  // Function to check if the user is logged in     $_SESSION["loggedin"] === true;
  function isLoggedIn()
  {
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
      return true;
    } else {
      return false;
    }
  }
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


  <?php
  // include the footer file
  include_once './includes/_footer.php';
  ?>
</body>

</html>

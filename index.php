<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">

<head>
  <title>Listify - Comprehansive Listing App</title>
  <?php
  // ! error reporting
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

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


  <?php
  // include the footer file
  include_once './includes/_footer.php';
  ?>
</body>

</html>

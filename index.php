<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">

<head>
  <title>Listify - Comprehansive Listing App</title>
  <?php
  // turn on error reporting
  ini_set('display_errors', 1);
  error_reporting(E_ALL);

  // include the head file
  include_once './includes/_head.php';
  session_start();
  ob_start();

  ?>
</head>

<body class="index-page">
  <?php
  // include the header file
  include_once './includes/_header.php';
  // Function to check if the user is logged in
  function isLoggedIn()
  {
    if (isset($_SESSION['user_id'])) {
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
  ?>
  <!-- signin form html -->
  <form action="../functions/auth/sigin_function.php" method="post">
    <input type="text" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="submit" name="submit" value="Sign In">
  </form><br>
  <!-- signup form html -->
  <form action="../functions/auth/sigin_function.php" method="post">
    <input type="text" name="username" placeholder="Username" required>
    <input type="text" name="email" placeholder="Email" required>
    <input type="text" name="phone" placeholder="Phone" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="submit" name="submit" value="Sign Up">
  </form><br>


</body>

</html>

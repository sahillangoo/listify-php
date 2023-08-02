<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="./assets/img/favicon.png">
  <title>
    Soft UI Design System by Creative Tim
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />

  <!-- Nucleo Icons -->
  <link href="./assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="./assets/css/nucleo-svg.css" rel="stylesheet" />

  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="./assets/css/nucleo-svg.css" rel="stylesheet" />

  <!-- CSS Files -->
  <link id="pagestyle" href="./assets/css/soft-design-system.css?v=1.0.9" rel="stylesheet" />

</head>

<body>

  <?php

  echo "hello world<br>";
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
  <form action="functions/sigin_function.php" method="post">
    <input type="text" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="submit" name="submit" value="Sign In">
  </form><br>
  <!-- signup form html -->
  <form action="functions/signup_function.php" method="post">
    <input type="text" name="username" placeholder="Username" required>
    <input type="text" name="email" placeholder="Email" required>
    <input type="text" name="phone" placeholder="Phone" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="submit" name="submit" value="Sign Up">
  </form><br>
  <!-- signout form html -->
  <form action="functions/auth_function.php" method="post">
    <input type="submit" name="submit" value="Sign Out">
  </form><br>

</body>

</html>

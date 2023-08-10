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

  <?php
  // include the footer file
  include_once './includes/_footer.php';
  ?>
</body>

</html>

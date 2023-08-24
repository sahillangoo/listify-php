<?php
/*
  *This file will handle the create listing form data and insert it into the database

*/
// include functions file
require_once __DIR__ . '/../functions.php';


// Recive the data from the create listing form then validate it and If the listing is a new listing then insert it into the database
if (isset($_POST['create_listing'])) {
  $user_id = $_SESSION['user_id'];
  $businessName = $_POST['businessName'];
  $category = $_POST['category'];
  $description = $_POST['description'];
  $latitude = $_POST['latitude'];
  $longitude = $_POST['longitude'];
  $address = $_POST['address'];
  $city = $_POST['city'];
  $pincode = $_POST['pincode'];
  $phoneNumber = $_POST['phoneNumber'];
  $email = $_POST['email'];
  $whatsapp = $_POST['whatsapp'];
  $instagramId = $_POST['instagramId'];
  $facebookId = $_POST['facebookId'];
  $website = $_POST['website'];
  $reviewsCount = 0;
  $rating = 0;
  $createdAt = date("Y-m-d H:i:s");
  $updatedAt = date("Y-m-d H:i:s");

  // if non required fields whatsapp, facebookId, instagramId, website are empty then set it to NULL
  if (empty($whatsapp)) {
    $whatsapp = NULL;
  }
  if (empty($facebookId)) {
    $facebookId = NULL;
  }
  if (empty($instagramId)) {
    $instagramId = NULL;
  }
  if (empty($website)) {
    $website = NULL;
  }

  // check the data empty and sanitize it
  $requiredFields = array(
    'businessName', 'category', 'description',  'address', 'city', 'pincode', 'phoneNumber', 'email'
  );

  foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
      $_SESSION['errorsession'] = "Please fill all the required fields";
      redirect('create-listing.php');
    }
  }

  // sanitize the input data
  $businessName = sanitize($_POST['businessName']);
  $category = sanitize($_POST['category']);
  $description = sanitize($_POST['description']);
  $address = sanitize($_POST['address']);
  $latitude = sanitize($_POST['latitude']);
  $longitude = sanitize($_POST['longitude']);
  $city = sanitize($_POST['city']);
  $postal_code = sanitize($_POST['pincode']);
  $phoneNumber = sanitize($_POST['phoneNumber']);
  $email = sanitize($_POST['email']);
  $whatsapp = sanitize($_POST['whatsapp']);
  $facebookId = sanitize($_POST['facebookId']);
  $instagramId = sanitize($_POST['instagramId']);
  $website = sanitize($_POST['website']);

  // validate the data
  switch (true) {
    case !preg_match('/^[a-zA-Z0-9\s-]+$/', $businessName):
      $_SESSION['errorsession'] = 'Please enter a valid business name (letters, numbers, spaces, and hyphens only)';
      redirect('create-listing.php');
      break;
    case !preg_match('/^[a-zA-Z0-9\s-]+$/', $category):
      $_SESSION['errorsession'] = 'Please enter a valid category';
      redirect('create-listing.php');
      break;
    case !preg_match('/^[a-zA-Z0-9\s.,!?]+$/', $description):
      $_SESSION['errorsession'] = 'Please enter a valid description (letters, numbers, spaces, and punctuation only)';
      redirect('create-listing.php');
      break;
    case !empty($latitude) && (!is_numeric($latitude) || $latitude < -90 || $latitude > 90):
      $_SESSION['errorsession'] = 'Enable location permission';
      redirect('create-listing.php');
      break;
    case !empty($longitude) && (!is_numeric($longitude) || $longitude < -180 || $longitude > 180):
      $_SESSION['errorsession'] = 'Enable location permission';
      redirect('create-listing.php');
      break;
    case !preg_match('/^[a-zA-Z0-9\s.,-]+$/', $address):
      $_SESSION['errorsession'] = 'Please enter a valid address (letters, numbers, spaces, and punctuation only)';
      redirect('create-listing.php');
      break;
    case !preg_match('/^[a-zA-Z0-9\s-]+$/', $city):
      $_SESSION['errorsession'] = 'Please enter a valid city (letters, numbers, spaces, and hyphens only)';
      redirect('create-listing.php');
      break;
    case !preg_match('/^\d{6}$/', $pincode):
      $_SESSION['errorsession'] = 'Please enter a valid pincode (6 digits only)';
      redirect('create-listing.php');
      break;
    case !preg_match('/^\d{10,}$/', $phoneNumber):
      $_SESSION['errorsession'] = "Invalid phone number. Please enter a 10-digit phone number.";
      redirect('signin.php');
      break;
    case !filter_var($email, FILTER_VALIDATE_EMAIL):
      $_SESSION['errorsession'] = "Check your email address";
      redirect('create-listing.php');
      break;
    case $whatsapp && !preg_match('/^\d{10}$/', $whatsapp):
      $_SESSION['errorsession'] = 'Please enter a valid WhatsApp number (10 digits only)';
      redirect('create-listing.php');
      break;
    case $facebookId && !preg_match('/^[a-zA-Z0-9.]+$/', $facebookId):
      $_SESSION['errorsession'] = 'Please enter a valid Facebook ID (letters, numbers, and periods only)';
      redirect('create-listing.php');
      break;
    case $instagramId && !preg_match('/^[a-zA-Z0-9_]+$/', $instagramId):
      $_SESSION['errorsession'] = 'Please enter a valid Instagram ID (letters, numbers, and underscores only)';
      redirect('create-listing.php');
      break;
    case $website && !filter_var($website, FILTER_VALIDATE_URL):
      $_SESSION['errorsession'] = 'Please enter a valid website URL (e.g. example.com)';
      redirect('create-listing.php');
      break;
    default:
      // check the image is empty and less than 2MB and the image type is jpg, jpeg or png then rename the image to business name and move it to the uploads/business_images/ folder
      $displayImage = $_FILES['displayImage'];
      if (!empty($displayImage)) {
        $max_file_size = 2000000; // 2mb
        $file_extension = pathinfo($displayImage['name'], PATHINFO_EXTENSION);
        $file_extension = strtolower($file_extension);
        switch ($file_extension) {
          case 'jpg':
          case 'jpeg':
          case 'png':
            if ($displayImage['size'] < $max_file_size) {
              $displayImage_name = $businessName . '.jpg';
              $displayImage_path = '../../uploads/business_images/' . $displayImage_name;
              if (move_uploaded_file($displayImage['tmp_name'], $displayImage_path)) {
                $displayImage_name = basename($displayImage_path);
              }
            } else {
              $_SESSION['errorsession'] = "Please upload an image less than 2MB";
              redirect('create-listing.php');
            }
            break;
          default:
            $_SESSION['errorsession'] = "The image you uploaded is not a jpg, jpeg or png image";
            redirect('create-listing.php');
            break;
        }
      } else {
        $_SESSION['errorsession'] = "Please upload an image of your business";
        redirect('create-listing.php');
      }

      // check if the business name already exists in the database
      try {
        $sql = 'SELECT * FROM listings WHERE businessName = :businessName';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':businessName', $businessName);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
          $_SESSION['errorsession'] = 'The business name already exists';
          redirect('account.php');
        } else {
          // insert data into the database
          try {
            $sql = 'INSERT INTO listings (user_id, businessName, category, description, latitude, longitude, address, city, pincode, phoneNumber, email, whatsapp, facebookId, instagramId, website, displayImage, reviewsCount, rating, createdAt, updatedAt) VALUES (:user_id, :businessName, :category, :description, :latitude, :longitude, :address, :city, :pincode, :phoneNumber, :email, :whatsapp, :facebookId, :instagramId, :website, :displayImage, :reviewsCount, :rating, :createdAt, :updatedAt)';

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':businessName', $businessName);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':latitude', $latitude);
            $stmt->bindParam(':longitude', $longitude);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':city', $city);
            $stmt->bindParam(':pincode', $pincode);
            $stmt->bindParam(':phoneNumber', $phoneNumber);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':whatsapp', $whatsapp);
            $stmt->bindParam(':facebookId', $facebookId);
            $stmt->bindParam(':instagramId', $instagramId);
            $stmt->bindParam(':website', $website);
            $stmt->bindParam(':displayImage', $displayImage_name);
            $stmt->bindParam(':reviewsCount', $reviewsCount);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':createdAt', $createdAt);
            $stmt->bindParam(':updatedAt', $updatedAt);

            $stmt->execute();
            // redirect to the account page
            $_SESSION['successsession'] = 'Your listing has been created successfully';
            redirect('account.php');
          } catch (PDOException $e) {
            $_SESSION['errorsession'] = $e->getMessage();
            redirect('create-listing.php');
          } finally {
            $stmt = null;
            $db = null;
          }
        }
      } catch (PDOException $e) {
        $_SESSION['errorsession'] = $e->getMessage();
        redirect('create-listing.php');
      } finally {
        $stmt = null;
      }
      break;
  }
} else {
  $_SESSION['errorsession'] = "Please fill all the required fields else";
  redirect('create-listing.php');
}

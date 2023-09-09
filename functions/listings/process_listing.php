<?php
/*
  *This file will handle the create listing form data and insert it into the database

*/
// include functions file
require_once __DIR__ . '/../functions.php';

try {
  // Recive the data from the create listing form then validate it and If the listing is a new listing then insert it into the database
  if (isset($_POST['create_listing'])) {
    $user_id = sanitize($_SESSION['user_id']);
    $businessName = sanitize($_POST['businessName']);
    $category = sanitize($_POST['category']);
    $description = sanitize($_POST['description']);
    $latitude = sanitize($_POST['latitude']);
    $longitude = sanitize($_POST['longitude']);
    $address = clean($_POST['address']);
    $city = sanitize($_POST['city']);
    $pincode = sanitize($_POST['pincode']);
    $phoneNumber = sanitize($_POST['phoneNumber']);
    $email = clean($_POST['email']);
    $whatsapp = sanitize($_POST['whatsapp']);
    $instagramId = sanitize($_POST['instagramId']);
    $facebookId = sanitize($_POST['facebookId']);
    $website = clean($_POST['website']);
    $featured = 0;
    $active = 1;

    // if non required fields whatsapp, facebookId, instagramId, website are empty then set it to NULL
    $whatsapp = empty($whatsapp) ? null : $whatsapp;
    $facebookId = empty($facebookId) ? null : $facebookId;
    $instagramId = empty($instagramId) ? null : $instagramId;
    $website = empty($website) ? null : $website;

    // check the data empty and sanitize it
    $requiredFields = array(
      'businessName', 'category', 'description',  'address', 'city', 'pincode', 'phoneNumber', 'email'
    );

    foreach ($requiredFields as $field) {
      if (empty($_POST[$field])) {
        throw new Exception("Please fill all the required fields");
      }
    }

    // validate the data
    switch (true) {
      case !preg_match('/^[a-zA-Z0-9 ]{3,30}$/', $businessName):
        throw new Exception('Please enter a valid business name (letters, numbers, spaces, and hyphens only)');
      case !preg_match('/^[a-zA-Z0-9\s-]+$/', $category):
        throw new Exception('Please enter a valid category');
      case !preg_match('/^[\w\s!?"\'&().:;,-]{10,999}$/', $description):
        throw new Exception('Please enter a valid description (letters, numbers, spaces, and punctuation only)');
      case !empty($latitude) && (!is_numeric($latitude) || $latitude < -90 || $latitude > 90):
        throw new Exception('Enable location permission');
      case !empty($longitude) && (!is_numeric($longitude) || $longitude < -180 || $longitude > 180):
        throw new Exception('Enable location permission');
      case !preg_match('/^[a-zA-Z0-9 -,_&]{8,30}$/', $address):
        throw new Exception('Please enter a valid address (letters, numbers, spaces, and punctuation only)');
      case !preg_match('/^[a-zA-Z\s-]+$/', $city):
        throw new Exception('Please enter a valid city (letters, numbers, spaces, and hyphens only)');
      case !preg_match('/^[0-9]{6}$/', $pincode):
        throw new Exception('Please enter a valid pincode (6 digits only)');
      case preg_match('/^[0-9]{10}$/', $phoneNumber):
        throw new Exception("Invalid phone number. Please enter a 10-digit phone number.");
      case !filter_var($email, FILTER_VALIDATE_EMAIL):
        throw new Exception("Check your email address");
      case $whatsapp && !preg_match('/^[0-9]{10}$/', $whatsapp):
        throw new Exception('Please enter a valid WhatsApp number (10 digits only)');
      case $facebookId && !preg_match('/^(?!.*\.\.)(?!.*\.$)[^\W][\w.]{3,30}$/', $facebookId):
        throw new Exception('Please enter a valid Facebook ID (letters, numbers, and periods only)');
      case $instagramId && !preg_match('/^(?!.*\.\.)(?!.*\.$)[^\W][\w.]{3,30}$/', $instagramId):
        throw new Exception('Please enter a valid Instagram ID (letters, numbers, and underscores only)');
      case $website && !filter_var($website, FILTER_VALIDATE_URL):
        throw new Exception('Please enter a valid website URL (e.g. example.com)');
      default:
        // check the image is empty and less than 2MB and the image type is jpg, jpeg or png then rename the image to business name and move it to the uploads/business_images/ folder
        $displayImage = $_FILES['displayImage'];
        if (empty($displayImage)) {
          throw new Exception("Please upload an image of your business");
        }
        $max_file_size = 2000000; // 2mb
        $file_extension = pathinfo($displayImage['name'], PATHINFO_EXTENSION);
        $file_extension = strtolower($file_extension);
        switch ($file_extension) {
          case 'jpg':
          case 'jpeg':
          case 'png':
            if ($displayImage['size'] >= $max_file_size) {
              throw new Exception("Please upload an image less than 2MB");
            }
            break;
          default:
            throw new Exception("The image you uploaded is not a jpg, jpeg or png image");
        }
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $displayImage['tmp_name']);
        if (!in_array($mime_type, ['image/jpeg', 'image/png'])) {
          throw new Exception("Invalid file type. Please upload a JPEG or PNG image.");
        }
        $displayImage_name = $businessName . '.jpg';
        $displayImage_path = '../../uploads/business_images/' . $displayImage_name;
        if (!move_uploaded_file($displayImage['tmp_name'], $displayImage_path)) {
          throw new Exception("Failed to upload image.");
        }

        // check if the business name already exists in the database
        $sql = 'SELECT * FROM listings WHERE businessName = :businessName';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':businessName', $businessName);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
          throw new Exception('The business name already exists');
        }

        // insert data into the database
        $sql = 'INSERT INTO listings (user_id, businessName, category, description, featured, active, latitude, longitude, address, city, pincode, phoneNumber, email, whatsapp, facebookId, instagramId, website, displayImage) VALUES (:user_id, :businessName, :category, :description, :featured, :active, :latitude, :longitude, :address, :city, :pincode, :phoneNumber, :email, :whatsapp, :facebookId, :instagramId, :website, :displayImage)';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':businessName', $businessName);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':featured', $featured);
        $stmt->bindParam(':active', $active);
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
        $stmt->execute();

        // redirect to the account page
        $_SESSION['successsession'] = 'Your listing has been created successfully';
        redirect('account.php');
    }
  } else {
    throw new Exception("Please fill all the required fields else");
  }
} catch (Exception $e) {
  $_SESSION['errorsession'] = $e->getMessage();
  redirect('add-listing.php');
} finally {
  $stmt = null;
  $db = null;
}

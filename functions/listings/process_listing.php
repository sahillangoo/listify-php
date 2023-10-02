<?php
/**
 * This file handles the create listing form data and inserts it into the database.
 *
 * Author: SahilLangoo
 * Last modified: 23/8/2023
 */

require_once __DIR__ . '/../functions.php';

try {
  // Use CSRF protection on this form
  $csrf_token = $_POST['csrf_token'] ?? '';
  if (!hash_equals($_SESSION['csrf_token'], $csrf_token)) {
    // Reset the CSRF token
    unset($_SESSION['csrf_token']);
    throw new Exception('CSRF token validation failed.');
  }

  // Receive the data from the create listing form, validate it, and insert it into the database
  if (isset($_POST['create_listing'])) {
    // Sanitize the data received from the form
    $user_id = sanitize($_SESSION['user_id']);
    $businessName = sanitize($_POST['businessName']);
    $category = sanitize($_POST['category']);
    $description = sanitize($_POST['description']);
    $address = clean($_POST['address']);
    $city = sanitize($_POST['city']);
    $pincode = sanitize($_POST['pincode']);
    $phone = sanitize($_POST['phone']);
    $email = clean($_POST['email']);
    $whatsapp = sanitize($_POST['whatsapp']) ?: null;
    $instagramId = sanitize($_POST['instagramId']) ?: null;
    $facebookId = sanitize($_POST['facebookId']) ?: null;
    $website = clean($_POST['website']) ?: null;

    // Set non-required fields to NULL or 0 if empty
    $latitude = empty($_POST['latitude']) ? 0 : sanitize($_POST['latitude']);
    $longitude = empty($_POST['longitude']) ? 0 : sanitize($_POST['longitude']);

    // Check if required fields are empty and throw an exception if so
    $requiredFields = ['businessName', 'category', 'description', 'address', 'city', 'pincode', 'phone', 'email'];
    foreach ($requiredFields as $field) {
      if (empty($_POST[$field])) {
        throw new Exception("Please fill in the $field field");
      }
    }

    $featured = 0;
    $active = 1;

    // Validate the data
    validateFields($_POST);

    // Check if the business name already exists in the database
    checkBusinessExists($businessName, $city, $phone);

    // Validate the image
    $displayImage_name = validateImage($_FILES['displayImage'], $businessName, $city);

    // Insert data into the database
    insertListing($user_id, $businessName, $category, $description, $featured, $active, $latitude, $longitude, $address, $city, $pincode, $phone, $email, $whatsapp, $facebookId, $instagramId, $website, $displayImage_name);

    // Redirect to the account page
    $_SESSION['successsession'] = 'Your listing has been created successfully';
    redirect('account.php');
  } else {
    throw new Exception("Please fill all the required fields");
  }
} catch (PDOException $e) {
  $_SESSION['errorsession'] = 'Database error: ' . $e->getMessage();
  redirect('add-listing.php');
} catch (Exception $e) {
  $_SESSION['errorsession'] = $e->getMessage();
  redirect('add-listing.php');
}

function validateFields($fields)
{
  foreach ($fields as $field => $value) {
    switch ($field) {
      case 'businessName':
        if (!preg_match('/^[a-zA-Z0-9 ]{3,30}$/', $value)) {
          throw new Exception('Please enter a valid business name (letters, numbers, spaces, and hyphens only)');
        }
        break;
      case 'category':
        if (!preg_match('/^[a-zA-Z0-9\s-]+$/', $value)) {
          throw new Exception('Please enter a valid category');
        }
        break;
      case 'description':
        if (!preg_match('/^[\w\s!?"\'&().:;,-]{10,999}$/', $value)) {
          throw new Exception('Please enter a valid description (letters, numbers, spaces, and punctuation only)');
        }
        break;
      case 'latitude':
        if (!empty($value) && (!is_numeric($value) || $value < -90 || $value > 90)) {
          throw new Exception('Enable location permission');
        }
        break;
      case 'longitude':
        if (!empty($value) && (!is_numeric($value) || $value < -180 || $value > 180)) {
          throw new Exception('Enable location permission');
        }
        break;
      case 'address':
        if (!preg_match('/^[a-zA-Z0-9 -,_&]{8,30}$/', $value)) {
          throw new Exception('Please enter a valid address (letters, numbers, spaces, and punctuation only)');
        }
        break;
      case 'city':
        if (!preg_match('/^[a-zA-Z\s-]+$/', $value)) {
          throw new Exception('Please enter a valid city (letters, numbers, spaces, and hyphens only)');
        }
        break;
      case 'pincode':
        if (!preg_match('/^[0-9]{6}$/', $value)) {
          throw new Exception('Please enter a valid pincode (6 digits only)');
        }
        break;
      case 'phone':
        if (!preg_match('/^[0-9]{10}$/', $value)) {
          throw new Exception("Invalid phone number. Please enter a 10-digit phone number.");
        }
        break;
      case 'email':
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
          throw new Exception("Check your email address");
        }
        break;
      case 'whatsapp':
        if ($value && !preg_match('/^[0-9]{10}$/', $value)) {
          throw new Exception('Please enter a valid WhatsApp number (10 digits only)');
        }
        break;
      case 'facebookId':
        if ($value && !preg_match('/^(?!.*\.\.)(?!.*\.$)[^\W][\w.]{3,30}$/', $value)) {
          throw new Exception('Please enter a valid Facebook ID (letters, numbers, and periods only)');
        }
        break;
      case 'instagramId':
        if ($value && !preg_match('/^(?!.*\.\.)(?!.*\.$)[^\W][\w.]{3,30}$/', $value)) {
          throw new Exception('Please enter a valid Instagram ID (letters, numbers, and underscores only)');
        }
        break;
      case 'website':
        if ($value && !filter_var($value, FILTER_VALIDATE_URL)) {
          throw new Exception('Please enter a valid website URL (e.g. example.com)');
        }
        break;
      default:
        break;
    }
  }
}

function validateImage($displayImage, $businessName, $city)
{
  // Check if file was uploaded
  if (empty($displayImage)) {
    throw new Exception("Please upload an image of your business");
  }

  // Check file size
  $max_file_size = 2000000; // 2mb
  if ($displayImage['size'] >= $max_file_size) {
    throw new Exception("Please upload an image less than 2MB");
  }

  // Check file type
  $file_extension = pathinfo($displayImage['name'], PATHINFO_EXTENSION);
  $file_extension = strtolower($file_extension);
  if (!in_array($file_extension, ['jpg', 'jpeg', 'png'])) {
    throw new Exception("The image you uploaded is not a jpg, jpeg or png image");
  }

  // Check image dimensions
  list($width, $height) = getimagesize($displayImage['tmp_name']);
  if ($width < 500 || $height < 500) {
    throw new Exception("Please upload an image with dimensions of at least 500x500 pixels");
  }

  // Compress image
  $quality = 75; // Set JPEG quality
  $image = imagecreatefromstring(file_get_contents($displayImage['tmp_name']));
  imagejpeg($image, $displayImage['tmp_name'], $quality);

  // Rename image
  $displayImage_name = sanitize($businessName) . '_' . sanitize($city) . '.' . $file_extension;

  // Check if image already exists in directory
  $i = 1;
  while (file_exists('../../uploads/business_images/' . $displayImage_name)) {
    $displayImage_name = sanitize($businessName) . '_' . sanitize($city) . '_' . $i . '.' . $file_extension;
    $i++;a
  }

  // Move image to uploads folder
  $displayImage_path = '../../uploads/business_images/' . $displayImage_name;
  if (!move_uploaded_file($displayImage['tmp_name'], $displayImage_path)) {
    throw new Exception("Failed to upload image.");
  }

  return $displayImage_name;
}
function checkBusinessExists($businessName, $city, $phone)
{
  global $db;

  $sql = 'SELECT COUNT(*) FROM listings WHERE businessName = :businessName AND city = :city AND phoneNumber = :phone';
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':businessName', $businessName);
  $stmt->bindParam(':city', $city);
  $stmt->bindParam(':phone', $phone);
  $stmt->execute();
  $result = $stmt->fetchColumn();
  if ($result > 0) {
    throw new Exception('The business already exists');
  }
}

function insertListing($user_id, $businessName, $category, $description, $featured, $active, $latitude, $longitude, $address, $city, $pincode, $phone, $email, $whatsapp, $facebookId, $instagramId, $website, $displayImage_name)
{
  global $db;

  $sql = 'INSERT INTO listings (user_id, businessName, category, description, featured, active, latitude, longitude, address, city, pincode, phoneNumber, email, whatsapp, facebookId, instagramId, website, displayImage) VALUES (:user_id, :businessName, :category, :description, :featured, :active, :latitude, :longitude, :address, :city, :pincode, :phone, :email, :whatsapp, :facebookId, :instagramId, :website, :displayImage)';
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
  $stmt->bindParam(':phone', $phone);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':whatsapp', $whatsapp);
  $stmt->bindParam(':facebookId', $facebookId);
  $stmt->bindParam(':instagramId', $instagramId);
  $stmt->bindParam(':website', $website);
  $stmt->bindParam(':displayImage', $displayImage_name);
  $stmt->execute();
}

<?php

/**
 * This file handles the create listing form data and inserts it into the database.
 *
 * Author: SahilLangoo
 * Last modified: 23/8/2023
 */

require_once __DIR__ . '/../functions.php';

try {
  $formData = $_POST;
  $fileData = $_FILES['displayImage'];

  // Validate CSRF token
  $csrfToken = $formData['csrf_token'] ?? '';
  if (!hash_equals($_SESSION['csrf_token'], $csrfToken)) {
    unset($_SESSION['csrf_token']);
    throw new Exception('CSRF token validation failed.');
  }

  // Validate required fields
  $requiredFields = ['businessName', 'category', 'description', 'address', 'city', 'pincode', 'phone', 'email'];
  foreach ($requiredFields as $field) {
    if (empty($formData[$field])) {
      throw new Exception("Please fill in the $field field");
    }
  }

  // Sanitize form data
  $userId = sanitize($_SESSION['user_id']);
  $businessName = sanitize($formData['businessName']);
  $category = sanitize($formData['category']);
  $description = clean($formData['description']);
  $address = clean($formData['address']);
  $city = sanitize($formData['city']);
  $pincode = sanitize($formData['pincode']);
  $phone = sanitize($formData['phone']);
  $email = sanitize($formData['email']);
  $whatsapp = sanitize($formData['whatsapp']);
  $instagramId = sanitize($formData['instagramId']);
  $latitude = empty($formData['latitude']) ? 0 : sanitize($formData['latitude']);
  $longitude = empty($formData['longitude']) ? 0 : sanitize($formData['longitude']);
  $website = clean($formData['website']);
  $facebookId = sanitize($formData['facebookId']);

  // Validate form data
  validateFields($formData);

  // Check if the business name already exists in the database
  checkBusinessExists($businessName, $city, $phone);

  // Validate the image
  $displayImageName = validateImage($fileData, $businessName, $city);

  // Insert data into the database
  $featured = 0;
  $active = 1;
  insertListing($userId, $businessName, $category, $description, $featured, $active, $latitude, $longitude, $address, $city, $pincode, $phone, $email, $whatsapp, $facebookId, $instagramId, $website, $displayImageName);

  // Redirect to the account page
  $_SESSION['successsession'] = 'Your listing has been created successfully';
  redirect('account.php');
} catch (PDOException $e) {
  error_log('Database error: ' . $e->getMessage());
  $_SESSION['errorsession'] = 'An error occurred while processing your request. Please try again later.';
  redirect('add-listing.php');
} catch (Exception $e) {
  error_log('Error: ' . $e->getMessage());
  $_SESSION['errorsession'] = $e->getMessage();
  redirect('add-listing.php');
}

// Function to check if the business already exists in the database
function checkBusinessExists(string $businessName, string $city, string $phone): void
{
  global $db;

  $sql = 'SELECT COUNT(*) FROM listings WHERE businessName = :businessName AND city = :city AND phoneNumber = :phone';
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':businessName', $businessName, PDO::PARAM_STR);
  $stmt->bindParam(':city', $city, PDO::PARAM_STR);
  $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);

  try {
    $stmt->execute();
  } catch (PDOException $e) {
    throw new Exception('An error occurred while checking if the business already exists in the database.');
  }

  $count = $stmt->fetchColumn();

  if ($count > 0) {
    throw new Exception('A business with the same name, city, and phone number already exists in the database.');
  }
}

// Function to insert the listing into the database
function insertListing($user_id, $businessName, $category, $description, $featured, $active, $latitude, $longitude, $address, $city, $pincode, $phoneNumber, $email, $whatsapp, $facebookId, $instagramId, $website, $displayImage_name)
{
  global $db;

  $sql = 'INSERT INTO listings (user_id, businessName, category, description, featured, active, latitude, longitude, address, city, pincode, phoneNumber, email, whatsapp, facebookId, instagramId, website, displayImage) VALUES (:user_id, :businessName, :category, :description, :featured, :active, :latitude, :longitude, :address, :city, :pincode, :phoneNumber, :email, :whatsapp, :facebookId, :instagramId, :website, :displayImage_name)';
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
  $stmt->bindParam(':businessName', $businessName, PDO::PARAM_STR);
  $stmt->bindParam(':description', $description, PDO::PARAM_STR);
  $stmt->bindParam(':category', $category, PDO::PARAM_STR);
  $stmt->bindParam(':featured', $featured, PDO::PARAM_BOOL);
  $stmt->bindParam(':active', $active, PDO::PARAM_BOOL);
  $stmt->bindParam(':latitude', $latitude, PDO::PARAM_STR);
  $stmt->bindParam(':longitude', $longitude, PDO::PARAM_STR);
  $stmt->bindParam(':address', $address, PDO::PARAM_STR);
  $stmt->bindParam(':city', $city, PDO::PARAM_STR);
  $stmt->bindParam(':pincode', $pincode, PDO::PARAM_INT);
  $stmt->bindParam(':phoneNumber', $phoneNumber, PDO::PARAM_INT);
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->bindParam(':whatsapp', $whatsapp, PDO::PARAM_INT);
  $stmt->bindParam(':facebookId', $facebookId, PDO::PARAM_STR);
  $stmt->bindParam(':instagramId', $instagramId, PDO::PARAM_STR);
  $stmt->bindParam(':website', $website, PDO::PARAM_STR);
  $stmt->bindParam(':displayImage_name', $displayImage_name, PDO::PARAM_STR);
  $stmt->execute();

  $listing_id = $db->lastInsertId();
  error_log("Listing id is $listing_id");

  return $listing_id;
}

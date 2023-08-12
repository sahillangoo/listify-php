<?php
/*
  *This file will handle the create listing form data and insert it into the database
List of functions:

*/

// Include the database connection file
include_once './../db_connect.php';
// Include the functions file
include_once './../functions.php';

// Recive the data from the create listing form then validate it and If the listing is a new listing then insert it into the database
if (isset($_POST['create_listing'])) {
  $user_id = $_SESSION['id'];
  $business_name = $_POST['business_name'];
  $category = $_POST['category'];
  $description = $_POST['description'];
  $address = $_POST['address'];
  $city = $_POST['city'];
  $pincode = $_POST['pincode'];
  $phone_number = $_POST['phone_number'];
  $email = $_POST['email'];
  $whatsapp = $_POST['whatsapp'];
  $facebook_id = $_POST['facebook_id'];
  $instagram_id = $_POST['instagram_id'];
  $website = $_POST['website'];
  $display_image = $_FILES['display_image'];
  $reviews_count = 0;
  $rating = 0;
}












?>

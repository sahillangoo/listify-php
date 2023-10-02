<?php

/**
 * This file handles the update listing form data and updates it into the database.
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
    if (!isset($_POST['update_listing'])) {
        throw new Exception("Please fill all the required fields");
    }

    // Sanitize the data received from the form
    $listing_id = clean($_POST['listing_id']);
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

    // Validate the data
    validateFields($_POST);

    // Check if the business name already exists in the database
    checkBusinessExists($businessName, $city, $phone);

    // Validate the image
    $displayImage_name = null;
    if (!empty($_FILES['displayImage'])) {
        $displayImage_name = validateImage($_FILES['displayImage'], $businessName, $city);
    }

    // Update data into the database
    updateListing($listing_id, $businessName, $category, $description, $latitude, $longitude, $address, $city, $pincode, $phone, $email, $whatsapp, $facebookId, $instagramId, $website, $displayImage_name);

    // Redirect to the account page
    $_SESSION['successsession'] = 'Your listing has been created successfully';
    redirect('account.php');
} catch (PDOException $e) {
    $_SESSION['errorsession'] = 'Database error: ' . $e->getMessage();
    redirect('update-listing.php');
} catch (Exception $e) {
    $_SESSION['errorsession'] = $e->getMessage();
    redirect('update-listing.php');
}

// Functions
function validateFields($fields)
{
    $validations = [
        'businessName' => '/^[a-zA-Z0-9 ]{3,30}$/',
        'category' => '/^[a-zA-Z0-9\s-]+$/',
        'description' => '/^[\w\s!?"\'&().:;,-]{10,999}$/',
        'latitude' => '/^[-]?([0-8]?[0-9]|90)\.[0-9]{1,15}$/',
        'longitude' => '/^[-]?([1]?[0-7]?[0-9]|[1-8]?[0-9]|90)\.[0-9]{1,15}$/',
        'address' => '/^[a-zA-Z0-9 -,_&]{8,30}$/',
        'city' => '/^[a-zA-Z\s-]+$/',
        'pincode' => '/^[0-9]{6}$/',
        'phone' => '/^[0-9]{10}$/',
        'email' => FILTER_VALIDATE_EMAIL,
        'whatsapp' => '/^[0-9]{10}$/',
        'facebookId' => '/^(?!.*\.\.)(?!.*\.$)[^\W][\w.]{3,30}$/',
        'instagramId' => '/^(?!.*\.\.)(?!.*\.$)[^\W][\w.]{3,30}$/',
        'website' => FILTER_VALIDATE_URL
    ];

    foreach ($fields as $field => $value) {
        if (isset($validations[$field])) {
            if ($validations[$field] === FILTER_VALIDATE_EMAIL) {
                if (!filter_var($value, $validations[$field])) {
                    throw new Exception('Please enter a valid email address');
                }
            } elseif (!preg_match($validations[$field], $value)) {
                throw new Exception("Please enter a valid $field");
            }
        }
    }
}

function validateImage($displayImage, $businessName, $city)
{
    // Check if file was uploaded
    if (empty($displayImage)) {
        throw new Exception('Please upload an image of your business');
    }

    // Check file size
    $max_file_size = 2000000; // 2mb
    if ($displayImage['size'] >= $max_file_size) {
        throw new Exception('Please upload an image less than 2MB');
    }

    // Check file type
    $file_extension = pathinfo($displayImage['name'], PATHINFO_EXTENSION);
    $file_extension = strtolower($file_extension);
    if (!in_array($file_extension, ['jpg', 'jpeg', 'png'])) {
        throw new Exception('The image you uploaded is not a jpg, jpeg or png image');
    }

    // Check image dimensions
    list($width, $height) = getimagesize($displayImage['tmp_name']);
    if ($width < 500 || $height < 500) {
        throw new Exception('Please upload an image with dimensions of at least 500x500 pixels');
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
        $i++;
    }

    // Move image to uploads folder
    $displayImage_path = '../../uploads/business_images/' . $displayImage_name;
    if (!move_uploaded_file($displayImage['tmp_name'], $displayImage_path)) {
        throw new Exception('Failed to upload image.');
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

function updateListing($listing_id, $businessName, $category, $description, $latitude, $longitude, $address, $city, $pincode, $phone, $email, $whatsapp, $facebookId, $instagramId, $website, $displayImage_name)
{
    global $db;

    try {
        $db->beginTransaction();

        $sql = 'UPDATE listings SET businessName = :businessName, category = :category, description = :description, latitude = :latitude, longitude = :longitude, address = :address, city = :city, pincode = :pincode, phoneNumber = :phone, email = :email, whatsapp = :whatsapp, facebookId = :facebookId, instagramId = :instagramId, website = :website';
        if (!empty($displayImage_name)) {
            $sql .= ', displayImage = :displayImage';
        }
        $sql .= ' WHERE listing_id = :listing_id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':businessName', $businessName);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':description', $description);
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
        if (!empty($displayImage_name)) {
            $stmt->bindParam(':displayImage', $displayImage_name);
        }
        $stmt->bindParam(':listing_id', $listing_id);
        $stmt->execute();

        $db->commit();
    } catch (PDOException $e) {
        $db->rollBack();
        throw new Exception('Database error: ' . $e->getMessage());
    }
}

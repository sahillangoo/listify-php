<?php

require_once __DIR__ . '/../functions.php';

try {
    // Use CSRF protection on this form
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'], $csrf_token)) {
        // Reset the CSRF token
        unset($_SESSION['csrf_token']);
        throw new Exception('CSRF token validation failed.');
    }

    // Receive the data from the update listing form and sanitize it
    $listing_id = sanitize($_POST['listing_id']);
    $businessName = sanitize($_POST['businessName']);
    $category = sanitize($_POST['category']);
    $description = sanitize($_POST['description']);
    $address = sanitize($_POST['address']);
    $city = sanitize($_POST['city']);
    $pincode = sanitize($_POST['pincode']);
    $phone = sanitize($_POST['phone']);
    $email = sanitize($_POST['email']);
    $whatsapp = sanitize($_POST['whatsapp']) ?: null;
    $instagramId = sanitize($_POST['instagramId']) ?: null;
    $facebookId = sanitize($_POST['facebookId']) ?: null;
    $website = sanitize($_POST['website']) ?: null;
    $latitude = empty($_POST['latitude']) ? 0 : sanitize($_POST['latitude']);
    $longitude = empty($_POST['longitude']) ? 0 : sanitize($_POST['longitude']);

    // Validate the data
    validateFields($_POST);

    // Check if the business name already exists in the database
    checkBusinessExists($businessName, $city,
        $phone,
        $listing_id
    );

    // Validate the image
    $displayImage_name = null;
    if (!empty($_FILES['displayImage'])) {
        $displayImage_name = validateImage($_FILES['displayImage'], $businessName, $city);
    }

    // Update data into the database
    updateListing($listing_id, $businessName, $category, $description, $latitude, $longitude, $address, $city, $pincode, $phone, $email, $whatsapp, $facebookId, $instagramId, $website, $displayImage_name);

    // Redirect to the account page
    $_SESSION['successsession'] = 'Your listing has been updated successfully';
    redirect('account.php');
} catch (PDOException $e) {
    $_SESSION['errorsession'] = 'Database error: ' . $e->getMessage();
    redirect('update-listing.php');
} catch (Exception $e) {
    $_SESSION['errorsession'] = $e->getMessage();
    redirect('update-listing.php');
}

// Function to check if the business already exists in the database
function checkBusinessExists(string $businessName, string $city, string $phone, int $listing_id): void
{
    global $db;

    $sql = 'SELECT COUNT(*) FROM listings WHERE businessName = :businessName AND city = :city AND phoneNumber = :phone AND id != :listing_id';
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':businessName', $businessName, PDO::PARAM_STR);
    $stmt->bindParam(':city', $city, PDO::PARAM_STR);
    $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
    $stmt->bindParam(':listing_id', $listing_id, PDO::PARAM_INT);

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

// Function to update the listing in the database
function updateListing(int $listing_id, string $businessName, string $category, string $description, float $latitude, float $longitude, string $address, string $city, int $pincode, int $phone, string $email, int $whatsapp = null, string $facebookId = null, string $instagramId = null, string $website = null, string $displayImage_name = null): void
{
    global $db;

    $sql = 'UPDATE listings SET businessName = :businessName, category = :category, description = :description, latitude = :latitude, longitude = :longitude, address = :address, city = :city, pincode = :pincode, phoneNumber = :phone, email = :email, whatsapp = :whatsapp, facebookId = :facebookId, instagramId = :instagramId, website = :website';
    if (!is_null($displayImage_name)) {
        $sql .= ', displayImage = :displayImage_name';
    }
    $sql .= ' WHERE id = :listing_id';

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':listing_id', $listing_id, PDO::PARAM_INT);
    $stmt->bindParam(':businessName', $businessName, PDO::PARAM_STR);
    $stmt->bindParam(':category', $category, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':latitude', $latitude, PDO::PARAM_STR);
    $stmt->bindParam(':longitude', $longitude, PDO::PARAM_STR);
    $stmt->bindParam(':address', $address, PDO::PARAM_STR);
    $stmt->bindParam(':city', $city, PDO::PARAM_STR);
    $stmt->bindParam(':pincode', $pincode, PDO::PARAM_INT);
    $stmt->bindParam(':phone', $phone, PDO::PARAM_INT);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':whatsapp', $whatsapp, PDO::PARAM_INT);
    $stmt->bindParam(':facebookId', $facebookId, PDO::PARAM_STR);
    $stmt->bindParam(':instagramId', $instagramId, PDO::PARAM_STR);
    $stmt->bindParam(':website', $website, PDO::PARAM_STR);
    if (!is_null($displayImage_name)) {
        $stmt->bindParam(':displayImage_name', $displayImage_name, PDO::PARAM_STR);
    }
    $stmt->execute();
}

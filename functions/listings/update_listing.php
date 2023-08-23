<?php
/*
  *This file will handle the update listing form data and update it in the database

*/

// Include the database connection file
include_once './../db_connect.php';
// Include the functions file
include_once './../functions.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['errorsession'] = "Please log in to update your listing";
    redirect('signin.php');
}

// Receive the data from the update listing form then validate it and update the listing in the database
if (isset($_POST['update_listing'])) {
    $user_id = $_SESSION['user_id'];
    $listing_id = $_POST['listing_id'];
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
            redirect("update-listing.php?id=$listing_id");
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
            redirect("update-listing.php?id=$listing_id");
            break;
        case !preg_match('/^[a-zA-Z0-9\s-]+$/', $category):
            $_SESSION['errorsession'] = 'Please enter a valid category';
            redirect("update-listing.php?id=$listing_id");
            break;
        case !preg_match('/^[a-zA-Z0-9\s.,!?]+$/', $description):
            $_SESSION['errorsession'] = 'Please enter a valid description (letters, numbers, spaces, and punctuation only)';
            redirect("update-listing.php?id=$listing_id");
            break;
        case !empty($latitude) && (!is_numeric($latitude) || $latitude < -90 || $latitude > 90):
            $_SESSION['errorsession'] = 'Enable location permission';
            redirect("update-listing.php?id=$listing_id");
            break;
        case !empty($longitude) && (!is_numeric($longitude) || $longitude < -180 || $longitude > 180):
            $_SESSION['errorsession'] = 'Enable location permission';
            redirect("update-listing.php?id=$listing_id");
            break;
        case !preg_match('/^[a-zA-Z0-9\s.,-]+$/', $address):
            $_SESSION['errorsession'] = 'Please enter a valid address (letters, numbers, spaces, and punctuation only)';
            redirect("update-listing.php?id=$listing_id");
            break;
        case !preg_match('/^[a-zA-Z0-9\s-]+$/', $city):
            $_SESSION['errorsession'] = 'Please enter a valid city (letters, numbers, spaces, and hyphens only)';
            redirect("update-listing.php?id=$listing_id");
            break;
        case !preg_match('/^\d{6}$/', $pincode):
            $_SESSION['errorsession'] = 'Please enter a valid pincode (6 digits only)';
            redirect("update-listing.php?id=$listing_id");
            break;
        case !preg_match('/^\d{10,}$/', $phoneNumber):
            $_SESSION['errorsession'] = "Invalid phone number. Please enter a 10-digit phone number.";
            redirect("update-listing.php?id=$listing_id");
            break;
        case !filter_var($email, FILTER_VALIDATE_EMAIL):
            $_SESSION['errorsession'] = "Check your email address";
            redirect("update-listing.php?id=$listing_id");
            break;
        case $whatsapp && !preg_match('/^\d{10}$/', $whatsapp):
            $_SESSION['errorsession'] = 'Please enter a valid WhatsApp number (10 digits only)';
            redirect("update-listing.php?id=$listing_id");
            break;
        case $facebookId && !preg_match('/^[a-zA-Z0-9.]+$/', $facebookId):
            $_SESSION['errorsession'] = 'Please enter a valid Facebook ID (letters, numbers, and periods only)';
            redirect("update-listing.php?id=$listing_id");
            break;
        case $instagramId && !preg_match('/^[a-zA-Z0-9_]+$/', $instagramId):
            $_SESSION['errorsession'] = 'Please enter a valid Instagram ID (letters, numbers, and underscores only)';
            redirect("update-listing.php?id=$listing_id");
            break;
        case $website && !filter_var($website, FILTER_VALIDATE_URL):
            $_SESSION['errorsession'] = 'Please enter a valid website URL (e.g. example.com)';
            redirect("update-listing.php?id=$listing_id");
            break;
        default:
            // check if the listing belongs to the user
            try {
                $sql = 'SELECT * FROM listings WHERE id = :listing_id AND user_id = :user_id';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':listing_id', $listing_id);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$result) {
                    $_SESSION['errorsession'] = 'You are not authorized to update this listing';
                    redirect('account.php');
                } else {
                    // update the data in the database
                    try {
                        $sql = 'UPDATE listings SET businessName = :businessName, category = :category, description = :description, latitude = :latitude, longitude = :longitude, address = :address, city = :city, pincode = :pincode, phoneNumber = :phoneNumber, email = :email, whatsapp = :whatsapp, facebookId = :facebookId, instagramId = :instagramId, website = :website, updatedAt = :updatedAt WHERE id = :listing_id';

                        $stmt = $db->prepare($sql);
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
                        $stmt->bindParam(':listing_id', $listing_id);
                        $stmt->bindParam(':updatedAt', $updatedAt);

                        $stmt->execute();
                        // redirect to the account page
                        $_SESSION['successsession'] = 'Your listing has been updated successfully';
                        redirect('account.php');
                    } catch (PDOException $e) {
                        $_SESSION['errorsession'] = $e->getMessage();
                        redirect("update-listing.php?id=$listing_id");
                    } finally {
                        $stmt = null;
                        $db = null;
                    }
                }
            } catch (PDOException $e) {
                $_SESSION['errorsession'] = $e->getMessage();
                redirect("update-listing.php?id=$listing_id");
            } finally {
                $stmt = null;
            }
            break;
    }
} else {
    $_SESSION['errorsession'] = "Please fill all the required fields";
    redirect("update-listing.php?id=$listing_id");
}

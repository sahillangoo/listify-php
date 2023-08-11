<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $business_name = filter_input(INPUT_POST, 'business_name', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
    $whatsapp = filter_input(INPUT_POST, 'whatsapp', FILTER_SANITIZE_NUMBER_INT);
    $instagram_id = filter_input(INPUT_POST, 'instagram_id', FILTER_SANITIZE_STRING);
    $phone_number = filter_input(INPUT_POST, 'phone_number', FILTER_SANITIZE_NUMBER_INT);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $display_image = filter_input(INPUT_POST, 'display_image', FILTER_SANITIZE_URL);

    try {
        $sql = "INSERT INTO listings (business_name, description, category, address, city, whatsapp, instagram_id, phone_number, email, display_image)
                VALUES (:business_name, :description, :category, :address, :city, :whatsapp, :instagram_id, :phone_number, :email, :display_image)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':business_name' => $business_name,
            ':description' => $description,
            ':category' => $category,
            ':address' => $address,
            ':city' => $city,
            ':whatsapp' => $whatsapp,
            ':instagram_id' => $instagram_id,
            ':phone_number' => $phone_number,
            ':email' => $email,
            ':display_image' => $display_image
        ]);
        echo "New record created successfully";
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

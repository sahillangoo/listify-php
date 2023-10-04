<?php
// include functions file
require_once __DIR__ . '/../functions/functions.php';

// get the id and value parameters from the URL
$id = $_GET['id'];
$value = $_GET['value'];

// update the active status in the database
try {
  $sql = "UPDATE listings SET active = :value WHERE id = :id";
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':value', $value, PDO::PARAM_INT);
  $stmt->bindParam(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  $success = true;
} catch (PDOException $e) {
  $success = false;
} finally {
  $stmt = null;
}

// return a JSON response indicating success or failure
header('Content-Type: application/json');
echo json_encode(['success' => $success]);

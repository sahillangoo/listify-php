<?php
// include functions file
require_once __DIR__ . '/../functions/functions.php';

// Get the ID and value from the query string
$id = $_GET['id'];
$value = $_GET['value'];

// Update the "featured" column in the database
try {
  $sql = "UPDATE listings SET featured = :value WHERE id = :id";
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':value', $value);
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  $success = true;
} catch (PDOException $e) {
  $success = false;
  $error = $e->getMessage();
} finally {
  $stmt = null;
}

// Return a JSON response indicating success or failure
header('Content-Type: application/json');
if ($success) {
  echo json_encode(['success' => true]);
} else {
  echo json_encode(['success' => false, 'error' => $error]);
}

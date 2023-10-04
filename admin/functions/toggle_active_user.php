<?php
// include functions file
require_once __DIR__ . '/../functions/functions.php';

// get the id and value parameters from the URL
$id = sanitize($_GET['id']);
$value = sanitize($_GET['value']);

if (!$id || !$value) {
  http_response_code(400);
  echo json_encode(['error' => 'Invalid request parameters']);
  exit;
}

try {
  $stmt = $db->prepare("UPDATE users SET status = :value WHERE id = :id");
  $stmt->bindParam(':value', $value, PDO::PARAM_STR);
  $stmt->bindParam(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  $success = true;
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Failed to update user status']);
  exit;
}

echo json_encode(['success' => $success]);

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    try {
        $sql = "DELETE FROM listings WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);
        echo "Record deleted successfully";
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

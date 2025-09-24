<?php
session_start();
include("database.php");

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode([]);
    exit;
}

$query = "SELECT * FROM notifications 
          WHERE user_id = ? 
          ORDER BY created_at DESC 
          LIMIT 20";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

echo json_encode($notifications);
?>
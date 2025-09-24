<?php
session_start();
include("database.php");

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(['count' => 0]);
    exit;
}

$query = "SELECT COUNT(*) as count FROM notifications 
          WHERE user_id = ? AND read_status = 0";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo json_encode(['count' => $data['count']]);
?>
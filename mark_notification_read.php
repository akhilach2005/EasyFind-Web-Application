<?php
session_start();
include("database.php");

// Set header for JSON response
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

try {
    // Get and decode JSON data
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['notification_id'])) {
        throw new Exception('Notification ID is required');
    }

    // Update notification read status
    $query = "UPDATE notifications 
              SET read_status = TRUE 
              WHERE notification_id = ? AND user_id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $data['notification_id'], $_SESSION['username']);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to update notification');
    }

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?>
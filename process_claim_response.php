<?php
session_start();
include("database.php");

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['claim_id']) || !isset($data['action'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data received']);
    exit;
}

$claim_id = intval($data['claim_id']);
$action = $data['action'];

// Update claim status
$status = ($action === 'accept') ? 'accepted' : 'rejected';
$query = "UPDATE claims SET status = ? WHERE claim_id = ? AND finder_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("sis", $status, $claim_id, $_SESSION['username']);

if ($stmt->execute()) {
    // Get claimer's info
    $query = "SELECT claimer_id, item_id FROM claims WHERE claim_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $claim_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $claim = $result->fetch_assoc();
    
    // Create notification for claimer
    $title = ($action === 'accept') ? 'Claim Accepted' : 'Claim Rejected';
    $message = ($action === 'accept') 
        ? 'Your claim has been accepted! You can now contact the finder.'
        : 'Your claim has been rejected.';
    
    $notification_query = "INSERT INTO notifications (user_id, title, message, type, related_claim_id) 
                          VALUES (?, ?, ?, ?, ?)";
    $type = ($action === 'accept') ? 'accept' : 'reject';
    $stmt = $conn->prepare($notification_query);
    $stmt->bind_param("sssis", $claim['claimer_id'], $title, $message, $type, $claim_id);
    $stmt->execute();
    
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error processing response']);
}
?>
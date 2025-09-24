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
    if (!isset($_GET['claim_id'])) {
        throw new Exception('Claim ID is required');
    }

    $claim_id = intval($_GET['claim_id']);

    // Get claim details
    $query = "SELECT c.*, f.ItemName as item_name, f.Description as item_description 
              FROM claims c 
              JOIN founditems f ON c.item_id = f.ItemID 
              WHERE c.claim_id = ? AND (c.finder_id = ? OR c.claimer_id = ?)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $claim_id, $_SESSION['username'], $_SESSION['username']);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to fetch claim details');
    }

    $result = $stmt->get_result();
    $claim = $result->fetch_assoc();

    if (!$claim) {
        throw new Exception('Claim not found or access denied');
    }

    // Get claimer's details from Students table
    $user_query = "SELECT Student_Name, email FROM Students WHERE RegistrationID = ?";
    $stmt = $conn->prepare($user_query);
    $stmt->bind_param("s", $claim['claimer_id']);
    $stmt->execute();
    $user_result = $stmt->get_result();
    $user = $user_result->fetch_assoc();

    // Combine claim and user details
    $response = [
        'success' => true,
        'claim_id' => $claim['claim_id'],
        'item_id' => $claim['item_id'],
        'item_name' => $claim['item_name'],
        'item_description' => $claim['item_description'],
        'claimer_id' => $claim['claimer_id'],
        'name' => $user['Student_Name'],
        'email' => $user['email'],
        'contact' => $claim['contact'],
        'description' => $claim['description'],
        'status' => $claim['status'],
        'claim_date' => $claim['claim_date']
    ];

    echo json_encode($response);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?>
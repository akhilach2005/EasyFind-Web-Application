<?php
session_start();
include("database.php");

// Enable error reporting but log to file instead of output
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

// Set header for JSON response
header('Content-Type: application/json');

// Function to log errors
function logError($message) {
    $logFile = "claim_errors.log";
    $timestamp = date('Y-m-d H:i:s');
    error_log("[$timestamp] $message\n", 3, $logFile);
}

try {
    // Check if user is logged in
    if (!isset($_SESSION['username'])) {
        throw new Exception('Please login to submit a claim');
    }

    // Decode JSON data
    $data = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON data: ' . json_last_error_msg());
    }

    // Validate required fields
    if (empty($data['itemId'])) throw new Exception('Item ID is required');
    if (empty($data['name'])) throw new Exception('Name is required');
    if (empty($data['contact'])) throw new Exception('Contact is required');
    if (empty($data['description'])) throw new Exception('Description is required');

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Get finder's ID from founditems table
        $check_query = "SELECT RegistrationId FROM founditems WHERE ItemID = ?";
        $stmt = mysqli_prepare($conn, $check_query);
        if (!$stmt) {
            throw new Exception("Database error: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "i", $data['itemId']);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Database error: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $finder = mysqli_fetch_assoc($result);

        if (!$finder) {
            throw new Exception("Item not found");
        }

        // Insert into claims table
        $claim_query = "INSERT INTO claims (item_id, claimer_id, finder_id, description, contact) 
                       VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $claim_query);
        if (!$stmt) {
            throw new Exception("Database error: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "issss", 
            $data['itemId'],
            $_SESSION['username'],
            $finder['RegistrationId'],
            $data['description'],
            $data['contact']
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Database error: " . mysqli_stmt_error($stmt));
        }

        $claim_id = mysqli_insert_id($conn);

        // Create notification for finder
        $notification_query = "INSERT INTO notifications (user_id, title, message, type, related_claim_id) 
                             VALUES (?, ?, ?, 'claim', ?)";
        $stmt = mysqli_prepare($conn, $notification_query);
        if (!$stmt) {
            throw new Exception("Database error: " . mysqli_error($conn));
        }

        $title = "New Claim Request";
        $message = "Someone has claimed your found item. Please review the claim.";
        
        mysqli_stmt_bind_param($stmt, "sssi", 
            $finder['RegistrationId'],
            $title,
            $message,
            $claim_id
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Database error: " . mysqli_stmt_error($stmt));
        }

        // Commit transaction
        mysqli_commit($conn);

        echo json_encode([
            'success' => true,
            'message' => 'Claim submitted successfully'
        ]);

    } catch (Exception $e) {
        mysqli_rollback($conn);
        throw $e;
    }

} catch (Exception $e) {
    logError("Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

// Close connection
mysqli_close($conn);
?>
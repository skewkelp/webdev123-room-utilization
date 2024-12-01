<?php
session_start();
require_once '../classes/database.class.php';

header('Content-Type: application/json');

// Debug session data
error_log('Session data: ' . print_r($_SESSION, true));

try {
    $db = new Database();
    $conn = $db->connect();
    
    // Check which session variable is actually set
    $user_id = $_SESSION['user_id'] ?? $_SESSION['account']['id'] ?? null;
    
    if (!$user_id) {
        echo json_encode([
            'success' => false,
            'message' => 'Not authenticated',
            'debug' => $_SESSION  // Remove in production
        ]);
        exit;
    }
    
    $query = "SELECT 
                id,
                first_name,
                last_name,
                username,
                email,
                role,
                CASE 
                    WHEN is_active = 1 THEN 'Active'
                    ELSE 'Inactive'
                END as status
              FROM account 
              WHERE id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // Set the full name in session
        $_SESSION['full_name'] = $user['first_name'] . ' ' . $user['last_name'];
        
        echo json_encode([
            'success' => true,
            'data' => [$user]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'User not found',
            'debug' => ['user_id' => $user_id]  // Remove in production
        ]);
    }
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()  // Remove detailed error in production
    ]);
}
?>
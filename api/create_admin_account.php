<?php
/**
 * Admin Account Handler
 * Handles admin account creation and sends welcome emails
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../classes/EmailSender.php';

try {
    // Only allow POST requests
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Only POST requests are allowed');
    }
    
    // Get JSON input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON data');
    }
    
    // Validate required fields
    $requiredFields = ['name', 'email', 'password', 'userType'];
    foreach ($requiredFields as $field) {
        if (empty($data[$field])) {
            throw new Exception("Field '{$field}' is required");
        }
    }
    
    // Validate email format
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }
    
    // Validate password length
    if (strlen($data['password']) < 6) {
        throw new Exception('Password must be at least 6 characters long');
    }
    
    // Prepare admin account data
    $adminData = [
        'name' => trim($data['name']),
        'email' => trim(strtolower($data['email'])),
        'contact_number' => !empty($data['contactNumber']) ? trim($data['contactNumber']) : null,
        'address' => !empty($data['address']) ? trim($data['address']) : null,
        'user_type' => $data['userType'],
        'password' => password_hash($data['password'], PASSWORD_DEFAULT), // Use PHP's secure hashing
        'image_url' => !empty($data['imageUrl']) ? $data['imageUrl'] : null,
        'created_at' => date('Y-m-d H:i:s'),
        'is_active' => true
    ];
    
    // Here you would normally insert into your database
    // For this example, I'll simulate a successful database insertion
    $adminId = simulateAdminCreation($adminData);
    
    // Send welcome email to the new admin
    $emailSender = new EmailSender();
    
    // Prepare email data
    $emailVariables = [
        'user_name' => $adminData['name'],
        'user_email' => $adminData['email'],
        'user_role' => $adminData['user_type'],
        'login_url' => 'http://' . $_SERVER['HTTP_HOST'] . '/sign_in_form.html',
        'contact_address' => 'Anihan Agricultural Management System',
        'contact_phone' => '+63 XXX XXX XXXX',
        'temporary_password' => $data['password'] // Include temporary password in welcome email
    ];
    
    // Send welcome email using the admin welcome template
    $emailResult = $emailSender->sendWithTemplate(
        [$adminData['email'] => $adminData['name']],
        'admin_welcome',
        $emailVariables,
        'Welcome to Anihan System - Your Admin Account is Ready!'
    );
    
    // Log email results
    $emailSuccess = $emailResult['total_sent'] > 0;
    
    // Prepare response
    $response = [
        'success' => true,
        'message' => 'Admin account created successfully',
        'admin_id' => $adminId,
        'admin_data' => [
            'id' => $adminId,
            'name' => $adminData['name'],
            'email' => $adminData['email'],
            'contact_number' => $adminData['contact_number'],
            'address' => $adminData['address'],
            'user_type' => $adminData['user_type'],
            'image_url' => $adminData['image_url'],
            'created_at' => $adminData['created_at']
        ],
        'email_sent' => $emailSuccess,
        'email_details' => [
            'sent_count' => $emailResult['total_sent'],
            'failed_count' => $emailResult['total_failed'],
            'recipients' => $emailResult['sent']
        ]
    ];
    
    // If email failed, add warning but don't fail the whole operation
    if (!$emailSuccess) {
        $response['warnings'] = ['Email notification could not be sent, but account was created successfully'];
        if (!empty($emailResult['failed'])) {
            $response['email_errors'] = $emailResult['failed'];
        }
    }
    
    echo json_encode($response);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

/**
 * Simulate admin account creation
 * In a real application, this would insert into your database
 * 
 * @param array $adminData
 * @return int Simulated admin ID
 */
function simulateAdminCreation($adminData) {
    // In a real application, you would:
    // 1. Connect to your database (MySQL, PostgreSQL, etc.)
    // 2. Insert the admin data
    // 3. Return the actual inserted ID
    
    // For now, we'll simulate this
    $simulatedId = rand(1000, 9999);
    
    // Log the simulated creation (in production, this would be actual database insertion)
    error_log("Simulated admin creation: ID {$simulatedId}, Email: {$adminData['email']}, Name: {$adminData['name']}");
    
    return $simulatedId;
}

/**
 * Validate contact number
 */
function validateContactNumber($contactNumber) {
    if (empty($contactNumber)) {
        return true; // Contact number is optional
    }
    
    // Remove any non-digit characters for validation
    $cleanNumber = preg_replace('/[^0-9]/', '', $contactNumber);
    
    // Check length (7-12 digits)
    if (strlen($cleanNumber) < 7 || strlen($cleanNumber) > 12) {
        return false;
    }
    
    return true;
}

/**
 * Log admin account activity
 */
function logAdminActivity($adminId, $action, $details = null) {
    $logData = [
        'timestamp' => date('Y-m-d H:i:s'),
        'admin_id' => $adminId,
        'action' => $action,
        'details' => $details,
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
    ];
    
    // In production, save this to a log file or database
    error_log("Admin Activity: " . json_encode($logData));
}
?>
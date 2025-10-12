<?php
/**
 * Two-Factor Authentication API
 * Handles 2FA code generation, sending, and verification
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

// Start session to store 2FA codes temporarily
session_start();

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
    
    $action = $data['action'] ?? '';
    
    switch ($action) {
        case 'generate_code':
            handleGenerateCode($data);
            break;
            
        case 'verify_code':
            handleVerifyCode($data);
            break;
            
        default:
            throw new Exception('Invalid action specified');
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

/**
 * Generate and send 2FA code
 */
function handleGenerateCode($data) {
    // Validate required fields
    $requiredFields = ['email', 'user_name'];
    foreach ($requiredFields as $field) {
        if (empty($data[$field])) {
            throw new Exception("Field '{$field}' is required");
        }
    }
    
    $email = trim(strtolower($data['email']));
    $userName = trim($data['user_name']);
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }
    
    // Generate 6-digit verification code
    $verificationCode = generateVerificationCode();
    
    // Store code in session with expiration (5 minutes)
    $codeData = [
        'code' => $verificationCode,
        'email' => $email,
        'user_name' => $userName,
        'expires_at' => time() + (5 * 60), // 5 minutes from now
        'attempts' => 0
    ];
    
    $_SESSION['2fa_code_' . $email] = $codeData;
    
    // Also store in temporary file for cross-session access
    $tempDir = sys_get_temp_dir();
    $tempFile = $tempDir . '/anihan_2fa_' . md5($email) . '.json';
    file_put_contents($tempFile, json_encode($codeData));
    
    // Send 2FA code via email
    $emailSender = new EmailSender();
    
    $emailVariables = [
        'user_name' => $userName,
        'user_email' => $email,
        'verification_code' => $verificationCode,
        'expires_minutes' => 5,
        'timestamp' => date('Y-m-d H:i:s'),
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown'
    ];
    
    $emailResult = $emailSender->sendWithTemplate(
        [$email => $userName],
        '2fa_verification',
        $emailVariables,
        'Anihan System - Login Verification Code'
    );
    
    $emailSent = $emailResult['total_sent'] > 0;
    
    // Clean up old expired codes
    cleanupExpiredCodes();
    
    echo json_encode([
        'success' => true,
        'message' => 'Verification code sent to your email',
        'email_sent' => $emailSent,
        'expires_in' => 300, // 5 minutes in seconds
        'code_length' => 6,
        'debug_info' => [
            'email' => $email,
            'code_generated' => true,
            'email_result' => $emailSent
        ]
    ]);
}

/**
 * Verify 2FA code
 */
function handleVerifyCode($data) {
    // Validate required fields
    $requiredFields = ['email', 'code'];
    foreach ($requiredFields as $field) {
        if (empty($data[$field])) {
            throw new Exception("Field '{$field}' is required");
        }
    }
    
    $email = trim(strtolower($data['email']));
    $inputCode = trim($data['code']);
    
    // Get stored code data - check both session and temp file
    $sessionKey = '2fa_code_' . $email;
    $codeData = $_SESSION[$sessionKey] ?? null;
    
    // If not in session, try to load from temporary file
    if (!$codeData) {
        $tempDir = sys_get_temp_dir();
        $tempFile = $tempDir . '/anihan_2fa_' . md5($email) . '.json';
        
        if (file_exists($tempFile)) {
            $fileContents = file_get_contents($tempFile);
            if ($fileContents) {
                $codeData = json_decode($fileContents, true);
                
                // Also restore to session for consistency
                if ($codeData) {
                    $_SESSION[$sessionKey] = $codeData;
                }
            }
        }
    }
    
    if (!$codeData) {
        throw new Exception('No verification code found. Please request a new code.');
    }
    
    // Check if code has expired
    if (time() > $codeData['expires_at']) {
        unset($_SESSION[$sessionKey]);
        
        // Also clean up temporary file
        $tempDir = sys_get_temp_dir();
        $tempFile = $tempDir . '/anihan_2fa_' . md5($email) . '.json';
        if (file_exists($tempFile)) {
            unlink($tempFile);
        }
        
        throw new Exception('Verification code has expired. Please request a new code.');
    }
    
    // Check attempt limit (max 3 attempts)
    if ($codeData['attempts'] >= 3) {
        unset($_SESSION[$sessionKey]);
        
        // Also clean up temporary file
        $tempDir = sys_get_temp_dir();
        $tempFile = $tempDir . '/anihan_2fa_' . md5($email) . '.json';
        if (file_exists($tempFile)) {
            unlink($tempFile);
        }
        
        throw new Exception('Too many failed attempts. Please request a new code.');
    }
    
    // Increment attempt counter
    $_SESSION[$sessionKey]['attempts']++;
    
    // Verify the code
    if ($inputCode !== $codeData['code']) {
        $remainingAttempts = 3 - $_SESSION[$sessionKey]['attempts'];
        throw new Exception("Invalid verification code. {$remainingAttempts} attempts remaining.");
    }
    
    // Code is valid - clean up and return success
    unset($_SESSION[$sessionKey]);
    
    // Also clean up temporary file
    $tempDir = sys_get_temp_dir();
    $tempFile = $tempDir . '/anihan_2fa_' . md5($email) . '.json';
    if (file_exists($tempFile)) {
        unlink($tempFile);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Verification code is valid',
        'verified' => true
    ]);
}

/**
 * Generate a 6-digit verification code
 */
function generateVerificationCode() {
    return str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);
}

/**
 * Clean up expired verification codes from session
 */
function cleanupExpiredCodes() {
    $currentTime = time();
    
    foreach ($_SESSION as $key => $value) {
        if (strpos($key, '2fa_code_') === 0 && is_array($value)) {
            if (isset($value['expires_at']) && $currentTime > $value['expires_at']) {
                unset($_SESSION[$key]);
            }
        }
    }
}

/**
 * Rate limiting for code generation (optional security measure)
 */
function checkRateLimit($email) {
    $rateLimitKey = '2fa_rate_' . $email;
    $currentTime = time();
    
    if (!isset($_SESSION[$rateLimitKey])) {
        $_SESSION[$rateLimitKey] = [
            'requests' => 1,
            'first_request' => $currentTime
        ];
        return true;
    }
    
    $rateData = $_SESSION[$rateLimitKey];
    
    // Reset if more than 15 minutes have passed
    if ($currentTime - $rateData['first_request'] > 900) {
        $_SESSION[$rateLimitKey] = [
            'requests' => 1,
            'first_request' => $currentTime
        ];
        return true;
    }
    
    // Allow max 5 requests per 15 minutes
    if ($rateData['requests'] >= 5) {
        return false;
    }
    
    $_SESSION[$rateLimitKey]['requests']++;
    return true;
}
?>
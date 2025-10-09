<?php
/**
 * Direct API Test - Debug Email Sending
 */

echo "=== TESTING EMAIL API DIRECTLY ===\n\n";

// Simulate the same data that would come from the form
$testData = [
    'name' => 'Debug Test Admin',
    'email' => 'debug.test@example.com', // Change this to your real email for testing
    'password' => 'DebugPass123!',
    'userType' => 'ADMIN',
    'contactNumber' => '1234567890',
    'address' => 'Test Address'
];

echo "Test data:\n";
print_r($testData);
echo "\n";

// Include the API file directly
echo "🔄 Testing API endpoint directly...\n\n";

// Capture the output
ob_start();

// Set up the environment to simulate a POST request
$_SERVER['REQUEST_METHOD'] = 'POST';
file_put_contents('php://input', json_encode($testData));

// Include the API file
try {
    include 'api/create_admin_account.php';
    $output = ob_get_clean();
    
    echo "✅ API Response:\n";
    echo $output . "\n";
    
    // Try to decode the JSON response
    $response = json_decode($output, true);
    if ($response) {
        echo "\n📊 Parsed Response:\n";
        echo "Success: " . ($response['success'] ? 'Yes' : 'No') . "\n";
        echo "Email Sent: " . (isset($response['email_sent']) && $response['email_sent'] ? 'Yes' : 'No') . "\n";
        
        if (isset($response['error'])) {
            echo "Error: " . $response['error'] . "\n";
        }
        
        if (isset($response['email_details'])) {
            echo "Email Details:\n";
            print_r($response['email_details']);
        }
    }
    
} catch (Exception $e) {
    ob_end_clean();
    echo "❌ Error testing API: " . $e->getMessage() . "\n";
}

echo "\n=== ADDITIONAL CHECKS ===\n";

// Check if files exist
$files = [
    'classes/EmailSender.php' => 'EmailSender Class',
    'config/email_config.php' => 'Email Configuration',
    'templates/email/admin_welcome.php' => 'Admin Welcome Template',
    'vendor/autoload.php' => 'Composer Autoload'
];

foreach ($files as $file => $description) {
    echo (file_exists($file) ? '✅' : '❌') . " {$description}: {$file}\n";
}

// Test EmailSender directly
echo "\n🔧 Testing EmailSender class directly...\n";
try {
    require_once 'classes/EmailSender.php';
    $emailSender = new EmailSender();
    echo "✅ EmailSender instantiated successfully\n";
    
    // Test email configuration
    $config = $emailSender->getConfig();
    echo "📧 Email configured for: " . $config['credentials']['email'] . "\n";
    
} catch (Exception $e) {
    echo "❌ EmailSender error: " . $e->getMessage() . "\n";
}

echo "\n=== TEST COMPLETED ===\n";
?>
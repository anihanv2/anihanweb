<?php
/**
 * Proper Email API Test
 */

echo "=== TESTING EMAIL API WITH PROPER JSON ===\n\n";

// Test data
$testData = [
    'name' => 'API Test Admin', 
    'email' => 'test@example.com', // Change this to your real email
    'password' => 'TestPass123!',
    'userType' => 'ADMIN',
    'contactNumber' => '1234567890',
    'address' => 'Test Address'
];

// Test EmailSender directly first
echo "1. Testing EmailSender directly...\n";
try {
    require_once 'classes/EmailSender.php';
    $emailSender = new EmailSender();
    
    $emailVariables = [
        'user_name' => $testData['name'],
        'user_email' => $testData['email'], 
        'user_role' => $testData['userType'],
        'temporary_password' => $testData['password'],
        'login_url' => 'http://localhost/anihanweb/sign_in_form.html',
        'contact_address' => 'Anihan Agricultural Management System',
        'contact_phone' => '+63 XXX XXX XXXX'
    ];
    
    echo "📧 Sending test email...\n";
    $result = $emailSender->sendWithTemplate(
        [$testData['email'] => $testData['name']],
        'admin_welcome',
        $emailVariables,
        'Welcome to Anihan System - Your Admin Account is Ready!'
    );
    
    echo "Email Result:\n";
    echo "- Sent: " . $result['total_sent'] . "\n";
    echo "- Failed: " . $result['total_failed'] . "\n";
    
    if ($result['total_sent'] > 0) {
        echo "✅ Email sent successfully!\n";
    } else {
        echo "❌ Email failed to send\n";
        if (!empty($result['failed'])) {
            foreach ($result['failed'] as $failed) {
                echo "  Error: " . $failed['error'] . "\n";
            }
        }
    }
    
} catch (Exception $e) {
    echo "❌ EmailSender test failed: " . $e->getMessage() . "\n";
}

echo "\n2. Testing API endpoint via HTTP simulation...\n";

// Create a temporary file to simulate JSON input
$jsonData = json_encode($testData);
file_put_contents('temp_input.json', $jsonData);

// Use proper HTTP simulation
$ch = curl_init();
if ($ch === false) {
    echo "❌ cURL not available, testing with file_get_contents...\n";
    
    // Alternative method using stream context
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => $jsonData
        ]
    ]);
    
    echo "📡 Making request to API...\n";
    $response = @file_get_contents('http://localhost:8000/api/create_admin_account.php', false, $context);
    
    if ($response === false) {
        echo "❌ API request failed. Make sure PHP server is running on localhost:8000\n";
        echo "Run: php -S localhost:8000\n";
    } else {
        echo "✅ API Response:\n";
        echo $response . "\n";
        
        $decoded = json_decode($response, true);
        if ($decoded) {
            echo "\n📊 Parsed Response:\n";
            echo "Success: " . ($decoded['success'] ? 'Yes' : 'No') . "\n";
            if (isset($decoded['email_sent'])) {
                echo "Email Sent: " . ($decoded['email_sent'] ? 'Yes' : 'No') . "\n";
            }
            if (isset($decoded['error'])) {
                echo "Error: " . $decoded['error'] . "\n";
            }
        }
    }
} else {
    curl_close($ch);
    echo "⚠️ cURL available but using simple method for compatibility\n";
}

// Clean up
if (file_exists('temp_input.json')) {
    unlink('temp_input.json');
}

echo "\n=== TROUBLESHOOTING TIPS ===\n";
echo "If emails aren't being sent from the admin form:\n";
echo "1. Check browser console for JavaScript errors\n";
echo "2. Make sure the API path is correct: api/create_admin_account.php\n";
echo "3. Verify the web server can access the PHP files\n";
echo "4. Check that PHPMailer is properly installed\n";
echo "5. Verify Gmail app password is correct\n\n";

echo "If the direct test above worked but the form doesn't:\n";
echo "- The issue is in the JavaScript/frontend integration\n";
echo "- Check the browser's Network tab for failed API calls\n";
echo "- Look for console errors in the browser developer tools\n\n";

echo "=== TEST COMPLETED ===\n";
?>
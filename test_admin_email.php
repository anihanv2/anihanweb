<?php
/**
 * Test Admin Email Notification System
 * This script tests the email functionality for admin account creation
 */

require_once __DIR__ . '/classes/EmailSender.php';

echo "=== ADMIN EMAIL NOTIFICATION TEST ===\n\n";

try {
    // Initialize EmailSender
    $emailSender = new EmailSender();
    echo "✅ EmailSender initialized successfully\n";
    
    // Test admin data
    $testAdminData = [
        'user_name' => 'Test Administrator',
        'user_email' => 'test.admin@example.com', // Change this to your actual test email
        'user_role' => 'ADMIN',
        'temporary_password' => 'TempPass123!',
        'login_url' => 'http://localhost/anihanweb/sign_in_form.html',
        'contact_address' => 'Anihan Agricultural Management System',
        'contact_phone' => '+63 XXX XXX XXXX'
    ];
    
    echo "📧 Sending test admin welcome email...\n";
    echo "To: {$testAdminData['user_email']}\n";
    echo "Name: {$testAdminData['user_name']}\n";
    echo "Role: {$testAdminData['user_role']}\n\n";
    
    // Send the email using the admin welcome template
    $emailResult = $emailSender->sendWithTemplate(
        [$testAdminData['user_email'] => $testAdminData['user_name']],
        'admin_welcome',
        $testAdminData,
        'Welcome to Anihan System - Your Admin Account is Ready!'
    );
    
    // Display results
    echo "=== EMAIL RESULTS ===\n";
    echo "Emails sent: " . $emailResult['total_sent'] . "\n";
    echo "Emails failed: " . $emailResult['total_failed'] . "\n";
    
    if ($emailResult['total_sent'] > 0) {
        echo "✅ SUCCESS: Email sent to: " . implode(', ', $emailResult['sent']) . "\n";
    }
    
    if ($emailResult['total_failed'] > 0) {
        echo "❌ FAILED EMAILS:\n";
        foreach ($emailResult['failed'] as $failed) {
            echo "  - {$failed['email']}: {$failed['error']}\n";
        }
    }
    
    echo "\n=== TEST COMPLETED ===\n";
    
    if ($emailResult['total_sent'] > 0) {
        echo "🎉 Email system is working correctly!\n";
        echo "Check your email inbox for the welcome message.\n\n";
        
        echo "Next steps:\n";
        echo "1. Update the test email address in this script to your actual email\n";
        echo "2. Test the admin account creation form in the browser\n";
        echo "3. Verify that emails are sent when creating new admin accounts\n";
    } else {
        echo "⚠️ Email sending failed. Check the following:\n";
        echo "1. PHPMailer is installed (run: composer require phpmailer/phpmailer)\n";
        echo "2. Gmail app password is correct in config/email_config.php\n";
        echo "3. Internet connection is available\n";
        echo "4. Gmail account has 2-factor authentication enabled\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n\n";
    
    echo "Troubleshooting:\n";
    echo "1. Make sure PHPMailer is installed: composer require phpmailer/phpmailer\n";
    echo "2. Check email configuration in config/email_config.php\n";
    echo "3. Verify Gmail app password is correct\n";
    echo "4. Ensure all required files exist:\n";
    echo "   - classes/EmailSender.php\n";
    echo "   - config/email_config.php\n";
    echo "   - templates/email/admin_welcome.php\n";
}

// Additional utility functions for testing

echo "\n=== CONFIGURATION CHECK ===\n";

// Check if required files exist
$requiredFiles = [
    'classes/EmailSender.php' => 'EmailSender class',
    'config/email_config.php' => 'Email configuration',
    'templates/email/admin_welcome.php' => 'Admin welcome template',
    'templates/email/default.php' => 'Default email template',
    'templates/email/notification.php' => 'Notification template'
];

foreach ($requiredFiles as $file => $description) {
    if (file_exists($file)) {
        echo "✅ {$description}: {$file}\n";
    } else {
        echo "❌ Missing {$description}: {$file}\n";
    }
}

// Check Composer autoload
if (file_exists('vendor/autoload.php')) {
    echo "✅ Composer autoload: vendor/autoload.php\n";
} else {
    echo "❌ Missing Composer autoload: vendor/autoload.php\n";
    echo "   Run: composer install\n";
}

echo "\n=== EMAIL CONFIGURATION ===\n";
if (file_exists('config/email_config.php')) {
    $config = require 'config/email_config.php';
    echo "SMTP Host: " . $config['smtp']['host'] . "\n";
    echo "SMTP Port: " . $config['smtp']['port'] . "\n";
    echo "From Email: " . $config['credentials']['email'] . "\n";
    echo "From Name: " . $config['credentials']['name'] . "\n";
} else {
    echo "❌ Email configuration file not found\n";
}

?>
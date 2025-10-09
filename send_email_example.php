<?php
/**
 * Email Usage Example Script
 * Demonstrates how to use the EmailSender class to send emails to multiple recipients
 */

require_once __DIR__ . '/classes/EmailSender.php';

try {
    // Initialize the EmailSender
    $emailSender = new EmailSender();
    
    echo "=== Anihan Email System Demo ===\n\n";
    
    // Example 1: Send simple email to multiple recipients
    echo "1. Sending simple email to multiple recipients...\n";
    
    $recipients = [
        'user1@example.com' => 'John Doe',
        'user2@example.com' => 'Jane Smith',
        'admin@example.com' => 'Admin User',
        // You can also use indexed array for emails without names
        'support@example.com'
    ];
    
    $subject = "Welcome to Anihan System - Agricultural Management Platform";
    
    $body = "
    <h2>Welcome to Anihan System!</h2>
    <p>Dear User,</p>
    <p>Thank you for joining our agricultural management platform. We're excited to have you on board!</p>
    <p>Our system helps you:</p>
    <ul>
        <li>Monitor agricultural products and prices</li>
        <li>Track inventory and sales</li>
        <li>Manage voucher systems</li>
        <li>Generate detailed reports</li>
    </ul>
    <p>Best regards,<br>The Anihan Team</p>
    ";
    
    $results = $emailSender->sendToMultiple($recipients, $subject, $body);
    
    echo "Results:\n";
    echo "- Successfully sent: " . $results['total_sent'] . " emails\n";
    echo "- Failed to send: " . $results['total_failed'] . " emails\n";
    
    if (!empty($results['sent'])) {
        echo "Sent to: " . implode(', ', $results['sent']) . "\n";
    }
    
    if (!empty($results['failed'])) {
        echo "Failed emails:\n";
        foreach ($results['failed'] as $failed) {
            echo "  - {$failed['email']}: {$failed['error']}\n";
        }
    }
    
    echo "\n" . str_repeat("-", 50) . "\n\n";
    
    // Example 2: Send email using template
    echo "2. Sending welcome email using template...\n";
    
    $welcomeRecipients = [
        'newuser@example.com' => 'New User'
    ];
    
    $templateVariables = [
        'user_name' => 'New User',
        'welcome_message' => 'We\'ve set up your account and you\'re ready to start managing your agricultural data.',
        'login_url' => 'https://yoursite.com/login'
    ];
    
    $templateResults = $emailSender->sendWithTemplate(
        $welcomeRecipients, 
        'welcome', 
        $templateVariables, 
        'Welcome to Anihan System!'
    );
    
    echo "Template email results:\n";
    echo "- Successfully sent: " . $templateResults['total_sent'] . " emails\n";
    echo "- Failed to send: " . $templateResults['total_failed'] . " emails\n";
    
    echo "\n" . str_repeat("-", 50) . "\n\n";
    
    // Example 3: Send notification email
    echo "3. Sending notification email...\n";
    
    $notificationRecipients = [
        'admin@example.com' => 'System Admin'
    ];
    
    $notificationVariables = [
        'recipient_name' => 'System Admin',
        'notification_type' => 'warning',
        'notification_title' => 'Low Inventory Alert',
        'notification_message' => 'Several products in your inventory are running low and need restocking.',
        'details' => 'Rice: 15 bags remaining<br>Corn: 8 bags remaining<br>Fertilizer: 3 bottles remaining',
        'action_required' => true,
        'action_description' => 'Please review the inventory and place new orders.',
        'action_url' => 'https://yoursite.com/inventory',
        'action_text' => 'View Inventory',
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    $notificationResults = $emailSender->sendWithTemplate(
        $notificationRecipients, 
        'notification', 
        $notificationVariables, 
        'Inventory Alert - Action Required'
    );
    
    echo "Notification email results:\n";
    echo "- Successfully sent: " . $notificationResults['total_sent'] . " emails\n";
    echo "- Failed to send: " . $notificationResults['total_failed'] . " emails\n";
    
    echo "\n" . str_repeat("-", 50) . "\n\n";
    
    // Example 4: Send single email with attachment
    echo "4. Sending single email with attachment...\n";
    
    $singleEmailBody = "
    <h2>Monthly Report</h2>
    <p>Dear Administrator,</p>
    <p>Please find attached the monthly agricultural report for your review.</p>
    <p>The report includes:</p>
    <ul>
        <li>Sales summary</li>
        <li>Inventory status</li>
        <li>Price monitoring data</li>
        <li>Voucher usage statistics</li>
    </ul>
    <p>Best regards,<br>Anihan System</p>
    ";
    
    // Example attachments (make sure these files exist)
    $attachments = [
        // You can add actual file paths here
        // ['path' => 'reports/monthly_report.pdf', 'name' => 'Monthly_Report.pdf'],
        // ['path' => 'data/inventory.csv', 'name' => 'Inventory_Data.csv']
    ];
    
    $singleResult = $emailSender->sendToSingle(
        'admin@example.com',
        'Monthly Agricultural Report',
        $singleEmailBody,
        'System Administrator',
        null, // Alt body
        $attachments
    );
    
    echo "Single email result: " . ($singleResult ? "Success" : "Failed") . "\n";
    
    echo "\n=== Email Demo Completed ===\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Make sure you have:\n";
    echo "1. Installed PHPMailer via Composer (composer require phpmailer/phpmailer)\n";
    echo "2. Updated the email configuration in config/email_config.php\n";
    echo "3. Enabled 2-factor authentication and generated an app password for Gmail\n";
}

// Additional utility functions for real-world usage

/**
 * Send bulk emails from CSV file
 */
function sendBulkEmailsFromCSV($csvFile, $subject, $body) {
    $emailSender = new EmailSender();
    $recipients = [];
    
    if (($handle = fopen($csvFile, "r")) !== FALSE) {
        // Skip header row
        fgetcsv($handle);
        
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $email = $data[0];
            $name = isset($data[1]) ? $data[1] : '';
            
            if ($emailSender->validateEmail($email)) {
                $recipients[$email] = $name;
            }
        }
        fclose($handle);
    }
    
    if (!empty($recipients)) {
        return $emailSender->sendToMultiple($recipients, $subject, $body);
    }
    
    return ['total_sent' => 0, 'total_failed' => 0, 'sent' => [], 'failed' => []];
}

/**
 * Send email with retry mechanism
 */
function sendEmailWithRetry($recipients, $subject, $body, $maxRetries = 3) {
    $emailSender = new EmailSender();
    $attempt = 1;
    
    while ($attempt <= $maxRetries) {
        try {
            echo "Attempt $attempt of $maxRetries...\n";
            $results = $emailSender->sendToMultiple($recipients, $subject, $body);
            
            if ($results['total_sent'] > 0) {
                return $results;
            }
            
            if ($attempt < $maxRetries) {
                echo "Waiting 5 seconds before retry...\n";
                sleep(5);
            }
            
        } catch (Exception $e) {
            echo "Attempt $attempt failed: " . $e->getMessage() . "\n";
            
            if ($attempt < $maxRetries) {
                echo "Waiting 10 seconds before retry...\n";
                sleep(10);
            }
        }
        
        $attempt++;
    }
    
    return ['total_sent' => 0, 'total_failed' => count($recipients), 'sent' => [], 'failed' => $recipients];
}
?>
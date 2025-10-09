<?php
/**
 * Hostinger Environment Check
 * Run this file on your Hostinger server to check compatibility
 */

echo "=== HOSTINGER ENVIRONMENT CHECK ===\n\n";

// Check PHP version
echo "PHP Version: " . PHP_VERSION . "\n";
if (version_compare(PHP_VERSION, '7.4.0') >= 0) {
    echo "✅ PHP version is compatible\n";
} else {
    echo "❌ PHP version too old. Need 7.4+\n";
}

echo "\n";

// Check required extensions
$required_extensions = ['openssl', 'mbstring', 'curl', 'json'];
echo "Required PHP Extensions:\n";
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ $ext: Available\n";
    } else {
        echo "❌ $ext: Missing\n";
    }
}

echo "\n";

// Check file permissions
echo "File System Check:\n";
$files_to_check = [
    'classes/EmailSender.php',
    'config/email_config.php',
    'templates/email/admin_welcome.php',
    'api/create_admin_account.php'
];

foreach ($files_to_check as $file) {
    if (file_exists($file)) {
        $perms = substr(sprintf('%o', fileperms($file)), -4);
        echo "✅ $file: Exists (permissions: $perms)\n";
    } else {
        echo "❌ $file: Missing\n";
    }
}

echo "\n";

// Check PHPMailer
echo "PHPMailer Check:\n";
if (file_exists('vendor/autoload.php')) {
    echo "✅ Composer autoload found\n";
    try {
        require_once 'vendor/autoload.php';
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        echo "✅ PHPMailer instantiated successfully\n";
    } catch (Exception $e) {
        echo "❌ PHPMailer error: " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ Composer autoload not found\n";
    echo "   Upload vendor folder or install manually\n";
}

echo "\n";

// Check email configuration
echo "Email Configuration Check:\n";
if (file_exists('config/email_config.php')) {
    echo "✅ Email config file exists\n";
    $config = include 'config/email_config.php';
    echo "📧 Configured for: " . $config['credentials']['email'] . "\n";
} else {
    echo "❌ Email config file missing\n";
}

echo "\n";

// Check server capabilities
echo "Server Capabilities:\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "\n";
echo "Max Execution Time: " . ini_get('max_execution_time') . " seconds\n";
echo "Memory Limit: " . ini_get('memory_limit') . "\n";

// Check SMTP connectivity
echo "\nSMTP Connectivity Test:\n";
$smtp_host = 'smtp.gmail.com';
$smtp_port = 587;

$connection = @fsockopen($smtp_host, $smtp_port, $errno, $errstr, 10);
if ($connection) {
    echo "✅ Can connect to Gmail SMTP ($smtp_host:$smtp_port)\n";
    fclose($connection);
} else {
    echo "❌ Cannot connect to Gmail SMTP: $errstr ($errno)\n";
    echo "   This might be a firewall issue on Hostinger\n";
}

echo "\n";

// Hostinger specific checks
echo "Hostinger Specific Checks:\n";

// Check if we're on Hostinger
$server_name = $_SERVER['SERVER_NAME'] ?? '';
if (strpos($server_name, 'hostinger') !== false || strpos($server_name, '.com') !== false) {
    echo "✅ Running on what appears to be Hostinger\n";
} else {
    echo "ℹ️ Server name: $server_name\n";
}

// Check for common Hostinger restrictions
if (function_exists('mail')) {
    echo "✅ PHP mail() function available\n";
} else {
    echo "❌ PHP mail() function disabled\n";
}

if (function_exists('curl_init')) {
    echo "✅ cURL available for external connections\n";
} else {
    echo "❌ cURL not available\n";
}

echo "\n=== RECOMMENDATIONS ===\n";
echo "1. Ensure all files have correct permissions (644 for files, 755 for folders)\n";
echo "2. Test Gmail app password separately\n";
echo "3. Check Hostinger's email sending limits\n";
echo "4. Consider using Hostinger's SMTP if Gmail doesn't work\n";
echo "5. Enable error logging for production\n";

echo "\n=== CHECK COMPLETED ===\n";
?>
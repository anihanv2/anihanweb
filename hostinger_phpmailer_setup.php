<?php
/**
 * Manual PHPMailer Installation Guide for Hostinger
 * Use this if Composer is not available
 */

echo "=== MANUAL PHPMAILER INSTALLATION FOR HOSTINGER ===\n\n";

echo "If Composer is not available on your Hostinger hosting, follow these steps:\n\n";

echo "1. Download PHPMailer manually:\n";
echo "   - Go to: https://github.com/PHPMailer/PHPMailer/releases\n";
echo "   - Download the latest .zip file\n";
echo "   - Extract it locally\n\n";

echo "2. Upload PHPMailer files to Hostinger:\n";
echo "   - Create folder: public_html/vendor/phpmailer/phpmailer/src/\n";
echo "   - Upload all .php files from PHPMailer/src/ to that folder\n\n";

echo "3. Create manual autoloader:\n";
echo "   - Upload the autoloader file below to: public_html/vendor/autoload.php\n\n";

// Create a simple manual autoloader for PHPMailer
$autoloader = '<?php
/**
 * Manual PHPMailer Autoloader for Hostinger
 * Use this if Composer autoload is not available
 */

// PHPMailer class files
require_once __DIR__ . \'/phpmailer/phpmailer/src/Exception.php\';
require_once __DIR__ . \'/phpmailer/phpmailer/src/PHPMailer.php\';
require_once __DIR__ . \'/phpmailer/phpmailer/src/SMTP.php\';

// Set up class aliases for compatibility
use PHPMailer\\PHPMailer\\PHPMailer;
use PHPMailer\\PHPMailer\\SMTP;
use PHPMailer\\PHPMailer\\Exception;
?>';

echo "4. Save this autoloader code to public_html/vendor/autoload.php:\n";
echo "```php\n";
echo $autoloader . "\n";
echo "```\n\n";

echo "5. Test the installation:\n";
echo "   - Upload and run the test files on your Hostinger domain\n";
echo "   - Check that PHPMailer classes load correctly\n\n";

echo "=== ALTERNATIVE: Download ready-made vendor folder ===\n";
echo "You can also:\n";
echo "1. Run 'composer install' on your local machine\n";
echo "2. Zip the entire 'vendor' folder\n";
echo "3. Upload and extract it on Hostinger\n\n";

echo "=== TEST AFTER UPLOAD ===\n";
echo "Create a simple test file on Hostinger:\n\n";

$testCode = '<?php
// Test PHPMailer installation on Hostinger
try {
    require_once \'vendor/autoload.php\';
    use PHPMailer\\PHPMailer\\PHPMailer;
    
    echo "✅ PHPMailer loaded successfully!\\n";
    
    $mail = new PHPMailer(true);
    echo "✅ PHPMailer instantiated successfully!\\n";
    
    echo "🎉 Ready to send emails on Hostinger!\\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\\n";
    echo "Check that PHPMailer files are uploaded correctly.\\n";
}
?>';

echo "```php\n";
echo $testCode . "\n";
echo "```\n";

echo "\n=== GUIDE COMPLETED ===\n";
?>
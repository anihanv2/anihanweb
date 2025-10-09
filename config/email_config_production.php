<?php
/**
 * Production Configuration for Hostinger
 * Copy this to replace config/email_config.php on your server
 */

return [
    // SMTP Configuration
    'smtp' => [
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'encryption' => 'tls',
        'auth' => true,
    ],
    
    // Gmail Account Credentials
    'credentials' => [
        'email' => 'anihanv2@gmail.com',
        'password' => 'gkbh ehwt msjm mnkn', // Your Gmail app password
        'name' => 'Anihan System',
    ],
    
    // Default Email Settings
    'defaults' => [
        'from_email' => 'anihanv2@gmail.com',
        'from_name' => 'Anihan System',
        'reply_to' => 'anihanv2@gmail.com',
        'charset' => 'UTF-8',
    ],
    
    // Email Templates Directory - UPDATED FOR PRODUCTION
    'templates_path' => $_SERVER['DOCUMENT_ROOT'] . '/templates/email/',
    
    // Production settings
    'production' => [
        'debug' => false, // Set to false in production
        'log_errors' => true, // Log errors to file
        'error_log_path' => $_SERVER['DOCUMENT_ROOT'] . '/logs/email_errors.log',
    ]
];
?>
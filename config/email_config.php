<?php
/**
 * Email Configuration File
 * Contains SMTP settings for Gmail integration
 */

return [
    // SMTP Configuration
    'smtp' => [
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'encryption' => 'tls', // Use 'ssl' for port 465
        'auth' => true,
    ],
    
    // Gmail Account Credentials
    'credentials' => [
        'email' => 'anihanv2@gmail.com',
        'password' => 'gkbh ehwt msjm mnkn', // App password
        'name' => 'Anihan System', // Display name for sender
    ],
    
    // Default Email Settings
    'defaults' => [
        'from_email' => 'anihanv2@gmail.com',
        'from_name' => 'Anihan System',
        'reply_to' => 'anihanv2@gmail.com',
        'charset' => 'UTF-8',
    ],
    
    // Email Templates Directory
    'templates_path' => __DIR__ . '/../templates/email/',
];
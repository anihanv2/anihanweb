<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Anihan System - Admin Account Created</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background-color: #ffffff;
            padding: 0;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            text-align: center;
            padding: 40px 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .message {
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 25px;
            color: #555;
        }
        .account-details {
            background-color: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            border-left: 4px solid #27ae60;
            margin: 25px 0;
        }
        .account-details h3 {
            color: #27ae60;
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .detail-item {
            display: flex;
            margin: 10px 0;
            font-size: 15px;
        }
        .detail-label {
            font-weight: 600;
            min-width: 120px;
            color: #2c3e50;
        }
        .detail-value {
            color: #555;
        }
        .credentials-box {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 25px;
            border-radius: 8px;
            margin: 25px 0;
            text-align: center;
        }
        .credentials-box h4 {
            margin: 0 0 15px 0;
            font-size: 18px;
        }
        .credentials-item {
            background: rgba(255,255,255,0.1);
            padding: 12px 20px;
            border-radius: 6px;
            margin: 8px 0;
            font-family: 'Courier New', monospace;
        }
        .credentials-label {
            font-size: 13px;
            opacity: 0.8;
            display: block;
        }
        .credentials-value {
            font-size: 16px;
            font-weight: bold;
        }
        .security-notice {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }
        .security-notice h4 {
            margin: 0 0 10px 0;
            color: #856404;
            font-size: 16px;
        }
        .security-notice ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .security-notice li {
            margin: 5px 0;
        }
        .features {
            background-color: #e8f5e8;
            padding: 25px;
            border-radius: 8px;
            margin: 25px 0;
        }
        .features h3 {
            color: #27ae60;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .features ul {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }
        .features li {
            padding: 8px 0;
            padding-left: 25px;
            position: relative;
            font-size: 15px;
        }
        .features li:before {
            content: "✓";
            color: #27ae60;
            font-weight: bold;
            position: absolute;
            left: 0;
            font-size: 16px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            padding: 15px 35px;
            text-decoration: none;
            border-radius: 8px;
            margin: 25px 0;
            font-weight: bold;
            font-size: 16px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
            transition: all 0.3s ease;
        }
        .button:hover {
            background: linear-gradient(135deg, #219a52, #27ae60);
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
        }
        .center {
            text-align: center;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            font-size: 14px;
            color: #6c757d;
            border-top: 1px solid #e9ecef;
        }
        .footer h4 {
            color: #495057;
            margin: 0 0 15px 0;
        }
        .contact-info {
            margin: 15px 0;
        }
        .timestamp {
            font-size: 12px;
            color: #adb5bd;
            font-style: italic;
            margin-top: 20px;
        }
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .content, .header, .footer {
                padding: 20px;
            }
            .detail-item {
                flex-direction: column;
            }
            .detail-label {
                min-width: auto;
                font-weight: bold;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🌾 Anihan System</h1>
            <p>Agricultural Management Platform</p>
        </div>
        
        <div class="content">
            <div class="greeting">Welcome to the Team, <?= htmlspecialchars($user_name ?? 'Administrator') ?>!</div>
            
            <div class="message">
                Congratulations! Your administrator account has been successfully created for the Anihan Agricultural Management System. 
                You now have access to our comprehensive platform designed to streamline agricultural operations and data management.
            </div>
            
            <div class="account-details">
                <h3>👤 Account Information</h3>
                <div class="detail-item">
                    <span class="detail-label">Full Name:</span>
                    <span class="detail-value"><?= htmlspecialchars($user_name ?? 'N/A') ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Email Address:</span>
                    <span class="detail-value"><?= htmlspecialchars($user_email ?? 'N/A') ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Role:</span>
                    <span class="detail-value"><?= htmlspecialchars($user_role ?? 'Administrator') ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Account Created:</span>
                    <span class="detail-value"><?= date('F j, Y \a\t g:i A') ?></span>
                </div>
            </div>
            
            <div class="credentials-box">
                <h4>🔐 Your Login Credentials</h4>
                <div class="credentials-item">
                    <span class="credentials-label">Email Address:</span>
                    <div class="credentials-value"><?= htmlspecialchars($user_email ?? 'your-email@example.com') ?></div>
                </div>
                <div class="credentials-item">
                    <span class="credentials-label">Temporary Password:</span>
                    <div class="credentials-value"><?= htmlspecialchars($temporary_password ?? '••••••••') ?></div>
                </div>
            </div>
            
            <div class="security-notice">
                <h4>🔒 Important Security Information</h4>
                <p>For your account security, please follow these important steps:</p>
                <ul>
                    <li><strong>Change your password immediately</strong> after your first login</li>
                    <li>Choose a strong password with at least 8 characters</li>
                    <li>Include uppercase, lowercase, numbers, and special characters</li>
                    <li>Never share your login credentials with anyone</li>
                    <li>Log out completely when using shared computers</li>
                </ul>
            </div>
            
            <div class="features">
                <h3>🚀 What You Can Do with Your Admin Account</h3>
                <ul>
                    <li>Manage user accounts and permissions</li>
                    <li>Monitor agricultural product inventories</li>
                    <li>Track price monitoring and market trends</li>
                    <li>Oversee voucher distribution and records</li>
                    <li>Generate comprehensive reports and analytics</li>
                    <li>Approve applications and manage workflows</li>
                    <li>Monitor sales data across different locations</li>
                    <li>Handle violation reports and compliance</li>
                </ul>
            </div>
            
            <div class="message">
                Ready to get started? Click the button below to access your dashboard and begin managing the agricultural data system.
            </div>
            
            <div class="center">
                <a href="<?= $login_url ?? '#' ?>" class="button">Access Your Dashboard</a>
            </div>
            
            <div class="message">
                If you encounter any issues or have questions about using the system, please don't hesitate to contact our support team. 
                We're here to ensure you have the best experience with the Anihan platform.
            </div>
        </div>
        
        <div class="footer">
            <h4>Anihan Agricultural Management System</h4>
            <div class="contact-info">
                <?= $contact_address ?? 'Agricultural Management Division' ?><br>
                Email: anihanv2@gmail.com<br>
                <?= $contact_phone ?? 'Phone: Contact your system administrator' ?>
            </div>
            
            <div>
                This email contains sensitive account information. Please keep it secure and do not forward it to unauthorized persons.
            </div>
            
            <div class="timestamp">
                Account created: <?= date('Y-m-d H:i:s T') ?>
            </div>
        </div>
    </div>
</body>
</html>
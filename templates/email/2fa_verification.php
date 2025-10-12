<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anihan System - Login Verification Code</title>
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
            background: linear-gradient(135deg, #007bff, #0056b3);
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
        .security-icon {
            text-align: center;
            font-size: 48px;
            color: #007bff;
            margin-bottom: 20px;
        }
        .greeting {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            text-align: center;
        }
        .message {
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 25px;
            color: #555;
            text-align: center;
        }
        .verification-code {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            margin: 30px 0;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }
        .verification-code h3 {
            margin: 0 0 15px 0;
            font-size: 18px;
            opacity: 0.9;
        }
        .code-display {
            font-size: 36px;
            font-weight: bold;
            font-family: 'Courier New', monospace;
            letter-spacing: 8px;
            margin: 15px 0;
            padding: 15px;
            background: rgba(255,255,255,0.2);
            border-radius: 8px;
            border: 2px dashed rgba(255,255,255,0.5);
        }
        .code-info {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 15px;
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
            margin: 8px 0;
        }
        .login-info {
            background-color: #e7f3ff;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
            margin: 25px 0;
        }
        .login-info h4 {
            color: #007bff;
            margin: 0 0 10px 0;
        }
        .info-item {
            display: flex;
            margin: 8px 0;
            font-size: 14px;
        }
        .info-label {
            font-weight: 600;
            min-width: 100px;
            color: #495057;
        }
        .info-value {
            color: #6c757d;
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
        .urgent {
            color: #dc3545;
            font-weight: bold;
        }
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .content, .header, .footer {
                padding: 20px;
            }
            .code-display {
                font-size: 28px;
                letter-spacing: 4px;
            }
            .info-item {
                flex-direction: column;
            }
            .info-label {
                min-width: auto;
                font-weight: bold;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🔐 Anihan System</h1>
            <p>Login Verification Required</p>
        </div>
        
        <div class="content">
            <div class="security-icon">🛡️</div>
            
            <div class="greeting">Hello <?= htmlspecialchars($user_name ?? 'User') ?>!</div>
            
            <div class="message">
                We received a login attempt for your account. To complete the sign-in process, 
                please enter the verification code below in the login form.
            </div>
            
            <div class="verification-code">
                <h3>🔢 Your Verification Code</h3>
                <div class="code-display"><?= htmlspecialchars($verification_code ?? '000000') ?></div>
                <div class="code-info">
                    This code expires in <strong><?= $expires_minutes ?? 5 ?> minutes</strong>
                </div>
            </div>
            
            <div class="login-info">
                <h4>📋 Login Attempt Details</h4>
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value"><?= htmlspecialchars($user_email ?? 'N/A') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Time:</span>
                    <span class="info-value"><?= $timestamp ?? date('Y-m-d H:i:s') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">IP Address:</span>
                    <span class="info-value"><?= htmlspecialchars($ip_address ?? 'Unknown') ?></span>
                </div>
            </div>
            
            <div class="security-notice">
                <h4>🚨 Important Security Information</h4>
                <ul>
                    <li><strong>Never share this code</strong> with anyone, including support staff</li>
                    <li><strong>Code expires</strong> in <?= $expires_minutes ?? 5 ?> minutes from the time it was sent</li>
                    <li><strong>If you didn't request this code</strong>, someone may be trying to access your account</li>
                    <li><strong>Only enter this code</strong> on the official Anihan login page</li>
                    <li><strong>Contact support immediately</strong> if you suspect unauthorized access</li>
                </ul>
            </div>
            
            <div class="message">
                If you didn't attempt to log in, please <span class="urgent">ignore this email</span> and 
                consider changing your password as a security precaution.
            </div>
        </div>
        
        <div class="footer">
            <h4>Anihan Agricultural Management System</h4>
            <div class="contact-info">
                Email: anihanv2@gmail.com<br>
                This is an automated security email from the Anihan System.
            </div>
            
            <div>
                <strong>Security Reminder:</strong> Anihan support will never ask for your verification code via email or phone.
            </div>
            
            <div class="timestamp">
                Code generated: <?= $timestamp ?? date('Y-m-d H:i:s T') ?>
            </div>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Email from Anihan System' ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e8e8e8;
        }
        .logo {
            max-width: 150px;
            height: auto;
            margin-bottom: 10px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin: 0;
        }
        .content {
            margin: 20px 0;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 15px;
            color: #2c3e50;
        }
        .message {
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #2980b9;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e8e8e8;
            font-size: 14px;
            color: #7f8c8d;
            text-align: center;
        }
        .contact-info {
            margin: 15px 0;
        }
        .social-links {
            margin-top: 15px;
        }
        .social-links a {
            color: #3498db;
            text-decoration: none;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <?php if (isset($logo_url)): ?>
                <img src="<?= $logo_url ?>" alt="Anihan Logo" class="logo">
            <?php endif; ?>
            <h1 class="company-name">Anihan System</h1>
        </div>
        
        <div class="content">
            <?php if (isset($recipient_name)): ?>
                <div class="greeting">Hello <?= htmlspecialchars($recipient_name) ?>,</div>
            <?php else: ?>
                <div class="greeting">Hello,</div>
            <?php endif; ?>
            
            <div class="message">
                <?= $message ?? 'Thank you for using our system.' ?>
            </div>
            
            <?php if (isset($action_url) && isset($action_text)): ?>
                <div style="text-align: center;">
                    <a href="<?= $action_url ?>" class="button"><?= $action_text ?></a>
                </div>
            <?php endif; ?>
            
            <?php if (isset($additional_content)): ?>
                <div class="message">
                    <?= $additional_content ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="footer">
            <div class="contact-info">
                <strong>Anihan System</strong><br>
                <?= $contact_address ?? 'Your Address Here' ?><br>
                Email: anihanv2@gmail.com<br>
                <?= $contact_phone ?? 'Phone: Your Phone Number' ?>
            </div>
            
            <div>
                This email was sent from the Anihan System. If you have any questions, please contact us.
            </div>
            
            <?php if (isset($unsubscribe_url)): ?>
                <div style="margin-top: 15px;">
                    <a href="<?= $unsubscribe_url ?>">Unsubscribe</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
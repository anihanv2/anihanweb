<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Anihan System</title>
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
            border-bottom: 2px solid #27ae60;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #27ae60;
            margin: 0;
        }
        .welcome-message {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }
        .welcome-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
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
        .features {
            background-color: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .features h3 {
            color: #27ae60;
            margin-top: 0;
        }
        .features ul {
            list-style-type: none;
            padding-left: 0;
        }
        .features li {
            margin: 8px 0;
            padding-left: 20px;
            position: relative;
        }
        .features li:before {
            content: "✓";
            color: #27ae60;
            font-weight: bold;
            position: absolute;
            left: 0;
        }
        .button {
            display: inline-block;
            background-color: #27ae60;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
            font-size: 16px;
        }
        .button:hover {
            background-color: #219a52;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e8e8e8;
            font-size: 14px;
            color: #7f8c8d;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1 class="company-name">Anihan System</h1>
        </div>
        
        <div class="welcome-message">
            <div class="welcome-title">Welcome!</div>
            <div>We're excited to have you on board</div>
        </div>
        
        <div class="content">
            <div class="greeting">Hello <?= htmlspecialchars($user_name ?? 'there') ?>,</div>
            
            <div class="message">
                Welcome to the Anihan System! We're thrilled to have you join our community. 
                Your account has been successfully created and you're now ready to explore all 
                the features we have to offer.
            </div>
            
            <div class="features">
                <h3>What you can do with Anihan System:</h3>
                <ul>
                    <li>Monitor agricultural products and prices</li>
                    <li>Track inventory and sales</li>
                    <li>Manage voucher systems</li>
                    <li>Access detailed reports and analytics</li>
                    <li>Connect with your local agricultural community</li>
                </ul>
            </div>
            
            <div class="message">
                <?= $welcome_message ?? "To get started, simply click the button below to access your dashboard and begin exploring the system." ?>
            </div>
            
            <div style="text-align: center;">
                <a href="<?= $login_url ?? '#' ?>" class="button">Get Started</a>
            </div>
            
            <div class="message">
                If you have any questions or need assistance, don't hesitate to reach out to our support team. 
                We're here to help you make the most of your Anihan System experience.
            </div>
        </div>
        
        <div class="footer">
            <div>
                <strong>Anihan System</strong><br>
                Email: anihanv2@gmail.com<br>
                This email was sent because you registered for an account with us.
            </div>
        </div>
    </div>
</body>
</html>
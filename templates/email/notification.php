<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification from Anihan System</title>
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
            border-bottom: 2px solid #3498db;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #3498db;
            margin: 0;
        }
        .notification-type {
            background-color: #3498db;
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
            font-size: 18px;
            font-weight: bold;
        }
        .notification-type.success {
            background-color: #27ae60;
        }
        .notification-type.warning {
            background-color: #f39c12;
        }
        .notification-type.error {
            background-color: #e74c3c;
        }
        .notification-type.info {
            background-color: #3498db;
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
        .details-box {
            background-color: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #3498db;
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
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e8e8e8;
            font-size: 14px;
            color: #7f8c8d;
            text-align: center;
        }
        .timestamp {
            font-size: 12px;
            color: #95a5a6;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1 class="company-name">Anihan System</h1>
        </div>
        
        <div class="notification-type <?= $notification_type ?? 'info' ?>">
            <?= strtoupper($notification_title ?? 'System Notification') ?>
        </div>
        
        <div class="content">
            <div class="greeting">Hello <?= htmlspecialchars($recipient_name ?? 'there') ?>,</div>
            
            <div class="message">
                <?= $notification_message ?? 'This is a notification from the Anihan System.' ?>
            </div>
            
            <?php if (isset($details)): ?>
            <div class="details-box">
                <strong>Details:</strong><br>
                <?= $details ?>
            </div>
            <?php endif; ?>
            
            <?php if (isset($action_required) && $action_required): ?>
            <div class="message">
                <strong>Action Required:</strong> <?= $action_description ?? 'Please review and take appropriate action.' ?>
            </div>
            <?php endif; ?>
            
            <?php if (isset($action_url) && isset($action_text)): ?>
            <div style="text-align: center;">
                <a href="<?= $action_url ?>" class="button"><?= $action_text ?></a>
            </div>
            <?php endif; ?>
            
            <?php if (isset($timestamp)): ?>
            <div class="timestamp">
                Notification sent: <?= $timestamp ?>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="footer">
            <div>
                <strong>Anihan System</strong><br>
                Email: anihanv2@gmail.com<br>
                This is an automated notification from the system.
            </div>
        </div>
    </div>
</body>
</html>
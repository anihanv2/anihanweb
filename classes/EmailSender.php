<?php
/**
 * EmailSender Class
 * A wrapper class for PHPMailer to simplify sending emails
 */

require_once __DIR__ . '/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailSender
{
    private $config;
    private $mail;
    
    public function __construct()
    {
        // Load configuration
        $this->config = require __DIR__ . '/../config/email_config.php';
        
        // Initialize PHPMailer
        $this->mail = new PHPMailer(true);
        $this->setupSMTP();
    }
    
    /**
     * Configure SMTP settings
     */
    private function setupSMTP()
    {
        try {
            // Server settings
            $this->mail->isSMTP();
            $this->mail->Host = $this->config['smtp']['host'];
            $this->mail->SMTPAuth = $this->config['smtp']['auth'];
            $this->mail->Username = $this->config['credentials']['email'];
            $this->mail->Password = $this->config['credentials']['password'];
            $this->mail->SMTPSecure = $this->config['smtp']['encryption'];
            $this->mail->Port = $this->config['smtp']['port'];
            
            // Default sender info
            $this->mail->setFrom(
                $this->config['defaults']['from_email'],
                $this->config['defaults']['from_name']
            );
            
            // Reply-to
            $this->mail->addReplyTo($this->config['defaults']['reply_to']);
            
            // Content settings
            $this->mail->isHTML(true);
            $this->mail->CharSet = $this->config['defaults']['charset'];
            
        } catch (Exception $e) {
            throw new Exception("SMTP Configuration Error: {$this->mail->ErrorInfo}");
        }
    }
    
    /**
     * Send email to multiple recipients
     * 
     * @param array $recipients Array of email addresses or [email => name] pairs
     * @param string $subject Email subject
     * @param string $body HTML email body
     * @param string|null $altBody Plain text alternative
     * @param array $attachments Optional file attachments
     * @return array Results array with success/failure info
     */
    public function sendToMultiple($recipients, $subject, $body, $altBody = null, $attachments = [])
    {
        $results = [
            'sent' => [],
            'failed' => [],
            'total_sent' => 0,
            'total_failed' => 0
        ];
        
        foreach ($recipients as $email => $name) {
            // Handle both indexed arrays and associative arrays
            if (is_numeric($email)) {
                $email = $name;
                $name = '';
            }
            
            try {
                // Clear previous recipients
                $this->mail->clearAddresses();
                $this->mail->clearAttachments();
                
                // Add recipient
                $this->mail->addAddress($email, $name);
                
                // Set content
                $this->mail->Subject = $subject;
                $this->mail->Body = $body;
                if ($altBody) {
                    $this->mail->AltBody = $altBody;
                }
                
                // Add attachments if provided
                foreach ($attachments as $attachment) {
                    if (is_array($attachment)) {
                        $this->mail->addAttachment($attachment['path'], $attachment['name'] ?? '');
                    } else {
                        $this->mail->addAttachment($attachment);
                    }
                }
                
                // Send email
                $this->mail->send();
                
                $results['sent'][] = $email;
                $results['total_sent']++;
                
            } catch (Exception $e) {
                $results['failed'][] = [
                    'email' => $email,
                    'error' => $this->mail->ErrorInfo
                ];
                $results['total_failed']++;
            }
        }
        
        return $results;
    }
    
    /**
     * Send email to single recipient
     * 
     * @param string $email Recipient email
     * @param string $subject Email subject
     * @param string $body HTML email body
     * @param string $name Recipient name (optional)
     * @param string|null $altBody Plain text alternative
     * @param array $attachments Optional file attachments
     * @return bool Success status
     */
    public function sendToSingle($email, $subject, $body, $name = '', $altBody = null, $attachments = [])
    {
        $recipients = [$email => $name];
        $results = $this->sendToMultiple($recipients, $subject, $body, $altBody, $attachments);
        
        return $results['total_sent'] > 0;
    }
    
    /**
     * Send email using template
     * 
     * @param array $recipients Email recipients
     * @param string $template Template filename (without .php extension)
     * @param array $variables Template variables
     * @param string $subject Email subject
     * @return array Results array
     */
    public function sendWithTemplate($recipients, $template, $variables = [], $subject = '')
    {
        $templatePath = $this->config['templates_path'] . $template . '.php';
        
        if (!file_exists($templatePath)) {
            throw new Exception("Email template not found: {$templatePath}");
        }
        
        // Extract variables for template
        extract($variables);
        
        // Capture template output
        ob_start();
        include $templatePath;
        $body = ob_get_clean();
        
        return $this->sendToMultiple($recipients, $subject, $body);
    }
    
    /**
     * Validate email address
     */
    public function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Get configuration
     */
    public function getConfig()
    {
        return $this->config;
    }
}
?>
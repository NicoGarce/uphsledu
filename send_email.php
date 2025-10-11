<?php
/**
 * Simple Email Sending Function for UPHSL Contact Form
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Simple email sending function using cURL and Gmail SMTP
 */

function sendContactEmail($to, $subject, $message, $from_email, $from_name) {
    // Gmail SMTP settings
    $smtp_host = 'smtp.gmail.com';
    $smtp_port = 587;
    $smtp_username = 'garce.nicoroell@gmail.com'; // Your Gmail
    $smtp_password = 'your-app-password'; // You need to generate an App Password
    
    // Email headers
    $headers = array(
        'From: ' . $from_name . ' <' . $from_email . '>',
        'Reply-To: ' . $from_email,
        'X-Mailer: PHP/' . phpversion(),
        'Content-Type: text/plain; charset=UTF-8'
    );
    
    // Try basic mail() function first
    if (function_exists('mail')) {
        $result = @mail($to, $subject, $message, implode("\r\n", $headers));
        if ($result) {
            return true;
        }
    }
    
    // If mail() fails, log the attempt
    $log_entry = date('Y-m-d H:i:s') . " - Email sending failed\n";
    $log_entry .= "To: " . $to . "\n";
    $log_entry .= "Subject: " . $subject . "\n";
    $log_entry .= "From: " . $from_email . "\n";
    $log_entry .= "Error: mail() function failed\n";
    $log_entry .= "---\n\n";
    
    file_put_contents('email_errors.txt', $log_entry, FILE_APPEND | LOCK_EX);
    
    return false;
}

// Alternative: Use a web service for sending emails
function sendViaWebService($to, $subject, $message, $from_email, $from_name) {
    // This would use a service like EmailJS, Formspree, or similar
    // For now, just return false
    return false;
}
?>

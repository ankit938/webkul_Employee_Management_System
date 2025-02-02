<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include the Composer autoloader
require __DIR__ . '/../../vendor/autoload.php'; // Correct path to autoload.php

function sendResetEmail($email, $token) {
    $mail = new PHPMailer(true);
    try {
        // Set mailer to use SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Gmail SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@gmail.com'; // Replace with your email
        $mail->Password = 'your_email_password'; // Replace with your email password or app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender and recipient
        $mail->setFrom('your_email@gmail.com', 'Webkul Support'); // Your email
        $mail->addAddress($email); // Recipient's email

        // Create the reset link
        $reset_link = "http://localhost/webkul/pages/reset_password.php?token=$token";

        // HTML email format
        $mail->isHTML(true);
        $mail->Subject = "Password Reset Request";
        $mail->Body = "Click the link to reset your password: <a href='$reset_link'>$reset_link</a>";

        // Send the email
        $mail->send();
    } catch (Exception $e) {
        // Log the error message if the email sending fails
        error_log("Email sending failed: " . $mail->ErrorInfo);
    }
}
?>

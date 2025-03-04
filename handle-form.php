<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\OAuth;
use League\OAuth2\Client\Provider\Google;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $message = htmlspecialchars($_POST['message']);

    if (!$email) {
        die("Invalid email address.");
    }

    // Gmail OAuth 2.0 Credentials (from environment variables)
    $clientId = getenv('GOOGLE_CLIENT_ID');
    $clientSecret = getenv('GOOGLE_CLIENT_SECRET');
    $refreshToken = getenv('GOOGLE_REFRESH_TOKEN');
    $gmailAccount = getenv('GMAIL_ACCOUNT');

    // Penerima
    $recipientEmail = getenv('RECIPIENT_EMAIL');
    $recipientName = getenv('RECIPIENT_NAME');

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->AuthType   = 'XOAUTH2';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // OAuth 2.0 Configuration
        $mail->setOAuth(new OAuth([
            'provider' => new Google([
                'clientId'     => $clientId,
                'clientSecret' => $clientSecret,
            ]),
            'clientId'     => $clientId,
            'clientSecret' => $clientSecret,
            'refreshToken' => $refreshToken,
            'userName'     => $gmailAccount,
        ]));

        // Send email to recipient
        $mail->setFrom($gmailAccount, 'Contact Form');
        $mail->addAddress($recipientEmail, $recipientName);
        $mail->isHTML(true);
        $mail->Subject = 'Contact Form Submission';
        $mail->Body    = "
            <h3>Contact Form Submission</h3>
            <p><strong>Name:</strong> $username</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Message:</strong></p>
            <p>$message</p>
        ";
        $mail->send();

        // Send confirmation email to the user
        $mail->clearAddresses(); // Clear previous recipients
        $mail->addAddress($email, $username);
        $mail->Subject = 'Thank You for Your Submission!';
        $mail->Body    = "
            <h2>Hi $username,</h2>
            <p>Thank you for reaching out! Your form has been successfully submitted. Here's what you sent us:</p>
            <hr>
            <p><strong>Your Message:</strong></p>
            <blockquote>$message</blockquote>
            <hr>
            <p>We'll get back to you shortly!</p>
            <p>Best regards,</p>
            <p><strong>$recipientName</strong></p>
        ";
        $mail->send();

        echo "Email successfully sent!";
        exit;
    } catch (Exception $e) {
        error_log("Email sending failed: {$mail->ErrorInfo}");
        echo "An error occurred. Please try again later.";
    }
} else {
    echo "Invalid request.";
}

<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// CORS Configuration
header("Access-Control-Allow-Origin: https://fatonyahmadfauzi.netlify.app");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Tangani preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Load dependencies
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\OAuth;
use League\OAuth2\Client\Provider\Google;
use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Validate form data
$response = ['status' => 'error', 'message' => ''];

try {
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        throw new Exception("Invalid request method");
    }

    // Input validation
    $requiredFields = ['name', 'email', 'message'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("All fields are required");
        }
    }

    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');

    if (!$email) {
        throw new Exception("Invalid email format");
    }

    // Configure PHPMailer
    $mail = new PHPMailer(true);

    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->AuthType = 'XOAUTH2';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // OAuth2 Configuration
    $provider = new Google([
        'clientId' => $_ENV['GOOGLE_CLIENT_ID'],
        'clientSecret' => $_ENV['GOOGLE_CLIENT_SECRET']
    ]);

    $mail->setOAuth(new OAuth([
        'provider' => $provider,
        'clientId' => $_ENV['GOOGLE_CLIENT_ID'],
        'clientSecret' => $_ENV['GOOGLE_CLIENT_SECRET'],
        'refreshToken' => $_ENV['GOOGLE_REFRESH_TOKEN'],
        'userName' => $_ENV['GMAIL_ACCOUNT']
    ]));

    // Send to admin
    $mail->setFrom($_ENV['GMAIL_ACCOUNT'], 'Contact Form');
    $mail->addAddress($_ENV['RECIPIENT_EMAIL'], $_ENV['RECIPIENT_NAME']);
    $mail->Subject = 'New Contact Form Submission';
    $mail->isHTML(true);
    $mail->Body = "
        <h3>New Message From $name</h3>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Message:</strong></p>
        <p>$message</p>
    ";

    $mail->send();

    // Send confirmation to user
    $mail->clearAddresses();
    $mail->addAddress($email, $name);
    $mail->Subject = 'Thank You for Contacting Us';
    $mail->Body = "
        <h2>Hi $name,</h2>
        <p>We've received your message:</p>
        <blockquote>$message</blockquote>
        <p>We'll respond within 24 hours.</p>
        <p>Best regards,<br>{$_ENV['RECIPIENT_NAME']}</p>
    ";

    $mail->send();

    $response = [
        'status' => 'success',
        'message' => 'Message sent successfully!'
    ];
} catch (Exception $e) {
    error_log('Email Error: ' . $e->getMessage());
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);

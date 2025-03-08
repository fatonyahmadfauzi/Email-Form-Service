<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// CORS Configuration
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Load dependencies
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Response template
$response = ['status' => 'error', 'message' => ''];

try {
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        throw new Exception("Invalid request method");
    }

    // Parse JSON payload
    $input = json_decode(file_get_contents('php://input'), true);

    // Input validation
    $requiredFields = ['name', 'email', 'message'];
    foreach ($requiredFields as $field) {
        if (empty($input[$field])) {
            throw new Exception("All fields are required");
        }
    }

    $name = htmlspecialchars($input['name'], ENT_QUOTES, 'UTF-8');
    $email = filter_var($input['email'], FILTER_VALIDATE_EMAIL);
    $message = htmlspecialchars($input['message'], ENT_QUOTES, 'UTF-8');

    if (!$email) {
        throw new Exception("Invalid email format");
    }

    // Configure PHPMailer
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp-relay.brevo.com'; // Brevo SMTP host
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['BREVO_EMAIL']; // Your Brevo email
    $mail->Password = $_ENV['BREVO_API_KEY']; // Your Brevo API key
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS
    $mail->Port = 587;

    // Send to admin
    $mail->setFrom($_ENV['BREVO_EMAIL'], 'Contact Form'); // Sender email
    $mail->addAddress($_ENV['RECIPIENT_EMAIL'], $_ENV['RECIPIENT_NAME']); // Recipient email
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
    $mail->addAddress($email, $name); // User email
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

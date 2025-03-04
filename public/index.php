<?php
// ==============================================
// 1. Konfigurasi Awal & Error Handling
// ==============================================
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ==============================================
// 2. Load Dependencies
// ==============================================
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\OAuth;
use League\OAuth2\Client\Provider\Google;

// ==============================================
// 3. Main Logic
// ==============================================
try {
    // Validasi request method
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        throw new Exception("Method not allowed", 405);
    }

    // Validasi input
    $requiredFields = ['name', 'email', 'message'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Missing required field: $field", 400);
        }
    }

    $username = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $message = htmlspecialchars($_POST['message']);

    if (!$email) {
        throw new Exception("Invalid email format", 400);
    }

    // ==============================================
    // 4. Email Configuration
    // ==============================================
    $mail = new PHPMailer(true);

    // Setup Google OAuth Provider
    $provider = new Google([
        'clientId'     => $_ENV['GOOGLE_CLIENT_ID'],
        'clientSecret' => $_ENV['GOOGLE_CLIENT_SECRET']
    ]);

    // Konfigurasi OAuth
    $mail->setOAuth(new OAuth([
        'provider'     => $provider,
        'clientId'     => $_ENV['GOOGLE_CLIENT_ID'],
        'clientSecret' => $_ENV['GOOGLE_CLIENT_SECRET'],
        'refreshToken' => $_ENV['GOOGLE_REFRESH_TOKEN'],
        'userName'     => $_ENV['GMAIL_ACCOUNT']
    ]));

    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->AuthType   = 'XOAUTH2';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // ==============================================
    // 5. Kirim Email ke Penerima
    // ==============================================
    $mail->setFrom($_ENV['GMAIL_ACCOUNT'], 'Contact Form');
    $mail->addAddress($_ENV['RECIPIENT_EMAIL'], $_ENV['RECIPIENT_NAME']);
    $mail->isHTML(true);
    $mail->Subject = 'New Contact Form Submission';

    $mail->Body = sprintf(
        '<h3>New Message from %s</h3>
        <p><strong>Email:</strong> %s</p>
        <p><strong>Message:</strong></p>
        <div style="border-left: 3px solid #ccc; padding-left: 1rem; margin: 1rem 0;">
            %s
        </div>',
        $username,
        $email,
        nl2br($message)
    );

    $mail->send();

    // ==============================================
    // 6. Kirim Email Konfirmasi
    // ==============================================
    $mail->clearAddresses();
    $mail->addAddress($email, $username);
    $mail->Subject = 'Thank You for Contacting Us';

    $mail->Body = sprintf(
        '<h2>Hi %s,</h2>
        <p>We have received your message:</p>
        <blockquote style="margin: 1rem 0; padding: 1rem; background: #f8f9fa; border-left: 3px solid #007bff;">
            %s
        </blockquote>
        <p>We will respond within 24-48 business hours.</p>',
        $username,
        nl2br($message)
    );

    $mail->send();

    // Response sukses
    echo json_encode([
        'status' => 'success',
        'message' => 'Email successfully sent to both parties'
    ]);
} catch (Exception $e) {
    // Error handling
    error_log("[EMAIL ERROR] " . $e->getMessage());
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'code' => $e->getCode()
    ]);
}

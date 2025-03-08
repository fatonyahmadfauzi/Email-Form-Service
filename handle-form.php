<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// CORS Configuration
header("Access-Control-Allow-Origin: https://fatonyahmadfauzi.netlify.app");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Load dependencies
require __DIR__ . '/vendor/autoload.php';

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

    // Prepare Brevo API request
    $apiKey = $_ENV['BREVO_API_KEY']; // Your Brevo API key
    $url = "https://api.brevo.com/v3/smtp/email";

    $data = [
        'sender' => [
            'name' => 'Contact Form',
            'email' => $_ENV['BREVO_EMAIL'],
        ],
        'to' => [
            [
                'email' => $_ENV['RECIPIENT_EMAIL'],
                'name' => $_ENV['RECIPIENT_NAME'],
            ],
        ],
        'subject' => "New Contact Form Submission",
        'htmlContent' => "
            <h3>New Message From $name</h3>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Message:</strong></p>
            <p>$message</p>
        ",
    ];

    // cURL for Brevo API request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "api-key: $apiKey",
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpCode !== 201) {
        throw new Exception("Failed to send email: " . curl_error($ch));
    }

    curl_close($ch);

    $response = [
        'status' => 'success',
        'message' => 'Message sent successfully!',
    ];
} catch (Exception $e) {
    error_log('Email Error: ' . $e->getMessage());
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);

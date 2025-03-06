# FAA Form Backend

This repository contains the backend implementation for a contact form application. It handles form submissions, validates input, and sends emails using PHPMailer with OAuth2 authentication.

## Features

- Receive form submissions via POST requests
- Validate and sanitize user input
- Send emails to specified recipients using Gmail SMTP with OAuth2
- Return JSON responses for success or failure

## Prerequisites

Before setting up the project, ensure you have:

1. PHP 8.0 or higher
2. Composer installed
3. Gmail account with API access (Client ID, Client Secret, Refresh Token)
4. Deployment platform like [Railway](https://railway.app/)

## Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/faa-form-backend.git
cd faa-form-backend
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Environment Variables

Create **.env** file:

```bash
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REFRESH_TOKEN=your-google-refresh-token
GMAIL_ACCOUNT=your-gmail-account@gmail.com
RECIPIENT_EMAIL=recipient-email@example.com
RECIPIENT_NAME=Recipient Name
```

### 4. Deploy on Railway

1. Create new Railway project and connect repository
2. Add environment variables
3. Deploy service

### 5. Update Frontend Integration

Update frontend fetch URL:

```bash
fetch("https://your-backend-url.up.railway.app/handle-form.php", {
  method: "POST",
  headers: {
    "Content-Type": "application/json",
  },
  body: JSON.stringify(formData),
});
```

## API Endpoint

### POST **/handle-form.php**

**Request Body:**

```bash
{
  "name": "John Doe",
  "email": "johndoe@example.com",
  "message": "Hello, this is a test message."
}
```

**Responses:**

- Success:

```bash
{ "status": "success", "message": "Message sent successfully!" }
```

- Error:

```bash
{ "status": "error", "message": "Error message here." }
```

## Error Handling

- Errors logged in server logs
- Disable **display_errors** in production

## Libraries Used

- [PHPMailer](https://github.com/PHPMailer/PHPMailer) - Email handling
- [League OAuth2 Client](https://github.com/thephpleague/oauth2-client) - OAuth2 integration
- [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) - Environment variable management

## Troubleshooting

- **CORS Issues**: Verify CORS headers configuration
- **OAuth Errors**: Check Google credentials validity
- **500 Errors**: Review server logs for details

## License

This project is open-source and available under the [MIT License](https://opensource.org/licenses/MIT).

## Acknowledgments

- PHPMailer contributors
- PHP community support

# FAA Form Backend with Node js Serverless Function for Email Backend Service on Vercel

This repository contains a serverless function for sending emails using Nodemailer and Gmail. The function handles both user and admin email notifications, ensuring a smooth experience for contact form submissions.

## Features

- Send email notifications to users who submit the contact form.
- Send email notifications to the admin with details of the form submission.
- Fully customizable email content.
- Environment variable support for secure and flexible configuration.

## Prerequisites

- Node.js installed on your local machine.
- A Gmail account with an App Password configured.
- The following environment variables set up:
  - `GMAIL_EMAIL`: Your Gmail address.
  - `GMAIL_APP_PASSWORD`: Your Gmail App Password.
  - `RECIPIENT_EMAIL`: The admin's email address where notifications will be sent.
  - `RECIPIENT_NAME`: The name of the admin or recipient.

## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/your-repo/serverless-email.git
   cd serverless-email
   ```

2. Install dependencies:

   ```bash
   npm install
   ```

3. Create a `.env` file in the root directory and add the following environment variables:
   ```env
   GMAIL_EMAIL=your-email@gmail.com
   GMAIL_APP_PASSWORD=your-app-password
   RECIPIENT_EMAIL=admin-email@example.com
   RECIPIENT_NAME=Admin Name
   ```

## Usage

### Endpoint

The function exposes a POST endpoint where you can send contact form submissions.

### Request Payload

The payload should be in JSON format with the following fields:

```json
{
  "name": "Your Name",
  "email": "your-email@example.com",
  "message": "Your message here."
}
```

### Response

The server will respond with:

- `200 OK` if the emails were sent successfully.
- `400 Bad Request` if any required fields are missing.
- `500 Internal Server Error` if an error occurs while sending emails.

## Email Content

### Email to Admin

The admin will receive an email with the following content:

- Subject: **New Contact Form Submission!**
- Body:
  ```html
  <h3>New Message From {name}</h3>
  <p><strong>Email:</strong> {email}</p>
  <p><strong>Message:</strong></p>
  <p>{message}</p>
  ```

### Email to User

The user will receive a confirmation email with the following content:

- Subject: **Thank You for Contacting Us!**
- Body:
  ```html
  <h2>Hi {name},</h2>
  <p>We've received your message:</p>
  <blockquote>{message}</blockquote>
  <p>We'll respond within 24 hours.</p>
  <p>Best regards,<br />{RECIPIENT_NAME}</p>
  ```

## Deploying the Function

You can deploy this function to platforms like Vercel or Netlify that support serverless functions.

### Example Deployment on Vercel

1. Install the Vercel CLI:

   ```bash
   npm install -g vercel
   ```

2. Deploy the function:

   ```bash
   vercel
   ```

3. Set the environment variables on Vercel:
   ```bash
   vercel env add
   ```

## License

This project is licensed under the MIT License.

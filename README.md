# FAA Form Backend with Node js Serverless Function for Email Backend Service on Vercel

This project is a serverless function for handling contact form submissions and sending emails using Gmail's SMTP service. It is designed to send a confirmation email to the user and notify the admin about new submissions.

## Features

- Sends a confirmation email to the user.
- Notifies the admin with the user's message.
- Handles CORS for requests from specific origins.
- Uses environment variables for configuration.

## Prerequisites

1. Create a Gmail account (or use an existing one).
2. Generate an **App Password** for your Gmail account:
   - Go to [Google Account Security](https://myaccount.google.com/security).
   - Enable **2-Step Verification**.
   - Generate an **App Password** for "Mail" and "Other" app.

## Installation

1. Clone the repository:

   ```bash
   git clone <repository-url>
   cd <repository-folder>
   ```

2. Install dependencies:

   ```bash
   npm install
   ```

3. Create a `.env` file in the root directory and add the following variables:

   ```env
   RECIPIENT_NAME=Your Recipient Name Here
   GMAIL_EMAIL=your-email@gmail.com
   GMAIL_APP_PASSWORD=your-app-password
   RECIPIENT_EMAIL=recipient-admin-email@gmail.com
   ```

4. Deploy the function to a serverless platform (e.g., Vercel, Netlify, etc.).

## Usage

1. Make a POST request to the deployed endpoint with the following JSON payload:

   ```json
   {
     "name": "User's Name",
     "email": "user@example.com",
     "message": "The user's message."
   }
   ```

2. Example cURL request:
   ```bash
   curl -X POST https://your-deployment-url/api/send-email \
   -H "Content-Type: application/json" \
   -d '{"name": "John Doe", "email": "john.doe@example.com", "message": "Hello!"}'
   ```

## Code Explanation

The serverless function is defined in `send-email.js`:

```javascript
import nodemailer from "nodemailer";
import dotenv from "dotenv";

dotenv.config();

export default async function handler(req, res) {
  res.setHeader(
    "Access-Control-Allow-Origin",
    "https://fatonyahmadfauzi.netlify.app"
  );
  res.setHeader("Access-Control-Allow-Methods", "POST, GET, OPTIONS");
  res.setHeader("Access-Control-Allow-Headers", "Content-Type");

  if (req.method === "OPTIONS") {
    return res.status(200).end();
  }

  if (req.method !== "POST") {
    return res.status(405).json({ message: "Method not allowed" });
  }

  const { name, email, message } = req.body;

  if (!name || !email || !message) {
    return res.status(400).json({ message: "All fields are required" });
  }

  try {
    const transporter = nodemailer.createTransport({
      service: "Gmail",
      auth: {
        user: process.env.GMAIL_EMAIL, // Gmail email
        pass: process.env.GMAIL_APP_PASSWORD, // App Password Gmail
      },
    });

    const recipientName = process.env.RECIPIENT_NAME || "Recipient"; // Default to "Recipient" if not set

    // Kirim email ke pengguna
    await transporter.sendMail({
      from: `"${recipientName}" <${process.env.GMAIL_EMAIL}>`,
      to: email,
      subject: "Thank you for contacting us!",
      html: `
        <h3>Hi ${name},</h3>
        <p>We've received your message:</p>
        <blockquote>${message}</blockquote>
        <p>We'll respond shortly.</p>
      `,
    });

    // Kirim email ke admin
    await transporter.sendMail({
      from: `"${recipientName}" <${process.env.GMAIL_EMAIL}>`,
      to: process.env.RECIPIENT_EMAIL,
      subject: "New Contact Form Submission",
      html: `
        <h3>New Message From ${name}</h3>
        <p><strong>Email:</strong> ${email}</p>
        <p><strong>Message:</strong></p>
        <p>${message}</p>
      `,
    });

    return res
      .status(200)
      .json({ status: "success", message: "Emails sent successfully!" });
  } catch (error) {
    console.error(error);
    return res
      .status(500)
      .json({ status: "error", message: "Internal server error" });
  }
}
```

## Deployment

### Vercel

1. Deploy the repository to Vercel.
2. Add the environment variables in the Vercel dashboard under "Settings > Environment Variables".

### Netlify

1. Deploy the repository to Netlify.
2. Add the environment variables in the Netlify dashboard under "Site settings > Environment Variables".

## Testing

Use a tool like Postman or cURL to send a POST request to your deployed function and verify the email sending functionality.

## Troubleshooting

- Ensure the **App Password** is correctly set up and matches the Gmail account.
- Verify that the recipient email is valid.
- Check server logs for errors if emails are not sent.

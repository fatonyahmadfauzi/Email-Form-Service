# FAA Form Backend with Node js Serverless Function for Email Backend Service on Vercel

This repository provides a serverless function (`send-email.js`) to handle email sending using Gmail SMTP and Nodemailer. It is configured with environment variables to keep sensitive information secure.

## Prerequisites

Before using this function, ensure you have the following:

1. **Node.js**: Install the latest LTS version of [Node.js](https://nodejs.org/).
2. **Gmail Account**: Set up a Gmail account with an **App Password**.
3. **Vercel Account**: Deploy the serverless function to [Vercel](https://vercel.com/).

---

## Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/your-repository.git
cd your-repository
```

### 2. Install Dependencies

Run the following command to install the required packages:

```bash
npm install
```

### 3. Environment Variables

Create a `.env` file in the root directory and add the following variables:

```env
GMAIL_EMAIL=your-email@gmail.com
GMAIL_APP_PASSWORD=your-app-password
RECIPIENT_EMAIL=admin-email@example.com
```

- `GMAIL_EMAIL`: Your Gmail address used to send emails.
- `GMAIL_APP_PASSWORD`: The App Password generated from your Gmail account.
- `RECIPIENT_EMAIL`: The admin email address where contact form submissions will be sent.

> **Note**: Ensure your Gmail account has enabled App Passwords. [Learn more about App Passwords here.](https://support.google.com/accounts/answer/185833)

---

### 4. Test Locally

To test the function locally, run the development server:

```bash
npm run dev
```

Send a POST request to `http://localhost:3000/api/send-email` with the following body:

```json
{
  "name": "John Doe",
  "email": "john.doe@example.com",
  "message": "Hello, this is a test message!"
}
```

---

### 5. Deploy to Vercel

Deploy the function to Vercel:

```bash
vercel deploy
```

Add the environment variables to your Vercel project in **Settings > Environment Variables**.

---

## Code Explanation

### `send-email.js`

This file contains the serverless function for sending emails. Here's the key structure:

```javascript
import nodemailer from "nodemailer";
import dotenv from "dotenv";

dotenv.config();

export default async function handler(req, res) {
  // CORS configuration
  res.setHeader(
    "Access-Control-Allow-Origin",
    "https://your-frontend-domain.com"
  );
  res.setHeader("Access-Control-Allow-Methods", "POST, GET, OPTIONS");
  res.setHeader("Access-Control-Allow-Headers", "Content-Type");

  if (req.method === "OPTIONS") return res.status(200).end();

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
        user: process.env.GMAIL_EMAIL,
        pass: process.env.GMAIL_APP_PASSWORD,
      },
    });

    // Send email to user
    await transporter.sendMail({
      from: `"Your Company" <${process.env.GMAIL_EMAIL}>`,
      to: email,
      subject: "Thank you for contacting us!",
      html: `
        <h3>Hi ${name},</h3>
        <p>We've received your message:</p>
        <blockquote>${message}</blockquote>
        <p>We'll respond shortly.</p>
      `,
    });

    // Send email to admin
    await transporter.sendMail({
      from: `"Your Company" <${process.env.GMAIL_EMAIL}>`,
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

---

## Testing

Use tools like Postman or curl to send test requests.

**Example POST request:**

```bash
curl -X POST https://your-vercel-deployment-url/api/send-email \
-H "Content-Type: application/json" \
-d '{
  "name": "Jane Doe",
  "email": "jane.doe@example.com",
  "message": "This is a test message!"
}'
```

---

## Troubleshooting

- **Error: `EAUTH`**: Verify the `GMAIL_EMAIL` and `GMAIL_APP_PASSWORD` in your `.env` file.
- **CORS Issues**: Ensure `Access-Control-Allow-Origin` matches your frontend domain.
- **Deployment Errors**: Check logs in Vercel's dashboard for debugging.

---

## License

This project is open source and available under the [MIT License](LICENSE).

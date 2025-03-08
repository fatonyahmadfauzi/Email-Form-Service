# FAA Form Backend with Node.js Serverless Function for Brevo on Vercel

This README provides a guide for converting a PHP backend into a Node.js serverless function to use Brevo's email-sending capabilities on Vercel. Since Vercel does not support PHP, this approach enables you to run the backend logic effectively in a serverless environment.

## Folder Structure

```
project-directory/
├── api/
│   ├── send-email.js
├── package.json
├── .env
├── .gitignore
```

### File Descriptions

- **`api/send-email.js`**: The serverless function handling the email-sending logic.
- **`package.json`**: Node.js dependencies and project configuration.
- **`.env`**: Contains environment variables for sensitive data (e.g., Brevo API key).
- **`.gitignore`**: Specifies files to exclude from version control.

## Prerequisites

- Node.js installed on your local machine.
- A Vercel account.
- A Brevo account for sending emails.

## Setup Instructions

### Step 1: Clone the Repository

```bash
git clone <your-repo-url>
cd project-directory
```

### Step 2: Install Dependencies

Ensure you have `axios` for API requests:

```bash
npm install axios dotenv
```

### Step 3: Configure Environment Variables

Create a `.env` file in the root of your project with the following variables:

```env
BREVO_API_KEY=your-brevo-api-key
RECIPIENT_EMAIL=recipient@example.com
RECIPIENT_NAME=Recipient Name
```

Replace placeholders with your actual values.

### Step 4: Create the Serverless Function

In the `api/` directory, create a file `send-email.js`:

```javascript
const axios = require("axios");
require("dotenv").config();

module.exports = async (req, res) => {
  if (req.method !== "POST") {
    return res.status(405).json({ error: "Only POST requests are allowed" });
  }

  try {
    const { name, email, message } = req.body;

    if (!name || !email || !message) {
      throw new Error("All fields are required");
    }

    const response = await axios.post(
      "https://api.brevo.com/v3/smtp/email",
      {
        sender: { name: "Contact Form", email: process.env.BREVO_API_KEY },
        to: [
          {
            email: process.env.RECIPIENT_EMAIL,
            name: process.env.RECIPIENT_NAME,
          },
        ],
        subject: "New Contact Form Submission",
        htmlContent: `<h3>New Message From ${name}</h3><p>Email: ${email}</p><p>Message:</p><p>${message}</p>`,
      },
      {
        headers: {
          "Content-Type": "application/json",
          "api-key": process.env.BREVO_API_KEY,
        },
      }
    );

    res
      .status(200)
      .json({ status: "success", message: "Email sent successfully!" });
  } catch (error) {
    console.error("Error sending email:", error);
    res.status(500).json({ status: "error", message: error.message });
  }
};
```

### Step 5: Deploy to Vercel

1. Run the following command to deploy the project:

```bash
vercel
```

2. Follow the prompts to complete the deployment.
3. After deployment, note the URL provided by Vercel (e.g., `https://your-vercel-app.vercel.app`).

### Step 6: Test the API

Send a POST request to `https://your-vercel-app.vercel.app/api/send-email` with the following JSON body:

```json
{
  "name": "John Doe",
  "email": "johndoe@example.com",
  "message": "This is a test message."
}
```

You can use tools like Postman or your frontend to send the request.

## Notes

- Ensure that your `.env` file is not pushed to version control by listing it in `.gitignore`.
- Adjust the `BREVO_API_KEY`, `RECIPIENT_EMAIL`, and `RECIPIENT_NAME` in your environment variables as needed.

## Troubleshooting

- **CORS Issues**: Vercel serverless functions automatically handle CORS. If issues arise, you can set response headers manually in the function.
- **Deployment Errors**: Verify the `vercel.json` configuration if you encounter any issues.

With this setup, you can effectively run the backend email logic using Node.js on Vercel while leveraging Brevo's email-sending API.

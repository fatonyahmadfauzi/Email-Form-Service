# FAA Form Backend with Node js Serverless Function for Email Backend Service on Vercel

This project is a backend service for handling email sending functionality using [Nodemailer](https://nodemailer.com/) and environment variables for secure configuration. It is designed to work seamlessly with Brevo or Gmail SMTP configurations. The service can be deployed on [Vercel](https://vercel.com).

## Features

- Sends acknowledgment emails to users.
- Sends notification emails to the admin.
- Cross-Origin Resource Sharing (CORS) configured.
- Environment variables for sensitive data management.

## Prerequisites

- Node.js installed (v16 or higher).
- A Brevo or Gmail account for SMTP configuration.
- A deployment platform such as Vercel.

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

3. Create a `Import.env` file in the root directory and add the following environment variables:

   **For Brevo:**

   ```env
   BREVO_SMTP_HOST=smtp-relay.brevo.com
   BREVO_SMTP_PORT=587
   BREVO_EMAIL=<your-brevo-email>
   BREVO_PASSWORD=<your-brevo-password>
   RECIPIENT_EMAIL=<admin-email>
   ```

   **For Gmail:**

   ```env
   GMAIL_USER=<your-gmail-email>
   GMAIL_PASSWORD=<your-app-password>
   RECIPIENT_EMAIL=<admin-email>
   ```

4. Update the `handler` function in the code to use Gmail or Brevo, depending on your SMTP provider.

## Usage

1. Run the development server:

   ```bash
   npm run dev
   ```

2. Send a POST request to `/api/send-email` with the following payload:

   ```json
   {
     "name": "John Doe",
     "email": "john@example.com",
     "message": "Hello! I have a question."
   }
   ```

3. Example Response:
   ```json
   {
     "status": "success",
     "message": "Emails sent successfully!"
   }
   ```

## Deployment

1. Deploy to Vercel:

   ```bash
   vercel deploy
   ```

2. Configure environment variables in Vercel:

   - Navigate to your project on the Vercel dashboard.
   - Go to **Settings > Environment Variables** and add the same variables as in your `Import.env` file.

3. Test the deployed API endpoint.

## Troubleshooting

- Ensure your `Import.env` variables are correctly set.
- For Gmail, ensure you have enabled "App Passwords" in your Google account settings.
- Check the Vercel logs for detailed error messages.

## License

This project is licensed under the MIT License. See the LICENSE file for more details.

---

For further assistance, feel free to contact the project maintainer.

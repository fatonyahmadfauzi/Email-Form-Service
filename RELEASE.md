## FAA Form Backend - Vercel Setup

This release includes the implementation of the serverless email-sending function built with **Node.js** and **Nodemailer**, allowing users to send emails via a contact form. It also includes robust error handling and environment variable configuration.

### Features:

1. **User Email Confirmation**
   Sends a confirmation email to the user who submitted the contact form, including their name and message.

2. **Admin Notification**
   Sends a notification email to the admin with details of the user's submission.

3. **Environment Variables**
   Sensitive data (e.g., Gmail credentials) is securely managed using `.env` files:

   - `GMAIL_EMAIL`: Gmail account used to send emails.
   - `GMAIL_APP_PASSWORD`: App password for Gmail.
   - `RECIPIENT_EMAIL`: Admin email to receive notifications.
   - `RECIPIENT_NAME`: Admin name to appear in emails.

4. **CORS Configuration**
   Configured to allow cross-origin requests from the specific client (`https://fatonyahmadfauzi.netlify.app`).

5. **Input Validation**
   Ensures all required fields (`name`, `email`, `message`) are provided before sending emails.

### Code Highlights:

- **Serverless Function**: Built using `nodemailer`.
- **Secure Credentials**: Managed with environment variables.
- **Custom Email Templates**: User and admin emails with `HTML` content.
- **Error Handling**: Returns appropriate status codes for errors.

---

This release lays the foundation for robust email-sending functionality. Future improvements might include:

- Adding support for attachments.
- Expanding service compatibility beyond Gmail.
- Advanced logging and monitoring.

Thank you for using this functionality!

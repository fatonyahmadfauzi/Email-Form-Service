## Email Form Service - Railway Setup

This release is the **Railway-ready version** of the Email Form Service. It contains the necessary configurations and instructions for deploying this project on Railway.

### Features

- Handles form submissions
- Validates user input
- Sends emails with PHPMailer and OAuth2

### Deployment Instructions (Railway)

1. **Clone the Repository**:

   - Switch to the `railway-setup` branch:
     ```bash
     git clone -b smtp-setup https://github.com/fatonyahmadfauzi/Email-Form-Service.git Email-Form-Service_SMTP-Railway
     cd Email-Form-Service_SMTP-Railway
     git checkout railway-setup
     ```

2. **Add to Railway**:

   - Create a new project in [Railway](https://railway.app/).
   - Connect the repository and select the `railway-setup` branch.

3. **Set Environment Variables**:
   Add the following environment variables in Railway's environment settings:
   ```plaintext
   GOOGLE_CLIENT_ID=your-google-client-id
   GOOGLE_CLIENT_SECRET=your-google-client-secret
   GOOGLE_REFRESH_TOKEN=your-google-refresh-token
   GMAIL_ACCOUNT=your-gmail-account@gmail.com
   RECIPIENT_EMAIL=recipient-email@example.com
   RECIPIENT_NAME=Recipient Name
   ```

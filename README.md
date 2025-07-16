# Email Form API - Node.js with Gmail OAuth2 for Vercel

This is a simple contact form backend built with Node.js and Gmail SMTP using OAuth2. It is designed to run as a Serverless API on [Vercel](https://vercel.com).

## ✨ Features

- Accepts POST requests with contact form data
- Sends email via Gmail using Nodemailer + OAuth2
- Deployed as serverless function on Vercel
- JSON API response for integration with frontend

## 📦 Tech Stack

- Node.js (ES Modules)
- [Nodemailer](https://nodemailer.com/)
- Gmail SMTP with OAuth2
- Vercel Serverless Function

---

## 🚀 Getting Started

### 1. Clone this Repository

```bash
git clone -b smtp-vercel https://github.com/fatonyahmadfauzi/Email-Form-Service.git Email-Form-Service_SMTP-Vercel

cd Email-Form-Service_SMTP-Vercel
```

### 2. Install Dependencies

```bash
npm install
```

### 3. Create `.env` File

```env
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REFRESH_TOKEN=your-refresh-token
GMAIL_ACCOUNT=your-gmail-account@gmail.com
RECIPIENT_EMAIL=recipient@example.com
RECIPIENT_NAME=Recipient Name
```

### 4. Deploy to Vercel

- Push to GitHub
- Import to [Vercel](https://vercel.com/)
- Add `.env` variables in Vercel > Project Settings > Environment Variables
- Deploy 🎉

---

## 📨 API Endpoint

POST `/api/handle-form`
Body JSON:

```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "message": "Hello!"
}
```

Success Response:

```json
{
  "status": "success",
  "message": "Message sent successfully!"
}
```

Error Response:

```json
{
  "status": "error",
  "message": "All fields are required"
}
```

---

## 🛠️ Troubleshooting

- Ensure Gmail API is enabled on Google Cloud
- Check OAuth credentials in Playground: [https://developers.google.com/oauthplayground]()
- For 500 errors, inspect deployment logs on Vercel

---

## 📄 License

MIT License — Free to use and modify

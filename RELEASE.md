## 🚀 Release: Vercel-Ready Email Form API (Node.js + Gmail OAuth2)

This release contains the **Node.js version** of the Email Form service designed for deployment on [Vercel](https://vercel.com).

### 🔧 Setup Highlights

- Converts original PHP version to Node.js using Nodemailer
- Sends email through Gmail with OAuth2 credentials
- Secure .env configuration with environment variables
- Minimalist RESTful API

---

### 📂 Folder Structure

```bash
📁 api
└── handle-form.js # API endpoint
📄 .env # Environment config (not committed)
📄 package.json # Node.js dependencies
📄 .vercel.json # Vercel function config (optional)
```

---

### 🌐 Deployment Instructions

1. Clone or fork this repository.
2. Add the following `.env` variables to your Vercel project:
   GOOGLE_CLIENT_ID
   GOOGLE_CLIENT_SECRET
   GOOGLE_REFRESH_TOKEN
   GMAIL_ACCOUNT
   RECIPIENT_EMAIL
   RECIPIENT_NAME
3. Push to GitHub and connect to Vercel.
4. Test the API endpoint:
   POST https://your-project.vercel.app/api/handle-form

---

### 🧪 Recommended Testing Tools

- [Postman](https://postman.com/)
- [Insomnia](https://insomnia.rest/)
- cURL or your frontend form

---

### 📌 Notes

- OAuth2 tokens are handled automatically by Nodemailer.
- Ensure your Gmail account has not blocked API access.
- Access token refresh is automatic using refresh token.

---

Happy emailing! ✉️

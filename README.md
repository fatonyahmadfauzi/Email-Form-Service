# FAA Form Backend with Brevo API

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

A contact form implementation using Brevo (Sendinblue) API. Features a PHP backend hosted on InfinityFree and a HTML/JavaScript frontend deployed on Netlify.

## Features

- 📄 **Frontend**: HTML/JavaScript hosted on Netlify
- ⚙️ **Backend**: PHP script using Brevo API hosted on InfinityFree
- 📧 **Email Handling**: Sends emails to recipient + confirmation to user
- 🔒 **CORS Configuration**: Secure cross-origin communication

## Prerequisites

- [Brevo Account](https://www.brevo.com/) (Free tier)
- [InfinityFree](https://infinityfree.net/) hosting account
- [Netlify](https://www.netlify.com/) account (or alternative static host)
- Basic understanding of PHP and JavaScript

## 🛠 Setup Guide

### Backend Setup (InfinityFree)

1. **Create PHP File**  
   Create `handle-form.php` with this structure:

   ```php
   <?php
   // Manually set environment variables (InfinityFree doesn't support .env)
   $_ENV['BREVO_API_KEY'] = 'your_brevo_api_key_here';
   $_ENV['RECIPIENT_EMAIL'] = 'recipient@example.com';
   $_ENV['RECIPIENT_NAME'] = 'Recipient Name';

   // Include dependencies
   require __DIR__ . '/vendor/autoload.php';

   // Add CORS headers
   header("Access-Control-Allow-Origin: https://your-netlify-domain.netlify.app");
   header("Access-Control-Allow-Methods: POST");
   header("Content-Type: application/json");

   // [Add your email sending logic here]
   ?>
   ```

2. **Install Dependencies**
   Run locally:

   ```bash
   composer require phpmailer/phpmailer league/oauth2-client vlucas/phpdotenv
   ```

3. **Upload Files**
   Deploy these to InfinityFree:
   - `handle-form.php`
   - `vendor/` folder
   - Other required PHP files

### Frontend Setup (Netlify)

1. **Create Contact Form** (`index.html`)

   ```html
   <form id="contactForm">
     <input type="text" name="name" placeholder="Your Name" required />
     <input type="email" name="email" placeholder="Your Email" required />
     <textarea name="message" placeholder="Message..." required></textarea>
     <button type="submit">Send Message</button>
   </form>
   ```

2. **Add JavaScript** (`script.js`)

   ```javascript
   document
     .getElementById("contactForm")
     .addEventListener("submit", async (e) => {
       e.preventDefault();

       const formData = {
         name: e.target.name.value,
         email: e.target.email.value,
         message: e.target.message.value,
       };

       try {
         const response = await fetch(
           "https://your-infinityfree-domain.infinityfreeapp.com/handle-form.php",
           {
             method: "POST",
             headers: { "Content-Type": "application/json" },
             body: JSON.stringify(formData),
           }
         );

         const result = await response.json();
         alert(
           result.status === "success"
             ? "Message sent!"
             : `Error: ${result.message}`
         );
       } catch (error) {
         alert("Failed to send message. Please try again.");
       }
     });
   ```

3. **Deploy to Netlify**
   Drag-and-drop your folder to Netlify dashboard or connect via Git.

## 📂 File Structure

```bash
├── backend/
│   ├── handle-form.php     # Main backend handler
│   └── vendor/             # PHP dependencies
│
└── frontend/
    ├── index.html          # Contact form
    └── script.js           # Form handling logic
```

## 🔍 How It Works

1. User submits form on Netlify-hosted page
2. Frontend sends POST request to PHP backend
3. Backend validates data and uses Brevo API to:
   - Send email to site owner
   - Send confirmation email to user
4. Returns JSON response to frontend

## ⚠️ Important Notes

- **InfinityFree Limitations**:
  API-based emails only (no SMTP support)
- **Brevo Limits**:
  Free plan allows 300 emails/day
- **CORS Configuration**:
  Update `Access-Control-Allow-Origin` header to match your frontend domain

## 🚨 Troubleshooting

| **Issue**        | **Solution**                               |
| ---------------- | ------------------------------------------ |
| CORS Errors      | Verify backend headers and origin URL      |
| Email Not Sent   | Check Brevo dashboard > Transactional Logs |
| 401 Unauthorized | Validate Brevo API key in PHP script       |

## License

MIT License - See [LICENSE](LICENSE) for details.

```bash
This README includes all critical information from your original request while maintaining a professional structure. It uses:
- Clear section headers
- Code blocks for key implementations
- Tables for troubleshooting
- Badges for visual hierarchy
- Emoji for quick scanning
```

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

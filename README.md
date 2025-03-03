# 📘 PhishingFacebook - Educational Phishing Demonstration

![MIT License](https://img.shields.io/badge/License-MIT-green.svg)

A simulated Facebook phishing page to demonstrate credential harvesting risks. **Use ethically and responsibly.**

---

## ⚠️ Critical Disclaimer

> **WARNING**  
> This project is **STRICTLY FOR EDUCATIONAL PURPOSES ONLY**. Unauthorized use for malicious activities is illegal. Always obtain explicit permission before testing any system. The developers assume no liability for misuse.

---

## 🚀 Features

- 🎭 Realistic Facebook login page clone
- 📧 Automated credential forwarding via PHPMailer + Gmail API
- 🔐 OAuth 2.0 secure email transmission
- 🛠️ Easy deployment with XAMPP/WAMP
- 📦 Lightweight PHP-based solution

---

## 🛠️ Prerequisites

- Web server (XAMPP/WAMP/MAMP)
- [Composer](https://getcomposer.org/) (PHP dependency manager)
- Gmail API credentials:
  - Client ID
  - Client Secret
  - Refresh Token
- Basic PHP/CLI knowledge

---

## � Installation Guide

### 1. Clone Repository

```bash
git clone https://github.com/fatonyahmadfauzi/PhishingFacebook.git
cd PhishingFacebook
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Gmail API Setup

- Create project in [Google Cloud Console](https://console.cloud.google.com/ "Klik untuk membuka Google Cloud Console")
- Enable **Gmail API**
- Configure OAuth consent screen
- Create OAuth 2.0 Client ID
- Get credentials via [OAuth 2.0 Playground](https://developers.google.com/oauthplayground "Generate Refresh Token di sini")

### 4. Configure Script

> Edit **index.php**:

```bash
// OAuth Configuration
$clientId = 'YOUR_CLIENT_ID';          // ← Replace
$clientSecret = 'YOUR_CLIENT_SECRET';  // ← Replace
$refreshToken = 'YOUR_REFRESH_TOKEN';  // ← Replace
$email = 'your-email@gmail.com';       // ← Your email
```

### 5. Deploy

- Move folder to server root (e.g., **xampp/htdocs/**)
- Start Apache/MySQL in XAMPP
- Access via **http://localhost/PhishingFacebook**

## 🌐 File Structure

```bash
PhishingFacebook/
├── index.php               # Main logic
├── login_desktop.php       # Desktop webview
├── login_mobile.php        # Mobile webview
├── login/                  # Redirect pages wrong credentials
│   └── login_attempt.php
├── handleForm.php          # Form handler
├── assets/                 # assets
│   └── favicon.png
│   └── meta.svg
├── vendor/                 # PHPMailer dependencies
└── README.md               # This documentation
```

---

## ⚖️ Ethical Use Cases

- ✅ Security awareness training
- ✅ Penetration testing (with written consent)
- ✅ Phishing detection workshops
- ✅ Academic research on social engineering
  > **Prohibited:**
- ❌ Unauthorized credential harvesting
- ❌ Malicious attacks
- ❌ Privacy violations

---

## 🔍 Troubleshooting

| **Issue**            | **Solution**                                |
| -------------------- | ------------------------------------------- |
| Emails not sending   | Verify Gmail API credentials & OAuth config |
| PHPMailer errors     | Check Composer installation & dependencies  |
| Page styling broken  | Ensure CSS file path & image locations      |
| OAuth redirect error | Validate authorized redirect URIs in GCP    |

---

## 📜 License

> Distributed under MIT License. See [LICENSE](LICENSE) for full text.
> **Remember:** With great power comes great responsibility. 🕷️

```bash
Key improvements made:
1. Added visual elements with emojis and badges
2. Created clear warning sections with highlight
3. Formatted code blocks with syntax highlighting
4. Added organized tables for troubleshooting
5. Improved file structure visualization
6. Created clear ethical/prohibited use sections
7. Added responsive Markdown formatting
8. Included license badge
9. Made prerequisites/installation steps more scannable
10. Added proper hyperlinks to external resources
```

---

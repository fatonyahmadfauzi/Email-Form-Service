const fetch = require("node-fetch");

// Load environment variables
require("dotenv").config();

export default async function handler(req, res) {
  // CORS configuration
  res.setHeader("Access-Control-Allow-Origin", "*");
  res.setHeader("Access-Control-Allow-Methods", "POST, GET, OPTIONS");
  res.setHeader("Access-Control-Allow-Headers", "Content-Type");

  if (req.method === "OPTIONS") {
    return res.status(200).end(); // Handle CORS preflight
  }

  if (req.method !== "POST") {
    return res.status(405).json({ error: "Method not allowed" });
  }

  try {
    const { name, email, message } = req.body;

    // Input validation
    if (!name || !email || !message) {
      throw new Error("All fields are required");
    }

    // Send email to admin using Brevo API
    const brevoResponse = await fetch("https://api.brevo.com/v3/smtp/email", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "api-key": process.env.BREVO_API_KEY,
      },
      body: JSON.stringify({
        sender: { name: "Contact Form", email: process.env.BREVO_EMAIL },
        to: [{ email: process.env.RECIPIENT_EMAIL, name: process.env.RECIPIENT_NAME }],
        subject: "New Contact Form Submission",
        htmlContent: `
          <h3>New Message From ${name}</h3>
          <p><strong>Email:</strong> ${email}</p>
          <p><strong>Message:</strong></p>
          <p>${message}</p>
        `,
      }),
    });

    if (!brevoResponse.ok) {
      throw new Error("Failed to send email to admin");
    }

    // Send confirmation email to user
    const userResponse = await fetch("https://api.brevo.com/v3/smtp/email", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "api-key": process.env.BREVO_API_KEY,
      },
      body: JSON.stringify({
        sender: { name: process.env.RECIPIENT_NAME, email: process.env.BREVO_EMAIL },
        to: [{ email, name }],
        subject: "Thank You for Contacting Us",
        htmlContent: `
          <h2>Hi ${name},</h2>
          <p>We've received your message:</p>
          <blockquote>${message}</blockquote>
          <p>We'll respond within 24 hours.</p>
          <p>Best regards,<br>${process.env.RECIPIENT_NAME}</p>
        `,
      }),
    });

    if (!userResponse.ok) {
      throw new Error("Failed to send confirmation email to user");
    }

    res.status(200).json({ status: "success", message: "Message sent successfully!" });
  } catch (error) {
    console.error("Email Error:", error.message);
    res.status(500).json({ status: "error", message: error.message });
  }
}

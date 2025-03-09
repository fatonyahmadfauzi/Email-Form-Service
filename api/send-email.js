import nodemailer from 'nodemailer';
import dotenv from 'dotenv';

dotenv.config();

export default async function handler(req, res) {
  res.setHeader('Access-Control-Allow-Origin', 'https://fatonyahmadfauzi.netlify.app');
  res.setHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS');
  res.setHeader('Access-Control-Allow-Headers', 'Content-Type');

  if (req.method === 'OPTIONS') {
    return res.status(200).end();
  }

  if (req.method !== 'POST') {
    return res.status(405).json({ message: 'Method not allowed' });
  }

  const { name, email, message } = req.body;

  if (!name || !email || !message) {
    return res.status(400).json({ message: 'All fields are required' });
  }

  try {
    const transporter = nodemailer.createTransport({
      service: 'Gmail',
      auth: {
        user: process.env.GMAIL_EMAIL, // Gmail email
        pass: process.env.GMAIL_APP_PASSWORD, // App Password Gmail
      },
    });

    const recipientName = process.env.RECIPIENT_NAME || "Recipient"; // Default to "Recipient" if not set

    // Kirim email ke admin
    await transporter.sendMail({
      from: `"[FAA] Contact Form" <${process.env.GMAIL_EMAIL}>`, // Disamakan dengan PHP
      to: process.env.RECIPIENT_EMAIL,
      subject: 'New Contact Form Submission!',
      html: `
        <h3>New Message From ${name}</h3>
        <p><strong>Email:</strong> ${email}</p>
        <p><strong>Message:</strong></p>
        <p>${message}</p>
      `,
    });

    // Kirim email konfirmasi ke pengguna
    await transporter.sendMail({
      from: `"[FAA] Contact Form" <${process.env.GMAIL_EMAIL}>`, // Disamakan dengan PHP
      to: email,
      subject: 'Thank You for Contacting Us!',
      html: `
        <h2>Hi ${name},</h2>
        <p>We've received your message:</p>
        <blockquote>${message}</blockquote>
        <p>We'll respond within 24 hours.</p>
        <p>Best regards,<br>${recipientName}</p>
      `,
    });

    return res.status(200).json({ status: 'success', message: 'Emails sent successfully!' });
  } catch (error) {
    console.error(error);
    return res.status(500).json({ status: 'error', message: 'Internal server error' });
  }
}

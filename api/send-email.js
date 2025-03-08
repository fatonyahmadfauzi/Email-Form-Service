import fetch from 'node-fetch'; // Gunakan import karena node-fetch mendukung ESM
import dotenv from 'dotenv';

dotenv.config();

export default async function handler(req, res) {
  res.setHeader('Access-Control-Allow-Origin', 'https://fatonyahmadfauzi.netlify.app'); // Izinkan frontend Anda
  res.setHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS');
  res.setHeader('Access-Control-Allow-Headers', 'Content-Type');

  if (req.method === 'OPTIONS') {
    return res.status(200).end(); // Respon untuk preflight request
  }

  if (req.method !== 'POST') {
    return res.status(405).json({ message: 'Method not allowed' });
  }

  const { name, email, message } = req.body;

  if (!name || !email || !message) {
    return res.status(400).json({ message: 'All fields are required' });
  }

  try {
    // Kirim email ke pengirim (user)
    const userResponse = await fetch('https://api.brevo.com/v3/smtp/email', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'api-key': process.env.BREVO_API_KEY,
      },
      body: JSON.stringify({
        sender: { name: "Fatony Contact Form", email: process.env.BREVO_EMAIL },
        to: [{ email }], // Kirim ke email pengirim
        subject: 'Thank you for contacting us!',
        htmlContent: `
          <h3>Hi ${name},</h3>
          <p>We've received your message:</p>
          <blockquote>${message}</blockquote>
          <p>We'll respond shortly.</p>
        `,
      }),
    });

    if (!userResponse.ok) {
      throw new Error('Failed to send email to user');
    }

    // Kirim email ke admin
    const adminResponse = await fetch('https://api.brevo.com/v3/smtp/email', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'api-key': process.env.BREVO_API_KEY,
      },
      body: JSON.stringify({
        sender: { name: "Fatony Contact Form", email: process.env.BREVO_EMAIL },
        to: [
          {
            email: process.env.RECIPIENT_EMAIL,
            name: process.env.RECIPIENT_NAME,
          },
        ], // Kirim ke admin
        subject: 'New Contact Form Submission',
        htmlContent: `
          <h3>New Message From ${name}</h3>
          <p><strong>Email:</strong> ${email}</p>
          <p><strong>Message:</strong></p>
          <p>${message}</p>
        `,
      }),
    });

    if (!adminResponse.ok) {
      throw new Error('Failed to send email to admin');
    }

    return res.status(200).json({ status: 'success', message: 'Emails sent successfully!' });
  } catch (error) {
    console.error(error);
    return res.status(500).json({ status: 'error', message: 'Internal server error' });
  }
}

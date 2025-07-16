import nodemailer from 'nodemailer';

export default async function handler(req, res) {
  // --- MULAI PENAMBAHAN KODE CORS ---
  // Izinkan permintaan dari domain frontend Anda
  res.setHeader('Access-Control-Allow-Origin', 'https://fatonyahmadfauzi.me', 'https://fatonyahmadfauzi.netlify.app');
  // Izinkan metode request yang diperlukan
  res.setHeader('Access-Control-Allow-Methods', 'POST, OPTIONS');
  // Izinkan header yang diperlukan
  res.setHeader('Access-Control-Allow-Headers', 'Content-Type');

  // Tangani permintaan preflight OPTIONS dari peramban
  if (req.method === 'OPTIONS') {
    return res.status(200).end();
  }
  // --- SELESAI PENAMBAHAN KODE CORS ---

  if (req.method !== 'POST') {
    return res.status(405).json({ status: 'error', message: 'Method Not Allowed' });
  }

  const { name, email, message } = req.body || {};

  if (!name || !email || !message) {
    return res.status(400).json({ status: 'error', message: 'All fields are required' });
  }

  try {
    const transporter = nodemailer.createTransport({
      service: 'gmail',
      auth: {
        type: 'OAuth2',
        user: process.env.GMAIL_ACCOUNT,
        clientId: process.env.GOOGLE_CLIENT_ID,
        clientSecret: process.env.GOOGLE_CLIENT_SECRET,
        refreshToken: process.env.GOOGLE_REFRESH_TOKEN,
      },
    });

    await transporter.sendMail({
      from: `"${name}" <${email}>`,
      to: process.env.RECIPIENT_EMAIL,
      subject: `New Contact Form Submission from ${name}`,
      text: message,
    });

    return res.status(200).json({ status: 'success', message: 'Message sent successfully!' });
  } catch (error) {
    console.error(error); // Tambahkan ini untuk debugging di log Vercel
    return res.status(500).json({ status: 'error', message: 'Failed to send message', error: error.message });
  }
}
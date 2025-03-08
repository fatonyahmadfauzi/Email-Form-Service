const nodemailer = require('nodemailer');

const transporter = nodemailer.createTransport({
  service: 'Gmail', // Menggunakan Gmail sebagai penyedia SMTP
  auth: {
    user: 'yasintaziawan@gmail.com', // Ganti dengan email Gmail pengirim Anda
    pass: 'tdax fajk lozz pjje', // Masukkan App Password dari Gmail
  },
});

async function sendTestEmail() {
  try {
    const info = await transporter.sendMail({
      from: '"Your Name" <yasintaziawan@gmail.com>', // Email pengirim
      to: 'fatonyahmadfauzi@gmail.com', // Email tujuan
      subject: 'Test Email from Nodemailer and Gmail',
      text: 'This is a test email sent using Nodemailer with Gmail SMTP.',
      html: '<p>This is a <strong>test email</strong> sent using Nodemailer with Gmail SMTP.</p>',
    });

    console.log('Email sent:', info.response);
  } catch (error) {
    console.error('Error sending email:', error);
  }
}

sendTestEmail();

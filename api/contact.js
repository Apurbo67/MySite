// api/contact.js
export default function handler(req, res) {
    if (req.method === 'POST') {
      // Handle the POST request
      res.status(200).json({ message: 'Message sent successfully' });
    } else {
      res.status(405).json({ message: 'Method Not Allowed' });
    }
  }
  
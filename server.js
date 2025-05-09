// const express = require("express");
// const nodemailer = require("nodemailer");
// const cors = require("cors");
// const bodyParser = require("body-parser");

// const app = express();
// const port = 3000;

// // Middleware
// app.use(cors());
// app.use(bodyParser.urlencoded({ extended: false }));
// app.use(bodyParser.json());

// // Route to handle form submission
// app.post("/send", async (req, res) => {
//     const { name, email, phone, subject, message } = req.body;

//     try {
//         let transporter = nodemailer.createTransport({
//             service: "gmail",
//             auth: {
//                 user: "mprs2508@gmail.com",
//                 pass: "rbhh otml rmlr dnlv"
//             }
//         });

//         const mailOptions = {
//             from: 'yourwebsite@example.com',
//             to: 'youremail@example.com',
//             subject: 'New Inquiry',
//             text: `Name: ${name}\nEmail: ${email}\nMessage:\n${message}`,
//             replyTo: email
//           };
          

//         await transporter.sendMail(mailOptions);
//         res.status(200).json({ message: "Email sent successfully!" });
//     } catch (error) {
//         console.error(error);
//         res.status(500).send("Something went wrong.");
//     }
// });

// app.listen(port, () => {
//     console.log(`Server running on http://localhost:${port}`);
// });


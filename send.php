<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// If using Composer autoload
require 'vendor/autoload.php';

// If manually included
// require 'PHPMailer/src/Exception.php';
// require 'PHPMailer/src/PHPMailer.php';
// require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'mprs2508@gmail.com'; // your email
        $mail->Password = 'rbhh otml rmlr dnlv'; // app password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('yourwebsite@example.com', 'Website Inquiry');
        $mail->addAddress('youremail@example.com'); // where the email is sent
        $mail->addReplyTo($email, $name);

        // Content
        $mail->isHTML(false);
        $mail->Subject = 'New Inquiry';
        $mail->Body = "Name: $name\nEmail: $email\nPhone: $phone\nSubject: $subject\n\nMessage:\n$message";

        $mail->send();
        echo json_encode(['message' => 'Email sent successfully!']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Mailer Error: ' . $mail->ErrorInfo]);
    }
}
?>

<?php

// Load Composer's autoloader
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
// Set up PHPMailer
$mail = new PHPMailer\PHPMailer\PHPMailer();
$mail->isSMTP();
$mail->Host = $_ENV['MAIL_HOST'];
$mail->SMTPAuth = true;
$mail->Username = $_ENV['mail_username'];
$mail->Password = $_ENV['MAIL_PASSWORD'];
$mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];
$mail->Port = $_ENV['MAIL_PORT'];

// Set email parameters
$mail->setFrom($_ENV['mail_username'], $_ENV['MAIL_FROM_NAME']);
$mail->addAddress('darpankhatri817@gmail.com');  // Replace with the recipient's email address
$mail->Subject = 'Test Email';
$mail->Body = 'This is a test email sent using PHP and Gmail.';

// Send the email
if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message sent!';
}

?>

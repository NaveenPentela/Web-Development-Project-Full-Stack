<?php
header('Content-Type: application/json');

$response = array();

if (isset($_POST["email"])) {
    require('config.php');
    $getEmail = mysqli_real_escape_string($con, $_POST["email"]);
    $query = "SELECT * FROM `users` WHERE email='$getEmail'";
    $result = mysqli_query($con, $query) or die();
    $rows = mysqli_num_rows($result);

    if ($rows == 1) {
        $token = bin2hex(random_bytes(32));
        $query = "UPDATE users SET token='$token' WHERE email='$getEmail'";
        $result = mysqli_query($con, $query) or die();

        if ($result) {
            require 'vendor/autoload.php';
            $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
            $dotenv->load();

            $mail = new PHPMailer\PHPMailer\PHPMailer();
            $mail->isSMTP();
            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['mail_username'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];
            $mail->Port = $_ENV['MAIL_PORT'];
            
            $mail->setFrom($_ENV['mail_username'], $_ENV['MAIL_FROM_NAME']);
            $mail->addAddress($getEmail);
            $mail->Subject = 'Forget Password Request';
            $mail->Body = "Click the following link to reset your password: http://localhost/gtlm/reset-password.php?email=$getEmail&token=$token";

            if (!$mail->send()) {
                $response['status'] = 0;
                $response['message'] = 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                $response['status'] = 1;
                $response['message'] = 'Email Sent Successfully! Check Email';
            }
        } else {
            $response['status'] = 0;
            $response['message'] = 'Something went wrong!';
        }
    } else {
        $response['status'] = 0;
        $response['message'] = 'Email not found!';
    }
} else {
    $response['status'] = 0;
    $response['message'] = 'Invalid request.';
}

echo json_encode($response);
exit;
?>

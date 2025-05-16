<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

session_start();
require_once realpath(__DIR__ . '/../vendor/autoload.php');

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

require 'connection.php';

function logoutSession()
{
    session_unset();
    session_destroy();
    header('Location: ../login.php');
    exit;
}

function sendMail($email, $subject, $body)
{
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USERNAME'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV['MAIL_PORT'];

        //Recipients
        $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
        $mail->addAddress($email);

        //Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        return $mail->send();
    } catch (Exception $e) {
        $_SESSION['message'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

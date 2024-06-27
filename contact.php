<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'assets/vendor/phpmail/src/Exception.php';
require 'assets/vendor/phpmail/src/PHPMailer.php';
require 'assets/vendor/phpmail/src/SMTP.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $response['message'] = 'Por favor, completa todos los campos.';
        echo json_encode($response);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Por favor, introduce un email válido.';
        echo json_encode($response);
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'asodehuila24@gmail.com';
        $mail->Password   = 'tuqay nmgw lltl ydal'; // Considera usar variables de entorno para esto
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('asodehuila24@gmail.com', 'ASODEHUILA');
        $mail->addReplyTo($email, $name);
        $mail->addAddress('decimo23@hotmail.com');

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = "
            <h2>Nuevo mensaje de contacto</h2>
            <p><strong>Nombre:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Asunto:</strong> $subject</p>
            <p><strong>Mensaje:</strong><br>$message</p>
        ";
        $mail->AltBody = "Nuevo mensaje de contacto\n\nNombre: $name\nEmail: $email\nAsunto: $subject\n\nMensaje:\n$message";

        $mail->send();
        $response['success'] = true;
        $response['message'] = 'Mensaje enviado con éxito.';
    } catch (Exception $e) {
        $response['message'] = "El mensaje no pudo ser enviado. Error: {$mail->ErrorInfo}";
    }
} else {
    $response['message'] = 'Método de solicitud no válido.';
}

echo json_encode($response);
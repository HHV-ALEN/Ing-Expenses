<?php

require '../../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$email = $_POST['email'];

## Verificar si el correo existe en la base de datos
require_once '../../config/db.php';

$sql = "SELECT * FROM usuarios WHERE correo = '$email'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    header('Location: ../../../index.php');
} else {



    // Importación de clases de PHPMailer


    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 0; //Enable verbose debug output
        $mail->isSMTP(); //Send using SMTP
        $mail->Host = 'smtp.office365.com'; //Set the SMTP server to send through

        $mail->SMTPAuth = true; //Enable SMTP authentication  
        $mail->Username = 'alenapp@alenintelligent.com'; //SMTP username
        $mail->Password = 'A1enM4IL.'; //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port = 587; //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        // Configurar el correo para el gerente
        $mail->setFrom('alenstore@alenintelligent.com', 'Solicitud de Viaticos');
        $mail->addAddress($email, 'RECUPERACIÓN DE CONTRASEÑA');
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Recuperación de contraseña';
        $mail->Body = '
        <p>Hola:</p>

        <p>Recibimos una solicitud para restablecer tu contraseña. Si no solicitaste esto, puedes ignorar este correo.</p>


        <p><a href="https://ingenieria.alenexpenses.com//reset-password.php?correo=' . $email . '">Ir al Sistema de Viáticos</a></p>
       
        
        <p>Saludos cordiales,</p>
        <p>El equipo de ALEN</p>';

        $mail->AltBody = 'Recibimos una solicitud para restablecer tu contraseña. Si no solicitaste esto, puedes ignorar este correo.';

        // Enviar el correo al gerente
        $mail->send();

        header('Location: ../../../../../index.php');

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

}

?>
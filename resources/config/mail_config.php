<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require ('../../../vendor/autoload.php');

function configureMailer() {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp-mail.outlook.com'; // Servidor SMTP de Hotmail/Outlook
        $mail->SMTPAuth   = true;
        $mail->Username   = 'betohurtado3@hotmail.com'; // Tu correo de Hotmail
        $mail->Password   = 'ltyvvutbykqdlefy'; // Tu contraseña de Hotmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Encriptación TLS
        $mail->Port       = 587; // Puerto TCP para conexión

        // Configuración por defecto del remitente (puede ser sobreescrito)
        $mail->setFrom('b.hurtado1998@gmail.com', 'Beto');

        // Habilitar depuración
        $mail->SMTPDebug = 2; // Opciones de depuración: 1 = errores y mensajes, 2 = solo mensajes

    } catch (Exception $e) {
        echo "La configuración del correo falló: {$mail->ErrorInfo}";
    }

    return $mail;
}

// Configurar y enviar correo
$mail = configureMailer();

try {
    $mail->addAddress('hhurtado@alenintelligent.com', 'Heriberto');
    $mail->isHTML(true);
    $mail->Subject = 'Nuevo viático registrado';
    $mail->Body    = 'Hola,<br><br>Se ha registrado un nuevo viático.';
    $mail->AltBody = 'Hola, Se ha registrado un nuevo viático.';

    $mail->send();
    echo 'El mensaje ha sido enviado';
} catch (Exception $e) {
    echo "El mensaje no pudo ser enviado. Mailer Error: {$mail->ErrorInfo}";
}
?>

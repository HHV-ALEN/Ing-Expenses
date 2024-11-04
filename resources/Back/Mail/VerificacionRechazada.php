<?php
include ('../../config/db.php');
require '../../../vendor/autoload.php';

$id_user = $_GET['id_viatico'];
$id_gerente = $_GET['id_gerente'];
$id_viatico = $_GET['id_viatico'];

/// Obtener el ultimo registro de la tabla de viaticos

$UltimoRegistro = "SELECT * FROM viaticos ORDER BY Id DESC LIMIT 1";
$UltimoRegistroQuery = $conn->query($UltimoRegistro);
// Pasar a Variables
if ($UltimoRegistroQuery->num_rows > 0) {
    $row = $UltimoRegistroQuery->fetch_assoc();
    $Id = $row['Id'];
    $Fecha_Salida = $row['Fecha_Salida'];
    $Fecha_Regreso = $row['Fecha_Regreso'];
    $Hora_Salida = $row['Hora_Salida'];
    $Hora_Regreso = $row['Hora_Regreso'];
    $Id_Usuario = $row['Id_Usuario'];
    $Id_Gerente = $row['Id_Gerente'];
    $Cliente = $row['Cliente'];
    $Motivo = $row['Motivo'];
    $Destino = $row['Destino'];
    $Total = $row['Total'];
}

// Obtener Información del usuario
$UsuarioQuery = "SELECT * FROM usuarios WHERE Id = $Id_Usuario";
$UsuarioQueryResult = $conn->query($UsuarioQuery);
if ($UsuarioQueryResult->num_rows > 0) {
    $row = $UsuarioQueryResult->fetch_assoc();
    $NombreUsuario = $row['Nombre'];
    $CorreoUsuario = $row['Correo'];
}

// Obtener Información del gerente
$GerenteQuery = "SELECT * FROM usuarios WHERE Id = $Id_Gerente";
$GerenteQueryResult = $conn->query($GerenteQuery);
if ($GerenteQueryResult->num_rows > 0) {
    $row = $GerenteQueryResult->fetch_assoc();
    $NombreGerente = $row['Nombre'];
    $CorreoGerente = $row['Correo'];
}

// Importación de clases de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

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
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->addAddress($CorreoUsuario, $NombreUsuario);
    $mail->Subject = '🚫 Evidencias Rechazadas de tu Solicitud de Viáticos, ' . $NombreUsuario;
    $mail->Body = '
    <div style="font-family: Arial, sans-serif; color: #333;">
        <h2 style="color: #e63946;">🚫 Evidencias Rechazadas</h2>
        
        <p>Estimado/a <strong>' . $NombreUsuario . '</strong>,</p>
    
        <p>Al revisar tu solicitud de viáticos, se encontró que algunas evidencias no son válidas y fueron eliminadas del sistema. Te pedimos que subas las correctas.</p>
        
        <hr style="border: 1px solid #f1faee;">
        <p><strong>Detalles de la solicitud:</strong></p>
        <p>
            🆔 <strong>Solicitud:</strong> ' . $Id . '<br>
            📅 <strong>Salida:</strong> ' . $Fecha_Salida . ' a las ' . $Hora_Salida . '<br>
            📅 <strong>Regreso:</strong> ' . $Fecha_Regreso . ' a las ' . $Hora_Regreso . '<br>
            👤 <strong>Cliente:</strong> ' . $Cliente . '<br>
            📝 <strong>Motivo:</strong> ' . $Motivo . '<br>
            📍 <strong>Destino:</strong> ' . $Destino . '<br>
            💰 <strong>Monto Solicitado:</strong> ' . $Total . '
        </p>
        <hr style="border: 1px solid #f1faee;">
    
        <p>📌 Para más detalles o seguimiento, accede al sistema: <a href="https://ingenieria.alenexpenses.com/" style="color: #1d3557;">Ir al Sistema de Viáticos</a></p>
    
        <p>Saludos,</p>
        <p><em>El equipo de ALEN</em></p>
    </div>';
    
    $mail->AltBody = 'Evidencias Rechazadas de tu Solicitud de Viáticos:
    - Solicitud: ' . $Id . '
    - Salida: ' . $Fecha_Salida . ' a las ' . $Hora_Salida . '
    - Regreso: ' . $Fecha_Regreso . ' a las ' . $Hora_Regreso . '
    - Cliente: ' . $Cliente . '
    - Motivo: ' . $Motivo . '
    - Destino: ' . $Destino . '
    - Monto Total Solicitado: ' . $Total . '
    Algunas evidencias fueron rechazadas. Sube las correctas en el plazo establecido.
    Accede al sistema para más detalles: https://ingenieria.alenexpenses.com/';

    
    // Enviar el correo al empleado
    $mail->send();
    echo 'Message has been sent';

    $sql_return = "UPDATE viaticos SET Estado = 'Prorroga' WHERE Id = $id_viatico";
    $conn->query($sql_return);

    header('Location: ../../../../src/Users/index.php');


} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

?>
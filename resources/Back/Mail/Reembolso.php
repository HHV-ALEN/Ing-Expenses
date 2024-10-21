<?php
include ('../../config/db.php');
require '../../../vendor/autoload.php';

$id_user = $_GET['id_usuario'];
$id_gerente = $_GET['id_gerente'];
$id_viatico = $_GET['id_viatico'];
$id_reembolso = $_GET['id_reembolso'];
$Id_ViaticoInt = (int) $id_viatico;

/// Obtener el ultimo registro de la tabla de viaticos

$Reembolso_Query = "SELECT * FROM reembolso ORDER BY Id DESC LIMIT 1";
$Reembolso_QueryResult = $conn->query($Reembolso_Query);
// Pasar a Variables
if ($Reembolso_QueryResult->num_rows > 0) {
    $row = $Reembolso_QueryResult->fetch_assoc();
    $Id = $row['Id'];
    $Monto = $row['Monto'];
    $Descripcion = $row['Descripcion'];
    $Concepto = $row['Concepto'];
}

// Obtener Información del usuario
$UsuarioQuery = "SELECT * FROM usuarios WHERE Id = $id_user ";
$UsuarioQueryResult = $conn->query($UsuarioQuery);
if ($UsuarioQueryResult->num_rows > 0) {
    $row = $UsuarioQueryResult->fetch_assoc();
    $NombreUsuario = $row['Nombre'];
    $CorreoUsuario = $row['Correo'];
}

// Obtener Información del gerente
$GerenteQuery = "SELECT * FROM usuarios WHERE Id = $id_gerente";
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
    $mail->addAddress($CorreoGerente, $NombreGerente);
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'Solicitud de Reembolso de ' . $NombreUsuario;
    $mail->Body = '
        <p>Estimado/a ' . $NombreGerente . ',</p>

        <p>El empleado ' . $NombreUsuario . ' ha solicitado el Reembolso No. ' . $id_reembolso . ' .</p>
        
        <hr>
        <p><strong>Información de la solicitud de reembolso:</strong></p>
        <p>
            <strong>Monto:</strong> ' . $Monto . '<br>
            <strong>Descripción:</strong> ' . $Descripcion . '<br>
            <strong>Concepto:</strong> ' . $Concepto . '<br>
        </p>
        <hr>
    
    
    <p>Para más detalles y seguimiento de la solicitud, acceda al aplicativo a través del siguiente enlace:</p>
    
    <p><a href="https://www.alenexpenses.com/">Ir al Sistema de Viáticos</a></p>
    
    <p>Saludos cordiales,</p>
    <p>El equipo de ALEN</p>';

    $mail->AltBody = 'El empleado ' . $NombreUsuario . ' ha solicitado un reembolso por los siguientes gastos realizados durante su viaje:
    <strong>Monto:</strong> ' . $Monto . '<br>
            <strong>Descripción:</strong> ' . $Descripcion . '<br>
            <strong>Concepto:</strong> ' . $Concepto . '<br>
    Para más detalles y seguimiento de la solicitud, acceda al aplicativo.';

    // Enviar el correo al gerente
    $mail->send();


    // Reiniciar las propiedades del correo para el próximo envío
    $mail->clearAddresses();
    $mail->clearAttachments();

    // Configurar el correo para el empleado
    $mail->addAddress($CorreoUsuario, $NombreUsuario);
    $mail->Subject = 'Tú Solicitud de Reembolso de ';
    $mail->Body = '
        <p>Estimado/a ' . $NombreUsuario . 'Se ha registrado el reembolso con No. ' . $id_reembolso . ' .</p>

        <p>Se ha registrado la solicitud de reembolso</p>
        <hr>
        <p><strong>Información de la solicitud de reembolso:</strong></p>
        <p>
            <strong>Monto:</strong> ' . $Monto . '<br>
            <strong>Descripción:</strong> ' . $Descripcion . '<br>
            <strong>Concepto:</strong> ' . $Concepto . '<br>
        </p>
        <hr>
    
    
    <p>Para más detalles y seguimiento de la solicitud, acceda al aplicativo a través del siguiente enlace:</p>
    
    <p><a href="https://www.alenexpenses.com/">Ir al Sistema de Viáticos</a></p>
    
    <p>Saludos cordiales,</p>
    <p>El equipo de ALEN</p>';

    $mail->AltBody = '' . $NombreUsuario . ' ha solicitado el reembolso con No. ' . $id_reembolso . ' .</p>
    <strong>Monto:</strong> ' . $Monto . '<br>
            <strong>Descripción:</strong> ' . $Descripcion . '<br>
            <strong>Concepto:</strong> ' . $Concepto . '<br>
    Para más detalles y seguimiento de la solicitud, acceda al aplicativo.';
    // Enviar el correo al empleado
    $mail->send();
    echo 'Message has been sent';
    
    
        header('Location: ../../../../../src/Control/detallesReembolso.php?id_reembolso=' . $id_reembolso);

} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
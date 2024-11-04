<?php 

// Importación de clases de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '../../../vendor/autoload.php';

session_start();
include('../../config/db.php');

$Id_Reembolso = $_GET['Id'];
$Nombre_Solicitante = $_SESSION['Name'];


$query = "SELECT 
            u1.Correo AS CorreoSolicitante, 
            u2.Nombre AS NombreGerente, 
            u2.Correo AS CorreoGerente 
          FROM usuarios u1 
          LEFT JOIN usuarios u2 ON u1.Gerente = u2.Nombre 
          WHERE u1.Nombre = '$Nombre_Solicitante'";

$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $CorreoSolicitante = $row['CorreoSolicitante'];
    $NombreGerente = $row['NombreGerente'];
    $CorreoGerente = $row['CorreoGerente'];

    echo "Correo del solicitante: $CorreoSolicitante <br>";
    echo "Nombre del gerente: $NombreGerente <br>";
    echo "Correo del gerente: $CorreoGerente <br>";
} else {
    echo "Error en la consulta: " . mysqli_error($conn);
}

/// Obtener información del viatico 
$ReembolsoQuery = "SELECT * FROM reembolsos WHERE Id = $Id_Reembolso";
$ReembolsoQueryResult = $conn->query($ReembolsoQuery);
if ($ReembolsoQueryResult->num_rows > 0) {
    $row = $ReembolsoQueryResult->fetch_assoc();
    $Solicitante = $row['Solicitante'];
    $Concepto = $row['Concepto'];
    $Monto = $row['Monto'];
    $Destino = $row['Destino'];
    $Fecha = $row['Fecha'];
    $Descripcion = $row['Descripcion'];
    $Estado = $row['Estado'];
}


echo "Información del reembolso: <br>";
echo "Solicitante: $Solicitante <br>";
echo "Concepto: $Concepto <br>";
echo "Monto: $Monto <br>";
echo "Destino: $Destino <br>";
echo "Fecha: $Fecha <br>";
echo "Descripcion: $Descripcion <br>";
echo "Estado: $Estado <br>";


$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = 0; //Enable verbose debug output
    $mail->isSMTP(); //Send using SMTP
    $mail->Host = 'smtp.office365.com'; //Set the SMTP server to send through

    $mail->SMTPAuth = true; //Enable SMTP authentication  
    $mail->Username = "alenapp@alenintelligent.com"; //SMTP username
    $mail->Password = "A1enM4IL."; //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port = 587; //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    // Configurar la codificación del correo
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    // Configurar el correo para el gerente
    $mail->setFrom('alenstore@alenintelligent.com', 'Solicitud de Viaticos');
    $mail->addAddress($CorreoSolicitante, $Nombre_Solicitante);
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    // Configurar el correo para el empleado

    $mail->Subject = '💸 Solicitud de Reembolso Registrada';
$mail->Body = '
    <div style="font-family: Arial, sans-serif; color: #333;">
        <p>Estimado/a <strong>' . $Nombre_Solicitante . '</strong>,</p>

        <p>✅ Tu solicitud de reembolso ha sido registrada exitosamente con la siguiente información:</p>

        <div style="border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; padding: 10px; margin: 10px 0;">
            <p style="margin: 5px 0;">📝 <strong>Concepto:</strong> ' . $Concepto . '</p>
            <p style="margin: 5px 0;">💵 <strong>Monto:</strong> $' . $Monto . '</p>
            <p style="margin: 5px 0;">📍 <strong>Destino:</strong> ' . $Destino . '</p>
            <p style="margin: 5px 0;">📅 <strong>Fecha:</strong> ' . $Fecha . '</p>
            <p style="margin: 5px 0;">🖊️ <strong>Descripción:</strong> ' . $Descripcion . '</p>
            <p style="margin: 5px 0;">📋 <strong>Estado:</strong> ' . $Estado . '</p>
        </div>

        <p>👤 El gerente <strong>' . $NombreGerente . '</strong> ha sido notificado para su aprobación.</p>
        
        <p>🔗 Para más detalles y seguimiento de tu solicitud, accede al aplicativo a través del siguiente enlace:</p>
        
        <p style="text-align: center; margin-top: 20px;">
            <a href="https://ingenieria.alenexpenses.com/" style="display: inline-block; background-color: #007bff; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px;">🚀 Ir al Sistema de Viáticos</a>
        </p>
        
        <p>Saludos cordiales,</p>
        <p>🤝 El equipo de ALEN</p>
    </div>';

$mail->AltBody = 'Nueva Solicitud de Reembolso de ' . $Nombre_Solicitante . ':
Concepto: ' . $Concepto . '
Monto: $' . $Monto . '
Destino: ' . $Destino . '
Fecha: ' . $Fecha . '
Descripción: ' . $Descripcion . '
Estado: ' . $Estado . '
El gerente ' . $NombreGerente . ' ha sido notificado para su aprobación. Para más detalles y seguimiento de tu solicitud, accede al aplicativo.';

    // Enviar el correo al empleado
    $mail->send();
    echo 'Message has been sent';
    header('Location: /src/Reembolsos/ReembolsoAnidado.php?id=' . $Id_Reembolso);

    
    
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

?>
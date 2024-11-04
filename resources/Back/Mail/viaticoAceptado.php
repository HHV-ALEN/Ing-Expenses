<?php
include '../../config/db.php';
session_start();

$Id_Viatico = $_GET['Id'];

echo "VIATICO REVISION <br> Id_Viatico: " . $Id_Viatico;


// Importación de clases de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '../../../vendor/autoload.php';



/// Obtener información del viatico 
$ViaticoQuery = "SELECT * FROM viaticos WHERE Id = $Id_Viatico";
$ViaticoQueryResult = $conn->query($ViaticoQuery);
if ($ViaticoQueryResult->num_rows > 0) {
    $row = $ViaticoQueryResult->fetch_assoc();
    $Orden_Venta = $row['Orden_Venta'];
    $Codigo = $row['Codigo'];
    $Nombre_Proyecto = $row['Nombre_Proyecto'];
    $Destino = $row['Destino'];
    $Total = $row['Total'];
    $Fecha_Salida = $row['Fecha_Salida'];
    $Hora_Salida = $row['Hora_Salida'];
    $Fecha_Regreso = $row['Fecha_Regreso'];
    $Hora_Regreso = $row['Hora_Regreso'];
    $Orden_Venta = $row['Orden_Venta'];
    $Codigo = $row['Codigo'];
    $Nombre_Proyecto = $row['Nombre_Proyecto'];
    $Destino = $row['Destino'];
    $Total = $row['Total'];
    $Solicitante = $row['Solicitante'];
}

$Nombre_Solicitante = $Solicitante;

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

    echo "<br>Correo del solicitante: $CorreoSolicitante <br>";
    echo "Nombre del gerente: $NombreGerente <br>";
    echo "Correo del gerente: $CorreoGerente <br>";
} else {
    echo "Error en la consulta: " . mysqli_error($conn);
}



// Fecha de hoy
$Fecha = date('Y-m-d');
$Nombre_Solicitante = $_SESSION['Name'];

/// Imprimir datos
echo "<br>Id del viático: $Id_Viatico<br>";
echo "Fecha de salida: $Fecha_Salida<br>";
echo "Hora de salida: $Hora_Salida<br>";
echo "Fecha de regreso: $Fecha_Regreso<br>";
echo "Hora de regreso: $Hora_Regreso<br>";
echo "Orden de venta: $Orden_Venta<br>";
echo "Código: $Codigo<br>";
echo "Nombre del proyecto: $Nombre_Proyecto<br>";
echo "Destino: $Destino<br>";
echo "Total: $Total<br>";


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
    
    $mail->addAddress($CorreoGerente, $NombreGerente);
    $mail->addAddress($CorreoSolicitante, $Nombre_Solicitante);
    $mail->addAddress('pagos@alenintelligent.com', 'Encargado de Pagos');
    $mail->addAddress('ovalverde@alenintelligent.com', 'Oscar Valverde');
    $mail->addAddress('mmedina@alenintelligent.com', 'Maria Medina');
    $mail->addAddress('gdeleon@alenintelligent.com', 'Gerardo De Leon');
    $mail->addAddress('steal45130@gmail.com', 'Steal (Prueba)');

    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = '(PRUEBA.............) ✅ El Viático de ' . $Nombre_Solicitante . ' ha sido Aceptado';
    $mail->Body = '
    <div style="font-family: Arial, sans-serif; color: #333;">
        <h2 style="text-align: center; color: #4CAF50;">🎉 Solicitud de Viáticos Aceptada</h2>
        <p>Estimado/a <strong>' . $NombreGerente . '</strong>,</p>

        <p>La solicitud de viáticos de <strong>' . $Nombre_Solicitante . '</strong> ha sido <strong style="color: #4CAF50;">aceptada</strong> con la siguiente información:</p>
        
        <hr style="border: 1px solid #ddd; margin: 20px 0;">

        <table style="width: 100%; border-collapse: collapse;">
            <tr><td>🗓️ <strong>Fecha de Salida:</strong></td><td>' . $Fecha_Salida . '</td></tr>
            <tr><td>⏰ <strong>Hora de Salida:</strong></td><td>' . $Hora_Salida . '</td></tr>
            <tr><td>🗓️ <strong>Fecha de Regreso:</strong></td><td>' . $Fecha_Regreso . '</td></tr>
            <tr><td>⏰ <strong>Hora de Regreso:</strong></td><td>' . $Hora_Regreso . '</td></tr>
            <tr><td>📄 <strong>Orden de Venta:</strong></td><td>' . $Orden_Venta . '</td></tr>
            <tr><td>🔑 <strong>Código:</strong></td><td>' . $Codigo . '</td></tr>
            <tr><td>📍 <strong>Destino:</strong></td><td>' . $Destino . '</td></tr>
            <tr><td>💰 <strong>Monto Total Solicitado:</strong></td><td>' . $Total . '</td></tr>
        </table>

        <hr style="border: 1px solid #ddd; margin: 20px 0;">

        <p>🔗 <a href="https://ingenieria.alenexpenses.com/" style="color: #007bff; text-decoration: none;">Ir al Sistema de Viáticos</a></p>

        <p>Saludos cordiales,</p>
        <p><em>El equipo de ALEN</em></p>
    </div>';

    $mail->AltBody = '
    El viático de ' . $Nombre_Solicitante . ' ha sido aceptado con la siguiente información:
    Fecha de Salida: ' . $Fecha_Salida . '
    Hora de Salida: ' . $Hora_Salida . '
    Fecha de Regreso: ' . $Fecha_Regreso . '
    Hora de Regreso: ' . $Hora_Regreso . '
    Orden de Venta: ' . $Orden_Venta . '
    Código: ' . $Codigo . '
    Destino: ' . $Destino . '
    Monto Total Solicitado: ' . $Total . '
    Para más detalles y seguimiento, accede a: https://ingenieria.alenexpenses.com/';


    // Enviar el correo al gerente
    $mail->send();

    // Reiniciar las propiedades del correo para el próximo envío
    $mail->clearAddresses();
    $mail->clearAttachments();

    // Configurar el correo para el Solicitante
    $mail->addAddress($CorreoSolicitante, $Nombre_Solicitante);
    $mail->Subject = 'Tu Solicitud de Viáticos ha sido Aceptada';
    $mail->Body = '
    <div style="font-family: Arial, sans-serif; color: #333;">
        <h2 style="text-align: center; color: #4CAF50;">🎉 Solicitud de Viáticos Aceptada</h2>
        <p>Estimado/a <strong>' . $NombreGerente . '</strong>,</p>

        <p>La solicitud de viáticos de <strong>' . $Nombre_Solicitante . '</strong> ha sido <strong style="color: #4CAF50;">aceptada</strong> con la siguiente información:</p>
        
        <hr style="border: 1px solid #ddd; margin: 20px 0;">

        <table style="width: 100%; border-collapse: collapse;">
            <tr><td>🗓️ <strong>Fecha de Salida:</strong></td><td>' . $Fecha_Salida . '</td></tr>
            <tr><td>⏰ <strong>Hora de Salida:</strong></td><td>' . $Hora_Salida . '</td></tr>
            <tr><td>🗓️ <strong>Fecha de Regreso:</strong></td><td>' . $Fecha_Regreso . '</td></tr>
            <tr><td>⏰ <strong>Hora de Regreso:</strong></td><td>' . $Hora_Regreso . '</td></tr>
            <tr><td>📄 <strong>Orden de Venta:</strong></td><td>' . $Orden_Venta . '</td></tr>
            <tr><td>🔑 <strong>Código:</strong></td><td>' . $Codigo . '</td></tr>
            <tr><td>📍 <strong>Destino:</strong></td><td>' . $Destino . '</td></tr>
            <tr><td>💰 <strong>Monto Total Solicitado:</strong></td><td>' . $Total . '</td></tr>
        </table>

        <hr style="border: 1px solid #ddd; margin: 20px 0;">

        <p>🔗 <a href="https://ingenieria.alenexpenses.com/" style="color: #007bff; text-decoration: none;">Ir al Sistema de Viáticos</a></p>

        <p>Saludos cordiales,</p>
        <p><em>El equipo de ALEN</em></p>
    </div>';

    $mail->AltBody = '
    
    Tu solicitud de viáticos ha sido aceptada con la siguiente información:
    Fecha de Salida: ' . $Fecha_Salida . '
    Hora de Salida: ' . $Hora_Salida . '
    Fecha de Regreso: ' . $Fecha_Regreso . '
    Hora de Regreso: ' . $Hora_Regreso . '
    Orden de Venta: ' . $Orden_Venta . '
    Código: ' . $Codigo . '
    Destino: ' . $Destino .
        'Monto Total Solicitado: ' . $Total . '
    ';

    // Enviar el correo al empleado
    $mail->send();
    echo 'Message has been sent';


    $mail->clearAddresses();
    $mail->clearAttachments();

    // Configurar el correo para el encargado de Pagos
    $mail->addAddress('pagos@alenintelligent.com', 'Encargado de Pagos');
    $mail->Subject = 'Viático Aceptado';
    $mail->Body = '
    <div style="font-family: Arial, sans-serif; color: #333;">
        <h2 style="text-align: center; color: #4CAF50;">🎉 Solicitud de Viáticos Aceptada</h2>
        <p>Estimado/a <strong>' . $NombreGerente . '</strong>,</p>

        <p>La solicitud de viáticos de <strong>' . $Nombre_Solicitante . '</strong> ha sido <strong style="color: #4CAF50;">aceptada</strong> con la siguiente información:</p>
        
        <hr style="border: 1px solid #ddd; margin: 20px 0;">

        <table style="width: 100%; border-collapse: collapse;">
            <tr><td>🗓️ <strong>Fecha de Salida:</strong></td><td>' . $Fecha_Salida . '</td></tr>
            <tr><td>⏰ <strong>Hora de Salida:</strong></td><td>' . $Hora_Salida . '</td></tr>
            <tr><td>🗓️ <strong>Fecha de Regreso:</strong></td><td>' . $Fecha_Regreso . '</td></tr>
            <tr><td>⏰ <strong>Hora de Regreso:</strong></td><td>' . $Hora_Regreso . '</td></tr>
            <tr><td>📄 <strong>Orden de Venta:</strong></td><td>' . $Orden_Venta . '</td></tr>
            <tr><td>🔑 <strong>Código:</strong></td><td>' . $Codigo . '</td></tr>
            <tr><td>📍 <strong>Destino:</strong></td><td>' . $Destino . '</td></tr>
            <tr><td>💰 <strong>Monto Total Solicitado:</strong></td><td>' . $Total . '</td></tr>
        </table>

        <hr style="border: 1px solid #ddd; margin: 20px 0;">

        <p>🔗 <a href="https://ingenieria.alenexpenses.com/" style="color: #007bff; text-decoration: none;">Ir al Sistema de Viáticos</a></p>

        <p>Saludos cordiales,</p>
        <p><em>El equipo de ALEN</em></p>
    </div>'; 

    $mail->AltBody = '
    
    Tu solicitud de viáticos ha sido aceptada con la siguiente información:
    Fecha de Salida: ' . $Fecha_Salida . '
    Hora de Salida: ' . $Hora_Salida . '
    Fecha de Regreso: ' . $Fecha_Regreso . '
    Hora de Regreso: ' . $Hora_Regreso . '
    Orden de Venta: ' . $Orden_Venta . '
    Código: ' . $Codigo . '
    Destino: ' . $Destino .
        'Monto Total Solicitado: ' . $Total . '
    ';
   
   
    //header('Location: ../../../../../src/dashboard.php'); 

} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
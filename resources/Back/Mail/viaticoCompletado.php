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
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'El Viático de ' . $Nombre_Solicitante . ' ha sido Completado';
    $mail->Body = '
    <p>Estimado/a ' . $NombreGerente . ',</p>

    <p>El viático de ' . $Nombre_Solicitante . ' ha sido Completado:</p>
    <hr>
    <p>
        <strong>Fecha de Salida:</strong> ' . $Fecha_Salida . '<br>
        <strong>Hora de Salida:</strong> ' . $Hora_Salida . '<br>
        <strong>Fecha de Regreso:</strong> ' . $Fecha_Regreso . '<br>
        <strong>Hora de Regreso:</strong> ' . $Hora_Regreso . '<br>
        <strong>Orden De Venta:</strong> ' . $Orden_Venta . '<br>
        <strong>Codigo:</strong> ' . $Codigo . '<br>
        <strong>Destino:</strong> ' . $Destino . '<br>
        <strong>Monto Total Solicitado:</strong> ' . $Total . '<br>
    </p>
    <hr>

    <p>Para más detalles y seguimiento de la solicitud, accede al aplicativo a través del siguiente enlace:</p>

    <p><a href="https://www.alenexpenses.com/">Ir al Sistema de Viáticos</a></p>

    <p>Saludos cordiales,</p>
    <p>El equipo de ALEN</p>';

    $mail->AltBody = '
    
    El viático de ' . $Nombre_Solicitante . ' ha sido Completado con la siguiente información:
    Fecha de Salida: ' . $Fecha_Salida . '
    Hora de Salida: ' . $Hora_Salida . '
    Fecha de Regreso: ' . $Fecha_Regreso . '
    Hora de Regreso: ' . $Hora_Regreso . '
    Orden de Venta: ' . $Orden_Venta . '
    Código: ' . $Codigo . '
    Destino: ' . $Destino . '
    ';
    
    // Enviar el correo al gerente
    $mail->send();

    // Reiniciar las propiedades del correo para el próximo envío
    $mail->clearAddresses();
    $mail->clearAttachments();

    // Configurar el correo para el empleado
    $mail->addAddress($CorreoSolicitante, $Nombre_Solicitante);
    $mail->Subject = 'Tu Solicitud de Viáticos ha sido Completado';
    $mail->Body = '
    
    <p>Estimado/a ' . $Nombre_Solicitante . ',</p>

    <p>Tu solicitud de viáticos ha sido Completado con la siguiente información:</p>
    <hr>
    <p>
        <strong>Fecha de Salida:</strong> ' . $Fecha_Salida . '<br>
        <strong>Hora de Salida:</strong> ' . $Hora_Salida . '<br>
        <strong>Fecha de Regreso:</strong> ' . $Fecha_Regreso . '<br>
        <strong>Hora de Regreso:</strong> ' . $Hora_Regreso . '<br>
        <strong>Orden De Venta:</strong> ' . $Orden_Venta . '<br>
        <strong>Codigo:</strong> ' . $Codigo . '<br>
        <strong>Destino:</strong> ' . $Destino . '<br>
        <strong>Monto Total Solicitado:</strong> ' . $Total . '<br>
    </p>
    <hr>

    <p>Para más detalles y seguimiento de la solicitud, accede al aplicativo a través del siguiente enlace:</p>

    <p><a href="https://www.alenexpenses.com/">Ir al Sistema de Viáticos</a></p>

    <p>Saludos cordiales,</p>
    <p>El equipo de ALEN</p>

    ';

    $mail->AltBody = '
    
    Tu solicitud de viáticos ha sido Completado con la siguiente información:
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
    header('Location: /src/Viaticos/detalles.php?id=' . $Id_Viatico);
    
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}


?>
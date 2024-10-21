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

// Fecha de hoy
$Fecha = date('Y-m-d');
$Nombre_Solicitante = $_SESSION['Name'];

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
    $mail->addAddress($CorreoGerente, $NombreGerente); 
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'Solicitud de reembolso de ' . $Nombre_Solicitante .' Aprobada';
    $mail->Body = '
    <p>Estimado ' . $NombreGerente . ',</p>

    <p>La solicitud de reembolso de ' . $Nombre_Solicitante . ' ha sido aprobada con la siguiente información:</p>
    <hr>
    <p>
        <strong>Solicitante:</strong> ' . $Solicitante . '<br>
        <strong>Concepto:</strong> ' . $Concepto . '<br>
        <strong>Monto:</strong> ' . $Monto . '<br>
        <strong>Destino:</strong> ' . $Destino . '<br>
        <strong>Fecha:</strong> ' . $Fecha . '<br>
        <strong>Descripción:</strong> ' . $Descripcion . '<br>
        <strong>Estado:</strong> ' . $Estado . '<br>
    <hr>

    <p>Por favor, revise el sistema para proceder con el procesoo.</p>
    
    <p>Para más detalles y seguimiento de la solicitud, acceda al aplicativo a través del siguiente enlace:</p>
    
    <p><a href="https://www.alenexpenses.com/">Ir al Sistema de Viáticos Ingenieria</a></p>
    
    <p>Saludos cordiales,</p>
    <p>El equipo de ALEN</p>';

    $mail->AltBody = '
    Solicitud de reembolso de ' . $Nombre_Solicitante . ' Aprobada:
    <strong>Solicitante:</strong> ' . $Solicitante . '<br>
        <strong>Concepto:</strong> ' . $Concepto . '<br>
        <strong>Monto:</strong> ' . $Monto . '<br>
        <strong>Destino:</strong> ' . $Destino . '<br>
        <strong>Fecha:</strong> ' . $Fecha . '<br>
        <strong>Descripción:</strong> ' . $Descripcion . '<br>
        <strong>Estado:</strong> ' . $Estado . '<br>
    Por favor, revise el sistema para proceder con su aprobación. Para más detalles y seguimiento de la solicitud, acceda al aplicativo.';



    // Enviar el correo al gerente
    $mail->send();

    // Reiniciar las propiedades del correo para el próximo envío
    $mail->clearAddresses();
    $mail->clearAttachments();

    // Configurar el correo para el empleado
    $mail->addAddress($CorreoSolicitante, $Nombre_Solicitante);
    $mail->Subject = 'Solicitud de Reembolso Registrada';
    $mail->Body = '
    <p>Estimado/a ' . $Nombre_Solicitante . ',</p>

    <p>Tu solicitud de reembolso ha sido registrada exitosamente con la siguiente información:</p>

    <hr>
<p>
        <strong>Solicitante:</strong> ' . $Solicitante . '<br>
        <strong>Concepto:</strong> ' . $Concepto . '<br>
        <strong>Monto:</strong> ' . $Monto . '<br>
        <strong>Destino:</strong> ' . $Destino . '<br>
        <strong>Fecha:</strong> ' . $Fecha . '<br>
        <strong>Descripción:</strong> ' . $Descripcion . '<br>
        <strong>Estado:</strong> ' . $Estado . '<br>
    <hr>


    <p>El gerente ' . $NombreGerente . ' ha sido notificado para su aprobación.</p>
    
    <p>Para más detalles y seguimiento de tu solicitud, accede al aplicativo a través del siguiente enlace:</p>
    
    <p><a href="https://www.alenexpenses.com/">Ir al Sistema de Viáticos</a></p>
    
    <p>Saludos cordiales,</p>
    <p>El equipo de ALEN</p>';

    $mail->AltBody = '
    <p>
        <strong>Solicitante:</strong> ' . $Solicitante . '<br>
        <strong>Concepto:</strong> ' . $Concepto . '<br>
        <strong>Monto:</strong> ' . $Monto . '<br>
        <strong>Destino:</strong> ' . $Destino . '<br>
        <strong>Fecha:</strong> ' . $Fecha . '<br>
        <strong>Descripción:</strong> ' . $Descripcion . '<br>
        <strong>Estado:</strong> ' . $Estado . '<br>
    <hr>
    ';

    // Enviar el correo al empleado
    $mail->send();
    echo 'Message has been sent';
    ///header("Location: /src/Reembolsos/ReembolsoAceptado.php?Id=$Id_Reembolso");
    
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

?>

<?php
include ('../../config/db.php');
require '../../../vendor/autoload.php';

$id_user = $_POST['id_usuario'];
$id_gerente = $_POST['id_gerente'];

/// Obtener el ultimo registro de la tabla de viaticos

$UltimoRegistro = "SELECT * FROM viaticos ORDER BY Id DESC LIMIT 1";
$UltimoRegistroQuery = $conn->query($UltimoRegistro);
// Pasar a Variables
if ($UltimoRegistroQuery->num_rows > 0) {
    $row = $UltimoRegistroQuery->fetch_assoc();
    $IdUltimoRegistro = $row['Id'];
    $Nombre = $row['Nombre'];
    $Fecha_SalidaUltimoRegistro = $row['Fecha_Salida'];
    $Fecha_RegresoUltimoRegistro = $row['Fecha_Regreso'];
    $Hora_SalidaUltimoRegistro = $row['Hora_Salida'];
    $Hora_RegresoUltimoRegistro = $row['Hora_Regreso'];
    $Id_Usuario = $row['Id_Usuario'];
    $Id_Gerente = $row['Id_Gerente'];
    $ClienteUltimoRegistro = $row['Cliente'];
    $MotivoUltimoRegistro = $row['Motivo'];
    $Destino = $row['Destino'];
    $TotalViaticos = $row['Total'];
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

echo "<br>----------------------------------<br>";
echo "Nombre Usuario: $NombreUsuario <br>";
echo "Correo Usuario: $CorreoUsuario <br>";
echo "<br>----------------------------------<br>";
echo "Nombre Gerente: $NombreGerente <br>";
echo "Correo Gerente: $CorreoGerente <br>";
echo "<br>----------------------------------<br>";


/// Obtener información de los archivos de la tabla solicitudes
$SolicitudesQuery = "SELECT * FROM solicitudes WHERE Id_Viatico = $IdUltimoRegistro";
$SolicitudesQueryResult = $conn->query($SolicitudesQuery);
if ($SolicitudesQueryResult->num_rows > 0) {
    $row = $SolicitudesQueryResult->fetch_assoc();
    $FileName = $row['Nombre'];
}

$path = '../../../uploads/Files/' . $FileName;

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
    $mail->Subject = 'Nueva Solicitud de Viático de ' . $NombreUsuario;
    $mail->AddAttachment($path, $FileName);
    $mail->Body = '
    <p>Estimado/a ' . $NombreGerente . ',</p>

    <p>Se ha registrado una nueva solicitud de ' . $NombreUsuario . ' para viáticos con la siguiente información:</p>
    
    <hr>
    <p>
        <strong>Fecha de Salida:</strong> ' . $Fecha_SalidaUltimoRegistro . '<br>
        <strong>Hora de Salida:</strong> ' . $Hora_SalidaUltimoRegistro . '<br>
        <strong>Fecha de Regreso:</strong> ' . $Fecha_RegresoUltimoRegistro . '<br>
        <strong>Hora de Regreso:</strong> ' . $Hora_RegresoUltimoRegistro . '<br>
        <strong>Cliente:</strong> ' . $ClienteUltimoRegistro . '<br>
        <strong>Motivo:</strong> ' . $MotivoUltimoRegistro . '<br>
        <strong>Destino:</strong> ' . $Destino . '<br>
        <strong>Monto Total Solicitado:</strong> ' . $TotalViaticos . '<br>
    </p>
    <hr>

    <p>Por favor, revise el sistema para proceder con su aprobación.</p>
    
    <p>Para más detalles y seguimiento de la solicitud, acceda al aplicativo a través del siguiente enlace:</p>
    
    <p><a href="https://ingenieria.alenexpenses.com/">Ir al Sistema de Viáticos</a></p>
    
    <p>Saludos cordiales,</p>
    <p>El equipo de ALEN</p>';

    $mail->AltBody = 'Nueva Solicitud de Viático de ' . $NombreUsuario . ': 
    Fecha de Salida: ' . $Fecha_SalidaUltimoRegistro . '
    Hora de Salida: ' . $Hora_SalidaUltimoRegistro . '
    Fecha de Regreso: ' . $Fecha_RegresoUltimoRegistro . '
    Hora de Regreso: ' . $Hora_RegresoUltimoRegistro . '
    Cliente: ' . $ClienteUltimoRegistro . '
    Motivo: ' . $MotivoUltimoRegistro . '
    Destino: ' . $Destino . '
    Monto Total Solicitado: ' . $TotalViaticos . '
    Por favor, revise el sistema para proceder con su aprobación. Para más detalles y seguimiento de la solicitud, acceda al aplicativo.';

    // Enviar el correo al gerente
    $mail->send();

    // Reiniciar las propiedades del correo para el próximo envío
    $mail->clearAddresses();
    $mail->clearAttachments();

    // Configurar el correo para el empleado
    $mail->addAddress($CorreoUsuario, $NombreUsuario);
    $mail->addAddress($CorreoUsuario, $NombreUsuario);
    $mail->Subject = 'Confirmación de Solicitud de Viático Registrada';
    $mail->AddAttachment($path, $FileName);
    $mail->Body = '
    <p>Estimado/a ' . $NombreUsuario . ',</p>

    <p>Tu solicitud de viáticos ha sido registrada exitosamente con la siguiente información:</p>
    
    <hr>
    <p>
        <strong>Fecha de Salida:</strong> ' . $Fecha_SalidaUltimoRegistro . '<br>
        <strong>Hora de Salida:</strong> ' . $Hora_SalidaUltimoRegistro . '<br>
        <strong>Fecha de Regreso:</strong> ' . $Fecha_RegresoUltimoRegistro . '<br>
        <strong>Hora de Regreso:</strong> ' . $Hora_RegresoUltimoRegistro . '<br>
        <strong>Cliente:</strong> ' . $ClienteUltimoRegistro . '<br>
        <strong>Motivo:</strong> ' . $MotivoUltimoRegistro . '<br>
        <strong>Destino:</strong> ' . $Destino . '<br>
        <strong>Monto Total Solicitado:</strong> ' . $TotalViaticos . '<br>
    </p>
    <hr>

    <p>El gerente ' . $NombreGerente . ' ha sido notificado para su aprobación.</p>
    
    <p>Para más detalles y seguimiento de tu solicitud, accede al aplicativo a través del siguiente enlace:</p>
    
    <p><a href="https://ingenieria.alenexpenses.com/">Ir al Sistema de Viáticos</a></p>
    
    <p>Saludos cordiales,</p>
    <p>El equipo de ALEN</p>';

    $mail->AltBody = 'Tu solicitud de viáticos ha sido registrada exitosamente: 
        Fecha de Salida: ' . $Fecha_SalidaUltimoRegistro . '
        Hora de Salida: ' . $Hora_SalidaUltimoRegistro . '
        Fecha de Regreso: ' . $Fecha_RegresoUltimoRegistro . '
        Hora de Regreso: ' . $Hora_RegresoUltimoRegistro . '
        Cliente: ' . $ClienteUltimoRegistro . '
        Motivo: ' . $MotivoUltimoRegistro . '
        Destino: ' . $Destino . '
        Monto Total Solicitado: ' . $TotalViaticos . '
        El gerente ' . $NombreGerente . ' ha sido notificado para su aprobación. Para más detalles y seguimiento de tu solicitud, accede al aplicativo.';

    // Enviar el correo al empleado
    $mail->send();
    echo 'Message has been sent';
    header('Location: ../../../../src/Users/index.php');
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
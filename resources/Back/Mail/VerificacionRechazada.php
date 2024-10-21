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
    $mail->addAddress($CorreoGerente, $NombreGerente);
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'Evidencias Rechazadas de la Solicitud de ' . $NombreUsuario;
    $mail->Body = '
    <p>Estimado/a ' . $NombreGerente . ',</p>

    <p>Se ha realizado una revisión de las evidencias subidas por ' . $NombreUsuario . ' para la solicitud de viáticos y se ha determinado que algunas no son correctas.</p>
    
    <hr>
    <p><strong>Información de la solicitud:</strong></p>
    <p>
        <strong>Número de Solicitud:</strong> ' . $Id . '<br>
        <strong>Fecha de Salida:</strong> ' . $Fecha_Salida . '<br>
        <strong>Hora de Salida:</strong> ' . $Hora_Salida . '<br>
        <strong>Fecha de Regreso:</strong> ' . $Fecha_Regreso . '<br>
        <strong>Hora de Regreso:</strong> ' . $Hora_Regreso . '<br>
        <strong>Cliente:</strong> ' . $Cliente . '<br>
        <strong>Motivo:</strong> ' . $Motivo . '<br>
        <strong>Destino:</strong> ' . $Destino . '<br>
        <strong>Monto Total Solicitado:</strong> ' . $Total . '<br>
    </p>
    <hr>
    
    
    <p>Se requiere que ' . $NombreUsuario . ' vuelva a subir las evidencias correctas dentro del plazo establecido.</p>
    
    <p>Para más detalles y seguimiento de la solicitud, acceda al aplicativo a través del siguiente enlace:</p>
    
    <p><a href="https://www.alenexpenses.com/">Ir al Sistema de Viáticos</a></p>
    
    <p>Saludos cordiales,</p>
    <p>El equipo de ALEN</p>';

    $mail->AltBody = 'Se ha realizado una revisión de las evidencias subidas por ' . $NombreUsuario . ' para la solicitud de viáticos y se ha determinado que algunas no son correctas:
        Número de Solicitud: ' . $Id . '
        Fecha de Salida: ' . $Fecha_Salida . '
        Hora de Salida: ' . $Hora_Salida . '
        Fecha de Regreso: ' . $Fecha_Regreso . '
        Hora de Regreso: ' . $Hora_Regreso . '
        Cliente: ' . $Cliente . '
        Motivo: ' . $Motivo . '
        Destino: ' . $Destino . '
        Monto Total Solicitado: ' . $Total . '

        Se requiere que ' . $NombreUsuario . ' vuelva a subir las evidencias correctas dentro del plazo establecido.
        Para más detalles y seguimiento de la solicitud, acceda al aplicativo.';
    // Enviar el correo al gerente
    $mail->send();


    // Reiniciar las propiedades del correo para el próximo envío
    $mail->clearAddresses();
    $mail->clearAttachments();


    $mail->addAddress($CorreoUsuario, $NombreUsuario);
    $mail->Subject = 'Evidencias Rechazadas de tu Solicitud de Viáticos, ' . $NombreUsuario;
    $mail->Body = '
        <p>Estimado/a ' . $NombreUsuario . ',</p>

        <p>Tras una revisión de las evidencias subidas para tu solicitud de viáticos, se ha determinado que algunas no son correctas y han sido eliminadas del sistema.</p>
        
        <hr>
        <p><strong>Información de la solicitud:</strong></p>
        <p>
            <strong>Número de Solicitud:</strong> ' . $Id . '<br>
            <strong>Fecha de Salida:</strong> ' . $Fecha_Salida . '<br>
            <strong>Hora de Salida:</strong> ' . $Hora_Salida . '<br>
            <strong>Fecha de Regreso:</strong> ' . $Fecha_Regreso . '<br>
            <strong>Hora de Regreso:</strong> ' . $Hora_Regreso . '<br>
            <strong>Cliente:</strong> ' . $Cliente . '<br>
            <strong>Motivo:</strong> ' . $Motivo . '<br>
            <strong>Destino:</strong> ' . $Destino . '<br>
            <strong>Monto Total Solicitado:</strong> ' . $Total . '<br>
        </p>
        <hr>
        
        <p>Por favor, vuelve a subir las evidencias correctas dentro del plazo establecido para que podamos continuar con la verificación de tu solicitud.</p>
        
        <p>Para más detalles y seguimiento de tu solicitud, accede al aplicativo a través del siguiente enlace:</p>
        
        <p><a href="https://www.alenexpenses.com/">Ir al Sistema de Viáticos</a></p>
        
        <p>Saludos cordiales,</p>
        <p>El equipo de ALEN</p>';

    $mail->AltBody = 'Tras una revisión de las evidencias subidas para tu solicitud de viáticos, se ha determinado que algunas no son correctas y han sido eliminadas del sistema:
    Número de Solicitud: ' . $Id . '
    Fecha de Salida: ' . $Fecha_Salida . '
    Hora de Salida: ' . $Hora_Salida . '
    Fecha de Regreso: ' . $Fecha_Regreso . '
    Hora de Regreso: ' . $Hora_Regreso . '
    Cliente: ' . $Cliente . '
    Motivo: ' . $Motivo . '
    Destino: ' . $Destino . '
    Monto Total Solicitado: ' . $Total . '
    Las siguientes evidencias han sido rechazadas y eliminadas del sistema:
    Por favor, vuelve a subir las evidencias correctas dentro del plazo establecido para que podamos continuar con la verificación de tu solicitud.
    Para más detalles y seguimiento de tu solicitud, accede al aplicativo.';
    // Enviar el correo al empleado
    $mail->send();
    echo 'Message has been sent';

    $sql_return = "UPDATE viaticos SET Estado = 'Verificacion' WHERE Id = $id_viatico";
    $conn->query($sql_return);

    header('Location: ../../../../src/Users/index.php');


} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

?>
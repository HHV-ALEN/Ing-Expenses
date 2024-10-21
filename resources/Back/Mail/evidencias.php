<?php
include ('../../config/db.php');
require '../../../vendor/autoload.php';
session_start();

$id_viatico = $_GET['id_viatico'];
$id_usuario = $_GET['id_usuario'];
$id_gerente = $_GET['id_gerente'];
echo "Id viatico: $id_viatico <br>";
echo "Id usuario: $id_usuario <br>";
echo "Id gerente: $id_gerente <br>";

//Obtener información del registro de viaticos
$ViaticoQuery = "SELECT * FROM viaticos WHERE Id = $id_viatico";
$ViaticoQueryResult = $conn->query($ViaticoQuery);
if ($ViaticoQueryResult->num_rows > 0) {
    $row = $ViaticoQueryResult->fetch_assoc();
    $Id = $row['Id'];
    $Fecha_Salida = $row['Fecha_Salida'];
    $Fecha_Regreso = $row['Fecha_Regreso'];
    $Hora_Salida = $row['Hora_Salida'];
    $Hora_Regreso = $row['Hora_Regreso'];
    $Cliente = $row['Cliente'];
    $Motivo = $row['Motivo'];
    $Destino = $row['Destino'];
    $Total = $row['Total'];
}

//Obtener información del usuario
$UsuarioQuery = "SELECT * FROM usuarios WHERE Id = $id_usuario";
$UsuarioQueryResult = $conn->query($UsuarioQuery);
if ($UsuarioQueryResult->num_rows > 0) {
    $row = $UsuarioQueryResult->fetch_assoc();
    $NombreUsuario = $row['Nombre'];
    $CorreoUsuario = $row['Correo'];
}

//Obtener información del gerente
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
    $mail->Subject = 'Evidencias subidas por ' . $NombreUsuario;
    $mail->Body = '
        <p>Estimado/a ' . $NombreGerente . ',</p>

        <p>Se han registrado las evidencias de la solicitud de viáticos de ' . $NombreUsuario . '.</p>
        
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
        
        <p>Las evidencias subidas incluyen gastos por concepto de:</p>
        <ul>
            <li>Hospedaje</li>
            <li>Gasolina</li>
            <li>Casetas</li>
            <li>Alimentos</li>
            <li>Vuelos</li>
        </ul>
        
        <p>Actualmente, estamos en espera de que el usuario de Control verifique los archivos. Una vez completada la verificación, se te notificará sobre el estado final de la solicitud.</p>
        
        <p>Para más detalles y seguimiento de la solicitud, acceda al aplicativo a través del siguiente enlace:</p>
        
        <p><a href="https://www.alenexpenses.com/">Ir al Sistema de Viáticos</a></p>
        
        <p>Saludos cordiales,</p>
        <p>El equipo de ALEN</p>';

    $mail->AltBody = 'Se han registrado las evidencias de la solicitud de viáticos de ' . $NombreUsuario . ':
    Número de Solicitud: ' . $Id . '
    Fecha de Salida: ' . $Fecha_Salida . '
    Hora de Salida: ' . $Hora_Salida . '
    Fecha de Regreso: ' . $Fecha_Regreso . '
    Hora de Regreso: ' . $Hora_Regreso . '
    Cliente: ' . $Cliente . '
    Motivo: ' . $Motivo . '
    Destino: ' . $Destino . '
    Monto Total Solicitado: ' . $Total . '
    Las evidencias subidas incluyen gastos por concepto de: Hospedaje, Gasolina, Casetas, Alimentos.
    Estamos en espera de que el usuario de Control verifique los archivos. Para más detalles y seguimiento de la solicitud, acceda al aplicativo.';

    // Enviar el correo al gerente
    $mail->send();


    // Reiniciar las propiedades del correo para el próximo envío
    $mail->clearAddresses();
    $mail->clearAttachments();

    // Configurar el correo para el empleado
    $mail->addAddress($CorreoUsuario, $NombreUsuario);
    $mail->addAddress($CorreoUsuario, $NombreUsuario);
    $mail->Subject = 'Gracias por subir tus evidencias, ' . $NombreUsuario;
    $mail->Body = '
        <p>Estimado/a ' . $NombreUsuario . ',</p>

        <p>Se han registrado las evidencias que subiste a la plataforma.</p>
        
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
        
        <p>Las evidencias subidas incluyen gastos por concepto de:</p>
        <ul>
            <li>Hospedaje</li>
            <li>Gasolina</li>
            <li>Casetas</li>
            <li>Alimentos</li>
            <li>Vuelos</li>
        </ul>
        
        <p>Los datos de tu solicitud están en proceso de revisión por el usuario de Control. Te notificaremos si se requiere alguna corrección o si se ha aprobado la verificación.</p>
        
        <p>Para más detalles y seguimiento de tu solicitud, accede al aplicativo a través del siguiente enlace:</p>
        
        <p><a href="https://www.alenexpenses.com/">Ir al Sistema de Viáticos</a></p>
        
        <p>Saludos cordiales,</p>
        <p>El equipo de ALEN</p>';

    $mail->AltBody = 'Se han registrado las evidencias que subiste a la plataforma:
    Número de Solicitud: ' . $Id . '
    Fecha de Salida: ' . $Fecha_Salida . '
    Hora de Salida: ' . $Hora_Salida . '
    Fecha de Regreso: ' . $Fecha_Regreso . '
    Hora de Regreso: ' . $Hora_Regreso . '
    Cliente: ' . $Cliente . '
    Motivo: ' . $Motivo . '
    Destino: ' . $Destino . '
    Monto Total Solicitado: ' . $Total . '
    Las evidencias subidas incluyen gastos por concepto de: Hospedaje, Gasolina, Casetas, Alimentos.
    Los datos de tu solicitud están en proceso de revisión por el usuario de Control. Te notificaremos si se requiere alguna corrección o si se ha aprobado la verificación. Para más detalles y seguimiento de tu solicitud, accede al aplicativo.';

    // Enviar el correo al empleado
    $mail->send();


    header('Location: ../../../../src/Users/index.php');


} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
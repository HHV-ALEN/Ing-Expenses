<?php
include ('../../config/db.php');
require '../../../vendor/autoload.php';

$id_user = $_GET['id_usuario'];
$id_gerente = $_GET['id_gerente'];
$id_viatico = $_GET['id_viatico'];
$Puesto = $_SESSION['Puesto'];

echo "Id usuario: $id_user <br>";
echo "Id gerente: $id_gerente <br>";
echo "Id viatico: $id_viatico <br>";

//Obtener información del registro de viaticos
$ViaticoQuery = "SELECT * FROM viaticos WHERE Id = $id_viatico";
$ViaticoQueryResult = $conn->query($ViaticoQuery);
if ($ViaticoQueryResult->num_rows > 0) {
    $row = $ViaticoQueryResult->fetch_assoc();
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
$UsuarioQuery = "SELECT * FROM usuarios WHERE Id = $id_user";
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
    $mail->addAddress('ftostado@alenintelligent.com', 'Fabiola Tostado');
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'La Solicitud de ' . $NombreUsuario . ' ha sido aceptada';
    $mail->Body = '

    <p>Buenas noticias: El gerente de ' . $NombreUsuario . ' ha aceptado la solcitud.</p>
    
    <hr>
    <p><strong>Información de la solicitud:</strong></p>
    <p>
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
    
    <p>Recuerda avisar al personal para que suban sus comprobantes de gastos una vez finalizado el viaje.</p>
    
    <p>Para más detalles y seguimiento de la solicitud, acceda al aplicativo a través del siguiente enlace:</p>
    
    <p><a href="https://ingenieria.alenexpenses.com/">Ir al Sistema de Viáticos</a></p>
    
    <p>Saludos cordiales,</p>
    <p>El equipo de ALEN</p>';

    $mail->AltBody = 'La solicitud de viáticos de ' . $NombreUsuario . ' ha sido aceptada por el gerente.: 
    Fecha de Salida: ' . $Fecha_Salida . '
    Hora de Salida: ' . $Hora_Salida . '
    Fecha de Regreso: ' . $Fecha_Regreso . '
    Hora de Regreso: ' . $Hora_Regreso . '
    Cliente: ' . $Cliente . '
    Motivo: ' . $Motivo . '
    Destino: ' . $Destino . '
    Monto Total Solicitado: ' . $Total . '
    Recuerda avisar al personal para que suban sus comprobantes de gastos. Para más detalles y seguimiento de la solicitud, acceda al aplicativo.';

    // Enviar el correo al gerente
    $mail->send();



    header('Location: ../../../../src/Users/index.php');

} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
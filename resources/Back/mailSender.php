<?php
session_start();
require ('../config/db.php');
$Nombre = $_GET['Nombre'];
$Fecha_Salida = $_GET['Fecha_Salida'];
$Gerente = $_GET['Gerente'];
$source = $_GET['source'];
$MailGerente = $_GET['MailGerente'];
$Id_ViaticoInt = $_GET['id_viatico'];
$id_usuario = $_GET['id_usuario']; 
$id_gerente = $_GET['id_gerente'];

echo "Id Usuario: " . $id_usuario . "<br>";
echo "Id Gerente: " . $id_gerente . "<br>";


// Obtener correo del Solicitante

$Solicitante_Query = "SELECT * FROM usuarios WHERE Nombre = '$Nombre'";
$Solicitante_Result = mysqli_query($conn, $Solicitante_Query);
$Solicitante_Row = mysqli_fetch_assoc($Solicitante_Result);
$CorreoSolicitante = $Solicitante_Row['Correo'];

// --------------- Obtener información previo a enviar el correo ------------------

/// Obtener el nombre del usuario 
$NombreUsuario_Query = "SELECT * FROM usuarios WHERE Id = $id_usuario";
$NombreUsuario_Result = mysqli_query($conn, $NombreUsuario_Query);
$NombreUsuario_Row = mysqli_fetch_assoc($NombreUsuario_Result);
$Nombre = $NombreUsuario_Row['Nombre'];
$CorreoSolicitante = $NombreUsuario_Row['Correo'];
echo "Correo del solicitante: " . $CorreoSolicitante . "<br>";

/// Obtener el nombre del gerente
$NombreGerente_Query = "SELECT * FROM gerente WHERE Id = $id_gerente";
$NombreGerente_Result = mysqli_query($conn, $NombreGerente_Query);
$NombreGerente_Row = mysqli_fetch_assoc($NombreGerente_Result);
$Gerente = $NombreGerente_Row['Nombre'];
$MailGerente = $NombreGerente_Row['Correo'];
echo "Correo del gerente: " . $MailGerente . "<br>";

# ---------------------------------------------------------

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../../vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = 0; //Enable verbose debug output
    $mail->isSMTP(); //Send using SMTP
    $mail->Host = 'smtp.office365.com'; //Set the SMTP server to send through

    $mail->SMTPAuth = true; //Enable SMTP authentication  
    $mail->Username = 'alenapp@alenintelligent.com'; //SMTP username
    $mail->Password = 'Lur23991'; //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port = 587; //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('alenstore@alenintelligent.com', 'Mailer');
    $mail->addAddress($MailGerente, $Gerente);     //Add a recipient
    $mail->addAddress($CorreoSolicitante, $Nombre);     //Add a recipient
    //$mail->addAddress('betohurtado3@hotmail.com');               //Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    echo "Source: " . $source . "<br>";
    switch ($source) {
        case 'Reembolso':
            $mail->isHTML(true);  
            $mail->CharSet = 'UTF-8';                                //Set email format to HTML
            $mail->Subject = 'Se ha registrado una solicitud de reembolso de ' . $Nombre . '';
            $mail->Body = 'Se ha registrado una solicitud de reembolso de ' . $Nombre . '';
            $mail->AltBody = 'Solicitud de Reembolso';
            $mail->send();
            echo 'Message has been sent';
            header('Location: ../../../../src/Viaticos/misViaticos.php');

            break;
        case 'Completado':
            $mail->isHTML(true); 
            $mail->CharSet = 'UTF-8';                                 //Set email format to HTML
            $mail->Subject = 'Verficacion de Viatico Completado';
            $mail->Body = 'Se ha completado la verificacion del viatico de ' . $Nombre . ' para el dia ' . $Fecha_Salida . '. ';
            $mail->AltBody = 'Completado';
            $mail->send();
            echo 'Message has been sent';
            header('Location: ../../../../src/Viaticos/misViaticos.php');
            break;
        case 'Verificacion':

            $mail->isHTML(true); 
            $mail->CharSet = 'UTF-8';                                 //Set email format to HTML
            $mail->Subject = 'Verficacion de Viatico';
            $mail->Body = 'El viatico de ' . $Nombre . ' para el dia ' . $Fecha_Salida . ' ha cumplido su fecha de regreso, por favor verificar en el sistema
            debido a que tienes 3 dias para subir evidencias';
            $mail->AltBody = 'Subir evidencias de viatico';
            $mail->send();
            echo 'Message has been sent';
            header('Location: ../../../../src/Viaticos/misViaticos.php');
            break;
        case 'ViaticoRechazado':
            //Content
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';                                 //Set email format to HTML
            $mail->Subject = 'Viatico Rechazado';
            $mail->Body = 'El viatico de ' . $Nombre . ' para el dia ' . $Fecha_Salida . ' ha sido rechazado';
            $mail->AltBody = 'Viatico Rechazado';

            $mail->send();
            echo 'Message has been sent';
            header('Location: ../../../../src/Viaticos/ListadoViaticos.php');
            break;
        case 'ViaticoAceptado':
            //Content
            $mail->isHTML(true);    
            $mail->CharSet = 'UTF-8';                              //Set email format to HTML
            $mail->Subject = 'Viatico Aceptado';
            $mail->Body = 'El viatico de ' . $Nombre . ' para el dia ' . $Fecha_Salida . ' ha sido aceptado';
            $mail->AltBody = 'Viatico Aceptado';

            $mail->send();
            echo 'Message has been sent';
            header('Location: ../../../../src/Viaticos/ListadoViaticos.php');
            break;

        case 'nuevoViatico':
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Nueva Solicitud de ' . $Nombre . '';
            $mail->Body = 'Se registro una solicitud de viáticos para el dia ' . $Fecha_Salida . ', 
            por favor revisar el sistema para su aprobacion';
            $mail->AltBody = 'Nueva Solicitud de viatico';

            $mail->send();
            echo 'Message has been sent';
            header('Location: ../../../../src/Viaticos/misViaticos.php');
            break;
        default:
            # code...
            break;
    }
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}


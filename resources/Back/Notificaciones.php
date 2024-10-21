<?php
session_start();
require('../../resources/config/db.php');

$Id = $_GET['Id'];
$Name = $_GET['Name'];
$Request = $_GET['Request'];
$Archivo = $_GET['Archivo'];

echo "Id: $Id, Name: $Name, Request: $Request";

// Tomar Información del Viático
$Sql_Viatico = "SELECT * FROM Viaticos WHERE Id = $Id";
$Result_Viatico = $conn->query($Sql_Viatico);
$Viatico = $Result_Viatico->fetch_assoc();
$FechaSalida = $Viatico['Fecha_Salida'];
$FechaSalida = date('d-m-Y', strtotime($FechaSalida));
$FechaRegreso = $Viatico['Fecha_Regreso'];
$FechaRegreso = date('d-m-Y', strtotime($FechaRegreso));
$OrdenVenta = $Viatico['Orden_Venta'];
$Nombre_Proyecto = $Viatico['Nombre_Proyecto'];
$Codigo = $Viatico['Codigo'];
$Destino = $Viatico['Destino'];
$Total = $Viatico['Total'];

// Formato: Orden de venta -> Código -> Nombre del Proyecto. 
$Formated = $OrdenVenta . ' ' . $Codigo . ' ' . $Nombre_Proyecto;
echo "<br> Formated: $Formated";

// Consulta SQL con self-join para obtener la información del usuario y su gerente
$Sql_Usuario = "
    SELECT 
        solicitante.Nombre AS nombre_solicitante, 
        solicitante.Telefono AS telefono_solicitante,
        gerente.Nombre AS nombre_gerente, 
        gerente.Telefono AS telefono_gerente
    FROM 
        Usuarios solicitante
    LEFT JOIN 
        Usuarios gerente 
    ON 
        solicitante.Gerente = gerente.Nombre
    WHERE 
        solicitante.Nombre = '$Name'
";

$Result_Usuario = $conn->query($Sql_Usuario);
$Usuario = $Result_Usuario->fetch_assoc();

$Nombre_Solicitante = $Usuario['nombre_solicitante'];
$Telefono_Solicitante = '+52' . $Usuario['telefono_solicitante'];

$Nombre_Gerente = $Usuario['nombre_gerente'];
$Telefono_Gerente = '+52' . $Usuario['telefono_gerente'];

// Definir la ruta del archivo 
$RutaCompleta = '/uploads/files/' . $Archivo;

/*
 Request:
 - Registro -> Registrar Viático
 - Aprobar -> Aprobar Viático (Gerente y Control Aprobaron)
 - Rechazar -> Rechazar Viático (Gerente o Control Rechazaron)
*/
// Ajustar 'Body' del mensaje dependiendo del 'Request'

if ($Request == 'Registro') {
  $Body = '👋 Hola, *' . $Nombre_Solicitante . '*!' . "\n" .
    'Tu solicitud de viático ha sido *Registrada* ✅.' . "\n\n" .
    '🗓️ *Fecha de Salida:* ' . $FechaSalida . "\n" .
    '🗓️ *Fecha de Regreso:* ' . $FechaRegreso . "\n" .
    '📍 *Destino:* ' . $Destino . "\n" .
    '🚧  *Proyecto:* ' . $Formated . "\n" .
    '💰 *Total:* $' . $Total . "\n\n" .
    'El resumen de tu solicitud se encuentra en el archivo adjunto.' . "\n" .
    'Si tienes alguna duda, puedes ingresar a la plataforma aquí: 👉 www.viaticos.com' . "\n" .
    '¡Gracias! 😊';

  $Body_Gerente = '👋 Hola, *' . $Nombre_Gerente . '*!' . "\n" .
    'Se ha registrado un nuevo viático para *' . $Nombre_Solicitante . '* 📝.' . "\n\n" .
    '🗓️ *Fecha de Salida:* ' . $FechaSalida . "\n" .
    '🗓️ *Fecha de Regreso:* ' . $FechaRegreso . "\n" .
    '📍 *Destino:* ' . $Destino . "\n" .
    '🚧 *Proyecto:* ' . $Formated . "\n" .
    '💰 *Total:* $' . $Total . "\n\n" .
    'El resumen de la solicitud se encuentra en el archivo adjunto.' . "\n" .
    'Si tienes alguna duda, puedes ingresar a la plataforma aquí: 👉 www.viaticos.com' . "\n" .
    '¡Gracias! 😊';



  /// Enviar mensaje al Solicitante
  $params = array(
    'token' => 'y3oaf6uaadjt03f4',
    'to' => $Telefono_Solicitante,
    'filename' => $Archivo,
    'document' => 'https://file-example.s3-accelerate.amazonaws.com/documents/cv.pdf',
    'body' => $Body,
    'caption' => 'Resumen de la solicitud de viático'
  );
  $curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.ultramsg.com/{instance95547}/messages/document",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_SSL_VERIFYHOST => 0,
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => http_build_query($params),
  CURLOPT_HTTPHEADER => array(
    "content-type: application/x-www-form-urlencoded"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}

  // Enviar mensaje al Gerente
  $params = array(
    'token' => 'y3oaf6uaadjt03f4',
    'to' => $Telefono_Gerente,
    'filename' => $Archivo,
    'document' => 'https://file-example.s3-accelerate.amazonaws.com/documents/cv.pdf', 
    'body' => $Body_Gerente,
    'caption' => 'Resumen de la solicitud de viático'
  );
  $curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.ultramsg.com/{instance95547}/messages/document",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_SSL_VERIFYHOST => 0,
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => http_build_query($params),
  CURLOPT_HTTPHEADER => array(
    "content-type: application/x-www-form-urlencoded"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}

}

header('Location: /src/Viaticos/detalles.php?id=' . $Id);
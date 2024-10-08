<?php
session_start();
require('../../resources/config/db.php');

$Id = $_GET['Id'];
$Name = $_GET['Name'];
$Request = $_GET['Request'];

echo "Id: $Id, Name: $Name, Request: $Request";

// Tomar Información del Viático
$Sql_Viatico = "SELECT * FROM Viaticos WHERE Id = $Id";
$Result_Viatico = $conn->query($Sql_Viatico);
$Viatico = $Result_Viatico->fetch_assoc();
$FechaSalida = $Viatico['Fecha_Salida'];
$OrdenVenta = $Viatico['Orden_Venta'];
$Nombre_Proyecto = $Viatico['Nombre_Proyecto'];

// Tomar Información del Usuario
$Sql_Usuario = "SELECT * FROM Usuarios WHERE Nombre = '$Name'";
$Result_Usuario = $conn->query($Sql_Usuario);
$Usuario = $Result_Usuario->fetch_assoc();
//$Telefono = $Usuario['Telefono'];
$Telefono = '+523332364881';

$params=array(
'token' => 'y3oaf6uaadjt03f4',
'to' => '+523332364881',
'body' => 'Hola, '.$Name.'! Tu solicitud de viático para el proyecto '.$Nombre_Proyecto.' con orden de venta '.$OrdenVenta.' ha sido '.$Request.'.',
);
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.ultramsg.com/instance95547/messages/chat",
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
<?php

require('../../config/db.php');
$Id_User = $_GET['id_user'];
$Name = $_GET['Nombre'];
$User = $_GET['Usuario'];
$Mail = $_GET['Correo'];
$Position = $_GET['Puesto'];
$Gerente = $_GET['Gerente'];
$Nss = $_GET['Nss'];
$Télefono = $_GET['Télefono'];

echo "<br>--------------- Información del Formulario ---------------<br>";
echo "Id de Usuario: " . $Id_User;
echo "<br>Nombre: " . $Name;
echo "<br>Usuario: " . $User;
echo "<br>Correo: " . $Mail;
echo "<br>Puesto: " . $Position;
echo "<br>Gerente: " . $Gerente;
echo "<br>NSS: " . $Nss;
echo "<br>Télefono: " . $Télefono;
echo "<br>--------------- ... ---------------<br>";

$sql_Query = "UPDATE usuarios SET Nombre = '$Name', Usuario = '$User', Correo = '$Mail', Puesto = '$Position', Gerente='$Gerente', NSS='$Nss', Telefono='$Télefono'  WHERE Id = '$Id_User'";
$result = $conn->query($sql_Query);
if ($result === TRUE) {
    echo "Usuario actualizado correctamente";
    header('Location: ../../../../src/Admin/Usuarios.php');
} else {
    echo "Error: " . $sql_Query . "<br>" . $conn->error;
}

?>
<?php
require('../../config/db.php');
$Name = $_POST['nombre'];
$Nickname = $_POST['Nickname'];
$Email = $_POST['correo'];
$Password = $_POST['password'];
$Position = $_POST['puesto'];
$nss = $_POST['nss'];

$EncryptedPassword = md5($Password);

echo "<br>--------------- Información del Formulario ---------------<br>";
echo "Nombre: " . $Name . "<br>";
echo "Nickname: " . $Nickname . "<br>";
echo "Correo: " . $Email . "<br>";
//echo "Contraseña: " . $Password . "<br>";
echo "Puesto: " . $Position . "<br>";
//echo "Contraseña Encriptada: " . $EncryptedPassword . "<br>";
echo "NSS: " . $nss . "<br>";
echo "--------------- Información del Formulario ---------------<br>";

/// Procesar la imagen que se envia por medio del formulario
/// Crear un nombre del archivo a partir del nombre del usuario
/// Guardar la imagen en la carpeta de imagenes de usr.
/// Habilitar un atributo para registrar el nombre de la imagen en la base de datos en la misma tabla de usuarios


/// Realizar Inserción de datos en la base de datos
$sql_Query = "INSERT INTO usuarios (Nombre, Usuario, Correo, Password, Puesto, Estado, Gerente) 
VALUES ('$Name', '$Nickname', '$Email', '$EncryptedPassword', '$Position', 'Activo', 'Gerente')";
$result = $conn->query($sql_Query);

if ($result === TRUE) {
    echo "Usuario creado correctamente";
    header('Location: ../../../../src/Admin/Usuarios.php');
} else {
    echo "Error: " . $sql_Query . "<br>" . $conn->error;
}



?>
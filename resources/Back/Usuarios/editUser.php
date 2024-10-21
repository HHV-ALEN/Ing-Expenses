<?php

require ('../../config/db.php');

$Id_user = $_POST['id_user'];
$Name = $_POST['nombre'];
$Mail = $_POST['correo'];
$Manager = $_POST['gerente'];
$Position = $_POST['puesto'];
$Sucursal = $_POST['sucursal'];

$update_Query = "UPDATE usuarios SET Nombre = '$Name', 
Correo = '$Mail', Gerente = '$Manager', Puesto = '$Position', Sucursal = '$Sucursal' WHERE Id = '$Id_user'";
$update_Query_run = mysqli_query($conn, $update_Query);

if ($update_Query_run) {
    echo '<script> alert("Datos actualizados correctamente"); </script>';
    header('Location: ../../../../../src/Admin/Usuarios.php');
} else {
    echo '<script> alert("Error al actualizar los datos"); </script>';
}


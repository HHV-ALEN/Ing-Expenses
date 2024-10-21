<?php

require ('../../config/db.php');

$Id_user = $_POST['id_user'];
$Name = $_POST['nombre'];
$Mail = $_POST['correo'];

$update_Query = "UPDATE gerente SET Nombre = '$Name', 
Correo = '$Mail' WHERE Id = '$Id_user'";
$update_Query_run = mysqli_query($conn, $update_Query);

if ($update_Query_run) {
    echo '<script> alert("Datos actualizados correctamente"); </script>';
    header('Location: ../../../../../src/Admin/Gerentes.php');
} else {
    echo '<script> alert("Error al actualizar los datos"); </script>';
}


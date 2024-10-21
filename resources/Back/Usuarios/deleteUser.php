<?php
require('../../config/db.php');
$Id_User = $_GET['id_user'];

echo $Id_User;

echo "Apunto de eliminar";
/// Eliminación logica del usuario
$delete_query = "UPDATE usuarios SET Estado = 'Inactivo' WHERE Id = '$Id_User'";
$delete_query_run = mysqli_query($conn, $delete_query);

if ($delete_query_run) {
    echo '<script> alert("Usuario eliminado correctamente"); </script>';
    header('Location: ../../../../../src/Admin/Usuarios.php');
} else {
    echo '<script> alert("Error al eliminar el usuario"); </script>';
}

header('Location: /src/Admin/Usuarios.php');

?>
<?php
require('../../config/db.php');

$id_usuario = $_POST['id_user'];

/// Eliminación logica del usuario
$delete_query = "UPDATE usuarios SET Estado = 'Inactivo' WHERE Id = '$id_usuario'";
$delete_query_run = mysqli_query($conn, $delete_query);

if ($delete_query_run) {
    echo '<script> alert("Usuario eliminado correctamente"); </script>';
    header('Location: ../../../../../src/Admin/Usuarios.php');
} else {
    echo '<script> alert("Error al eliminar el usuario"); </script>';
}

?>
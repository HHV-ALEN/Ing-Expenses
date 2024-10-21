<?php
require('../../config/db.php');

$Name = $_POST['nombre'];
$Correo = $_POST['correo'];

$add_query = "INSERT INTO gerente (Nombre, Correo, Estado) VALUES ('$Name', '$Correo', 'Activo')";
$add_query_run = mysqli_query($conn, $add_query);

if ($add_query_run) {
    echo '<script> alert("Gerente agregado correctamente"); </script>';
    header('Location: ../../../../../src/Admin/Gerentes.php');
} else {
    echo '<script> alert("Error al agregar el gerente"); </script>';
}

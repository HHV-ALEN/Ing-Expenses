<?php
ob_start(); // Inicia el buffer de salida
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

include ('../../config/db.php');
require '../../../vendor/autoload.php';
$nombre_imagen = $_GET['name'];
$id_reembolso = $_GET['id_reembolso'];

echo "Nombre de la imagen: ".$nombre_imagen."<br>";
echo "ID del viático: ".$id_reembolso."<br>";

// Eliminar registro en la base de datos
$sql = "DELETE FROM reembolsos_anidados WHERE Id = $id_reembolso AND Imagen = '$nombre_imagen'";
if ($conn->query($sql) === TRUE) {
    if ($conn->affected_rows > 0) {
        header('Location: ../../../src/Viaticos/detalleReembolso.php?id_reembolso='.$id_reembolso.'&status=success');
    } else {
        header('Location: ../../../src/Viaticos/detalleReembolso.php?id_reembolso='.$id_reembolso.'&status=not_found');
    }
} else {
    header('Location: ../../../src/Viaticos/detalleReembolso.php?id_reembolso='.$id_reembolso.'&status=error');
}

?>

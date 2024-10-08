<?php
session_start();
require '../../config/db.php';

$Id_Reembolso = $_GET['id'];
$Nombre = $_SESSION['Name'];

echo "<br> Id_Reembolso: " . $Id_Reembolso;
echo "<br> Nombre: " . $Nombre;

/// Eliminar reembolso
$Eliminar_Reembolso = "DELETE FROM reembolsos WHERE Id = '$Id_Reembolso' AND Solicitante = '$Nombre'";
$Resultado_Eliminar_Reembolso = mysqli_query($conn, $Eliminar_Reembolso);

if ($Resultado_Eliminar_Reembolso) {
    echo "<br> Reembolso eliminado correctamente";
} else {
    echo "<br> Error al eliminar reembolso";
}

header("Location: ../../../../src/Reembolsos/MisReembolsos.php");


?>
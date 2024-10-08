<?php 
include '../../config/db.php';
session_start();
$Tipo_Usuario = $_SESSION['Position'];
/// Obtener y limpiar la variable Response de la URL
$Response = $_GET['Response'];
$Id = $_GET['id'];
$Id_viatico = $_GET['Id_viatico'];

echo "Id de la evidencia: ".$Id;
echo "<br> Tipo de Usuario: ".$Tipo_Usuario;
echo "<br> Respuesta: ".$Response;
echo "<br> Id del viatico: ".$Id_viatico;

/// Si la respuesta es Aceptado, actualizar la evidencia a Aceptado
if($Response == "Aceptado"){
    $sql = "UPDATE evidencias SET Estado = 'Aceptado' WHERE Id = '$Id'";
    if($conn->query($sql) === TRUE){
        echo "<br> Evidencia Aceptada";
    }else{
        echo "<br> Error al aceptar la evidencia";
    }
} elseif($Response == "Rechazado"){
    $sql = "UPDATE evidencias SET Estado = 'Rechazado' WHERE Id = '$Id'";
    if($conn->query($sql) === TRUE){
        echo "<br> Evidencia Rechazada";
    }else{
        echo "<br> Error al rechazar la evidencia";
    }
}

header("Location: ../../../src/Viaticos/SubirEvidencias.php?id=$Id_viatico");
?>
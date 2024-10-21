<?php 
require ('../../config/db.php');
session_start();

$id_viatico = $_GET['Id_Viatico'];
$Response = $_GET['Response'];
echo "Id_Viatico: $id_viatico<br>";

/// Obtener información del viatico
$sql_query = "SELECT * FROM viaticos WHERE Id = $id_viatico ";
$result = $conn->query($sql_query);
/// Si se encontró el viatico, obtener el Id_Usuario y el Id_Gerente
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $Id_ViaticoInt = $row['Id'];
    $id_Usuario = $row['Id_Usuario'];
    $id_Gerente = $row['Id_Gerente'];
} else {
    echo "Error: " . $sql_query . "<br>" . $conn->error;
}


if($Response=='PrimeraRevision'){

    /// Actualizar el estado de la evidencia a Verificación
    $sql_query = "UPDATE viaticos SET Estado = 'Comprobación' WHERE Id = $id_viatico";
    if ($conn->query($sql_query) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    // Registrar Verificación para que sea revisada por el gerente y control
    $sql_query = "INSERT INTO verificacion (Id_Viatico, Solicitante, Tipo, Aceptado_Control, Aceptado_Gerente) VALUES
    ($id_viatico, $id_Usuario, 'Comprobación', 'Pendiente', 'Pendiente')";
    if ($conn->query($sql_query) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }

} elseif ($Response=='SegundaRevision'){
    /// Actualizar el estado de la evidencia a Verificación
    $sql_query = "UPDATE viaticos SET Estado = 'Segunda Revisión' WHERE Id = $id_viatico";
    if ($conn->query($sql_query) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    // Registrar Verificación para que sea revisada por el gerente y control
    $sql_query = "INSERT INTO verificacion (Id_Viatico, Solicitante, Tipo, Aceptado_Control, Aceptado_Gerente) VALUES
    ($id_viatico, $id_Usuario, 'Segunda Revisión', 'Pendiente', 'Pendiente')";
    if ($conn->query($sql_query) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
    
}






header("Location: /src/Viaticos/MisViaticos.php");

/// Mandar correo al gerente
//header("Location: ../Mail/evidencias.php?id_usuario=$id_Usuario&id_gerente=$id_Gerente&id_viatico=$id_viatico");




?>
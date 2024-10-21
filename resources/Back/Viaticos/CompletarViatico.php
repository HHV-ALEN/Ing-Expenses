<?php 
include '../../config/db.php';
session_start();
$id = $_GET['id'];
echo "Id Recibido: ".$id;
$TipoUsuario = $_SESSION['Position'];
$Response = $_GET['Response'];


if($Response == 'Aceptado'){
    $sql = "UPDATE viaticos SET Estado = 'Completado' WHERE Id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
        header("Location: /resources/Back/Mail/viaticoCompletado.php?Id=$id");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
elseif ($Response == 'Rechazado') {
    $sql = "UPDATE viaticos SET Estado = 'Prórroga' WHERE Id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
        /// Registrar fecha en la que el viático entro a la prórroga

        /// dia actual en una variable
        $Fecha_Actual = date("Y-m-d");

        $sql = "UPDATE viaticos SET Fecha_Prórroga = '$Fecha_Actual' WHERE Id = $id";
        if ($conn->query($sql) === TRUE) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $conn->error;
        }

        // Limpiar las evidencias cuando el viático entra en prórroga
        $sql_delete_evidencias = "DELETE FROM evidencias WHERE Id_Relacionado = $id AND Tipo = 'Evidencia' AND Estado = 'Pendiente' Or Estado = 'Rechazado'";
        if ($conn->query($sql_delete_evidencias) === TRUE) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "Error updating record: " . $conn->error;
    }

}


if ($TipoUsuario == 'Gerente') {
    header("Location: ../../../../../src/Viaticos/Superior/Viaticos_AMiCargo.php");
} else {
    header("Location: ../../../../../src/Viaticos/Superior/ListadoViaticos.php");
}

?>
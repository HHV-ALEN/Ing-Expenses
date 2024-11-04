<?php 
$Id_viatico = $_GET['id'];
include '../../config/db.php';
session_start();
$Tipo_Usuario = $_SESSION['Position'];
$Response = $_GET['Response'];

echo "<br> Tipo de Usuario: ".$Tipo_Usuario;
echo "<br> Id del Viatico: ".$Id_viatico;

if ($Response == 'SegundaRevisión'){
    $Update_Query = "UPDATE viaticos SET Estado = 'Segunda Revisión' WHERE Id = '$Id_viatico'";
    $Update = $conn->query($Update_Query);
    if ($Update) {
        echo "<br> Viatico Enviado a segunda Revisón <br>";
        ///header ('Location: /resources/Back/Mail/viaticoRechazado.php?Id_Viatico=' . $Id_Viatico);
    } else {
        echo "<br> Error al actualizar <br>";
    }
} else {

    /// Se cambiara el estado del viatico a Revisión:
    $Update_Viatico = "UPDATE viaticos SET Estado = 'Revisión' WHERE Id = '$Id_viatico'";
    $Result_Update_Viatico = mysqli_query($conn, $Update_Viatico);
    if($Result_Update_Viatico){
        echo "<br> Estado del viatico cambiado a Revisión";
        /// Notificar al solicitante y al gerente de que su viatico se encuentra en revisión

        header("Location: ../../../../../resources/Back/Mail/viaticoEnRevisión.php?Id=$Id_viatico");
    }else{
        echo "<br> Error al cambiar el estado del viatico a Revisión";
    }

}
header ('Location: /resources/Back/Mail/viaticoEnRevisión.php?Id_Viatico=' . $Id_viatico);
//header("Location: ../../../../../src/Viaticos/MisViaticos.php");



?>
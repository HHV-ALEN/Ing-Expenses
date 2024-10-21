<?php 
include '../../config/db.php';
$Id_Viatico = $_GET['id'];
echo $Id_Viatico;

/// Query para eliminar registro de la tabla viaticos en donde el id sea igual al id que se recibe por GET
$DeleteViatico = "DELETE FROM viaticos WHERE Id = $Id_Viatico";
$ResultDeleteViatico = mysqli_query($conn, $DeleteViatico);


if ($ResultDeleteViatico) {
    echo "Registro eliminado";
} else {
    echo "Error al eliminar registro";
}
header("Location: ../../../../../src/Viaticos/MisViaticos.php");

?>
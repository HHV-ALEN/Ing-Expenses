<?php

include_once '../../../resources/config/db.php';

$idFolioCorrecto = $_POST['idFolioCorrecto'];
$id_reembolso = $_GET['id_reembolso'];

echo $idFolioCorrecto;
echo "<br> ID Reembolso: ".$id_reembolso;

/// Verificar si un registro ya tiene ese mimsmo FOLIO
$consulta = "SELECT * FROM reembolso WHERE Id = '$idFolioCorrecto'";
$resultado = $conn->query($consulta);
$filas = $resultado->num_rows;
if ($filas > 0) {
    echo "<br> Ya existe un registro con ese folio";
    header("Location: ../detallesReembolso.php?id_reembolso=$id_reembolso&error=1");
} else {
    echo "<br> No existe un registro con ese folio";
    $consulta = "UPDATE reembolso SET Id = '$idFolioCorrecto' WHERE Id = '$id_reembolso'";
    $resultado = $conn->query($consulta);
    if ($resultado) {
        echo "<br> Se actualizó el folio correctamente";
        /// Editar Registros Anidados
        $consulta_anidada = "UPDATE reembolsos_anidados SET Id = '$idFolioCorrecto', Anidado = '$idFolioCorrecto' 
        WHERE Anidado = '$id_reembolso'";
        $resultado_anidado = $conn->query($consulta_anidada);
        if ($resultado_anidado) {
            echo "<br> Se actualizó el folio en los registros anidados";
            header("Location: ../detallesReembolso.php?id_reembolso=$idFolioCorrecto&success=1");
        } else {
            echo "<br> No se actualizó el folio en los registros anidados";
        }
        
    } else {
        echo "<br> No se actualizó el folio";
        header("Location: ../detallesReembolso.php?id_reembolso=$id_reembolso&error=2");
    }
}
?>
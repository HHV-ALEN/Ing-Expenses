<?php 

require ('../../config/db.php');
session_start();
$Estados = $_SESSION['Estados'];

echo "------------------------------------ <br>";
print_r($Estados);
echo "<br>------------------------------------ <br>";

$Id_Reembolso = $Estados[0]['Id_Reembolso'];
echo "Id Reembolso: $Id_Reembolso <br>";

/// Verificar si todos los estados son Diferentes a 'Abierto'
$Estados_Abiertos = 0;
foreach($Estados as $Estado){
    if($Estado['Estado'] == 'Abierto'){
        $Estados_Abiertos++;
    }
}

if($Estados_Abiertos > 0){
    echo "--------------- Hay estados Abiertos --------------- <br>";
    echo "--------------- No se puede finalizar el reembolso --------------- <br>";
    // Regresar a la pagina de Reembolsos
    header('Location: ../../../../../src/Control/detallesReembolso.php?id_reembolso=' . $Id_Reembolso);
} else {
    echo "--------------- Todos los estados son diferentes a Abierto --------------- <br>";
    echo "--------------- Se puede finalizar el reembolso --------------- <br>";

    // Actualizar el estado a Aceptado en la tabla verificacion:
    $Update_Verificacion = "UPDATE verificacion SET Aceptado_Control = 'Completado' WHERE Id_Reembolso = $Id_Reembolso";
    $Result_Update_Verificacion = mysqli_query($conn, $Update_Verificacion);
    if($Result_Update_Verificacion){
        echo "--------------- Se actualizo el estado a Completado en la tabla verificacion --------------- <br>";
    } else {
        echo "--------------- No se actualizo el estado a Completado en la tabla verificacion --------------- <br>";
    }

    // Actualizar el estado a Completado en la tabla reembolso:
    $Update_Reembolso = "UPDATE reembolso SET Estado = 'Completado' WHERE Id = $Id_Reembolso";
    $Result_Update_Reembolso = mysqli_query($conn, $Update_Reembolso);
    if($Result_Update_Reembolso){
        echo "--------------- Se actualizo el estado a Completado en la tabla reembolso --------------- <br>";
    } else {
        echo "--------------- No se actualizo el estado a Completado en la tabla reembolso --------------- <br>";
    }

    // Actualizar el estado a Completado en la tabla reembolso_anidados:
    $Update_Reembolso_Anidados = "UPDATE reembolsos_anidados SET Estado = 'Completado' WHERE Anidado = $Id_Reembolso";
    $Result_Update_Reembolso_Anidados = mysqli_query($conn, $Update_Reembolso_Anidados);
    if($Result_Update_Reembolso_Anidados){
        echo "--------------- Se actualizo el estado a Completado en la tabla reembolso_anidados --------------- <br>";
    } else {
        echo "--------------- No se actualizo el estado a Completado en la tabla reembolso_anidados --------------- <br>";
    }

    // Limpiar la variable de sesion Estados
    unset($_SESSION['Estados']);

    echo "Id Reembolso: $Id_Reembolso <br>";

    // Regresar a la pagina de Reembolsos
    header('Location: ../../../../../src/Control/detallesReembolso.php?id_reembolso=' . $Id_Reembolso);

    }

?>
<?php
require ('../../config/db.php');
session_start();


$id_viatico = $_GET['id_viatico'];
$Respuesta = $_GET['Respuesta'];
$Puesto = $_SESSION['Position'];
$Nombre = $_SESSION['Name'];

echo "Id viatico: $id_viatico <br>";
echo "Respuesta: $Respuesta <br>";
echo "Puesto: $Puesto <br>";
echo "Nombre: $Nombre <br>";

/// Obtener información previo a enviar el correo ------------------

$viatico_Query = "SELECT * FROM viaticos WHERE Id = $id_viatico";
$viatico_Result = mysqli_query($conn, $viatico_Query);
$viatico_Row = mysqli_fetch_assoc($viatico_Result);
$id_usuario = $viatico_Row['Id_Usuario'];
$Id_Gerente = $viatico_Row['Id_Gerente'];
$FechaHoy = date("Y-m-d");

if ($Puesto == "Gerente") {
    if ($Respuesta == "Aceptado") {
        $Aceptado_Query = "UPDATE verificacion SET Aceptado_Gerente = 'Aceptado', Gerente = '$Nombre' WHERE Id_Viatico = $id_viatico";
        $Aceptado_Result = mysqli_query($conn, $Aceptado_Query);
        if ($Aceptado_Result) {
            echo "Viatico aceptado con id: $id_viatico";
            header('Location: ../Mail/NotificarAlAceptarGerente.php?id_usuario=' . $id_usuario . '&id_gerente=' . $Id_Gerente . '&id_viatico=' . $id_viatico . '');

        } else {
            echo "Error al aceptar viatico";
        }
    }
} elseif ($Puesto == "Control") {
    if ($Respuesta == "Aceptado") {
        $Aceptado_Query = "UPDATE verificacion SET Aceptado_Control = 'Aceptado', Verificador = '$Nombre' WHERE Id_Viatico = $id_viatico";
        $Aceptado_Result = mysqli_query($conn, $Aceptado_Query);
        if ($Aceptado_Result) {
            echo "Viatico aceptado con id: $id_viatico";

        } else {
            echo "Error al aceptar viatico";
        }
    }
}

if ($Respuesta == "Rechazado") {
    if ($Puesto == "Gerente") {
        $Rechazar_Query = "UPDATE verificacion SET Aceptado_Gerente = 'Rechazado', Gerente = '$Nombre' WHERE Id_Viatico = $id_viatico";
        $Rechazar_Result = mysqli_query($conn, $Rechazar_Query);
        if ($Rechazar_Result) {

            $Rechazado_Query = "UPDATE viaticos SET Estado = 'Rechazado' WHERE Id = $id_viatico";
            $Rechazado_Result = mysqli_query($conn, $Rechazado_Query);
            if ($Rechazado_Result) {
                echo "Viatico rechazado con id: $id_viatico";
                header('Location: ../Mail/Rechazado.php?id_usuario=' . $id_usuario . '&id_gerente=' . $Id_Gerente . '&id_viatico=' . $id_viatico . '');
            } else {
                echo "Error al rechazar viatico";
            }
        } else {
            echo "Error al rechazar viatico";
        }
    } elseif ($Puesto == "Control") {
        $Rechazar_Query = "UPDATE verificacion SET Aceptado_Control = 'Rechazado', Verificador = '$Nombre' WHERE Id_Viatico = $id_viatico";
        $Rechazar_Result = mysqli_query($conn, $Rechazar_Query);
        if ($Rechazar_Result) {
            $Rechazado_Query = "UPDATE viaticos SET Estado = 'Rechazado' WHERE Id = $id_viatico";
            $Rechazado_Result = mysqli_query($conn, $Rechazado_Query);
            if ($Rechazado_Result) {
                echo "Viatico rechazado con id: $id_viatico";
                header('Location: ../Mail/Rechazado.php?id_usuario=' . $id_usuario . '&id_gerente=' . $Id_Gerente . '&id_viatico=' . $id_viatico . '');
            } else {
                echo "Error al rechazar viatico";
            }
        } else {
            echo "Error al rechazar viatico";
        }
    }

}

/// Verificar si el viatico ya fue aceptado por todos los involucrados
$Verificar_Query = "SELECT * FROM verificacion WHERE Id_Viatico = $id_viatico";
$Verificar_Result = mysqli_query($conn, $Verificar_Query);
$Verificar_Row = mysqli_fetch_assoc($Verificar_Result);
$Aceptado_Gerente = $Verificar_Row['Aceptado_Gerente'];
$Aceptado_Control = $Verificar_Row['Aceptado_Control'];
$Gerente = $Verificar_Row['Gerente'];
$Verificador = $Verificar_Row['Verificador'];

if ($Aceptado_Gerente == "Aceptado" && $Aceptado_Control == "Aceptado") {
    // Cambiar estado del viatico a aceptado
    $Aceptar_Query = "UPDATE viaticos SET Estado = 'Aceptado' WHERE Id = $id_viatico";
    $Aceptar_Result = mysqli_query($conn, $Aceptar_Query);
    if ($Aceptar_Result) {
        echo "Viatico aceptado con id: $id_viatico";
        header('Location: ../Mail/Aceptado.php?id_usuario=' . $id_usuario . '&id_gerente=' . $Id_Gerente . '&id_viatico=' . $id_viatico . '');
    } else {
        if ($Puesto == "Gerente") {
            header('Location: ../../../../../src/Viaticos/ViaticosACargo.php');
        } else {
            header('Location: ../../../../../src/Viaticos/ListadoViaticos.php');
        }

    }
} else {
    if ($Puesto == "Gerente") {
        header('Location: ../../../../../src/Viaticos/ViaticosACargo.php');
    } elseif ($Puesto == "Control") {
        header('Location: ../../../../../src/Viaticos/ListadoViaticos.php');
    } else {
        header('Location: ../../../../../src/Viaticos/misViaticos.php');
    }

}


?>
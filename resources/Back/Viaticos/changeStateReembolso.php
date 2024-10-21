<?php

require ('../../config/db.php');
session_start();

$id_reembolso = $_GET['id_reembolso'];
$Id_Folio_Reembolso = $_GET['Id_Folio_Reembolso'];
$Respuesta = $_GET['Respuesta'];
$Puesto = $_SESSION['Position'];
$Source = $_GET['Source'];

echo "------------------------------------ <br>";
echo "Id Reembolso: $id_reembolso <br>";
echo "Puesto: $Puesto <br>";
echo "Respuesta: $Respuesta <br>";
echo "Source: $Source <br>";
echo "------------------------------------ <br>";


/// Verificar si este reembolso esta ligado a un viatico
$GetInfo_Reembolso = "SELECT * FROM reembolso WHERE Id = $id_reembolso";
$Result_GetInfo_Reembolso = mysqli_query($conn, $GetInfo_Reembolso);
$Data_GetInfo_Reembolso = mysqli_fetch_array($Result_GetInfo_Reembolso);
//Pasar a variables:
$id_viatico = $Data_GetInfo_Reembolso['Id_Viatico'];
$Estado = $Data_GetInfo_Reembolso['Estado'];
$Id_Usuario = $Data_GetInfo_Reembolso['Id_Usuario'];
$Id_Gerente = $Data_GetInfo_Reembolso['Id_Gerente'];

// -------- Mostrar para debug:
echo "--------- Información del Reembolso-------------- <br>";
echo "Id Viatico: $id_viatico <br>";
echo "Estado: $Estado <br>";
echo "Id Usuario: $Id_Usuario <br>";
echo "Id Gerente: $Id_Gerente <br>";
echo "------------------------------------ <br>";

if($id_viatico == '0'){
    echo "--------------- Este reembolso no esta ligado a un viatico --------------- <br>";

    if($Source == 'Original'){
        echo "--------------- Source: Original --------------- <br>";
        if($Puesto == 'Control'){
            echo "--------------- Puesto: Control --------------- <br>";
            if($Respuesta == 'Aceptado'){
                echo "--------------- Respuesta: Aceptado --------------- <br>";
                // Actualizar el estado a Aceptado en la tabla verificacion:
                $Update_Verificacion = "UPDATE verificacion SET Aceptado_Control = 'Aceptado' WHERE Id_Reembolso = $id_reembolso";
                $Result_Update_Verificacion = mysqli_query($conn, $Update_Verificacion);
                if($Result_Update_Verificacion){
                    echo "--------------- Se actualizo el estado a Aceptado en la tabla verificacion --------------- <br>";
                } else {
                    echo "--------------- No se actualizo el estado a Aceptado en la tabla verificacion --------------- <br>";
                }
            } elseif ($Respuesta == 'Rechazado'){
                echo "--------------- Respuesta: Rechazado --------------- <br>";
                // Actualizar el estado a Rechazado en la tabla verificacion:
                $Update_Verificacion = "UPDATE verificacion SET Aceptado_Control = 'Rechazado' WHERE Id_Reembolso = $id_reembolso";
                $Result_Update_Verificacion = mysqli_query($conn, $Update_Verificacion);
                if($Result_Update_Verificacion){
                    echo "--------------- Se actualizo el estado a Rechazado en la tabla verificacion --------------- <br>";
                    /// Cuando Se rechaza, actualizar el estado del reembolso a Rechazado
                    $Update_Reembolso = "UPDATE reembolso SET Estado = 'Rechazado' WHERE Id = $id_reembolso";
                    $Result_Update_Reembolso = mysqli_query($conn, $Update_Reembolso);
                    if($Result_Update_Reembolso){
                        echo "--------------- Se actualizo el estado a Rechazado en la tabla reembolso --------------- <br>";
                    } else {
                        echo "--------------- No se actualizo el estado a Rechazado en la tabla reembolso --------------- <br>";
                    }
                } else {
                    echo "--------------- No se actualizo el estado a Rechazado en la tabla verificacion --------------- <br>";
                }
            }
        } elseif ($Puesto == 'Gerente'){
            echo "--------------- Puesto: Gerente --------------- <br>";
            if($Respuesta == 'Aceptado'){
                echo "--------------- Respuesta: Aceptado --------------- <br>";
                // Actualizar el estado a Aceptado en la tabla verificacion:
                $Update_Verificacion = "UPDATE verificacion SET Aceptado_Gerente = 'Aceptado' WHERE Id_Reembolso = $id_reembolso";
                $Result_Update_Verificacion = mysqli_query($conn, $Update_Verificacion);
                if($Result_Update_Verificacion){
                    echo "--------------- Se actualizo el estado a Aceptado en la tabla verificacion --------------- <br>";
                } else {
                    echo "--------------- No se actualizo el estado a Aceptado en la tabla verificacion --------------- <br>";
                }
            } elseif ($Respuesta == 'Rechazado'){
                echo "--------------- Respuesta: Rechazado --------------- <br>";
                // Actualizar el estado a Rechazado en la tabla verificacion:
                $Update_Verificacion = "UPDATE verificacion SET Aceptado_Gerente = 'Rechazado' WHERE Id_Reembolso = $id_reembolso";
                $Result_Update_Verificacion = mysqli_query($conn, $Update_Verificacion);
                if($Result_Update_Verificacion){
                    echo "--------------- Se actualizo el estado a Rechazado en la tabla verificacion --------------- <br>";
                    /// Cuando Se rechaza, actualizar el estado del reembolso a Rechazado
                    $Update_Reembolso = "UPDATE reembolso SET Estado = 'Rechazado' WHERE Id = $id_reembolso";
                    $Result_Update_Reembolso = mysqli_query($conn, $Update_Reembolso);
                    if($Result_Update_Reembolso){
                        echo "--------------- Se actualizo el estado a Rechazado en la tabla reembolso --------------- <br>";
                    } else {
                        echo "--------------- No se actualizo el estado a Rechazado en la tabla reembolso --------------- <br>";
                    }
                } else {
                    echo "--------------- No se actualizo el estado a Rechazado en la tabla verificacion --------------- <br>";
                }
            }
        }

        /// Cuando ambas verificaciones estan aceptadas, se cambia el estado del reembolso a Aceptado
        $GetInfo_Verificacion = "SELECT * FROM verificacion WHERE Id_Reembolso = $id_reembolso";
        $Result_GetInfo_Verificacion = mysqli_query($conn, $GetInfo_Verificacion);
        $Data_GetInfo_Verificacion = mysqli_fetch_array($Result_GetInfo_Verificacion);
        //Pasar a variables:
        $Aceptado_Control = $Data_GetInfo_Verificacion['Aceptado_Control'];
        $Aceptado_Gerente = $Data_GetInfo_Verificacion['Aceptado_Gerente'];

        if($Aceptado_Control == 'Aceptado' && $Aceptado_Gerente == 'Aceptado'){
            echo "--------------- Ambas verificaciones estan aceptadas --------------- <br>";
            $Update_Reembolso = "UPDATE reembolso SET Estado = 'Aceptado' WHERE Id = $id_reembolso";
            $Result_Update_Reembolso = mysqli_query($conn, $Update_Reembolso);
            if($Result_Update_Reembolso){
                echo "--------------- Se actualizo el estado a Aceptado en la tabla reembolso --------------- <br>";
            } else {
                echo "--------------- No se actualizo el estado a Aceptado en la tabla reembolso --------------- <br>";
            }
        } else {
            echo "--------------- Ambas verificaciones no estan aceptadas --------------- <br>";
        }

    }  elseif ($Source == 'Anidado') {
    echo "--------------- Source: Anidado --------------- <br>";
    if($Respuesta == 'Aceptado'){
        echo "--------------- Respuesta: Aceptado --------------- <br>";
        // Actualizar el estado a Aceptado en la tabla reembolso:
        $Update_Reembolso = "UPDATE reembolsos_anidados SET Estado = 'Aceptado' WHERE Id_Reembolso_Anidado = $id_reembolso";
        $Result_Update_Reembolso = mysqli_query($conn, $Update_Reembolso);
        if($Result_Update_Reembolso){
            echo "--------------- Se actualizo el estado a Aceptado en la tabla reembolso --------------- <br>";
        } else {
            echo "--------------- No se actualizo el estado a Aceptado en la tabla reembolso --------------- <br>";
        }
    } elseif ($Respuesta == 'Rechazado'){
        echo "--------------- Respuesta: Rechazado --------------- <br>";
        // Actualizar el estado a Rechazado en la tabla reembolso:
        $Update_Reembolso = "UPDATE reembolsos_anidados SET Estado = 'Rechazado' WHERE Id_Reembolso_Anidado = $id_reembolso";
        $Result_Update_Reembolso = mysqli_query($conn, $Update_Reembolso);
        if($Result_Update_Reembolso){
            echo "--------------- Se actualizo el estado a Rechazado en la tabla reembolso --------------- <br>";
        } else {
            echo "--------------- No se actualizo el estado a Rechazado en la tabla reembolso --------------- <br>";
        }
    }
}
}
echo "------------------------------------ <br>";
echo "id_reembolso: $id_reembolso <br>";
echo "Id_Folio_Reembolso: $Id_Folio_Reembolso <br>";
header ('Location: ../../../../../src/Control/detallesReembolso.php?id_reembolso=' . $Id_Folio_Reembolso);
?>


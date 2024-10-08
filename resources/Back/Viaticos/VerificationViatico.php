<?php
include '../../config/db.php';
session_start();
$Position = $_SESSION['Position'];
$Id_Viatico = $_GET['id'];
$Estado = $_GET['Estado'];

echo "<br> Id_Viatico: " . $Id_Viatico;
echo "<br> Estado: " . $Estado;
echo "<br> Position: " . $Position;


if($Position == 'Control'){
    echo "<br>Usuario Control";
    $Verification_Query = "UPDATE verificacion SET Aceptado_Control = '$Estado' WHERE Id_Relacionado = $Id_Viatico";
    if ($conn->query($Verification_Query) === TRUE) {
        echo "<br> Record updated successfully<br>";
    } else {
        echo "<br> Error updating record: " . $conn->error;
    }
}else if($Position == 'Gerente'){
    echo "<br>Usuario Gerente";
    $Verification_Query = "UPDATE verificacion SET Aceptado_Gerente = '$Estado' WHERE Id_Relacionado = $Id_Viatico";
    if ($conn->query($Verification_Query) === TRUE) {
        echo "<br> Record updated successfully<br>";
    } else {
        echo "<br> Error updating record: " . $conn->error;
    }
}

/// Verificar que los dos Campos esten Aceptados
$Verification_Query = "SELECT * FROM verificacion WHERE Id_Relacionado = $Id_Viatico";
$Verification_Result = $conn->query($Verification_Query);
$Verification_Row = $Verification_Result->fetch_assoc();
$Aceptado_Control = $Verification_Row['Aceptado_Control'];
$Aceptado_Gerente = $Verification_Row['Aceptado_Gerente'];
echo "<br>---------------------------------------------------<br>";
echo "<br> Aceptado_Control: " . $Aceptado_Control;
echo "<br> Aceptado_Gerente: " . $Aceptado_Gerente;
echo "<br>---------------------------------------------------<br>";

if($Aceptado_Control == 'Aceptado' && $Aceptado_Gerente == 'Aceptado'){
    echo "<br> Ambos Aceptados";
    $sql = "UPDATE viaticos SET Estado = 'Aceptado' WHERE Id = $Id_Viatico";
    if ($conn->query($sql) === TRUE) {
        echo "<br> Record updated successfully";
        header("Location: /resources/Back/Mail/viaticoCompletado.php?Id=$Id_Viatico");
    } else {
        echo "<br> Error updating record: " . $conn->error;
    }
} elseif ($Aceptado_Control == 'Rechazado' || $Aceptado_Gerente == 'Rechazado') {
    echo "<br> Alguno Rechazado";
    $sql = "UPDATE viaticos SET Estado = 'Rechazado' WHERE Id = $Id_Viatico";
    if ($conn->query($sql) === TRUE) {
        echo "<br> Record updated successfully";
        header("Location: /resources/Back/Mail/viaticoRechazado.php?Id=$Id_Viatico");
    } else {
        echo "<br> Error updating record: " . $conn->error;
    }
} 

echo "<br> Redireccionando a detalles CON ID: " . $Id_Viatico;

header("Location: /src/Viaticos/detalles.php?id=" . $Id_Viatico);
///header ('Location: ../../../../../src/Viaticos/detalles.php?id=' . $Id_Viatico);
?>
<?php
require ('../../config/db.php');
session_start();
$Tipo_Usuario = $_SESSION['Position'];

$id_viatico = $_GET['id_viatico'];
echo "Id_Viatico: $id_viatico<br>";
$Response = $_GET['Response'];
echo "Response: $Response<br>";
$Tipo = $_GET['Tipo'];
if ($Tipo == Null ){
    $Tipo = 'Comprobación';
}

echo "Tipo: $Tipo<br>";

if($Response == 'Aceptado'){
    /// Actualizar Aceptado por Control

    if ($Tipo_Usuario == 'Control'){
        $sql_query = "UPDATE verificacion SET Aceptado_Control = 'Aceptado' WHERE Id_Viatico = $id_viatico and Tipo = 'Comprobación'";
        if ($conn->query($sql_query) === TRUE) {
            echo "Aceptar Control Record updated successfully<br>";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } elseif ($Tipo_Usuario == 'Gerente'){
        $sql_query = "UPDATE verificacion SET Aceptado_Gerente = 'Aceptado' WHERE Id_Viatico = $id_viatico and Tipo = 'Comprobación'";
        if ($conn->query($sql_query) === TRUE) {
            echo "Aceptar Gerente Record updated successfully<br>";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }

    } elseif ($Response == 'Rechazado'){
    /// Actualizar Aceptado por Control
    if ($Tipo_Usuario == 'Control'){
        $sql_query = "UPDATE verificacion SET Aceptado_Control = 'Rechazado' WHERE Id_Viatico = $id_viatico and Tipo = 'Comprobación'";
        if ($conn->query($sql_query) === TRUE) {
            echo "Aceptado_Control - Rechazado - Record updated successfully<br>";
        
            /// Actualizar el estado del viático a Rechazado
            $sql_query = "UPDATE viaticos SET Estado = 'Rechazado' WHERE Id = $id_viatico";
            if ($conn->query($sql_query) === TRUE) {
                echo "Estado del viático actualizado a _Rechazado_<br>";
            } else {
                echo "Error updating record: " . $conn->error;
            }

        } else {
            echo "Error updating record: " . $conn->error;
        }
    } elseif ($Tipo_Usuario == 'Gerente'){
        $sql_query = "UPDATE verificacion SET Aceptado_Gerente = 'Rechazado' WHERE Id_Viatico = $id_viatico and Tipo = 'Comprobación'";
        if ($conn->query($sql_query) === TRUE) {
            echo "Aceptado_Gerente - Rechazado - Record updated successfully<br>";
            /// Actualizar el estado del viático a Rechazado
            $sql_query = "UPDATE viaticos SET Estado = 'Rechazado' WHERE Id = $id_viatico";
            if ($conn->query($sql_query) === TRUE) {
                echo "Estado del viático actualizado a _Rechazado_<br>";
            } else {
                echo "Error updating record: " . $conn->error;
            }
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
    
} elseif($Response == 'Prórroga'){
    /// Actualizar Aceptado por Control
    if ($Tipo_Usuario == 'Control'){
        $sql_query = "UPDATE verificacion SET Aceptado_Control = 'Prórroga' WHERE Id_Viatico = $id_viatico and Tipo = 'Comprobación'";
        if ($conn->query($sql_query) === TRUE) {
            echo "Aceptado_Control - Prórroga - Record updated successfully<br>";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } elseif ($Tipo_Usuario == 'Gerente'){
        $sql_query = "UPDATE verificacion SET Aceptado_Gerente = 'Prórroga' WHERE Id_Viatico = $id_viatico and Tipo = 'Comprobación'";
        if ($conn->query($sql_query) === TRUE) {
            echo "Aceptado_Gerente - Prórroga - Record updated successfully<br>";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }

    /// Actualizar el estado del viático a Prórroga
    $sql_query = "UPDATE viaticos SET Estado = 'Prórroga' WHERE Id = $id_viatico";
    if ($conn->query($sql_query) === TRUE) {
        echo "Estado del viático actualizado a _Prórroga_<br>";

    } else {
        echo "Error updating record: " . $conn->error;
    }
}


//// Verificar si ya se aceptaron ambas comprobaciones
$sql_query = "SELECT * FROM verificacion WHERE Id_Viatico = $id_viatico and Tipo = '$Tipo'";
$result = $conn->query($sql_query);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $Aceptado_Control = $row['Aceptado_Control'];
    $Aceptado_Gerente = $row['Aceptado_Gerente'];
    if ($Aceptado_Control == 'Aceptado' && $Aceptado_Gerente == 'Aceptado'){
        /// Actualizar el estado del viático a Completado
        $sql_query = "UPDATE viaticos SET Estado = 'Completado' WHERE Id = $id_viatico";
        if ($conn->query($sql_query) === TRUE) {
            echo "Viático completado<br>";
            if ($Tipo_Usuario == 'Control'){
                header("Location: ../../../../../src/Viaticos/ListadoViaticos.php");
            } elseif ($Tipo_Usuario == 'Gerente'){
                header("Location: ../../../../../src/Viaticos/ViaticosACargo.php");
            }
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } elseif ($Aceptado_Control == 'Rechazado' || $Aceptado_Gerente == 'Rechazado'){
        /// Actualizar el estado del viático a Rechazado
        $sql_query = "UPDATE viaticos SET Estado = 'Rechazado' WHERE Id = $id_viatico";
        if ($conn->query($sql_query) === TRUE) {
            echo "Viático rechazado<br>";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
} else {
    echo "Error: " . $sql_query . "<br>" . $conn->error;
}

if ($Tipo_Usuario == 'Control'){
    header("Location: ../../../../../src/Viaticos/ListadoViaticos.php");
} elseif ($Tipo_Usuario == 'Gerente'){
    header("Location: ../../../../../src/Viaticos/ViaticosACargo.php");
}

?>
<?php
require ('../../config/db.php');
session_start();
$Puesto = $_SESSION['Position'];
$Nombre = $_GET['name'];
$Id_Viatico = $_GET['id_viatico'];
/// Hacer entero
$Id_Viatico = (int) $Id_Viatico;
$concepto = $_GET['concepto'];
$concepto = $_GET['concepto'];
$concepto = trim($concepto, "'");
$source = $_GET['source'];

echo "Source :";
echo $source;
echo "<br>";

echo $concepto;
echo "Concepto: $concepto<br>";
echo "Puesto: $Puesto<br>";
echo "Nombre: $Nombre<br>";
echo "Id_Viatico: $Id_Viatico<br>";
if ($Puesto === 'Empleado') {
    /// Eliminar Imagen de la base de datos
    $DeleteImage_Query = "DELETE FROM imagen WHERE Nombre = ? AND Concepto = ? AND Id_Viatico = ?";
    $stmt = $conn->prepare($DeleteImage_Query);
    $stmt->bind_param("ssi", $Nombre, $concepto, $Id_Viatico);

    if ($stmt->execute()) {
        echo "Imagen eliminada correctamente";
        /// Eliminar Evidencias de la base de datos
        $DeleteEvidencias_Query = "DELETE FROM evidencias WHERE Nombre = ? AND Concepto = ? AND Id_Viatico = ?";
        $stmt = $conn->prepare($DeleteEvidencias_Query);
        $stmt->bind_param("ssi", $Nombre, $concepto, $Id_Viatico);

        if ($stmt->execute()) {
            echo "Evidencias eliminadas correctamente";
            // Eliminar Reembolso de la base de datos
            $DeleteReembolso_Query = "DELETE FROM reembolso WHERE Imagen = ? AND Concepto = ? AND Id_Viatico = ?";
            $stmt = $conn->prepare($DeleteReembolso_Query);
            $stmt->bind_param("ssi", $Nombre, $concepto, $Id_Viatico);

            if ($stmt->execute()) {
                echo "Reembolso eliminado correctamente";
                
                if($source == 'evidencias')
                    header('Location: ../../../../../src/Viaticos/evidencias.php?id_viatico=' . $Id_Viatico);
                elseif($source == 'revision')
                    header('Location: ../../../../../src/Control/detallesEvidencias.php?id_viatico=' . $Id_Viatico);
                    
                exit();
            } else {
                echo "Error: " . $DeleteReembolso_Query . "<br>" . $conn->error;
            }

        } else {
            echo "Error: " . $DeleteEvidencias_Query . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $DeleteImage_Query . "<br>" . $conn->error;
    }

    if($source == 'evidencias')
                    header('Location: ../../../../../src/Viaticos/evidencias.php?id_viatico=' . $Id_Viatico);
                elseif($source == 'revision')
                    header('Location: ../../../../../src/Control/detallesEvidencias.php?id_viatico=' . $Id_Viatico);
    exit();
} elseif ($Puesto === 'Control' || $Puesto === 'Gerente') {
    /// Eliminar Imagen de la base de datos

    echo "-----------------------------------<br>";
    echo "Nombre: $Nombre<br>";
    echo "Concepto: $concepto<br>";
    echo "Id_Viatico: $Id_Viatico<br>";
    echo "-----------------------------------<br>";

    $DeleteImage_Query = "DELETE FROM imagen WHERE Nombre = ? AND Concepto = ? AND Id_Viatico = ?";
    $stmt = $conn->prepare($DeleteImage_Query);
    $stmt->bind_param("ssi", $Nombre, $concepto, $Id_Viatico);

    if ($stmt->execute()) {
        echo "Imagen eliminada correctamente";
        /// Eliminar Evidencias de la base de datos
        $DeleteEvidencias_Query = "DELETE FROM evidencias WHERE Nombre = ? AND Concepto = ? AND Id_Viatico = ?";
        $stmt = $conn->prepare($DeleteEvidencias_Query);
        $stmt->bind_param("ssi", $Nombre, $concepto, $Id_Viatico);

        if ($stmt->execute()) {
            echo "Evidencias eliminadas correctamente";
            // Eliminar Reembolso de la base de datos
            $DeleteReembolso_Query = "DELETE FROM reembolso WHERE Imagen = ? AND Concepto = ? AND Id_Viatico = ?";
            $stmt = $conn->prepare($DeleteReembolso_Query);
            $stmt->bind_param("ssi", $Nombre, $concepto, $Id_Viatico);

            if ($stmt->execute()) {
                echo "Reembolso eliminado correctamente";
                if($source == 'evidencias')
                    header('Location: ../../../../../src/Viaticos/evidencias.php?id_viatico=' . $Id_Viatico);
                elseif($source == 'revision')
                    header('Location: ../../../../../src/Control/detallesEvidencias.php?id_viatico=' . $Id_Viatico);
                exit();
            } else {
                echo "Error: " . $DeleteReembolso_Query . "<br>" . $conn->error;
            }

        } else {
            echo "Error: " . $DeleteEvidencias_Query . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $DeleteImage_Query . "<br>" . $conn->error;
    }

    if($source == 'evidencias')
                    header('Location: ../../../../../src/Viaticos/evidencias.php?id_viatico=' . $Id_Viatico);
                elseif($source == 'revision')
                    header('Location: ../../../../../src/Control/detallesEvidencias.php?id_viatico=' . $Id_Viatico);
    exit();
}

header('Location: ../../../../../src/Control/detallesEvidencias.php?id_viatico=' . $Id_Viatico);



/*
if ($Puesto === 'Empleado') {
    echo "Entra como empleado";
    /// Eliminar Imagen de la base de datos
    $DeleteImage_Query = "DELETE FROM imagen WHERE Nombre = ? AND Concepto = ? AND Id_Viatico = ?";
    $stmt = $conn->prepare($DeleteImage_Query);
    $stmt->bind_param("ssi", $Nombre, $concepto, $Id_Viatico);

    if ($stmt->execute()) {
        echo "Imagen eliminada correctamente";
    } else {
        echo "Error: " . $DeleteImage_Query . "<br>" . $conn->error;
    }

} elseif ($Puesto === 'Control' || $Puesto === 'Gerente') {
    /// Eliminar Imagen de la base de datos

}




$deletImage_Query = "DELETE FROM imagen WHERE Nombre = '$Nombre' AND Concepto = '$concepto'";
if ($conn->query($deletImage_Query) === TRUE) {

    $deleteReembolso_Query = "DELETE FROM reembolso WHERE Imagen = '$Nombre' AND Concepto = '$concepto'";
    if ($conn->query($deleteReembolso_Query) === TRUE) {
        echo "Reembolso eliminado correctamente";
        // Eliminar registro de evidencias
        $deleteEvidencias_Query = "DELETE FROM evidencias WHERE Nombre = '$Nombre' AND Concepto = '$concepto'";
        if ($conn->query($deleteEvidencias_Query) === TRUE) {
            echo "Evidencias eliminadas correctamente";
        } else {
            echo "Error: " . $deleteEvidencias_Query . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $deleteReembolso_Query . "<br>" . $conn->error;
    }

    echo "Imagen eliminada correctamente";
    if ($source === "reembolso") {
        echo "Reembolso";
        header('Location: ../../../../../src/Viaticos/reembolso.php?id_viatico=' . $Id_Viatico);
    } elseif ($source === "detallesEvidencias") {
        echo "detallesEvidencias";
        header('Location: ../../../../../src/Control/detallesEvidencias.php?id_viatico=' . $Id_Viatico);
    } else {
        echo "Evidencias";
        header('Location: ../../../../../src/Viaticos/evidencias.php?id_viatico=' . $Id_Viatico);
    }

} else {
    echo "Error: " . $deletImage_Query . "<br>" . $conn->error;
}
?>*/
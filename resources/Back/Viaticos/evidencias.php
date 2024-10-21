<?php
require ('../../config/db.php');
session_start();

$Id_Viatico = $_GET['id_viatico'];
$Monto = $_POST['monto'];
$Descripcion = $_POST['descripcion'];
$Concepto = $_POST['concepto'];
$Name = $_SESSION['Name'];

echo "---------------- Datos Generales ----------------<br>";
echo "Id Viatico: $Id_Viatico<br>";
echo "Monto: $Monto<br>";
echo "Descripcion: $Descripcion<br>";
echo "Concepto: $Concepto<br>";
echo "Nombre: $Name<br>";
echo "---------------- ****  ----------------<br><br>";


$sql_query = "SELECT * FROM viaticos WHERE Id = $Id_Viatico";
$result = $conn->query($sql_query);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $Id_ViaticoInt = $row['Id'];
    $id_Usuario = $row['Id_Usuario'];
    $id_Gerente = $row['Id_Gerente'];
} else {
    echo "Error: " . $sql_query . "<br>" . $conn->error;
}
// Verifica si se ha enviado un archivo
if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
    $uploadDir = '../../../uploads/';
    $tempFile = $_FILES['file']['tmp_name'];
    $originalFileName = basename($_FILES['file']['name']);
    $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
    $fileMimeType = mime_content_type($tempFile);

    // Verificar el tipo MIME del archivo
    $allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $allowedPdfType = 'application/pdf';

    // Generar un nombre único para el archivo
    $fechaHora = date('Ymd_His');
    $newFileName = pathinfo($originalFileName, PATHINFO_FILENAME) . "_" . $fechaHora . "_" . uniqid() . '.' . $fileExtension;
    $newFilePath = $uploadDir . $newFileName;

    // Verificar si es una imagen
    if (in_array($fileMimeType, $allowedImageTypes)) {
        // Es una imagen
        if (move_uploaded_file($tempFile, $newFilePath)) {
            echo "Imagen subida correctamente.<br>";
        } else {
            echo "Error al mover la imagen.";
            exit;
        }
    } elseif ($fileMimeType == $allowedPdfType) {
        // Es un archivo PDF
        if (move_uploaded_file($tempFile, $newFilePath)) {
            echo "Archivo PDF subido correctamente.<br>";
        } else {
            echo "Error al mover el archivo PDF.";
            exit;
        }
    } else {
        echo "Tipo de archivo no soportado.";
        exit;
    }
}

echo "---------------- Nuevos datos del Archivo ----------------<br>";
echo "Nombre del archivo: $newFileName<br>";
echo "Monto: $Monto<br>";
echo "Concepto: $Concepto<br>";

// Verificar que no se tenga un registro con el mismo nombre, concepto y monto
$sql = "SELECT * FROM evidencias WHERE Nombre = '$newFileName' AND Concepto = '$Concepto' AND Monto = '$Monto'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo "Ya existe un registro con el mismo nombre, concepto y monto.";
    //header('Location: ../../../../../src/Viaticos/evidencias.php?id_viatico=' . $Id_ViaticoInt);
    exit;
} else {
    echo "No existe registro con el mismo nombre, concepto y monto.<br>";

    // Guardar la imagen en formato PNG y comprimirla - Registro en tabla Imagen
    $sql = "INSERT INTO imagen (id_Viatico, Id_Usuario, Id_Gerente, Nombre, Descripcion, Monto, Concepto)
    VALUES ('$Id_ViaticoInt', '$id_Usuario ', '$id_Gerente', '$newFileName', '$Descripcion', '$Monto', '$Concepto')";

    if ($conn->query($sql) === TRUE) {
        echo "Nueva Imagen registrada correctamente.<br>";
        // Registro en tabla Evidencias
        $sql = "INSERT INTO evidencias (Nombre, Concepto, Monto, Estado, Id_Viatico, Id_User)
        VALUES ('$newFileName', '$Concepto', '$Monto', 'Pendiente', '$Id_ViaticoInt', '$id_Usuario')";
        if ($conn->query($sql) === TRUE) {
            echo "Nueva Evidencia registrada correctamente.<br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    header('Location: ../../../../../src/Viaticos/evidencias.php?id_viatico=' . $Id_ViaticoInt);
}

$conn->close();
?>
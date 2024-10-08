<?php
require('../../config/db.php');
session_start();

$Tipo_Usuario = $_SESSION['Position'];
$Nombre_Usuario = $_SESSION['Name'];
$Id = $_POST['Id'];
$Monto = $_POST['Monto'];
$Concepto = $_POST['selectConcepto'];
$Destino = $_POST['Destino'];
$Concepto_Otro = $_POST['Concepto'];
$Destino = $_POST['Destino'];
$Fecha = $_POST['Fecha'];
$Nombre_Archivo = $_POST['Nombre_Archivo'];
$Descripcion = $_POST['Descripcion'];
$Nombre_Archivo_Actualizado = $_POST['file'];


echo "---------------- Carga y Procesamiento del Archivo ----------------<br>";
/// Carga y procesamiento de la imagen
$UploadDir = '../../../uploads/';
$tempFile = $_FILES['file']['tmp_name'];
$originalFileName = basename($_FILES['file']['name']);
$fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
$originalFile = $UploadDir . $originalFileName;

// Verificar el tipo MIME del archivo
$allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$allowedPdfType = 'application/pdf';
$fileMimeType = mime_content_type($tempFile);

// Variables para guardar la ruta del archivo y el tipo de archivo
$newFilePath = '';
$fileType = '';

if (in_array($fileMimeType, $allowedImageTypes)) {
    /// Cargando como imagen
    $fileType = 'image';
    $fechaHora = date('d.m.Y');
    $newFileName = pathinfo($originalFileName, PATHINFO_FILENAME). '-' . $fechaHora .  '.' .  $fileExtension;
    $newFilePath = $UploadDir . $newFileName;
    if (move_uploaded_file($tempFile, $newFilePath)) {
        echo "<br>Imagen subida correctamente.<br>";
    } else {
        echo "Error al mover la imagen.";
        exit;
    }

} elseif ($fileMimeType == $allowedPdfType) {
    // Es un archivo PDF
    $fileType = 'pdf';
    $fechaHora = date('d.m.Y');
    $newFileName = pathinfo($originalFileName, PATHINFO_FILENAME). '-'  . $fechaHora . '.' . $fileExtension;
    $newFilePath = $UploadDir . $newFileName;

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


echo "Nombre original: $originalFileName<br>";
echo "Tipo de archivo: $fileExtension<br>";
echo "Nuevo Nombre: $newFileName<br>";  
echo "<br> Id: " . $Id;
echo "<br> Monto: " . $Monto;
echo "<br> Concepto: " . $Concepto;
echo "<br> Destino: " . $Destino;
echo "<br> Fecha: " . $Fecha;
echo "<br> Nombre_Archivo: " . $Nombre_Archivo;
echo "<br> Descripcion: " . $Descripcion;

if ($Nombre_Archivo_Actualizado == null) {
    $Nombre_Archivo_Actualizado = $Nombre_Archivo;
}

if ($Concepto_Otro != null) {
    $Concepto = $Concepto_Otro;
}

// Actualizar el reembolso
$query = "UPDATE reembolsos SET Monto = '$Monto', Concepto = '$Concepto', Destino = '$Destino', Fecha = '$Fecha', Nombre_Archivo = '$newFileName', 
Descripcion = '$Descripcion' WHERE Id = '$Id'";

if ($conn->query($query) === TRUE) {
    echo "<br>Registro actualizado correctamente";
} else {
    echo "Error: " . $query . "<br>" . $conn->error;
}

header("Location: ../../../../../src/Reembolsos/editarReembolso.php?id=$Id");  




?>

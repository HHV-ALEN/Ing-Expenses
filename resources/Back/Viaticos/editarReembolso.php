<?php
ob_start(); // Inicia el buffer de salida
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/// Mostrar los datos de envio
echo "<pre>";
print_r($_POST);
echo "</pre>";

include ('../../config/db.php');
require '../../../vendor/autoload.php';

$id_reembolso = $_POST['id_reembolso'];
$Monto = $_POST['Monto'];
$Concepto = $_POST['Concepto'];
$Descr = $_POST['Descripcion'];
$Destino = $_POST['Destino'];
$ImagenOriginal = $_POST['NombreImagen'];

// Mostrar valores recibidos
echo "---------------- Datos Recibidos ----------------<br>";
echo "id_reembolso: $id_reembolso<br>";
echo "Monto: $Monto<br>";
echo "Concepto: $Concepto<br>";
echo "Descripcion: $Descr<br>";
echo "Destino: $Destino<br>";
echo "Imagen Original: $ImagenOriginal<br>";
echo "-----------------------------------------------<br>";

/// Gestionar Imagen / Documento
$uploadDir = '../../../uploads/';
$tempFile = $_FILES['file']['tmp_name'];
$originalFileName = basename($_FILES['file']['name']);
$fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
$originalFile = $uploadDir . $originalFileName;

// Variables para guardar la ruta del archivo y el tipo de archivo
$newFilePath = '';
$fileType = '';

// Verificar el tipo MIME del archivo
$allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$allowedPdfType = 'application/pdf';


// Verificar si se ha subido un archivo
if (!empty($tempFile) && is_uploaded_file($tempFile)) {
    // Verificar el tipo MIME del archivo
    $fileMimeType = mime_content_type($tempFile);

    if (in_array($fileMimeType, $allowedImageTypes)) {
        // Cargar como imagen
        $fileType = 'image';
        $fechaHora = date('d.m.Y');
        $newFileName = pathinfo($originalFileName, PATHINFO_FILENAME) . $fechaHora . '.' . $fileExtension;
        $newFilePath = $uploadDir . $newFileName;

        if (move_uploaded_file($tempFile, $newFilePath)) {
            echo "Imagen subida correctamente.<br>";
        } else {
            echo "Error al mover la imagen.";
            exit;
        }
    } elseif ($fileMimeType == $allowedPdfType) {
        // Es un archivo PDF
        $fileType = 'pdf';
        $fechaHora = date('d.m.Y');
        $newFileName = pathinfo($originalFileName, PATHINFO_FILENAME) . $fechaHora . '.' . $fileExtension;
        $newFilePath = $uploadDir . $newFileName;

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

    echo "---------------- Datos del Archivo ----------------<br>";
    echo "Nombre original: $originalFileName<br>";
    echo "Tipo de archivo: $fileExtension<br>";
    echo "Nuevo Nombre: $newFileName<br>";
    echo "-----------------------------------------------<br>";
} else {
    // No se ha subido ningún archivo, mantén el nombre del archivo original
    $newFileName = $ImagenOriginal;
}

// Actualizar los datos en BD
$updateQuery1 = "
    UPDATE reembolso 
    SET Monto = '$Monto', Concepto = '$Concepto', Descripcion = '$Descr', Destino = '$Destino',
    Imagen = '$newFileName' 
    WHERE Id = '$id_reembolso' AND Imagen = '$ImagenOriginal'
";
$fetchQueryRun1 = mysqli_query($conn, $updateQuery1);

// Verificar si se actualizó alguna fila
if (mysqli_affected_rows($conn) > 0) {
    echo "Actualización en reembolso realizada correctamente.";
    header('Location: ../../../src/Viaticos/detalleReembolso.php?id_reembolso=' . $id_reembolso);
} else {
    echo "Error en la consulta SQL: " . mysqli_error($conn);
    // No se actualizó ninguna fila en la primera tabla, intentar la segunda consulta
    $updateQuery2 = "
        UPDATE reembolsos_anidados 
        SET Monto = '$Monto', Concepto = '$Concepto', Descripcion = '$Descr', Destino = '$Destino',
        Imagen = '$newFileName' 
        WHERE Id = '$id_reembolso' AND Imagen = '$ImagenOriginal'
    ";
    $fetchQueryRun2 = mysqli_query($conn, $updateQuery2);

    if (mysqli_affected_rows($conn) > 0) {
        echo "Actualización en reembolsos_anidados realizada correctamente.";
        header('Location: ../../../src/Viaticos/detalleReembolso.php?id_reembolso=' . $id_reembolso);
    } else {
        echo "Error en la consulta SQL: " . mysqli_error($conn);
        echo "Error al actualizar los registros en ambas tablas: " . $conn->error;
    }
}

?>
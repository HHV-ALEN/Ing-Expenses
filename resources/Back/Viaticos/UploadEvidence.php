<?php 
include '../../config/db.php';
session_start();
$Nombre_Usuario = $_SESSION['Name'];

$monto = $_POST['monto'];
// Hacer la variable $descripcion a mayusculas
$descripcion = strtoupper($_POST['descripcion']);
$id_viatico = $_POST['id_viatico'];
$file = $_FILES['archivo'];
$fecha = date("Y-m-d H:i:s");
$concepto = $_POST['concepto'];

echo "<br> Fecha Registro: " . $fecha;
echo "<br> Solicitante: " . $Nombre_Usuario;
echo "<br> Nombre de Archivo: " . $file['name'];
echo "<br> Concepto: " . $concepto;
echo "<br> Monto: " . $monto;
echo "<br> Estado: Pendiente";
echo "<br> Id Relacionado: " . $id_viatico;
echo "<br> Tipo: Viatico";
ECHO "<br> ------------------- <br>";

// Verifica si se ha enviado un archivo
if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
    // Ruta del directorio de carga
    $uploadDir = realpath('../../../uploads/') . '/';
    
    // Verificar si el directorio de uploads existe
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true); // Crear el directorio si no existe
    }

    $tempFile = $_FILES['archivo']['tmp_name'];
    $originalFileName = basename($_FILES['archivo']['name']);
    $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
    $fileMimeType = mime_content_type($tempFile);

    // Verificar el tipo MIME del archivo
    $allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $allowedPdfType = 'application/pdf';

    // Generar un nombre único para el archivo (evitar caracteres no permitidos)
    $fecha = str_replace(':', '-', date('Y-m-d H-i-s')); // Reemplazar ':' y ' ' por guiones
    $newFileName = pathinfo($originalFileName, PATHINFO_FILENAME) . '.' . $fileExtension;
    $newFilePath = $uploadDir . $newFileName;
    echo "<br> Ruta del archivo: " . $newFilePath;

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



/// Agregar Evidencia a la tabla de evidencias
// Fecha_Registro | Solicitante | Nombre_Archivo | Concepto | Monto | Estado | Id_Relacionado | Tipo |
$Insert_Query = "INSERT INTO evidencias (Fecha_Registro, Solicitante, Nombre_Archivo, Concepto, Monto, Estado, Id_Relacionado, Tipo, Descripcion)
 VALUES ('$fecha', '$Nombre_Usuario', '$newFileName', '$concepto', '$monto', 'Pendiente', '$id_viatico', 'Evidencia', '$descripcion')";
$Insert = $conn->query($Insert_Query);
if ($Insert) {
    echo "<br> Evidencia Agregada";
    // Obtener el ultimo Id registrado en la tabla evidencias
    $id_evidencia = $conn->insert_id;
} else {
    echo "<br> Error al agregar la evidencia";
}

/// Agregar Evidencia a la tabla verificación
// Tipo = Evidencia | Id_Relacionado = $id_evidencia | Aceptado_Gerente = Pendiente | Aceptado_Control = Pendiente | Soliciante = $Nombre_Usuario
$Insert_Query = "INSERT INTO verificacion (Tipo, Id_Relacionado, Aceptado_Gerente, Aceptado_Control, Solicitante)
 VALUES ('Evidencia', '$id_evidencia', 'Pendiente', 'Pendiente', '$Nombre_Usuario')";
$Insert = $conn->query($Insert_Query);
if ($Insert) {
    echo "<br> Verificación Agregada";
} else {
    echo "<br> Error al agregar la verificación";
}




header('Location: ../../../src/Viaticos/SubirEvidencias.php?id=' . $id_viatico);



?>
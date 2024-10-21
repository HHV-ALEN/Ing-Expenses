<?php
ob_start(); // Inicia el buffer de salida
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

include ('../../config/db.php');
require '../../../vendor/autoload.php';

/// Obtener datos del formulario
$Id = $_SESSION['ID'];
$id_reembolso = $_POST['folio'];
$Monto = $_POST['quantity'];
$Concepto = $_POST['concept'];
$Descr = $_POST['desc'];
$Destino = $_POST['destino'];

/// Mostrar Información:
echo "ID: ".$id_reembolso."<br>";
echo "ID Usuario: ".$Id."<br>";
echo "Monto: ".$Monto."<br>";
echo "Concepto: ".$Concepto."<br>";
echo "Descripción: ".$Descr."<br>";
echo "Destino: ".$Destino."<br>";


$uploadDir = '../../../uploads/';
$tempFile = $_FILES['file']['tmp_name'];
$originalFileName = basename($_FILES['file']['name']);
$fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
$originalFile = $uploadDir . $originalFileName;

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
    $newFileName = pathinfo($originalFileName, PATHINFO_FILENAME) . $fechaHora . $originalFileName . '.' . $fileExtension;
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
    $newFileName = pathinfo($originalFileName, PATHINFO_FILENAME) . $fechaHora . $originalFileName . '.' . $fileExtension;
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

// Obtener el ID del gerente
$sql_getGerente = "SELECT u1.Id AS UsuarioId, u1.Nombre AS UsuarioNombre, u2.Id AS GerenteId, u2.Nombre AS GerenteNombre
FROM usuarios u1
INNER JOIN usuarios u2 ON u1.Gerente = u2.Nombre
WHERE u1.Id = $Id;";
$result = $conn->query($sql_getGerente);
$row = $result->fetch_assoc();
$IdGerente = $row['GerenteId'];

echo "ID Gerente: ".$IdGerente."<br>";


echo "<br>----------------- Datos para la inserción de un reembolso anidado -----------------------<br>";
echo "Monto: ".$Monto."<br>";
echo "Descripción: ".$Descr."<br>";
echo "Imagen: ".$newFileName."<br>";
echo "ID Usuario: ".$Id."<br>";
echo "ID Gerente: ".$IdGerente."<br>";
echo "Concepto: ".$Concepto."<br>";
echo "Destino: ".$Destino."<br>";
echo "Estado: Abierto<br>";
echo "Anidado: ".$id_reembolso."<br>";
echo "Fecha de Registro: ".date('Y-m-d H:i:s')."<br>";
echo "Id Reembolso: ".$id_reembolso."<br>";

echo "<br>----------------- Datos para la inserción de un reembolso anidado -----------------------<br>";
echo "<br> - ID_REEMBOLSO ANIDADO: - AUTOINCREMENTABLE -<br>";
echo "<br> - ID: ".$id_reembolso."<br>";
echo "<br> - MONTO: ".$Monto."<br>";
echo "<br> - DESCRIPCIÓN: ".$Descr."<br>";
echo "<br> - IMAGEN: ".$newFileName."<br>";
echo "<br> - ID_USUARIO: ".$Id."<br>";
echo "<br> - ID_GERENTE: ".$IdGerente."<br>";
echo "<br> - CONCEPTO: ".$Concepto."<br>";
echo "<br> - DESTINO: ".$Destino."<br>";
echo "<br> - ESTADO: - ABIERTO -<br>";
echo "<br> - ANIDADO: ".$id_reembolso."<br>";
echo "<br> - FECHA DE REGISTRO: ".date('Y-m-d H:i:s')."<br>";

$sql = "INSERT INTO reembolsos_anidados (Id, Monto, Descripcion, Imagen, Id_Usuario, Id_Gerente, Concepto, Destino, Estado, Anidado, Fecha_Registro)
VALUES ('$id_reembolso','$Monto', '$Descr', '$newFileName', '$Id', '$IdGerente','$Concepto', '$Destino', 'Abierto', '$id_reembolso', NOW())";
if ($conn->query($sql) === TRUE) {
    echo "Reembolso agregado correctamente.<br>";
    header ('Location: ../../../src/Control/detallesReembolso.php?id_reembolso='. $id_reembolso);
    
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

ob_end_flush(); // Envía el contenido del buffer de salida y lo apaga

?>
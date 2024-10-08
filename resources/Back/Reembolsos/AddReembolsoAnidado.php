<?php 
echo "------------- AddReembolsoAnidado.php -------------<br>";
require('../../config/db.php');
session_start();

// Información de La sesion:
echo "Usuario: ".$_SESSION['Name']."<br>";
$Nombre_Solicitante = $_SESSION['Name'];

/// Carga de información del formulario
$Monto = $_POST['Monto'];
$Concepto = $_POST['Concepto'];
$Cliente = $_POST['Cliente'];
$Concepto = strtoupper($Concepto);
$Destino = $_POST['Destino'];
$Fecha = $_POST['Fecha'];
$Descripcion = $_POST['Descripcion'];
$selectConcepto = $_POST['selectConcepto'];
$Id_Reembolso = $_POST['Id_Reembolso'];
$Orden_Venta = $_POST['ordenVenta'];
$Codigo_Prefix = $_POST['codigoPrefix'];
$Codigo_Proyecto = $_POST['codigo'];
$Nombre_Proyecto = strtoupper($_POST['nombreProyecto']);
$Codigo_Completo = $Codigo_Prefix."-".$Codigo_Proyecto;

if($selectConcepto == 'Otro'){
    // Si el concepto es otro, se toma el valor del input en mayusculas
    $Concepto = strtoupper($_POST['Concepto']);
} else {
    $Concepto = $selectConcepto;
}

echo "<br> Monto: $Monto <br>";
echo "<br> Concepto: $Concepto <br>";
echo "<br> Destino: $Destino <br>";
echo "<br> Fecha: $Fecha <br>";
echo "<br> Descripcion: $Descripcion <br>";
echo "<br>Concepto: $Concepto<br>";
echo "<br>Cliente: $Cliente<br>";
echo "<br>Orden de Venta: $Orden_Venta<br>";
echo "<br>Codigo Prefix: $Codigo_Prefix<br>";
echo "<br>Codigo: $Codigo_Proyecto<br>";
echo "<br>Nombre Proyecto: $Nombre_Proyecto<br>";
echo "<br>Codigo Completo: $Codigo_Completo<br>";


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

// Insertar en la base de datos en la tabla reembolsos_anidados
$query = "INSERT INTO reembolsos_anidados (Id_Reembolso, Solicitante, Cliente, Concepto, Monto, Destino, Fecha, Descripcion, Estado, Nombre_Archivo, Nombre_Proyecto, Codigo, Orden_Venta)
VALUES ('$Id_Reembolso', '$Nombre_Solicitante', '$Cliente' ,'$Concepto', '$Monto', '$Destino', '$Fecha', '$Descripcion', 'Abierto', '$newFileName', '$Nombre_Proyecto', '$Codigo_Completo ', '$Orden_Venta') ";
if($conn->query($query) === TRUE){
    echo "Datos insertados correctamente en la tabla reembolsos_anidados.<br>";
} else {
    echo "Error al insertar datos en la tabla reembolsos_anidados: " . $conn->error;
    exit;
}

/// obtener el id del reembolso anidado
$Id_Reembolso_Anidado = $conn->insert_id;
echo "Id del reembolso anidado: $Id_Reembolso_Anidado<br>";

/// Insertar en la tabla de verificacion
$query = "INSERT INTO verificacion (Tipo, Id_Relacionado, Aceptado_Gerente, Aceptado_Control, Solicitante)
VALUES ('Reembolso_Anidado', '$Id_Reembolso_Anidado', 'Pendiente', 'Pendiente', '$Nombre_Solicitante')";
if($conn->query($query) === TRUE){
    echo "Datos insertados correctamente en la tabla verificacion.<br>";
} else {
    echo "Error al insertar datos en la tabla verificacion: " . $conn->error;
    exit;
}

header ("Location: ../../../../../src/Reembolsos/ReembolsoAnidado.php?id=$Id_Reembolso");

?>
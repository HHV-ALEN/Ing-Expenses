<?php
require ('../../config/db.php');
session_start();

echo "<pre>";
print_r($_POST);
echo "</pre>";

$Id_User = $_POST['id_user'];
$Monto = $_POST['quantity'];
$Concepto = $_POST['concept'];
$Descripcion = $_POST['Descripcion'];
$Destino = $_POST['Destino'];


// Obtener Nombre de Gerente
$getGerente = "SELECT * FROM usuarios WHERE Id = $Id_User";
$resultGerente = $conn->query($getGerente);
if ($resultGerente->num_rows > 0) {
    $rowGerente = $resultGerente->fetch_assoc();
    $Gerente = $rowGerente['Gerente'];
    /// Obtener ID DEL GERENTE
    $getGerente = "SELECT * FROM usuarios WHERE Nombre = '$Gerente'";
    $resultGerente = $conn->query($getGerente);
    if ($resultGerente->num_rows > 0) {
        $rowGerente = $resultGerente->fetch_assoc();
        $Id_Gerente = $rowGerente['Id'];
    } else {
        echo "Error: " . $getGerente . "<br>" . $conn->error;
    }
} else {
    echo "Error: " . $getGerente . "<br>" . $conn->error;
}

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

$fechaHora = date('d.m.Y');

// Insertar Registro de Reembolso en la Base de Datos
$Sql_Query = "INSERT INTO reembolso (Monto, Descripcion, Imagen, Id_Usuario, Id_Gerente, Estado, Concepto, Destino, Fecha_Registro)
VALUES ('$Monto', '$Descripcion', '$newFileName', '$Id_User', '$Id_Gerente','Abierto', '$Concepto', '$Destino', '$fechaHora')";
if ($conn->query($Sql_Query) === TRUE) {
    echo "Registro de Reembolso creado exitosamente";
} else {
    echo "Error: " . $Sql_Query . "<br>" . $conn->error;
}

// Insertar la Imagen en tabla imagen
$Sql_Query = "INSERT INTO imagen (Id_Usuario, Id_Gerente, Nombre, Descripcion, Monto, Concepto)
VALUES ('$Id_User', '$Id_Gerente', '$newFileName', '$Descripcion', '$Monto', '$Concepto')";
if ($conn->query($Sql_Query) === TRUE) {
    echo "Registro de Imagen creado exitosamente";
} else {
    echo "Error: " . $Sql_Query . "<br>" . $conn->error;
}

// Obtener el último ID de reembolso
$query = "SELECT MAX(Id) AS id FROM reembolso";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$id_Reembolso = $row['id'];

$query_verificación = "INSERT INTO verificacion(Aceptado_Control, Aceptado_Gerente, Id_Viatico, Id_Reembolso, Solicitante)
                    VALUES ('Pendiente', 'Pendiente', '0', '$id_Reembolso', '$Id_User')";
if ($conn->query($query_verificación) === TRUE) {
    echo "Registro de Verificación creado exitosamente";
} else {
    echo "Error: " . $query_verificación . "<br>" . $conn->error;
}

header('Location: ../Mail/Reembolso.php?id_usuario=' . $Id_User . '&id_gerente=' . $Id_Gerente . '&id_reembolso=' . $id_Reembolso . '');

/// Insertar 

// Mover Archivo a la Carpeta uploads





?>


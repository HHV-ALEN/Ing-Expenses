<?php 
echo "------------- AddReembolso.php -------------<br>";
require('../../config/db.php');
session_start();

// Información de La sesion:
echo "Usuario: ".$_SESSION['Name']."<br>";
$Nombre_Solicitante = $_SESSION['Name'];

/// Carga de información del formulario
$Monto = $_POST['Monto'];
$Concepto = $_POST['Concepto'];
$Destino = $_POST['Destino'];
$Fecha = $_POST['Fecha'];
$Descripcion = $_POST['Descripcion'];
$selectConcepto = $_POST['selectConcepto'];
$Cliente = $_POST['Cliente'];
$Orden_Venta = $_POST['ordenVenta'];
$Codigo_Prefix = $_POST['codigoPrefix'];
$Codigo_Proyecto = $_POST['codigo'];
$Nombre_Proyecto = strtoupper($_POST['nombreProyecto']);


if($selectConcepto == 'Otro'){
    // Si el concepto es otro, se toma el valor del input en mayusculas
    $Concepto = strtoupper($_POST['Concepto']);
} else {
    $Concepto = $selectConcepto;
}

$Codigo_Completo = $Codigo_Prefix."-".$Codigo_Proyecto;

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


// --- Insertar en Reembolsos ---
echo "<br>************** Insertando datos en la base de datos -- Tabla: Reembolsos  ***** <br>";

$Insert_Reembolsos = "INSERT INTO reembolsos (Solicitante, Cliente, Concepto, Monto, Destino, Fecha, Descripcion, Estado, Nombre_Archivo, Nombre_Proyecto, Codigo, Orden_Venta)
VALUES ('$Nombre_Solicitante', '$Cliente', '$Concepto', '$Monto', '$Destino', '$Fecha', '$Descripcion', 'Abierto', '$newFileName', '$Nombre_Proyecto', '$Codigo_Completo', '$Orden_Venta')";

// Verificar que la consulta está correctamente construida
echo "Consulta SQL: $Insert_Reembolsos<br>";

// Ejecutar la consulta de inserción
$Result_Reembolsos = mysqli_query($conn, $Insert_Reembolsos);

if ($Result_Reembolsos) {
    echo "<br>Reembolso registrado correctamente";
} else {
    // Mostrar el error de SQL si la inserción falla
    echo "<br>Error al registrar la verificación: " . mysqli_error($conn);
}
echo "<br>-------------------------------------------------------------------------<br>";

/// Obtener el Ultimo ID registrado en la tabla reembolsos
// -------- Insertar en la BD --------
$Id_Reembolso = "SELECT MAX(Id) FROM reembolsos";
$Id_Reembolso = $conn->query($Id_Reembolso);
if ($Id_Reembolso->num_rows > 0) {
    $row = $Id_Reembolso->fetch_assoc();
    $Id_Reembolso = $row['MAX(Id)'];
} else {
    echo "Error: " . $Id_Reembolso . "<br>" . $conn->error;
}

echo "<br>Id Reembolso: $Id_Reembolso <br>";

/// ---- Insertar En Verificación
    /// Crear registro de verificaicón:
        // Id - Id_Viatico - Aceptado_Control - Aceptado_Gerente - Solicitante
        
        echo "<br>************** Insertando datos en la base de datos -- Tabla: Verificacion  ***** <br>";
        $Insert_Verificacion = "INSERT INTO verificacion (Tipo, Id_Relacionado, Aceptado_Control, Aceptado_Gerente, Solicitante)
        VALUES ('Reembolso', $Id_Reembolso,'Pendiente', 'Pendiente', '".$_SESSION['Name']."')";
        $Result_Verificacion = mysqli_query($conn, $Insert_Verificacion);
        if($Result_Verificacion){
            echo "<br>Verificación registrada correctamente";
        }else{
            echo "<br>Error al registrar la verificación";
        }
        echo "<br>-------------------------------------------------------------------------<br>";
    
        header("Location: ../Mail/NewReembolso.php?Id=$Id_Reembolso"); 
        

        header('Location: ../../../../../src/Reembolsos/ReembolsoAnidado.php?id='.$Id_Reembolso.'');
?>
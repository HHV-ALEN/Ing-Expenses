<?php
require ('../../config/db.php');
session_start();
$id_viatico = $_GET['id_viatico'];


$Monto = $_POST['quantity'];
$Descripcion = $_POST['desc'];
$Concepto = $_POST['concept'];

echo "Monto: $Monto<br>";
echo "Descripcion: $Descripcion<br>";
echo "Concepto: $Concepto<br>";
echo "Id_Viatico: $id_viatico  <br>";

$sql_query = "SELECT * FROM viaticos WHERE Id = $id_viatico ";
$result = $conn->query($sql_query);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $Id_ViaticoInt = $row['Id'];
    $id_Usuario = $row['Id_Usuario'];
    $id_Gerente = $row['Id_Gerente'];
} else {
    echo "Error: " . $sql_query . "<br>" . $conn->error;
}


if (!isset($_GET['status']) || $_GET['status'] != 'correo_enviado') {

    echo "---------------------------------------------------";
    echo "Id_ViaticoInt: $Id_ViaticoInt<br>";

    $getGerente = "SELECT * FROM usuarios WHERE Id = $id_Gerente";
    $resultGerente = $conn->query($getGerente);
    if ($resultGerente->num_rows > 0) {
        $rowGerente = $resultGerente->fetch_assoc();
        $Gerente = $rowGerente['Nombre'];
        $CorreoGerente = $rowGerente['Correo'];
    } else {
        echo "Error: " . $getGerente . "<br>" . $conn->error;
    }

    echo "Id_ViaticoInt: $Id_ViaticoInt<br>";
    echo "id_Usuario: $id_Usuario<br>";
    echo "id_Gerente: $id_Gerente<br>";
    echo "Gerente: $Gerente<br>";
    echo "CorreoGerente: $CorreoGerente<br>";
    echo "-----------------------------------<br>";



    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $uploadDir = '../../../uploads/';
        $tempFile = $_FILES['file']['tmp_name'];
        $originalFileName = basename($_FILES['file']['name']);
        $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
        $originalFile = $uploadDir . $originalFileName;

        echo "---------------- Datos del Archivo ----------------<br>";
        echo "Nombre original: $originalFileName<br>";
        echo "Tipo de archivo: $fileExtension<br>";

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

        echo "---------------- Nuevos datos del Archivo ----------------<br>";
        echo "Nombre del archivo: $newFileName<br>";
        echo "Monto: $Monto<br>";
        echo "Concepto: $Concepto<br>";

        $sql = "INSERT INTO imagen (id_Viatico, Id_Usuario, Id_Gerente, Nombre, Descripcion, Monto, Concepto) 
        VALUES ('$Id_ViaticoInt', '$id_Usuario ', '$id_Gerente', '$newFileName', '$Descripcion', '$Monto', 'Reembolso')";
        if ($conn->query($sql) === TRUE) {
            echo "Nueva Imagen registrada correctamente.<br>";
            // Registro en tabla Evidencias
            $sql = "INSERT INTO evidencias (Nombre, Concepto, Monto, Estado, Id_Viatico, Id_User)
            VALUES ('$newFileName', '$Concepto', '$Monto', 'Pendiente', '$Id_ViaticoInt', '$id_Usuario')";

            if ($conn->query($sql) === TRUE) {

                echo "Nueva Evidencia registrada correctamente.<br>";
                $Query_Reembolso = "INSERT INTO reembolso (Monto, Descripcion, Imagen, Id_Viatico, Id_Usuario, Id_Gerente, Estado, Concepto, Fecha_Registro)
                VALUES ('$Monto', '$Descripcion', '$newFileName', '$Id_ViaticoInt', '$id_Usuario', '$id_Gerente', 'Abierto', '$Concepto', NOW())";
                if ($conn->query($Query_Reembolso) === TRUE) {
                    echo "Reembolso registrado correctamente.";
                    // Obtener el último ID de reembolso
                    $query = "SELECT MAX(Id) AS id FROM reembolso";
                    $result = $conn->query($query);
                    $row = $result->fetch_assoc();
                    $id_Reembolso = $row['id'];

                    $query_verificación = "INSERT INTO verificacion(Aceptado_Control, Aceptado_Gerente, Id_Viatico, Id_Reembolso, Solicitante)
                    VALUES ('Pendiente', 'Pendiente', '$Id_ViaticoInt', '$id_Reembolso', '$id_Usuario')";
                    if ($conn->query($query_verificación) === TRUE) {
                        header('Location: ../Mail/Reembolso.php?id_usuario=' . $id_Usuario . '&id_gerente=' . $id_Gerente . '&id_viatico=' . $Id_ViaticoInt . '&id_reembolso=' . $id_Reembolso . '');
                        echo "Verificación registrada correctamente.";
                    } else {
                        echo "Error: " . $query_verificación . "<br>" . $conn->error;
                    }




                }

            }
        }

    } else {
        echo "Error al guardar la imagen.";
    }

} else {
    echo "No se ha recibido ningún archivo.";
}

/*


if (!in_array($mimetype, $allowedTypes)) {
    echo 'El tipo de archivo no es válido.';
}
/// Crear directorio si no existe
if (move_uploaded_file($file_temp_name, $target_file)) {
    echo "El archivo se ha subido correctamente.";

    $sql = "INSERT INTO imagen (Id_Viatico, Id_Usuario, Id_Gerente, Nombre, Descripcion, Monto, Concepto) 
VALUES ('$Id_ViaticoInt', '$id_Usuario ', '$id_Gerente', '$nuevoNombre', 'Reembolso', '$Monto', '$Concepto')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";

        $query_Reembolso = "INSERT INTO reembolso (Monto, Descripcion, Imagen, Id_Viatico, Id_Usuario, Id_Gerente,Estado,Concepto) 
    VALUES ('$Monto', '$Descripcion', '$nuevoNombre','$Id_ViaticoInt', '$id_Usuario', '$id_Gerente','Abierto','$Concepto')";
        if ($conn->query($query_Reembolso) === TRUE) {

            //oBTENER EL ultimo ID de reembolso
            $query = "SELECT MAX(Id) AS id FROM reembolso";
            $result = $conn->query($query);
            $row = $result->fetch_assoc();
            $id_Reembolso = $row['id'];

            $query_verificación = "INSERT INTO verificacion(Id_Reembolso, Solicitante)
            VALUES ('$id_Reembolso','$id_Usuario')";
            if ($conn->query($query_verificación) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $query_verificación . "<br>" . $conn->error;
            }

            echo "New record created successfully";
        } else {
            echo "Error: " . $query_Reembolso . "<br>" . $conn->error;
        }

        header('Location: ../../../../../src/Viaticos/reembolso.php?id_viatico=' . $Id_ViaticoInt . ' ');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

}
header('Location: ../Mail/Reembolso.php?id_usuario=' . $id_Usuario . '&id_gerente=' . $id_Gerente . '');

} else {
echo 'No se ha recibido ningún archivo.';
header('Location: ../../../../../src/Viaticos/reembolso.php?id_viatico=' . $Id_ViaticoInt . ' ');
}

?>*/
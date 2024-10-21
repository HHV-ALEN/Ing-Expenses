<?php
require ('../../config/db.php');
session_start();
$id_viatico = $_GET['id_viatico'];
// Pasar a entero
$id_viatico_int = (int) $id_viatico;
$Concepto = $_POST['concepto'];
$Descripcion = $_POST['descripcion'];
$Monto = $_POST['monto'];

$sql_query = "SELECT * FROM viaticos WHERE Id = $id_viatico";
$result = $conn->query($sql_query);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $Id_ViaticoInt = $row['Id'];
    $id_Usuario = $row['Id_Usuario'];
    $id_Gerente = $row['Id_Gerente'];
} else {
    echo "Error: " . $sql_query . "<br>" . $conn->error;
}


if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $uploadDir = '../../../uploads/';
    /// Verifica si es una imagen
    $originalFile = ($_FILES['image']['name']);
    $check = getimagesize($_FILES['image']['tmp_name']);
    // Si es un archivo pdf
    if ($check === false) {
        echo "Check: $check";

        $originalFile = $uploadDir . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($originalFile, PATHINFO_EXTENSION));
        $NewName = $Concepto . $Descripcion . $Monto . $Id_ViaticoInt;
        $newFileName = pathinfo($NewName, PATHINFO_FILENAME) . '.pdf';
        $newFilePath = $uploadDir . $newFileName;

        // Guardar documento dentro de la carpeta uploads
        if (move_uploaded_file($_FILES['image']['tmp_name'], $newFilePath)) {
            echo "Documento subido exitosamente.";
            $sql = "INSERT INTO imagen (id_Viatico, Id_Usuario, Id_Gerente, Nombre, Descripcion, Monto, Concepto) 
            VALUES ('$Id_ViaticoInt', '$id_Usuario ', '$id_Gerente', '$newFileName', '$Descripcion', '$Monto', '$Concepto')";

            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
                header('Location: ../../../../../src/Viaticos/evidencias.php?id_viatico=' . $Id_ViaticoInt);
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Error al mover el archivo.";
        }
    } else {


        $uploadDir = '../../../uploads/';
        $originalFile = $uploadDir . basename($_FILES['image']['name']);
        $tempFile = $_FILES['image']['tmp_name'];
        $imageFileType = strtolower(pathinfo($originalFile, PATHINFO_EXTENSION));

        // Mover el archivo subido a la carpeta de destino
        if (move_uploaded_file($_FILES['image']['tmp_name'], $originalFile)) {
            // Crear la imagen en memoria según su tipo
            switch ($imageFileType) {
                case 'jpeg':
                    $sourceImage = @imagecreatefromjpeg($originalFile);
                    echo $sourceImage;
                    echo "SourceImage: $sourceImage<br>";
                    break;
                case 'jpg':
                    echo "Nombre original: $originalFile<br>";
                    echo "Tipo de archivo: $imageFileType<br>";
                    break;
                case 'png':
                    $sourceImage = imagecreatefrompng($originalFile);
                    break;
                case 'gif':
                    $sourceImage = imagecreatefromgif($originalFile);
                    break;
                case 'webp':
                    $sourceImage = imagecreatefromwebp($originalFile);
                    break;
                default:
                    echo "Tipo de archivo no soportado.";
                    exit;
            }

            // Reducir las dimensiones de la imagen si es muy grande

            // Verificar si la imagen se cargó correctamente

            if ($sourceImage === false) {
                echo "Error al cargar la imagen.";
                exit;
            }

            $maxWidth = 800;
            $maxHeight = 800;
            $width = imagesx($sourceImage);
            $height = imagesy($sourceImage);

            if ($width > $maxWidth || $height > $maxHeight) {
                $ratio = min($maxWidth / $width, $maxHeight / $height);
                $newWidth = (int) ($width * $ratio);
                $newHeight = (int) ($height * $ratio);
                $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
                imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagedestroy($sourceImage);
                $sourceImage = $resizedImage;
            }

            // Crear el nuevo nombre de archivo con la extensión deseada (ejemplo: png)
            echo "Nombre original: $originalFile<br>";
            $NewName = $Concepto . $Descripcion . $Monto . $Id_ViaticoInt;
            echo "NombrePrueba: $NewName<br>";

            $newFileName = pathinfo($NewName, PATHINFO_FILENAME) . '.png';
            $newFilePath = $uploadDir . $newFileName;

            // Guardar la imagen en el nuevo formato y comprimirla
            if (imagepng($sourceImage, $newFilePath, 9)) { // Nivel de compresión 6 (0-9)
                echo "Imagen convertida y guardada como $newFileName";

                $sql = "INSERT INTO imagen (id_Viatico, Id_Usuario, Id_Gerente, Nombre, Descripcion, Monto, Concepto) 
            VALUES ('$Id_ViaticoInt', '$id_Usuario ', '$id_Gerente', '$newFileName', '$Descripcion', '$Monto', '$Concepto')";

                if ($conn->query($sql) === TRUE) {
                    echo "New record created successfully";
                    header('Location: ../../../../../src/Viaticos/evidencias.php?id_viatico=' . $Id_ViaticoInt);
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "Error al guardar la imagen.";
            }
            // Liberar memoria
            imagedestroy($sourceImage);
            // Eliminar el archivo original
            unlink($originalFile);
        } else {
            echo "Error al mover el archivo.";
        }
    }
} else {
    echo "No se ha subido ningún archivo.";
}


?>
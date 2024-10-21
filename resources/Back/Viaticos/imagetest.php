<?php
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $uploadDir = '../../../uploads/';
    $tempFile = $_FILES['image']['tmp_name'];
    $originalFileName = basename($_FILES['image']['name']);
    $imageFileType = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
    $originalFile = $uploadDir . $originalFileName;

    echo "Nombre original: $originalFileName<br>";
    echo "Tipo de archivo: $imageFileType<br>";

    // Verificar si el archivo es realmente una imagen
    $check = getimagesize($tempFile);
    if ($check === false) {
        echo "El archivo no es una imagen válida.";
        exit;
    }

    // Verificar el tipo de archivo
    if ($imageFileType == 'jpeg' || $imageFileType == 'jpg') {
        echo "Imagen jpeg o jpg<br>";

        // Mover el archivo a la carpeta de destino
        if (move_uploaded_file($tempFile, $originalFile)) {
            echo "Archivo movido correctamente.<br>";

            // Crear la imagen en memoria
            $sourceImage = @imagecreatefromjpeg($originalFile);

            // Verificar si la imagen se cargó correctamente
            if ($sourceImage === false) {
                echo "Error al cargar la imagen.";
                exit;
            } else {
                echo "Imagen cargada correctamente.<br>";
            }

            // Crear el nuevo nombre de archivo con la extensión .png
            $newFileName = pathinfo($originalFileName, PATHINFO_FILENAME) . '.png';
            $newFilePath = $uploadDir . $newFileName;

            // Guardar la imagen en formato PNG y comprimirla
            if (imagepng($sourceImage, $newFilePath, 6)) { // Nivel de compresión 0-9
                echo "Imagen convertida y guardada como $newFileName<br>";

                // Liberar memoria
                imagedestroy($sourceImage);

                // Eliminar el archivo original
                unlink($originalFile);
            } else {
                echo "Error al guardar la imagen.";
            }
        } else {
            echo "Error al mover el archivo.";
        }
    } else {
        // Para otros tipos de archivo, simplemente mueve el archivo subido
        if (move_uploaded_file($tempFile, $originalFile)) {
            echo "Archivo movido correctamente a $originalFile<br>";
        } else {
            echo "Error al mover el archivo.";
        }
    }
} else {
    echo "No se ha subido ningún archivo o hubo un error en la carga.";
}
?>
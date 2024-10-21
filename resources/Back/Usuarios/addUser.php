<?php
require ('../../config/db.php');
session_start();

$Name = $_POST['nombre'];
$Password = $_POST['password'];
$Mail = $_POST['correo'];
$Manager = $_POST['gerente'];
$Sucursal = $_POST['sucursal'];
$Position = $_POST['puesto'];

echo $_SESSION['ID'];
$EncryptedPassword = md5($Password);

// Verificar que la el usuario este activo con SESSION
$sql_Query = "INSERT INTO usuarios (Nombre, Password, Correo, Estado, Gerente, Puesto, Sucursal) 
    VALUES ('$Name', '$EncryptedPassword', '$Mail', 'Activo', '$Manager', '$Position', '$Sucursal');";
$result = $conn->query($sql_Query);
if ($result) {
    echo "Usuario agregado correctamente";
} else {
    echo "Error al agregar usuario";
}

// Obtener el ID del usuario
$sql_Query = "SELECT * FROM usuarios WHERE Nombre = '$Name'";
$result = $conn->query($sql_Query);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $id_Usuario = $row['Id'];
} else {
    echo "Error: " . $sql_Query . "<br>" . $conn->error;
}


// Verifica si se ha enviado un archivo
if (isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $file_temp_name = $file['tmp_name'];
    // Obtén información sobre el archivo
    $file = $_FILES['file'];
    $fileName = $file['name'];
    $mimetype = $file['type'];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $file_ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $fechaHora = date('d.m.Y.H.i');

    /// Proofs
    echo "Nombre del archivo: $fileName<br>";
    $sinExtension = substr($fileName, 0, -4);
    echo "Tipo de archivo: $mimetype<br>";
    echo "file_temp_name: $file_temp_name<br>";
    echo "Fecha y hora: $fechaHora<br> ";
    echo "Extensión: $file_ext<br>";

    // Generar un número aleatorio entre 97 y 122, que corresponden a los códigos ASCII de las letras minúsculas
    $random_ascii = rand(97, 122);

    // connvertir el número aleatorio en una letra utilizando la función chr()
    $random_letter = chr($random_ascii);


    /// Nuevo nombre
    $nuevoNombre = $sinExtension . $random_letter . '_' . $fechaHora . '.' . $file_ext;
    echo "Nuevo nombre: $nuevoNombre<br>";

    /// Ruta completa
    $target_dir = '../../../uploads/';
    $target_file = $target_dir . $nuevoNombre;

    echo "Ruta completa: $target_file<br>";


    if (!in_array($mimetype, $allowedTypes)) {
        echo 'El tipo de archivo no es válido.';
    }


    /// Crear directorio si no existe
    if (move_uploaded_file($file_temp_name, $target_file)) {
        echo "El archivo se ha subido correctamente.";

        $sql = "INSERT INTO imagen ( Id_Usuario, Nombre, Descripcion) 
        VALUES ('$id_Usuario ', '$nuevoNombre', 'Imagen-Perfil')";

        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
} else {
    echo 'No se ha recibido ningún archivo.';
}
header('Location: ../../../../src/Admin/Usuarios.php');

$conn->close();
?>
<?php
require('../config/db.php');

session_set_cookie_params([
    'lifetime' => 420, // 7 minutos
    'path' => '/',
    'secure' => true, // Asegúrate de que tu sitio esté usando HTTPS
    'httponly' => true,
    'samesite' => 'Strict'
]);

session_start();

$username = $_POST['username'];
$password = $_POST['password'];

$EncryptedPassword = md5($password);

$sql_Query = "SELECT * FROM usuarios WHERE Usuario = '$username' AND Password = '$EncryptedPassword' AND Estado = 'Activo'";
$result = $conn->query($sql_Query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['ID'] = $row['Id'];
    $_SESSION['Name'] = $row['Nombre'];
    $_SESSION['User'] = $row['Usuario'];
    $_SESSION['Position'] = $row['Puesto'];
    $_SESSION['Mail'] = $row['Correo'];
    $_SESSION['Status'] = $row['Estado'];
    $_SESSION['Manager'] = $row['Gerente'];

    // Redirigir al dashboard después de iniciar sesión correctamente
    header('Location: ../../../../src/dashboard.php');
    exit();
} else {
    // Si el usuario o contraseña no son correctos
    header('Location: /index.php?error=1');
    exit();
}

$conn->close();

?>
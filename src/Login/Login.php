<?php
require ('../../resources/config/db.php');

session_set_cookie_params([
    'lifetime' => 420, // 1 hora
    'path' => '/',
    'domain' => '',
    'secure' => true, // Asegúrate de que tu sitio esté usando HTTPS
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();
$username = $_POST['username'];
$password = $_POST['password'];

$EncryptedPassword = md5($password);

$sql_Query = "SELECT * FROM usuarios WHERE Nombre = '$username' AND Password = '$EncryptedPassword' AND Estado = 'Activo';";
$result = $conn->query($sql_Query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['ID'] = $row['Id'];
    $_SESSION['Name'] = $row['Nombre'];
    $_SESSION['Position'] = $row['Puesto'];
    $_SESSION['Mail'] = $row['Correo'];
    $_SESSION['Status'] = $row['Estado'];
    $_SESSION['Manager'] = $row['Gerente'];
    $_SESSION['Sucursal'] = $row['Sucursal'];
} else {
    echo "Usuario o contraseña incorrectos";
    header('Location: ../../index.php?error=1');
    exit();
}

switch ($_SESSION['Position']) {
    case 'Admin':
        header('Location: ../Admin/Admin.php');
        break;
    case 'Empleado':
        header('Location: ../Users/index.php');
        break;
    case 'Control':
        header('Location: ../Users/index.php');
        break;
    case 'Gerente':
        header('Location: ../Users/index.php');
        break;
    default:
        # code...
        break;
}

$conn->close();
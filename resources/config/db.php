<?php
/// Variables para el entorno Local:
/// Definir las constantes de la base de datos

//define('DB_SERVER', 'localhost');
//define('DB_USERNAME', 'root');
//define('DB_PASSWORD', '');
//define('DB_NAME', 'u553178725_AlenViaticos');

/// Entorno de producción
//define('DB_SERVER', '127.0.0.1:3306');
//define('DB_USERNAME', 'u553178725_root');
//define('DB_PASSWORD', 'k8NcWZooN&');
//define('DB_NAME', 'u553178725_Viaticos2');

/// - Entorno Publico
define('DB_SERVER', '127.0.0.1:3306');
define('DB_USERNAME', 'u617278495_Alen');
define('DB_PASSWORD', 'Alen.2024');
define('DB_NAME', 'u617278495_AlenViaticos');

/// Conectar a la base de datos
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

/// Verificar la conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



?>

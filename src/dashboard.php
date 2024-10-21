<?php
session_start(); // Asegúrate de que esto esté al principio, antes de cualquier HTML

// Verificar si las variables de sesión existen
if (!isset($_SESSION['Name']) || !isset($_SESSION['Position'])) {
    // Redirigir al usuario al inicio de sesión si no hay sesión activa
    header('Location: /index.php?error=1');
    exit();
}

// Obtener datos de la sesión
$Nombre = $_SESSION['Name'];
$Position = $_SESSION['Position'];

// Incluye la conexión a la base de datos si es necesario
include '../resources/config/db.php';

// Incluye navbar después de definir las variables de sesión
include 'navbar.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Alen Viáticos</title>
    <link rel="shortcut icon" href="/resources/img/logo-icon.png" />
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="../resources/Css/Main-Card.css">
</head>

<body>
    
    
    <div class="bg">
    <br>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- User Information Card -->
                <div class="card">
                    <div class="card-header card-header-custom">
                        <h5 class="card-title text-center"><i class="fas fa-user"></i> Información General </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6"> 
                                <p class="text-center"><strong>Nombre:</strong> </p>
                                <p class="text-center"><?php echo $_SESSION['Name'] ?></p>
                                <p class="text-center"><strong>Puesto:</strong> </p>
                                <p class="text-center"><?php echo $_SESSION['Position'] ?></p>
                            </div>
                            <div class="col-md-6"> 
                                <p class="text-center"><strong>Email:</strong></p>
                                <p class="text-center"><?php echo $_SESSION['Mail'] ?></p>

                                <p class="text-center"><strong>Gerente:</strong></p>
                                <p class="text-center"><?php echo $_SESSION['Manager'] ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <br>
                </div>
                </div>
                
            </div>
        </div>
    </div>
    </div>
    
    <!-- Bootstrap JS and dependencies (Popper.js and jQuery) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

<!-- 

 <div class="card">
                <div class="card-header card-header-custom">
                    <h5 class="card-title"><i class="fas fa-user"></i> Resumen del Usuario</h5>
                </div>
                <div class="card-body">
                    <p class="card-text"><strong>Nombre:</strong> <?php echo $_SESSION['Name'] ?></p>
                    <p class="card-text"><strong>Email:</strong> <?php echo $_SESSION['Mail'] ?></p>
                    <p class="card-text"><strong>Puesto:</strong> <?php echo $_SESSION['Position'] ?></p>
                </div>
            </div>
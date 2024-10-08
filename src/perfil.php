<?php
session_start();
$Nombre = $_SESSION['Name'];

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

    <!-- Incluye esto en el <head> de cada archivo -->
        <link rel="stylesheet" href="../resources/Css/Main-Card.css">

</head>

<body>
    <?php include 'navbar.php'; ?>
    
    <div class="bg">
    <br>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- User Information Card -->
                <div class="card">
                    <div class="card-header card-header-custom">
                        <h5 class="card-title text-center"><i class="fas fa-user"></i> <?php echo $_SESSION['Name'] ?></h5>
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
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card-inside">
                                <div class="card-main-custom">
                                    <h5 class="card-title text-center text-success mb-2"><i class="fas fa-user-cog"></i> Administración de Usuarios</h5>
                                    <!-- Botón que redirige a la página y abre el modal -->
                                    <button id="btnAddUser" type="button" class="btn btn-outline-success btn-block">
                                        Agregar Usuario
                                    </button>
                                </div>
                            </div>       
                        </div>
                        <div class="col-md-6">
                            <div class="card-inside">
                                <div class="card-main-custom">

                                    <h5 class="card-title text-center text-success mb-2"><i class="fas fa-user-cog"></i> Administración de Usuarios</h5>
                                    
                                    <button type="button" class="btn btn-outline-success btn-block mt-2">Agregar Usuario</button>
                                </div>
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

    <script>
        // Script para manejar el click del botón
        document.getElementById('btnAddUser').addEventListener('click', function() {
            // Redirigir a la página Usuarios.php con el parámetro openModal=true
            window.location.href = '/Admin/Usuarios.php?openModal=true';
        });

        

    </script>
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
<?php
require ('../resources/config/db.php');
session_start();
if (!isset($_SESSION['ID'])) {
    // La sesión ha caducado o el usuario no ha iniciado sesión
    session_unset(); // Elimina todas las variables de sesión
    session_destroy(); // Destruye la sesión

    header('Location: ../index.php'); // Redirige al formulario de inicio de sesión
    exit();
}



$Nombre = $_SESSION['Name'];
$Id_Usuario = $_SESSION['ID'];

$image_Query = "SELECT * FROM imagen WHERE Id_Usuario = $Id_Usuario AND Descripcion = 'Imagen-Perfil'";
$result = $conn->query($image_Query);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $Imagen = $row['Nombre'];
} else {
    $Imagen = "No-Image.png";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Alen Viáticos</title>
    <link rel="shortcut icon" href="/resources/img/logo-icon.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        body,
        html {
            height: 100%;
            margin: 0;
        }

        .bg {
            background-image: url('../../resources/img/montain-bg.jpg');
            min-height: 100vh;
            /* Asegura que la altura mínima cubra el 100% del viewport */
            background-repeat: no-repeat;
            background-size: cover;
            /* Ajusta la imagen para que se vea completa */
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 20px;
        }

        .card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
        }

        .card-header-custom {
            background-color: #3b4ba1;
            color: white;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table th,
        .table td {
            text-align: center;
        }

        @media (max-width: 991.98px) {
            .navbar-nav .nav-link {
                text-align: center;
                border-bottom: 1px solid #e9ecef;
                padding: 10px 0;
                width: 100%;
            }

            .navbar-nav .nav-link:last-child {
                border-bottom: none;
            }

            .navbar-nav .dropdown-divider {
                display: none;
            }

            .navbar-collapse {
                display: flex;
                flex-direction: column;
                align-items: center;
                width: 100%;
            }

            .navbar-nav {
                width: 100%;
            }
        }

        @media (max-width: 575.98px) {

            .table th,
            .table td {
                white-space: nowrap;
            }
        }
    </style>
</head>

<body>
    <!-- Inicio de la barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a href="Users/index.php"><img src="../../resources/img/Alen.png" alt="ALEN Viáticos" class="img-fluid"
                    style="padding: 5px; height: 47px;"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <?php
                    if ($_SESSION['Position'] == 'Admin') {
                        echo '
                        <li class="nav-item">
                            <a class="nav-link" href="Admin/Usuarios.php">Usuarios</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" href="Admin/reportes.php">Reportes</a>
                        </li>';
                    } elseif ($_SESSION['Position'] == 'Control') {
                        echo '
                        <li class="nav-item">
                            <a class="nav-link" href="Viaticos/solicitar.php">Solicitar Viáticos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Viaticos/ListadoViaticos.php">Listado Viáticos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Viaticos/misViaticos.php">Mis Viáticos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Viaticos/reembolsar.php">Solicitar Reembolso</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Control/listadoReembolsos.php">Reembolsos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Viaticos/verificacionEvidencias.php">Verificación de Evidencias</a>
                        </li>';
                    } elseif ($_SESSION['Position'] == 'Empleado') {
                        echo '
                        <li class="nav-item">
                            <a class="nav-link" href="Viaticos/solicitar.php">Solicitar Viáticos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Viaticos/misViaticos.php">Mis Viáticos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Viaticos/reembolsar.php">Solicitar Reembolso</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Viaticos/misReembolsos.php">Mis Reembolsos</a>
                        </li>';
                    } elseif ($_SESSION['Position'] == 'Gerente') {
                        echo '
                        <li class="nav-item">
                            <a class="nav-link" href="Viaticos/solicitar.php">Solicitar Viáticos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Viaticos/misViaticos.php">Mis Viáticos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Viaticos/reembolsar.php">Solicitar Reembolso</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="Viaticos/misReembolsos.php">Mis Reembolsos</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                A mi cargo
                            </a>
                            <ul class="dropdown-menu text-center" aria-labelledby="navbarDropdown">
                                <li class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="Viaticos/ViaticosACargo.php">Solicitudes</a></li>
                                <li class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="Viaticos/enEvidencia.php">Evidencias</a></li>
                                <li class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="Viaticos/reembolsosACargo.php">Reembolsos</a></li>
                                <li class="dropdown-divider"></li>
                            </ul>
                        </li>';
                    }
                    ?>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="perfil.php"><?php echo $_SESSION['Name'] ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Salir</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Fin de la barra de navegación -->


    <div class="bg">
        <div class="container mt-2">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <!-- User Information Card -->
                    <div class="card card-custom">
                        <div class="card-header card-header-custom">
                            <h4 class="text-center"><?php echo $_SESSION['Name']; ?></h4>
                            <p class="text-center"><strong>ID : </strong><?php echo $_SESSION['ID']; ?></p>
                        </div>
                        <!-- Mostrar Imagene de perfil -->
                        <div class="card-body text-center">
                            <img src="../uploads/<?php echo $Imagen ?>" alt="User Image"
                                class="img-fluid rounded-circle">
                        </div>
                        <div class="card-body">
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="email">Email:</label>
                                    <input type="email" class="form-control" id="email"
                                        value="<?php echo $_SESSION['Mail']; ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="email">Sucursal:</label>
                                    <input type="email" class="form-control" id="email"
                                        value="<?php echo $_SESSION['Sucursal']; ?>" readonly>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="position">Puesto:</label>
                                    <input type="text" class="form-control" id="position"
                                        value="<?php echo $_SESSION['Position']; ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="position">Gerente:</label>
                                    <input type="text" class="form-control" id="manager"
                                        value="<?php echo $_SESSION['Manager']; ?>" readonly>
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>
                    <br>
                    <!-- Action Buttons Card -->
                </div>
            </div>
        </div>
        <br> <br>
    </div>


    <!-- Bootstrap JS and dependencies (Popper.js and jQuery) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>


</html>
<?php
require ('../../resources/config/db.php');
session_start();

if (!isset($_SESSION['ID'])) {
    // La sesión ha caducado o el usuario no ha iniciado sesión
    session_unset(); // Elimina todas las variables de sesión
    session_destroy(); // Destruye la sesión

    header('Location: ../index.php'); // Redirige al formulario de inicio de sesión
    exit();
}
$Nombre = $_SESSION['Name'];

$MontoTotalHospedaje_query = "SELECT SUM(Hospedaje) as MontoTotalHospedaje FROM viaticos ";
$MontoTotalHospedaje_result = mysqli_query($conn, $MontoTotalHospedaje_query);
$MontoTotalHospedaje = mysqli_fetch_assoc($MontoTotalHospedaje_result);


$MontoTotalAlimentacion_query = "SELECT SUM(Alimentacion) as MontoTotalAlimentacion FROM viaticos ";
$MontoTotalAlimentacion_result = mysqli_query($conn, $MontoTotalAlimentacion_query);
$MontoTotalAlimentacion = mysqli_fetch_assoc($MontoTotalAlimentacion_result);


$MontoTotalGasolina_query = "SELECT SUM(Gasolina) as MontoTotalGasolina FROM viaticos ";
$MontoTotalGasolina_result = mysqli_query($conn, $MontoTotalGasolina_query);
$MontoTotalGasolina = mysqli_fetch_assoc($MontoTotalGasolina_result);


$MontoTotalCasetas_query = "SELECT SUM(Casetas) as MontoTotalCasetas FROM viaticos ";
$MontoTotalCasetas_result = mysqli_query($conn, $MontoTotalCasetas_query);
$MontoTotalCasetas = mysqli_fetch_assoc($MontoTotalCasetas_result);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Administración</title>
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
            background-image: url('../../resources/img/FONDONEGRO.png');
            height: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
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
    </style>
</head>

<body>
    <!-- Inicio de la barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a href="../Users/index.php"><img src="../../resources/img/Alen.png" alt="ALEN Viáticos" class="img-fluid"
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
                            <a class="nav-link" href="../Admin/Usuarios.php">Usuarios</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" href="reportes.php">Reportes</a>
                        </li>';
                    }
                    ?>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../perfil.php"><?php echo $_SESSION['Name'] ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../index.php">Salir</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Fin de la barra de navegación -->

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <!-- User Information Card -->
                <div class="card card-custom">
                    <div class="card-header card-header-custom">
                        <h5 class="card-title"><i class="fas fa-user"></i> Resumen: </h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><strong>Nombre:</strong> <?php echo $_SESSION['Name'] ?></p>
                        <p class="card-text"><strong>Email:</strong> <?php echo $_SESSION['Mail'] ?></p>
                        <p class="card-text"><strong>Puesto:</strong> <?php echo $_SESSION['Position'] ?></p>
                    </div>
                </div>
                <br>
                <!-- Action Buttons Card -->
                <div class="card card-custom">
                    <div class="card-body text-center">
                        <h3>Usuarios del sistema:</h3>
                        <button onclick="location.href='Usuarios.php'" type="button" class="btn btn-primary"><i
                                class="fas fa-cog"></i>Ir al listado</button>
                    </div>
                </div>
                <br>
                <!-- Result Card -->
                <div class="card card-custom">
                    <div class="card-header card-header-custom">
                        <h5 class="card-title"><i class="fas fa-calculator"></i> Resultados: </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="card-text"><strong>Monto Total Hospedaje:</strong>
                                    <?php echo $MontoTotalHospedaje['MontoTotalHospedaje'] ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="card-text"><strong>Monto Total Alimentación:</strong>
                                    <?php echo $MontoTotalAlimentacion['MontoTotalAlimentacion'] ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="card-text"><strong>Monto Total Gasolina:</strong>
                                    <?php echo $MontoTotalGasolina['MontoTotalGasolina'] ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="card-text"><strong>Monto Total Casetas:</strong>
                                    <?php echo $MontoTotalCasetas['MontoTotalCasetas'] ?></p>
                            </div>
                        </div>
                    </div>
                    <!-- Chart Card -->
                    <div class="card card-custom">
                        <div class="card-header card-header-custom">
                            <h5 class="card-title"><i class="fas fa-chart-bar"></i> Gráfico: </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="chart"></canvas>
                        </div>
                    </div>

                    <!-- Chart Script -->
                    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.6.0/dist/chart.min.js"></script>
                    <script>
                        // Get the data for the chart
                        var montoHospedaje = <?php echo $MontoTotalHospedaje['MontoTotalHospedaje'] ?>;
                        var montoAlimentacion = <?php echo $MontoTotalAlimentacion['MontoTotalAlimentacion'] ?>;
                        var montoGasolina = <?php echo $MontoTotalGasolina['MontoTotalGasolina'] ?>;
                        var montoCasetas = <?php echo $MontoTotalCasetas['MontoTotalCasetas'] ?>;

                        // Create the chart
                        var ctx = document.getElementById('chart').getContext('2d');
                        var myChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: ['Hospedaje', 'Alimentacion', 'Gasolina', 'Casetas'],
                                datasets: [{
                                    label: 'Monto Total',
                                    data: [montoHospedaje, montoAlimentacion, montoGasolina, montoCasetas],
                                    backgroundColor: [
                                        'rgba(255, 99, 132, 0.2)',
                                        'rgba(54, 162, 235, 0.2)',
                                        'rgba(255, 206, 86, 0.2)',
                                        'rgba(75, 192, 192, 0.2)'
                                    ],
                                    borderColor: [
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 206, 86, 1)',
                                        'rgba(75, 192, 192, 1)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    </script>
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
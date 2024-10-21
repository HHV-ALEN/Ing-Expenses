<?php
require_once '../../resources/config/db.php';
session_start();
$Nombre = $_SESSION['Name'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Paso 2: Consulta PHP para obtener los datos filtrados
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $sql = "SELECT u.Sucursal, COUNT(v.Id_Usuario) as total_solicitudes
    FROM viaticos v
    INNER JOIN usuarios u ON v.Id_Usuario = u.Id
    WHERE v.Fecha_Regreso BETWEEN '$start_date' AND '$end_date'
    GROUP BY u.Sucursal";
    $result = $conn->query($sql);

    $sucursales = [];
    $totales = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sucursales[] = $row['Sucursal'];
            $totales[] = $row['total_solicitudes'];
        }
    } else {
        echo "No se encontraron resultados.";
    }


    # Suma de conceptos total

    #---------------- Suma de Alimentos ----------------#
    $Alimentos_Query = "SELECT SUM(Alimentacion) AS TotalAlimentos FROM viaticos
    WHERE Fecha_Regreso BETWEEN '$start_date' AND '$end_date'";
    $Alimentos = $conn->query($Alimentos_Query);
    $Alimentos = $Alimentos->fetch_assoc();
    $TotalAlimentos = $Alimentos['TotalAlimentos'];

    #---------------- Suma de Hospedaje ----------------#
    $Hospedaje_Query = "SELECT SUM(Hospedaje) AS TotalHospedaje FROM viaticos
       WHERE Fecha_Regreso BETWEEN '$start_date' AND '$end_date'";
    $Hospedaje = $conn->query($Hospedaje_Query);
    $Hospedaje = $Hospedaje->fetch_assoc();
    $TotalHospedaje = $Hospedaje['TotalHospedaje'];

    #---------------- Suma de Gasolina ----------------#
    $Gasolina_Query = "SELECT SUM(Gasolina) AS TotalGasolina FROM viaticos
       WHERE Fecha_Regreso BETWEEN '$start_date' AND '$end_date'";
    $Gasolina = $conn->query($Gasolina_Query);
    $Gasolina = $Gasolina->fetch_assoc();
    $TotalGasolina = $Gasolina['TotalGasolina'];

    #---------------- Suma de Casetas ----------------#
    $Casetas_Query = "SELECT SUM(Casetas) AS TotalCasetas FROM viaticos
       WHERE Fecha_Regreso BETWEEN '$start_date' AND '$end_date'";
    $Casetas = $conn->query($Casetas_Query);
    $Casetas = $Casetas->fetch_assoc();
    $TotalCasetas = $Casetas['TotalCasetas'];

    #---------------- Suma de Vuelos ----------------#
    $Vuelos_Query = "SELECT SUM(Vuelos) AS TotalVuelos FROM viaticos
       WHERE Fecha_Regreso BETWEEN '$start_date' AND '$end_date'";
    $Vuelos = $conn->query($Vuelos_Query);
    $Vuelos = $Vuelos->fetch_assoc();
    $TotalVuelos = $Vuelos['TotalVuelos'];

    #---------------- Suma de Transporte ----------------#
    $Transporte_Query = "SELECT SUM(Transporte) AS TotalTransporte FROM viaticos
       WHERE Fecha_Regreso BETWEEN '$start_date' AND '$end_date'";
    $Transporte = $conn->query($Transporte_Query);
    $Transporte = $Transporte->fetch_assoc();
    $TotalTransporte = $Transporte['TotalTransporte'];


    #---------------- Suma de Estacionamiento ----------------#
    $Estacionamiento_Query = "SELECT SUM(Estacionamiento) AS TotalEstacionamiento FROM viaticos
       WHERE Fecha_Regreso BETWEEN '$start_date' AND '$end_date'";
    $Estacionamiento = $conn->query($Estacionamiento_Query);
    $Estacionamiento = $Estacionamiento->fetch_assoc();
    $TotalEstacionamiento = $Estacionamiento['TotalEstacionamiento'];



    #---------------- Destinos más visitados ----------------#
// Ejecutar la consulta
    $sql = "SELECT Destino, COUNT(Destino) AS Frecuencia FROM viaticos
     WHERE Fecha_Regreso BETWEEN '$start_date' AND '$end_date'
     GROUP BY Destino ORDER BY Frecuencia DESC LIMIT 5";

    $result = $conn->query($sql);

    // Preparar los datos para Chart.js
    $estados = [];
    $frecuencias = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $estados[] = $row['Destino'];
            $frecuencias[] = $row['Frecuencia'];
        }
    } else {
        //echo "0 resultados";
    }

    /// Usuario con más solicitudes

    $data1 = [
        'labels' => ['Alimentacion', 'Hospedaje', 'Gasolina', 'Casetas', 'Vuelos', 'Transporte', 'Estacionamiento'],
        'values' => [$TotalAlimentos, $TotalHospedaje, $TotalGasolina, $TotalCasetas, $TotalVuelos, $TotalTransporte, $TotalEstacionamiento]
    ];



    /// Monto total por sucursales en el intervalo de tiempo
    $sql = "SELECT u.Sucursal, SUM(v.Total) as total_monto
    FROM viaticos v
    INNER JOIN usuarios u ON v.Id_Usuario = u.Id
    WHERE v.Fecha_Regreso BETWEEN '$start_date' AND '$end_date'
    GROUP BY u.Sucursal";
    $result = $conn->query($sql);

    $sucursalesTotal = [];
    $totalesxSucursals = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sucursalesTotal[] = $row['Sucursal'];
            $totalesxSucursals[] = $row['total_monto'];
        }
    } else {
        echo "No se encontraron resultados.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes</title>
    <link rel="shortcut icon" href="../../resources/img/logo-icon.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .card-custom {
            margin-bottom: 20px;
        }

        .card-header-custom {
            background-color: #f8f9fa;
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
    
    <?php 
    
        // Ejecutar la consulta
    $sql = "SELECT * FROM usuarios";
    $result = $conn->query($sql);

    // Inicializar una variable para almacenar las opciones
    $options = "";

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $options .= "<option value='" . $row['Id'] . "'>" . $row['Nombre'] . "</option>";
        }
    } else {
        $options = "<option>No hay usuarios registrados</option>";
    }
    ?>

    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Resumen del personal:</h5>
            </div>
            <form action="descargarResumen.php" method="post">
                <div class="card-body">
                    <label for="exampleFormControlSelect1">Selecciona un usuario</label>
                    <select name="Usuario" class="form-control" id="exampleFormControlSelect1">
                        <?php echo $options; ?>
                    </select>
                    <br>
                    <button type="submit" class="btn btn-outline-success">Descargar</button>
                </div>

            </form>
        </div>
        <div class="container mt-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Intervalos:</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="start_date">Fecha de Inicio:</label>
                                <input class="form-control" type="date" id="start_date" name="start_date" required>
                            </div>
                            <div class="col-md-6">
                                <label for="end_date">Fecha de Fin:</label>
                                <input class="form-control" type="date" id="end_date" name="end_date" required>
                                <br>
                            </div>
                        </div>
                        <button class="btn btn-outline-primary" type="submit">Filtrar</button>
                    </form>

                </div>
            </div>


            <br>
            <div class="row">
                <!-- First Row -->
                <div class="col-md-6">
                    <!-- Chart Card 1 -->
                    <div class="card card-custom">
                        <div class="card-header card-header-custom">
                            <h5 class="card-title"><i class="fas fa-chart-bar"></i> Por Conceptos</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="chart1"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <!-- Chart Card 2 -->
                    <div class="card card-custom">
                        <div class="card-header card-header-custom">
                            <h5 class="card-title"><i class="fas fa-chart-bar"></i>Estados Más Visitados</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <!-- First Row -->
                <div class="col-md-6">
                    <!-- Chart Card 1 -->
                    <div class="card card-custom">
                        <div class="card-header card-header-custom">
                            <h5 class="card-title"><i class="fas fa-chart-bar"></i>Solicitudes por Sucursal
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="myChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Second Row -->
                <div class="col-md-6">
                    <!-- Chart Card 2 -->
                    <div class="card card-custom">
                        <div class="card-header card-header-custom">
                            <h5 class="card-title"><i class="fas fa-chart-bar"></i>Total por Sucursal
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="TotalXSucursal" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Chart Script -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js@3.6.0/dist/chart.min.js"></script>
             <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

            <script>
                // Datos para los gráficos
                var data1 = <?php echo json_encode($data1); ?>;

                // Crear gráficos
                var ctx1 = document.getElementById('chart1').getContext('2d');
                var myChart1 = new Chart(ctx1, {
                    type: 'bar',
                    data: {
                        labels: data1.labels,
                        datasets: [{
                            label: 'Monto por Concepto',
                            data: data1.values,
                            backgroundColor: 'rgba(0, 164, 255, 0.2)',
                            borderColor: 'rgba(61, 92, 112, 1)',
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

                // Obtener datos de PHP
                var estados = <?php echo json_encode($estados); ?>;
                var frecuencias = <?php echo json_encode($frecuencias); ?>;

                // Crear el gráfico
                var ctx = document.getElementById('chart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: estados,
                        datasets: [{
                            label: 'Frecuencia de Visitas',
                            data: frecuencias,
                            backgroundColor: 'rgba(107, 185, 237, 0.2)',
                            borderColor: 'rgba(79, 142, 184, 1)',
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

                // Paso 3: Generación del gráfico utilizando Chart.js
                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: <?php echo json_encode($sucursales); ?>,
                        datasets: [{
                            label: 'Solicitudes por Sucursal',
                            data: <?php echo json_encode($totales); ?>,
                            backgroundColor: 'rgba(24, 107, 161, 0.2)',
                            borderColor: 'rgba(77, 118, 145, 1)',
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

                var ctx = document.getElementById('TotalXSucursal').getContext('2d');
                var TotalXSucursal = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: <?php echo json_encode($sucursalesTotal); ?>,
                        datasets: [{
                            label: 'Total por Sucursal',
                            data: <?php echo json_encode($totalesxSucursals); ?>,
                            backgroundColor: 'rgba(133, 182, 214, 0.2)',
                            borderColor: 'rgba(85, 94, 99, 1)',
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
</body>

</html>
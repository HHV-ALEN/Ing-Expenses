<?php
session_start();
include ('../../resources/config/db.php');
if (!isset($_SESSION['ID'])) {
    // La sesión ha caducado o el usuario no ha iniciado sesión
    session_unset(); // Elimina todas las variables de sesión
    session_destroy(); // Destruye la sesión

    header('Location: ../../index.php'); // Redirige al formulario de inicio de sesión
    exit();
}
$id_user = $_SESSION['ID'];
$Puesto = $_SESSION['Position'];


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Lista de reembolsos</title>
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
            <a href="../Users/index.php"><img src="../../resources/img/Alen.png" alt="ALEN Viáticos" class="img-fluid"
                    style="padding: 5px; height: 47px;"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <?php
                    if ($_SESSION['Position'] == 'Admin' || $_SESSION['Position'] == 'Control') {
                        echo '
                        <li class="nav-item">
                            <a class="nav-link" href="../Viaticos/solicitar.php">Solicitar Viáticos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Viaticos/ListadoViaticos.php">Listado Viáticos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Viaticos/misViaticos.php">Mis Viáticos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Viaticos/reembolsar.php">Solicitar Reembolso</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Control/listadoReembolsos.php">Reembolsos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Viaticos/verificacionEvidencias.php">Verificación de Evidencias</a>
                        </li>';
                    } elseif ($_SESSION['Position'] == 'Empleado') {
                        echo '
                        <li class="nav-item">
                            <a class="nav-link" href="../Viaticos/solicitar.php">Solicitar Viáticos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Viaticos/misViaticos.php">Mis Viáticos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Viaticos/reembolsar.php">Solicitar Reembolso</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Viaticos/misReembolsos.php">Mis Reembolsos</a>
                        </li>';
                    } elseif ($_SESSION['Position'] == 'Gerente') {
                        echo '
                        <li class="nav-item">
                            <a class="nav-link" href="../Viaticos/solicitar.php">Solicitar Viáticos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Viaticos/misViaticos.php">Mis Viáticos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Viaticos/reembolsar.php">Solicitar Reembolso</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="../Viaticos/misReembolsos.php">Mis Reembolsos</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                A mi cargo
                            </a>
                            <ul class="dropdown-menu text-center" aria-labelledby="navbarDropdown">
                                <li class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="../Viaticos/ViaticosACargo.php">Solicitudes</a></li>
                                <li class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="../Viaticos/enEvidencia.php">Evidencias</a></li>
                                <li class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="../Viaticos/reembolsosACargo.php">Reembolsos</a></li>
                                <li class="dropdown-divider"></li>
                            </ul>
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
    <br>
    <!-- Fin de la barra de navegación -->
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-custom">
                    <div class="card-header card-header-custom">
                        <h4 class="text-center">Listado de Reembolsos</h4>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="text-align:center">Nombre de usuario</th>
                                        <th style="text-align:center">Folio de Solicitud</th>
                                        <th style="text-align:center">Monto</th>
                                        <th style="text-align:center">Destino</th>
                                        <th style="text-align:center">Estado</th>
                                        <th style="text-align:center">Información</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                               // Conexión a la base de datos (asumiendo que ya está establecida en $conn)

// Configuración de la paginación
$records_per_page = 10; // Número de registros por página
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Página actual
$offset = ($page - 1) * $records_per_page; // Cálculo del offset

// Consulta para contar el número total de registros que cumplen las condiciones
$sql_count = "
SELECT COUNT(*) as total
FROM reembolso
WHERE Id_Viatico = 0 
AND Estado NOT IN ('Rechazado', 'Completado')
";
$result_count = $conn->query($sql_count);
$total_records = $result_count->fetch_assoc()['total'];

// Consulta SQL con LIMIT y OFFSET para paginación
$sql_query = "
SELECT r.*, u.nombre AS nombre_usuario
FROM reembolso r
INNER JOIN usuarios u ON r.Id_Usuario = u.Id
WHERE r.Id_Viatico = 0 
AND r.Estado NOT IN ('Rechazado', 'Completado')
ORDER BY r.Id DESC
LIMIT $offset, $records_per_page
";
$result = $conn->query($sql_query);

// Calcular el número total de páginas
$total_pages = ceil($total_records / $records_per_page);


                                

                                // Mostrar los registros en una tabla
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $id_reembolso = $row['Id'];
                                        echo "<tr>";
                                        echo "<td>" . $row['nombre_usuario'] . "</td>";
                                        echo "<td>" . $row['Id'] . "</td>";
                                        
                                        //echo "<td>" . ($row['Id_Viatico'] == 0 ? 'N/A' : $row['Id_Viatico']) . "</td>";
                                        /// Consultar la suma total de los montos en este reembolso
                                        // El Query para sumar los montos de ambas tablas
                                        $query = "
                                            SELECT SUM(MontoTotal) AS MontoTotalGeneral
                                            FROM (
                                                SELECT Monto AS MontoTotal
                                                FROM reembolso
                                                WHERE Id = '$id_reembolso'
                                                
                                                UNION ALL
                                                
                                                SELECT Monto AS MontoTotal
                                                FROM reembolsos_anidados
                                                WHERE Id = '$id_reembolso'
                                            ) AS Montos";

                                            // Ejecutar el query
                                            $resultado = $conn->query($query);

                                            if ($resultado) {
                                                $row2 = $resultado->fetch_assoc();
                                                $montoTotal = $row2['MontoTotalGeneral'];
                                                echo "<td>" . number_format($montoTotal, 2) . " $</td>";
                                            } else {
                                                echo "Error al calcular el monto total: " . $conn->error;
                                            }
                                        
                                        //echo "<td>" . ($row['Monto'] == 0 ? 'N/A' : $row['Monto']) . "</td>";


                                        if ($row['Destino'] == '') {
                                            echo "<td>N/A</td>";
                                        } else {
                                            echo "<td>" . $row['Destino'] . "</td>";
                                        }
                                        echo "<td>" . $row['Estado'] . "</td>";
                                        echo "<td><a href='detallesReembolso.php?id_reembolso=" . $row['Id'] . "' class='btn btn-info'>Ver Información</a></td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8'>No hay viáticos registrados.</td></tr>";
                                }
                                // Generar enlaces de paginación
                                echo '</tbody></table></div>';
                                echo '<nav><ul class="pagination justify-content-center">';
                                if ($page > 1) {
                                    echo '<li class="page-item"><a class="page-link" href="?page=' . ($page - 1) . '" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
                                }
                                for ($i = 1; $i <= $total_pages; $i++) {
                                    $active_class = ($i == $page) ? 'active' : '';
                                    echo '<li class="page-item ' . $active_class . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                                }
                                if ($page < $total_pages) {
                                    echo '<li class="page-item"><a class="page-link" href="?page=' . ($page + 1) . '" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
                                }
                                echo '</ul></nav>';
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- Paginación -->

                    </div>



                </div>
            </div>
        </div>
    </div>
    <br>
    <br>

    <!-- Bootstrap JS and dependencies (Popper.js and jQuery) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>
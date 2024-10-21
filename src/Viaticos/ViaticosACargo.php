<?php
include ('../actualizarVerificacion.php');
session_start();

if (!isset($_SESSION['ID'])) {
    // La sesión ha caducado o el usuario no ha iniciado sesión
    session_unset(); // Elimina todas las variables de sesión
    session_destroy(); // Destruye la sesión

    header('Location: ../../index.php'); // Redirige al formulario de inicio de sesión
    exit();
}

$Id_usuario = $_SESSION['ID'];
$Nombre = $_SESSION['Name'];
$Puesto = $_SESSION['Position'];

// Obtener ID de Gerente
$sql_query = "SELECT * FROM usuarios WHERE Id = '$Id_usuario'";
$result = $conn->query($sql_query);
$row = $result->fetch_assoc();
$id_Gerente = $row['Id'];

$records_per_page = 10; // Número de registros por página
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Página actual
$offset = ($page - 1) * $records_per_page; // Offset para la consulta SQL

// Consulta SQL con LIMIT y OFFSET para paginación
$sql_query = "SELECT viaticos.*, usuarios.Nombre FROM viaticos INNER JOIN usuarios ON viaticos.Id_Usuario = usuarios.Id WHERE Id_Gerente = '$id_Gerente' ORDER BY Id DESC
            LIMIT $offset, $records_per_page";

$result = $conn->query($sql_query);

// Consulta para contar el total de registros
$total_records_query = "SELECT COUNT(*) AS total FROM viaticos WHERE Id_Gerente = '$id_Gerente'";
$total_records_result = $conn->query($total_records_query);
$total_records_row = $total_records_result->fetch_assoc();
$total_records = $total_records_row['total'];

// Calcular el número total de páginas
$total_pages = ceil($total_records / $records_per_page);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Viáticos a mi cargo</title>
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
                            <a class="nav-link" href="../Control/ListadoReembolsos.php">Reembolsos</a>
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
                        <h4 class="text-center">Viáticos de mi personal</h4>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="text-align:center">Nombre</th>
                                        <th style="text-align:center">Folio (ID)</th>
                                        <th style="text-align:center">Fecha de Solicitud</th>
                                        <th style="text-align:center">Fecha de Inicio</th>
                                        <th style="text-align:center">Fecha de Fin</th>
                                        <th style="text-align:center">Cliente</th>
                                        <th style="text-align:center">Estado</th>
                                        <th style="text-align:center" colspan="2">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
            
                                            /// Consulta para obtener los clientes desde la tabla clientes a partr del id del viatico: $row['Id'] 
                                            $sql_clientes = "SELECT Nombre FROM clientes WHERE Id_Viatico = " . $row['Id'];
                                            $result_clientes = $conn->query($sql_clientes);
                                            $clientes = "";
                                            if ($result_clientes->num_rows > 0) {
                                                while ($row_clientes = $result_clientes->fetch_assoc()) {
                                                    $clientes .= $row_clientes['Nombre'] . ", ";
                                                }
                                                $clientes = substr($clientes, 0, -2);
                                            } else {
                                                $clientes = "No hay clientes";
                                            }

                    ?>
            <tr>
                <td data-label="Nombre" style="text-align:center"><?= $row['Nombre'] ?></td>
                <td data-label="Folio (ID)" style="text-align:center"><?= $row['Id'] ?></td>
                <td data-label="Fecha de Solicitud" style="text-align:center"><?= $row['Fecha_Solicitud'] ?></td>
                <td data-label="Fecha de Inicio" style="text-align:center"><?= $row['Fecha_Salida'] ?></td>
                <td data-label="Fecha de Fin" style="text-align:center"><?= $row['Fecha_Regreso'] ?></td>
                <td data-label="Cliente" style="text-align:center"><?= $clientes  ?></td>
                <td data-label="Estado" style="text-align:center"><?= $row['Estado'] ?></td>
                <td data-label="Acciones" style="text-align:center" colspan="2">
                    <?php
                    if ($row['Estado'] == 'Abierto' && $Puesto == 'Empleado') {
                        echo "<a href='detalles.php?id_viatico=" . $row['Id'] . "' class='btn btn-info'>Ver Información</a> ";
                        echo "<a href='editar.php?id_viatico=" . $row['Id'] . "' class='btn btn-warning'>Editar Información</a>";
                    } elseif ($row['Estado'] == 'Completado' && $Puesto == 'Empleado') {
                        echo "<a href='detalles.php?id_viatico=" . $row['Id'] . "' class='btn btn-info'>Ver Información</a> ";
                        echo "<a href='reembolso.php?id_viatico=" . $row['Id'] . "' class='btn btn-success'>Solicitar Reembolso</a>";
                    } elseif ($row['Estado'] == 'Aceptado' && $Puesto == 'Empleado') {
                        echo "<a href='detalles.php?id_viatico=" . $row['Id'] . "' class='btn btn-info' colspan='2'>Ver Información</a>";
                    } elseif ($row['Estado'] == 'Verificación') {
                        echo "<a href='detalles.php?id_viatico=" . $row['Id'] . "' class='btn btn-info'>Ver Información</a> ";
                        echo "<a href='../Control/detallesEvidencias.php?id_viatico=" . $row['Id'] . "' class='btn btn-success'>Evidencias</a>";
                        
                    } elseif ($row['Estado'] == 'EnEspera') {
                        echo "<a href='detalles.php?id_viatico=" . $row['Id'] . "' class='btn btn-info'>Ver Información</a> ";
                        echo "<a href='../Control/detallesEvidencias.php?id_viatico=" . $row['Id'] . "' class='btn btn-success' colspan='2'>Verificar Evidencias</a>";
                    }  elseif ($row['Estado'] == 'Prórroga' || $row['Estado'] == 'Comprobación') {
                        echo "<a href='detalles.php?id_viatico=" . $row['Id'] . "' class='btn btn-info'>Ver Información</a>";
                        echo "<a href='../Control/detallesEvidencias.php?id_viatico=" . $row['Id'] . "' class='btn btn-success'>Evidencias</a>";
                        
                    }
                    
                    else {
                        echo "<a href='detalles.php?id_viatico=" . $row['Id'] . "' class='btn btn-info' colspan='2'>Ver Información</a>";
                    }
                    ?>
                </td>
            </tr>
            <?php
        }
    } else {
        echo "<tr><td colspan='9' style='text-align:center'>No hay viáticos registrados.</td></tr>";
    }
    ?>
</tbody>
                            </table>
                        </div>
                        <nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
        
        <?php if ($page < $total_pages): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>

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
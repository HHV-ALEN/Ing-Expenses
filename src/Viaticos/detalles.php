<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
$id_viatico = $_GET['id_viatico'];

//echo "<script>console.log('Debug Objects - ID: " . $id_viatico . "' );</script>";
//echo "<script>console.log('Debug Objects - ID: " . $id_user . "' );</script>";

//$sql = "SELECT * FROM viaticos WHERE Id = $id_viatico";

$sql_Extend = "SELECT v.*, u.Nombre, u.Gerente FROM viaticos v INNER JOIN usuarios u ON v.Id_Usuario = u.Id WHERE v.Id = $id_viatico";

$result = $conn->query($sql_Extend);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $folio = $row['Id'];
    $Id_usuario = $row['Id_Usuario'];
    $fechaSolicitud = $row['Fecha_Solicitud'];
    $fechaSalida = $row['Fecha_Salida'];
    $fechaRegreso = $row['Fecha_Regreso'];
    $Hora_Salida = $row['Hora_Salida'];
    $Hora_Regreso = $row['Hora_Regreso'];
    $nombreSolicitante = $row['Nombre'];
    $nombreGerente = $row['Gerente'];
    $destino = $row['Destino'];
    $motivo = $row['Motivo'];
    $estado = $row['Estado'];
    $Hospedaje = $row['Hospedaje'];
    $Alimentacion = $row['Alimentacion'];
    $Casetas = $row['Casetas'];
    $Gasolina = $row['Gasolina'];
    $Vuelos = $row['Vuelos'];
    $Transporte = $row['Transporte'];
    $Estacionamiento = $row['Estacionamiento'];
    $Total = $row['Total'];
} else {
    //echo "0 results";
}

$TotalDePersonasEnElViatico = 1;

$sql_acompanantes = "SELECT * FROM acompanantes WHERE Id_Viatico = $id_viatico";
$result_acompanantes = $conn->query($sql_acompanantes);

$acompanantes = [];

if ($result_acompanantes->num_rows > 0) {
    while ($row = $result_acompanantes->fetch_assoc()) {
        $acompanantes[] = $row['Nombre'];
        $TotalDePersonasEnElViatico++;
    }
} else {
    $acompanantes[] = 'Ninguno';
}
///echo "<script>console.log('Debug Objects - Estado: " . $estado . "' );</script>";


$sql_ruta = "SELECT * FROM destino WHERE Id_Viatico = $id_viatico";
$result_ruta = $conn->query($sql_ruta);

$ciudades = [];


if ($result_ruta->num_rows > 0) {
    while ($row = $result_ruta->fetch_assoc()) {
        $ciudades[] = $row['Ciudad'];
    }
} else {
    /// echo "0 results";
}

/// Traer la información de los clientes registrados
$sql_clientes = "SELECT * FROM clientes WHERE Id_Viatico = $folio";
$result_clientes = $conn->query($sql_clientes);
/// Inicializar los arrays
$clientes = [];
$motivos = [];
$fechas = [];

if ($result_clientes->num_rows > 0) {
    while ($row = $result_clientes->fetch_assoc()) {
        $clientes[] = $row['Nombre'];
        $motivos[] = $row['Motivo'];
        $fechas[] = $row['Fecha'];
    }
} else {
    $clientes[] = 'Ninguno';
    $motivos[] = 'Ninguno';
    $fechas[] = 'Ninguno';
}

// Convertir los arrays a cadenas de texto para la visualización
$clientes_str = htmlspecialchars(implode(", ", $clientes), ENT_QUOTES, 'UTF-8');
$motivos_str = htmlspecialchars(implode(", ", $motivos), ENT_QUOTES, 'UTF-8');
$fechas_str = htmlspecialchars(implode(", ", $fechas), ENT_QUOTES, 'UTF-8');


?>
<!DOCTYPE html>
<html>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Detalles de la solicitud</title>
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
    <!-- Fin de la barra de navegación -->

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card">
                    <?php
                    // PHP code to determine the class based on the status
                    $headerClass = ''; // Default class
                    
                    switch ($estado) {
                        case 'Abierto':
                            $headerClass = 'bg-white text-dark';
                            break;
                        case 'Aceptada':
                            $headerClass = 'bg-success';
                            break;
                        case 'Rechazado':
                            $headerClass = 'bg-danger text-white';
                            break;
                        case 'Verificacion':
                            $headerClass = 'bg-warning';
                            break;
                        case 'Fuera de Periodo':
                            $headerClass = 'bg-secondary';
                            break;
                        default:
                            $headerClass = 'bg-primary';
                            break;
                    }
                    ?>
                    <div class="card-header <?php echo $headerClass ?> ">
                        <strong>Folio de Solicitud (ID): </strong><span id="folio"><?= $folio ?></span>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <p><strong>Fecha de Solicitud:</strong> <span id="fechaSolicitud"><?= $fechaSolicitud ?></span></p>
                            <p><strong>Días Solicitados:</strong> <span><?php
                                $datetime1 = new DateTime($fechaSalida);
                                $datetime2 = new DateTime($fechaRegreso);
                                $interval = $datetime1->diff($datetime2);
                                $days = $interval->days; // Obtiene la diferencia en días
                                
                                // Si los días son 0, muestra 1
                                echo $days == 0 ? '1 día' : $days . ' días';
                            ?></span></p>
                        </div>

                        <hr>
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Fecha de Salida:</strong> <span
                                            id="fechaSalida"><?= $fechaSalida ?></span></p>
                                    <p><strong>Hora de Salida:</strong> <span id="horaSalida"><?= $Hora_Salida ?></span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Fecha de Regreso:</strong> <span
                                            id="fechaRegreso"><?= $fechaRegreso ?></span></p>
                                    <p><strong>Hora de Regreso:</strong> <span
                                            id="horaRegreso"><?= $Hora_Regreso ?></span></p>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Solicitante:</strong> <span id="fechaSalida"></span></p>
                                    <p><?= $nombreSolicitante ?></p>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Gerente:</strong> <span id="fechaRegreso"></span></p>
                                    <p><?= $nombreGerente ?></p>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <p><strong>Conceptos:</strong></p>
                            <div class="row">
                                <div class="col-md-5">
                                    <p class="text-center"><strong>Hospedaje:</strong><br><?= $Hospedaje ?></p>
                                    <p class="text-center"><strong>Casetas:</strong><br><?= $Casetas ?></p>
                                </div>
                                <div class="col-md-5">
                                    <p class="text-center"><strong>Alimentación:</strong><br><?= $Alimentacion ?> </p>
                                    <p class="text-center"><strong>Gasolina:</strong><br><?= $Gasolina ?> </p>
                                </div>
                                <div class="col-md-5">
                                    <p class="text-center"><strong>Vuelos:</strong><br><?= $Vuelos ?> </p>
                                    <p class="text-center"><strong>Transporte:</strong><br><?= $Transporte ?> </p>
                                </div>
                                <div class="col-md-5">
                                    <p class="text-center"><strong>Estacionamiento:</strong><br><?= $Estacionamiento ?> </p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="mb-3">
                                    <p style="display: flex; align-items: center;">
                                        <strong style="margin-right: 5px;">Monto Total Solicitado:</strong> 
                                        <span><?= $Total ?></span>
                                    </p>
                                </div>

                            </div>


                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header <?php echo $headerClass ?> ">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Detalles de la Solicitud </strong><span id="estatus">(<?= $estado ?>)</span>

                            </div>
                            <div class="col-md-6">
                                <!-- Descargar formato de solicitud -->
                                <?php
                                $Formato_query = "SELECT * FROM solicitudes WHERE Id_Viatico = $folio";
                                $Formato_result = $conn->query($Formato_query);
                                $row_Formato = $Formato_result->fetch_assoc();
                                $FileName = $row_Formato['Nombre'];
                                $path = '../../uploads/Files/' . $FileName;
                                ?>
                                <a href="<?php echo $path ?>" class="btn btn-success">Descargar Formato</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6 ">
                                <p><strong>Destino:</strong> <span id="destino"><?= $destino ?></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Ruta:</strong></p>
                                <?php if (count($ciudades) > 0): ?>
                                    <?php foreach ($ciudades as $ciudad): ?>
                                        <p><?php echo htmlspecialchars($ciudad); ?></p>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>No hay ciudades en la ruta</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <div class="col-md-6">
                                <p><strong>Clientes:</strong> <span id="cliente"><?= $clientes_str; ?></span></p>
                                <p><strong>Motivos:</strong> <span id="motivo"><?= $motivos_str; ?></span></p>
                                <p><strong>Fechas:</strong> <span id="fecha"><?= $fechas_str; ?></span></p>
                            </div>

                        </div>
                        <hr>
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Total de Personas:</strong> <span
                                            id="totalPersonas"><?= $TotalDePersonasEnElViatico ?></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Acompañantes: </strong></p>
                                    <?php if (count($acompanantes) > 0 && $acompanantes[0] != 'Ninguno'): ?>
                                        <?php foreach ($acompanantes as $nombre): ?>
                                            <p>Nombre del Acompañante: <?php echo htmlspecialchars($nombre); ?></p>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p>Nombre del Acompañante: Ninguno</p>
                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>
                        <hr>
                        <div class="mb-3">
                            <?php
                            echo "Folio: " . $folio;
                            if ($estado == 'Abierto' || $estado == 'Verificacion') {
                                $query_Verificacion = "SELECT * FROM verificacion WHERE Id_Viatico = $folio";
                                $result_Verificacion = $conn->query($query_Verificacion);
                                $row_Verificacion = $result_Verificacion->fetch_assoc();
                                $Aceptado_Gerente = $row_Verificacion['Aceptado_Gerente'];
                                $Aceptado_Control = $row_Verificacion['Aceptado_Control'];
                                $Gerente = $row_Verificacion['Gerente'];
                                $Verificador = $row_Verificacion['Verificador'];

                                if ($Aceptado_Gerente == "Aceptado" && $Gerente == $nombreGerente) {
                                    ?>
                                    <p><strong>Estado:</strong> <span id="estado"><?= $estado ?></span></p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Gerente:</strong> <input type="checkbox" checked></p>
                                        </div>
                                        <?php
                                } else {
                                    ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Gerente:</strong> <input type="checkbox"></p>
                                            </div>
                                            <?php
                                }
                                if ($Aceptado_Control == "Aceptado" && $Verificador != '') {
                                    ?>
                                            <div class="col-md-6">
                                                <p><strong>Control:</strong> <input type="checkbox" checked></p>
                                            </div>
                                            <?php
                                } else {
                                    ?>
                                            <div class="col-md-6">
                                                <p><strong>Control:</strong> <input type="checkbox"></p>
                                            </div>
                                            <?php
                                }
                            }
                            ?>

                                </div>
                            </div>

                        </div>
                    </div>
                    <br>

                    <?php
                    
                        if ($estado == 'Abierto') {
                            if ($Puesto == 'Control' || $Puesto == 'Admin' || $Puesto == 'Gerente') {
                                ?>
                                <div class="card">
                                    <div class="text-center card-header">
                                        <strong>Seleccionar Respuesta:</strong>
                                    </div>
                                    <div class="card-body ">
                                        <div class="text-center row">
                                            <div class="col-md-6">
                                                <a href='../../resources/Back/Viaticos/changeState.php?id_viatico=<?= $folio ?>&Respuesta=Aceptado'
                                                    type="button" class="btn btn-outline-primary">
                                                    Aceptar Solicitud
                                                </a>
                                            </div>
                                            <div class="col-md-6">
                                                <a href='../../resources/Back/Viaticos/changeState.php?id_viatico=<?= $folio ?>&Respuesta=Rechazado'
                                                    type="button" class="btn btn-outline-danger">
                                                    Rechazar Solicitud
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    
                    ?>
                </div>
            </div>
        </div>
    </div>

    <br>

    <!-- Bootstrap JS and dependencies (Popper.js and jQuery) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>
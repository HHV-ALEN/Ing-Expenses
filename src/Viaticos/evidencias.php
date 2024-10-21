<?php
session_start();
if (!isset($_SESSION['ID'])) {
    // La sesión ha caducado o el usuario no ha iniciado sesión
    session_unset(); // Elimina todas las variables de sesión
    session_destroy(); // Destruye la sesión

    header('Location: ../../index.php'); // Redirige al formulario de inicio de sesión
    exit();
}
include ('../../resources/config/db.php');

// Id del viatico
$Id_Viatico = $_GET['id_viatico'];

// Datos del viatico
$viatico_Query = "SELECT * FROM viaticos WHERE Id = '$Id_Viatico'";
$viatico_Result = $conn->query($viatico_Query);
$viatico_Row = $viatico_Result->fetch_assoc();

// Datos del viatico (Pasamos a variables)
$id_Viatico = $viatico_Row['Id'];
$id_usuario = $viatico_Row['Id_Usuario'];
$id_gerente = $viatico_Row['Id_Gerente'];
$fecha_Salida = $viatico_Row['Fecha_Salida'];
$fecha_Regreso = $viatico_Row['Fecha_Regreso'];
$destino = $viatico_Row['Destino'];
$estado = $viatico_Row['Estado'];
$motivo = $viatico_Row['Motivo'];
$fecha_Solicitud = $viatico_Row['Fecha_Solicitud'];

$Hospedaje = $viatico_Row['Hospedaje'];
$Gasolina = $viatico_Row['Gasolina'];
$Casetas = $viatico_Row['Casetas'];
$Alimentos = $viatico_Row['Alimentacion'];
$Vuelos = $viatico_Row['Vuelos'];
$Estacionamiento = $viatico_Row['Estacionamiento'];
$Transporte = $viatico_Row['Transporte'];

// Acompañantes
$Acompañantes = "";
$acompanantes_Query = "SELECT * FROM acompanantes WHERE Id_Viatico = '$Id_Viatico'";
$acompanantes_Result = $conn->query($acompanantes_Query);

while ($acompanantes_Row = $acompanantes_Result->fetch_assoc()) {
    $Acompañantes .= $acompanantes_Row['Nombre'] . ", ";
}

$Acompañantes = substr($Acompañantes, 0, -2);

// Variables para acumular los montos de las evidencias
$sumaEvidenciasHospedaje = 0;
$sumaEvidenciasGasolina = 0;
$sumaEvidenciasCasetas = 0;
$sumaEvidenciasAlimentos = 0;
$sumaEvidenciasVuelos = 0;
$sumaEvidenciasEstacionamiento = 0;
$sumaEvidenciasTransporte = 0;


// Consulta para obtener todas las evidencias y sumarlas por concepto
$image_Query = "SELECT * FROM imagen WHERE Id_Viatico = '$Id_Viatico'";
$image_Result = $conn->query($image_Query);
while ($image_Row = $image_Result->fetch_assoc()) {
    $Cantidad = $image_Row['Monto'];
    $Concepto = $image_Row['Concepto'];

    // Acumular montos por concepto
    switch ($Concepto) {
        case 'Hospedaje':
            $sumaEvidenciasHospedaje += $Cantidad;
            break;
        case 'Gasolina':
            $sumaEvidenciasGasolina += $Cantidad;
            break;
        case 'Casetas':
            $sumaEvidenciasCasetas += $Cantidad;
            break;
        case 'Alimentos':
            $sumaEvidenciasAlimentos += $Cantidad;
            break;
        case 'Vuelos':
            $sumaEvidenciasVuelos += $Cantidad;
            break;
        case 'Estacionamiento':
            $sumaEvidenciasEstacionamiento += $Cantidad;
            break;
        case 'Transporte':
            $sumaEvidenciasTransporte += $Cantidad;
            break;
    }
}

// Función para determinar el estado de la tarjeta// Función para determinar el estado de la tarjeta
function determinarEstado($sumaEvidencias, $montoTotal)
{
    if ($sumaEvidencias == $montoTotal) {
        return ['color' => 'bg-success', 'texto' => 'Completado'];
    } elseif ($sumaEvidencias > $montoTotal) {
        return ['color' => 'bg-success', 'texto' => 'MontoExtra'];
    } else {
        return ['color' => 'bg-warning', 'texto' => 'Pendiente'];
    }
}

$estadoHospedaje = determinarEstado($sumaEvidenciasHospedaje, $Hospedaje);
$estadoGasolina = determinarEstado($sumaEvidenciasGasolina, $Gasolina);
$estadoCasetas = determinarEstado($sumaEvidenciasCasetas, $Casetas);
$estadoAlimentos = determinarEstado($sumaEvidenciasAlimentos, $Alimentos);
$estadoVuelos = determinarEstado($sumaEvidenciasVuelos, $Vuelos);
$estadoEstacionamiento = determinarEstado($sumaEvidenciasEstacionamiento, $Estacionamiento);
$estadoTransporte = determinarEstado($sumaEvidenciasTransporte, $Transporte);


$resultRestaGasolina = 0;
$resultRestaHospedaje = 0;
$resultRestaCasetas = 0;
$resultRestaAlimentos = 0;
$resultRestaVuelos = 0;
$resultRestaEstacionamiento = 0;
$resultRestaTransporte = 0;

$FlagToUseIfAVariableGetsExtra = false;

if ($estadoGasolina['texto'] == 'MontoExtra') {
    $resultRestaGasolina = $sumaEvidenciasGasolina - $Gasolina;
    $FlagToUseIfAVariableGetsExtra = true;
}

if ($estadoHospedaje['texto'] == 'MontoExtra') {
    $resultRestaHospedaje = $sumaEvidenciasHospedaje - $Hospedaje;
    $FlagToUseIfAVariableGetsExtra = true;
}

if ($estadoCasetas['texto'] == 'MontoExtra') {
    $resultRestaCasetas = $sumaEvidenciasCasetas - $Casetas;
    $FlagToUseIfAVariableGetsExtra = true;
}

if ($estadoAlimentos['texto'] == 'MontoExtra') {
    $resultRestaAlimentos = $sumaEvidenciasAlimentos - $Alimentos;
    $FlagToUseIfAVariableGetsExtra = true;
}

if ($estadoVuelos['texto'] == 'MontoExtra') {
    $resultRestaVuelos = $sumaEvidenciasVuelos - $Vuelos;
    $FlagToUseIfAVariableGetsExtra = true;
}

if($estadoEstacionamiento['texto'] == 'MontoExtra'){
    $resultRestaEstacionamiento = $sumaEvidenciasEstacionamiento - $Estacionamiento;
    $FlagToUseIfAVariableGetsExtra = true;
}

if($estadoTransporte['texto'] == 'MontoExtra'){
    $resultRestaTransporte = $sumaEvidenciasTransporte - $Transporte;
    $FlagToUseIfAVariableGetsExtra = true;
}




if (
    $estadoHospedaje['texto'] != 'Pendiente' && $estadoGasolina['texto'] != 'Pendiente'
    && $estadoCasetas['texto'] != 'Pendiente' && $estadoAlimentos['texto'] != 'Pendiente'
    && $estadoVuelos['texto'] != 'Pendiente' && $estadoEstacionamiento['texto'] != 'Pendiente' 
    && $estadoTransporte['texto'] != 'Pendiente'
) {
    $estadoDeEvidencias = 'Completado';

} else {
    $estadoDeEvidencias = 'Pendiente';
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Verificacion de Evidencias</title>
    <link rel="shortcut icon" href="/resources/img/logo-icon.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
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


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card card-custom">
                    <div class="card-header card-header-custom">
                        <h4 class="text-center">Evidencias: </h4>
                        <h5 class="text-center">Solicitud con Folio: <?php echo $Id_Viatico ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th style="text-align:center">Fecha de Solicitud</th>
                                                <th style="text-align:center">Fecha de Inicio</th>
                                                <th style="text-align:center">Fecha de Regreso</th>
                                                <th style="text-align:center">Destino</th>
                                                <th style="text-align:center">Motivo</th>
                                                <th style="text-align:center">Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="text-align:center"><?php echo $fecha_Solicitud; ?></td>
                                                <td style="text-align:center"><?php echo $fecha_Salida; ?></td>
                                                <td style="text-align:center"><?php echo $fecha_Regreso; ?></td>
                                                <td style="text-align:center"><?php echo $destino; ?></td>
                                                <td style="text-align:center"><?php echo $motivo; ?></td>
                                                <td style="text-align:center"><?php echo $estado; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="card-body">
                                        <li class="list-group-item">Acompañantes:
                                            <strong><?php echo $Acompañantes ?></strong>
                                        </li>
                                        <li class="list-group-item">Con Presupusto de:

                                            <div class="container text-center">
                                                <br>
                                                <div class="row align-items-start">
                                                    <div class="col">
                                                        <strong>Hospedaje</strong><br>
                                                        <?php echo $Hospedaje; ?>
                                                    </div>
                                                    <div class="col">
                                                        <strong>Gasolina</strong><br>
                                                        <?php echo $Gasolina; ?>
                                                    </div>
                                                    <div class="col">
                                                        <strong>Casetas</strong><br>
                                                        <?php echo $Casetas; ?>
                                                    </div>
                                                    <div class="col">
                                                        <strong>Alimentos</strong><br>
                                                        <?php echo $Alimentos; ?>
                                                    </div>
                                                    <div class="col">
                                                        <strong>Vuelos</strong><br>
                                                        <?php echo $Vuelos; ?>
                                                    </div>
                                                    <div class="col">
                                                        <strong>Transporte</strong><br>
                                                        <?php echo $Transporte; ?>
                                                    </div>
                                                    <div class="col">
                                                        <strong>Estacionamiento</strong><br>
                                                        <?php echo $Estacionamiento; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>

                                        <br>
                                        <div class="card-body">
                                        <!-- Subir evidencia -->
                                        <form
                                            action="../../resources/Back/Viaticos/evidencias.php?id_viatico='<?php echo $Id_Viatico ?>'"
                                            method="post" enctype="multipart/form-data">
                                            <div class="row mb-3">
                                                <div class="col-sm-4">
                                                    <label for="formFile" class="form-label">Monto: </label>
                                                    <input type="number" class="form-control" id="monto" name="monto" required>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label for="formFile" class="form-label">Concepto: </label>
                                                    <select class="form-control" id="concepto" name="concepto" aria-label="Default select example" required>
                                                        <option selected>Seleccione Concepto</option>
                                                        <option value="Hospedaje">Hospedaje</option>
                                                        <option value="Gasolina">Gasolina</option>
                                                        <option value="Casetas">Casetas</option>
                                                        <option value="Alimentos">Alimentos</option>
                                                        <option value="Vuelos">Vuelos</option>
                                                        <option value="Estacionamiento">Estacionamiento</option>
                                                        <option value="Transporte">Transporte</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label for="formFile" class="form-label">Descripción: </label>
                                                    <input type="text" class="form-control" id="descripcion" name="descripcion" required>
                                                </div>
                                                <br>
                                            </div>
                                            <br>
                                            <div class="mb-4">
                                                <label for="formFile" class="form-label">Seleccionar Evidencia</label>
                                                <input type="file" id="file" name="file" required>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <button type="submit" class="btn btn-primary">Subir</button>
                                                <?php
                                                if ($estadoDeEvidencias == 'Completado') {
                                                    if($estado == 'Prórroga'){
                                                        echo '<button type="button" class="btn btn-outline-primary"><a href="../../resources/Back/Viaticos/EvidenciasCompletadas.php?Id_Viatico='.$Id_Viatico.'&Response=SegundaRevision">Completar Verificación - Segunda Revisión</a></button>';
                                                    }else{
                                                        echo '<button type="button" class="btn btn-outline-primary"><a href="../../resources/Back/Viaticos/EvidenciasCompletadas.php?Id_Viatico='.$Id_Viatico.'&Response=PrimeraRevision">Completar Verificación</a></button>';
                                                    }

                                                } else {
                                                    echo '<button type="button" class="btn btn-outline-primary" disabled>Completar Verificación</button>';
                                                }

                                                ?>
                                                
                                            </div>
                                        </form>
                                    </div>



                                    </div>
                                    <hr>
                                    <div class="card-body">
                                        <h5 class="card-title text-center">Evidencias</h5>
                                        <div class="container">
                                            <div class="row">

                                                <!-- Hospedaje Column -->
                                                <div class="col">
                                                    <h6 class="text-center">Hospedaje</h6>
                                                    <div class="card <?php echo $estadoHospedaje['color']; ?>">
                                                        <div class="card-body">
                                                            <p class="card-text text-center">
                                                                <?php echo $estadoHospedaje['texto']; ?>
                                                            </p>
                                                            <hr>
                                                            <strong class="card-text text-center">
                                                                Acumulado:</strong>
                                                            <?php
                                                            echo $sumaEvidenciasHospedaje;
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <hr>
                                                    <?php
                                                    $image_Query = "SELECT * FROM imagen WHERE Id_Viatico = '$Id_Viatico' AND Concepto = 'Hospedaje'";
                                                    $image_Result = $conn->query($image_Query);
                                                    while ($image_Row = $image_Result->fetch_assoc()) {
                                                        $id_imagen = $image_Row['Id'];
                                                        $image = $image_Row['Nombre'];
                                                        $Descripcion = $image_Row['Descripcion'];
                                                        $Cantidad = $image_Row['Monto'];
                                                        $Concepto = $image_Row['Concepto'];
                                                        ?>

                                                        <div class="card mb-4">
                                                            <?php
                                                            $Extension = pathinfo($image, PATHINFO_EXTENSION);
                                                            if ($Extension == 'pdf' or $Extension == 'PDF') {
                                                                ?>
                                                                <img src="../../resources/img/pdf-icon.png" class="card-img-top"
                                                                    alt="..." data-toggle="modal" data-target="#imageModal"
                                                                    data-image="../../resources/img/pdf-icon.png">
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <img src="../../uploads/<?php echo $image ?>"
                                                                    class="card-img-top" alt="..." data-toggle="modal"
                                                                    data-target="#imageModal"
                                                                    data-image="../../uploads/<?php echo $image ?>">
                                                                <?php
                                                            }
                                                            ?>

                                                            <div class="card-body">
                                                                <p class="card-text"><strong>Descripción:</strong>
                                                                    <?php echo $Descripcion ?></p>
                                                                <p class="card-text"><strong>Monto:</strong>
                                                                    <?= number_format($Cantidad, 2, '.', "'") ?></p>
                                                                <a href="../../uploads/<?php echo $image ?>" download
                                                                    class="btn btn-primary">Descargar</a>

                                                                <a href="../../resources/Back/Viaticos/deleteImage.php?name=<?php echo $image ?>&id_viatico=<?php echo $Id_Viatico ?>&concepto='Hospedaje&source=evidencias"
                                                                    class="btn btn-danger">Eliminar</a>
                                                            </div>
                                                        </div>

                                                        <?php
                                                    }
                                                    ?>
                                                </div>

                                                <!-- Gasolina Column -->
                                                <div class="col">
                                                    <h6 class="text-center">Gasolina</h6>
                                                    <div class="card <?php echo $estadoGasolina['color']; ?>">
                                                        <div class="card-body">
                                                            <p class="card-text text-center">
                                                                <?php echo $estadoGasolina['texto']; ?>
                                                            </p>
                                                            <hr>
                                                            <strong class="card-text text-center">Acumulado:</strong>
                                                            <?php
                                                            echo $sumaEvidenciasGasolina;
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <hr>
                                                    <?php
                                                    $image_Query = "SELECT * FROM imagen WHERE Id_Viatico = '$Id_Viatico' AND Concepto = 'Gasolina'";
                                                    $image_Result = $conn->query($image_Query);
                                                    while ($image_Row = $image_Result->fetch_assoc()) {
                                                        $id_imagen = $image_Row['Id'];
                                                        $image = $image_Row['Nombre'];
                                                        $Descripcion = $image_Row['Descripcion'];
                                                        $Cantidad = $image_Row['Monto'];
                                                        ?>

                                                        <div class="card mb-4">
                                                            <?php
                                                            $Extension = pathinfo($image, PATHINFO_EXTENSION);
                                                            if ($Extension == 'pdf' or $Extension == 'PDF') {
                                                                ?>
                                                                <img src="../../resources/img/pdf-icon.png" class="card-img-top"
                                                                    alt="..." data-toggle="modal" data-target="#imageModal"
                                                                    data-image="../../resources/img/pdf-icon.png">
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <img src="../../uploads/<?php echo $image ?>"
                                                                    class="card-img-top" alt="..." data-toggle="modal"
                                                                    data-target="#imageModal"
                                                                    data-image="../../uploads/<?php echo $image ?>">
                                                                <?php
                                                            }
                                                            ?>

                                                            <div class="card-body">
                                                                <p class="card-text"><strong>Descripción:</strong>
                                                                    <?php echo $Descripcion ?></p>
                                                                <p class="card-text"><strong>Monto:</strong>
                                                                    <?= number_format($Cantidad, 2, '.', "'") ?></p>
                                                                <a href="../../uploads/<?php echo $image ?>" download
                                                                    class="btn btn-primary">Descargar</a>
                                                                 <a href="../../resources/Back/Viaticos/deleteImage.php?name=<?php echo $image ?>&id_viatico=<?php echo $Id_Viatico ?>&concepto='Gasolina&source=evidencias"
                                                                    class="btn btn-danger">Eliminar</a>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                                <!-- Casetas Column -->
                                                <div class="col">
                                                    <h6 class="text-center">Casetas</h6>
                                                    <div class="card <?php echo $estadoCasetas['color']; ?>">
                                                        <div class="card-body">
                                                            <p class="card-text text-center">
                                                                <?php echo $estadoCasetas['texto']; ?>
                                                            </p>
                                                            <hr>
                                                            <strong class="card-text text-center">Acumulado:</strong>
                                                            <?php
                                                            echo $sumaEvidenciasCasetas;
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <hr>
                                                    <?php
                                                    $image_Query = "SELECT * FROM imagen WHERE Id_Viatico = '$Id_Viatico' AND Concepto = 'Casetas'";
                                                    $image_Result = $conn->query($image_Query);
                                                    while ($image_Row = $image_Result->fetch_assoc()) {
                                                        $id_imagen = $image_Row['Id'];
                                                        $image = $image_Row['Nombre'];
                                                        $Descripcion = $image_Row['Descripcion'];
                                                        $Cantidad = $image_Row['Monto'];
                                                        ?>
                                                        <div class="card mb-4">

                                                            <?php
                                                            $Extension = pathinfo($image, PATHINFO_EXTENSION);
                                                            if ($Extension == 'pdf' or $Extension == 'PDF') {
                                                                ?>
                                                                <img src="../../resources/img/pdf-icon.png" class="card-img-top"
                                                                    alt="..." data-toggle="modal" data-target="#imageModal"
                                                                    data-image="../../resources/img/pdf-icon.png">
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <img src="../../uploads/<?php echo $image ?>"
                                                                    class="card-img-top" alt="..." data-toggle="modal"
                                                                    data-target="#imageModal"
                                                                    data-image="../../uploads/<?php echo $image ?>">
                                                                <?php
                                                            }
                                                            ?>

                                                            <div class="card-body">
                                                                <p class="card-text"><strong>Descripción:</strong>
                                                                    <?php echo $Descripcion ?></p>
                                                                <p class="card-text"><strong>Monto:</strong>
                                                                    <?= number_format($Cantidad, 2, '.', "'") ?></p>
                                                                <a href="../../uploads/<?php echo $image ?>" download
                                                                    class="btn btn-primary">Descargar</a>
                                                                <a href="../../resources/Back/Viaticos/deleteImage.php?name=<?php echo $image ?>&id_viatico=<?php echo $Id_Viatico ?>&concepto='Casetas&source=evidencias"
                                                                    class="btn btn-danger">Eliminar</a>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>

                                                <!-- Alimentos Column -->
                                                <div class="col">
                                                    <h6 class="text-center">Alimentos</h6>
                                                    <div class="card <?php echo $estadoAlimentos['color']; ?>">
                                                        <div class="card-body">
                                                            <p class="card-text text-center">
                                                                <?php echo $estadoAlimentos['texto']; ?>
                                                            </p>
                                                            <hr>
                                                            <strong class="card-text text-center">Acumulado:</strong>
                                                            <?php
                                                            echo $sumaEvidenciasAlimentos;
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <hr>
                                                    <?php
                                                    $image_Query = "SELECT * FROM imagen WHERE Id_Viatico = '$Id_Viatico' AND Concepto = 'Alimentos'";
                                                    $image_Result = $conn->query($image_Query);
                                                    while ($image_Row = $image_Result->fetch_assoc()) {
                                                        $id_imagen = $image_Row['Id'];
                                                        $image = $image_Row['Nombre'];
                                                        $Descripcion = $image_Row['Descripcion'];
                                                        $Cantidad = $image_Row['Monto'];
                                                        ?>
                                                        <div class="card mb-4">

                                                            <?php
                                                            $Extension = pathinfo($image, PATHINFO_EXTENSION);
                                                            if ($Extension == 'pdf' or $Extension == 'PDF') {
                                                                ?>
                                                                <img src="../../resources/img/pdf-icon.png" class="card-img-top"
                                                                    alt="..." data-toggle="modal" data-target="#imageModal"
                                                                    data-image="../../resources/img/pdf-icon.png">
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <img src="../../uploads/<?php echo $image ?>"
                                                                    class="card-img-top" alt="..." data-toggle="modal"
                                                                    data-target="#imageModal"
                                                                    data-image="../../uploads/<?php echo $image ?>">
                                                                <?php
                                                            }
                                                            ?>

                                                            <div class="card-body">
                                                                <p class="card-text"><strong>Descripción:</strong>
                                                                    <?php echo $Descripcion ?></p>
                                                                <p class="card-text"><strong>Monto:</strong>
                                                                    <?= number_format($Cantidad, 2, '.', "'") ?></p>
                                                                <a href="../../uploads/<?php echo $image ?>" download
                                                                    class="btn btn-primary">Descargar</a>
                                                                 <a href="../../resources/Back/Viaticos/deleteImage.php?name=<?php echo $image ?>&id_viatico=<?php echo $Id_Viatico ?>&concepto='Alimentos&source=evidencias"
                                                                    class="btn btn-danger">Eliminar</a>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>

                                                <!-- Vuelos Column -->
                                                <div class="col">
                                                    <h6 class="text-center">Vuelos</h6>
                                                    <div class="card <?php echo $estadoVuelos['color']; ?>">
                                                        <div class="card-body">
                                                            <p class="card-text text-center">
                                                                <?php echo $estadoVuelos['texto']; ?>
                                                            </p>
                                                            <hr>
                                                            <strong class="card-text text-center">Acumulado:</strong>
                                                            <?php
                                                            echo $sumaEvidenciasVuelos;
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <hr>
                                                    <?php
                                                    $image_Query = "SELECT * FROM imagen WHERE Id_Viatico = '$Id_Viatico' AND Concepto = 'Vuelos'";
                                                    $image_Result = $conn->query($image_Query);
                                                    while ($image_Row = $image_Result->fetch_assoc()) {
                                                        $id_imagen = $image_Row['Id'];
                                                        $image = $image_Row['Nombre'];
                                                        $Descripcion = $image_Row['Descripcion'];
                                                        $Cantidad = $image_Row['Monto'];
                                                        ?>
                                                        <div class="card mb-4">
                                                            <?php
                                                            $Extension = pathinfo($image, PATHINFO_EXTENSION);
                                                            if ($Extension == 'pdf' or $Extension == 'PDF') {
                                                                ?>
                                                                <img src="../../resources/img/pdf-icon.png" class="card-img-top"
                                                                    alt="..." data-toggle="modal" data-target="#imageModal"
                                                                    data-image="../../resources/img/pdf-icon.png">
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <img src="../../uploads/<?php echo $image ?>"
                                                                    class="card-img-top" alt="..." data-toggle="modal"
                                                                    data-target="#imageModal"
                                                                    data-image="../../uploads/<?php echo $image ?>">
                                                                <?php
                                                            }
                                                            ?>


                                                            <div class="card-body">
                                                                <p class="card-text"><strong>Descripción:</strong>
                                                                    <?php echo $Descripcion ?></p>
                                                                <p class="card-text"><strong>Monto:</strong>
                                                                    <?= number_format($Cantidad, 2, '.', "'") ?></p>
                                                                <a href="../../uploads/<?php echo $image ?>" download
                                                                    class="btn btn-primary">Descargar</a>
                                                                 <a href="../../resources/Back/Viaticos/deleteImage.php?name=<?php echo $image ?>&id_viatico=<?php echo $Id_Viatico ?>&concepto='Vuelos&source=evidencias"
                                                                    class="btn btn-danger">Eliminar</a>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>

                                                <!-- Transporte Column -->
                                                <div class="col">
                                                    <h6 class="text-center">Transporte</h6>
                                                    <div class="card <?php echo $estadoTransporte['color']; ?>">
                                                        <div class="card-body">
                                                            <p class="card-text text-center">
                                                                <?php echo $estadoTransporte['texto']; ?>
                                                            </p>
                                                            <hr>
                                                            <strong class="card-text text-center">Acumulado:</strong>
                                                            <?php
                                                            echo $sumaEvidenciasTransporte;
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <hr>
                                                    <?php
                                                    $image_Query = "SELECT * FROM imagen WHERE Id_Viatico = '$Id_Viatico' AND Concepto = 'Transporte'";
                                                    $image_Result = $conn->query($image_Query);
                                                    while ($image_Row = $image_Result->fetch_assoc()) {
                                                        $id_imagen = $image_Row['Id'];
                                                        $image = $image_Row['Nombre'];
                                                        $Descripcion = $image_Row['Descripcion'];
                                                        $Cantidad = $image_Row['Monto'];
                                                        ?>
                                                        <div class="card mb-4">
                                                            <?php
                                                            $Extension = pathinfo($image, PATHINFO_EXTENSION);
                                                            if ($Extension == 'pdf' or $Extension == 'PDF') {
                                                                ?>
                                                                <img src="../../resources/img/pdf-icon.png" class="card-img-top"
                                                                    alt="..." data-toggle="modal" data-target="#imageModal"
                                                                    data-image="../../resources/img/pdf-icon.png">
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <img src="../../uploads/<?php echo $image ?>"
                                                                    class="card-img-top" alt="..." data-toggle="modal"
                                                                    data-target="#imageModal"
                                                                    data-image="../../uploads/<?php echo $image ?>">
                                                                <?php
                                                            }
                                                            ?>


                                                            <div class="card-body">
                                                                <p class="card-text"><strong>Descripción:</strong>
                                                                    <?php echo $Descripcion ?></p>
                                                                <p class="card-text"><strong>Monto:</strong>
                                                                    <?= number_format($Cantidad, 2, '.', "'") ?></p>
                                                                <a href="../../uploads/<?php echo $image ?>" download
                                                                    class="btn btn-primary">Descargar</a>
                                                                 <a href="../../resources/Back/Viaticos/deleteImage.php?name=<?php echo $image ?>&id_viatico=<?php echo $Id_Viatico ?>&concepto='Transporte&source=evidencias"
                                                                    class="btn btn-danger">Eliminar</a>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>

                                                <!-- Estacionamiento Column -->
                                                <div class="col">
                                                    <h6 class="text-center">Estacionamiento</h6>
                                                    <div class="card <?php echo $estadoEstacionamiento['color']; ?>">
                                                        <div class="card-body">
                                                            <p class="card-text text-center">
                                                                <?php echo $estadoEstacionamiento['texto']; ?>
                                                            </p>
                                                            <hr>
                                                            <strong class="card-text text-center">Acumulado:</strong>
                                                            <?php
                                                            echo $sumaEvidenciasEstacionamiento;
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <hr>
                                                    <?php
                                                    $image_Query = "SELECT * FROM imagen WHERE Id_Viatico = '$Id_Viatico' AND Concepto = 'Estacionamiento'";
                                                    $image_Result = $conn->query($image_Query);
                                                    while ($image_Row = $image_Result->fetch_assoc()) {
                                                        $id_imagen = $image_Row['Id'];
                                                        $image = $image_Row['Nombre'];
                                                        $Descripcion = $image_Row['Descripcion'];
                                                        $Cantidad = $image_Row['Monto'];
                                                        ?>
                                                        <div class="card mb-4">
                                                            <?php
                                                            $Extension = pathinfo($image, PATHINFO_EXTENSION);
                                                            if ($Extension == 'pdf' or $Extension == 'PDF') {
                                                                ?>
                                                                <img src="../../resources/img/pdf-icon.png" class="card-img-top"
                                                                    alt="..." data-toggle="modal" data-target="#imageModal"
                                                                    data-image="../../resources/img/pdf-icon.png">
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <img src="../../uploads/<?php echo $image ?>"
                                                                    class="card-img-top" alt="..." data-toggle="modal"
                                                                    data-target="#imageModal"
                                                                    data-image="../../uploads/<?php echo $image ?>">
                                                                <?php
                                                            }
                                                            ?>
                                                                     <div class="card-body">
                                                                <p class="card-text"><strong>Descripción:</strong>
                                                                    <?php echo $Descripcion ?></p>
                                                                <p class="card-text"><strong>Monto:</strong>
                                                                    <?= number_format($Cantidad, 2, '.', "'") ?></p>
                                                                <a href="../../uploads/<?php echo $image ?>" download
                                                                    class="btn btn-primary">Descargar</a>
                                                                 <a href="../../resources/Back/Viaticos/deleteImage.php?name=<?php echo $image ?>&id_viatico=<?php echo $Id_Viatico ?>&concepto='Estacionamiento&source=evidencias"
                                                                    class="btn btn-danger">Eliminar</a>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <hr>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <br>

    <!-- Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Imagen Completa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="modalImage" src="" alt="Imagen Completa" class="img-fluid">
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS and dependencies (Popper.js and jQuery) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.card-img-top').click(function () {
                var src = $(this).attr('data-image');
                $('#modalImage').attr('src', src);
            });
        });
    </script>
</body>

</html>
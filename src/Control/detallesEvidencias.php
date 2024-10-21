<?php
session_start();
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
$Transporte = $viatico_Row['Transporte'];
$Vuelos = $viatico_Row['Vuelos'];
$Estacionamiento = $viatico_Row['Estacionamiento'];

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
$sumaEvidenciasTransporte = 0;
$sumaEvidenciasEstacionamiento = 0;

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
        case 'Transporte':
            $sumaEvidenciasTransporte += $Cantidad;
            break;
        case 'Estacionamiento':
            $sumaEvidenciasEstacionamiento += $Cantidad;
            break;
    }
}

// Función para determinar el estado de la tarjeta
function determinarEstado($sumaEvidencias, $montoTotal)
{
    if ($sumaEvidencias >= $montoTotal) {
        return ['color' => 'bg-success', 'texto' => 'Completado'];
    } else {
        return ['color' => 'bg-warning', 'texto' => 'Pendiente'];
    }
}

$estadoHospedaje = determinarEstado($sumaEvidenciasHospedaje, $Hospedaje);
$estadoGasolina = determinarEstado($sumaEvidenciasGasolina, $Gasolina);
$estadoCasetas = determinarEstado($sumaEvidenciasCasetas, $Casetas);
$estadoAlimentos = determinarEstado($sumaEvidenciasAlimentos, $Alimentos);
$estadoVuelos = determinarEstado($sumaEvidenciasVuelos, $Vuelos);
$estadoTransporte = determinarEstado($sumaEvidenciasTransporte, $Transporte);
$estadoEstacionamiento = determinarEstado($sumaEvidenciasEstacionamiento, $Estacionamiento);

/*
if ($estadoHospedaje['texto'] == 'Completado' && $estadoGasolina['texto'] == 'Completado' && $estadoCasetas['texto'] == 'Completado' && $estadoAlimentos['texto'] == 'Completado') {
    $sql_query = "UPDATE viaticos SET Estado = 'EnEspera' WHERE Id = '$Id_Viatico'";
    $conn->query($sql_query);
        // Añadir JavaScript para recargar la página
        echo "<script>
        setTimeout(function() {
            location.reload();
        }, 2000); // Esperar 2 segundos antes de recargar
      </script>";
      header("Location: ../../../../resources/Back/Mail/VerificacionCompletada.php?id_usuario=$id_usuario&id_gerente=$id_gerente&id_viatico=$id_Viatico");
} else {
    $sql_query = "UPDATE viaticos SET Estado = 'EnEspera' WHERE Id = '$Id_Viatico'";
    $conn->query($sql_query);
}*/

$source = "detallesEvidencias";

//echo $Id_Viatico;
// Obtener clientes de la base de datos
$clientes_Query = "SELECT * FROM clientes WHERE Id_Viatico = '$Id_Viatico'";
/// Contar si hay registros, si no hay, igualar a '' (null)
$clientes_Result = $conn->query($clientes_Query);
if ($clientes_Result->num_rows > 0) {
    // Guardar los clientes en un arreglo para mostrarlos
    $cliente = '';
    while ($clientes_Row = $clientes_Result->fetch_assoc()) {
        $cliente .= $clientes_Row['Nombre'] . ', ';
    }
} else {
    $cliente = '';
}

//echo "<br>Clientes del viatico: " . $cliente . "<br>";

$Comprobacion = array(
    'Id_Viatico' => $Id_Viatico,
    'Fecha_Salida' => $fecha_Salida,
    'Fecha_Regreso' => $fecha_Regreso,
    'Cliente' => $cliente,
    'Hospedaje' =>$sumaEvidenciasHospedaje,
    'Gasolina' =>$sumaEvidenciasGasolina,
    'Casetas' =>$sumaEvidenciasCasetas,
    'Alimentos' =>$sumaEvidenciasAlimentos,
    'Vuelos' =>$sumaEvidenciasVuelos,
    'Transporte' =>$sumaEvidenciasTransporte,
    'Estacionamiento' =>$sumaEvidenciasEstacionamiento
);

$MontosUtilizados = Array();

$_SESSION['Comprobacion'] = $Comprobacion; // Comprobación lleva la suma de los montos evidenciados
  
//print_r($Comprobacion);

$MontosPedidos = array();
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

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-custom text-center">
                    <div class="card-header card-header-custom">
                        <h4 class="text-center">Revisión de Evidencias: </h4>
                        <h5 class="text-center">Solicitud con Folio: <?php echo $Id_Viatico ?></h5>
                        <a href="ComprobacionEvidencias.php?id_usuario=<?php echo $id_usuario ?>"  class="btn btn-primary">
                        Descargar Comprobación
                    </a>
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
                                                        <?php echo $Hospedaje; 
                                                        array_push($MontosPedidos, $Hospedaje);
                                                        ?>
                                                    </div>
                                                    <div class="col">
                                                        <strong>Gasolina</strong><br>
                                                        <?php echo $Gasolina; ?>
                                                        <?php array_push($MontosPedidos, $Gasolina); ?>
                                                    </div>
                                                    <div class="col">
                                                        <strong>Casetas</strong><br>
                                                        <?php echo $Casetas; 
                                                        array_push($MontosPedidos, $Casetas);
                                                        ?>

                                                    </div>
                                                    <div class="col">
                                                        <strong>Alimentos</strong><br>
                                                        <?php echo $Alimentos; 
                                                        array_push($MontosPedidos, $Alimentos);
                                                        ?>
                                                    </div>
                                                    <div class="col">
                                                        <strong>Vuelos</strong><br>
                                                        <?php echo $Vuelos; 
                                                        array_push($MontosPedidos, $Vuelos);
                                                        ?>
                                                    </div>
                                                    <div class="col">
                                                        <strong>Transporte</strong><br>
                                                        <?php echo $Transporte; 
                                                        array_push($MontosPedidos, $Transporte);
                                                        ?>
                                                    </div>
                                                    <div class="col">
                                                        <strong>Estacionamiento</strong><br>
                                                        <?php echo $Estacionamiento; 
                                                        array_push($MontosPedidos, $Estacionamiento); 
                                                        
                                                        $_SESSION['MontosPedidos'] = $MontosPedidos;
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </div>
                                    <hr>

                                    <div class="card-body">
                                        <h3 class="text-center">Comprobaciones:</h3>
                                        <div class="container">
                                            <?php 
                                            /// Tomar información de la verificación
                                            if($estado == 'Segunda Revisión'){
                                                $verificacion_Query = "SELECT * FROM verificacion WHERE Id_Viatico = '$Id_Viatico' AND Tipo = 'Segunda Revisión'";
                                            } else {
                                                $verificacion_Query = "SELECT * FROM verificacion WHERE Id_Viatico = '$Id_Viatico' AND Tipo = 'Comprobación'";
                                            }
                                            $verificacion_Result = $conn->query($verificacion_Query);
                                            $verificacion_Row = $verificacion_Result->fetch_assoc();
                                            $Aceptado_Control = $verificacion_Row['Aceptado_Control'];
                                            $Aceptado_Gerente = $verificacion_Row['Aceptado_Gerente'];
                                        
                                            ?>
                                            <div class="row text-center">
                                                <div class="col">
                                                    <h6 class="text-center">Control: <?php echo $Aceptado_Control ?></h6>
                                                    <a href="../../resources/Back/Viaticos/Comprobacion.php?id_viatico=<?php echo $Id_Viatico ?>&Response=Aceptado" class="btn btn-success">Aceptar</a>

                                                </div>
                                                <div class="col">
                                                    <h6 class="text-center">Gerente: <?php echo $Aceptado_Gerente ?></h6>
                                                    <?php if($estado == 'Prórroga'){ ?>
                                                    <a href="../../resources/Back/Viaticos/Comprobacion.php?id_viatico=<?php echo $Id_Viatico ?>&Response=Rechazado" class="btn btn-danger">Rechazar - Terminar Solicitud</a>
                                                    <?php }elseif($estado == 'Segunda Revisión'){
                                                        ?>
                                                        <a href="../../resources/Back/Viaticos/Comprobacion.php?id_viatico=<?php echo $Id_Viatico ?>&Response=Rechazado" class="btn btn-danger">Rechazar - Terminar Solicitud</a>
                                                        <?php 
                                                    } else {
                                                        ?>
                                                        <a href="../../resources/Back/Viaticos/Comprobacion.php?id_viatico=<?php echo $Id_Viatico ?>&Response=Prórroga" class="btn btn-danger">Rechazar</a>
                                                        <?php
                                                    } ?>
                                                </div>
                                            </div>
 
                                        </div>

                                        <br>
                                        <hr>
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
                                                                <img src="../../uploads/pdf-icon.png" class="card-img-top"
                                                                    alt="..." data-toggle="modal" data-target="#imageModal"
                                                                    data-image="../../uploads/pdf-icon.png">
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

                                                                <a href="../../resources/Back/Viaticos/deleteImage.php?name=<?php echo $image ?>&id_viatico=<?php echo $Id_Viatico ?>&concepto='Hospedaje'"
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
                                                        $MontosUtilizados[] = $Cantidad;
                                                        ?>
                                                        <div class="card mb-4">
                                                            <?php
                                                            $Extension = pathinfo($image, PATHINFO_EXTENSION);
                                                            if ($Extension == 'pdf' or $Extension == 'PDF') {
                                                                ?>
                                                                <img src="../../uploads/pdf-icon.png" class="card-img-top"
                                                                    alt="..." data-toggle="modal" data-target="#imageModal"
                                                                    data-image="../../uploads/pdf-icon.png">
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
                                                                <a href="../../resources/Back/Viaticos/deleteImage.php?name=<?php echo $image ?>&id_viatico=<?php echo $Id_Viatico ?>&concepto='Gasolina'"
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
                                                        $MontosUtilizados[] = $Cantidad;
                                                        ?>
                                                        <div class="card mb-4">
                                                            <?php
                                                            $Extension = pathinfo($image, PATHINFO_EXTENSION);
                                                            if ($Extension == 'pdf' or $Extension == 'PDF') {
                                                                ?>
                                                                <img src="../../uploads/pdf-icon.png" class="card-img-top"
                                                                    alt="..." data-toggle="modal" data-target="#imageModal"
                                                                    data-image="../../uploads/pdf-icon.png">
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

                                                                <a href="../../resources/Back/Viaticos/deleteImage.php?name=<?php echo $image ?>&id_viatico=<?php echo $Id_Viatico ?>&concepto='Casetas'"
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
                                                        $MontosUtilizados[] = $Cantidad;
                                                        ?>
                                                        <div class="card mb-4">
                                                            <?php
                                                            $Extension = pathinfo($image, PATHINFO_EXTENSION);
                                                            if ($Extension == 'pdf' or $Extension == 'PDF') {
                                                                ?>
                                                                <img src="../../uploads/pdf-icon.png" class="card-img-top"
                                                                    alt="..." data-toggle="modal" data-target="#imageModal"
                                                                    data-image="../../uploads/pdf-icon.png">
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
                                                                <a href="../../resources/Back/Viaticos/deleteImage.php?name=<?php echo $image ?>&id_viatico=<?php echo $Id_Viatico ?>&concepto='Alimentos'"
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
                                                        $MontosUtilizados[] = $Cantidad;
                                                        ?>
                                                        <div class="card mb-4">
                                                            <?php
                                                            $Extension = pathinfo($image, PATHINFO_EXTENSION);
                                                            if ($Extension == 'pdf' or $Extension == 'PDF') {
                                                                ?>
                                                                <img src="../../uploads/pdf-icon.png" class="card-img-top"
                                                                    alt="..." data-toggle="modal" data-target="#imageModal"
                                                                    data-image="../../uploads/pdf-icon.png.png">
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
                                                                <a href="../../resources/Back/Viaticos/deleteImage.php?name=<?php echo $image ?>&id_viatico=<?php echo $Id_Viatico ?>&concepto='Vuelos'"
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
                                                        $MontosUtilizados[] = $Cantidad;
                                                        ?>
                                                        <div class="card mb-4">
                                                            <?php
                                                            $Extension = pathinfo($image, PATHINFO_EXTENSION);
                                                            if ($Extension == 'pdf' or $Extension == 'PDF') {
                                                                ?>
                                                                <img src="../../uploads/pdf-icon.png" class="card-img-top"
                                                                    alt="..." data-toggle="modal" data-target="#imageModal"
                                                                    data-image="../../uploads/pdf-icon.png">
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
                                                                <a href="../../resources/Back/Viaticos/deleteImage.php?name=<?php echo $image ?>&id_viatico=<?php echo $Id_Viatico ?>&concepto='Transporte'"
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
                                                        $MontosUtilizados[] = $Cantidad;
                                                        ?>
                                                        <div class="card mb-4">
                                                            <?php
                                                            $Extension = pathinfo($image, PATHINFO_EXTENSION);
                                                            if ($Extension == 'pdf' or $Extension == 'PDF') {
                                                                ?>
                                                                <img src="../../uploads/pdf-icon.png.png" class="card-img-top"
                                                                    alt="..." data-toggle="modal" data-target="#imageModal"
                                                                    data-image="../../uploads/pdf-icon.png">
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
                                                                <a href="../../resources/Back/Viaticos/deleteImage.php?name=<?php echo $image ?>&id_viatico=<?php echo $Id_Viatico ?>&concepto='Transporte'"
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
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <?php
    $_SESSION['MontosUtilizados'] = $MontosUtilizados; 
    //print_r($MontosUtilizados); 
    ?>

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

        // Obtener referencias a los elementos del formulario y al botón de submit
        const form = document.getElementById('evidenceForm');
        const evidenceSelects = document.querySelectorAll('.evidence-select');
        const submitButton = document.getElementById('submitButton');

        // Función para habilitar/deshabilitar el botón de submit
        function updateSubmitButtonState() {
            // Verificar si todos los selects tienen el valor 'Aceptar'
            const allAccepted = Array.from(evidenceSelects).every(select => select.value === 'Aceptar');
            submitButton.disabled = !allAccepted;
        }

        // Agregar un event listener a cada select para actualizar el estado del botón de submit cuando cambie el valor de un select
        evidenceSelects.forEach(select => {
            select.addEventListener('change', updateSubmitButtonState);
        });

        // Deshabilitar el botón de submit inicialmente
        updateSubmitButtonState();
    </script>
</body>

</html>
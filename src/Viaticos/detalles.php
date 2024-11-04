<?php
include '../../resources/config/db.php';
session_start();
$Id_Viatico = $_GET['id'];

// Obtener detalles del viático
$sql = "SELECT * FROM viaticos WHERE Id = $Id_Viatico";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$Solicitante = $row['Solicitante'];
$Fecha_Salida = $row['Fecha_Salida'];
$Hora_Salida = $row['Hora_Salida'];
$Fecha_Regreso = $row['Fecha_Regreso'];
$Hora_Regreso = $row['Hora_Regreso'];
$Orden_Venta = $row['Orden_Venta'];
$Codigo = $row['Codigo'];
$Nombre_Proyecto = $row['Nombre_Proyecto'];
$Destino = $row['Destino'];
$Total = $row['Total'];
$Estado = $row['Estado'];
$Fecha_Registro = $row['Fecha_Registro'];

// Obtener información del gerente
$sql_names = "SELECT * FROM usuarios WHERE Nombre = '$Solicitante'";
$result = $conn->query($sql_names);
$row = $result->fetch_assoc();
$nombreGerente = $row['Gerente'];

// Obtener conceptos
$sql_conceptos = "SELECT * FROM conceptos WHERE Id_Viatico = $Id_Viatico";
/// Generar un arreglo que guarde los conceptos y sus montos | Ejemplo: ['Hospedaje' => 1000, 'Casetas' => 500]
// Atributos de la tabla = 'Concepto' y 'Monto'
$conceptos = [];
$result = $conn->query($sql_conceptos);
while ($row = $result->fetch_assoc()) {
    $conceptos[$row['Concepto']] = $row['Monto'];
}

// Obtener Información del destino
$sql_destino = "SELECT * FROM destino WHERE Id_Viatico = $Id_Viatico";
// Genera un arreglo que guarde la información del destino | Ejemplo: ['Estado' => 'Jalisco', 'Ciudad' => 'Zapopan']
$destino = [];
$result = $conn->query($sql_destino);
while ($row = $result->fetch_assoc()) {
    $destino['Estado'] = $row['Estado'];
    $destino['Ciudad'] = $row['Ciudad'];
}

// Obtener información de los acompañantes
$sql_acompanantes = "SELECT * FROM acompanantes WHERE Id_Viatico = $Id_Viatico";
// Genera un arreglo que guarde la información de los acompañantes | Ejemplo: ['Nombre' => 'Juan Pérez', 'Puesto' => 'Desarrollador']
$acompanantes = [];
$result = $conn->query($sql_acompanantes);
while ($row = $result->fetch_assoc()) {
    $acompanantes[] = [
        'Nombre' => $row['Nombre']
    ];
}

// Obtener información de los clientes
$sql_clientes = "SELECT * FROM clientes WHERE Id_Viatico = $Id_Viatico";
// Genera un arreglo que guarde la información de los clientes | Ejemplo: ['Nombre' => 'IBM', 'Motivo' => 'Soporte', 'Fecha' => '2021-12-31']
$clientes = [];
$result = $conn->query($sql_clientes);
while ($row = $result->fetch_assoc()) {
    $clientes[] = [
        'Nombre' => $row['Nombre'],
        'Motivo' => $row['Motivo'],
        'Fecha' => $row['Fecha']
    ];
}

// Obtener Información de la tabla verificacion
$sql_verificacion = "SELECT * FROM verificacion WHERE Id_Relacionado = $Id_Viatico AND Tipo = 'Viatico'";
$result = $conn->query($sql_verificacion);
$row = $result->fetch_assoc();
$Aceptado_Gerente = $row['Aceptado_Gerente'];
$Aceptado_Control = $row['Aceptado_Control'];


switch ($Aceptado_Gerente) {
    case 'Pendiente':
        $Aceptado_Gerente = '<span class="badge bg-warning">Pendiente</span>';
        break;
    case 'Aceptado':
        $Aceptado_Gerente = '<span class="badge bg-success">Aceptado</span>';
        break;
    case 'Rechazado':
        $Aceptado_Gerente = '<span class="badge bg-danger">Rechazado</span>';
        break;
    default:
        $Aceptado_Gerente = '<span class="badge bg-warning">Pendiente</span>';
        break;
}

switch ($Aceptado_Control) {
    case 'Pendiente':
        $Aceptado_Control = '<span class="badge bg-warning">Pendiente</span>';
        break;
    case 'Aceptado':
        $Aceptado_Control = '<span class="badge bg-success">Aceptado</span>';
        break;
    case 'Rechazado':
        $Aceptado_Control = '<span class="badge bg-danger">Rechazado</span>';
        break;
    default:
        $Aceptado_Control = '<span class="badge bg-warning">Pendiente</span>';
        break;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles</title>
    <link rel="shortcut icon" href="/resources/img/logo-icon.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>
    <?php include '../../src/navbar.php' ?>

    <!-- Tarjeta para el usuario Gerente y Control para aceptar o rechazar el viático -->
    <?php

    /// Obtener información de la Tabla verificación para mostrar los botones de aceptar o rechazar
    
    $Sql_Verificadores = "SELECT * FROM verificacion WHERE Id_Relacionado = $Id_Viatico AND Tipo = 'Viatico'";
    $Result_Verificadores = $conn->query($Sql_Verificadores);
    $Row_Verificadores = $Result_Verificadores->fetch_assoc();
    $Aceptado_Gerente = $Row_Verificadores['Aceptado_Gerente'];
    $Aceptado_Control = $Row_Verificadores['Aceptado_Control'];

    echo "<script>console.log('Debug Objects: " . $Aceptado_Gerente . "' );</script>";


    if ($Aceptado_Control == 'Pendiente' && $_SESSION['Position'] == 'Gerente') {
        if (($_SESSION['Position'] == 'Gerente' || $_SESSION['Position'] == 'Control') && $Estado == 'Abierto') {
            echo '<div class="container mt-5">
        
                <div class="card">
                    <div class="card-header bg-outline-primary text-Dark">
                        <h5 class="card-title text-center">Aceptación de la Solicitud - Estado:<strong> ' . $Estado . ' </strong></h5>
                        
                    </div>
                    <div class="card-body">
                        <div class="row">
        
                            <div class="col-md-6 text-center">
                                <p><strong>Gerente:</strong> <span id="aceptadoGerente">' . $Aceptado_Gerente . '</span></p>
        
                            </div>
                            <div class="col-md-6 text-center">
                                <p><strong>Control:</strong> <span id="aceptadoControl">' . $Aceptado_Control . '</span></p>
                                </div>
                        </div>
                        <div class="mb-3 text-center">
                            <div class="row">
                                <div class="col-md-6">
                                    
                                        <p>(Aun no aceptado por el Usuario Control)</p>
                                </div>
                                <div class="col-md-6">
                                    <a href="../../resources/Back/Viaticos/VerificationViatico.php?id=' . $Id_Viatico . '&Estado=Rechazado" class="btn btn-danger btn-block">
                                        Rechazar Solicitud
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
        
                </div>
            </div>';
        }
    } else {

        if (($_SESSION['Position'] == 'Gerente' || $_SESSION['Position'] == 'Control') && $Estado == 'Abierto') {
            echo '<div class="container mt-5">
                <div class="card">
                    <div class="card-header bg-outline-primary text-dark">
                        <h5 class="card-title text-center">Aceptación de la Solicitud - Estado: <strong><?php echo $Estado; ?></strong></h5>
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <div class="row w-100">
                            <!-- Column for Gerente and Control -->
                            <div class="col-md-6 d-flex flex-column justify-content-center align-items-center mb-3">
                                <p><strong>Gerente:</strong> <span id="aceptadoGerente">' . $Aceptado_Gerente . '</span></p>
                                <p><strong>Control:</strong> <span id="aceptadoControl">' . $Aceptado_Control . '</span></p>
                            </div>

                            <!-- Column for Buttons -->
                            <div class="col-md-6 d-flex flex-column justify-content-center align-items-center">
                                <a href="../../resources/Back/Viaticos/VerificationViatico.php?id=' . $Id_Viatico . '&Estado=Aceptado"
                                class="btn btn-success w-75 mb-2">
                                    Aceptar Solicitud
                                </a>
                                <a href="../../resources/Back/Viaticos/VerificationViatico.php?id=' . $Id_Viatico . '&Estado=Rechazado"
                                class="btn btn-danger w-75">
                                    Rechazar Solicitud
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        }
    }
    ?>

    <div class="container mt-5">
        <div class="row">
            <!-- Card de la parte de Izquierda -->
            <div class="col-md-6 mb-3">
                <div class="card">
                    <?php
                    // PHP code to determine the class based on the status
                    $headerClass = ''; // Default class
                    
                    switch ($Estado) {
                        case 'Abierto':
                            $headerClass = 'bg-primary text-white';
                            $ButtonColor = 'btn btn-warning';
                            break;
                        case 'Aceptado':
                            $headerClass = 'bg-success';
                            $ButtonColor = 'btn btn-warning';
                            break;
                        case 'Rechazado':
                            $headerClass = 'bg-danger text-white';
                            $ButtonColor = 'btn btn-warning';
                            break;
                        case 'Verificacion':
                            $headerClass = 'bg-warning';
                            $ButtonColor = 'btn btn-success';
                            break;
                        case 'FueraPeriodo':
                            $headerClass = 'bg-secondary';
                            $ButtonColor = 'btn btn-warning';
                            break;
                        default:
                            $headerClass = 'bg-primary';
                            $ButtonColor = 'btn btn-warning';
                            break;
                    }
                    ?>
                    <div class="card-header card-header-custom <?php echo $headerClass ?> text-white">
                        <h5 class="card-title text-center "><i class="fas fa-info-circle"></i> Detalles del Viático No.
                            <?php echo $Id_Viatico; ?>
                        </h5>
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <p><strong>Fecha de Solicitud:</strong> <span
                                    id="fechaSolicitud"><?= $Fecha_Registro ?></span></p>
                            <p><strong>Días Solicitados:</strong> <span><?php
                            $datetime1 = new DateTime($Fecha_Salida);
                            $datetime2 = new DateTime($Fecha_Regreso);
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
                                            id="fechaSalida"><?= $Fecha_Salida ?></span></p>
                                    <p><strong>Hora de Salida:</strong> <span id="horaSalida"><?= $Hora_Salida ?></span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Fecha de Regreso:</strong> <span
                                            id="fechaRegreso"><?= $Fecha_Regreso ?></span></p>
                                    <p><strong>Hora de Regreso:</strong> <span
                                            id="horaRegreso"><?= $Hora_Regreso ?></span></p>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-3 ">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Solicitante:</strong> <span id="fechaSalida"></span></p>
                                    <p><?= $Solicitante ?></p>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Gerente:</strong> <span id="fechaRegreso"></span></p>
                                    <p><?= $nombreGerente ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <!-- Mostrar los conceptos en una tabla -->
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Concepto</th>
                                        <th>Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $MontoTotal = 0;
                                    foreach ($conceptos as $concepto => $monto) {
                                        echo "<tr>";
                                        echo "<td>$concepto</td>";
                                        echo "<td>$ $monto</td>";
                                        echo "</tr>";
                                        $MontoTotal += $monto;

                                    }
                                    ?>
                                </tbody>
                            </table>
                            <hr>
                            <div class="row text-center">
                                <div class="mb-3 text-center">
                                    <p class="text-center">
                                        <strong style="margin-right: 5px;">Monto Total Solicitado:</strong>
                                        <span>$ <?= $MontoTotal ?></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header card-header-custom <?php echo $headerClass ?> text-white">
                        <h5 class="card-title text-center"><i class="fas fa-map-marker-alt"></i>Estado:
                            <?php echo $Estado ?>
                        </h5>
                        <hr>
                        <div class="text-center">
                            <?php
                            /// Consultar Nombre del archivo para descagar
                            $sql = "SELECT * FROM resumen_solicitud WHERE Id_Viatico = $Id_Viatico";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();
                            $Nombre_Archivo = $row['Nombre'];
                            $Ruta = '../../uploads/files/' . $Nombre_Archivo;
                            ?>
                            <a href="<?php echo $Ruta ?>" class="btn <?php echo $ButtonColor ?> ">Descargar Formato</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <p><strong>Orden de Venta:</strong> <span><?= $Orden_Venta ?></span></p>
                            <p><strong>Código:</strong> <span><?= $Codigo ?></span></p>
                            <p><strong>Proyecto:</strong> <span><?= $Nombre_Proyecto ?></span></p>
                            <p><strong>Destino:</strong> <span><?= $Destino ?></span></p>
                        </div>
                        <hr>
                        <!-- mostrar el destino en una tabla -->
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Estado</th>
                                    <th>Ciudad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php
                                    if (empty($destino['Estado'])) {
                                        echo 'No especificado';
                                    } else {
                                        echo $destino['Estado'];
                                    }

                                    ?></td>
                                    <td><?php
                                    if (empty($destino['Ciudad'])) {
                                        echo 'No especificado';
                                    } else {
                                        echo $destino['Ciudad'];
                                    }
                                    ?></td>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <hr>
                        <div class="mb-3">
                            <p><strong>Acompañantes:</strong></p>
                            <ul>
                                <?php
                                foreach ($acompanantes as $acompanante) {
                                    echo "<li>" . $acompanante['Nombre'] . "</li>";
                                }
                                ?>
                            </ul>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <p><strong>Clientes:</strong></p>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Motivo</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($clientes as $cliente) {
                                        echo "<tr>";
                                        echo "<td>" . $cliente['Nombre'] . "</td>";
                                        echo "<td>" . $cliente['Motivo'] . "</td>";
                                        echo "<td>" . $cliente['Fecha'] . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>



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
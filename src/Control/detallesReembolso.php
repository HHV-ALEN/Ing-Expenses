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
$id_reembolso = $_GET['id_reembolso'];

/// Hacer int 
$id_reembolso = (int) $id_reembolso;

$sql_reembolsos = "SELECT * FROM reembolso WHERE Id = '$id_reembolso'";

$result_reembolsos = $conn->query($sql_reembolsos);
if ($result_reembolsos->num_rows > 0) {
    $row_reembolsos = $result_reembolsos->fetch_assoc();
    $id_viatico = $row_reembolsos['Id_Viatico'];
    $monto = $row_reembolsos['Monto'];
    $destino = $row_reembolsos['Destino'];
    $descripcion = $row_reembolsos['Descripcion'];
    $concepto = $row_reembolsos['Concepto'];
    $estado = $row_reembolsos['Estado'];
    $Id_UsuarioCreador = $row_reembolsos['Id_Usuario'];
} else {
    echo "Error: " . $sql_reembolsos . "<br>" . $conn->error;
}

// Verificar si el arreglo de estados está vacío
if (empty($_SESSION['Estados'])) {
    // Si la bandera de recarga no está definida
    if (!isset($_SESSION['reloaded'])) {
        // Establecer la bandera de recarga
        $_SESSION['reloaded'] = true;

        // Recargar la página después de 1 segundo
        echo '<script>setTimeout(function(){ window.location.href = "detallesReembolso.php?id_reembolso=' . $id_reembolso . '"; }, 1000);</script>';

        // Detener la ejecución para evitar que el código continúe
        exit();
    } else {
        // Si la página ya se recargó, eliminar la bandera para futuras visitas
        unset($_SESSION['reloaded']);
    }
}

/// Obtener Información del creador del reembolso
$sql_user = "SELECT * FROM usuarios WHERE ID = $Id_UsuarioCreador";
$result_user = $conn->query($sql_user);
if ($result_user->num_rows > 0) {
    $row_user = $result_user->fetch_assoc();
    $Nombre_Usuario = $row_user['Nombre'];
} else {
    echo "Error: " . $sql_user . "<br>" . $conn->error;
}


$sql_sum = "SELECT SUM(Monto) AS TotalMonto FROM reembolsos_anidados WHERE Id = $id_reembolso";
$result_sum = $conn->query($sql_sum);
if ($result_sum->num_rows > 0) {
    $row_sum = $result_sum->fetch_assoc();
    $total_monto = $row_sum['TotalMonto'];
} else {
    echo "Error: " . $sql_sum . "<br>" . $conn->error;
}
$total_monto = $total_monto + $monto;

/// Crear un Arreglo que guardara los estados de los reembolsos

$Reembolso = array();
$Datos = array();

$Datos[] = array(
    'Nombre' => $Nombre_Usuario,
    'Id_Reembolso' => $id_reembolso,
    'Id_Viatico' => $id_viatico,
    'Monto' => $monto,
);   

$_SESSION['Datos'] = $Datos;

$Estados = array();
$_SESSION['Estados'] = $Estados;

$images = [];
$zip = new ZipArchive();
$zip_name = "reembolsos_imagenes_" . time() . ".zip"; // Nombre del archivo ZIP
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Reembolso No. <?php echo $id_reembolso ?></title>
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
            padding: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
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

        .custom-image {
    width: 100%; /* Ajusta el ancho al 100% del contenedor */
    height: auto; /* Mantén la proporción de la imagen */
    max-width: 500px; /* Máximo ancho de la imagen */
    max-height: 500px; /* Máximo alto de la imagen */
    object-fit: cover; /* Asegura que la imagen cubra el contenedor sin distorsión */
}

.custom-image-pdf {
    width: 100%; /* Ajusta el ancho al 100% del contenedor */
    height: auto; /* Mantén la proporción de la imagen */
    max-width: 100px; /* Máximo ancho de la imagen */
    object-fit: cover; /* Asegura que la imagen cubra el contenedor sin distorsión */
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
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class=" text-center">
                        <div class="card-header-custom">
                            <br>
                            <h4><?php
                            echo "<br>";
                            echo "Reembolso No. " . $id_reembolso;
                            
                            ?></h4>
                            <h6><strong>Estado de la solicitud: </strong>(<?php echo $estado ?>)</h6> 
                            <label for="total">Monto total acumulado: <?= number_format($total_monto, 2, '.', "'") ?> $</label>
                            <br>
                            <br>
                            <?php 
                                if ($_SESSION['Position'] == 'Control') {
                                    ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php
                                            if (isset($_GET['success'])) {
                                                echo '<div class="alert alert-success">Folio actualizado correctamente.</div>';
                                            } elseif (isset($_GET['error'])) {
                                                $error = $_GET['error'];
                                                if ($error == 1) {
                                                    echo '<div class="alert alert-danger">Ya existe un registro con ese folio.</div>';
                                                } elseif ($error == 2) {
                                                    echo '<div class="alert alert-danger">No se pudo actualizar el folio.</div>';
                                                }
                                            }
                                            ?>
                                            <form action="<?php echo 'fixtures/changeID.php?id_reembolso=' . $id_reembolso; ?>" method="POST">
                                                <div class="form-group">
                                                    <input placeholder="---- ID CORRECTO ---" name="idFolioCorrecto" type="number">
                                                </div><br>
                                                <button type="submit" class="btn btn-primary">Corregir Folio</button>
                                            </form>
                                        </div>
                                    </div>

                                    <?php
                                }
                            
                            ?>
                            <br>
                    </div>
                    <br>
                    <div class="text-center">
                        <div class="row">
                            <div class="col-md-6">
                                <label><strong> Resumen de reembolsos</strong></label><br><hr>
                                <a class="btn btn-success" href="resumenReembolsos.php">Exportar</a>
                            </div>
                            <div class="col-md-6">
                            <?php 

                            
                            foreach ($_SESSION['Estados'] as $estado): ?>
                            <div class="card">
                                <p class="card-text text-center estado-folio"><strong>Estado:</strong><br><?php echo $estado['Estado']; ?></p>
                            </div>
                        <?php endforeach; ?>
                        
                        <!-- Botón para finalizar folios -->
                        <label><strong>Finalizar solicitud de reembolsos</strong></label><br><hr>
                        <a id="finalizar-btn" class="btn btn-primary" href="../../../../resources/Back/Viaticos/FinalizarReembolso.php" style="display: none;">Finalizar Folio</a>
                        <button id="finalizar-btn-disabled" class="btn btn-primary" type="button" >Finalizar Folio</button>
                        
<
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                // Seleccionar todos los elementos que contienen el estado de los folios
                                const estados = document.querySelectorAll('.estado-folio');
                                let todosAceptados = true;

                                // Crear un array para almacenar los estados
                                let estadosArray = [];

                                // Recorrer todos los elementos y obtener el texto de los estados
                                estados.forEach(function(estado) {
                                    const estadoTexto = estado.textContent.trim().replace('Estado:', '').trim();
                                    estadosArray.push(estadoTexto);
                                    console.log(estadosArray);

                                    // Verificar si algún estado es diferente a "Aceptado" o "Rechazado"
                                    if (estadoTexto !== 'Aceptado' && estadoTexto !== 'Rechazado' && estadoTexto !== 'Completado') {
                                        todosAceptados = false;
                                    }
                                });

                                // Mostrar u ocultar el botón basado en la verificación
                                const finalizarBtn = document.getElementById('finalizar-btn');
                                const finalizarBtnDisabled = document.getElementById('finalizar-btn-disabled');

                                if (todosAceptados) {
                                    finalizarBtn.style.display = 'inline-block';
                                    finalizarBtnDisabled.style.display = 'none';
                                } else {
                                    finalizarBtn.style.display = 'none';
                                    finalizarBtnDisabled.style.display = 'inline-block';
                                }

                                // (Opcional) Imprimir el array de estados en la consola para verificar
                                console.log(estadosArray);
                            });


                            </script>
                                
                            </div>

                        </div>
                    </div>
                    <hr>
                </div>
                <div class="row">
                    <div class="col-md-6 text-center">
                    <?php 
                        if ($id_viatico == 0) {
                            echo "Anidado al Reembolso: " . $id_reembolso;
                            /// Tomar los Id_Anidados a este id_reembolso
                        } else {
                            echo "Viatico: " . $id_viatico;
                        }
                        ?>
                    </div>
                    <div class="col-md-6 text-center">
                        <p>Solicitado por: <?php echo $Nombre_Usuario ?></p>
                    </div>
                </div>

                 
            </div>
            <div class="card-body">
                <form action="../../resources/Back/Viaticos/agregarReembolso.php"
                method="POST" enctype="multipart/form-data">
                <div class="container">
                </div>
                <?php 
                if ($Id_UsuarioCreador == $id_user) {
                    ?>
                    <div class="container">
                        <h4 class="text-center">Agregar Reembolso:</h4>
                        <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group text-center">
                                        <strong><label for="id">ID Reembolso:</label></strong>
                                        <input type="number" class="form-control" id="folio" name="folio" value="<?php echo $id_reembolso ?>"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group text-center">
                                        <strong><label for="id">Destino:</label></strong>
                                        <input type="text" class="form-control" id="destino" name="destino" value="<?php echo $destino ?>">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group text-center">
                                        <strong><label for="quantity">Monto:</label></strong>
                                        <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Ingrese un monto...">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                <div class="form-group text-center">
                                        <strong><label for="quantity">Descripcion:</label></strong>
                                        <input type="text" class="form-control" id="desc" name="desc" placeholder="Ingrese una descripción...">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                <div class="form-group text-center">
                                        <strong><label for="concept">Concepto:</label></strong>
                                        <select class="form-control" id="concept" name="concept" required>
                                            <option value="">Seleccione un concepto</option>
                                            <option value="Hospedaje">Hospedaje</option>
                                            <option value="Gasolina">Gasolina</option>
                                            <option value="Casetas">Casetas</option>
                                            <option value="Alimentacion">Alimentos</option>
                                            <option value="Vuelos">Vuelos</option>
                                            <option value="Transporte">Transporte</option>
                                            <option value="Estacionamiento">Estacionamiento</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group text-center">
                                        <br>
                                        <strong><label for="image">Agregar Evidencia:</label></strong>
                                        <input type="file" class="form-control-file" id="file" name="file" required>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Registrar Reembolso</button>
                                    </div>
                                </div>
                            </div>
                            <hr>
                    </div>
                    <?php
                } 
                ?>
                </form> 
            </div>
            <!-- Fin del card-body -->

            <div id="notification" class="alert" style="display:none;"></div>

            <!-- Inicio de la tabla de reembolsos -->
            <div class="row text-center" id="editReembolso">
            <h4>Originales:</h4>  
            <hr>
            <?php 
                 if ($id_viatico == 0) {
                     /// Mostrar reembolso original en donde se anidaran los demas reembolsos
                        $Query = "SELECT * FROM reembolso WHERE Id = '$id_reembolso'";
                 } else {
                     /// Mostrar reembolso original en donde se anidaran los demas reembolsos
                        $Query = "SELECT * FROM reembolso WHERE Id_Viatico = '$id_viatico'";
                 }

                $image_Result = $conn->query($Query);
                while ($image_Row = $image_Result->fetch_assoc()) {
                    $Id_Reembolso = $image_Row['Id'];
                    $Id_UsuarioCreador = $image_Row['Id_Usuario'];
                    $image = $image_Row['Imagen'];
                    $Descripcion = $image_Row['Descripcion'];
                    $Cantidad = $image_Row['Monto'];
                    $concepto = $image_Row['Concepto'];
                    $Destino = $image_Row['Destino'];
                    $Estado = $image_Row['Estado'];
                    $Id_Usuario = $image_Row['Id_Usuario'];
                    $Id_Gerente = $image_Row['Id_Gerente'];
                    $FechaRegistro = $image_Row['Fecha_Registro'];

                    array_push($Estados, array(
                        'Id_Reembolso' => $Id_Reembolso,
                        'Estado' => $Estado,
                    ));

                    // Consulta para el reembolso original
                    $image_Result = $conn->query($Query);
                    while ($image_Row = $image_Result->fetch_assoc()) {
                        $images[] = "../../uploads/" . $image_Row['Imagen'];
                    }


                    //echo "<br>---------- Reembolso Original ----------<br>";
                    //print_r($Estados);

                    $Reembolso[$Id_Reembolso] = array(
                        'Id_UsuarioCreador' => $Id_UsuarioCreador,
                        'Imagen' => $image,
                        'Descripcion' => $Descripcion,
                        'Cantidad' => $Cantidad,
                        'Concepto' => $concepto,
                        'Destino' => $Destino,
                        'Estado' => $Estado,
                        'FechaRegistro' => $FechaRegistro
                    );             
                    ?>
                <div class="col-md-4">
                    <?php 
                    ?>
                    <div class="card" >
                        <?php
                        $Extension = pathinfo($image, PATHINFO_EXTENSION);
                        if ($Extension == 'pdf' or $Extension == 'PDF') {
                            ?>
                            <img src="../../uploads/pdf-icon.png" class="card-img-top custom-image-pdf" alt="..." data-toggle="modal"
                                 data-target="#imageModal" data-image="../../uploads/pdf-icon.png">
                            <?php
                        } else {
                            ?>
                            <img src="../../uploads/<?php echo $image ?>" class="card-img-top custom-image" alt="..." data-toggle="modal"
                                 data-target="#imageModal" data-image="../../uploads/<?php echo $image ?>">
                            <?php
                        }
                        if ($Estado == 'Aceptado') {
                            ?>
                            <div class="card-header bg-success text-white">
                                Aceptado
                            </div>
                            <?php
                        } elseif ($Estado == 'Rechazado') {
                            ?>
                            <div class="card-header bg-danger text-white">
                                Rechazado
                            </div>
                            <?php
                        } elseif ($Estado == 'Completado') {
                            ?>
                            <div class="card-header bg-success text-white">
                                Completado
                            </div>
                            <?php
                        } elseif ($Estado == 'Abierto') {
                            ?>
                            <div class="card-header bg-warning text-white">
                                Abierto
                            </div>
                            <?php
                        } elseif ($Estado == 'Verificacion') {
                            ?>
                            <div class="card-header bg-info text-white">
                                Verificación
                            </div>
                            <?php
                        }
                        ?>

                        <div class="card-body">
                            <p class="card-text text-center"><strong>Fecha:</strong> <br> <?php echo $FechaRegistro ?></p>
                            <p class="card-text text-center"><strong>Id:</strong><br><?php echo $Id_Reembolso ?></p>
                            <p class="card-text text-center estado-folio"><strong>Estado:</strong><br><?php echo $Estado ?></p>
                            <p class="card-text text-center"><strong>Concepto:</strong><br><?php echo $concepto ?></p>
                            <p class="card-text text-center"><strong>Descripción:</strong><br><?php echo $Descripcion ?></p>
                            <p class="card-text text-center"><strong>Monto:</strong><br><?= number_format($Cantidad, 2, '.', "'") ?></p>
                            <p class="card-text text-center"><strong>Destino:</strong><br><?php echo $Destino ?></p>
                            <hr>
                            <?php
                                if ($Id_UsuarioCreador == $id_user) {
                                    ?>
                                        <div class="row  justify-content-center">
                                            <button type="button" class="btn btn-warning btn-block edit_data"
                                                data-bs-toggle="modal" data-target="#showDetails"
                                                value="<?= $image ?>" data-id="<?= $image ?>">Editar
                                            </button>
                                        </div>
                                        <hr>
                                        <div class="row  justify-content-center">
                                            <a href="../../uploads/<?php echo $image ?>" download class="btn btn-primary btn-block">Descargar</a>
                                        </div>
                                        <hr>
                                        <div class="row  justify-content-center">
                                            <a href="../../resources/Back/Viaticos/deleteReembolso.php?name=<?php echo $image ?>&id_reembolso=<?php echo $id_reembolso ?>&source=reembolso"
                                            class="btn btn-danger btn-block">Eliminar</a>
                                        </div>
                                    <?php
                                } else
                                {
                                    ?>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <a href="../../uploads/<?php echo $image ?>" download class="btn btn-primary">Descargar</a>
                                        </div>
                                        <div class="col-md-6">
                                            <a href="../../resources/Back/Viaticos/deleteReembolso.php?name=<?php echo $image ?>&id_reembolso=<?php echo $id_reembolso ?>&source=reembolso"
                                            class="btn btn-danger">Eliminar</a>
                                        </div>
                                    </div>
                                <?php
                                }
                                if($Puesto === 'Control' || $Puesto === 'Gerente'){
                                    ?>
                                    <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Aceptar Reembolso -->
                                        <a href='../../resources/Back/Viaticos/changeStateReembolso.php?id_reembolso=<?= $Id_Reembolso ?>&Respuesta=Aceptado&Source=Original&Id_Folio_Reembolso=<?= $id_reembolso ?>'
                                        type="button" class="btn btn-outline-primary">
                                            Aceptar Reembolso
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- Rechazar Reembolso -->
                                        <a href='../../resources/Back/Viaticos/changeStateReembolso.php?id_reembolso=<?= $Id_Reembolso ?>&Respuesta=Rechazado&Source=Original'
                                        type="button" class="btn btn-outline-danger">
                                        Rechazar Reembolso
                                        </a>
                                    </div>
                                </div>
                                <?php
                                }
                                
                            ?>
                            

                            <hr>
                            <?php

                             /// Verificar el estado de verificacion de este reembolso
                             $query_Verificacion = "SELECT * FROM verificacion WHERE Id_Reembolso = $Id_Reembolso";
                             $result_Verificacion = $conn->query($query_Verificacion);

                             ///Si no tiene nada en la tabla hacer un isset a la variable
                             if ($result_Verificacion->num_rows > 0) {
                                 $row_Verificacion = $result_Verificacion->fetch_assoc();
                                 $Aceptado_Gerente = $row_Verificacion['Aceptado_Gerente'];
                                 $Aceptado_Control = $row_Verificacion['Aceptado_Control'];
                                 $Gerente = $row_Verificacion['Gerente'];
                                 $Verificador = $row_Verificacion['Verificador'];
                             } else {
                                 $Aceptado_Gerente = '';
                                 $Aceptado_Control = '';
                                 $Gerente = '';
                                 $Verificador = '';
                             }
                             
                            ?>
                            
                            <div class="container" style="display: flex; justify-content: center; align-items: center; ">
                                <?php 
                                if ($Aceptado_Control == '' || $Aceptado_Control == 'Pendiente'){
                                    echo "
                                    <label for=''>Control: &nbsp;</label>
                                    <input type='checkbox'></input>";
                                } else {
                                    echo "Control: &nbsp;<input type='checkbox' checked></input>";
                                } 
                                ?>
                            </div>
                            <div class="container" style="display: flex; justify-content: center; align-items: center; ">
                                <?php 
                                if ($Aceptado_Gerente == '' || $Aceptado_Gerente == 'Pendiente'){
                                    echo "
                                    <label for=''>Gerente: &nbsp;</label>
                                    <input type='checkbox'></input>";
                                } else {
                                    echo "Gerente: &nbsp;<input type='checkbox' checked></input>";
                                } 
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <h4>Anidados:</h4>
            <hr>
            <?php 
                if ($id_viatico == 0) {
                    /// Mostrar Reembolsos Anidados
                    $image_Query = "SELECT * FROM reembolsos_anidados WHERE Anidado = '$id_reembolso'";
                } else {
                    /// Mostrar Reembolsos Anidados
                    $image_Query = "SELECT * FROM reembolsos_anidados WHERE Anidado = '$id_viatico'";
                }
                $image_Result = $conn->query($image_Query);

                if ($image_Result) {
                    if ($image_Result->num_rows > 0) {
                        while ($image_Row = $image_Result->fetch_assoc()) {
                            $Id_Reembolso_Anidado = $image_Row['Id_Reembolso_Anidado'];
                            $Id_Reembolso = $image_Row['Id'];
                            $Id_UsuarioCreador = $image_Row['Id_Usuario'];
                            $image = $image_Row['Imagen'];
                            $Descripcion = $image_Row['Descripcion'];
                            $Cantidad = $image_Row['Monto'];
                            $concepto = $image_Row['Concepto'];
                            $Destino = $image_Row['Destino'];
                            $Estado = $image_Row['Estado'];
                            $Id_Usuario = $image_Row['Id_Usuario'];
                            $Id_Gerente = $image_Row['Id_Gerente'];
                            $FechaRegistro = $image_Row['Fecha_Registro'];
                            $IdAnidado = $image_Row['Anidado'];

                            array_push($Estados, array(
                                'Id_Reembolso' => $Id_Reembolso,
                                'Estado' => $Estado,
                            ));

                            // Consulta para los reembolsos anidados
                            $image_Result_Anidado = $conn->query($image_Query);
                            while ($image_Row = $image_Result_Anidado->fetch_assoc()) {
                                $images[] = "../../uploads/" . $image_Row['Imagen'];
                            }

                            //echo "<br>---------- Anidado al Reembolso: $IdAnidado ----------<br>";
                            //print_r($Estados);

                            $Reembolso[$Id_Reembolso_Anidado] = array(
                                'Id_UsuarioCreador' => $Id_UsuarioCreador,
                                'Imagen' => $image,
                                'Descripcion' => $Descripcion,
                                'Cantidad' => $Cantidad,
                                'Concepto' => $concepto,
                                'Destino' => $Destino,
                                'Estado' => $Estado,
                                'FechaRegistro' => $FechaRegistro
                            );              
            ?>
            <div class="col-md-4">
                <div class="card" >
                    <?php
                    $Extension = pathinfo($image, PATHINFO_EXTENSION);
                    if ($Extension == 'pdf' or $Extension == 'PDF') {
                        ?>
                        <img src="../../uploads/pdf-icon.png" class="card-img-top custom-image" alt="..." data-toggle="modal"
                             data-target="#imageModal" data-image="../../uploads/pdf-icon.png">
                        <?php
                    } else {
                        ?>
                        <img src="../../uploads/<?php echo $image ?>" class="card-img-top custom-image" alt="..." data-toggle="modal"
                             data-target="#imageModal" data-image="../../uploads/<?php echo $image ?>">
                        <?php
                    }
                    if ($Estado == 'Aceptado') {
                        ?>
                        <div class="card-header bg-success text-white">
                            Aceptado
                        </div>
                        <?php
                    } elseif ($Estado == 'Rechazado') {
                        ?>
                        <div class="card-header bg-danger text-white">
                            Rechazado
                        </div>
                        <?php
                    } elseif ($Estado == 'Completado') {
                        ?>
                        <div class="card-header bg-success text-white">
                            Completado
                        </div>
                        <?php
                    }
                    elseif ($Estado == 'Abierto') {
                        ?>
                        <div class="card-header bg-warning text-white">
                            Abierto
                        </div>
                        <?php
                    } elseif ($Estado == 'Verificacion') {
                        ?>
                        <div class="card-header bg-info text-white">
                            Verificación
                        </div>
                        <?php
                    }
                    ?>

                    <div class="card-body" >

                    <p class="card-text text-center"><strong>Fecha:</strong> <br> <?php echo $FechaRegistro ?></p>
                    <p class="card-text text-center"><strong>Ligado al Folio:</strong><br><?php echo $Id_Reembolso  ?></p>
                    <p class="card-text text-center estado-folio"><strong>Estado:</strong><br><?php echo $Estado ?></p>
                    <p class="card-text text-center"><strong>Concepto:</strong><br><?php echo $concepto ?></p>
                    <p class="card-text text-center"><strong>Descripción:</strong><br><?php echo $Descripcion ?></p>
                    <p class="card-text text-center"><strong>Monto:</strong><br><?= number_format($Cantidad, 2, '.', "'") ?></p>
                    <p class="card-text text-center"><strong>Destino:</strong><br><?php echo $Destino ?></p>
                    <hr>

                    <?php
                        if ($Id_UsuarioCreador == $id_user) {
                            ?>
                                <div class="row  justify-content-center">
                                    <button type="button" class="btn btn-warning btn-block edit_data"
                                        data-bs-toggle="modal" data-target="#showDetails"
                                        value="<?= $image ?>" data-id="<?= $image ?>">Editar
                                    </button>
                                </div>
                                <hr>
                                <div class="row  justify-content-center">
                                    <a href="../../uploads/<?php echo $image ?>" download class="btn btn-primary btn-block">Descargar</a>
                                </div>
                                <hr>
                                <div class="row  justify-content-center">
                                    <a href="../../resources/Back/Viaticos/deleteReembolso.php?name=<?php echo $image ?>&id_reembolso=<?php echo $id_reembolso ?>&source=reembolso"
                                    class="btn btn-danger btn-block">Eliminar</a>
                                </div>
                            <?php
                        } else
                        {
                            ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="../../uploads/<?php echo $image ?>" download class="btn btn-primary">Descargar</a>
                                </div>
                                <div class="col-md-6">
                                    <a href="../../resources/Back/Viaticos/deleteReembolso.php?name=<?php echo $image ?>&id_reembolso=<?php echo $id_reembolso ?>&source=reembolso"
                                    class="btn btn-danger">Eliminar</a>
                                </div>
                            </div>
                        <?php
                        }
                        if($Puesto === 'Control'){
                            ?>
                            <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Aceptar Reembolso -->
                                <a href='../../resources/Back/Viaticos/changeStateReembolso.php?id_reembolso=<?= $Id_Reembolso_Anidado ?>&Respuesta=Aceptado&Source=Anidado&Id_Folio_Reembolso=<?= $id_reembolso ?>'
                                        type="button" class="btn btn-outline-primary">
                                            Aceptar Reembolso
                                        </a>
                            </div>
                            <div class="col-md-6">
                                <!-- Rechazar Reembolso -->
                                <a href='../../resources/Back/Viaticos/changeStateReembolso.php?id_reembolso=<?= $Id_Reembolso_Anidado ?>&Respuesta=Rechazado&Source=Anidado'
                                type="button" class="btn btn-outline-danger">
                                Rechazar Reembolso
                                </a>
                            </div>
                        </div>
                        <?php
                        }
                    ?>

                    <hr>
                        <?php
                            /// Los reembolsos anidados no tienen verificacion, solo el Atributo Estado
                            $query_Verificacion = "SELECT * FROM reembolsos_anidados WHERE Id_Reembolso_Anidado = $Id_Reembolso_Anidado";
                            $result_Verificacion = $conn->query($query_Verificacion);
                            ///Si no tiene nada en la tabla hacer un isset a la variable
                            if ($result_Verificacion->num_rows > 0) {
                                $row_Verificacion = $result_Verificacion->fetch_assoc();
                                $Estado = $row_Verificacion['Estado'];
                            } else {
                                $Estado = '';
                            }

                        ?>
                        <div class="container" style="display: flex; justify-content: center; align-items: center; ">
                            <?php 
                            
                            if ($Estado != 'Abierto' ){
                                echo "Control: <input type='checkbox' checked></input>";
                                
                            } else {
                                echo "
                                <label for=''>Control: &nbsp;</label>
                                <input type='checkbox'></input>";
                            } 
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
                echo "No hay registros anidados.";
            }
        } else {
            
            echo "Error al ejecutar la consulta: " . $conn->error;
        }
        // Crear archivo ZIP con todas las imágenes
        $zip = new ZipArchive();
        $zip_name = "ZipFiles/reembolsos_imagenes_" . time() . ".zip"; // Nombre del archivo ZIP

        if ($zip->open($zip_name, ZipArchive::CREATE) !== TRUE) {
            exit("No se pudo crear el archivo ZIP.");
        }

        $errores = [];

        foreach ($images as $image) {
            if (file_exists($image)) {
                $zip->addFile($image, basename($image));
            } else {
                // Si la imagen no existe, agregar un mensaje de error al array de errores
                $errores[] = "El archivo $image no se encontró y no se pudo agregar al ZIP.";
            }
        }

$zip->close();

// Mostrar botón de descarga si el archivo ZIP fue creado
if (file_exists($zip_name)) {
    echo '<br><hr><br>';
    echo '<div class="row justify-content-center">';
    echo '<a href="' . $zip_name . '" download class="btn btn-primary btn-block">Descargar Todos los Archivos</a>';
    echo '</div>';
}

// Mostrar errores si existen
if (!empty($errores)) {
    echo '<div class="alert alert-warning">';
    echo '<strong>Advertencia:</strong><br>';
    foreach ($errores as $error) {
        echo $error . "<br>";
    }
    echo '</div>';
    echo '<br>';
}
        ?>

        </div>
        </div>
    </div>
    </div>
    </div>

    <br>
    <!-- Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageModalLabel">Imagen Completa</h5>
                        
                    </div>
                    <div class="modal-body">
                        <img id="modalImage" src="" alt="Imagen Completa" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar -->
    <div class="modal fade" id="editusermodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header header">
                    <h5 class="modal-title" style="color: green" id="exampleModalLongTitle">Edición de Usuario</h5>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="view_edit_data">
                            <!-- Aqui se mostraran los detalles de la etiqueta -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    echo "<br>";
    // Almacenar el array en la sesión
    $_SESSION['Reembolso'] = $Reembolso;
    $_SESSION['Estados'] = $Estados;
    //print_r($_SESSION['Estados']);
    ?>


    <!-- Bootstrap JS and dependencies (Popper.js and jQuery) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        $(document).ready(function () {
            $('.card-img-top').click(function () {
                var src = $(this).attr('data-image');
                $('#modalImage').attr('src', src);
                $('#imageModal').modal('show'); // Asegurarse de que el modal se muestra
            });
        });

        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
            
            if (status === 'success') {
                $('#notification').addClass('alert-success').text('El registro se eliminó correctamente.').show();
            } else if (status === 'error') {
                $('#notification').addClass('alert-danger').text('Error al eliminar el registro.').show();
            } else if (status === 'not_found') {
                $('#notification').addClass('alert-warning').text('No se puede eliminar la evidencia ligada al folio.').show();
            }
        });

        
// Editar Usuario
$(document).ready(function () {
            // Adjuntar evento clic a los botones "Editar" generados dinámicamente
            $('#editReembolso').on('click', '.edit_data', function () {
                var ImageName = $(this).data('id');
                console.log("Nombre: " + ImageName)
                $.ajax({
                    method: 'POST',
                    url: '../modals/Reembolso/editar.php', // Archivo que se encargara de mostrar los detalles
                    data: {
                        'click_edit_btn': true,
                        'ImageName': ImageName,
                    },
                    success: function (response) {
                        console.log(response); // Muestra la respuesta en consola
                        $('.view_edit_data').html(response);
                        $('#editusermodal').modal('show');
                    }
                });

            });
            $('.closeusermodal').click(function () {
                $('#editusermodal').modal('hide');
            });
        });
    </script>
</body>

</html>
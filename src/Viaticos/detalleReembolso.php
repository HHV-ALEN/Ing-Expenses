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
$id_reembolso = $_GET['id_reembolso'];
/// Hacer int 
$id_reembolso = (int) $id_reembolso;

$sql_reembolsos = "SELECT * FROM reembolso WHERE Id = '$id_reembolso'";

$result_reembolsos = $conn->query($sql_reembolsos);
if ($result_reembolsos->num_rows > 0) {
    $row_reembolsos = $result_reembolsos->fetch_assoc();
    $id_viatico = $row_reembolsos['Id_Viatico'];
    $monto = $row_reembolsos['Monto'];
    $descripcion = $row_reembolsos['Descripcion'];
    $concepto = $row_reembolsos['Concepto'];
    $estado = $row_reembolsos['Estado'];
} else {
    echo "Error: " . $sql_reembolsos . "<br>" . $conn->error;
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
            padding: 20px;
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
    <br>
    <div class="container">
        <div class="card">
            <div class="card-header">
                Solicitud de Reembolso (<?php echo $estado ?>)
            </div>
            <div class="card-body">
                
                <form action="../../resources/Back/Viaticos/agregarReembolso.php"
                method="POST" enctype="multipart/form-data">
                    <div class="container">
                        <h4>Agregar Reemboslo a este folio:</h4>
                        <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <strong><label for="id">ID Reembolso:</label></strong>
                                        <input type="number" class="form-control" id="folio" name="folio" value="<?php echo $id_reembolso ?>"
                                            readonly="readonly">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <strong><label for="id">Destino:</label></strong>
                                        <input type="text" class="form-control" id="destino" name="destino" placeholder="Ejemplo: Jalisco, CDMX, Tijuana">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <strong><label for="quantity">Monto:</label></strong>
                                        <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Ingrese un monto...">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                <div class="form-group">
                                        <strong><label for="quantity">Descripcion:</label></strong>
                                        <input type="text" class="form-control" id="desc" name="desc" placeholder="Ingrese una descripción...">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                <div class="form-group">
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
                                    <div class="form-group">
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
            
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <strong><label for="image">Monto Total: </label></strong><?php echo " " . $total_monto ?>
                                    </div>
                                </div>
                            </div>
                    </div>
                </form> 
            </div>
            <br>
            <hr>
            <div id="notification" class="alert" style="display:none;"></div>
            <div class="row" id="editReembolso">
            <?php
            $image_Query = "
                SELECT 'principal' AS tipo, Id, Imagen, Descripcion, Monto, Concepto, Destino FROM reembolso WHERE Id = '$id_reembolso'
                UNION ALL
                SELECT 'anidado' AS tipo, Id, Imagen, Descripcion, Monto, Concepto, Destino FROM reembolsos_anidados WHERE Id = '$id_reembolso';
            ";
            $image_Result = $conn->query($image_Query);
            while ($image_Row = $image_Result->fetch_assoc()) {
                $id_imagen = $image_Row['Id'];
                $image = $image_Row['Imagen'];
                $Descripcion = $image_Row['Descripcion'];
                $Cantidad = $image_Row['Monto'];
                $concepto = $image_Row['Concepto'];
                $Destino = $image_Row['Destino'];
                ?>
                <div class="col-md-4">
                    <div class="card">
                        <?php
                        $Extension = pathinfo($image, PATHINFO_EXTENSION);
                        if ($Extension == 'pdf' or $Extension == 'PDF') {
                            ?>
                            <img src="../../uploads/pdf-icon.png" class="card-img-top" alt="..." data-toggle="modal"
                                 data-target="#imageModal" data-image="../../uploads/pdf-icon.png">
                            <?php
                        } else {
                            ?>
                            <img src="../../uploads/<?php echo $image ?>" class="card-img-top" alt="..." data-toggle="modal"
                            data-target="#imageModal" data-image="../../uploads/<?php echo $image ?>">
                            <?php
                        }
                        ?>
                        <div class="card-body">
                            <p class="card-text"><strong>Concepto:</strong> <?php echo $concepto ?></p>
                            <p class="card-text"><strong>Descripción:</strong> <?php echo $Descripcion ?></p>
                            <p class="card-text"><strong>Monto:</strong> <?= number_format($Cantidad, 2, '.', "'") ?></p>
                            <p class="card-text"><strong>Destino:</strong> <?php echo $Destino ?></p>

                            <button type="button" class="btn btn-warning  edit_data"
                                                        data-bs-toggle="modal" data-target="#showDetails"
                                                        value="<?= $image ?>" data-id="<?= $image ?>">Editar</button>

                            <a href="../../uploads/<?php echo $image ?>" download class="btn btn-primary">Descargar</a>
                            <a href="../../resources/Back/Viaticos/deleteReembolso.php?name=<?php echo $image ?>&id_reembolso=<?php echo $id_reembolso ?>&source=reembolso"
                               class="btn btn-danger">Eliminar</a>

                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
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
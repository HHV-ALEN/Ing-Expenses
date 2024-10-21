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
$id_viatico = $_GET['id_viatico'];


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

        .card-img-top {
            max-width: 100%;
            height: auto;
            max-height: 200px;
            /* Ajusta el tamaño máximo según tus necesidades */
            object-fit: cover;
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
    <br>
    <div class="container">
        <div class="card">
            <div class="card-header">
                Solicitud de Reembolso
            </div>
            <div class="card-body">
                <form action="../../resources/Back/Viaticos/reembolso.php?id_viatico=<?php echo $id_viatico ?>"
                    method="POST" enctype="multipart/form-data">
                    <div class="container">
                        <form>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="hidden" class="form-control" id="id_viatico" name="id_viatico"
                                            value="<?php echo $id_viatico ?>">
                                        <label for="quantity">Monto:</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity"
                                            placeholder="Ingrese Monto A Reembolsar..." required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="image">Agregar Evidencia:</label>
                                        <input type="file" class="form-control-file" id="file" name="file" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="concept">Concepto:</label>
                                        <select class="form-control" id="concept" name="concept" required>
                                            <option value="">Seleccione un concepto</option>
                                            <option value="Hospedaje">Hospedaje</option>
                                            <option value="Gasolina">Gasolina</option>
                                            <option value="Casetas">Casetas</option>
                                            <option value="Alimentacion">Alimentos</option>
                                            <option value="Vuelos">Vuelos</option>
                                        </select>

                                        <input hidden type="text" class="form-control" id="desc" name="desc"
                                            value="Reembolso" required>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <hr>
                            <button type="submit" class="btn btn-primary">Solicitar</button>
                        </form>
                    </div>
                </form>
            </div>
            <hr>
            <div class="row">
                <?php
                $image_Query = "SELECT * FROM `imagen` WHERE Id_Viatico = $id_viatico AND Descripcion = 'Reembolso'";
                $image_Result = $conn->query($image_Query);
                while ($image_Row = $image_Result->fetch_assoc()) {
                    $id_imagen = $image_Row['Id'];
                    $image = $image_Row['Nombre'];
                    $concepto = $image_Row['Concepto'];
                    $Cantidad = $image_Row['Monto'];
                    ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <?php
                            $Extension = pathinfo($image, PATHINFO_EXTENSION);
                            if ($Extension == 'pdf' or $Extension == 'PDF') {
                                ?>
                                <img src="../../resources/img/pdf-icon.png" class="card-img-top img-fluid" alt="..."
                                    data-toggle="modal" data-target="#imageModal" data-image="../../resources/img/pdf-icon.png">
                                <?php
                            } else {
                                ?>
                                <img src="../../uploads/<?php echo $image ?>" class="card-img-top img-fluid" alt="..."
                                    data-toggle="modal" data-target="#imageModal"
                                    data-image="../../uploads/<?php echo $image ?>">
                                <?php
                            }
                            ?>
                            <div class="card-body">
                                <p class="card-text"><strong>Concepto:</strong> <?php echo $concepto ?></p>
                                <p class="card-text"><strong>Monto:</strong> <?= number_format($Cantidad, 2, '.', "'") ?>
                                </p>
                                <a href="../../uploads/<?php echo $image ?>" download class="btn btn-primary">Descargar</a>
                                <a href="../../resources/Back/Viaticos/deleteImage.php?name=<?php echo $image ?>&id_viatico=<?php echo $id_viatico ?>&source=reembolso"
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
    <!-- Modal -->
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
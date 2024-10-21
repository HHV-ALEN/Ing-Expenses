<?php

include ("../../resources/config/db.php");
session_start(); // Inicia la sesión
if (!isset($_SESSION['ID'])) {
    // La sesión ha caducado o el usuario no ha iniciado sesión
    session_unset(); // Elimina todas las variables de sesión
    session_destroy(); // Destruye la sesión

    header('Location: ../index.php'); // Redirige al formulario de inicio de sesión
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Usuarios</title>
    <link rel="shortcut icon" href="/resources/img/logo-icon.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="../../resources/img/logo-icon.png" />
    <style>
        .card {
            margin: 0 auto;
            background-color: #f1f1f1;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #aee0ab;
            color: #fff;
            width: 100%;
        }

        .select_Style {
            -webkit-appearance: listbox !important;
            appearance: listbox !important;
            -moz-appearance: none;
            border: 1px solid #dfdfdf;
            border-radius: 5px;
            font-size: 15px;
        }

        .space {
            margin: 5px;
        }

        .card-custom {
            background-color: #ffffff;
            /* Blanco */
            border: 1px solid #dee2e6;
            /* Borde gris claro */
            border-radius: 0.25rem;
            /* Borde redondeado */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Sombra */
            margin-bottom: 1.5rem;
            /* Espacio inferior */
        }

        .card-header-custom {
            background-color: #2c3e50;
            /* Azul oscuro */
            color: #ecf0f1;
            /* Blanco humo */
        }

        .btn-custom {
            background-color: #2c3e50;
            /* Azul oscuro */
            color: #ecf0f1;
            /* Blanco humo */
        }

        .btn-custom:hover {
            background-color: #34495e;
            /* Azul oscuro un poco más claro */
            color: #bdc3c7;
            /* Gris claro */
        }
    </style>
</head>

<body>
    <!-- Inicio de la barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a href="Admin.php"><img src="../../resources/img/Alen.png" alt="ALEN Viáticos" class="img-fluid"
                    style="padding: 5px; height: 47px;"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                <ul class="navbar-nav">
                    <a class="nav-link" href="../Admin/Usuarios.php">Usuarios</a>
                    <a class="nav-link" href="../Admin/Gerentes.php">Gerentes</a>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Viáticos
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item fa fa-tags" href="../Viaticos/solicitar.php">
                                    Solicitar</a></li>
                            <li><a class="dropdown-item fa fa-tasks" href="../Viaticos/ListadoViaticos.php">
                                    Verificar</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../Perfil/perfil.php">Perfil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../index.php">Salir</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Fin de la barra de navegación -->

    <!-- Card para mostrar Gerentes -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <!-- User Information Card -->
                <div class="card">
                    <div class="card-header header bg-white">
                        <!-- Agregar Nuevo Gerente-->
                        <div class="d-flex justify-content-between bg-white">
                            <h4 style="color: Black" class="card-title"> <i class="fa fa-search">&#160;</i> Gerentes
                                Registrados</h4>
                            <button class="btn btn-success " type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                <i class="fa fa-plus"></i> Agregar Nuevo Gerente
                            </button>
                        </div>
                        <!-- Fin de Agregar Nueva Etiqueta-->
                    </div>
                    <!-- 'Collapse' para Mostrar el formulario de una nueva etiqueta -->
                    <div class="collapse" id="collapseExample">
                        <div class="card card-body">

                            <form class="form-horizontal" method="post"
                                action="../../resources/Back/Gerentes/addManager.php">
                                <h4>Registrar nuevo Gerente</h4>
                                <div class="container mt-5">
                                    <div class="row mb-3">
                                        <div class="col-sm-2">
                                            <label for="nombre1" class="col-form-label">Nombre:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                                        </div>
                                        <div class="col-sm-2">
                                            <label for="nombre2" class="col-form-label">Correo:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="correo" name="correo" required>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="modal-footer">
                                    <button class="btn btn-outline-danger space " type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseExample"
                                        aria-expanded="false" aria-controls="collapseExample">
                                        Cerrar
                                    </button>
                                    <button type="submit" class="btn btn-outline-success space"
                                        id="guardar_datos">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- End of Collapse to add new ticket -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaGerentes">
                                <thead>
                                    <tr>
                                        <th style="text-align:center" scope="col">#</th>
                                        <th style="text-align:center" scope="col">Nombre</th>
                                        <th style="text-align:center" scope="col">Correo</th>
                                        <th style="text-align:center" scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = $conn->query("SELECT * FROM gerente
                                                        WHERE Estado = 'Activo' ORDER BY Id ASC ");
                                    while ($row = $sql->fetch_object()) {
                                        ?>
                                        <tr>
                                            <th style="text-align:center" scope="row" class="folioID">
                                                <?= $row->Id ?>
                                            </th>
                                            <td style="text-align:center"><?= $row->Nombre ?></td>
                                            <td style="text-align:center"><?= $row->Correo ?></td>
                                            <td style="text-align:center">
                                                <button type="button" class="btn btn-primary me-2 space fa fa-eye view_data"
                                                    data-bs-toggle="modal" data-target="#showDetails"
                                                    value="<?= $row->Id ?>" data-id="<?= $row->Id ?>">
                                                    Detalles</button>
                                                <button type="button"
                                                    class="btn btn-warning me-2 space fa fa-edit edit_data"
                                                    data-bs-toggle="modal" data-target="#showDetails"
                                                    value="<?= $row->Id ?>" data-id="<?= $row->Id ?>">
                                                    Editar</button>
                                                <button type="button" class="btn btn-danger delete_data me-2"
                                                    data-id="<?= $row->Id ?>" data-nombre="<?= $row->Nombre ?>">
                                                    <i class="fa fa-trash">Eliminar</i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>

                                    <!-- Agregar más filas según sea necesario -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
    <!-- Fin de Card de Usuarios -->

    <!-- Modal to add a new ticket -->
    <div class="modal fade modal-md" id="viewmanagermodal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header header">
                    <h5 class="modal-title" style="color: green" id="exampleModalLongTitle">Detalles del Usuario</h5>
                </div>
                <div class="collapse" id="collapseExample">
                    <div class="card card-body">

                    </div>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="view_user_data">
                            <!-- Aqui se mostraran los detalles del usuario -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger closeusermodal" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin del modal para agregar un nuevo Usuario -->

    <!-- Modal para editar -->
    <div class="modal fade" id="editusermodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header header">
                    <h5 class="modal-title" style="color: green" id="exampleModalLongTitle">Detalles de Etiqueta</h5>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="view_edit_data">
                            <!-- Aqui se mostraran los detalles de la etiqueta -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeusermodal" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS and dependencies (Popper.js and jQuery) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>

    <script>
        // Ver detalles del Gerente
        $(document).ready(function () {
            // Adjuntar evento clic a los botones "Ver" generados dinámicamente
            $('#tablaGerentes').on('click', '.view_data', function () {
                var usuarioID = $(this).data('id');
                console.log("ID de usuario: " + usuarioID)
                $.ajax({
                    method: 'POST',
                    url: '../modals/Gerentes/verDetalles.php', // Archivo que se encargara de mostrar los detalles

                    data: {
                        'click_view_btn': true,
                        'userId': usuarioID,
                    },
                    success: function (response) {
                        console.log(response); // Muestra la respuesta en consola
                        $('.view_user_data').html(response);
                        $('#viewmanagermodal').modal('show');
                    }
                });
                //window.location.href = 'pagina_info_usuario.php?id=' + usuarioID;
            });
            $('.closeusermodal').click(function () {
                $('#viewmanagermodal').modal('hide');
            });
        });

        // Editar Gerente
        $(document).ready(function () {
            // Adjuntar evento clic a los botones "Editar" generados dinámicamente
            $('#tablaGerentes').on('click', '.edit_data', function () {
                var usuarioID = $(this).data('id');
                console.log("ID de usuario: " + usuarioID)
                $.ajax({
                    method: 'POST',
                    url: '../modals/Gerentes/editarGerente.php', // Archivo que se encargara de mostrar los detalles
                    data: {
                        'click_edit_btn': true,
                        'id_user': usuarioID,
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

        // Eliminar Usuario
        $(document).ready(function () {
            // Adjuntar evento clic a los botones "Eliminar" generados dinámicamente
            $('#tablaGerentes').on('click', '.delete_data', function () {
                var id_usuario = $(this).data('id');
                var nombre_usuario = $(this).data('nombre');
                ///console.log("Nombre de usuario: " + nombre_usuario)
                if (confirm('¿Estás seguro de que deseas eliminar a ' + nombre_usuario + '?')) {
                    $.ajax({
                        method: 'POST',
                        url: '../../resources/Back/Usuarios/deleteUser.php',
                        data: {
                            'click_delete_btn': true,
                            'id_user': id_usuario,
                        },
                        success: function (response) {
                            console.log(response); // Muestra la respuesta en consola
                            alert("Etiqueta eliminada correctamente");
                            location.reload();
                        }
                    })
                } else {
                    // El usuario canceló la eliminación
                    alert('Eliminación cancelada');
                }
            });
        });

    </script>
</body>

</html>
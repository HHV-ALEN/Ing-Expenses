<?php 

session_start();
require('../../resources/config/db.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <link rel="shortcut icon" href="/resources/img/logo-icon.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php include '../navbar.php'; ?>

<!-- Card para mostrar Usuarios -->
<div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <!-- User Information Card -->
                <div class="card">
                    <div class="card-header header bg-black">
                        <!-- Agregar Nueva Etiqueta-->
                        <div class="d-flex justify-content-between bg-black">
                            <h4 style="color: White" class="card-title"> <i class="fa fa-search">&#160;</i> Usuarios
                                Registrados</h4>
                            <button class="btn btn-success" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                <i class="fa fa-plus"></i> Agregar Nuevo Usuario
                            </button>
                        </div>
                        <!-- Fin de Agregar Nueva Etiqueta-->
                    </div>

                    <!-- 'Collapse' para Mostrar el formulario -->
                    <div class="collapse" id="collapseExample">
                        <div class="card card-body">
                            <form class="form-horizontal" method="post"

                                action="../../resources/Back/Usuarios/AddUser.php" enctype="multipart/form-data">
                                <div class="container mt-5">
                                    <div class="row mb-3">
                                        <div class="col-sm-2">
                                            <label for="nombre" class="col-form-label">Nombre Completo:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                                        </div>
                                        <div class="col-sm-2">
                                            <label for="Nickname" class="col-form-label">Nombre de usuario:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="Nickname" name="Nickname" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-2">
                                            <label for="correo" class="col-form-label">Correo:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="email" class="form-control" id="correo" name="correo" required>
                                        </div>

                                        <div class="col-sm-2">
                                            <label for="password" class="col-form-label">Contraseña:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="password" class="form-control" id="password" name="password"
                                                required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-2">
                                            <label for="puesto" class="col-form-label">Puesto:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <select class="form-select" aria-label="Default select example"
                                                name="puesto" id="puesto" required>
                                                <option selected>Seleccione una puesto</option>
                                                <option value="Admin">Admin</option>
                                                <option value="Control">Control</option>
                                                <option value="Gerente">Gerente</option>
                                                <option value="Empleado">Empleado</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <label for="estado" class="col-form-label">Núm. Seguro Social:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="nss" name="nss" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-2">
                                            <label for="Télefono" class="col-form-label">Télefono:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="telefono" name="telefono" required>
                                        </div>
                                
                                    </div>
                                        
                                </div>
                                <br>
                                <hr>
                                <div class="row">
                                        <div class="col-md-4">
                                            <button class="btn btn-outline-danger space" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseExample" aria-expanded="false"
                                            aria-controls="collapseExample">Cerrar</button>
                                            <button type="submit" class="btn btn-outline-success space"
                                            id="guardar_datos">Guardar</button>
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>
                    <!-- End of Collapse to add new user -->

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaUsuarios">
                                <thead>
                                    <tr>
                                        <th style="text-align:center" scope="col">#</th>
                                        <th style="text-align:center" scope="col">Nombre</th>
                                        <th style="text-align:center" scope="col">Nickname</th>
                                        <th style="text-align:center" scope="col">Correo</th>
                                        <th style="text-align:center" scope="col">Puesto</th>
                                        <th style="text-align:center" scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = $conn->query("SELECT * FROM usuarios WHERE Estado = 'Activo' ORDER BY Id ASC");
                                    while ($row = $sql->fetch_object()) {
                                        ?>
                                        <tr>
                                            <th style="text-align:center" scope="row" class="folioID"><?= $row->Id ?></th>
                                            <td style="text-align:center"><?= $row->Nombre ?></td>
                                            <td style="text-align:center"><?= $row->Usuario ?></td>
                                            <td style="text-align:center"><?= $row->Correo ?></td>
                                            <td style="text-align:center"><?= $row->Puesto ?></td>
                                            <td style="text-align:center">
                                                <div class="btn-group btn-group-responsive">
                                                    
                                                    <button type="button" class="btn btn-primary me-2 fa fa-eye view-data-btn"
                                                    data-bs-toggle="modal" data-bs-target="#viewusermodal"
                                                    value="<?= $row->Id ?>" data-id="<?= $row->Id ?>">Detalles</button>
                                                        
                                                    <button type="button" class="btn btn-warning me-2 fa fa-edit edit_data"
                                                        data-bs-toggle="modal" data-target="#editusermodal"
                                                        value="<?= $row->Id ?>" data-id="<?= $row->Id ?>">Editar</button>

                                                    <a href="../../resources/Back/Usuarios/DeleteUser.php?id_user=<?= $row->Id ?>"
                                                        class="btn btn-danger delete_data" data-id="<?= $row->Id ?>"
                                                        data-nombre="<?= $row->Nombre ?>">Eliminar</a>
                                                    
                                                </div>
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
    <!-- Fin de Card de Usuarios -->
    <!-- Modal para ver detalles de un usuario -->
    <div class="modal fade modal-md" id="viewusermodal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header header text-center">
                <h5 class="text-center" style="color: green" id="exampleModalLongTitle">Detalles del Usuario</h5>
                
            </div>
            <div class="modal-body">
                <div class="view-data-content">
                    <!-- Aquí se mostrarán los detalles del usuario -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger closeusermodal" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Fin del modal para ver detalles de un Usuario -->

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

        // Script para manejar el collapse para agregar nuevo usuario
        // Script para abrir el modal automáticamente si se encuentra en la página correcta
        document.addEventListener('DOMContentLoaded', function() {
            // Obtener los parámetros de la URL
            const params = new URLSearchParams(window.location.search);
            // Verificar si existe el parámetro openModal y si es true
            if (params.get('openModal') === 'true') {
                // Mostrar el modal
                const userModal = new bootstrap.Modal(document.getElementById('userModal'));
                userModal.show();
            }
        });

        $(document).ready(function () {
            $('.card-img-top').click(function () {
                var src = $(this).attr('data-image');
                $('#modalImage').attr('src', src);
            });
        });


        $(document).ready(function () {
        // Adjuntar evento clic a los botones "Ver" generados dinámicamente
        $('#tablaUsuarios').on('click', '.view-data-btn', function () {
            var usuarioID = $(this).data('id');
            console.log("ID de usuario: " + usuarioID)
            $.ajax({
                method: 'POST',
                url: '../Modal/detallesUser.php', // Archivo que se encargará de mostrar los detalles
                data: {
                    'click_view_btn': true,
                    'userId': usuarioID,
                },
                success: function (response) {
                    console.log(response); // Muestra la respuesta en consola
                    $('.view-data-content').html(response);
                    $('#viewusermodal').modal('show');
                }
            });
        });

        $('.closeusermodal').click(function () {
            $('#viewusermodal').modal('hide');
        });
    });

        // Editar Usuario
        $(document).ready(function () {
            // Adjuntar evento clic a los botones "Editar" generados dinámicamente
            $('#tablaUsuarios').on('click', '.edit_data', function () {
                var usuarioID = $(this).data('id');
                console.log("ID de usuario: " + usuarioID)
                $.ajax({
                    method: 'POST',
                    url: '../Modal/editUser.php', // Archivo que se encargara de mostrar los detalles
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
            $('#tablaUsuarios').on('click', '.delete_data', function () {
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
                            alert("Usuario eliminado correctamente");
                            //location.reload();
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
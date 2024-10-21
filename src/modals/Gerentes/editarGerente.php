<?php
require ('../../../resources/config/db.php');
$id_user = $_POST['id_user'];

session_start(); // Inicia la sesión
if (!isset($_SESSION['ID'])) {
    // La sesión ha caducado o el usuario no ha iniciado sesión
    session_unset(); // Elimina todas las variables de sesión
    session_destroy(); // Destruye la sesión

    header('Location: ../index.php'); // Redirige al formulario de inicio de sesión
    exit();
}

if (isset($_POST['save_data'])) {

}

$allSucursales = ['GDL' => 'Guadalajara', 'MTY' => 'Monterrey', 'TX' => 'Texas'];

if (isset($_POST['click_edit_btn'])) {

    $fetch_query = "SELECT * FROM gerente WHERE Id = '$id_user'";

    $resultado = mysqli_query($conn, $fetch_query);
    if (!$resultado) {
        die("Error en la consulta");
    } else {
        while ($row = mysqli_fetch_assoc($resultado)) {
            $Name = $row['Nombre'];
            $Mail = $row['Correo'];
        }
    }

    $fetch_query_run = mysqli_query($conn, $fetch_query);
    if (mysqli_num_rows($fetch_query_run) > 0) {
        while ($row = mysqli_fetch_array($fetch_query_run)) {
            echo '
                <form class="form-horizontal" method="post" action="../../../resources/Back/Gerentes/editManager.php">
                    <input type="hidden" name="id_user" id="id_user" value="' . $id_user . '">
                    <div class="container">
                        <div class="row mb-3">
                            <div class="col-sm-2">
                                <label for="nombre1" class="col-form-label">Nombre:</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nombre" name="nombre" value="' . $Name . '" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-2">
                                <label for="Correo" class="col-form-label">Correo:</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="correo" name="correo" value="' . $Mail . '" required>
                            </div>
                        </div>
                    </div>                        
                    <div class="modal-footer">
                        <button class="btn btn-outline-success space" id="guardar_datos">Guardar</button>
                    </div>
                </form>
            ';
        }
    } else {
        echo $result = '<div class="alert alert-danger" role="alert">
		No se encontraron resultados
	  </div>';
    }
}
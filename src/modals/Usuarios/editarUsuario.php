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

    $fetch_query = "SELECT * FROM usuarios WHERE Id = '$id_user'";

    $image_Query = "SELECT * FROM imagen WHERE Id_Usuario = '$id_user' AND Descripcion = 'Imagen-Perfil'";
    $image_Result = mysqli_query($conn, $image_Query);
    $Image = "No-image.png";
    if (!$image_Result) {
        $Image = "No-image.png";
        die("Error en la consulta");
    } else {
        while ($row = mysqli_fetch_assoc($image_Result)) {
            $Image = $row['Nombre'];
        }
    }

    $resultado = mysqli_query($conn, $fetch_query);
    if (!$resultado) {
        die("Error en la consulta");
    } else {
        while ($row = mysqli_fetch_assoc($resultado)) {
            $Name = $row['Nombre'];
            $Mail = $row['Correo'];
            $Manager = $row['Gerente'];
            $Position = $row['Puesto'];
            $Sucursal = $row['Sucursal'];
        }
    }
    $query_Manager = mysqli_query($conn, "select * from usuarios WHERE puesto = 'Gerente' ");

    $fetch_query_run = mysqli_query($conn, $fetch_query);
    if (mysqli_num_rows($fetch_query_run) > 0) {
        while ($row = mysqli_fetch_array($fetch_query_run)) {
            echo '
            <div class="row text-center">
            
            <form class="form-horizontal" method="post" action="../../resources/Back/Usuarios/editUser.php">
            <img src="../../uploads/' . $Image . '" alt="Imagen de perfil" width="250" height="250 " style="border-radius: 50%;" ">
            <hr>
                <div class="form-group row">
                
                    <label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nombre" name="nombre" value="' . $Name . '">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="correo" class="col-sm-2 col-form-label">Correo</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" id="correo" name="correo" value="' . $Mail . '">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="gerente" class="col-sm-2 col-form-label">Gerente</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="gerente" name="gerente">
                            <option value="' . $Manager . '">' . $Manager . '</option>';
            while ($row = mysqli_fetch_array($query_Manager)) {
                echo '<option value="' . $row['Nombre'] . '">' . $row['Nombre'] . '</option>';
            }
            echo '</select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="puesto" class="col-sm-2 col-form-label">Puesto</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="puesto" name="puesto">
                            <option value="' . $Position . '">' . $Position . '</option>
                            <option value="Gerente">Gerente</option>
                            <option value="Empleado">Empleado</option>
                            <option value="Cliente">Cliente</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="sucursal" class="col-sm-2 col-form-label">Sucursal</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="sucursal" name="sucursal">
                            <option value="' . $Sucursal . '">' . $Sucursal . '</option>
                            <option value="Guadalajara">Guadalajara</option>
                            <option value="Tijuana">Tijuana</option>
                            <option value="Veracruz">Veracruz</option>
                            <option value="CDMX">CDMX</option>
                            <option value="Texas">Texas</option>
                            <option value="Panama">Panamá</option>
                            <option value="Guatemala">Guatemala</option>
                           </select>
                    </div>
                </div>
                <input type="hidden" name="id_user" value="' . $id_user . '">
                <div class="form-group row">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary" name="save_data">Guardar</button>
                        <a href="Usuarios.php" class="btn btn-danger">Cancelar</a>
                    </div>
                </div>
            </form>
            </div>
            ';
        }
    } else {
        echo $result = '<div class="alert alert-danger" role="alert">
		No se encontraron resultados
	  </div>';
    }
}
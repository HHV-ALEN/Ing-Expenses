<?php
require_once ('../../resources/config/db.php');

$User_Id = $_POST['id_user'];

if (isset($_POST['click_edit_btn'])) {

    
    /// Obtener Información del Usuario
    $fetch_query = "SELECT * FROM usuarios WHERE Id = '$User_Id'";
    $fetch_result = mysqli_query($conn, $fetch_query);
    if (!$fetch_result) {
        die("Error en la consulta");
    } else {
        while ($row = mysqli_fetch_assoc($fetch_result)) {
            $Nombre = trim($row['Nombre']);
            $Mail = trim($row['Correo']);
            $Puesto = trim($row['Puesto']);
            $Manager = trim($row['Gerente']);
            $User = trim($row['Usuario']);
            $Nss = trim($row['NSS']);
            $Télefono = trim($row['Telefono']);
        }
    }
        /// Obtener a los gerentes para que se muestre un select con los gerentes
        $fetch_manager_query = "SELECT * FROM usuarios WHERE Puesto = 'Gerente'";
        $fetch_manager_result = mysqli_query($conn, $fetch_manager_query);
        if (!$fetch_manager_result) {
            die("Error en la consulta");
        } else {
            $Managers = "";
            while ($row = mysqli_fetch_assoc($fetch_manager_result)) {
                $Managers .= "<option value='" . $row['Id'] . "'>" . $row['Nombre'] . "</option>";
            }
        }
    

    echo "
    <form class='form-horizontal'
    action='/resources/Back/Usuarios/EditUser.php' method='POST
    '> 
        <div class='mb-3'>
        <input type='hidden' name='id_user' value='$User_Id'>
        <label for='formGroupExampleInput' class='form-label'>No. De Registro:</label>
            <input type='text' class='form-control' id='formGroupExampleInput' disabled placeholder='Id...' value='$User_Id'>
        </div>
        <div class='mb-3'>
            <label for='formGroupExampleInput2' class='form-label'>Nombre:</label>
            <input type='text' name='Nombre' class='form-control' id='formGroupExampleInput2' placeholder='Another input placeholder' value='$Nombre'>
        </div>
        <div class='mb-3'>
            <label for='formGroupExampleInput2' class='form-label
            '>Usuario:</label>
            <input type='text' name='Usuario' class='form-control' id='formGroupExampleInput2' placeholder='Another input placeholder' value='$User'>
            </div>
        <div class='mb-3'>
            <label for='formGroupExampleInput2' class='form-label'>Correo:</label>
            <input type='text' name='Correo' class='form-control' id='formGroupExampleInput2' placeholder='Another input placeholder' value='$Mail'>
        </div>
        <div class='form-group'>
                            <label for='Gerente'>Gerente</label>
                            <select class='form-control' id='Gerente' name='Gerente' required>
                                <option value='$Manager'>$Manager</option>
                                $Managers
                            </select>
                        </div>

        <div class='mb-3'>
            <label for='formGroupExampleInput2' class='form-label'>Puesto:</label>
            <select class='form-control' name='Puesto' id='Puesto'>
                <option value='Admin'> Administrador </option>
                <option value='Gerente'> Gerente </option>
                <option value='Empleado'> Empleado </option>
                <option value='Control'> Control </option>
            </select>
        </div>
        <div class='mb-3'>
            <label for='Nss' class='form-label'>NSS:</label>
            <input type='text' name='Nss' class='form-control' id='Nss' placeholder='NSS...' value='$Nss'>
        <hr>
        <div class='mb-3'>
            <label for='Télefono' class='form-label'>Télefono:</label>
            <input type='text' name='Télefono' class='form-control' id='Télefono' placeholder='Télefono...' value='$Télefono'>
        </div>
        <button type='submit' class='btn btn-outline-success'>Guardar Cambios</button>
    </form>
    ";

}

?>
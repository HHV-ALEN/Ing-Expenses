<?php

require_once('../../resources/config/db.php');

if (isset($_POST['click_view_btn'])) {

    $User_Id = $_POST['userId'];
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
        }
    }



    echo "
    <div class='mb-3'>
    <label for='formGroupExampleInput' class='form-label'>No. De Registro:</label>
        <input type='text' class='form-control' id='formGroupExampleInput' placeholder='Identificador...' value='$User_Id'>
    </div>
    <div class='mb-3'>
        <label for='formGroupExampleInput2' class='form-label'>Nombre:</label>
        <input type='text' class='form-control' id='formGroupExampleInput2' placeholder='Nombre...' value='$Nombre'>
    </div>
    <div class='mb-3'>
        <label for='formGroupExampleInput2' class='form-label'>Usuario:</label>
        <input type='text' class='form-control' id='formGroupExampleInput2' placeholder='Usuario...' value='$User'>
        </div>
    <div class='mb-3'>
        <label for='formGroupExampleInput2' class='form-label'>Correo:</label>
        <input type='text' class='form-control' id='formGroupExampleInput2' placeholder='Correo Electronico...' value='$Mail'>
    </div>
    <div class='mb-3'>
        <label for='formGroupExampleInput2' class='form-label'>Puesto:</label>
        <input type='text' class='form-control' id='formGroupExampleInput2' placeholder='Puesto...' value='$Puesto'>
    </div>
    
    
    
    
    ";


}



?>
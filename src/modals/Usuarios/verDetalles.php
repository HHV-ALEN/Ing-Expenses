<?php
require ('../../../resources/config/db.php');

session_start(); // Inicia la sesión
if (!isset($_SESSION['ID'])) {
    // La sesión ha caducado o el usuario no ha iniciado sesión
    session_unset(); // Elimina todas las variables de sesión
    session_destroy(); // Destruye la sesión

    header('Location: ../index.php'); // Redirige al formulario de inicio de sesión
    exit();
}


if (isset($_POST['click_view_btn'])) {

	$User_Id = $_POST['userId'];

    $fetch_query = "SELECT * FROM usuarios WHERE Id = '$User_Id'";

    $image_Query = "SELECT * FROM imagen WHERE Id_Usuario = '$User_Id' AND Descripcion = 'Imagen-Perfil'";
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
            $Nombre = $row['Nombre'];
            $Mail = $row['Correo'];
            $Puesto = $row['Puesto'];
            $Manager = $row['Gerente'];
            $Sucursal = $row['Sucursal'];
        }
    }
    $fetch_query_run = mysqli_query($conn, $fetch_query);
    if (mysqli_num_rows($fetch_query_run) > 0) {
        while ($row = mysqli_fetch_array($fetch_query_run)) {
            echo '
            <div class="card bg-white">
                <div class="card-header">
                    <img src="../../uploads/' . $Image . '" class="card-img-top"
                        alt="..." data-toggle="modal" data-target="#imageModal"
                        data-image="../../uploads/' . $Image . '">
                    <hr>
                    <p class="card-title"> <strong>Número ID:</strong> ' . $User_Id . '</p>
                    <p class="card-text"> <strong>Nombre:</strong> ' . $Nombre . '</p>
                </div>
                <div class="card-body">
                    <p class="card-text"><strong>Correo:</strong> ' . $Mail . '</p>
                    <p class="card-text"><strong>Puesto:</strong> ' . $Puesto . '</p>
                    <p class="card-text"><strong>Gerente:</strong> ' . $Manager . '</p>
                    <p class="card-text"><strong>Sucursal:</strong> ' . $Sucursal . '</p>
                </div>
            </div>
            ';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">
                No se encontraron resultados
              </div>';
    }
}
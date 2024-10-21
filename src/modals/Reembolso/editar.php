<?php
require ('../../../resources/config/db.php');
$ImageName = $_POST['ImageName'];

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

if (isset($_POST['click_edit_btn'])) {
    $Query = "
(
    SELECT 
        Id,
        Imagen,
        Descripcion,
        Monto,
        Concepto,
        Destino,
        'reembolso' AS Tabla
    FROM 
        reembolso
    WHERE 
        Imagen = '$ImageName'
)
UNION
(
    SELECT 
        Id,
        Imagen,
        Descripcion,
        Monto,
        Concepto,
        Destino,
        'reembolsos_anidados' AS Tabla
    FROM 
        reembolsos_anidados
    WHERE 
        Imagen = '$ImageName'
)";
    $fetch_query_run = mysqli_query($conn, $Query);

    if (!$fetch_query_run) {
        die("Error en la consulta");
    } else {
        while ($row = mysqli_fetch_assoc($fetch_query_run)) {
            $id_reembolso = $row['Id'];
            $Monto = $row['Monto'];
            $Concepto = $row['Concepto'];
            $Descr = $row['Descripcion'];
            $Destino = $row['Destino'];
        }
    }

    $result = mysqli_query($conn, $Query);
    if (mysqli_num_rows($result) > 0) {
        $Extension = pathinfo($ImageName, PATHINFO_EXTENSION);
        if ($Extension == 'pdf' or $Extension == 'PDF') {
            ?>
            <img src="../../uploads/pdf-icon.png" class="card-img-top" alt="..." data-toggle="modal"
                 data-target="#imageModal" data-image="../../uploads/pdf-icon.png">
            <?php
        } else {
            ?>
            <img src="../../uploads/<?php echo $ImageName ?>" class="card-img-top" alt="..." data-toggle="modal"
            data-target="#imageModal" data-image="../../uploads/<?php echo $ImageName ?>">
            <?php
        }
        echo '
        <div class="row text-center">
        <label for="Monto" class=" col-form-label">FOLIO (ID):</label>
        <input type="number" class="form-control" name="id_reembolso" value="' . $id_reembolso . '" readonly>
        
        <form class="form-horizontal" method="post" action="../../resources/Back/Viaticos/editarReembolso.php" enctype="multipart/form-data">

        <input type="hidden" name="id_reembolso" value="' . $id_reembolso . '">
        
        <input type="hidden" id="NombreImagen" name="NombreImagen" value="' . $ImageName . '">

        <br>
        <div class="form-group row">
                <label for="Monto" class="col-sm-2 col-form-label">Monto</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="Monto" name="Monto" value="' . $Monto . '">
            </div>
        </div>
        <br>



        <div class="form-group row">
            <label for="Concepto" class="col-sm-2 col-form-label">Concepto</label>
            <div class="col-sm-10">
                <select class="form-control" id="Concepto" name="Concepto">
                    <option value="' . $Concepto . '">' . $Concepto . '</option>
                    <option value="Hospedaje">Hospedaje</option>
                    <option value="Gasolina">Gasolina</option>
                    <option value="Casetas">Casetas</option>
                    <option value="Alimentos">Alimentos</option>
                    <option value="Vuelos">Vuelos</option>
                    <option value="Transporte">Transporte</option>
                    <option value="Estacionamiento">Estacionamiento</option>
                </select>
            </div>
        </div>
        
        <br>
        <div class="form-group row">
            <label for="Descripcion" class="col-sm-2 col-form-label">Descr.</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="Descripcion" name="Descripcion" value="' . $Descr . '">
            </div>
        </div><br>
        <div class="form-group row">
            <label for="Destino" class="col-sm-2 col-form-label">Destino</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="Destino" name="Destino" value="' . $Destino . '">
            </div>
        </div><br>
        <div class="form-group row">
            <label for="Imagen" class="col-sm-2 col-form-label">Imagen</label>
            <div class="col-sm-10">
                <input type="file" class="form-control-file" id="file" name="file">
            </div>
        </div><br>
        <div class="form-group row">
            <div class="col-sm-10">
                <button type="submit" class="btn btn-primary" name="save_data">Guardar</button>
            </div>
        </div>
    ';} else {
        echo $result = '<div class="alert alert-danger" role="alert">
		No se encontraron resultados
	  </div>';
    }
}
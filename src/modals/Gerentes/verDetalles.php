<?php
require('../../../resources/config/db.php');

if (isset($_POST['click_view_btn'])) {

	$User_Id = $_POST['userId'];

	$fetch_query = "SELECT * FROM gerente WHERE Id = '$User_Id'";

	$resultado = mysqli_query($conn, $fetch_query);
	if (!$resultado) {
		die("Error en la consulta");
	}
	else {
		while ($row = mysqli_fetch_assoc($resultado)) {
			$Nombre = $row['Nombre'];
			$Mail = $row['Correo'];
            $Estado = $row['Estado'];
		}
	}

	$ManagerOfUsers_query = "SELECT * FROM usuarios WHERE Gerente = '$Nombre'";
	$ManagerOfUsers_query_run = mysqli_query($conn, $ManagerOfUsers_query);

	$fetch_query_run = mysqli_query($conn, $fetch_query);

	if (mysqli_num_rows($fetch_query_run) > 0) {
		while ($row = mysqli_fetch_array($fetch_query_run)) {
			echo '
            <div class="card bg-white">
                <div class="card-header">
				<p class="card-title"> <strong>Número ID:</strong> ' . $User_Id . '</p>
                    <p class="card-text"> <strong>Nombre:</strong> ' . $Nombre . '</p>
                    
                </div>
                <div class="card-body">
                    <p class="card-text"><strong>Correo:</strong> ' . $Mail . '</p>
                    <p class="card-text"><strong>Estado:</strong> ' . $Estado . '</p>
                 </div>
            </div>
			<br>
			<div class="card bg-white">
				<div class="card-header">
					<strong><p class="card-title">Usuarios asignados a este gerente</p></strong>
				</div>
				<table class="table table-striped">
					<thead>
						<tr>
							<th scope="col">ID</th>
							<th scope="col">Nombre</th>

						</tr>
					</thead>
					<tbody>';
					if (mysqli_num_rows($ManagerOfUsers_query_run) > 0) {
					while ($row = mysqli_fetch_array($ManagerOfUsers_query_run)) {
						echo '
						<tr>
							<td>' . $row['Id'] . '</td>
							<td>' . $row['Nombre'] . '</td>
						</tr>
						';
					} } else {
						echo '
						<tr>
							<td colspan="2">No hay usuarios asignados a este gerente</td>
						</tr>
						';
					}

					echo '
					</tbody>
				</table>

            </div>
            ';
		}
	} else {
		echo $result = '<div class="alert alert-danger" role="alert">
		No se encontraron resultados
	  </div>';
	}

}
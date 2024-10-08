<?php
include('../../resources/config/db.php');

session_start();
$Nombre_Usuario = $_SESSION['Name'];
$Tipo_Usuario = $_SESSION['Position'];

// Configuración de la paginación
$records_per_page = 10; // Número de registros por página
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Página actual
$offset = ($page - 1) * $records_per_page; // Cálculo del offset

// Consulta SQL con LIMIT y OFFSET para paginación
$sql_query = "SELECT * FROM viaticos WHERE Solicitante = '$Nombre_Usuario' ORDER BY Id DESC LIMIT $offset, $records_per_page";
$result = $conn->query($sql_query);

// Obtener el valor del folio de venta desde el formulario (GET request)
$Orden_Venta = isset($_GET['Orden_Venta']) ? $_GET['Orden_Venta'] : '';

// Construir la consulta SQL para obtener los registros
$sql_query = "SELECT * FROM viaticos WHERE Solicitante = ?";

// Si se ha introducido un folio de venta, añadir el filtro al query
if (!empty($Orden_Venta)) {
    $sql_query .= " AND Orden_Venta LIKE ?";
}

// Ordenar y agregar paginación
$sql_query .= " ORDER BY Id DESC LIMIT ?, ?";

// Preparar la consulta
$stmt = $conn->prepare($sql_query);

// Manejar el valor para el LIKE del folio de venta
$like_folio = "%$Orden_Venta%";

// Asignar parámetros a la consulta preparada
if (!empty($Orden_Venta)) {
    $stmt->bind_param("ssii", $Nombre_Usuario, $like_folio, $offset, $records_per_page);
} else {
    $stmt->bind_param("sii", $Nombre_Usuario, $offset, $records_per_page);
}

// Ejecutar la consulta
$stmt->execute();
$result = $stmt->get_result();

// ------------------------
// Contar el total de registros (ajustando la consulta)
// ------------------------

$total_records_query = "SELECT COUNT(*) AS total_registros FROM viaticos WHERE Solicitante = ?";

// Si se ha introducido un folio de venta, añadir el filtro al query
if (!empty($Orden_Venta)) {
    $total_records_query .= " AND Orden_Venta LIKE ?";
}

// Preparar la consulta para contar los registros
$stmt_count = $conn->prepare($total_records_query);

// Asignar parámetros a la consulta de conteo
if (!empty($Orden_Venta)) {
    $stmt_count->bind_param("ss", $Nombre_Usuario, $like_folio);
} else {
    $stmt_count->bind_param("s", $Nombre_Usuario);
}

// Ejecutar la consulta de conteo
$stmt_count->execute();
$total_records_result = $stmt_count->get_result();
$total_records_row = $total_records_result->fetch_assoc();
$total_records = $total_records_row['total_registros'];

// Calcular el número total de páginas
$total_pages = ceil($total_records / $records_per_page);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Viaticos</title>
    <link rel="shortcut icon" href="/resources/img/logo-icon.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>
    <?php
    include 'Actualizadores.php';
    include '../../src/navbar.php';

    ?>
    <br>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header card-header-custom">
                        <h5 class="card-title text-center"><i class="fas fa-user"></i> Mis Viáticos</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">ID</th>
                                        <th class="text-center" scope="col">Fecha de Registro</th>
                                        <th class="text-center" scope="col">Fecha de Salida</th>
                                        <th class="text-center" scope="col">Fecha de Regreso</th>
                                        <th class="text-center" scope="col">Proyecto</th>
                                        <th class="text-center" scope="col">Estado</th>
                                        <th class="text-center" colspan="3">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <div class="container">
                                        <form method="GET" action="">
                                            <input type="text" name="Orden_Venta"
                                                placeholder="Buscar por Folio de Venta"
                                                value="<?php echo isset($_GET['Orden_Venta']) ? $_GET['Orden_Venta'] : ''; ?>">
                                            <button type="submit">Buscar</button>
                                        </form>

                                    </div>

                                    <?php
                                    $Color_Row = "";
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            switch ($row['Estado']) {
                                                case 'Abierto':
                                                    $Color_Row = "table-secondary";
                                                    break;
                                                case 'Aceptado':
                                                    $Color_Row = "table-primary";
                                                    break;
                                                case 'Rechazado':
                                                    $Color_Row = "table-danger";
                                                    break;
                                                case 'Verificación':
                                                    $Color_Row = "table-info";
                                                    break;
                                                case 'Revisión':
                                                    $Color_Row = "table-warning";
                                                    break;
                                                case 'Prórroga':
                                                    $Color_Row = "table-warning";
                                                    break;
                                                case 'Completado':
                                                    $Color_Row = "table-success";
                                                    break;
                                                case 'En Curso':
                                                    $Color_Row = "table-light";
                                                    break;
                                                case 'Cerrado':
                                                    $Color_Row = "table-danger";
                                                    break;
                                                case 'Segunda Revisión':
                                                    $Color_Row = "table-warning";
                                                    break;
                                            }
                                            echo '';
                                            echo "<tr  class='" . $Color_Row . "'>";
                                            echo "<td class='text-center'>" . $row['Id'] . "</td>";
                                            echo "<td class='text-center'>" . $row['Fecha_Registro'] . "</td>";
                                            echo "<td class='text-center'>" . $row['Fecha_Salida'] . "</td>";
                                            echo "<td class='text-center'>" . $row['Fecha_Regreso'] . "</td>";
                                            echo "<td class='text-center'>" . $row['Orden_Venta'] . " " . $row['Codigo'] . " " . $row['Nombre_Proyecto'] . "</td>";
                                            echo "<td class='text-center'>" . $row['Estado'] . "</td>";

                                            if ($row['Estado'] == 'Abierto' && $Tipo_Usuario == 'Empleado') {
                                                echo "<td class='text-center'><a href='detalles.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>";
                                                echo "<td class='text-center'><a href='../../resources/Back/Viaticos/DeleteViatico.php?id=" . $row['Id'] . "' class='btn btn-danger'>Eliminar</a></td>";
                                                echo "<td class='text-center'><a href='editar.php?id=" . $row['Id'] . "' class='btn btn-warning'>Editar</a></td>";
                                                echo "<td></td>";
                                            }
                                            if (($row['Estado'] == 'Revisión' || $row['Estado'] == 'En Curso' || $row['Estado']=='Verificación' ||  $row['Estado']=='Prórroga') && $Tipo_Usuario == 'Empleado') {
                                                echo "<td class='text-center'><a href='detalles.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>";
                                                echo "<td class='text-center'><a href='SubirEvidencias.php?id=" . $row['Id'] . "' class='btn btn-success'>Evidenciar</a></td>";
                                                echo "<td></td>";
                                                echo "<td></td>";
                                            }
                                            if ($row['Estado'] == 'Completado' || $row['Estado'] == 'Segunda Revisión' ){
                                                echo "<td class='text-center'><a href='detalles.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>";
                                                echo "<td class='text-center'><a href='SubirEvidencias.php?id=" . $row['Id'] . "' class='btn btn-success'>Ver Evidencias</a></td>";
                                                echo "<td></td>";
                                                echo "<td></td>";
                                            }
                                            elseif ($row['Estado'] == 'Rechazado'){
                                                echo "<td class='text-center'><a href='detalles.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>";
                                                echo "<td></td>";
                                                echo "<td></td>";
                                                echo "<td></td>";
                                            }
                                            elseif ($row['Estado'] == 'Aceptado'){
                                                echo "<td class='text-center'><a href='detalles.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>";
                                                echo "<td></td>";
                                                echo "<td></td>";
                                                echo "<td></td>";
                                            }
                                            elseif ($row['Estado'] == 'Fuera de Rango'){
                                                echo "<td class='text-center'><a href='detalles.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>";
                                                echo "<td></td>";
                                                echo "<td></td>";
                                                echo "<td></td>";
                                            }
                                            


                                        }
                                    } else {
                                        echo "<tr><td colspan='7'>No hay viáticos registrados</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- Paginación -->
                        <nav>
                            <ul class="pagination justify-content-center">
                                <?php if ($page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($page < $total_pages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>


                    </div>
                </div>
            </div>

            <!-- Bootstrap JS and dependencies (Popper.js and jQuery) -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>
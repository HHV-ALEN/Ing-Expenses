<?php
include('../../../resources/config/db.php');
include '../Actualizadores.php';

session_start();
$Nombre_Usuario = $_SESSION['Name'];
$Tipo_Usuario = $_SESSION['Position'];

// Configuración de la paginación
$records_per_page = 10; // Número de registros por página
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Página actual
$offset = ($page - 1) * $records_per_page; // Cálculo del offset

$Nombre_Gerente = trim($_SESSION['Name']); // Nombre del gerente

// Obtener el valor del filtro de Orden de Venta desde el formulario
$Orden_Venta = isset($_GET['Orden_Venta']) ? $_GET['Orden_Venta'] : '';

// Construir la consulta SQL base para la tabla de reembolsos
$sql_query = "SELECT * FROM viaticos WHERE 1"; // "1" para asegurarse de que siempre tenga una condición

// Si se ha introducido un folio de venta, agregar el filtro
if (!empty($Orden_Venta)) {
    $sql_query .= " AND Orden_Venta LIKE ?"; // Agregar filtro de búsqueda con LIKE
}

// Agregar la paginación y el orden
$sql_query .= " ORDER BY Id DESC LIMIT ?, ?";

// Preparar la consulta
$stmt = $conn->prepare($sql_query);

// Manejar el valor para el LIKE del folio de venta
$like_folio = "%$Orden_Venta%";

// Asignar los parámetros según si hay un filtro o no
if (!empty($Orden_Venta)) {
    $stmt->bind_param("sii", $like_folio, $offset, $records_per_page);
} else {
    $stmt->bind_param("ii", $offset, $records_per_page);
}

// Ejecutar la consulta y obtener los resultados
$stmt->execute();
$result = $stmt->get_result();

// ------------------------
// Contar el total de registros (con filtro si aplica)
// ------------------------

$total_records_query = "SELECT COUNT(*) AS total_registros FROM viaticos WHERE 1";

// Si se ha introducido un folio de venta, agregar el filtro
if (!empty($Orden_Venta)) {
    $total_records_query .= " AND Orden_Venta LIKE ?";
}

// Preparar la consulta para contar los registros
$stmt_count = $conn->prepare($total_records_query);

// Asignar los parámetros a la consulta de conteo
if (!empty($Orden_Venta)) {
    $stmt_count->bind_param("s", $like_folio);
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
    <title>Listado Viaticos</title>
    <link rel="shortcut icon" href="/resources/img/logo-icon.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


</head>

<body>
    <?php include '../../../src/navbar.php';
    
    ?>
    <br>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header card-header-custom">
                        <h5 class="card-title text-center"><i class="fas fa-user"></i> Viáticos Registrados -
                            (<?php echo $total_records; ?>) </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">ID</th>
                                        <th class="text-center" scope="col">Solicitante</th>
                                        <th class="text-center" scope="col">Fecha de Solicitud</th>
                                        <th class="text-center" scope="col">Fecha de Salida</th>
                                        <th class="text-center" scope="col">Fecha de Regreso</th>
                                        <th class="text-center" scope="col">Proyecto</th>
                                        <th class="text-center" scope="col">Estado</th>
                                        <th class="text-center" colspan="3">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <form method="GET" action="">
                                            <input type="text" name="Orden_Venta"
                                                placeholder="Buscar por Folio de Venta"
                                                value="<?php echo isset($_GET['Orden_Venta']) ? $_GET['Orden_Venta'] : ''; ?>">
                                            <button type="submit">Buscar</button>
                                        </form>
                                    <?php
                                    $Color_Row = "";
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            include 'actionsButtons.php';
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
        </div>
    </div>
    <script src="https://kit.fontawesome.com/7b8b8c1c1d.js" crossorigin="anonymous"></script>
    <!-- Bootstrap JS and dependencies (Popper.js and jQuery) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>



</body>

</html>
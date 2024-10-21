<?php
session_start();
ob_start(); // Inicia el buffer de salida
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include ('../../config/db.php');
require '../../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('Plantilla.xlsx');



/// Definir variables
$Nombre = $_SESSION['Name'];
$Id_User = $_SESSION['ID'];
$Gerente = $_SESSION['Manager'];
$Fecha_Salida = $_POST['FechaSalida'];
$Hora_Salida = $_POST['HoraSalida'];
$Fecha_Regreso = $_POST['FechaRegreso'];
$Hora_Regreso = $_POST['HoraRegreso'];
$Estado = $_POST['Estado'];
$ciudades = $_POST['Ciudad'];
$Acompanantes = $_POST['Acompanantes'];
$Hospedaje = $_POST['Hospedaje'];
$Gasolina = $_POST['Gasolina'];
$Casetas = $_POST['Casetas'];
$Vuelos = $_POST['Vuelos'];
$Alimentacion = $_POST['Alimentacion'];
$Transporte = $_POST['Transporte'];
$Estacionamiento = $_POST['Estacionamiento'];
$Transporte = $_POST['Transporte'];
$totalDelConteo = $_POST['TotalDeViaticos'];
$FechaDeHoy = date("Y-m-d");

// Hacer variables de texto a mayúsculas
$Estado = strtoupper($Estado);


//// Recibimiento de datos: 

echo "<br>-----------------------Datos recibidos:-----------------------------------------<br>";
echo "Nombre: " . $Nombre . "<br>";
echo "ID Usuario: " . $Id_User . "<br>";
echo "Gerente: " . $Gerente . "<br>";
echo "Fecha de Salida: " . $Fecha_Salida . "<br>";
echo "Hora de Salida: " . $Hora_Salida . "<br>";
echo "Fecha de Regreso: " . $Fecha_Regreso . "<br>";
echo "Hora de Regreso: " . $Hora_Regreso . "<br>";
echo "Estado: " . $Estado . "<br>";
echo "Hospedaje: " . $Hospedaje . "<br>";
echo "Gasolina: " . $Gasolina . "<br>";
echo "Casetas: " . $Casetas . "<br>";
echo "Vuelos: " . $Vuelos . "<br>";
echo "Alimentación: " . $Alimentacion . "<br>";
echo "Transporte: " . $Transporte . "<br>";
echo "Estacionamiento: " . $Estacionamiento . "<br>";
echo "Total de viáticos: " . $totalDelConteo . "<br>";
echo "Fecha de hoy: " . $FechaDeHoy . "<br>";


/// Obtener el folio de la solicitud
$folioQuery = "SELECT MAX(Id) FROM viaticos";
$folioResult = $conn->query($folioQuery);
if ($folioResult->num_rows > 0) {
    $row = $folioResult->fetch_assoc();
    $folio = $row['MAX(Id)'] + 1;
} else {
    echo "Error Al consultar el folio de la ultima solicitud: " . $folioQuery . "<br>" . $conn->error;
}


// ------------ Días entre fecha de salida y fecha de regreso------------------------------
$datetime1 = new DateTime($Fecha_Salida);
$datetime2 = new DateTime($Fecha_Regreso);
$interval = $datetime1->diff($datetime2);
$Dias = $interval->format('%a');
$Dias = $Dias + 1;
echo "<br>-----------------------Días entre fecha de salida y fecha de regreso:-----------------------------------------<br>";
echo "Días entre fecha de salida y fecha de regreso: " . $Dias . "<br>";

// Testeo - Taer información de los clientes a visitar
// Query de la tabla clientes

// Inicializar un array para los clientes
$clientes = [];

// Recorrer los datos enviados del formulario
foreach ($_POST as $key => $value) {
    if (strpos($key, 'Cliente') === 0 && !empty($value)) {
        // Obtener el índice del cliente
        $index = str_replace('Cliente', '', $key);

        // Recopilar los datos del cliente
        $cliente = [
            'nombre' => $value,
            'motivo' => isset($_POST['Motivo' . $index]) ? $_POST['Motivo' . $index] : '',
            'fecha_visita' => isset($_POST['FechaDeVisita' . $index]) ? $_POST['FechaDeVisita' . $index] : ''
        ];

        // Añadir el cliente al array
        $clientes[] = $cliente;
    }
}

echo "<br>-----------------------Clientes:-----------------------------------------<br>";

// Mostrar los datos recibidos (esto es solo para pruebas, puedes procesar los datos según tus necesidades)
$clientes[0]['nombre'] = strtoupper($clientes[0]['nombre']);
echo "Nombre Cliente 1: " . htmlspecialchars($clientes[0]['nombre']) . "<br>";
echo "Motivo Cliente 1: " . htmlspecialchars($clientes[0]['motivo']) . "<br>";
echo "Fecha de Visita Cliente 1: " . htmlspecialchars($clientes[0]['fecha_visita']) . "<br>";

$clientes[1]['nombre'] = strtoupper($clientes[1]['nombre']);
echo "Nombre Cliente 2: " . htmlspecialchars($clientes[1]['nombre']) . "<br>";
echo "Motivo Cliente 2: " . htmlspecialchars($clientes[1]['motivo']) . "<br>";
echo "Fecha de Visita Cliente 2: " . htmlspecialchars($clientes[1]['fecha_visita']) . "<br>";

$clientes[2]['nombre'] = strtoupper($clientes[2]['nombre']);
echo "Nombre Cliente 3: " . htmlspecialchars($clientes[2]['nombre']) . "<br>";
echo "Motivo Cliente 3: " . htmlspecialchars($clientes[2]['motivo']) . "<br>";
echo "Fecha de Visita Cliente 3: " . htmlspecialchars($clientes[2]['fecha_visita']) . "<br>";



// Redireccionar o realizar alguna acción adicional
// Cargar la plantilla de Excel
$sheet = $spreadsheet->getActiveSheet();

// Llenar las celdas con los datos del formulario
$sheet->setCellValue('D8', $Nombre);
$sheet->setCellValue('J6', $FechaDeHoy);
$sheet->setCellValue('H5', $folio);
$sheet->setCellValue('D12', $Fecha_Salida);
$sheet->setCellValue('H12', $Fecha_Regreso);
$sheet->setCellValue('D14', $Hora_Salida);
$sheet->setCellValue('J14', $Hora_Regreso);
$sheet->setCellValue('L12', $Dias);
$sheet->setCellValue('L38', $Dias);
$sheet->setCellValue('D18', $Estado);


$row = 16;
foreach ($ciudades as $ciudad) {
    $sheet->setCellValue('H' . $row, $ciudad);
    $row += 2;
}

$sheet->setCellValue('B22', $clientes[0]['nombre']); 
$sheet->setCellValue('J22', $clientes[0]['motivo']);
$sheet->setCellValue('E22', $clientes[0]['fecha_visita']);

$sheet->setCellValue('B24', $clientes[1]['nombre']);
$sheet->setCellValue('J24', $clientes[1]['motivo']);
$sheet->setCellValue('E24', $clientes[1]['fecha_visita']);

$sheet->setCellValue('B26', $clientes[2]['nombre']);
$sheet->setCellValue('J26', $clientes[2]['motivo']);
$sheet->setCellValue('E26', $clientes[2]['fecha_visita']);

$sheet->setCellValue('D30', $Hospedaje);
$sheet->setCellValue('D31', $Gasolina);
$sheet->setCellValue('D32', $Casetas);
$sheet->setCellValue('D33', $Alimentacion);

$sheet->setCellValue('J30', $Vuelos);
$sheet->setCellValue('J31', $Transporte);
$sheet->setCellValue('J32', $Estacionamiento);
$sheet->setCellValue('J34', $totalDelConteo);


$row = 38;
$contador = 1;
foreach ($Acompanantes as $acompanante) {
    $sheet->setCellValue('F' . $row, $acompanante);
    $row++;
    $contador++;
}

function getRandomString($length = 3)
{
    // Genera bytes aleatorios
    $bytes = random_bytes($length);
    // Convierte los bytes a una cadena hexadecimal y toma los primeros 3 caracteres
    $randomString = substr(bin2hex($bytes), 0, $length);
    return $randomString;
}

$randomString = getRandomString(3);
echo $randomString;

$writer = new Xlsx($spreadsheet);
$FileName = 'Solicitud-' . $Nombre . '-' . $Fecha_Salida . '-' . $randomString . '.xlsx';
$writer->save('../../../uploads/Files/' . $FileName);



// Obtener Información del gerente 
$IdManagerQuery = "SELECT * FROM usuarios WHERE Nombre = '$Gerente'";
$IdManager = $conn->query($IdManagerQuery);

if ($IdManager->num_rows > 0) {
    $row = $IdManager->fetch_assoc();
    $GerenteId = $row['Id'];
    $CorreoGerente = $row['Correo'];
} else {
    echo "Error: " . $IdManagerQuery . "<br>" . $conn->error;
}

if (empty($Nombre) || empty($Id_User) || empty($Gerente) || empty($GerenteId)){
    die("Error: Faltan datos necesarios para la inserción.");
    header('Location: ../../../../../src/Viaticos/solicitar.php');
}

        /// Realizar query para insertar solicitud en la BD, Tabla viaticos
        $sql_query_Insert_Viaticos = "INSERT INTO viaticos
        (Fecha_Solicitud, Fecha_Salida, Fecha_Regreso, Hora_Salida, Hora_Regreso, Destino, Id_Usuario, Id_Gerente, Estado, Hospedaje, Gasolina, Casetas, Alimentacion, Vuelos, Transporte, Estacionamiento,Total)
        VALUES
        ('$FechaDeHoy','$Fecha_Salida', '$Fecha_Regreso', '$Hora_Salida', '$Hora_Regreso', '$Estado', '$Id_User', '$GerenteId', 'Abierto', '$Hospedaje', '$Gasolina', '$Casetas', '$Alimentacion', '$Vuelos', '$Transporte', '$Estacionamiento','$totalDelConteo')";

        if ($conn->query($sql_query_Insert_Viaticos) === TRUE) {

            // -------- Insertar en la BD --------
            $Id_Viatico = "SELECT MAX(Id) FROM viaticos";
            $Id_Viatico = $conn->query($Id_Viatico);
            if ($Id_Viatico->num_rows > 0) {
                $row = $Id_Viatico->fetch_assoc();
                $Id_Viatico = $row['MAX(Id)'];
            } else {
                echo "Error: " . $Id_Viatico . "<br>" . $conn->error;
            }

            $Solicitu_Query = "INSERT INTO solicitudes (Id_Viatico, Id_Usuario, Nombre) VALUES ('$Id_Viatico', '$Id_User', '$FileName')";
            if ($conn->query($Solicitu_Query) === TRUE) {
                echo "Solicitud creada correctamente";
            } else {
                echo "Error: " . $Solicitu_Query . "<br>" . $conn->error;
            }


            $CreateVericacion = "INSERT INTO verificacion (Id_Viatico, Solicitante, Aceptado_Control, Aceptado_Gerente) VALUES ('$folio', '$Id_User', 'Pendiente', 'Pendiente')";
            if ($conn->query($CreateVericacion) === TRUE) {
                echo "Verificación creada correctamente";

                // Inicializar un array para almacenar los datos de los clientes
                $clientes = [];

                // Contar el número de clientes enviados
                $numClientes = 0;
                while (isset($_POST['Cliente' . ($numClientes + 1)])) {
                    $numClientes++;
                }

                echo "-------------- Registro de clientes --------------<br>";

                // Obtener los datos de cada cliente
                for ($i = 1; $i <= $numClientes; $i++) {
                    $cliente = [
                        'nombre' => $_POST['Cliente' . $i],
                        'motivo' => $_POST['Motivo' . $i],
                        'fechaVisita' => $_POST['FechaDeVisita' . $i],
                    ];


                    $clientes[] = $cliente;
                }

                foreach ($clientes as $index => $cliente) {
                    /// Insertar en tabla clientes
                    $Cliente_Query = "INSERT INTO clientes (Id_Usuario, Id_Viatico, Nombre, Motivo, Fecha) VALUES 
                    ('$Id_User' , '$Id_Viatico', '" . $cliente['nombre'] . "', '" . $cliente['motivo'] . "', '" . $cliente['fechaVisita'] . "')";
                    if ($conn->query($Cliente_Query) === TRUE) {
                        echo "-------------- Registro de clientes --------------<br>";
                        echo "Cliente agregado correctamente";
                    } else {
                        echo "Error: " . $Cliente_Query . "<br>" . $conn->error;
                    }
                }
                header('Location: ../Mail/solicitud.php?id_usuario=' . $Id_User . '&id_gerente=' . $GerenteId . '&Fecha_Salida=' . $Fecha_Salida . '');

            } else {
                echo "Error: " . $CreateVericacion . "<br>" . $conn->error;
            }
            echo "Solicitud enviada correctamente";

            foreach ($ciudades as $ciudad) {
                $sql_query_Insert_Destino = "INSERT INTO destino (Id_Viatico, Id_Usuario, Estado, Ciudad)
                VALUES ($Id_Viatico, '$Id_User', '$Estado', '$ciudad')";
                if ($conn->query($sql_query_Insert_Destino) === TRUE) {
                    echo "Destino agregado correctamente";
                } else {
                    echo "Error: " . $sql_query_Insert_Destino . "<br>" . $conn->error;
                }
            }

            foreach ($Acompanantes as $acompanante) {
                $sql_query_Insert_Acompanantes = "INSERT INTO acompanantes (Id_Viatico, Nombre, Id_Usuario)
                VALUES ($Id_Viatico, '$acompanante', '$Id_User' )";
                if ($conn->query($sql_query_Insert_Acompanantes) === TRUE) {
                    echo "Acompañante agregado correctamente";
                    
                } else {
                    echo "Error: " . $sql_query_Insert_Acompanantes . "<br>" . $conn->error;
                }
            }
        } else {
            echo "Error: " . $sql_query_Insert_Viaticos . "<br>" . $conn->error;
        }
ob_end_flush(); // Envía el contenido del buffer de salida y lo apaga
exit;

?>
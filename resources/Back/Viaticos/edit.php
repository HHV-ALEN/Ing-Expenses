<?php
ob_start(); // Inicia el buffer de salida
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/// Mostrar los datos de envio
//echo "<pre>";
//print_r($_POST);
//echo "</pre>";

include ('../../config/db.php');
require '../../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('Plantilla.xlsx');
session_start();

$id_viatico = $_POST['id'];
$id_usuario = $_POST['id_usuario'];
$Fecha_Salida = $_POST['FechaSalida'];
$Fecha_Regreso = $_POST['FechaRegreso'];
$Hora_Salida = $_POST['HoraSalida'];
$Hora_Regreso = $_POST['HoraRegreso'];
$Hospedaje = $_POST['Hospedaje'];
$Gasolina = $_POST['Gasolina'];
$Casetas = $_POST['Casetas'];
$Alimentacion = $_POST['Alimentacion'];
$Estado = $_POST['Estado'];
$Ciudades = $_POST['Ciudad'];
$Vuelos = $_POST['Vuelos'];
$Transporte = $_POST['Transporte'];
$Estacionamiento = $_POST['Estacionamiento'];
$totalDelConteo = $_POST['TotalDeViaticos'];
$FechaDeHoy = date("Y-m-d");
$Acompanantes = $_POST['Acompanantes'];
/// Definir variables
$Nombre = $_SESSION['Name'];
$Id_User = $_SESSION['ID'];
$Gerente = $_SESSION['Manager'];
//// ----------------- Datos del Formulario ----------------------

/*
echo "-------------- Información del Formulario --------------<br>";
echo "<br>";
echo "ID Viatico: " . $id_viatico . "<br>";
echo "ID Usuario: " . $id_usuario . "<br>";
echo "Fecha Salida: " . $Fecha_Salida . "<br>";
echo "Fecha Regreso: " . $Fecha_Regreso . "<br>";
echo "Hora Salida: " . $Hora_Salida . "<br>";
echo "Hora Regreso: " . $Hora_Regreso . "<br>";
echo "<br>";
echo "Estado: " . $Estado . "<br>";
echo "Ciudades: ";
print_r($Ciudades);
echo "<br>";
echo "<br>";
echo "----------- Conceptos de Viaticos -----------<br>";
echo "Hospedaje: " . $Hospedaje . "<br>";
echo "Gasolina: " . $Gasolina . "<br>";
echo "Casetas: " . $Casetas . "<br>";
echo "Alimentacion: " . $Alimentacion . "<br>";
echo "Vuelos: " . $Vuelos . "<br>";
echo "Transporte: " . $Transporte . "<br>";
echo "Estacionamiento: " . $Estacionamiento . "<br>";
echo "Total de Viaticos: " . $totalDelConteo . "<br>";
*/
$datetime1 = new DateTime($Fecha_Salida);
$datetime2 = new DateTime($Fecha_Regreso);
$interval = $datetime1->diff($datetime2);
$Dias = $interval->format('%a');
$Dias = $Dias + 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    
    /// Mostrar Registros de clientes a eliminar
    $MostrarCliente = "SELECT * FROM clientes WHERE Id_Viatico = $id_viatico";
    $Result_MostrarCliente = $conn->query($MostrarCliente);
    if ($Result_MostrarCliente->num_rows > 0) {
        while ($Row_MostrarCliente = $Result_MostrarCliente->fetch_assoc()) {
            echo "<br>-------------- Clientes que seran eliminados --------------<br>";
            echo "Nombre Cliente: " . $Row_MostrarCliente['Nombre'] . "<br>";
            echo "Motivo Cliente: " . $Row_MostrarCliente['Motivo'] . "<br>";
            echo "Fecha de Visita Cliente: " . $Row_MostrarCliente['Fecha'] . "<br>";
        }
    } else {
        echo "No hay registros de clientes<br>";
    }

    /// Eliminar los registros de la tabla de clientes
    $Delete_Clientes = "DELETE FROM clientes WHERE Id_Viatico = $id_viatico";
    if ($conn->query($Delete_Clientes) === TRUE) {
        echo "<br>Clientes del Viatico . '$id_viatico ' . han sido eliminados correctamente<br><br>";
    } else {
        echo "Error: " . $Delete_Clientes . "<br>" . $conn->error;
        echo "Error en el query de Delete Clientes  " . $Delete_Clientes . "<br>" . $conn->error;
    }


    $clientes = [];

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
}

// Mostrar los datos recibidos (esto es solo para pruebas, puedes procesar los datos según tus necesidades)
/// Impresión de información del cliente con un foreach
foreach ($clientes as $cliente) {
    echo "<br>-------------- Información del Cliente --------------<br>";
    echo "Nombre Cliente: " . htmlspecialchars($cliente['nombre']) . "<br>";
    echo "Motivo Cliente: " . htmlspecialchars($cliente['motivo']) . "<br>";
    echo "Fecha de Visita Cliente: " . htmlspecialchars($cliente['fecha_visita']) . "<br>";

    $Cliente_Query = "INSERT INTO clientes (Id_Viatico, Nombre, Motivo, Fecha)
     VALUES ('$id_viatico', '" . htmlspecialchars($cliente['nombre']) . "', '" . htmlspecialchars($cliente['motivo']) . "', '" . htmlspecialchars($cliente['fecha_visita']) . "')";
    if ($conn->query($Cliente_Query) === TRUE) {
        echo "Cliente registrado correctamente<br>";
    } else {
        echo "Error: " . $Cliente_Query . "<br>" . $conn->error;
    }
}

/// ----------------- Archivo de Solicitud de Excel ----------------------

/// 1.- Eliminar Archivo Anterior
$Query_Get_Name = "SELECT Nombre FROM solicitudes WHERE Id_Viatico = $id_viatico";
$Result_Get_Name = mysqli_query($conn, $Query_Get_Name);
$Row_Get_Name = mysqli_fetch_assoc($Result_Get_Name);
$Nombre_Archivo = $Row_Get_Name['Nombre'];

echo "<br>Nombre del Archivo: " . $Nombre_Archivo . "<br>";
unlink("../../../uploads/" . $Nombre_Archivo);


/// 2.- Crear Archivo Nuevo
// Cargar la plantilla de Excel
$sheet = $spreadsheet->getActiveSheet();
// Llenar las celdas con los datos del formulario
$sheet->setCellValue('D8', $Nombre);
$sheet->setCellValue('J6', $FechaDeHoy);
$sheet->setCellValue('H5', $id_viatico);
$sheet->setCellValue('D12', $Fecha_Salida);
$sheet->setCellValue('H12', $Fecha_Regreso);
$sheet->setCellValue('D14', $Hora_Salida);
$sheet->setCellValue('J14', $Hora_Regreso);
$sheet->setCellValue('L12', $Dias);
$sheet->setCellValue('L38', $Dias);
$sheet->setCellValue('D18', $Estado);

$row = 16;
foreach ($Ciudades as $ciudad) {
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
$sheet->setCellValue('J33', $totalDelConteo);


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

$writer = new Xlsx($spreadsheet);
$FileName = 'Solicitud-' . $Nombre . '-' . $Fecha_Salida . '-' . $randomString . '.xlsx';
$writer->save('../../../uploads/Files/' . $FileName);

/// Renombrar archivo dentro de la DB - Antiguo = $Nombre_Archivo | Nuevo = $FileName
$SQL_UPDATE = "UPDATE solicitudes SET Nombre = '$FileName' WHERE Id_Viatico = $id_viatico";
if ($conn->query($SQL_UPDATE) === TRUE) {
    echo "Archivo renombrado correctamente<br>";
} else {
    echo "Error: " . $SQL_UPDATE . "<br>" . $conn->error;
}

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

/// Realizar query para Actualizar solicitud en la BD, Tabla viaticos
$SQL_UPDATE = "UPDATE viaticos SET
Fecha_Salida = '$Fecha_Salida',
Fecha_Regreso = '$Fecha_Regreso',
Hora_Salida = '$Hora_Salida',
Hora_Regreso = '$Hora_Regreso',
Destino = '$Estado',
Estado = 'Abierto',
Hospedaje = '$Hospedaje',
Gasolina = '$Gasolina',
Casetas = '$Casetas',
Alimentacion = '$Alimentacion',
Vuelos = '$Vuelos',
Transporte = '$Transporte',
Estacionamiento = '$Estacionamiento',
Total = '$totalDelConteo'
WHERE Id = $id_viatico";

if ($conn->query($SQL_UPDATE) === TRUE) {

    $Solicitud_Query = "INSERT INTO solicitudes (Id_Viatico, Id_Usuario, Nombre) VALUES ('$id_viatico', '$Id_User', '$FileName')";
    if ($conn->query($Solicitud_Query) === TRUE) {
        echo "Actualización de los datos del Viatico actualizados correctamente<br>";
    } else {
        echo "Error en el query de Solicitudes " . $Solicitud_Query . "<br>" . $conn->error;
    }

    
    /// Actualizar tabla de destino
    $Delete_Destinos = "DELETE FROM destino WHERE Id_Viatico = $id_viatico";
    if ($conn->query($Delete_Destinos) === TRUE) {
        echo "Destinos de destinos eliminados correctamente<br>";
    } else {
        echo "Error: " . $Delete_Destinos . "<br>" . $conn->error;
        echo "Error en el query de Delete  " . $Delete_Destinos . "<br>" . $conn->error;
    }
    
    foreach ($Ciudades as $ciudad) {
        $Destino_Query = "INSERT INTO destino (Id_Viatico, Ciudad) VALUES ('$id_viatico', '$ciudad')";
        if ($conn->query($Destino_Query) === TRUE) {
            echo "Destinos registrados correctamente<br>";
        } else {
            echo "Error: " . $Destino_Query . "<br>" . $conn->error;
            echo "Error en el query de Destinos " . $Destino_Query . "<br>" . $conn->error;
        }
    }

    /// Actualizar tabla Acompañantes
    $Delete_Acompanantes = "DELETE FROM acompanantes WHERE Id_Viatico = $id_viatico";
    if ($conn->query($Delete_Acompanantes) === TRUE) {
        echo "Acompañantes eliminados correctamente<br>";
    } else {
        echo "Error: " . $Delete_Acompanantes . "<br>" . $conn->error;
        echo "Error en el query de Delete Acompañantes  " . $Delete_Acompanantes . "<br>" . $conn->error;
    }

    foreach ($Acompanantes as $acompanante) {
        $Acompanante_Query = "INSERT INTO acompanantes (Id_Viatico, Nombre) VALUES ('$id_viatico', '$acompanante')";
        if ($conn->query($Acompanante_Query) === TRUE) {
            echo "Acompañantes Registrados correctamente<br>";
        } else {
            echo "Error: " . $Acompanante_Query . "<br>" . $conn->error;
            echo "Error en el query de Acompañantes " . $Acompanante_Query . "<br>" . $conn->error;
        }
    }

    header("Location: ../../../src/Viaticos/editar.php?id_viatico=$id_viatico");


} else {
    echo "Error: " . $SQL_UPDATE . "<br>" . $conn->error;
}


?>*/
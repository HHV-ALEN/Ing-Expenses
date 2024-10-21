<?php

session_start();
include ('../../resources/config/db.php');

$Comprobacion = $_SESSION['Comprobacion']; /// Suma de los montos evidenciados
$Id_Usuario = $_GET['id_usuario'];
$MontosPedidos = $_SESSION['MontosPedidos'];



/// Sumar los montos del arreglo Montos
$TotalGastado = 0;
foreach ($Montos as $key => $value) {
    $TotalGastado += $value;
}

echo "<br> ******** Montos del Arreglo Comprobación ********<br>";
print_r($Comprobacion);

/// Poner los datos del arreglo en variables
$Id_Viatico = $Comprobacion['Id_Viatico'];
$Fecha_Salida = $Comprobacion['Fecha_Salida'];
$Fecha_Regreso = $Comprobacion['Fecha_Regreso'];
$Cliente = $Comprobacion['Cliente'];
$Hospedaje = $Comprobacion['Hospedaje'];
$Gasolina = $Comprobacion['Gasolina'];
$Alimentacion = $Comprobacion['Alimentos'];
$Casetas = $Comprobacion['Casetas'];
$Vuelos = $Comprobacion['Vuelos'];
$Transporte = $Comprobacion['Transporte'];
$Estacionamiento = $Comprobacion['Estacionamiento'];

echo "<br><br><br>-------------------- Información de la Comprobación -------------------<br>";
/// Mostrar los datos en la vista
echo "<br>Id_Viatico: ".$Id_Viatico;
echo "<br>Fecha_Salida: ".$Fecha_Salida;
echo "<br>Fecha_Regreso: ".$Fecha_Regreso;
echo "<br>Cliente: ".$Cliente;
echo "<br>----------Conceptos----------<br>";
echo "<br>Gasolina: ".$Gasolina;
echo "<br>Hospedaje: ".$Hospedaje;
echo "<br>Alimentacion: ".$Alimentacion;
echo "<br>Casetas: ".$Casetas;
echo "<br>Vuelos: ".$Vuelos;
echo "<br>Transporte: ".$Transporte;
echo "<br>Estacionamiento: ".$Estacionamiento;
$MontoTotalDeComprobacion = $Hospedaje + $Gasolina + $Alimentacion + $Casetas + $Vuelos + $Transporte + $Estacionamiento;
echo "<br>----------...----------<br>";
echo "<br>Total Montos Gastados: ".$MontoTotalDeComprobacion . "<br>";
echo "<br>----------...----------<br>";

//////////// OBtener total de la tabla viaticos
$GetTotalViaticos_Query = "SELECT * FROM viaticos WHERE Id = '$Id_Viatico'";
$GetTotalViaticos_Result = $conn->query($GetTotalViaticos_Query);
$GetTotalViaticos_Row = $GetTotalViaticos_Result->fetch_assoc();
$TotalViaticos = $GetTotalViaticos_Row['Total'];


/// ------------------- Monto Establecido -------------------
echo "<br>------------------- Montos Establecidos -------------------<br>";
$TotalMontoPedidos = 0;
foreach ($MontosPedidos as $key => $value) {
    echo "<br> Concepto: ".$key." Monto: ".$value;
    $TotalMontoPedidos += $value;
}
echo "<br>Total Montos Solicitados: ".$TotalMontoPedidos . "<br>";

echo "<br>----------------------------------------------------------<br>";
/// Faltan hacer las operaciones de los montos y mostrarlos en la vista
echo "<br>------------------- Calculos -------------------<br>";
echo "<br>Monto Total de evidencias Subidas: ".$MontoTotalDeComprobacion;
echo "<br>Monto Total solicitado: ".$TotalMontoPedidos;
echo "<br>Restante: ".($TotalMontoPedidos - $MontoTotalDeComprobacion);

echo "<br>Id_Usuario: ".$Id_Usuario;
//////Obtener Nombre del solicitante
$GetName_Query = "SELECT Nombre FROM usuarios WHERE Id = '$Id_Usuario'";
$GetName_Result = $conn->query($GetName_Query);
$GetName_Row = $GetName_Result->fetch_assoc();
$Nombre = $GetName_Row['Nombre'];

echo "<br>Nombre: ".$Nombre;

require '../../vendor/autoload.php'; // Asegúrate de que el autoload de Composer esté disponible
//////echo "Comienza a crear el archivo";
// Habilitar el reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('Plantilla_ComprobanteDeViaticos.xlsx');
if ($spreadsheet) {
    ////echo "Plantilla cargada correctamente.<br>";
} else {
    ////echo "Error al cargar la plantilla.<br>";
    exit;
}

$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('C5', $Fecha_Salida);
$sheet->setCellValue('I5', $Fecha_Regreso);
$sheet->setCellValue('C6', $Nombre);
$sheet->setCellValue('C8', $Cliente);
$sheet->setCellValue('C11', $Hospedaje);
$sheet->setCellValue('C13', $Gasolina);
$sheet->setCellValue('C17', $Alimentacion);
$sheet->setCellValue('C15', $Casetas);
$sheet->setCellValue('I11', $Vuelos);
$sheet->setCellValue('I13', $Transporte);
$sheet->setCellValue('I15', $Estacionamiento);

$sheet->setCellValue('D20', $MontoTotalDeComprobacion);
$sheet->setCellValue('D21', $TotalViaticos);
$sheet->setCellValue('D22', ($TotalViaticos - $MontoTotalDeComprobacion));

$FileName = 'ComprobaciónDeEvidencias-' . $Nombre . '-' . date('Y-m-d-H-i-s');

// Preparar para la descarga del archivo
ob_end_clean(); // Evitar caracteres no deseados
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'. $FileName .'.xlsx"');
header('Cache-Control: max-age=0');

// Enviar el archivo Excel al navegador
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit; // Asegúrate de salir después de enviar el archivo

?>
<?php
include('../resources/config/db.php');
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('Plantilla.xlsx');

$id = $_GET['id'];
$NAME = $_GET['Nombre'];
$Fecha_Salida = $_GET['Fecha_Salida'];
$Fecha_Regreso = $_GET['Fecha_Regreso'];
$Hora_Salida = $_GET['Hora_Salida'];
$Hora_Regreso = $_GET['Hora_Regreso'];
$Cliente = $_GET['Cliente'];
$Motivo = $_GET['Motivo'];
$Destino = $_GET['Destino'];
$Total = $_GET['Total'];

echo "Id: " . $id . "<br>";
echo "Nombre: " . $NAME . "<br>";
echo "Fecha de salida: " . $Fecha_Salida . "<br>";
echo "Fecha de regreso: " . $Fecha_Regreso . "<br>";
echo "Hora de salida: " . $Hora_Salida . "<br>";
echo "Hora de regreso: " . $Hora_Regreso . "<br>";
echo "Cliente: " . $Cliente . "<br>";
echo "Motivo: " . $Motivo . "<br>";
echo "Destino: " . $Destino . "<br>";
echo "Total: " . $Total . "<br>";
echo "---------------------------------------------<br>";


// Crear un nuevo objeto de la clase PhpSpreadsheet



// Cargar la plantilla de Excel
$sheet = $spreadsheet->getActiveSheet();

// Llenar las celdas con los datos del formulario
$sheet->setCellValue('D8', $NAME);
$sheet->setCellValue('J6', $FechaDeHoy);
$sheet->setCellValue('H5', $folio);
$sheet->setCellValue('D12', $Fecha_Salida);
$sheet->setCellValue('H12', $Fecha_Regreso);
$sheet->setCellValue('D14', $Hora_Salida);
$sheet->setCellValue('J14', $Hora_Regreso);
$sheet->setCellValue('L12', $Dias);
$sheet->setCellValue('D18', $Estado);
$sheet->setCellValue('D22', $Cliente);
$sheet->setCellValue('J22', $Motivo);

$row = 16;
foreach ($ciudades as $ciudad) {
    $sheet->setCellValue('H' . $row, $ciudad);
    $row+=2;
}

$sheet->setCellValue('D25', $Hospedaje);
$sheet->setCellValue('D26', $Gasolina);
$sheet->setCellValue('D27', $Casetas);
$sheet->setCellValue('D28', $Alimentacion);

$row = 32;
$contador = 0;
foreach ($Acompanantes as $acompanante) {
    $sheet->setCellValue('F' . $row, $acompanante);
    $row++;
    $contador++;
}

$HospedajeXDia = $Hospedaje * $Dias;
$GasolinaXDia = $Gasolina * $Dias;
$CasetasXDia = $Casetas * $Dias;
$AlimentacionXDia = $Alimentacion * $Dias;

$sheet->setCellValue('G25', $HospedajeXDia);
$sheet->setCellValue('G26', $GasolinaXDia);
$sheet->setCellValue('G27', $CasetasXDia);
$sheet->setCellValue('G28', $AlimentacionXDia);

$HospedajeXDiaXacompanante = $HospedajeXDia * $contador;
$GasolinaXDiaXacompanante = $GasolinaXDia * $contador;
$CasetasXDiaXacompanante = $CasetasXDia * $contador;
$AlimentacionXDiaXacompanante = $AlimentacionXDia * $contador;

$sheet->setCellValue('J25', $HospedajeXDiaXacompanante);
$sheet->setCellValue('J26', $GasolinaXDiaXacompanante);
$sheet->setCellValue('J27', $CasetasXDiaXacompanante);
$sheet->setCellValue('J28', $AlimentacionXDiaXacompanante);

$sheet->setCellValue('D30', $totalDelConteo);


// Evitar caracteres no deseados
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Solicitud-'.$NAME.'.xlsx"');
header('Cache-Control: max-age=0');

// Enviar el archivo Excel al navegador
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

// Limpiar el buffer de salida
ob_clean();

header('Location: misViaticos.php');




?>
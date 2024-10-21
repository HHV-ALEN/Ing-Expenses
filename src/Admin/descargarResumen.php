<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Style;

require '../../vendor/autoload.php'; // Asegúrate de incluir el autoload de Composer
require_once '../../resources/config/db.php';

// Función para agregar bordes a una celda
session_start();
ob_start();
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('PlantillaResumen.xlsx');
$sheet = $spreadsheet->getActiveSheet();

$Usuario = $_POST['Usuario'];


// Obtener datos del usuario
$usuario_sql = "SELECT * FROM usuarios WHERE id = $Usuario";
$usuario_result = $conn->query($usuario_sql);
$usuario = $usuario_result->fetch_assoc();
$nombre_usuario = $usuario['Nombre'];
$Correo = $usuario['Correo'];
$Gerente = $usuario['Gerente'];
$Puesto = $usuario['Puesto'];
$Sucursal = $usuario['Sucursal'];


// Obtener solicitudes del usuario
$solicitudes_sql = "SELECT * FROM viaticos WHERE Id_Usuario = $Usuario";
$solicitudes_result = $conn->query($solicitudes_sql);

// Obtener reembolsos del usuario
$reembolsos_sql = "SELECT * FROM reembolso WHERE Id_Usuario = $Usuario";
$reembolsos_result = $conn->query($reembolsos_sql);


$sheet->setCellValue('G2', $nombre_usuario);
$sheet->setCellValue('G4', $Correo);
$sheet->setCellValue('G6', $Gerente);
$sheet->setCellValue('L4', $Puesto);
$sheet->setCellValue('L2', $Sucursal);


// Agregar solicitudes a partir de la fila 4
$row = 13;
if ($solicitudes_result->num_rows > 0) {
    while ($solicitud = $solicitudes_result->fetch_assoc()) {
        echo "Solicitud: " . $solicitud['Id'] . "<br>";
        $sheet->setCellValue('B' . $row, $solicitud['Id']); // Suponiendo que tienes una columna 'descripcion'
        $sheet->setCellValue('C' . $row, $solicitud['Fecha_Solicitud']);
        $sheet->setCellValue('D' . $row, $solicitud['Destino']);
        $sheet->setCellValue('E' . $row, $solicitud['Cliente']);
        $sheet->setCellValue('F' . $row, $solicitud['Motivo']);
        $sheet->setCellValue('G' . $row, $solicitud['Estado']);
        $sheet->setCellValue('H' . $row, $solicitud['Total']);
        $row += 2; // Dejar una fila en blanco
    }
} else {
    $sheet->setCellValue('B28', 'No hay solicitudes registradas');
    $row++;
}

// Agregar solicitudes a partir de la fila 4
$row = 13;
if ($reembolsos_result->num_rows > 0) {
    while ($reembolso = $reembolsos_result->fetch_assoc()) {
        $sheet->setCellValue('K' . $row, $reembolso['Id']); // Suponiendo que tienes una columna 'descripcion'
        $sheet->setCellValue('L' . $row, $reembolso['Monto']);
        $sheet->setCellValue('M' . $row, $reembolso['Id_Viatico']);
        $sheet->setCellValue('N' . $row, $reembolso['Estado']);
        $sheet->setCellValue('O' . $row, $reembolso['Concepto']);
        $row++; // Dejar una fila en blanco
    }
} else {
    $sheet->setCellValue('K28', 'No hay Reembolosos registradas');
    $row++;
}

$fecha = date('Y-m-d');

// Guardar el archivo Excel en el servidor
$writer = new Xlsx($spreadsheet);

$FileName = "Resumen_" . $nombre_usuario . "_" . $fecha . ".xlsx";
$FilePath = '../../uploads/Files/' . $FileName;
$writer->save($FilePath);

ob_end_clean();

// Enviar el archivo directamente al navegador
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Resumen_' . $nombre_usuario . '_' . $fecha . '.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');

?>
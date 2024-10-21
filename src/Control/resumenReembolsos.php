<?php
// Iniciar la sesión al principio del script
session_start();
ob_start();
require '../../resources/config/db.php';
require '../../vendor/autoload.php'; // Asegúrate de que el autoload de Composer esté disponible
// Recuperar el array de la sesión
if (isset($_SESSION['Reembolso'])) {
    $Reembolso = $_SESSION['Reembolso'];
    // Procesar el array
    //print_r($Reembolso);
} else {
    //echo "No hay datos disponibles para exportar.";
    exit; // Salir si no hay datos para evitar errores posteriores
}
//echo "<br>------------------------------------ <br>";

// Recuperar el array de la sesión
if (isset($_SESSION['Datos'])) {
    $Datos = $_SESSION['Datos'];
    // Procesar el array
    //print_r($Datos);
} else {
    //echo "No hay datos disponibles para exportar.";
    exit; // Salir si no hay datos para evitar errores posteriores
}
//echo "<br>------------------------------------ <br>";

// Para asignar los valores del array a variables individuales
foreach ($_SESSION['Datos'] as $dato) {
    $nombre = $dato['Nombre'];
    $idReembolso = $dato['Id_Reembolso'];
    $idViatico = $dato['Id_Viatico'];
    $monto = $dato['Monto'];

    // Aquí puedes usar las variables como necesites
    //echo "Nombre: $nombre\n";
    //echo "Id_Reembolso: $idReembolso\n";
    //echo "Id_Viatico: $idViatico\n";
    //echo "Monto: $monto\n";
}

// Obtener El nombre del gerente a partir de la información del usuario
echo "-------    Este es el usuario que solicita: " . $nombre . "<br>";
echo "-------    Este es el id del reembolso: " . $idReembolso . "<br>";

/// Obtener el gerente a partir del nombre del usuario
$sql_gerente = "SELECT Gerente FROM usuarios WHERE Nombre = '$nombre'";
$resultado = mysqli_query($conn, $sql_gerente);
$gerente = mysqli_fetch_assoc($resultado);
$Gerente = $gerente['Gerente'];
echo "-------    Este es el gerente: " . $Gerente . "<br>";

/// 


// Inicializar arrays para almacenar los valores desglosados
$Conceptos = array();
$Descripciones = array();
$Cantidades = array();
$Destinos = array();
$Estados = array();
$Imagenes = array();
$Id_Usuarios = array();
$Id_Gerentes = array();
$Id_UsuariosCreador = array();
$Fechas = array();

// Recorrer el arreglo y desglosar los datos
foreach ($Reembolso as $id_imagen => $datos) {
    $Conceptos[] = $datos['Concepto'];
    $Descripciones[] = $datos['Descripcion'];
    $Cantidades[] = $datos['Cantidad'];
    $Destinos[] = $datos['Destino'];
    $Estados[] = $datos['Estado'];
    $Imagenes[] = $datos['Imagen'];
    $Id_UsuariosCreador[] = $datos['Id_UsuarioCreador'];
    $Fechas[] = $datos['FechaRegistro'];
}

echo "Conceptos: <br>";
print_r($Conceptos);
echo "<br>------------------------------------ <br>";
echo "Descripciones: <br>";
print_r($Descripciones);
echo "<br>------------------------------------ <br>";
echo "Cantidades: <br>";
print_r($Cantidades);
echo "<br>------------------------------------ <br>";
echo "Destinos: <br>";
print_r($Destinos);
echo "<br>------------------------------------ <br>";
echo "Estados: <br>";
print_r($Estados);
echo "<br>------------------------------------ <br>";
echo "Imagenes: <br>";
print_r($Imagenes);
echo "<br>------------------------------------ <br>";
echo "Fechas: <br>";
print_r($Fechas);
echo "<br>------------------------------------ <br>";
//-------------
echo $datos['FechaRegistro'];
echo "Comienza a crear el archivo";

// Imprimir usuario y gerente
echo "Usuario: " . $nombre . "<br>";
echo "Gerente: " . $Gerente . "<br>";

// Habilitar el reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('Plantilla_Resumen_Reembolsos.xlsx');
if ($spreadsheet) {
    //echo "Plantilla cargada correctamente.<br>";
} else {
    //echo "Error al cargar la plantilla.<br>";
    exit;
}

$sheet = $spreadsheet->getActiveSheet();

// Agregar título
$sheet->setCellValue('E2', $nombre);
$sheet->setCellValue('H2', $idReembolso);
$sheet->setCellValue('H3', $idViatico);
$sheet->setCellValue('E3', $Gerente);

// Agregar encabezados
$sheet->setCellValue('B6', 'Id');
$sheet->setCellValue('C6', 'Concepto');
$sheet->setCellValue('D6', 'Descripcion');
$sheet->setCellValue('E6', 'Cantidad');
$sheet->setCellValue('F6', 'Destino');
$sheet->setCellValue('G6', 'Estado');
$sheet->setCellValue('H6', 'Fecha');

// Agregar datos y cargar imágenes
$row = 7; // Comienza en la fila 7 para dejar la fila 6 para los encabezados
$imageRow = 7; // Inicializa la fila de la imagen en la misma fila que los datos
foreach ($Reembolso as $id_imagen => $datos) {
    $sheet->setCellValue('B' . $row, $id_imagen);
    $sheet->setCellValue('C' . $row, $datos['Concepto']);
    $sheet->setCellValue('D' . $row, $datos['Descripcion']);
    $sheet->setCellValue('E' . $row, $datos['Cantidad']);
    $sheet->setCellValue('F' . $row, $datos['Destino']);
    $sheet->setCellValue('G' . $row, $datos['Estado']);
    $sheet->setCellValue('H' . $row, $datos['FechaRegistro']);
    $row++;
}
$FileName = 'Resumen-' . $nombre . '-' . date('Y-m-d-H-i-s');

// Preparar para la descarga del archivo
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'. $FileName .'.xlsx"');
header('Cache-Control: max-age=0');

// Limpiar el búfer de salida y enviar contenido
ob_end_clean();

// Crear el archivo Excel
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>

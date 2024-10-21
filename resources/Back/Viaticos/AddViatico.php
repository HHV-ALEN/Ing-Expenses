<?php 
echo "------------- AddViatico.php -------------<br>";
require('../../config/db.php');
session_start();
// Información de a sesion:
echo "Usuario: ".$_SESSION['Name']."<br>";
echo "Puesto: ".$_SESSION['Position']."<br>";
$Nombre_Solicitante = $_SESSION['Name'];
$TipoUsuario = $_SESSION['Position'];

echo "<br>------------- Solicitud de Viático -------------<br>";

/// FECHAS Y HORARIOS:
$Fecha = date('Y-m-d');
$Fecha_Salida = $_POST['Fecha_Salida'];
$Fecha_Regreso = $_POST['Fecha_Regreso'];
$Hora_Salida = $_POST['horaSalida'];
$Hora_Regreso = $_POST['horaRegreso'];
$Orden_Venta = $_POST['ordenVenta'];
$Codigo_Prefix = $_POST['codigoPrefix'];
$Codigo = $_POST['codigo'];
$Nombre_Proyecto = strtoupper($_POST['nombreProyecto']);
$clientes = $_POST['clientes'];
$ciudades = $_POST['ciudades'];
$destino = strtoupper($_POST['destino']);
$Materiales = $_POST['materiales'];
$GastosMedicos = $_POST['gastosMedicos'];
$Equipos = $_POST['equipos'];
$Concepto = strtoupper($_POST['Concepto']);
$Monto_Concepto = $_POST['Monto'];
$Hospedaje = $_POST['hospedaje'];
$Vuelos = $_POST['vuelos'];
$Alimentacion = $_POST['alimentacion'];
$Transporte = $_POST['transporte'];
$Estacionamiento = $_POST['estacionamiento'];
$Gasolina = $_POST['gasolina'];
$Casetas = $_POST['casetas'];

/// Guardar los conceptos en un array
$conceptos = [
    'MATERIALES', 'GASTOS MÉDICOS', 'EQUIPOS', $Concepto, 'HOSPEDAJE', 'VUELOS', 'ALIMENTACION', 'TRANSPORTE', 'ESTACIONAMIENTO', 'GASOLINA', 'CASETAS'
];

$Montos = [
    $Materiales, $GastosMedicos, $Equipos, $Monto_Concepto, $Hospedaje, $Vuelos, $Alimentacion, $Transporte, $Estacionamiento, $Gasolina, $Casetas
];

// Suma de conceptos:
$Addition = $Materiales + $GastosMedicos + $Equipos + $Monto_Concepto + $Hospedaje + $Vuelos + $Alimentacion + $Transporte + $Estacionamiento + $Gasolina + $Casetas;

 // Inicializa un array vacío para almacenar los acompañantes
 $acompanantes = [];




/// Imprimir los clientes 

 // Captura los valores de los acompañantes dinámicamente
 for ($i = 1; $i <= 6; $i++) {
     if (!empty($_POST["acomp_$i"])) {
         $acompanantes[] = strtoupper($_POST["acomp_$i"]);
     }
 }

$Codigo_Completo = $Codigo_Prefix."-".$Codigo;


/*
echo "<br>-------- Datos recibidos --------<br>";
echo "Fecha de salida: $Fecha_Salida<br>";
echo "Fecha de regreso: $Fecha_Regreso<br>";
echo "Hora de salida: $Hora_Salida<br>";
echo "Hora de regreso: $Hora_Regreso<br>";
echo "Orden de venta: $Orden_Venta<br>";
echo "Código: $Codigo_Completo<br>";
echo "Nombre del proyecto: $Nombre_Proyecto<br>";
echo "<br><br><br>--------------- Clientes -------------------<br>";*/

// Insertar datos en la base de datos

    echo "<br>*** Insertando datos en la base de datos -- Tabla: Viaticos  ***** ,<br>";
    echo "Solicitante: ".$_SESSION['Name']."<br>";
    echo "Fecha de salida: $Fecha_Salida<br>";
    echo "Hora de salida: $Hora_Salida<br>";
    echo "Fecha de regreso: $Fecha_Regreso<br>";
    echo "Hora de regreso: $Hora_Regreso<br>";
    echo "Orden de venta: $Orden_Venta<br>";
    echo "Código: $Codigo_Completo<br>";
    echo "Nombre del proyecto: $Nombre_Proyecto<br>";
    echo "Destino : $destino<br>";
    echo "Total: $Addition<br>";
    echo "<br>-------------------------------------------------------------------------<br>";
    echo "<br>************CONCEPTOS************<br>";
    echo "<br> Materiales: $Materiales<br>";
    echo "<br> Gastos Médicos: $GastosMedicos<br>";
    echo "<br> Equipos: $Equipos<br>";
    echo "<br> Concepto: $Concepto<br>";
    echo "<br> Monto Concepto: $Monto_Concepto<br>";
    echo "<br> Hospedaje: $Hospedaje<br>";
    echo "<br> Vuelos: $Vuelos<br>";
    echo "<br> Alimentación: $Alimentacion<br>";
    echo "<br> Transporte: $Transporte<br>";
    echo "<br> Estacionamiento: $Estacionamiento<br>";
    echo "<br> Gasolina: $Gasolina<br>";
    echo "<br> Casetas: $Casetas<br>";
    echo "<br> Total: $Addition<br>";
    echo "<br>-------------------------------------------------------------------------<br>";



    
    //////// ------------------ Primera Tabla .- Viático --------------------
    $Insert_Viatico = "INSERT INTO viaticos (Solicitante, Fecha_Salida, Hora_Salida, Fecha_Regreso, Hora_Regreso, Orden_Venta, Codigo, Nombre_Proyecto, Destino, Total, Fecha_Registro, Estado)
    VALUES ('".$_SESSION['Name']."', '$Fecha_Salida', '$Hora_Salida', '$Fecha_Regreso', '$Hora_Regreso', '$Orden_Venta', '$Codigo_Completo', '$Nombre_Proyecto', '$destino', '$Addition', '$Fecha', 'Abierto')";
    $Result_Viatico = mysqli_query($conn, $Insert_Viatico);
    if($Result_Viatico){
        echo "<br>Viático registrado correctamente";
    }else{
        echo "<br>Error al registrar el viático";
    } 

    // Obtener el ID DEL VIATICO registrado
    $sql = "SELECT MAX(Id) AS Id FROM viaticos";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $Id_Viatico = $row['Id'];
    echo "<br>Id del viático: $Id_Viatico<br>";
    echo "<br>-------------------------------------------------------------------------<br>";

//////// ------------------ Segunda Tabla .- Clientes --------------------
echo "<br>******************* Insertando datos en la base de datos -- Tabla: Clientes  ***** ,<br>";
foreach ($_POST['clientes'] as $cliente) {
    echo "<br>Destino: $destino<br>";
    $nombre = strtoupper(trim($cliente['nombre']));
    $motivo = strtoupper(trim($cliente['motivo']));
    $fecha = trim($cliente['fecha']);

    // Si el motivo es "Otro", usamos el valor del campo "otro_motivo"
    if ($motivo === 'OTRO' && !empty($cliente['otro_motivo'])) {
        $motivo = strtoupper(trim($cliente['otro_motivo']));
    }

    echo "<br>Cliente: $nombre<br>";
    echo "<br>Motivo: $motivo<br>";
    echo "<br>Fecha: $fecha<br>";

    $Insert_Clientes = "INSERT INTO clientes (Solicitante , Id_Viatico, Nombre, Motivo, Fecha, Destino)
    VALUES ('".$_SESSION['Name']."', $Id_Viatico , '$nombre', '$motivo', '$fecha', '$destino')";
    $Result_Clientes = mysqli_query($conn, $Insert_Clientes);
    if($Result_Clientes){
        echo "<br>Cliente registrado correctamente";
    }else{
        echo "<br>Error al registrar el cliente";
    }
}

echo "<br>-------------------------------------------------------------------------<br>";

//////// ------------------ Tercera Tabla .- Acompañantes --------------------
    echo "<br>************** Insertando datos en la base de datos -- Tabla: Acompañantes  ***** ,<br>";
    if (!empty($acompanantes)) {
        foreach ($acompanantes as $index => $acomp) {
            echo "<br>Acompañante " . ($index + 1) . ": " . htmlspecialchars($acomp) . "<br>";
            $Insert_Acomp = "INSERT INTO acompanantes (Nombre, Id_Viatico, Solicitante)
            VALUES ('$acomp', $Id_Viatico, '".$_SESSION['Name']."')";
            $Result_Acomp = mysqli_query($conn, $Insert_Acomp);
            if($Result_Acomp){
                echo "<br>Acompañante registrado correctamente";
            }else{
                echo "<br>Error al registrar el acompañante";
            }
        }
    } else {
        echo "No se registraron acompañantes.";
    }
    
    echo "<br>-------------------------------------------------------------------------<br>";
//////// ------------------ Cuarta Tabla .- Destinos --------------------
// Id - Solicitante - Id_Viatico - Estado - Ciudad
echo "<br>************** Insertando datos en la base de datos -- Tabla: Destinos  ***** ,<br>";

foreach ($ciudades as $ciudad) {
    // Aquí deberías acceder a 'nombre' dentro del array $ciudad
    $nombreCiudad = $ciudad['nombre'];

    // Imprime el nombre de la ciudad para verificar
    echo "<br>Ciudad: $nombreCiudad<br>";

    // Corrige el query para insertar el nombre correcto de la ciudad
    $Insert_Destinos = "INSERT INTO destino (Id_Viatico, Solicitante, Estado, Ciudad)
                        VALUES ('$Id_Viatico', '".$_SESSION['Name']."', '$destino', '$nombreCiudad')";

    $Result_Destinos = mysqli_query($conn, $Insert_Destinos);

    if ($Result_Destinos) {
        echo "<br>Ciudad registrada correctamente: $nombreCiudad";
    } else {
        echo "<br>Error al registrar la ciudad: " . mysqli_error($conn);
    }
}


echo "<br>-------------------------------------------------------------------------<br>";

///// ----------------- Quinta Tabla .- Conceptos ------------------------

    /// Iterar el Arreglo para hacer una inserción por cada concepto
    echo "<br>************** Insertando datos en la base de datos -- Tabla: Conceptos  ***** ,<br>";
    for ($i = 0; $i < count($conceptos); $i++) {
        $concepto = $conceptos[$i];
        $monto = $Montos[$i];
        echo "<br>Concepto: $concepto";
        echo "<br>Monto: $monto";
        $Insert_Conceptos = "INSERT INTO conceptos (Id_Viatico, Solicitante, Concepto, Monto)
        VALUES ($Id_Viatico, '".$_SESSION['Name']."','$concepto', $monto)";
        $Result_Conceptos = mysqli_query($conn, $Insert_Conceptos);
        if($Result_Conceptos){
            echo "<br>Concepto registrado correctamente<br>";
        }else{
            echo "<br>Error al registrar el concepto";
        }
    }

    echo "<br>-------------------------------------------------------------------------<br>";

    /// Crear registro de verificaicón:
        // Id - Id_Viatico - Aceptado_Control - Aceptado_Gerente - Solicitante
        echo "<br>************** Insertando datos en la base de datos -- Tabla: Verificacion  ***** ,<br>";
        $Insert_Verificacion = "INSERT INTO verificacion (Tipo, Id_Relacionado, Aceptado_Control, Aceptado_Gerente, Solicitante)
        VALUES ('Viatico', $Id_Viatico,'Pendiente', 'Pendiente', '".$_SESSION['Name']."')";
        $Result_Verificacion = mysqli_query($conn, $Insert_Verificacion);
        if($Result_Verificacion){
            echo "<br>Verificación registrada correctamente";
        }else{
            echo "<br>Error al registrar la verificación";
        }

        echo "<br>-------------------------------------------------------------------------<br>";

    ////// ---------------- Creación del archivo Excel ----------------

    /// Crear el archivo de Excel

    require '../../../vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('Plantilla-Resumen.xlsx');


    // Cargar la plantilla de Excel
    $sheet = $spreadsheet->getActiveSheet();

    // Llenar las celdas con los datos del formulario
$sheet->setCellValue('D8', $Nombre_Solicitante);
$sheet->setCellValue('H5', $Id_Viatico);
$sheet->setCellValue('J6', $Fecha);
$sheet->setCellValue('D12', $Fecha_Salida);
$sheet->setCellValue('H12', $Fecha_Regreso);
// Obtener diferencia de días entre la fecha de salida y la fecha de regreso
$Fecha_Salida = new DateTime($Fecha_Salida);
$Fecha_Regreso = new DateTime($Fecha_Regreso);
$Diferencia = $Fecha_Salida->diff($Fecha_Regreso);
$Dias = $Diferencia->days;
$sheet->setCellValue('L12', $Dias);
$sheet->setCellValue('D14', $Hora_Salida);
$sheet->setCellValue('J14', $Hora_Regreso);
$sheet->setCellValue('D16', $Orden_Venta);
$sheet->setCellValue('J16', $Codigo);
$NombreCompleto = $Orden_Venta.' '.$Codigo.' '.$Nombre_Proyecto;  
$sheet->setCellValue('D18', $NombreCompleto);
$sheet->setCellValue('D22', $destino);

$Destinos_Array = [];
foreach ($ciudades as $ciudad) {
    $Destinos_Array[] = $ciudad['nombre'];
}

$row = 20;
// Llenar los datos de los acompañantes
foreach ($Destinos_Array as $destino) {
    echo "Destino: $destino<br>";
    $sheet->setCellValue('H'.$row, $destino);
    $row = $row + 2;
}

$Clientes_Array = [];
foreach ($clientes as $cliente) {
    $Clientes_Array[] = [
        'Nombre' => $cliente['nombre'],
        'Motivo' => $cliente['motivo'],
        'Fecha' => $cliente['fecha']
    ];
}


$row = 26;
echo "<br>************ Clientes ************<br>";
foreach ($clientes as $cliente) {
    $nombre = $cliente['nombre'];
    $motivo = $cliente['motivo'];
    $fecha = $cliente['fecha'];

    // Si el motivo es "Otro", usamos el valor del campo "otro_motivo"
    if ($motivo === 'Otro' && !empty($cliente['otro_motivo'])) {
        $motivo = strtoupper(trim($cliente['otro_motivo']));
    }

    // Asignar valores a las celdas en el archivo Excel
    $sheet->setCellValue('B'.$row, $nombre);
    $sheet->setCellValue('J'.$row, $motivo);
    $sheet->setCellValue('E'.$row, $fecha);

    // Aumentamos la fila para la siguiente entrada
    $row = $row + 2;

    // Mensaje para depuración
    echo "<br>Cliente: $nombre<br>";
    echo "<br>Motivo: $motivo<br>";
    echo "<br>Fecha: $fecha<br>";
    echo "<br>------------------------<br>";
}



$Gastos_Array = [];
foreach ($conceptos as $index => $concepto) {
    // Limita el bucle a los primeros 4 elementos
    if ($index >= 4) {
        break; // Sale del bucle después de 4 iteraciones
    }

    $Gastos_Array[] = [
        'Concepto' => $concepto,
        'Monto' => $Montos[$index]
    ];
}


// Llenar los datos de los gastos -D34
$Monto_Total = 0;
$row = 34;
foreach ($Gastos_Array as $gasto) {
    echo "Concepto: ".$gasto['Concepto']."<br>";
    echo "Monto: ".$gasto['Monto']."<br>";
    $Monto_Total += $gasto['Monto'];
    $sheet->setCellValue('B'.$row, $gasto['Concepto']);
    $sheet->setCellValue('D'.$row, $gasto['Monto']);
    $row++;
}

$row++;
$sheet->setCellValue('D38', $Hospedaje);
$sheet->setCellValue('D39', $Vuelos);
$sheet->setCellValue('J39', $Monto_Total);
$sheet->setCellValue('J34', $Alimentacion);
$sheet->setCellValue('J35', $Transporte);
$sheet->setCellValue('J36', $Estacionamiento);
$sheet->setCellValue('J37', $Gasolina);
$sheet->setCellValue('J38', $Casetas);
$sheet->setCellValue('J39', $Addition);

$Acompanantes_Array = [];
foreach ($acompanantes as $acompanante) {
    $Acompanantes_Array[] = $acompanante;
}

// Llenar los datos de los acompañantes
$row = 42;
foreach ($Acompanantes_Array as $acompanante) {
    echo "Acompañante: $acompanante<br>";
    $sheet->setCellValue( 'F'.$row, $acompanante);
    $row++;
}

/// El nombre del archivo sera conformado del Id del viatico + - + Orden de venta + - + Código + - + - + Nombre del proyecto



// Guardar el archivo
// Crear Nombre para el archivo en base al nombre del solicitante y el id del viático + la fecha de hoy
$Nombre_Archivo = 'Viaticos-' . $Id_Viatico.'-'.$Nombre_Proyecto.'.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save('../../../uploads/files/' . $Nombre_Archivo);


/// Guardar información del archivo en la base de datos
$Insert_Archivo = "INSERT INTO resumen_solicitud (Id_Viatico, Nombre, Solicitante)
VALUES ($Id_Viatico, '$Nombre_Archivo', '".$_SESSION['Name']."')";
$Result_Archivo = mysqli_query($conn, $Insert_Archivo);
if($Result_Archivo){
    echo "<br>Archivo registrado correctamente";
    header('Location ../../../../../src/Viaticos/detalles.php?id=' . $Id_Viatico);
}else{
    echo "<br>Error al registrar el archivo";
}

echo "<br>-------------------------------------------------------------------------<br>";

//header('Location: ../Mail/NewViatico.php?Id='.$Id_Viatico.'&Archivo='.$Nombre_Archivo);

//header('Location: ../Notificaciones.php?Id='.$Id_Viatico.'&Name='.$Nombre_Solicitante.'&Request=Registro&Archivo='.$Nombre_Archivo);


header("Location: ../../../../../src/Viaticos/MisViaticos.php");

?>
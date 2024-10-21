<?php
require('../../config/db.php');
session_start();
// Información de a sesion:
echo "Usuario: " . $_SESSION['Name'] . "<br>";
echo "Puesto: " . $_SESSION['Position'] . "<br>";
$Nombre_Solicitante = $_SESSION['Name'];

echo "<br>------------- Solicitud de Viático -------------<br>";
$Id_Viatico = $_POST['Id_Viatico'];
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

$MATERIALES = $_POST['MATERIALES'];
$GASTOS_MEDICOS = $_POST['GASTOS_MEDICOS'];
$EQUIPOS = $_POST['EQUIPOS'];
$HOSPEDAJE = $_POST['HOSPEDAJE'];
$VUELOS = $_POST['VUELOS'];
$ALIMENTACION = $_POST['ALIMENTACION'];
$TRANSPORTE = $_POST['TRANSPORTE'];
$ESTACIONAMIENTO = $_POST['ESTACIONAMIENTO'];
$GASOLINA = $_POST['GASOLINA'];
$CASETAS = $_POST['CASETAS'];
$Nombre_Concepto = $_POST['Nombre_Concepto'];
$Monto_Concepto = $_POST['Nombre_Concepto_value'];
echo "<br> - Gastos Médicos: $GASTOS_MÉDICOS";
echo "<br> - Equipos: $EQUIPOS";
echo "<br> - Hospedaje: $HOSPEDAJE";
echo "<br> - Vuelos: $VUELOS";
echo "<br> - Alimentación: $ALIMENTACIÓN";
echo "<br> - Transporte: $TRANSPORTE";
echo "<br> - Estacionamiento: $ESTACIONAMIENTO";
echo "<br> - Gasolina: $GASOLINA";
echo "<br> - Casetas: $CASETAS";
echo "<br> - Nombre del Concepto: $Nombre_Concepto";
echo "<br> - Monto del Concepto: $Monto_Concepto";


$Addition = $GASTOS_MÉDICOS + $EQUIPOS + $HOSPEDAJE + $VUELOS + $ALIMENTACIÓN + $TRANSPORTE + $ESTACIONAMIENTO + $GASOLINA + $CASETAS + $Monto_Concepto;
echo "<br> - Total: $Addition";



// Si existe un concepto adicional
$Nombre_Concepto = isset($_POST['Nombre_Concepto']) ? $_POST['Nombre_Concepto'] : '';
$Monto_Concepto = isset($_POST['Nombre_Concepto_value']) ? $_POST['Nombre_Concepto_value'] : '';

// Array de conceptos predefinidos y sus valores
$conceptos = [
    'GASTOS MÉDICOS' => $GASTOS_MEDICOS,
    'EQUIPOS' => $EQUIPOS,
    'HOSPEDAJE' => $HOSPEDAJE,
    'VUELOS' => $VUELOS,
    'ALIMENTACIÓN' => $ALIMENTACION,
    'TRANSPORTE' => $TRANSPORTE,
    'ESTACIONAMIENTO' => $ESTACIONAMIENTO,
    'GASOLINA' => $GASOLINA,
    'CASETAS' => $CASETAS,
    $Nombre_Concepto => $Monto_Concepto

];


// Iterar sobre cada concepto para actualizarlo en la base de datos
foreach ($conceptos as $nombreConcepto => $monto) {
    // Sanitizar los valores para evitar inyecciones SQL
    $nombreConcepto = mysqli_real_escape_string($conn, $nombreConcepto);
    $monto = mysqli_real_escape_string($conn, $monto);

    // Generar la consulta SQL para actualizar cada concepto
    $updateQuery = "UPDATE conceptos 
                    SET Monto = '$monto' 
                    WHERE Concepto = '$nombreConcepto' AND Id_Viatico = '$Id_Viatico'";

    // Ejecutar la consulta
    if (mysqli_query($conn, $updateQuery)) {
        echo "INFO: Concepto '$nombreConcepto' actualizado correctamente con el monto de $monto.<br>";
    } else {
        echo "ERROR: No se pudo actualizar el concepto '$nombreConcepto': " . mysqli_error($conn) . "<br>";
    }
}

// Si hay un concepto adicional, también lo procesamos
if (!empty($Nombre_Concepto) && !empty($Monto_Concepto)) {
    // Sanitizar los valores
    $Nombre_Concepto = mysqli_real_escape_string($conn, $Nombre_Concepto);
    $Monto_Concepto = mysqli_real_escape_string($conn, $Monto_Concepto);

    // Generar la consulta SQL para el concepto adicional
    $updateQuery = "UPDATE conceptos 
                    SET Monto = '$Monto_Concepto' 
                    WHERE Concepto = '$Nombre_Concepto' AND Id_Viatico = '$Id_Viatico'";

    // Si no existe el concepto, insertar uno nuevo
    if (!mysqli_query($conn, $updateQuery)) {
        // Intentamos insertarlo si no existe
        $insertQuery = "INSERT INTO conceptos (Concepto, Monto, Id_Viatico) 
                        VALUES ('$Nombre_Concepto', '$Monto_Concepto', '$Id_Viatico')";
        if (mysqli_query($conn, $insertQuery)) {
            echo "INFO: Concepto '$Nombre_Concepto' insertado correctamente con el monto de $Monto_Concepto.<br>";
        } else {
            echo "ERROR: No se pudo insertar el concepto '$Nombre_Concepto': " . mysqli_error($conn) . "<br>";
        }
    } else {
        echo "INFO: Concepto '$Nombre_Concepto' actualizado correctamente con el monto de $Monto_Concepto.<br>";
    }
}


// Inicializa un array vacío para almacenar los acompañantes
$acompanantes = [];

// Captura los valores de los acompañantes dinámicamente
for ($i = 1; $i <= 6; $i++) {
    if (!empty($_POST["acomp_$i"])) {
        $acompanantes[] = strtoupper($_POST["acomp_$i"]);
    }
}

$Codigo_Completo = $Codigo_Prefix . "-" . $Codigo;
echo "<br>-------- Datos recibidos --------<br>";
echo "Fecha de salida: $Fecha_Salida<br>";
echo "Fecha de regreso: $Fecha_Regreso<br>";
echo "Hora de salida: $Hora_Salida<br>";
echo "Hora de regreso: $Hora_Regreso<br>";
echo "Orden de venta: $Orden_Venta<br>";
echo "Código: $Codigo_Completo<br>";
echo "Nombre del proyecto: $Nombre_Proyecto<br>";
echo "<br><br><br>--------------- Clientes -------------------<br>";

// Insertar datos en la base de datos
/// Query Para UPDATE la tabla de viáticos
echo "<br>*** Insertando datos en la base de datos -- Tabla: viaticos  ***** ,<br>";
echo "Solicitante: " . $_SESSION['Name'] . "<br>";
echo "Fecha de salida: $Fecha_Salida<br>";
echo "Hora de salida: $Hora_Salida<br>";
echo "Fecha de regreso: $Fecha_Regreso<br>";
echo "Hora de regreso: $Hora_Regreso<br>";
echo "Orden de venta: $Orden_Venta<br>";
echo "Código: $Codigo_Completo<br>";
echo "Nombre del proyecto: $Nombre_Proyecto<br>";
echo "Destino : $destino<br>";
echo "Total: $Addition<br>";


$Update_Viatico = "UPDATE viaticos SET Fecha_Salida = '$Fecha_Salida', Hora_Salida = '$Hora_Salida', Fecha_Regreso = '$Fecha_Regreso', Hora_Regreso = '$Hora_Regreso', Orden_Venta = '$Orden_Venta', Codigo = '$Codigo_Completo', Nombre_Proyecto = '$Nombre_Proyecto', Destino = '$destino', Total = '$Addition'
     WHERE Id = '$Id_Viatico'";
$Result_Update = mysqli_query($conn, $Update_Viatico);
if ($Result_Update) {
    echo "<br>INFO:: - Viático actualizado correctamente<br>";
} else {
    echo "<br>Error al actualizar el viático";
}

// Mostrar los registros de Destino
echo "<br>*** Insertando datos en la base de datos -- Tabla: Conceptos  ***** ,<br>";
echo "<br>";


$Update_Query = "UPDATE destino SET Estado = '$destino' WHERE Id_Viatico = '$Id_Viatico'";
$Result_Update = mysqli_query($conn, $Update_Query);
if ($Result_Update) {
    echo "<br>Destino actualizado correctamente";
} else {
    echo "<br>Error al actualizar el destino";
}


// Primero obtenemos todas las ciudades registradas en la base de datos para este viático
$Ciudades_DB = [];
$Select_Query = "SELECT Ciudad FROM destino WHERE Id_Viatico = '$Id_Viatico'";
$Result_Select = mysqli_query($conn, $Select_Query);

while ($row = mysqli_fetch_assoc($Result_Select)) {
    $Ciudades_DB[] = $row['Ciudad'];
}

// Procesamos el arreglo de ciudades del formulario
foreach ($ciudades as $ciudad) {
    $Nombre_Ciudad = $ciudad['nombre'];

    // Verificamos si la ciudad ya está en la base de datos
    $Check_Query = "SELECT * FROM destino WHERE Id_Viatico = '$Id_Viatico' AND Ciudad = '$Nombre_Ciudad'";
    $Result_Check = mysqli_query($conn, $Check_Query);

    if (mysqli_num_rows($Result_Check) > 0) {
        // La ciudad ya está en la base de datos, actualizamos si es necesario
        $Update_Query = "UPDATE destino SET Ciudad = '$Nombre_Ciudad' WHERE Id_Viatico = '$Id_Viatico' AND Ciudad = '$Nombre_Ciudad'";
        $Result_Update = mysqli_query($conn, $Update_Query);
        if ($Result_Update) {
            echo "<br>INFO: - Ciudad $Nombre_Ciudad actualizada correctamente<br>";
        } else {
            echo "<br>Error al actualizar la ciudad $Nombre_Ciudad";
        }
    } else {
        // Si la ciudad no está en la base de datos, la insertamos
        $Insert_Query = "INSERT INTO destino (Id_Viatico, Ciudad, Estado) VALUES ('$Id_Viatico', '$Nombre_Ciudad', '$destino')";
        $Result_Insert = mysqli_query($conn, $Insert_Query);
        if ($Result_Insert) {
            echo "<br>INFO: - Ciudad $Nombre_Ciudad insertada correctamente<br>";
        } else {
            echo "<br>Error al insertar la ciudad $Nombre_Ciudad";
        }
    }
}

// Ahora eliminamos las ciudades que ya no están en el arreglo pero que sí existen en la base de datos
foreach ($Ciudades_DB as $ciudadDB) {
    $existeEnFormulario = false;

    foreach ($ciudades as $ciudadFormulario) {
        if ($ciudadFormulario['nombre'] == $ciudadDB) {
            $existeEnFormulario = true;
            break;
        }
    }

    // Si la ciudad de la base de datos no está en el formulario, la eliminamos
    if (!$existeEnFormulario) {
        $Delete_Query = "DELETE FROM destino WHERE Id_Viatico = '$Id_Viatico' AND Ciudad = '$ciudadDB'";
        $Result_Delete = mysqli_query($conn, $Delete_Query);
        if ($Result_Delete) {
            echo "<br>INFO: - Ciudad $ciudadDB eliminada correctamente<br>";
        } else {
            echo "<br>Error al eliminar la ciudad $ciudadDB";
        }
    }
}

// Actualizar los clientes

echo "<br>*** Insertando datos en la base de datos -- Tabla: Clientes  ***** ,<br>";
echo "<br>";
/// Primero obtenemos todos los clientes registrados en la base de datos para este viático
$Clientes_DB = [];
$Select_Query = "SELECT * FROM clientes WHERE Id_Viatico = '$Id_Viatico'";
$Result_Select = mysqli_query($conn, $Select_Query);

while ($row = mysqli_fetch_assoc($Result_Select)) {
    $Clientes_DB[] = $row['Nombre'];/// <- Se almacenan sus diferentes atributos en arreglos
}


// Procesamos el arreglo de clientes del formulario
foreach ($clientes as $cliente) {
    $Nombre_Cliente = $cliente['nombre'];
    $Motivo_Cliente = $cliente['motivo'];
    $Fecha_Cliente = $cliente['fecha'];
    //echo "<br>Cliente: $Nombre_Cliente";
    //echo "<br>Motivo: $Motivo_Cliente";
    echo "<br>//////////// -------- Fecha: $Fecha_Cliente";

    // Verificamos si el cliente ya está en la base de datos
    $Check_Query = "SELECT * FROM clientes WHERE Id_Viatico = '$Id_Viatico' AND Nombre = '$Nombre_Cliente'";
    $Result_Check = mysqli_query($conn, $Check_Query);

    if (mysqli_num_rows($Result_Check) > 0) {
        // El cliente ya está en la base de datos, actualizamos si es necesario
        $Update_Query = "UPDATE clientes SET Solicitante = '$Nombre_Solicitante' , Nombre = '$Nombre_Cliente', Motivo = '$Motivo_Cliente', Fecha = '$Fecha_Cliente' WHERE Id_Viatico = '$Id_Viatico' AND Nombre = '$Nombre_Cliente'";
        $Result_Update = mysqli_query($conn, $Update_Query);
        if ($Result_Update) {
            echo "<br>INFO: - Cliente $Nombre_Cliente actualizado correctamente<br>";
        } else {
            echo "<br>Error al actualizar el cliente $Nombre_Cliente";
        }
    } else {
        // Si el cliente no está en la base de datos, lo insertamos
        $Insert_Query = "INSERT INTO clientes (Solicitante,Id_Viatico, Nombre, Motivo, Fecha) VALUES ('$Nombre_Solicitante','$Id_Viatico', '$Nombre_Cliente', '$Motivo_Cliente', '$Fecha_Cliente')";
        $Result_Insert = mysqli_query($conn, $Insert_Query);
        if ($Result_Insert) {
            echo "<br>INFO: - Cliente $Nombre_Cliente insertado correctamente<br>";
        } else {
            echo "<br>Error al insertar el cliente $Nombre_Cliente";
        }
    }
}

/// Eliminar los clientes que ya no estan en el arreglo pero si en la base de datos
foreach ($Clientes_DB as $clienteDB) {

    $existeEnFormulario = false;

    foreach ($clientes as $clienteFormulario) {
        if ($clienteFormulario['nombre'] == $clienteDB) {
            $existeEnFormulario = true;
            //echo "<br>Cliente $clienteDB existe en el formulario";
            break;
        }
    }

    // Si el cliente de la base de datos no está en el formulario, lo eliminamos
    if (!$existeEnFormulario) {
        echo "<br>Cliente $clienteDB no existe en el formulario";
        $Delete_Query = "DELETE FROM clientes WHERE Id_Viatico = '$Id_Viatico' AND Nombre = '$clienteDB'";
        $Result_Delete = mysqli_query($conn, $Delete_Query);
        if ($Result_Delete) {
            echo "<br>INFO: - Cliente $clienteDB eliminado correctamente<br>";
        } else {
            echo "<br>Error al eliminar el cliente $clienteDB";
        }
    }
}

// Actualizar los acompañantes

echo "<br>*** Insertando datos en la base de datos -- Tabla: Acompañantes  ***** ,<br>";
echo "<br>";
/// Primero obtenemos todos los acompañantes registrados en la base de datos para este viático
$Acompanantes_DB = [];
$Select_Query = "SELECT * FROM acompanantes WHERE Id_Viatico = '$Id_Viatico'";
$Result_Select = mysqli_query($conn, $Select_Query);

while ($row = mysqli_fetch_assoc($Result_Select)) {
    $Acompanantes_DB[] = $row['Nombre']; /// <- Se almacenan sus diferentes atributos en arreglos
}

// Procesamos el arreglo de acompañantes del formulario

foreach ($acompanantes as $acompanante) {
    $Nombre_Acompanante = $acompanante;
    //echo "<br>Acompañante: $Nombre_Acompanante";

    // Verificamos si el acompañante ya está en la base de datos
    $Check_Query = "SELECT * FROM acompanantes WHERE Id_Viatico = '$Id_Viatico' AND Nombre = '$Nombre_Acompanante'";
    $Result_Check = mysqli_query($conn, $Check_Query);

    if (mysqli_num_rows($Result_Check) > 0) {
        // El acompañante ya está en la base de datos, actualizamos si es necesario
        $Update_Query = "UPDATE acompanantes SET Nombre = '$Nombre_Acompanante' WHERE Id_Viatico = '$Id_Viatico' AND Nombre = '$Nombre_Acompanante'";
        $Result_Update = mysqli_query($conn, $Update_Query);
        if ($Result_Update) {
            echo "<br>INFO: - Acompañante $Nombre_Acompanante actualizado correctamente<br>";
        } else {
            echo "<br>Error al actualizar el acompañante $Nombre_Acompanante";
        }
    } else {
        // Si el acompañante no está en la base de datos, lo insertamos
        $Insert_Query = "INSERT INTO acompanantes (Id_Viatico, Nombre) VALUES ('$Id_Viatico', '$Nombre_Acompanante')";
        $Result_Insert = mysqli_query($conn, $Insert_Query);
        if ($Result_Insert) {
            echo "<br>INFO: - Acompañante $Nombre_Acompanante insertado correctamente<br><br>";
        } else {
            echo "<br>Error al insertar el acompañante $Nombre_Acompanante";
        }
    }
}

/// Eliminar los acompañantes que ya no estan en el arreglo pero si en la base de datos
foreach ($Acompanantes_DB as $acompananteDB) {

    $existeEnFormulario = false;

    foreach ($acompanantes as $acompananteFormulario) {
        if ($acompananteFormulario == $acompananteDB) {
            $existeEnFormulario = true;
            //echo "<br>Acompañante $acompananteDB existe en el formulario";
            break;
        }
    }

    // Si el acompañante de la base de datos no está en el formulario, lo eliminamos
    if (!$existeEnFormulario) {
        echo "<br>Acompañante $acompananteDB no existe en el formulario";
        $Delete_Query = "DELETE FROM acompanantes WHERE Id_Viatico = '$Id_Viatico' AND Nombre = '$acompananteDB'";
        $Result_Delete = mysqli_query($conn, $Delete_Query);
        if ($Result_Delete) {
            echo "<br>INFO: - Acompañante $acompananteDB eliminado correctamente<br>";
        } else {
            echo "<br>Error al eliminar el acompañante $acompananteDB";
        }
    }
}


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
$NombreCompleto = $Orden_Venta . ' ' . $Codigo . ' ' . $Nombre_Proyecto;
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
    $sheet->setCellValue('H' . $row, $destino);
    $row = $row + 2;
}

$row = 26;
// Recorrer los clientes enviados
foreach ($_POST['clientes'] as $cliente) {
    $nombre = $cliente['nombre'];
    $motivo = $cliente['motivo'];
    $fecha = $cliente['fecha'];
    
    // Si el motivo es "Otro", obtén el campo 'otro_motivo'
    if ($motivo === 'Otro') {
        $otroMotivo = $cliente['otro_motivo'];
        // Procesa el otro motivo
    }

    echo "<br>Cliente: $nombre<br>";
    echo "<br>Motivo: $motivo<br>";
    echo "<br>Fecha: $fecha<br>";
    echo "<br>------------------------<br>";

    $sheet->setCellValue('B' . $row, $nombre);
    $sheet->setCellValue('J' . $row, $motivo);
    $sheet->setCellValue('E' . $row, $fecha);
    $row = $row + 2;

    // Procesa el nombre y motivo del cliente
}



$sheet->setCellValue('D34', $MATERIALES);
$sheet->setCellValue('D35', $EQUIPOS);
$sheet->setCellValue('D36', $GASTOS_MEDICOS);
$sheet->setCellValue('B37', $Nombre_Concepto);
$sheet->setCellValue('D37', $Monto_Concepto);
$sheet->setCellValue('D38', $HOSPEDAJE);
$sheet->setCellValue('D39', $VUELOS);

$sheet->setCellValue('J34', $ALIMENTACION);
$sheet->setCellValue('J35', $TRANSPORTE);
$sheet->setCellValue('J36', $ESTACIONAMIENTO);
$sheet->setCellValue('J37', $GASOLINA);
$sheet->setCellValue('J38', $CASETAS);
$sheet->setCellValue('J39', $Addition);


$Acompanantes_Array = [];
foreach ($acompanantes as $acompanante) {
    $Acompanantes_Array[] = $acompanante;
}

// Llenar los datos de los acompañantes
$row = 42;
foreach ($Acompanantes_Array as $acompanante) {
    echo "Acompañante: $acompanante<br>";
    $sheet->setCellValue('F' . $row, $acompanante);
    $row++;
}

// Guardar el archivo
// Crear Nombre para el archivo en base al nombre del solicitante y el id del viático + la fecha de hoy
$Nombre_Archivo = $Nombre_Solicitante . '-' . $Id_Viatico . '-' . $Fecha . '.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save('../../../uploads/files/' . $Nombre_Archivo);

/// Eliminar el archivo anterior
$Delete = "DELETE FROM resumen_solicitud WHERE Id_Viatico = $Id_Viatico AND Solicitante = '" . $_SESSION['Name'] . "'";
$Result_Delete = mysqli_query($conn, $Delete);
if ($Result_Delete) {
    echo "<br>Archivo anterior eliminado correctamente";
} else {
    echo "<br>Error al eliminar el archivo anterior";
}

/// Guardar información del archivo en la base de datos
$Insert_Archivo = "INSERT INTO resumen_solicitud (Id_Viatico, Nombre, Solicitante)
VALUES ($Id_Viatico, '$Nombre_Archivo', '" . $_SESSION['Name'] . "')";
$Result_Archivo = mysqli_query($conn, $Insert_Archivo);
if ($Result_Archivo) {
    echo "<br>Archivo registrado correctamente";
} else {
    echo "<br>Error al registrar el archivo";
}

header("Location: ../../../../../src/Viaticos/editar.php?id=$Id_Viatico");
?>
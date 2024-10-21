    <?php
    echo "------------- Descargar resumen de los reembolsos.php -------------<br>";
    require('../../config/db.php');
    session_start();
    require '../../../vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx ;

    $Id_Reembolso_Maestro = $_GET['Id'];
    // Crear el array para almacenar los reembolsos anidados
    $reembolsos_anidados_array = array();

    echo "EL ID del reembolso es: $Id_Reembolso_Maestro<br>";
    /// Obtener información del reembolso:
    $Query_Reembolso_Maestro = "SELECT * FROM reembolsos WHERE Id = $Id_Reembolso_Maestro";
    $Result_Reembolso_Maestro = mysqli_query($conn, $Query_Reembolso_Maestro);
    if ($Result_Reembolso_Maestro->num_rows > 0) {
        $row = $Result_Reembolso_Maestro->fetch_assoc();
        $Nombre_Proyecto = $row['Nombre_Proyecto'];
        $Codigo = $row['Codigo'];
        $Orden_Venta = $row['Orden_Venta'];
        $Solicitante = $row['Solicitante'];
        $Cliente = $row['Cliente'];
        $Concepto = $row['Concepto'];
        $Monto = $row['Monto'];
        $Destino = $row['Destino'];
        $Fecha = $row['Fecha'];
        $Descripcion = $row['Descripcion'];
        $Estado = $row['Estado'];
    } else {
        echo "Error: " . $Query_Reembolso_Maestro . "<br>" . $conn->error;
    }

    echo "<br> Información del Reembolso. <br>";
    echo "<br> - Solicitante: $Solicitante";
    echo "<br> - Concepto: $Concepto";
    echo "<br> - Monto: $Monto";
    echo "<br> - Destino: $Destino";
    echo "<br> - Fecha: $Fecha";
    echo "<br> - Descripción: $Descripcion";
    echo "<br> - Estado: $Estado";
    echo "<br> - Nombre Proyecto: $Nombre_Proyecto";
    echo "<br> - Código: $Codigo";
    echo "<br> - Orden de Venta: $Orden_Venta";
    echo "<br> - Nomenclatura Completa: $Nomenclatura_Completa";

    $Nomenclatura_Completa = $Orden_Venta . " - " . $Codigo . " - " . $Nombre_Proyecto;

    $Nombre_Archivo = 'Reembolso -'. $Id_Reembolso_Maestro.'-'.$Orden_Venta.'-'.$Codigo.'-'.$Nombre_Proyecto.'.xlsx';

    // Definir la ruta donde están almacenados los archivos
    $Ruta_Archivo = '../../../uploads/files/' . $Nombre_Archivo;

    /// Condicional .- Si ya existe el archivo, dentro de la base de datos, solamente se descarga
    /// Si no, se crea el archivo y se descarga

    $Query_Archivo = "SELECT * FROM resumen_reembolsos WHERE Nombre_Archivo = '$Nombre_Archivo'";
    $Result_Archivo = mysqli_query($conn, $Query_Archivo);

    if ($Result_Archivo && $Result_Archivo->num_rows > 0) {
        // Limpiar cualquier salida previa
        ob_clean();  // Limpia el búfer de salida si hay contenido previo
        flush();     // Asegura que el búfer esté limpio

        // Verificar si el archivo existe físicamente en el servidor
        if (file_exists($Ruta_Archivo)) {
            // Enviar el archivo para descargar
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $Nombre_Archivo . '"');
            header('Cache-Control: max-age=0');

            // Leer el archivo y enviarlo al navegador
            readfile($Ruta_Archivo);
            exit; // Finaliza el script después de la descarga
        } else {
            echo "Error: El archivo fue registrado en la base de datos pero no se encuentra en el servidor.";
        }
    } else{
        echo "No se encontró el archivo en la base de datos. Se procederá a crearlo.";
        /// ----------------- Obtener información de los Reembolsos Anidados


        /// Aqui va el query 
        $Query_Reembolsos_Anidados = "SELECT * FROM reembolsos_anidados WHERE Id_Reembolso = $Id_Reembolso_Maestro";
        $Result_Reembolsos_Anidados = mysqli_query($conn, $Query_Reembolsos_Anidados);
        if ($Result_Reembolsos_Anidados->num_rows > 0) {
            while ($row = $Result_Reembolsos_Anidados->fetch_assoc()) {
                // Almacenar cada reembolso en el array
                $reembolsos_anidados_array[] = array(
                    'Id' => $row['Id'],
                    'Concepto' => $row['Concepto'],
                    'Cliente' => $row['Cliente'],
                    'Monto' => $row['Monto'],
                    'Destino' => $row['Destino'],
                    'Fecha' => $row['Fecha'],
                    'Descripcion' => $row['Descripcion'],
                    'Estado' => $row['Estado'],
                    'Nombre_Archivo' => $row['Nombre_Archivo'],
                    'Nombre_Proyecto' => $row['Nombre_Proyecto'],
                    'Codigo' => $row['Codigo'],
                    'Orden_Venta' => $row['Orden_Venta']
                );
            }
        } else {
            echo "Error: " . $Query_Reembolsos_Anidados . "<br>" . $conn->error;
        }

        /// Obtener información del solicitante
        $Query_Solicitante = "SELECT * FROM usuarios WHERE Nombre = '$Solicitante'";
        $Result_Solicitante = mysqli_query($conn, $Query_Solicitante);
        if ($Result_Solicitante->num_rows > 0) {
            $row = $Result_Solicitante->fetch_assoc();
            $Nombre_Solicitante = $row['Nombre'];
            $Correo_Solicitante = $row['Correo'];
            $Gerente = $row['Gerente'];
            $Puesto = $row['Puesto'];
            $NSS = $row['NSS'];
            $Telefono = $row['Telefono'];

        } else {
            echo "Error: " . $Query_Solicitante . "<br>" . $conn->error;
        }

        echo "<br><br> Información del Solicitante. <br>";
        echo "<br> - Nombre: $Nombre_Solicitante";
        echo "<br> - Correo: $Correo_Solicitante";
        echo "<br> - Gerente: $Gerente";
        echo "<br> - Puesto: $Puesto";
        echo "<br> - NSS: $NSS";
        echo "<br> - Teléfono: $Telefono";

        echo "<br>-----------------------------------------<br>";
        echo "<br> Información de los reembolsos anidados. <br>";
        foreach ($reembolsos_anidados_array as $reembolso_anidado) {
            echo "<br> - Id: " . $reembolso_anidado['Id'];
            echo "<br> - Concepto: " . $reembolso_anidado['Concepto'];
            echo "<br> - Cliente: " . $reembolso_anidado['Cliente'];
            echo "<br> - Monto: " . $reembolso_anidado['Monto'];
            echo "<br> - Destino: " . $reembolso_anidado['Destino'];
            echo "<br> - Fecha: " . $reembolso_anidado['Fecha'];
            echo "<br> - Descripción: " . $reembolso_anidado['Descripcion'];
            echo "<br> - Estado: " . $reembolso_anidado['Estado'];
            echo "<br> - Nombre Archivo: " . $reembolso_anidado['Nombre_Archivo'];
            echo "<br> - Nombre Proyecto: " . $reembolso_anidado['Nombre_Proyecto'];
            echo "<br> - Código: " . $reembolso_anidado['Codigo'];
            echo "<br> - Orden de Venta: " . $reembolso_anidado['Orden_Venta'];
            echo "<br>-----------------------------------------<br>";
        }
    }
        // Nombre de plantilla -> Plantilla-Resumen_Reembolso.xlsx

        ////// ---------------- Creación del archivo Excel ----------------

        /// Crear el archivo de Excel

        if (!file_exists('Plantilla-Resumen_Reembolso.xlsx')) {
            die("Error: No se encontró el archivo de plantilla.");
        }
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('Plantilla-Resumen_Reembolso.xlsx');
        } catch (Exception $e) {
            die('Error cargando archivo de plantilla: ' . $e->getMessage());
        }
        // Cargar la plantilla de Excel
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('Q4', $Fecha);
        $sheet->setCellValue('H6', $Id_Reembolso_Maestro);
        $sheet->setCellValue('M5', $Cliente);
        $sheet->setCellValue('D8', $Solicitante);
        $sheet->setCellValue('N8', $Gerente);
        $sheet->setCellValue('N10', $Destino);
        
        
        // Insertar la información del reembolso
        $sheet->setCellValue('B13', $Id_Reembolso_Maestro);
        $sheet->setCellValue('C13', $Fecha);
        $sheet->setCellValue('D13', $Monto);
        $sheet->setCellValue('E13', $Cliente);
        $sheet->setCellValue('F13', $Descripcion);
        $sheet->setCellValue('G13', $Destino);
        $sheet->setCellValue('H13', $Nomenclatura_Completa);
        // Insertar la información de los reembolsos anidados
        /// Recorrer los reembolsos anidados
        $Fila = 14;
        $Contador = 0;
        $TotalDeMontos = 0;

        foreach ($reembolsos_anidados_array as $reembolso_anidado) {
            $sheet->setCellValue('B' . $Fila, $reembolso_anidado['Id']);
            $sheet->setCellValue('C' . $Fila, $reembolso_anidado['Fecha']);
            $sheet->setCellValue('D' . $Fila, $reembolso_anidado['Monto']);
            $sheet->setCellValue('E' . $Fila, $reembolso_anidado['Cliente']);
            $sheet->setCellValue('F' . $Fila, $reembolso_anidado['Descripcion']);
            $sheet->setCellValue('G' . $Fila, $reembolso_anidado['Destino']);
            $TotalDeMontos += $reembolso_anidado['Monto'];
            $Nomenclatura_completa = $reembolso_anidado['Orden_Venta'] . '-' . $reembolso_anidado['Codigo'] . '-' . $reembolso_anidado['Nombre_Proyecto'];
            $sheet->setCellValue('H' . $Fila, $Nomenclatura_completa);
            $Fila++;
            $Contador++;
        }

        $Fila++;
        $sheet->setCellValue('D' . $Fila, $TotalDeMontos);

        // Guardar el archivo

        try {
            // Verificar si se puede escribir en la carpeta antes de intentar guardar
            if (!is_writable('../../../uploads/files/')) {
                die("Error: No se puede escribir en la carpeta 'uploads/files/'. Verifica los permisos.");
            }

            // Guardar el archivo en el servidor
            $writer = new Xlsx($spreadsheet);
            $writer->save('../../../uploads/files/' . $Nombre_Archivo);

            // Insertar el registro en la base de datos
            $Insert_Archivo = "INSERT INTO resumen_reembolsos (Id_Reembolso, Solicitante, Nombre_Archivo) VALUES ($Id_Reembolso_Maestro, '$Nombre_Solicitante', '$Nombre_Archivo')";
            $Result_Archivo = mysqli_query($conn, $Insert_Archivo);

            if ($Result_Archivo) {
                // Limpiar cualquier salida previa
                ob_clean();  // Limpia el búfer de salida si hay contenido previo
                flush();     // Asegura que el búfer esté limpio

                // Enviar el archivo para descargar
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $Nombre_Archivo . '"');
                header('Cache-Control: max-age=0');

                // Enviar el archivo al navegador
                $writer->save('php://output');
                exit; // Finaliza el script después de la descarga
            } else {
                echo "<br>Error al registrar el archivo en la base de datos: " . mysqli_error($conn);
            }
        } catch (Exception $e) {
            die('Error guardando archivo Excel: ' . $e->getMessage());
        }
    ?>
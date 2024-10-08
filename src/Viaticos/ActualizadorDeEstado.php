<?php

$Actualización = "";


/// Cuando un Viático se encuentre Aceptado y que hoy sea igual o mayor al Día de Salida,
/// Se actualiza a el estado "En Curso"

$SQL = "SELECT * FROM viaticos WHERE Estado = 'Aceptado' AND Fecha_Salida <= CURDATE()";
$Result = mysqli_query($conn, $SQL);
if (mysqli_num_rows($Result) > 0) {
    //echo "<br>:::: Se encontraron registros  con Estado 'Aceptado' y con la fecha de salida igual o menor al día de hoy ::::<br>";
    while ($Row = mysqli_fetch_assoc($Result)) {

        $Id = $Row['Id'];
        //echo "<br>--- Registro con Id: " . $Id;
        //echo "<br>--- Fecha de Salida: " . $Row['Fecha_Salida'];
        //echo "<br>--- Solicitante: " . $Row['Solicitante'];
        //echo "<br>--- Nombre del Proyecto: " . $Row['Nombre_Proyecto'];
        //echo "<br>--- Estado: " . $Row['Estado'];

        $SQL_Update = "UPDATE viaticos SET Estado = 'En Curso' WHERE Id = $Id";
        $Result_Update = mysqli_query($conn, $SQL_Update);
        if ($Result_Update) {
            /// Enviar correo al solicitante para notificar que su viático se encuentra en curso
            header ("Location: ../../../../resources/Back/Mail/viaticoEnCurso.php?Id=$Id");
            /////////////// ---------------------------------------------------- Cuando se actualiza a En Curso
            //echo "<br> Se actualizó correctamente - En Curso";
        } else {
            //echo "<br> No se actualizó correctamente";
        }
    }
} else {
    //echo "<br>:::: No se encontraron registros con Estado 'Aceptado' y con la fecha de salida igual o menor al día de hoy ::::::::<br>";
}


/// Cuando un Viático se encuentre En Curso y que hoy sea igual o mayor al Día de Regreso,
/// Se actualiza a el estado "Verificación"

$SQL = "SELECT * FROM viaticos WHERE Estado = 'En Curso' AND Fecha_Regreso <= CURDATE()";
$Result = mysqli_query($conn, $SQL);
if (mysqli_num_rows($Result) > 0) {
    //echo "<br>:::: Se encontraron registros con Estado 'En Curso' Y Fecha de Regreso igual o menor al día de hoy ::::<br>";
    while ($Row = mysqli_fetch_assoc($Result)) {

        $Id = $Row['Id'];
        //echo "<br>--- Registro con Id: " . $Id;
        //echo "<br>--- Fecha de Salida: " . $Row['Fecha_Salida'];
        //echo "<br>--- Solicitante: " . $Row['Solicitante'];
        //echo "<br>--- Nombre del Proyecto: " . $Row['Nombre_Proyecto'];
        //echo "<br>--- Estado: " . $Row['Estado'];

        $SQL_Update = "UPDATE viaticos SET Estado = 'Verificación' WHERE Id = $Id";
        $Result_Update = mysqli_query($conn, $SQL_Update);
        if ($Result_Update) {
            header ("Location: ../../../../resources/Back/Mail/viaticoEnVerificación.php?Id=$Id");
            //echo "<br> Se actualizó correctamente - Verificación";
        } else {
            //echo "<br> No se actualizó correctamente";
        }
    }
} else {
    //echo "<br>:::: No se encontraron registros con Estado 'En Curso' Y Fecha de Regreso igual o menor al día de hoy :::: ::::<br>";
}

// Función para calcular los días hábiles excluyendo fines de semana
function sumarDiasHabiles($fecha, $dias) {
    $diasHabiles = 0;
    $nuevaFecha = strtotime($fecha); // Convertir la fecha a timestamp
    
    while ($diasHabiles < $dias) {
        // Avanza un día
        $nuevaFecha = strtotime("+1 day", $nuevaFecha);
        // Verifica si el día es un día hábil (no es sábado ni domingo)
        $diaSemana = date("N", $nuevaFecha); // N devuelve 1 para lunes, 7 para domingo
        if ($diaSemana < 6) { // Si es de lunes a viernes
            $diasHabiles++;
        }
    }
    
    return date("Y-m-d", $nuevaFecha); // Retornar la nueva fecha en formato Y-m-d
}

// Obtén todos los registros en estado 'Verificación'
$SQL = "SELECT * FROM viaticos WHERE Estado = 'Verificación'";
$Result = mysqli_query($conn, $SQL);

if (mysqli_num_rows($Result) > 0) {
    while ($Row = mysqli_fetch_assoc($Result)) {
        $Id = $Row['Id'];
        $Fecha_Regreso = $Row['Fecha_Regreso'];

        // Imprimir fecha de regreso para depuración
        echo "<br>Fecha de regreso: " . $Fecha_Regreso;

        // Suma 3 días hábiles a la fecha de regreso
        $fechaLimite = sumarDiasHabiles($Fecha_Regreso, 3);

        // Imprimir la fecha límite calculada para depuración
        echo "<br>Fecha límite (3 días hábiles): " . $fechaLimite;

        // Compara con la fecha actual
        if (strtotime($fechaLimite) < strtotime(date("Y-m-d"))) {
            // Imprimir mensaje cuando se cumpla la condición
            echo "<br>La solicitud con Id: " . $Id . " está fuera de rango.";

            // Actualiza el estado a 'Fuera de Rango' si ya pasaron los días hábiles
            $SQL_Update = "UPDATE viaticos SET Estado = 'Fuera de Rango' WHERE Id = $Id";
            $Result_Update = mysqli_query($conn, $SQL_Update);
            
            if ($Result_Update) {
                echo "<br>El registro con Id: " . $Id . " fue actualizado a 'Fuera de Rango'.";
                header("Location: ../../../../resources/Back/Mail/viaticoFueraDeRango.php?Id=$Id");
            } else {
                echo "<br>No se pudo actualizar el registro con Id: " . $Id;
            }
        } else {
            echo "<br>La solicitud con Id: " . $Id . " aún está en verificación.";
        }
    }
}



?>
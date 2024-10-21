<?php
/// Obtener registros de viaticos
// Fecha de hoy
require ('../../resources/config/db.php');
$today = date("Y-m-d");

/// Si no se obtiene el id del usuario y el id del gerente, se asigna un valor por defecto

$id_gerente = isset($_GET['id_gerente']) ? intval($_GET['id_gerente']) : 0; // Página actual


$queryViaticos = "SELECT * FROM viaticos ";
$resultViaticos = $conn->query($queryViaticos);

if ($resultViaticos->num_rows > 0) {
    $viaticos = array();
    while ($row = $resultViaticos->fetch_assoc()) {
        $viaticos[] = $row;
        /// Guardar en variables
        $id_viatico = $row['Id'];
        $id_usuario = $row['Id_Usuario'];
        $id_gerente = $row['Id_Gerente'];
        $fecha_salida = $row['Fecha_Salida'];
        $fecha_regreso = $row['Fecha_Regreso'];
        $estado = $row['Estado'];

        if ($today >= $fecha_salida && $estado == 'Aceptado') {
            $queryUpdate = "UPDATE viaticos SET Estado = 'EnCurso' WHERE Id = $id_viatico;";
            $resultUpdate = $conn->query($queryUpdate);
            if ($resultUpdate) {
                //echo "Se actualizo el estado" . "<br>";
                /// Si se actualiza, actualizar el registro de la Verificación, asignarlos de nuevo a pendiente
                /// Y asignar Tipo = 'Comprobación'
                $queryUpdateVerificacion = "UPDATE verificacion SET Aceptado_Gerente = 'Pendiente', Aceptado_Control = 'Pendiente' , Tipo = 'Comprobacion' WHERE Id_Viatico = $id_viatico";
                $resultUpdateVerificacion = $conn->query($queryUpdateVerificacion);
                if ($resultUpdateVerificacion) {
                    //echo "Se actualizo el estado de verificacion" . "<br>";
                } else {
                    echo "No se actualizo el estado de verificacion" . "<br>";
                }
            } else {
                //echo "No se actualizo el estado" . "<br>";
            }
        } elseif ($today >= $fecha_regreso && $estado == 'EnCurso') {
            //echo "Ya se paso de regreso" . "<br>";
            $queryUpdate = "UPDATE viaticos SET Estado = 'Verificacion' WHERE Id = $id_viatico;";
            $resultUpdate = $conn->query($queryUpdate);
            if ($resultUpdate) {
                //echo "Se actualizo el estado" . "<br>";
                header('Location: ../../../resources/Back/Mail/intervaloCompletado.php?id_usuario=' . $id_usuario . '&id_gerente=' . $id_gerente . '&id_viatico=' . $id_viatico);
            } else {
                //echo "No se actualizo el estado" . "<br>";
            }
        } else {
            // echo "Aun no ha pasado" . "<br>";
        }

    }
} else {
    //echo "No hay viaticos abiertos";
}

$queryViaticosenVerificacion = "SELECT * FROM viaticos WHERE Estado = 'Verificacion';";
$resultViaticosenVerificacion = $conn->query($queryViaticosenVerificacion);

if ($resultViaticosenVerificacion->num_rows > 0) {
    $viaticosVerificacion = array();
    while ($row = $resultViaticosenVerificacion->fetch_assoc()) {
        $viaticosVerificacion[] = $row;
        /// Guardar en variables
        $id_viatico = $row['Id'];
        $id_usuario = $row['Id_Usuario'];
        $fecha_salida = $row['Fecha_Salida'];
        $fecha_regreso = $row['Fecha_Regreso'];
        $today = new DateTime();

        // Convertir $fecha_regreso a un objeto DateTime
        $fecha_regreso_dt = new DateTime($fecha_regreso);

        // Calcular la diferencia en días laborales
        $difference_in_business_days = getBusinessDays($fecha_regreso_dt->format('Y-m-d'), $today->format('Y-m-d'));

        // Verificar si la diferencia es mayor a 3 días laborales
        if ($difference_in_business_days > 4 && $row['Estado'] == 'Verificacion') {
            //echo "Ya se pasaron 3 días laborales desde la fecha de regreso" . "<br>";
            $queryUpdate = "UPDATE viaticos SET Estado = 'FueraDeRango' WHERE Id = $id_viatico;";
            $resultUpdate = $conn->query($queryUpdate);
            if ($resultUpdate) {
                //echo "Se actualizó el estado" . "<br>";

            } else {
                //echo "No se actualizó el estado" . "<br>";
            }
        }
    }
} else {
    //echo "No hay viaticos en verificacion";
}

function getBusinessDays($startDate, $endDate) {
    $startDate = new DateTime($startDate);
    $endDate = new DateTime($endDate);
    $businessDays = 0;

    while ($startDate <= $endDate) {
        $dayOfWeek = $startDate->format('N'); // 1 (lunes) a 7 (domingo)
        if ($dayOfWeek < 6) { // Si es de lunes a viernes
            $businessDays++;
        }
        $startDate->modify('+1 day');
    }
    
    return $businessDays;
}

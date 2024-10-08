<?php 

include '../../../resources/config/db.php';
$Materiales = $_POST['Materiales'];
$Medicos = $_POST['Medicos'];
$Equipos = $_POST['Equipos'];
$Otro = $_POST['Otro'];
$id_viatico = $_POST['id_viatico'];

echo "Id Viatico: $id_viatico<br>";

$Resultados_Arreglo = array($Materiales, $Medicos, $Equipos, $Otro);
$justification_materiales = isset($_POST['justification_materiales']) && !empty($_POST['justification_materiales']) ? $_POST['justification_materiales'] : " ";
$justification_medicos = isset($_POST['justification_medicos']) && !empty($_POST['justification_medicos']) ? $_POST['justification_medicos'] : " ";
$justification_equipos = isset($_POST['justification_equipos']) && !empty($_POST['justification_equipos']) ? $_POST['justification_equipos'] : " ";
$justification_otro = isset($_POST['justification_otro']) && !empty($_POST['justification_otro']) ? $_POST['justification_otro'] : " ";

$evidencia_materiales = isset($_FILES['evidencia_materiales']) && !empty($_FILES['evidencia_materiales']) ? $_FILES['evidencia_materiales'] : " ";
$evidencia_medicos = isset($_FILES['evidencia_medicos']) && !empty($_FILES['evidencia_medicos']) ? $_FILES['evidencia_medicos'] : " ";
$evidencia_equipos = isset($_FILES['evidencia_equipos']) && !empty($_FILES['evidencia_equipos']) ? $_FILES['evidencia_equipos'] : " ";
$evidenciaMaterialesSeleccionada = $_POST['evidencia_materiales'];
$evidenciaGastosMedicosSleccionada = $_POST['evidencia_medicos'];
$evidenciaEquiposSeleccionada = $_POST['evidencia_equipos'];
$evidencia_otro = $_POST['evidencia_otro'];

echo "La evidencia seleccionada es: " . $evidenciaMaterialesSeleccionada;
echo "<br>Justificacion Materiales: $justification_materiales<br>";
echo "<br>--------------------------------------------------------------------<br>";  
echo "La evidencia seleccionada es: " . $evidenciaGastosMedicosSleccionada;
echo "<br>Justificación de Gastos Médicos: $justification_medicos<br>";
echo "<br>--------------------------------------------------------------------<br>";
echo "La evidencia seleccionada es: " . $evidenciaEquiposSeleccionada;
echo "<br>Justificación de Equipos: $justification_equipos<br>";
echo "<br>--------------------------------------------------------------------<br>";
echo "La evidencia seleccionada es: " . $evidencia_otro;
echo "<br>Justificación de Otros: $justification_otro<br>";
echo "<br>--------------------------------------------------------------------<br>";

echo "Materiales: $Materiales<br>";
echo "Medicos: $Medicos<br>";
echo "Equipos: $Equipos<br>";
echo "Otro: $Otro<br>";


echo "<br>--------------------------------------------------------------------<br>";

echo "Justificacion Materiales: $justification_materiales<br>";
echo "Justificacion Medicos: $justification_medicos<br>";
echo "Justificacion Equipos: $justification_equipos<br>";
echo "Justificacion Otros: $justification_otro<br>";


echo "<br>--------------------------------------------------------------------<br>";


echo "<br>";

/// Si todos los elementos del arreglo son 1, entonces todos los campos estan llenos
if (array_sum($Resultados_Arreglo) == 4) {
    echo "<br>Todos los campos estan llenos";
    // cUANDO TODOS LOS ELEMENTOS SON 1, se actualiza el estado del viatico a "Completado"
    $sql_query = "UPDATE viaticos SET Estado = 'Completado' WHERE Id = $id_viatico";
    if ($conn->query($sql_query) === TRUE) {
        echo "<br>Registro actualizado correctamente";
        // Actualizar las evidencias a "Aceptado"
        $sql_query = "UPDATE evidencias SET Estado = 'Aceptado' WHERE Id_Relacionado = $id_viatico AND Tipo = 'Evidencia'";
        if ($conn->query($sql_query) === TRUE) {
            echo "<br>Registro actualizado correctamente";
        } else {
            echo "Error: " . $sql_query . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql_query . "<br>" . $conn->error;
    }
    echo "<br>--------------------------------------------------------------------<br>";

} 

if ($Resultados_Arreglo[0]==2){
    echo "<br>El campo Materiales esta Rechazado";
    /// Cuando uno de los conceptos es rechazado (Es == 2) Se toma el texto de lo que se tiene en la variable Descripción
    /// Y se le concatena al texto "(Rechazado: + $justification_materiales)"

    // Obtener la descripción actual 
    $sql_query = "SELECT Descripcion FROM evidencias WHERE Id_Relacionado = $id_viatico AND Tipo = 'Evidencia' AND Concepto = 'Materiales'";
    $result = $conn->query($sql_query);
    $descripcion = "";
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $descripcion = $row['Descripcion'];
            echo "<br>Descripcion Actual: $descripcion <br>";
        }
    } else {
        echo "0 results";
    }
    
    $descripcion = $descripcion . " (Rechazado: $justification_materiales)";
    echo "<br>Descripcion Nueva: $descripcion";

    /// Se actualiza la descripción
    $sql_query = "UPDATE evidencias SET Descripcion = '$descripcion', Estado = 'Rechazado' WHERE Id = $id_viatico AND Tipo = 'Evidencia' AND Concepto = 'MATERIALES'";
    if ($conn->query($sql_query) === TRUE) {
        echo "<br>Registro actualizado correctamente";
    } else {
        echo "Error: " . $sql_query . "<br>" . $conn->error;
    }
    
    echo "<br>--------------------------------------------------------------------<br>";
    
} 

if($Resultados_Arreglo[1]==2){
    echo "<br>El campo Medicos esta Rechazado";
    // Obtener la descripción actual
    $sql_query = "SELECT Descripcion FROM evidencias WHERE Id_Relacionado = $id_viatico AND Tipo = 'Evidencia' AND Concepto = 'GASTOS MÉDICOS'";
    $result = $conn->query($sql_query);
    $descripcion = "";
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $descripcion = $row['Descripcion'];
            echo "<br>Descripcion Actual: $descripcion";
        }
    } else {
        echo "0 results";
    }

    $descripcion = $descripcion . " (Rechazado: $justification_medicos)";
    echo "<br>Descripcion Nueva: $descripcion";

    // Actualizar la descripción

    $sql_query = "UPDATE evidencias SET Descripcion = '$descripcion', Estado = 'Rechazado' WHERE Id_Relacionado = $id_viatico AND Tipo = 'Evidencia' AND Concepto = 'GASTOS MÉDICOS'";
    if ($conn->query($sql_query) === TRUE) {
        echo "<br>Registro actualizado correctamente";
    } else {
        echo "Error: " . $sql_query . "<br>" . $conn->error;
    }

    echo "<br>--------------------------------------------------------------------<br>";
    
} 
if($Resultados_Arreglo[2]==2){
    echo "<br>El campo Equipos esta Rechazado";
    // Obtener la descripción actual
    $sql_query = "SELECT Descripcion FROM evidencias WHERE Id_Relacionado = $id_viatico AND Tipo = 'Evidencia' AND Concepto = 'EQUIPOS'";
    $result = $conn->query($sql_query);
    $descripcion = "";
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $descripcion = $row['Descripcion'];
            echo "<br>Descripcion Actual: $descripcion";
        }
    } else {
        echo "0 results";
    }

    $descripcion = $descripcion . " (Rechazado: $justification_equipos)";
    echo "<br>Descripcion Nueva: $descripcion";

    // Actualizar la descripción

    $sql_query = "UPDATE evidencias SET Descripcion = '$descripcion', Estado = 'Rechazado' WHERE Id_Relacionado = $id_viatico AND Tipo = 'Evidencia' AND Concepto = 'EQUIPOS'";
    if ($conn->query($sql_query) === TRUE) {
        echo "<br>Registro actualizado correctamente";
    } else {
        echo "Error: " . $sql_query . "<br>" . $conn->error;
    }

    echo "<br>--------------------------------------------------------------------<br>";
} 
if($Resultados_Arreglo[3]==2){
    echo "<br>El campo Otro esta Rechazado";

    // Obtener la descripción actual
    $sql_query = "SELECT Descripcion FROM evidencias WHERE Id_Relacionado = $id_viatico AND Tipo = 'Evidencia' AND Concepto = 'OTROS'";
    $result = $conn->query($sql_query);
    $descripcion = "";
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $descripcion = $row['Descripcion'];
            echo "<br>Descripcion Actual: $descripcion";
        }
    } else {
        echo "0 results";
    }

    $descripcion = $descripcion . " (Rechazado: $justification_otro)";

    echo "<br>Descripcion Nueva: $descripcion";

    // Actualizar la descripción

    $sql_query = "UPDATE evidencias SET Descripcion = '$descripcion', Estado = 'Rechazado' WHERE Id_Relacionado = $id_viatico AND Tipo = 'Evidencia' AND Concepto = 'OTROS'";
    if ($conn->query($sql_query) === TRUE) {
        echo "<br>Registro actualizado correctamente";
    } else {
        echo "Error: " . $sql_query . "<br>" . $conn->error;
    }
    echo "<br>--------------------------------------------------------------------<br>";

}

header("Location: ListadoViaticos.php");

?>
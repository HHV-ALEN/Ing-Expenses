<?php
$Tipo_Usuario = $_SESSION['Position'];


switch ($row['Estado']) {
    case 'Abierto':
        $Color_Row = "table-secondary";
        break;
    case 'Aceptado':
        $Color_Row = "table-primary";
        break;
    case 'Rechazado':
        $Color_Row = "table-danger";
        break;
    case 'Verificación':
        $Color_Row = "table-info";
        break;
    case 'Revisión':
        $Color_Row = "table-warning";
        break;
    case 'Prórroga':
            $Color_Row = "table-warning";
            break;
    case 'Completado':
        $Color_Row = "table-success";
        break;
    case 'EnCurso':
        $Color_Row = "table-light";
        break;
    case 'Cerrado':
        $Color_Row = "table-danger";
        break;
    case 'Segunda Revisión':
        $Color_Row = "table-warning";
        break;
}
echo '';
echo "<tr  class='" . $Color_Row . "'>";
echo "<td class='text-center'>" . $row['Id'] . "</td>";
echo "<td class='text-center'>" . $row['Solicitante'] . "</td>";
echo "<td class='text-center'>" . $row['Fecha_Registro'] . "</td>";
echo "<td class='text-center'>" . $row['Fecha_Salida'] . "</td>";
echo "<td class='text-center'>" . $row['Fecha_Regreso'] . "</td>";
echo "<td class='text-center'>" . $row['Orden_Venta'] . " " . $row['Codigo'] . " " . $row['Nombre_Proyecto'] . "</td>";
echo "<td class='text-center'>" . $row['Estado'] . "</td>";


if ($row['Estado'] == 'Abierto' && $Tipo_Usuario == 'Empleado') {
    echo "<td class='text-center'><a href='detalles.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>";
    echo "<td class='text-center'><a href='../../resources/Back/Viaticos/DeleteViatico.php?id=" . $row['Id'] . "' class='btn btn-danger'>Eliminar</a></td>";
    echo "<td class='text-center'><a href='editar.php?id=" . $row['Id'] . "' class='btn btn-warning'>Editar</a></td>";
    echo "<td></td>";
} elseif ($row['Estado'] == 'EnCurso' && $Tipo_Usuario == 'Empleado') {
    echo "<td class='text-center'><a href='detalles.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>";
    echo "<td class='text-center'><a href='SubirEvidencias.php?id=" . $row['Id'] . "' class='btn btn-success'>Evidenciar</a></td>";
    echo "<td></td>";
    echo "<td></td>";
} elseif ($row['Estado'] == 'Completado' && $Tipo_Usuario == 'Empleado') {
    echo "<td></td>";
    echo "<td class='text-center'><a href='detalles.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>";
    echo "<td class='text-center'><a href='SubirEvidencias.php?id=" . $row['Id'] . "' class='btn btn-success'>Evidencias</a></td>";
    echo "<td></td>";
} elseif ($row['Estado'] == 'Cerrado' && $Tipo_Usuario == 'Empleado') {
    echo "<td class='text-center'><a href='detalles.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td></td>";
} elseif ($row['Estado'] == 'Rechazado' && $Tipo_Usuario == 'Empleado') {
    echo "<td></td>";
    echo "<td class='text-center'><a href='detalles.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>";
    echo "<td></td>";
    echo "<td></td>";
} elseif ($row['Estado'] == 'Aceptado' && $Tipo_Usuario == 'Empleado') {
    echo "<td></td>";
    echo "<td class='text-center'><a href='detalles.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>";
    echo "<td></td>";
}
elseif($row['Estado'] == 'Verificación' && $Tipo_Usuario == 'Empleado'){
    echo "<td></td>";
    echo "<td class='text-center'><a href='detalles.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>";
    echo "<td class='text-center'><a href='SubirEvidencias.php?id=" . $row['Id'] . "' class='btn btn-success'>Evidencias</a></td>";
    echo "<td></td>";
}
elseif ($row['Estado'] == 'Revisión' && $Tipo_Usuario == 'Empleado') {
    
    echo "<td class='text-center'><a href='detalles.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td></td>";
} 
elseif ($row['Estado'] == 'Fuera de Rango' && $Tipo_Usuario == 'Empleado') {
    echo "<td></td>";
    echo "<td class='text-center'><a href='detalles.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>";
    echo "<td></td>";
}

elseif ($row['Estado'] == "Prórroga" && $Tipo_Usuario == 'Empleado') {
    echo "<td></td>";
    echo "<td class='text-center'><a href='/src/Viaticos/detalles.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>";
    echo "<td class='text-center'><a href='/src/Viaticos/SubirEvidencias.php?id=" . $row['Id'] . "' class='btn btn-success'>Evidencias</a></td>";
    echo "<td></td>";
}
elseif ($row['Estado'] == "Segunda Revisión" && $Tipo_Usuario == 'Empleado'){
    
    echo "<td class='text-center'><a href='/src/Viaticos/detalles.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>";
    echo "<td class='text-center'><a href='/src/Viaticos/SubirEvidencias.php?id=" . $row['Id'] . "' class='btn btn-success'>Evidencias</a></td>";
    echo "<td></td>";
    echo "<td></td>";
}

/// ---------- Control && Gerente  ------------------
elseif ($row['Estado'] == 'Segunda Revisión' && ($Tipo_Usuario == 'Control' || $Tipo_Usuario == 'Gerente')) {
    echo "
    <td class='text-center'><a href='/src/Viaticos/detalles.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>
    <td class='text-center'><a href='/src/Viaticos/SubirEvidencias.php?id=" . $row['Id'] . "' class='btn btn-success'>Evidencias</a></td>
    <td></td>
    ";
}

elseif ($row['Estado'] == 'Abierto' && ($Tipo_Usuario == 'Control' || $Tipo_Usuario == 'Gerente')) {
    echo "
    <td></td>
    <td class='text-center'><a href='/src/Viaticos/detalles.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>
    
    <td></td>";
} elseif ($row['Estado'] == 'EnCurso' && ($Tipo_Usuario == 'Control' || $Tipo_Usuario == 'Gerente')) {
    echo "
    <td class='text-center'><a href='/src/Viaticos/detalles.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>
    <td class='text-center'><a href='/src/Viaticos/SubirEvidencias.php?id=" . $row['Id'] . "' class='btn btn-success'>Evidencias</a></td>
    <td></td>
    ";
} elseif ($row['Estado'] == 'Completado' && ($Tipo_Usuario == 'Control' || $Tipo_Usuario == 'Gerente')) {
    /// Detalles | Ver evidencias
    echo "
    <td class='text-center'><a href='/src/Viaticos/detalles.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>
    <td class='text-center'><a href='/src/Viaticos/SubirEvidencias.php?id=" . $row['Id'] . "' class='btn btn-success'>Evidencias</a></td>
    <td></td>
    ";
} elseif ($row['Estado'] == 'Cerrado' && ($Tipo_Usuario == 'Control' || $Tipo_Usuario == 'Gerente')) {
    /// Detalles | Ver evidencias
    echo " 
    <td class='text-center'><a href='/src/Viaticos/detalles.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>
    <td></td>
    <td></td>
    ";
} elseif ($row['Estado'] == 'Aceptado' && ($Tipo_Usuario == 'Control' || $Tipo_Usuario == 'Gerente')) {
    /// Detalles | Ver evidencias
    echo "
    <td></td>
    <td class='text-center'><a href='/src/Viaticos/detalles.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>
    <td></td>
    ";
} elseif ($row['Estado'] == 'Rechazado' && ($Tipo_Usuario == 'Control' || $Tipo_Usuario == 'Gerente')) {
    /// Detalles | Ver evidencias
    echo "
    <td></td>
    <td class='text-center'><a href='/src/Viaticos/detalles.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>
    <td></td>
    ";
} elseif ($row['Estado'] == 'Verificación' && ($Tipo_Usuario == 'Control' || $Tipo_Usuario == 'Gerente')) {
    /// Detalles | Ver evidencias
    echo "
    
    <td class='text-center'><a href='/src/Viaticos/detalles.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>
    <td class='text-center'><a href='/src/Viaticos/SubirEvidencias.php?id=" . $row['Id'] . "' class='btn btn-success'>Evidencias</a></td>
    <td></td>
    ";
} elseif ($row['Estado'] == 'Revisión' && ($Tipo_Usuario == 'Control' || $Tipo_Usuario == 'Gerente')) {
    /// Detalles | Ver evidencias
    echo "
    
    <td class='text-center'><a href='/src/Viaticos/detalles.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>
    <td class='text-center'><a href='/src/Viaticos/SubirEvidencias.php?id=" . $row['Id'] . "' class='btn btn-success'>Evidencias</a></td>
    <td></td>
    ";
} elseif ($row['Estado'] == 'Fuera de Rango' && ($Tipo_Usuario == 'Control' || $Tipo_Usuario == 'Gerente')) {
    echo "
    <td></td>
    <td class='text-center'><a href='../src/Viaticos/detalles.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>
    <td></td>
    ";
}
elseif ($row['Estado'] == "Prórroga"  && ($Tipo_Usuario == 'Control' || $Tipo_Usuario == 'Gerente')) {
    
    echo "<td class='text-center'><a href='/src/Viaticos/detalles.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>";
    echo "<td class='text-center'><a href='/src/Viaticos/SubirEvidencias.php?id=" . $row['Id'] . "' class='btn btn-success'>Evidencias</a></td>";
    echo "<td></td>";
}
echo "</tr>";
?>
<?php
$Tipo_Usuario = $_SESSION['Position'];
$Nombre_Usuario = $_SESSION['Name'];

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
    case 'Revision':
        $Color_Row = "table-warning";
        break;
    case 'Completado':
        $Color_Row = "table-success";
        break;
    case 'En Curso':
        $Color_Row = "table-light";
        break;
    case 'Cerrado':
        $Color_Row = "table-danger";
        break;
}
echo "<tr  class='" . $Color_Row . "'>";
echo "<td class='text-center'>" . $row['Id'] . "</td>";
echo "<td class='text-center'>" . $row['Solicitante'] . "</td>";
echo "<td class='text-center'>" . $row['Fecha'] . "</td>";
echo "<td class='text-center'>" . $row['Concepto'] . "</td>";
echo "<td class='text-center'>" . $row['Monto'] . "</td>";
echo "<td class='text-center'>" . $row['Destino'] . "</td>";
echo "<td class='text-center'>" . $row['Descripcion'] . "</td>";
echo "<td class='text-center'>" . $row['Estado'] . "</td>";
echo "<td class='text-center'><a href='/src/Reembolsos/ReembolsoAnidado.php?id=" . $row['Id'] . "' class='btn btn-info'>Detalles</a></td>";


if ($row['Estado'] == 'Abierto' && $row['Solicitante'] == $Nombre_Usuario) {
    echo "<td class='text-center'><a href='editarReembolso.php?id=" . $row['Id'] . "' class='btn btn-warning'>Editar</a></td>";
    echo "<td></td>";
}

if ($row['Estado'] == 'Aceptado' && $Tipo_Usuario == 'Empleado') {
    echo "<td></td>";
    echo "<td></td>";
}
if ($row['Estado'] == 'Rechazado' && $Tipo_Usuario == 'Empleado') {
    echo "<td></td>";
    echo "<td></td>";
}


echo "</tr>";


?>
<?php
session_start();
include "../../../config/db.php";

$Id_Maestro = $_GET['Id_Maestro'];
$Id_Relacionado = $_GET['Id'];
$Tipo = $_GET['Tipo'];
$Respuesta = $_GET['Respuesta'];
$Position = $_SESSION['Position'];

echo "<br> Id_Relacionado: " . $Id_Relacionado;
echo "<br> Tipo: " . $Tipo;
echo "<br> Respuesta: " . $Respuesta;
echo "<br> Position: " . $Position;

echo "<br>";

if ($Respuesta == 'Completar') {
    /// Actualizar el estado a completado y todos sus reembolsos anidados a completado tambien
    $SQL = "UPDATE reembolsos SET Estado = 'Completado' WHERE Id = $Id_Relacionado";
    $result = $conn->query($SQL);
    if ($result) {
        echo "Reembolso Completado";
        /// Completar la verificación del reembolso 
        $SQL = "UPDATE verificacion SET Aceptado_Control = 'Completado', Aceptado_Gerente = 'Completado' WHERE Id_Relacionado = $Id_Relacionado AND Tipo = 'Reembolso'";
        $result = $conn->query($SQL);
        if ($result) {
            echo "Verificación Completada";
        } else {
            echo "Error al completar la verificación";
        }
    } else {
        echo "Error al completar el reembolso";
    }

    $SQL = "UPDATE reembolsos_anidados SET Estado = 'Completado' WHERE Id = $Id_Relacionado";
    $result = $conn->query($SQL);
    if ($result) {
        echo "Reembolso Anidado Completado";
        /// Completar la verificación del reembolso anidado
        $SQL = "UPDATE verificacion SET Aceptado_Control = 'Completado', Aceptado_Gerente = 'Completado' WHERE Id_Relacionado = $Id_Relacionado AND Tipo = 'Reembolso_Anidado'";
        $result = $conn->query($SQL);
        if ($result) {
            echo "Verificación Completada";
            header("Location: ../../../../../../src/Reembolsos/Superior/ListadoReembolsos.php");
        } else {
            echo "Error al completar la verificación";
        }

    } else {
        echo "Error al completar el reembolso anidado";
    }

    header("Location: ../../../../../../src/Reembolsos/Superior/ListadoReembolsos.php");
} else {

    if ($Position == 'Control' && $Tipo == 'Reembolso') {
        echo "Usuario Control - Reembolso <br>";
        if ($Respuesta == 'Aceptado') {
            $sql = "UPDATE verificacion SET Aceptado_Control = 'Aceptado' WHERE Id_Relacionado = $Id_Relacionado AND Tipo = '$Tipo'";
            $result = $conn->query($sql);
            if ($result) {
                echo "Reembolso Aceptado";

            } else {
                echo "Error al aceptar el reembolso";
            }
        } else {
            $sql = "UPDATE verificacion SET Aceptado_Control = 'Rechazado' WHERE Id_Relacionado = $Id_Relacionado AND Tipo = '$Tipo'";
            $result = $conn->query($sql);
            if ($result) {
                echo "Reembolso Rechazado";
            } else {
                echo "Error al rechazar el reembolso";
            }
        }
    }

    if ($Position == 'Gerente' && $Tipo == 'Reembolso') {
        echo "Usuario Gerente - Reembolso <br>";
        if ($Respuesta == 'Aceptado') {
            $sql = "UPDATE verificacion SET Aceptado_Gerente = 'Aceptado' WHERE Id_Relacionado = $Id_Relacionado AND Tipo = '$Tipo'";
            $result = $conn->query($sql);
            if ($result) {
                echo "Reembolso Aceptado";
            } else {
                echo "Error al aceptar el reembolso";
            }
        } else {
            $sql = "UPDATE verificacion SET Aceptado_Gerente = 'Rechazado' WHERE Id_Relacionado = $Id_Relacionado AND Tipo = '$Tipo'";
            $result = $conn->query($sql);
            if ($result) {
                echo "Reembolso Rechazado";
            } else {
                echo "Error al rechazar el reembolso";
            }
        }
    }

    if ($Position == 'Control' && $Tipo == 'Reembolso_Anidado') {
        echo "Usuario Control - Reembolso Anidado <br>";
        if ($Respuesta == 'Aceptado') {
            $sql = "UPDATE verificacion SET Aceptado_Control = 'Aceptado' WHERE Id_Relacionado = $Id_Relacionado AND Tipo = '$Tipo'";
            $result = $conn->query($sql);
            if ($result) {
                echo "Reembolso Anidado Aceptado";
            } else {
                echo "Error al aceptar el reembolso anidado";
            }
        } else {
            $sql = "UPDATE verificacion SET Aceptado_Control = 'Rechazado' WHERE Id_Relacionado = $Id_Relacionado AND Tipo = '$Tipo'";
            $result = $conn->query($sql);
            if ($result) {
                echo "Reembolso Anidado Rechazado";
            } else {
                echo "Error al rechazar el reembolso anidado";
            }
        }
    }

    if ($Position == 'Gerente' && $Tipo == 'Reembolso_Anidado') {
        echo "Usuario Gerente - Reembolso Anidado <br>";
        if ($Respuesta == 'Aceptado') {
            $sql = "UPDATE verificacion SET Aceptado_Gerente = 'Aceptado' WHERE Id_Relacionado = $Id_Relacionado AND Tipo = '$Tipo'";
            $result = $conn->query($sql);
            if ($result) {
                echo "Reembolso Anidado Aceptado";
            } else {
                echo "Error al aceptar el reembolso anidado";
            }
        } else {
            $sql = "UPDATE verificacion SET Aceptado_Gerente = 'Rechazado' WHERE Id_Relacionado = $Id_Relacionado AND Tipo = '$Tipo'";
            $result = $conn->query($sql);
            if ($result) {
                echo "Reembolso Anidado Rechazado";
            } else {
                echo "Error al rechazar el reembolso anidado";
            }
        }
    }

    $BD = "";
    /// Verificar si el reembolso con el id relacionado ya fue aceptado por control y gerente
    $sql = "SELECT * FROM verificacion WHERE Id_Relacionado = $Id_Relacionado AND Tipo = '$Tipo'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    if ($row['Aceptado_Control'] == 'Aceptado') {
        if ($Tipo == 'Reembolso' && $Respuesta == 'Aceptado') {
            $SQL = "UPDATE reembolsos SET Estado = 'Aceptado' WHERE Id = $Id_Relacionado";
        } else {
            $SQL = "UPDATE reembolsos_anidados SET Estado = 'Aceptado' WHERE Id = $Id_Relacionado";

        }
        $result = $conn->query($SQL);
        if ($result) {
            echo "<br> $Tipo - Aceptado por Control y Gerente";
            echo "<br> Id_Relacionado: " . $Id_Relacionado;
            echo "<br> Tipo: " . $Tipo;
            echo "<br> Respuesta: " . $Respuesta;
            echo "<br> ************************************************************************+++";
            //header ("Location: /resources/Back/Mail/reembolsoAceptado.php?Id=$Id_Relacionado");


        } else {
            echo "Error al verificar si el reembolso fue aceptado por control y gerente";
        }
    }

    if ($Tipo == 'Reembolso' || $Respuesta == 'Rechazado') {
        $SQL = "UPDATE reembolsos SET Estado = 'Rechazado' WHERE Id = $Id_Relacionado";
    } else {
        $SQL = "UPDATE reembolsos_anidados SET Estado = 'Rechazado' WHERE Id = $Id_Relacionado";
    }

    if ($row['Aceptado_Control'] == 'Rechazado') {
        $result = $conn->query($SQL);
        if ($result) {
            echo "<br> $Tipo - Rechazado por Control o Gerente";
            //header ("Location: /resources/Back/Mail/reembolsoRechazado.php?Id=$Id_Relacionado");
        } else {
            echo "Error al verificar si el reembolso fue rechazado por control o gerente";
        }
    }

    echo "Este es el Id_Relacionado: " . $Id_Relacionado;
    echo "El ID correspondiente es: " . $Id_Maestro;


    if ($Id_Maestro == NULL) {
        $Id_Maestro = $Id_Relacionado;
    }

    header("Location: /src/Reembolsos/ReembolsoAnidado.php?id=$Id_Maestro");
}



//header("Location: ../../../../../../src/Reembolsos/ReembolsoAnidado.php?Id=$Id_Relacionado");

?>
<?php
require ('../../config/db.php');
session_start();

echo "Aqui entra cuando hay un monto extra<br>";
$id_viatico = $_GET['id_viatico'];
$id_usuario = $_GET['id_usuario'];
$id_Gerente = $_GET['id_gerente'];
$resultRestaGasolina = $_GET['resultRestaGasolina'];
$resultRestaHospedaje = $_GET['resultRestaHospedaje'];
$resultRestaCasetas = $_GET['resultRestaCasetas'];
$resultRestaAlimentos = $_GET['resultRestaAlimentos'];

echo "Id viatico: $id_viatico <br>";
echo "Resta Gasolina: $resultRestaGasolina <br>";
echo "Resta Hospedaje: $resultRestaHospedaje <br>";
echo "Resta Casetas: $resultRestaCasetas <br>";
echo "Resta Alimentos: $resultRestaAlimentos <br>";

#------------------------------------------------------------ Función para procesar los conceptos ------------------------------------------------------------
function procesarConcepto($conn, $id_viatico, $id_usuario, $id_Gerente, $resultResta, $concepto)
{
    echo "Concepto: $concepto <br>";
    echo "Resultado de la resta: $resultResta <br>";
    echo "Id viatico: $id_viatico <br>";
    echo "Id usuario: $id_usuario <br>";
    echo "Id Gerente: $id_Gerente <br>";
    


}

procesarConcepto($conn, $id_viatico, $id_usuario, $id_Gerente, $resultRestaAlimentos, 'Alimentos');

    /*
    if ($resultResta > 0) {
        // Traer imágenes de la tabla "imagen" para insertar en la tabla reembolso
        $imagen_Query = "SELECT * FROM imagen WHERE Id_Viatico = $id_viatico AND Concepto = '$concepto'";
        $imagen_Result = mysqli_query($conn, $imagen_Query);
        $imagenes = array();
        while ($imagen_Row = mysqli_fetch_assoc($imagen_Result)) {
            $imagenes[] = $imagen_Row['Nombre'];
            echo "Nombre de la imagen: " . $imagen_Row['Nombre'] . "<br>";
        }

        // Verificar si el reembolso ya existe
        $reembolso_Query = "SELECT * FROM reembolso WHERE Id_Viatico = $id_viatico AND Concepto = '$concepto'";
        

        // Crear una sola solicitud de reembolso con el monto acumulado
        if (count($imagenes) > 0) {
            $imagen = $imagenes[0]; // Usar la primera imagen para la solicitud de reembolso
            echo "Imagen a insertar: $imagen <br>";
            $insertarImagen = "INSERT INTO reembolso (Monto, Descripcion, Imagen, Id_Viatico, Id_Usuario, Id_Gerente, Estado, Concepto) 
            VALUES ($resultResta, 'Reembolso', '$imagen', $id_viatico, $id_usuario, $id_Gerente, 'Abierto', '$concepto')";
            $insertarImagen_Result = mysqli_query($conn, $insertarImagen);

            // Obtener el último registro de la tabla reembolso
            $LastReembolso_Query = "SELECT Id FROM reembolso ORDER BY Id DESC LIMIT 1";
            $LastReembolso_Result = mysqli_query($conn, $LastReembolso_Query);
            $LastReembolso = mysqli_fetch_assoc($LastReembolso_Result);
            $id_reembolso = $LastReembolso['Id'];
            $NombreImagenInsertada = $LastReembolso['Imagen'];

            echo "Nombre de la imagen insertada: $NombreImagenInsertada <br>";

            // Crear registro de verificación para aceptación de reembolso
            $verificacion = "INSERT INTO verificacion (Id_Viatico, Id_Reembolso, Solicitante) VALUES ($id_viatico, '$id_reembolso', '$id_usuario')";
            $verificacion_Result = mysqli_query($conn, $verificacion);

            // Asignar todas las imágenes a la solicitud de reembolso
            foreach ($imagenes as $img) {
                $actualizarImagen = "UPDATE imagen SET Id_Reembolso = $id_reembolso WHERE Nombre = '$img'";
                $actualizarImagen_Result = mysqli_query($conn, $actualizarImagen);
            }
        }
    }

}

# Procesar cada concepto
procesarConcepto($conn, $id_viatico, $id_usuario, $id_Gerente, $resultRestaGasolina, 'Gasolina');
procesarConcepto($conn, $id_viatico, $id_usuario, $id_Gerente, $resultRestaHospedaje, 'Hospedaje');
procesarConcepto($conn, $id_viatico, $id_usuario, $id_Gerente, $resultRestaCasetas, 'Casetas');
procesarConcepto($conn, $id_viatico, $id_usuario, $id_Gerente, $resultRestaAlimentos, 'Alimentos');

header("Location: ../../../../../src/Viaticos/misViaticos.php");

?>*/
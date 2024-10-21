<?php
require ('../../config/db.php');
session_start();

$Folio_Original = $_GET['Origen'];
$id_reembolso = $_GET['id_reembolso'];
$Respuesta = $_GET['Respuesta'];
$Puesto = $_SESSION['Position'];
$Puesto = $_SESSION['Position'];
$verificador = $_SESSION['Name'];
$Id_Usuario = $_GET['Id_Usuario'];
$Id_Gerente = $_GET['Id_Gerente'];

echo "------------------------------------ <br>";
echo "Id Reembolso: $id_reembolso <br>";
echo "Id Usuario: $Id_Usuario <br>";
echo "Id Gerente: $Id_Gerente <br>";
echo "Puesto: $Puesto <br>";
echo "Respuesta: $Respuesta <br>";
echo "Id Usuario: $Id_Usuario <br>";
echo "Id Gerente: $Id_Gerente <br>";
echo "------------------------------------ <br>";

/// Si el reembolso tiene un 0 en Id_Viatico, entonces es un reembolso anidado
$Reembolso_Check = "SELECT * FROM reembolso WHERE Id = $id_reembolso";

/// Ejecuta la consulta y si no encuentra registros, entonces es un reembolso anidado
$Result_Check = $conn->query($Reembolso_Check);
    if ($Result_Check->num_rows == 0) {
        echo "Reembolso Anidado <br>";

        // Obtener información de ese reembolso anidado
        $ReembolsoAnidado_Query = "SELECT * FROM reembolsos_anidados WHERE id = $id_reembolso";
        $ReembolsoAnidado_Result = mysqli_query($conn, $ReembolsoAnidado_Query);
        $ReembolsoAnidado_Row = mysqli_fetch_assoc($ReembolsoAnidado_Result);
        $id_usuario = $ReembolsoAnidado_Row['Id_Usuario'];
        $Id_Gerente = $ReembolsoAnidado_Row['Id_Gerente'];
        $Estado = $ReembolsoAnidado_Row['Estado'];
        $Id_Del_Reembolso_Anidado = $ReembolsoAnidado_Row['Id'];

        echo "------------------------------------ <br>";
        echo "Id Usuario: $id_usuario <br>";
        echo "Id Gerente: $Id_Gerente <br>";
        echo "Estado: $Estado <br>";
        echo "Id Reembolso Anidado: $Id_Del_Reembolso_Anidado <br>";
        echo "------------------------------------ <br>";

        /// Cuando el reembolso es aceptado
        if ($Respuesta == 'Aceptado') {

            if ($Puesto == 'Control') {

                // Verificar si el Id_Reembolso ya está registrado
                $sql_check = "SELECT * FROM verificacion WHERE Id_Reembolso = '$id_reembolso'";
                $result_check = $conn->query($sql_check);

                if ($result_check->num_rows == 0) {
                    $sql_insert = "INSERT INTO verificacion (Aceptado_Control, Id_Reembolso, Verificador, Solicitante)
                        VALUES ('$Respuesta', '$id_reembolso', '$verificador', '$id_usuario')";

                    if ($conn->query($sql_insert) === TRUE) {
                        echo "(Anidado) Registro insertado correctamente en la tabla verificacion.";
                        
                        //header("Location: ../../../../../src/Control/detallesReembolso.php?id_reembolso=$Folio_Original");
                    } else {
                        echo "Error al insertar el registro: " . $conn->error;
                    }

                } else {
                    $sql_aceptar = "UPDATE verificacion SET Aceptado_Control 
                    = '$Respuesta' WHERE Id_Reembolso = '$id_reembolso'";
                    if ($conn->query($sql_aceptar) === TRUE) {
                        echo "(Anidado) Registro actualizado correctamente en la tabla verificacion.";
                        // -> Cambiar el estado del reembolso a aceptado
                        //header("Location: ../../../../../src/Control/detallesReembolso.php?id_reembolso=$Folio_Original");
                    } else {
                        echo "Error al actualizar el registro: " . $conn->error;
                    }
                }
            } /// Fin del Control

            /// Cuando el reembolso es rechazado
        } elseif($Respuesta == 'Rechazado'){
            if($Puesto == 'Control'){
                // Verificar si el Id_Reembolso ya está registrado
                $sql_check = "SELECT * FROM verificacion WHERE Id_Reembolso = '$id_reembolso'";
                $result_check = $conn->query($sql_check);

                if ($result_check->num_rows == 0) {

                    $sql_insert = "INSERT INTO verificacion (Aceptado_Control, Id_Reembolso, Verificador, Solicitante)
                        VALUES ('$Respuesta', '$id_reembolso', '$verificador', '$id_usuario')";

                    if ($conn->query($sql_insert) === TRUE) {
                        /// Cambiar el estado del reembolso a rechazado
                        $sql_rechazar = "UPDATE reembolsos_anidados SET Estado = 'Rechazado' WHERE Id = '$Id_Del_Reembolso_Anidado'";
                        if ($conn->query($sql_rechazar) === TRUE) {
                            echo "(Anidado Rechazado) Registro insertado correctamente en la tabla verificacion.";
                            //header("Location: ../../../../../src/Control/detallesReembolso.php?id_reembolso=$Folio_Original");
                        } else {
                            echo "Error al insertar el registro: " . $conn->error;
                        }
                    } else {
                        echo "Error al insertar el registro: " . $conn->error;
                    }
                } else {
                    $rechazar_Verificacion = "UPDATE verificacion SET Aceptado_Control 
                    = '$Respuesta' WHERE Id_Reembolso = '$id_reembolso'";
                    if ($conn->query($rechazar_Verificacion) === TRUE) {
                        /// Cambiar el estado del reembolso a rechazado
                        $sql_rechazar = "UPDATE reembolsos_anidados SET Estado = 'Rechazado' WHERE Id = '$Id_Del_Reembolso_Anidado'";
                        if ($conn->query($sql_rechazar) === TRUE) {
                            echo "(Anidado Rechazado) Registro actualizado correctamente en la tabla verificacion.";
                            //header("Location: ../../../../../src/Control/detallesReembolso.php?id_reembolso=$Folio_Original");
                        } else {
                            echo "Error al actualizar el registro: " . $conn->error;
                        }
                    } else {
                        echo "Error al actualizar el registro: " . $conn->error;
                    }
                }
            } 
        }

    } else { /// Reembolso Normal -> No es un reembolso anidado
        // Obtener información del Reembolso
        echo "Este es el ID QUE ESTA TOMANDO: $id_reembolso <br>";
        $viatico_Query = "SELECT * FROM reembolso WHERE Id = $id_reembolso";
        $viatico_Result = mysqli_query($conn, $viatico_Query);
        $viatico_Row = mysqli_fetch_assoc($viatico_Result);
        $id_usuario = $viatico_Row['Id_Usuario'];
        $Id_Gerente = $viatico_Row['Id_Gerente'];
        $Estado = $viatico_Row['Estado'];
        $Id_Viatico = $viatico_Row['Id_Viatico'];


        echo "------------------------------------ <br>";
        echo "Id Usuario: $id_usuario <br>";
        echo "Id Gerente: $Id_Gerente <br>";
        echo "Estado: $Estado <br>";
        echo "------------------------------------ <br>";

        /// Cuando el reembolso es aceptado
        if($Respuesta == 'Aceptado'){
            if ($Puesto == 'Control') {
                // Verificar si el Id_Reembolso ya está registrado
                $sql_check = "SELECT * FROM verificacion WHERE Id_Reembolso = '$id_reembolso'";
                $result_check = $conn->query($sql_check);

                if ($result_check->num_rows == 0) {

                    $sql_insert = "INSERT INTO verificacion (Aceptado_Control, Id_Reembolso, Verificador, Solicitante)
                        VALUES ('$Respuesta', '$id_reembolso', '$verificador', '$id_usuario')";

                    if ($conn->query($sql_insert) === TRUE) {
                        echo "Registro insertado correctamente en la tabla verificacion.";
                        //header("Location: ../../../../../src/Control/detallesReembolso.php?id_reembolso=$Folio_Original");
                    } else {
                        echo "Error al insertar el registro: " . $conn->error;
                    }
                } else {
                    $sql_aceptar = "UPDATE verificacion SET Aceptado_Control 
                    = '$Respuesta' WHERE Id_Reembolso = '$id_reembolso'";
                    if ($conn->query($sql_aceptar) === TRUE) {
                        echo "(Aceptado Control)Registro actualizado correctamente en la tabla verificacion.";
                        /// Actualizar estado del reembolso
                        //header("Location: ../../../../../src/Control/detallesReembolso.php?id_reembolso=$Folio_Original");
                    } else {
                        echo "Error al actualizar el registro: " . $conn->error;
                    }
                }
            }

            if ($Puesto == 'Gerente') {
                // Verificar si el Id_Reembolso ya está registrado
                $sql_check = "SELECT * FROM verificacion WHERE Id_Reembolso = '$id_reembolso'";
                $result_check = $conn->query($sql_check);

                if ($result_check->num_rows == 0) {
                    $sql_insert = "INSERT INTO verificacion (Aceptado_Gerente, Id_Reembolso, Gerente, Verificador, Solicitante)
                                    VALUES ('$Respuesta', '$id_reembolso', '$Id_Gerente', '$verificador', '$id_usuario')";

                    if ($conn->query($sql_insert) === TRUE) {
                        echo "Registro insertado correctamente en la tabla verificacion.";
                        //header("Location: ../../../../../src/Control/detallesReembolso.php?id_reembolso=$Folio_Original");
                    } else {
                        echo "Error al insertar el registro: " . $conn->error;
                    }
                } else {
                    $sql_aceptar = "UPDATE verificacion SET Aceptado_Gerente 
                    = '$Respuesta' WHERE Id_Reembolso = '$id_reembolso'";
                    if ($conn->query($sql_aceptar) === TRUE) {
                        echo "(Aceptado Gerente)Registro actualizado correctamente en la tabla verificacion.";
                        //header("Location: ../../../../../src/Control/detallesReembolso.php?id_reembolso=$Folio_Original");
                    } else {
                        echo "Error al actualizar el registro: " . $conn->error;
                    }
                }
            }
        } else {
            $Respuesta = 'Rechazado';
            if ($Puesto == 'Control') {
                // Verificar si el Id_Reembolso ya está registrado
                $sql_check = "SELECT * FROM verificacion WHERE Id_Reembolso = '$id_reembolso'";
                $result_check = $conn->query($sql_check);

                if ($result_check->num_rows == 0) {

                    $sql_insert = "INSERT INTO verificacion (Aceptado_Control, Id_Reembolso, Verificador, Solicitante)
                        VALUES ('$Respuesta', '$id_reembolso', '$verificador', '$id_usuario')";

                    if ($conn->query($sql_insert) === TRUE) {
                        /// Cambiar el estado del reembolso a rechazado
                        $sql_rechazar = "UPDATE reembolso SET Estado = 'Rechazado' WHERE Id = '$id_reembolso'";
                        if ($conn->query($sql_rechazar) === TRUE) {
                            echo "Registro insertado correctamente en la tabla verificacion.";
                            //header("Location: ../../../../../src/Control/detallesReembolso.php?id_reembolso=$Folio_Original");
                        } else {
                            echo "Error al insertar el registro: " . $conn->error;
                        }
                    } else {
                        echo "Error al insertar el registro: " . $conn->error;
                    }
                } else {
                    $sql_aceptar = "UPDATE verificacion SET Aceptado_Control 
                    = '$Respuesta' WHERE Id_Reembolso = '$id_reembolso'";
                    if ($conn->query($sql_aceptar) === TRUE) {
                        /// Cambiar el estado del reembolso a rechazado
                        $sql_rechazar = "UPDATE reembolso SET Estado = 'Rechazado' WHERE Id = '$id_reembolso'";
                        if ($conn->query($sql_rechazar) === TRUE) {
                            echo "(Rechazado Control)Registro actualizado correctamente en la tabla verificacion.)";
                            //header("Location: ../../../../../src/Control/detallesReembolso.php?id_reembolso=$Folio_Original");
                        } else {
                            echo "Error al actualizar el registro: " . $conn->error;
                        }
                    } else {
                        echo "Error al actualizar el registro: " . $conn->error;
                    }

                }
            } elseif ($Puesto == 'Gerente') { /// pal' gerente
                // Verificar si el Id_Reembolso ya está registrado
                $sql_check = "SELECT * FROM verificacion WHERE Id_Reembolso = '$id_reembolso'";
                $result_check = $conn->query($sql_check);

                if ($result_check->num_rows == 0) {

                    $sql_insert = "INSERT INTO verificacion (Aceptado_Gerente, Id_Reembolso, Gerente, Verificador, Solicitante)
                        VALUES ('$Respuesta', '$id_reembolso', '$Id_Gerente', '$verificador', '$id_usuario')";

                    if ($conn->query($sql_insert) === TRUE) {
                        /// Cambiar el estado del reembolso a rechazado
                        $sql_rechazar = "UPDATE reembolso SET Estado = 'Rechazado' WHERE Id = '$id_reembolso'";
                        if ($conn->query($sql_rechazar) === TRUE) {
                            echo "Registro insertado correctamente en la tabla verificacion.";
                            //header("Location: ../../../../../src/Control/detallesReembolso.php?id_reembolso=$Folio_Original");
                        } else {
                            echo "Error al insertar el registro: " . $conn->error;
                        }
                    } else {
                        echo "Error al insertar el registro: " . $conn->error;
                    }
                } else {
                    $sql_aceptar = "UPDATE verificacion SET Aceptado_Gerente
                    = '$Respuesta' WHERE Id_Reembolso = '$id_reembolso'";
                    if ($conn->query($sql_aceptar) === TRUE) {
                        /// Cambiar el estado del reembolso a rechazado
                        $sql_rechazar = "UPDATE reembolso SET Estado = 'Rechazado' WHERE Id = '$id_reembolso'";
                        if ($conn->query($sql_rechazar) === TRUE) {
                            echo "(Rechazado Gerente) Registro actualizado correctamente en la tabla verificacion.";
                            //header("Location: ../../../../../src/Control/detallesReembolso.php?id_reembolso=$Folio_Original");
                        } else {
                            echo "Error al actualizar el registro: " . $conn->error;
                        }
                    } else {
                        echo "Error al actualizar el registro: " . $conn->error;
                    }
                }
            } 

        }
    }


/// ---------------- El Reembolso desde 0 Solo sera manejado por Control ----------------- 

/// Verificar si el reembolso ya fue aceptado por Control y Gerente para cambiar el estado
$queryVerificador = "SELECT * FROM verificacion WHERE Id_Reembolso = $id_reembolso";
$resultVerificador = $conn->query($queryVerificador);
$rowVerificador = $resultVerificador->fetch_assoc();
$Aceptado_Control = $rowVerificador['Aceptado_Control'];
$Aceptado_Gerente = $rowVerificador['Aceptado_Gerente'];

if ($Aceptado_Control == 'Aceptado' && $Aceptado_Gerente == 'Aceptado') {

    $queryUpdate = "UPDATE reembolso SET Estado = 'Aceptado' WHERE Id = $id_reembolso;";
    $resultUpdate = $conn->query($queryUpdate);
    if ($resultUpdate) {
        echo "Se actualizo el estado" . "<br>";
        //header("Location: ../../../../../src/Control/detallesReembolso.php?id_reembolso=$Folio_Original");
    } else {
        echo "No se actualizo el estado" . "<br>";
    }
} else {
    echo "Aun no ha pasado" . "<br>";
    //header("Location: ../../../../../src/Control/detallesReembolso.php?id_reembolso=$Folio_Original");
}
/*
$viatico_Query = "SELECT * FROM reembolso WHERE Id = $id_reembolso";
$viatico_Result = mysqli_query($conn, $viatico_Query);
$viatico_Row = mysqli_fetch_assoc($viatico_Result);
$id_usuario = $viatico_Row['Id_Usuario'];
$Id_Gerente = $viatico_Row['Id_Gerente'];
$FechaHoy = date("Y-m-d");

$aceptado_control = "Aceptado";

$GetUserInfo = "SELECT * FROM usuarios WHERE Id = $Id_Usuario";
$UserInfo = mysqli_query($conn, $GetUserInfo);
$UserInfoRow = mysqli_fetch_assoc($UserInfo);
$solicitante = $UserInfoRow['Nombre'];


$GetGerenteInfo = "SELECT * FROM usuarios WHERE Id = $Id_Gerente";
$GerenteInfo = mysqli_query($conn, $GetGerenteInfo);
$GerenteInfoRow = mysqli_fetch_assoc($GerenteInfo);
$gerente = $GerenteInfoRow['Nombre'];

echo "------------------------------------ <br>";
echo "Solicitante: $solicitante <br>";
echo "Gerente: $gerente <br>";
echo "------------------------------------ <br>";

/// Cuando el reembolso es aceptado
if ($Respuesta == 'Aceptado'){
    if ($Puesto == 'Control') {
        // Verificar si el Id_Reembolso ya está registrado
    $sql_check = "SELECT * FROM verificacion WHERE Id_Reembolso = '$id_reembolso'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows == 0) {

        $sql_insert = "INSERT INTO verificacion (Aceptado_Control, Id_Reembolso, Verificador, Solicitante)
                    VALUES ('$aceptado_control', '$id_reembolso', '$verificador', '$solicitante')";

        if ($conn->query($sql_insert) === TRUE) {
            echo "Registro insertado correctamente en la tabla verificacion.";
            header("Location: ../../../../../src/Control/detallesReembolso.php?id_reembolso=$id_reembolso");
        } else {
            echo "Error al insertar el registro: " . $conn->error;
        }
    } else {
        $sql_aceptar = "UPDATE verificacion SET Aceptado_Control 
        = '$aceptado_control' WHERE Id_Reembolso = '$id_reembolso'";
        if ($conn->query($sql_aceptar) === TRUE) {
            echo "Registro actualizado correctamente en la tabla verificacion.";
            header("Location: ../../../../../src/Control/detallesReembolso.php?id_reembolso=$id_reembolso");
        } else {
            echo "Error al actualizar el registro: " . $conn->error;
        }
    }
    }

    if($Puesto == 'Gerente'){
            // Verificar si el Id_Reembolso ya está registrado
            $sql_check = "SELECT * FROM verificacion WHERE Id_Reembolso = '$id_reembolso'";
            $result_check = $conn->query($sql_check);

            if ($result_check->num_rows == 0) {
                    $sql_insert = "INSERT INTO verificacion (Aceptado_Gerente, Id_Reembolso, Gerente, Verificador, Solicitante)
                                VALUES ('$Respuesta', '$id_reembolso', '$gerente', '$verificador', '$solicitante')";
        
                    if ($conn->query($sql_insert) === TRUE) {
                        echo "Registro insertado correctamente en la tabla verificacion.";
                    } else {
                        echo "Error al insertar el registro: " . $conn->error;
                    }
            } else {
                $sql_aceptar = "UPDATE verificacion SET Aceptado_Gerente 
                = '$Respuesta' WHERE Id_Reembolso = '$id_reembolso'";
                if ($conn->query($sql_aceptar) === TRUE) {
                    echo "Registro actualizado correctamente en la tabla verificacion.";
                } else {
                    echo "Error al actualizar el registro: " . $conn->error;
                }
            }
    }
    /// Cuando el reembolso es rechazado
} else {
    $Respuesta = 'Rechazado';
    if ($Puesto == 'Control') {
        // Verificar si el Id_Reembolso ya está registrado
    $sql_check = "SELECT * FROM verificacion WHERE Id_Reembolso = '$id_reembolso'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows == 0) {

        $sql_insert = "INSERT INTO verificacion (Aceptado_Control, Id_Reembolso, Verificador, Solicitante)
                    VALUES ('$Respuesta', '$id_reembolso', '$verificador', '$solicitante')";

        if ($conn->query($sql_insert) === TRUE) {
            /// Cambiar el estado del reembolso a rechazado
            $sql_rechazar = "UPDATE reembolso SET Estado = 'Rechazado' WHERE Id = '$id_reembolso'";
            if ($conn->query($sql_rechazar) === TRUE) {
                echo "Registro insertado correctamente en la tabla verificacion.";
            } else {
                echo "Error al insertar el registro: " . $conn->error;
            }
        } else {
            echo "Error al insertar el registro: " . $conn->error;
        }
    } else {
        $sql_aceptar = "UPDATE verificacion SET Aceptado_Control 
        = '$Respuesta' WHERE Id_Reembolso = '$id_reembolso'";
        if ($conn->query($sql_aceptar) === TRUE) {
            /// Cambiar el estado del reembolso a rechazado
            $sql_rechazar = "UPDATE reembolso SET Estado = 'Rechazado' WHERE Id = '$id_reembolso'";
            if ($conn->query($sql_rechazar) === TRUE) {
                echo "Registro actualizado correctamente en la tabla verificacion.";
            } else {
                echo "Error al actualizar el registro: " . $conn->error;
            }
        } else {
            echo "Error al actualizar el registro: " . $conn->error;
        }
    }
    }

    if($Puesto == 'Gerente'){
            // Verificar si el Id_Reembolso ya está registrado
            $sql_check = "SELECT * FROM verificacion WHERE Id_Reembolso = '$id_reembolso'";
            $result_check = $conn->query($sql_check);

            if ($result_check->num_rows == 0) {
                    $sql_insert = "INSERT INTO verificacion (Aceptado_Gerente, Id_Reembolso, Gerente, Verificador, Solicitante)
                                VALUES ('$Respuesta', '$id_reembolso', '$gerente', '$verificador', '$solicitante')";
        
                    if ($conn->query($sql_insert) === TRUE) {
                        echo "Registro insertado correctamente en la tabla verificacion.";
                    } else {
                        echo "Error al insertar el registro: " . $conn->error;
                    }
            } else {
                $sql_aceptar = "UPDATE reembolso SET Estado = 'Rechazado' WHERE Id = '$id_reembolso'"; 
                if ($conn->query($sql_aceptar) === TRUE) {
                    echo "Registro actualizado correctamente en la tabla verificacion.";
                } else {
                    echo "Error al actualizar el registro: " . $conn->error;
                }
            }
    }
}

$queryVerificador = "SELECT * FROM verificacion WHERE Id_Reembolso = $id_reembolso";
$resultVerificador = $conn->query($queryVerificador);
$rowVerificador = $resultVerificador->fetch_assoc();
$Aceptado_Control = $rowVerificador['Aceptado_Control'];
$Aceptado_Gerente = $rowVerificador['Aceptado_Gerente'];
/*
if ($Aceptado_Control == 'Aceptado' && $Aceptado_Gerente == 'Aceptado') {

    $queryUpdate = "UPDATE reembolso SET Estado = 'Aceptado' WHERE Id = $id_reembolso;";
    $resultUpdate = $conn->query($queryUpdate);
    if ($resultUpdate) {
        echo "Se actualizo el estado" . "<br>";
    } else {
        echo "No se actualizo el estado" . "<br>";
    }
} else {
    echo "Aun no ha pasado" . "<br>";
}*/

?>
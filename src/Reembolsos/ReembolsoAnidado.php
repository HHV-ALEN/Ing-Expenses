<?php
session_start();
require('../../resources/config/db.php');

$Id_Reembolso = $_GET['id'];
$Nombre = $_SESSION['Name'];
$Tipo_Usuario = $_SESSION['Position'];
/// Obtener información del reembolso

$Sql_Reembolso = "SELECT * FROM reembolsos WHERE Id = '$Id_Reembolso'";
$result = $conn->query($Sql_Reembolso);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $Solicitante = $row['Solicitante'];
    $Concepto = $row['Concepto'];
    $Monto = $row['Monto'];
    $Destino = $row['Destino'];
    $Fecha = $row['Fecha'];
    $Descripción = $row['Descripcion'];
    $Estado = $row['Estado'];
    $Nombre_Archivo = $row['Nombre_Archivo'];
    $Orden_Venta = $row['Orden_Venta'];
    $Codigo = $row['Codigo'];
    $Nombre_Proyecto = $row['Nombre_Proyecto'];

}

/// Cuantos reembolsos anidados con estado "Abierto" hay
$ContadorDeReembolsosActivos = 0;
$Sql_ReembolsosAnidados = "SELECT * FROM reembolsos_anidados WHERE Id_Reembolso = '$Id_Reembolso'";
$Result_ReembolsosAnidados = $conn->query($Sql_ReembolsosAnidados);
if ($Result_ReembolsosAnidados->num_rows > 0) {
    while ($row = $Result_ReembolsosAnidados->fetch_assoc()) {
        $Estado_anidado = $row['Estado'];
        if ($Estado_anidado == 'Abierto') {
            $ContadorDeReembolsosActivos++;
        }
    }
}
//echo "<br> Hay $ContadorDeReembolsosActivos reembolsos anidados activos";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reembolso</title>
    <link rel="shortcut icon" href="/resources/img/logo-icon.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="../../resources/Css/ColorTarjetasReembolso.css">
    <style>
        /* Ajusta el tamaño del select */
        .codigo-prefix {
            flex: 0 0 25%;
            /* Establece el ancho del select al 25% del contenedor */
            max-width: 80px;
            /* Ancho máximo del select */
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        /* Ajusta el tamaño del input para que ocupe el resto del espacio */
        .codigo-input {
            flex: 1;
            /* Ocupa el resto del espacio disponible */
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }
    </style>

    <style>
        .clickable-img {
            transition: transform 0.2s ease;
        }

        .clickable-img:hover {
            transform: scale(1.05);
        }

        #imageModal .modal-body img {
            width: 100%;
            height: auto;
        }
    </style>
</head>

<body>
    <?php include '../navbar.php'; ?>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6 text-center">
                        <h5>Reembolso No.: <?php echo $Id_Reembolso; ?></h5>
                        <a href="../../resources/Back/Reembolsos/DownloadReembolso.php?Id=<?= urlencode($Id_Reembolso) ?>"
                            class="btn btn-primary w-100">Descargar información del reembolso</a>
                    </div>
                    <div class="col-md-6 text-center">
                        <h5>Reembolsos Activos: <?php echo $ContadorDeReembolsosActivos; ?></h5>
                        <?php
                        if($Tipo_Usuario != 'Empleado'){
                            if ($ContadorDeReembolsosActivos == 0) {
                                echo "Puedes aceptar el reembolso";
                                /// boton para ir a completar el estado del reembolso
                                echo "<a href='../../resources/Back/Reembolsos/Anidados/VerifyReembolso_Anidado.php?Id=$Id_Reembolso&Tipo=Reembolso&Respuesta=Completar' class='btn btn-success w-100'>Completar Reembolsos </a>";

                            } else {
                                echo "No puedes aceptar el reembolso hasta que los reembolsos anidados esten aceptados";
                            }
                        }
                    
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Si el usuario es el solicitante, mostrar formulario para anidar reembolso -->
    <?php if ($Nombre == $Solicitante) {

        ?>
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm border-0">
                        
                        <div class="card-header bg-black text-white text-center">
                            <h5>Anidar Al reembolso: <?php echo $Id_Reembolso ?></h5>
                        </div>
                        <!--- Boton para descargar el archivo con la información del reembolso -->

                        <div class="card-body">
                            <form action="/resources/Back/Reembolsos/AddReembolsoAnidado.php" method="POST"
                                enctype="multipart/form-data">
                                <input type="hidden" name="Id_Reembolso" value="<?php echo $Id_Reembolso; ?>">
                                <!-- Monto y Concepto -->
                                <div class="row my-2">
                                    <div class="col-md-6 text-center">
                                        <label for="Monto" class="form-label"><strong>Monto:</strong></label>
                                        <input type="number" class="form-control" id="Monto" name="Monto" required>
                                    </div>
                                    <div class="col-md-6 text-center">
                                        <label for="Concepto" class="form-label"><strong>Concepto:</strong></label>
                                        <!-- Select de los conceptos -->
                                        <select class="form-select" id="selectConcepto" name="selectConcepto"
                                            aria-label="Default select example" onchange="mostrarInput()">
                                            <option value="Hospedaje">Hospedaje</option>
                                        <option value="Vuelos">Vuelos</option>
                                        <option value="Alimentación">Alimentación</option>
                                        <option value="Transporte">Transporte</option>
                                        <option value="Estacionamiento">Estacionamiento</option>
                                        <option value="Gasolina">Gasolina</option>
                                        <option value="Casetas">Casetas</option>
                                        <option value="Levantamiento">Levantamiento</option>
                                        <option value="Soporte Técnico">Soporte Técnico</option>
                                        <option value="Servicio">Servicio</option>
                                        <option value="Puesto en marcha">Puesto en marcha</option>
                                        <option value="Ejecucion">Ejecución</option>
                                        <option value="Garantia">Garantía</option>
                                        <option value="Otro">Otro</option>
                                        </select>
                                        <!-- Input oculto por defecto -->
                                <div class="col-md-6 text-center" id="inputOtroConcepto"
                                    style="display: none; margin-top: 10px;">
                                    <label for="otroConcepto" class="form-label"><strong>Especifica el
                                            concepto:</strong></label>
                                    <input type="text" class="form-control" id="otroConcepto" name="Concepto"
                                        placeholder="Escribe el concepto">
                                </div>
                                    </div>
                                </div>



                                <!-- Datos para la creación del FOLIO -->
                                <div class="row my-4">
                                    <div class="col-md-6 text-center">
                                        <label for="ordenVenta" class="form-label"><strong>Orden de venta:</strong></label>
                                        <input type="text" placeholder="Solo números" class="form-control" id="ordenVenta"
                                            name="ordenVenta" required inputmode="numeric"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    </div>


                                    <div class="col-md-6 text-center">
                                        <label for="codigo" class="form-label"><strong>Código:</strong></label>
                                        <div class="input-group">
                                            <select class="form-select form-select-sm codigo-prefix" id="codigoPrefix"
                                                name="codigoPrefix" aria-label="Seleccionar código">
                                                <option value="PYE" selected>PYE</option>
                                                <option value="PYI">PYI</option>
                                            </select>
                                            <input type="text" class="form-control codigo-input" id="codigo" name="codigo"
                                                placeholder="Ingresar código" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                <div class="col-md-6 text-center">
                                    <label for="Cliente" class="form-label"><strong>Cliente:</strong></label>
                                    <input type="text" placeholder="Ingrese Nombre del Cliente" class="form-control"
                                        onkeyup="javascript:this.value = this.value.toUpperCase().replace(/[^A-Z0-9\s]/g, '');"
                                        id="Cliente" name="Cliente" required>
                                </div>

                            
                                <div class="col-md-6 text-center">
                                    <label for="nombreProyecto" class="form-label"><strong>Nombre del
                                            proyecto:</strong></label>
                                    <input type="text" placeholder="Ingrese Nombre del proyecto" class="form-control"
                                        onkeyup="javascript:this.value = this.value.toUpperCase().replace(/[^A-Z0-9\s]/g, '');"
                                        id="nombreProyecto" name="nombreProyecto" required>
                                </div>
                            </div>

                                
                                <!-- Destino y Imagen -->
                                <div class="row my-4">
                                    <div class="col-md-6 text-center">
                                        <label for="Destino" class="form-label"><strong>Destino:</strong></label>
                                        <input type="Text" class="form-control" id="Destino" name="Destino" required>
                                    </div>
                                    <div class="col-md-6 text-center">
                                        <label for="Descripcion" class="form-label"><strong>Descripcion:</strong></label>
                                        <input type="Text" class="form-control" id="Descripcion" name="Descripcion"
                                            required>
                                    </div>
                                    
                                </div>
                                <div class="row my-4">

                                    <div class="col-md-6 text-center">
                                        <label for="archivo" class="form-label"><strong>Archivo de
                                                Evidencia:</strong></label>
                                        <input type="file" class="form-control" id="file" name="file" required>
                                    </div>
                                    <div class="col-md-6 text-center">
                                        <label for="Fecha" class="form-label"><strong>Fecha:</strong></label>
                                        <input type="Date" class="form-control" id="Fecha" name="Fecha" required>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-outline-success w-100">Finalizar Solicitud</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php } else {
        echo "<h1>  </h1>";
    }
    ?>

    <!-- Reembolso Maestro -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php
                // Definir la clase de color de fondo para la card según el estado del reembolso
                $colorClassReembolso = '';
                switch ($Estado) {
                    case 'Abierto':
                        $colorClassReembolso = 'card-reembolso-pendiente'; // Amarillo suave
                        break;
                    case 'Rechazado':
                        $colorClassReembolso = 'card-reembolso-rechazado'; // Rojo suave
                        break;
                    case 'Aceptado':
                        $colorClassReembolso = 'card-reembolso-aceptado'; // Verde suave
                        break;
                    default:
                        $colorClassReembolso = 'card-light'; // Fondo por defecto
                }
                ?>

                <!-- Tarjeta de reembolso con sombra y color personalizado según el estado -->
                <div class="card shadow-sm border-0 <?php echo $colorClassReembolso; ?>">
                    <!-- Cabecera de la tarjeta -->
                    <div class="card-header bg-dark text-white text-center">
                        <h5>Reembolso No.: <?php echo $Id_Reembolso; ?></h5>
                    </div>
                    <!-- Cuerpo de la tarjeta -->
                    <div class="card-body row">

                        <!-- Columna izquierda: Imagen del reembolso y botones -->
                        <div class="col-md-5 text-center">
                            <!-- Imagen con la opción de ampliar al hacer clic -->

                            <strong>Archivo:</strong>
                            <a href="https://ingenieria.alenexpenses.com/uploads/<?php echo $Evidencia['Nombre_Archivo']; ?>"
                                target="_blank">
                                <?php echo $Evidencia['Nombre_Archivo']; ?>
                            </a>

                            <!-- Botón para descargar la imagen -->
                            <a href="../../uploads/<?php echo $Nombre_Archivo; ?>"
                                download="<?php echo $Nombre_Archivo; ?>" class="btn btn-primary mt-3 w-100">Descargar
                            </a>
                            <hr>

                            <?php
                            /// Modulo de Verificación para el Reembolso Maestro (Principal)
                            
                            // Obtener la información de los estados de verificación
                            $Sql_Verificacion = "SELECT * FROM verificacion WHERE Id_Relacionado = '$Id_Reembolso' And Tipo = 'Reembolso'";
                            $Result_Verificacion = $conn->query($Sql_Verificacion);
                            if ($Result_Verificacion == TRUE) {
                                $row = $Result_Verificacion->fetch_assoc();
                                $Aceptado_Control = $row['Aceptado_Control'];
                            }
                            ?>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <h6 class="mb-3"><strong>Control:</strong><br><?php echo $Aceptado_Control; ?></h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 text-center">
                                    <?php
                                    //echo "Reembolsos Activos: $ContadorDeReembolsosActivos";


                                    if ($Tipo_Usuario == 'Control') {
                                        ?>
                                        <div class="col-md-12 text-center">

                                            <a href="../../resources/Back/Reembolsos/Anidados/VerifyReembolso_Anidado.php?Id=<?= urlencode($Id_Reembolso) ?>&Tipo=Reembolso&Respuesta=Aceptado"
                                                class="btn btn-success mt-3 w-100">Aceptar</a>
                                        </div>
                                        <div class="col-md-12 text-center">
                                            <a href="../../resources/Back/Reembolsos/Anidados/VerifyReembolso_Anidado.php?Id=<?= urlencode($Id_Reembolso) ?>&Tipo=Reembolso&Respuesta=Rechazado"
                                                class="btn btn-danger mt-3 w-100">Rechazar</a>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>

                            </div>
                        </div>

                        <!-- Columna derecha: Información del reembolso -->
                        <div class="col-md-7">
                            <div class="ps-3"> <!-- Clase para agregar espacio a la izquierda -->
                                <h6 class="mb-3"><strong>Solicitante:</strong>
                                    <?php echo htmlspecialchars($Solicitante); ?></h6>
                                <h6 class="mb-3"><strong>Cliente:</strong> <?php echo htmlspecialchars($Cliente); ?>
                                <h6 class="mb-3"><strong>Concepto:</strong> <?php echo htmlspecialchars($Concepto); ?>
                                </h6>
                                <h6 class="mb-3"><strong>Monto:</strong> $<?php echo number_format($Monto, 2); ?></h6>
                                <h6 class="mb-3"><strong>Destino:</strong> <?php echo htmlspecialchars($Destino); ?>
                                </h6>
                                <h6 class="mb-3"><strong>Fecha:</strong> <?php echo htmlspecialchars($Fecha); ?></h6>
                                <h6 class="mb-3"><strong>Descripción:</strong>
                                    <?php echo htmlspecialchars($Descripción); ?></h6>
                                <h6 class="mb-3"><strong>Estado:</strong> <?php echo htmlspecialchars($Estado); ?></h6>
                                <h6 class="mb-3"><strong>Código:</strong>
                                    <?php echo htmlspecialchars($Orden_Venta . "-" . $Codigo . "-" . $Nombre_Proyecto); ?>

                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <!-- Reembolso Anidado -->
    <!-- Obtener los reembolsos anidados a este folio y mostrar debajo del reembolso maestro -->
    <?php
    $ContadorDeReembolsosActivos = 0;
    $Sql_ReembolsosAnidados = "SELECT * FROM reembolsos_anidados WHERE Id_Reembolso = '$Id_Reembolso'";
    $Result_ReembolsosAnidados = $conn->query($Sql_ReembolsosAnidados);
    /// Si hay reembolsos Anidados mostrarlos con el formato de la tarjeta
    if ($Result_ReembolsosAnidados->num_rows > 0) {
        while ($row = $Result_ReembolsosAnidados->fetch_assoc()) {
            $Id_ReembolsoAnidado = $row['Id'];
            $Solicitante = $row['Solicitante'];
            $Cliente = $row['Cliente'];
            $Concepto = $row['Concepto'];
            $Monto = $row['Monto'];
            $Destino = $row['Destino'];
            $Fecha = $row['Fecha'];
            $Descripcion = $row['Descripcion'];
            $Estado = $row['Estado'];
            $Nombre_Archivo = $row['Nombre_Archivo'];
            $Nombre_Proyecto = $row['Nombre_Proyecto'];
            $Codigo = $row['Codigo'];
            $Orden_Venta = $row['Orden_Venta'];



            // Incrementar el contador si el reembolso está en estado "Abierto"
            if ($Estado == 'Abierto') {
                $ContadorDeReembolsosActivos++;
            }
            ?>
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <?php
                        // Definir clase de color de fondo para la card según el estado del reembolso
                        $colorClassReembolso = '';

                        switch ($Estado) {
                            case 'Abierto':
                                $colorClassReembolso = 'card-reembolso-pendiente'; // Amarillo suave
                                break;
                            case 'Rechazado':
                                $colorClassReembolso = 'card-reembolso-rechazado'; // Rojo suave
                                break;
                            case 'Aceptado':
                                $colorClassReembolso = 'card-reembolso-aceptado'; // Verde suave
                                break;
                            default:
                                $colorClassReembolso = 'card-light'; // Fondo por defecto, si es necesario
                        }
                        ?>

                        <!-- Tarjeta de reembolso con sombra y color personalizado según el estado -->
                        <div class="card shadow-sm border-0 <?php echo $colorClassReembolso; ?>">
                            <!-- Cabecera de la tarjeta -->
                            <div class="card-header bg-dark text-white text-center">
                                <h5>Reembolso Anidado No.: <?php echo $Id_ReembolsoAnidado; ?></h5>
                            </div>
                            <!-- Cuerpo de la tarjeta -->
                            <div class="card-body row">

                                <!-- Columna izquierda: Imagen del reembolso y botones -->
                                <div class="col-md-5 text-center">
                                    <!-- Imagen con la opción de ampliar al hacer clic -->
                                    <!-- Imagen con la opción de ampliar al hacer clic -->

                                    <strong>Archivo:</strong>
                                    <a href="/uploads/<?php echo $Nombre_Archivo; ?>" target="_blank">
                                        <?php echo $Nombre_Archivo ?>
                                    </a>
                                    <!-- Botón para descargar la imagen -->
                                    <a href="../../uploads/<?php echo $Nombre_Archivo; ?>"
                                        download="<?php echo $Nombre_Archivo; ?>" class="btn btn-primary mt-3 w-100">Descargar
                                    </a>

                                    <!-- Si el usuario es 'Control' o 'Gerente' y el reembolso está abierto -->
                                    <?php if (($Tipo_Usuario == 'Control' || $Tipo_Usuario == 'Gerente') && $Estado == 'Abierto') { ?>
                                        <div class="row">
                                            <?php
                                            // Obtener datos de la tabla verificación
                                            $Sql_Verificacion = "SELECT * FROM verificacion WHERE Id_Relacionado = '$Id_ReembolsoAnidado' And Tipo = 'Reembolso_anidado'";
                                            $Result_Verificacion = $conn->query($Sql_Verificacion);
                                            if ($Result_Verificacion->num_rows > 0) {
                                                $row = $Result_Verificacion->fetch_assoc();
                                                $Aceptado_Control = $row['Aceptado_Control'];
                                                ?>
                                                <!-- Botones de aceptar y rechazar -->
                                                <div class="row">
                                                    <div class="col-md-6 text-center">
                                                        <?php
                                                        if ($Tipo_Usuario == 'Control') {
                                                            ?>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <a href="../../resources/Back/Reembolsos/Anidados/VerifyReembolso_Anidado.php?Id=<?= urlencode($Id_ReembolsoAnidado) ?>&Id_Maestro=<?= urlencode($Id_Reembolso) ?>&Tipo=Reembolso_Anidado&Respuesta=Aceptado"
                                                                        class="btn btn-success mt-3 w-100">Aceptar</a>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <a href="../../resources/Back/Reembolsos/Anidados/VerifyReembolso_Anidado.php?Id=<?= urlencode($Id_ReembolsoAnidado) ?>&Id_Maestro=<?= urlencode($Id_Reembolso) ?>&Tipo=Reembolso_Anidado&Respuesta=Rechazado"
                                                                        class="btn btn-danger mt-3 w-100">Rechazar</a>
                                                                </div>


                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            }

                                            ?>
                                    <?php } ?>
                                </div>

                                <!-- Columna derecha: Información del reembolso -->
                                <div class="col-md-7">
                                    <div class="ps-3"> <!-- Clase para agregar espacio a la izquierda -->
                                        <h6 class="mb-3"><strong>Solicitante:</strong>
                                            <?php echo htmlspecialchars($Solicitante); ?></h6>
                                        <h6 class="mb-3"><strong>Cliente:</strong> <?php echo htmlspecialchars($Cliente); ?>
                                        <h6 class="mb-3"><strong>Concepto:</strong> <?php echo htmlspecialchars($Concepto); ?>
                                        </h6>
                                        <h6 class="mb-3"><strong>Monto:</strong> $<?php echo number_format($Monto, 2); ?></h6>
                                        <h6 class="mb-3"><strong>Destino:</strong> <?php echo htmlspecialchars($Destino); ?>
                                        </h6>
                                        <h6 class="mb-3"><strong>Fecha:</strong> <?php echo htmlspecialchars($Fecha); ?></h6>
                                        <h6 class="mb-3"><strong>Descripción:</strong>
                                            <?php echo htmlspecialchars($Descripción); ?></h6>
                                        <h6 class="mb-3"><strong>Estado:</strong> <?php echo htmlspecialchars($Estado); ?></h6>
                                        <h6 class="mb-3"><strong>Código:</strong>
                                            <?php echo htmlspecialchars($Orden_Venta . "-" . $Codigo . "-" . $Nombre_Proyecto); ?>


                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
            <?php
        }
    }


    ?>





    <br>

    <!-- Modal para expandir imagen -->
    <div id="imageModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <img id="expandedImg" class="img-fluid">
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS and dependencies (Popper.js and jQuery) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>




    <script>
        function mostrarInput() {
            var select = document.getElementById("selectConcepto");
            var inputOtro = document.getElementById("inputOtroConcepto");

            // Si selecciona "Otro", mostrar el input
            if (select.value === "Otro") {
                inputOtro.style.display = "block";
            } else {
                inputOtro.style.display = "none";
            }
        }
    </script>
</body>

</html>
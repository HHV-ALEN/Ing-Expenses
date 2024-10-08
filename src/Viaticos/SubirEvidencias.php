<?php
include('../../resources/config/db.php');
session_start();
$Id_Viatico = $_GET['id'];
$Tipo_Usuario = $_SESSION['Position'];
$Nombre_Usuario = $_SESSION['Name'];


/// Obtener información General Del Viatico
$Query = "SELECT * FROM viaticos WHERE Id = $Id_Viatico";
$Result = $conn->query($Query);
if ($Result->num_rows > 0) {
    $Row = $Result->fetch_assoc();
    $Orden_Venta = $Row['Orden_Venta'];
    $Codigo = $Row['Codigo'];
    $Nombre_Proyecto = $Row['Nombre_Proyecto'];
    $Destino = $Row['Destino'];
    $Total = $Row['Total'];
    $Fecha_Salida = $Row['Fecha_Salida'];
    $Fecha_Regreso = $Row['Fecha_Regreso'];
    $Solicitante = $Row['Solicitante'];
    $Estado = $Row['Estado'];

}
// Imprimir información del viático
/*
echo "<br> - Orden de Venta: " . $Orden_Venta;
echo "<br> - Código: " . $Codigo;
echo "<br> - Proyecto: " . $Nombre_Proyecto;
echo "<br> - Destino: " . $Destino;
echo "<br> - Total: " . $Total;
echo "<br> - Fecha de Salida: " . $Fecha_Salida;
echo "<br> - Fecha de Regreso: " . $Fecha_Regreso;*/

/// Obtener información de los conceptos solicitados relacionados con este viático
// Y guardar en un array
$Conceptos = array();
$Query = "SELECT * FROM conceptos WHERE Id_Viatico = $Id_Viatico";
$Result = $conn->query($Query);
if ($Result->num_rows > 0) {
    while ($Row = $Result->fetch_assoc()) {
        $Conceptos[] = $Row;
    }
}

///print_r($Conceptos);

$MontosCompletados = 0;
$ConteoDeMontosIncompletos = 0;
$Estados_De_Evidencias = array();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evidenciar Viático</title>
    <link rel="shortcut icon" href="/resources/img/logo-icon.png" />
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Importar Css ColorTarjetas.css -->
    <link rel="stylesheet" href="/resources/css/ColorTarjetas.css">

</head>

<body>
    <?php include '../navbar.php' ?>
    <br>
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-md-12">
            <?php 
                // Definir variables para color de fondo y texto
                $Color_Row = '';
                $Text_Color = '';
                switch ($Estado) {
                    case 'Abierto':
                        $Color_Row = "table-secondary"; // Fondo gris claro
                        $Text_Color = "text-dark"; // Texto oscuro para fondo claro
                        break;
                    case 'Aceptado':
                        $Color_Row = "table-primary"; // Fondo azul
                        $Text_Color = "text-white"; // Texto blanco para fondo oscuro
                        break;
                    case 'Rechazado':
                        $Color_Row = "table-danger"; // Fondo rojo
                        $Text_Color = "text-white"; // Texto blanco para fondo oscuro
                        break;
                    case 'Verificación':
                        $Color_Row = "table-info"; // Fondo azul claro
                        $Text_Color = "text-dark"; // Texto oscuro para fondo claro
                        break;
                    case 'Revisión':
                        $Color_Row = "table-success"; // Fondo verde
                        $Text_Color = "text-white"; // Texto blanco para fondo oscuro
                        break;
                    case 'Prórroga':
                        $Color_Row = "table-warning"; // Fondo amarillo
                        $Text_Color = "text-dark"; // Texto oscuro para fondo claro
                        break;
                    case 'Completado':
                        $Color_Row = "table-success"; // Fondo verde
                        $Text_Color = "text-white"; // Texto blanco para fondo oscuro
                        break;
                    case 'En Curso':
                        $Color_Row = "table-light"; // Fondo blanco
                        $Text_Color = "text-dark"; // Texto oscuro para fondo claro
                        break;
                    case 'Cerrado':
                        $Color_Row = "table-danger"; // Fondo rojo
                        $Text_Color = "text-white"; // Texto blanco para fondo oscuro
                        break;
                    case 'Segunda Revisión':
                        $Color_Row = "table-warning"; // Fondo amarillo
                        $Text_Color = "text-dark"; // Texto oscuro para fondo claro
                        break;
                }
                ?>
            <div class="card  shadow-sm border-0">
                <div class="card-header  text-center">
                    <h5>Folio de Viáticos: <?php echo $Id_Viatico; ?> - Estado: <?php echo $Estado; ?>  </h5>
                </div>
            </div>

                    <div class="card-body">
                        
                        <div class="row">
                            <div class="col-md-6 text-center">
                                <p><strong>Código:</strong> <?php echo $Codigo; ?></p>
                                <p><strong>Proyecto:</strong> <?php echo $Nombre_Proyecto; ?></p>
                                <p><strong>Destino:</strong> <?php echo $Destino; ?></p>
                            </div>
                            <div class="col-md-6 text-center">
                                <p><strong>Total:</strong> $<?php echo number_format($Total, 2); ?></p>
                                <p><strong>Fecha de Salida:</strong>
                                    <?php echo date('d-m-Y', strtotime($Fecha_Salida)); ?></p>
                                <p><strong>Fecha de Regreso:</strong>
                                    <?php echo date('d-m-Y', strtotime($Fecha_Regreso)); ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <?php 
                            if ($Solicitante == $Nombre_Usuario)
                            {
                                if ($Estado == 'Prórroga'){
                                    ?>
                                   <div class="col-md-12 text-center">
                                <p></p><strong>Finalizar Segunda recopilación de evidencias:</strong></p>
                                <p>Una vez que finalices, el viático cambiará a estado de <strong>Segunda Revisión</strong> y no podras volver a evidenciar.</p>
                                <a href="../../resources/Back/Viaticos/FinalizarViatico.php?id=<?php echo $Id_Viatico; ?>&Response=SegundaRevisión"
                                    class="btn btn-success">Finalizar Recopilación de evidencias</a>

                                    <?php
                                }
                                elseif($Estado == 'En Curso' || $Estado == 'Revisión' || $Estado == 'Verificación')
                                {
                                ?>
                                    <div class="col-md-12 text-center">
                                    <p></p><strong>Finalizar recopilación de evidencias:</strong></p>
                                    <p>Una vez que finalices, el viático cambiará a estado de Revisión.</p>
                                    <a href="../../resources/Back/Viaticos/FinalizarViatico.php?id=<?php echo $Id_Viatico; ?>"
                                    class="btn btn-success">Finalizar Recopilación de evidencias</a>
                                    
                                <?php
                                }   
                            }
                            ?>                        
                    </div>
                </div>
            </div>
    </div>
    <div class="container mt-4">
        <div class="row">

            <!-- Iterar sobre los conceptos en PHP -->
            <?php foreach ($Conceptos as $concepto) { ?>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-dark text-white text-center">
                            <h6><?php echo $concepto['Concepto']; ?></h6>
                        </div>
                        <div class="card-body bg-light">
                            <div class="row">
                                <div class="col-md-6 text-center">
                                    <p><strong>Solicitante:</strong> <?php echo $concepto['Solicitante']; ?></p>
                                    <p><strong>Monto Solicitado:</strong>
                                        $<?php echo number_format($concepto['Monto'], 2); ?></p>

                                    <?php if($Solicitante == $_SESSION['Name']){ ?>
                                        <button class="btn btn-success btn-sm" data-bs-toggle="collapse"
                                            data-bs-target="#collapse-<?php echo $concepto['Id']; ?>">
                                            Agregar Evidencias
                                        </button>
                                    <?php } ?>


                                </div>
                                <div class="col-md-6 text-center">
                                    <!-- Mostrar la cantidad del monto evidenciado en los registros por cada concepto -->
                                    <p><strong>Monto Evidenciado:</strong>
                                        <?php
                                        $Query = "SELECT SUM(Monto) AS total_monto FROM evidencias WHERE Id_Relacionado = " . $concepto['Id_Viatico'] . " AND Concepto = '" . $concepto['Concepto'] . "' AND Estado = 'Aceptado'";
                                        $Result = $conn->query($Query);
                                        $Row = $Result->fetch_assoc();
                                        $Total_Monto = $Row['total_monto'];
                                        echo "$" . number_format($Row['total_monto'], 2);
                                        ?>
                                        <!-- Mostrar el número de Evidencias registradas con el mismo concepto -->
                                    <p><strong>Evidencias Registradas:</strong>
                                        <?php
                                        $Query = "SELECT COUNT(*) AS total_evidencias FROM evidencias WHERE Id_Relacionado = " . $concepto['Id_Viatico'] . " AND Concepto = '" . $concepto['Concepto'] . "'";
                                        $Result = $conn->query($Query);
                                        $Row = $Result->fetch_assoc();
                                        echo $Row['total_evidencias'];
                                        ?>

                                    </p>
                                    <!-- Botón para abrir el nuevo collapse (nuevo botón) -->
                                    <button class="btn btn-dark btn-sm" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-new-<?php echo $concepto['Id']; ?>">
                                        Ver Evidencias relacionadas
                                    </button>

                                </div>
                            </div>
                            <hr>

                            <!-- Sección de collapse para agregar evidencias -->
                            <div id="collapse-<?php echo $concepto['Id']; ?>" class="collapse">
                                <form action="../../resources/Back/Viaticos/UploadEvidence.php" method="POST"
                                    enctype="multipart/form-data">
                                    <input type="hidden" name="id_viatico" value="<?php echo $Id_Viatico; ?>">
                                    <!-- Hidden secreto para mandar el concepto $concepto['Concepto']; -->
                                    <input type="hidden" name="concepto" value="<?php echo $concepto['Concepto']; ?>">
                                    <div class="mb-3">
                                        <label for="monto-<?php echo $concepto['Id']; ?>" class="form-label">Monto</label>
                                        <input type="number" class="form-control" id="monto-<?php echo $concepto['Id']; ?>"
                                            name="monto" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="descripcion-<?php echo $concepto['Id']; ?>"
                                            class="form-label">Descripción</label>
                                        <textarea class="form-control" id="descripcion-<?php echo $concepto['Id']; ?>"
                                            name="descripcion" rows="3" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="archivo-<?php echo $concepto['Id']; ?>" class="form-label">Subir
                                            Archivo</label>
                                        <input type="file" class="form-control" id="archivo-<?php echo $concepto['Id']; ?>"
                                            name="archivo" accept=".jpg, .png, .pdf" required>
                                    </div>
                                    <input type="hidden" name="id_concepto" value="<?php echo $concepto['Id']; ?>">
                                    <button type="submit" class="btn btn-primary">Subir Evidencia</button>
                                </form>
                            </div>

                            <!-- Sección de collapse para Mostrar Evidencias con el mismo concepto (nuevo collapse) -->
                            <div id="collapse-new-<?php echo $concepto['Id']; ?>" class="collapse">
                                <h6>Evidencias relacionadas con el concepto: <?php echo $concepto['Concepto']; ?></h6>

                                <?php
                                
                                $Evidencias = array();
                                $Query = "SELECT * FROM evidencias WHERE Id_Relacionado = " . $concepto['Id_Viatico'] . " AND Concepto = '" . $concepto['Concepto'] . "'";
                                $Result = $conn->query($Query);

                                if ($Result->num_rows > 0) {
                                    while ($Row = $Result->fetch_assoc()) {
                                        $Evidencias[] = $Row;
                                        array_push($Estados_De_Evidencias, $Row['Estado']);
                                    }

                                    // Mostrar las evidencias en tarjetas
                                    foreach ($Evidencias as $Evidencia) {



                                        // Definir clase de color de fondo para la card según el estado
                                        $colorStyle = '';

                                        switch (trim($Evidencia['Estado'])) {
                                            case 'Pendiente':
                                                $colorStyle = 'background-color: rgba(255, 193, 7, 0.2);'; // Amarillo suave con opacidad
                                                break;
                                            case 'Rechazado':
                                                $colorStyle = 'background-color: rgba(220, 53, 69, 0.2);'; // Rojo suave con opacidad
                                                break;
                                            case 'Aceptado':
                                                $colorStyle = 'background-color: rgba(40, 167, 69, 0.2);'; // Verde suave con opacidad
                                                break;
                                            default:
                                                $colorStyle = 'background-color: rgba(248, 249, 250, 0.8);'; // Fondo claro neutro con opacidad
                                        }
                                        
                                        ?>
                                         <div class="card mb-3" style="<?php echo $colorStyle; ?>">
                                            <div class="card-body">
                                                <h5 class="card-title">Evidencia #<?php echo $Evidencia['Id']; ?></h5>

                                                <ul class="list-unstyled">
                                                    <li><strong>Fecha de Registro:</strong>
                                                        <?php echo $Evidencia['Fecha_Registro']; ?></li>
                                                    <li><strong>Solicitante:</strong> <?php echo $Evidencia['Solicitante']; ?></li>
                                                    <li><strong>Concepto:</strong> <?php echo $Evidencia['Concepto']; ?></li>
                                                    <li><strong>Descripción:</strong> <?php echo $Evidencia['Descripcion']; ?></li>
                                                    <li><strong>Monto:</strong>
                                                        $<?php echo number_format($Evidencia['Monto'], 2); ?></li>
                                                    <?php
                                                    if ($Evidencia['Concepto'] == 'Gastos Médicos') {
                                                        $Arreglo_Gastos_Medicos[] = array(
                                                            'Monto' => $Evidencia['Monto'],
                                                            'Id' => $Evidencia['Id']
                                                        );
                                                        $Total_Gastos_Medicos += $Evidencia['Monto'];
                                                    }
                                                    ?>
                                                    <li><strong>Estado:</strong> <?php echo $Evidencia['Estado']; ?></li>
                                                    <li>
                                                        <strong>Archivo:</strong>
                                                        <a href="/uploads/<?php echo $Evidencia['Nombre_Archivo']; ?>"
                                                            target="_blank">
                                                            <?php echo $Evidencia['Nombre_Archivo']; ?>
                                                        </a>
                                                    </li>
                                                </ul>
                                                <hr>
                                                <?php if($Tipo_Usuario == 'Control'){
                                                    if($Evidencia['Estado']== 'Pendiente'){
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <a href="../../resources/Back/Viaticos/VerificacionEvidencia.php?id=<?php echo $Evidencia['Id']; ?>&Response=Aceptado&Id_viatico=<?php echo $Id_Viatico; ?>"
                                                                class="btn btn-success">Aceptar</a>

                                                        </div>
                                                        <div class="col-md-6">
                                                            <a href="../../resources/Back/Viaticos/VerificacionEvidencia.php?id=<?php echo $Evidencia['Id']; ?>&Response=Rechazado&Id_viatico=<?php echo $Id_Viatico; ?>"
                                                                class="btn btn-danger">Rechazar</a>
                                                            
                                                        </div>
                                                    </div>
                                                <?php
                                                }}
                                                ?>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                } else {
                                    echo "<br> - No hay evidencias relacionadas con este concepto.";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Tarjeta Con footer visible una vez que se terminan de subir todas las evidencias,
    se habilita un botón para finalizar la subida de evidencias y que el viático cambié de estado a Revisión.
    Solo una vez que ConteoDeMontosIncompletos sea 0, es decir no se encuentren montos incompletos -->
    <?php

    if ($Tipo_Usuario == 'Control' || $Tipo_Usuario == 'Gerente') { ?>
        <div class="container mt-8">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow-sm border-0">
                        
                        <div class="card-header bg-dark text-white text-center">
                            <h6>Finalizar Solicitd de Evidencias</h6>
                        </div>
                        <div class="card-body text-center">
                            
                            <?php 

                            if ($Estado == 'Revisión' || $Estado == 'Verificación' || $Estado == 'En Curso') {
                                
                                    /// Si Todos los Elementos del arreglo Estados_De_Evidencias son "Aceptado" 
                                    if (count(array_unique($Estados_De_Evidencias)) === 1 && end($Estados_De_Evidencias) === 'Aceptado') { ?>
                                        <a href="../../resources/Back/Viaticos/CompletarViatico.php?id=<?php echo $Id_Viatico; ?>&Response=Aceptado"
                                            class="btn btn-primary">Finalizar Recopilación de evidencias</a>
                                        <a href="../../resources/Back/Viaticos/CompletarViatico.php?id=<?php echo $Id_Viatico; ?>&Response=Rechazado"
                                            class="btn btn-danger">Rechazar Viático</a>
                                    <?php } else{
                                        ?>
                                        <button class="btn btn-primary" type="button" disabled>Finalizar Recopilación de evidencias</button>
                                        <a href="../../resources/Back/Viaticos/CompletarViatico.php?id=<?php echo $Id_Viatico; ?>&Response=Rechazado"
                                            class="btn btn-danger">Rechazar Viático</a>
                                        <?php
                                    } 
                            } elseif ($Estado == 'Segunda Revisión' || $Estado == 'Prórroga') {
                                if (count(array_unique($Estados_De_Evidencias)) === 1 && end($Estados_De_Evidencias) === 'Aceptado') { ?>
                                <a href="../../resources/Back/Viaticos/CompletarViatico.php?id=<?php echo $Id_Viatico; ?>&Response=Aceptado"
                                    class="btn btn-primary">Finalizar Segunda Recopilación de evidencias</a>
                                <a href="../../resources/Back/Viaticos/CompletarViatico.php?id=<?php echo $Id_Viatico; ?>&Response=Rechazado"
                                    class="btn btn-danger">Rechazar y Cerrar Viático</a>
                                <?php
                            }
                        }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    

    <!-- Bootstrap JS and dependencies (Popper.js and jQuery) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        // Función para mostrar/ocultar el campo de justificación
        function toggleJustification(selectElement, justificationId) {
            const justificationDiv = document.getElementById(justificationId);
            if (selectElement.value == "2") { // Si se selecciona "Rechazar"
                justificationDiv.style.display = 'block';
            } else {
                justificationDiv.style.display = 'none';
            }
            validateForm();
        }

        // Función para validar si todos los selects tienen valor seleccionado
        function validateForm() {
            const selectControls = document.querySelectorAll('.select-control');
            const submitButton = document.getElementById('submit-button');

            let allSelected = true;
            selectControls.forEach(function (select) {
                if (select.value === "") {
                    allSelected = false;
                }
            });

            // Habilitar o deshabilitar el botón
            submitButton.disabled = !allSelected;
        }
    </script>



</body>

</html>
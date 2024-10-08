<?php
session_start();
require('../../resources/config/db.php');

$Id_Viatico = $_GET['id'];
/// Obtener información del viático
$sql = "SELECT * FROM viaticos WHERE Id = $Id_Viatico";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$Fecha_Salida = $row['Fecha_Salida'];
$Hora_Salida = $row['Hora_Salida'];
$Fecha_Regreso = $row['Fecha_Regreso'];
$Hora_Regreso = $row['Hora_Regreso'];
$Orden_Venta = $row['Orden_Venta'];
$Codigo = $row['Codigo'];
$Nombre_Proyecto = $row['Nombre_Proyecto'];
$Estado = $row['Estado'];

/// Obtener información de los clientes
$sql = "SELECT * FROM clientes WHERE Id_Viatico = $Id_Viatico";
$result = $conn->query($sql);
$clientes = [];
while ($row = $result->fetch_assoc()) {
    $clientes[] = $row;
}


$estado_a_visitar = '';
/// Obtener información de las ciudades
$sql = "SELECT * FROM destino WHERE Id_Viatico = $Id_Viatico";
$result = $conn->query($sql);
$ciudades = [];
while ($row = $result->fetch_assoc()) {
    $ciudades[] = $row['Ciudad'];
    $estado_a_visitar .= $row['Estado'] . ', ';
}
/// Obtener información de los acompañantes
$sql = "SELECT * FROM acompanantes WHERE Id_Viatico = $Id_Viatico";
$result = $conn->query($sql);
$acompanantes = [];
while ($row = $result->fetch_assoc()) {
    $acompanantes[] = $row;
}

// Obtener información de los conceptos
$sql = "SELECT * FROM conceptos WHERE Id_Viatico = $Id_Viatico";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    //echo "<br>----------------------------------<br>";
    //echo "<br>Concepto: " . $row['Concepto'];
    //echo "<br>Monto: " . $row['Monto'];
    //echo "<br>----------------------------------<br>";

    // Ver si el concepto MATERIALES está en la base de datos
    if (trim(strtoupper($row['Concepto'])) == 'MATERIALES') {
        //echo "<br>ENCONTRÉ MATERIALES!<br>";
    }

    // Lógica de tu código
    switch (trim(strtoupper($row['Concepto']))) {
        case 'MATERIALES':
            $MATERIALES = $row['Monto'];
            break;
        case 'GASTOS MÉDICOS':
            $GASTOS_MEDICOS = $row['Monto'];
            break;
        case 'EQUIPOS':
            $EQUIPOS = $row['Monto'];
            break;
        case 'HOSPEDAJE':
            $HOSPEDAJE = $row['Monto'];
            break;
        case 'VUELOS':
            $VUELOS = $row['Monto'];
            break;
        case 'ALIMENTACION':
            $ALIMENTACION = $row['Monto'];
            break;
        case 'TRANSPORTE':
            $TRANSPORTE = $row['Monto'];
            break;
        case 'ESTACIONAMIENTO':
            $ESTACIONAMIENTO = $row['Monto'];
            break;
        case 'GASOLINA':
            $GASOLINA = $row['Monto'];
            break;
        case 'CASETAS':
            $CASETAS = $row['Monto'];
            break;
        default:
            $Nombre_Concepto = $row['Concepto'];
            $Otro = $row['Monto'];
            break;
    }
}


/// iMPRIMIR EL ARREGLO DE CONCEPTOS CON SALTADO DE LINEA ENTRE CADA CONCEPTO
//echo "<pre>";
//print_r($conceptos);
//echo "</pre>";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edicion de Viáticos</title>
    <link rel="shortcut icon" href="/resources/img/logo-icon.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
</head>

<body>
    <?php include '../navbar.php'; ?>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-black text-white text-center">
                        <h5>Registro de solicitud de viático - No. <?php echo $Id_Viatico; ?></h5>
                    </div>
                    <div class="card-body">
                        <form action="/resources/Back/Viaticos/EditViatico.php" method="POST">
                            <!-- Fecha de salida y regreso -->
                            <input type="hidden" name="Id_Viatico" value="<?php echo $Id_Viatico; ?>">
                            <div class="row my-2">
                                <div class="col-md-6 text-center">
                                    <label for="fechaSalida" class="form-label"><strong>Fecha de
                                            Salida:</strong></label>
                                    <input type="date" class="form-control" id="fechaSalida" name="Fecha_Salida"
                                        value="<?php echo $Fecha_Salida ?>">
                                </div>
                                <div class="col-md-6 text-center">
                                    <label for="fechaRegreso" class="form-label"><strong>Fecha de
                                            Regreso:</strong></label>
                                    <input type="date" class="form-control" id="fechaRegreso" name="Fecha_Regreso"
                                        value="<?php echo $Fecha_Regreso ?>">
                                </div>
                            </div>
                            <!-- Hora de salida y regreso -->
                            <div class="row my-4">
                                <div class="col-md-6 text-center">
                                    <label for="horaSalida" class="form-label"><strong>Hora de Salida:</strong></label>
                                    <input type="time" class="form-control" id="horaSalida" name="horaSalida"
                                        value="<?php echo $Hora_Salida ?>">
                                </div>
                                <div class="col-md-6 text-center">
                                    <label for="horaRegreso" class="form-label"><strong>Hora de
                                            Regreso:</strong></label>
                                    <input type="time" class="form-control" id="horaRegreso" name="horaRegreso"
                                        value="<?php echo $Hora_Regreso ?>">
                                </div>
                            </div>
                            <!-- Datos para la creación del FOLIO -->
                            <div class="row my-4">
                                <div class="col-md-6 text-center">
                                    <label for="ordenVenta" class="form-label"><strong>Orden de venta:</strong></label>
                                    <input type="number" placeholder="XXXX-XXXX" class="form-control" id="ordenVenta"
                                        name="ordenVenta" value="<?php echo $Orden_Venta ?>" required>
                                </div>
                                <div class="col-md-6 text-center">
                                    <label for="codigo" class="form-label"><strong>Código:</strong></label>
                                    <div class="input-group">
                                        <select class="form-select form-select-sm codigo-prefix" id="codigoPrefix"
                                            name="codigoPrefix" aria-label="Seleccionar código">
                                            <?php
                                            /// Si los primeros 3 caracteres del codigo son PYE, selected a esa opcion
                                            if (substr($Codigo, 0, 3) === 'PYE') {
                                                echo '<option value="PYE" selected>PYE</option>';
                                                echo '<option value="PYI">PYI</option>';
                                            } else {
                                                echo '<option value="PYE">PYE</option>';
                                                echo '<option value="PYI" selected>PYI</option>';
                                            }

                                            ?>
                                        </select>
                                        <input type="text" class="form-control codigo-input" id="codigo" name="codigo"
                                            placeholder="Ingresar código" value="<?php
                                            /// Imprimir los siguientes caracteres a partir del '-'
                                            echo substr($Codigo, strpos($Codigo, '-') + 1);
                                            ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <label for="nombreProyecto" class="form-label"><strong>Nombre del
                                            proyecto:</strong></label>
                                    <input type="text" placeholder="Ingrese Nombre del proyecto" class="form-control"
                                        onkeyup="javascript:this.value=this.value.toUpperCase();" id="nombreProyecto"
                                        name="nombreProyecto" value="<?php echo $Nombre_Proyecto ?>" required>
                                </div>
                            </div>
                            <hr>

                            <div class="text-center">
                                <label for="clientes" class="form-label text-center"><strong>Clientes a
                                        visitar:</strong></label>
                            </div>

                            <?php
                            // Asegurarse de que hay al menos un cliente en $clientes
                            if (!empty($clientes)) {
                                // Mostrar los clientes existentes en los campos del formulario
                                foreach ($clientes as $index => $cliente) {
                                    ?>
                                    <div class="row mt-3" id="cliente_<?php echo $index + 1; ?>">
                                        <div class="col-md-6 text-center">
                                            <label for="Nombre_Cliente_<?php echo $index + 1; ?>"
                                                class="form-label"><strong>Nombre:</strong></label>
                                            <input type="text" class="form-control"
                                                id="Nombre_Cliente_<?php echo $index + 1; ?>"
                                                name="clientes[<?php echo $index; ?>][nombre]"
                                                value="<?php echo htmlspecialchars($cliente['Nombre']); ?>" required>
                                        </div>
                                        <div class="col-md-6 text-center">
                                            <label for="Motivo_Cliente_<?php echo $index + 1; ?>"
                                                class="form-label"><strong>Motivo:</strong></label>
                                            <select class="form-select motivo-cliente" id="Motivo_Cliente_<?php echo $index + 1; ?>"
                                                name="clientes[<?php echo $index; ?>][motivo]" required>
                                                <option value="">Seleccione una opción</option>
                                                <option value="Hospedaje" <?php echo ($cliente['Motivo'] == 'Hospedaje') ? 'selected' : ''; ?>>Hospedaje</option>
                                                <option value="Vuelos" <?php echo ($cliente['Motivo'] == 'Vuelos') ? 'selected' : ''; ?>>Vuelos</option>
                                                <option value="Alimentación" <?php echo ($cliente['Motivo'] == 'Alimentación') ? 'selected' : ''; ?>>Alimentación</option>
                                                <option value="Transporte" <?php echo ($cliente['Motivo'] == 'Transporte') ? 'selected' : ''; ?>>Transporte</option>
                                                <option value="Estacionamiento" <?php echo ($cliente['Motivo'] == 'Estacionamiento') ? 'selected' : ''; ?>>
                                                    Estacionamiento</option>
                                                <option value="Gasolina" <?php echo ($cliente['Motivo'] == 'Gasolina') ? 'selected' : ''; ?>>Gasolina</option>
                                                <option value="Casetas" <?php echo ($cliente['Motivo'] == 'Casetas') ? 'selected' : ''; ?>>Casetas</option>
                                                <option value="Levantamiento" <?php echo ($cliente['Motivo'] == 'Levantamiento') ? 'selected' : ''; ?>>Levantamiento</option>
                                                <option value="Soporte Técnico" <?php echo ($cliente['Motivo'] == 'Soporte Técnico') ? 'selected' : ''; ?>>Soporte Técnico</option>
                                                <option value="Servicio" <?php echo ($cliente['Motivo'] == 'Servicio') ? 'selected' : ''; ?>>Servicio</option>
                                                <option value="Puesto en marcha" <?php echo ($cliente['Motivo'] == 'Puesto en marcha') ? 'selected' : ''; ?>>Puesto en marcha</option>
                                                <option value="Ejecución" <?php echo ($cliente['Motivo'] == 'Ejecución') ? 'selected' : ''; ?>>Ejecución</option>
                                                <option value="Garantía" <?php echo ($cliente['Motivo'] == 'Garantía') ? 'selected' : ''; ?>>Garantía</option>
                                                <option value="Otro" <?php echo ($cliente['Motivo'] == 'Otro') ? 'selected' : ''; ?>>Otro</option>
                                            </select>
                                            <!-- Campo de texto para "Otro" inicialmente oculto -->
                                            <input type="text" placeholder="Especifique otro motivo"
                                                class="form-control mt-3 otro-motivo-input" id="Otro_Cliente_1"
                                                name="clientes[0][otro_motivo]" style="display:none;"
                                                oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                        <div class="col-md-6 text-center">
                                            <label for="Fecha_Cliente_<?php echo $index + 1; ?>"
                                                class="form-label"><strong>Fecha:</strong></label>
                                            <input type="date" class="form-control cliente-fecha"
                                                id="Fecha_Cliente_<?php echo $index + 1; ?>"
                                                name="clientes[<?php echo $index; ?>][fecha]"
                                                value="<?php echo isset($cliente['Fecha']) ? date('Y-m-d', strtotime($cliente['Fecha'])) : ''; ?>"
                                                required>
                                        </div>
                                        <div class="col-md-6 text-center mt-4">
                                            <?php if ($index + 1 > 1) { ?>
                                                <button type="button" class="btn btn-danger eliminar-cliente"
                                                    data-cliente-id="<?php echo $index + 1; ?>">Eliminar</button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>

                            <!-- Botón para agregar cliente -->
                            <div class="col-md-6 text-center mt-4">
                                <button type="button" class="btn btn-primary w-100" id="agregarClienteBtn">+ Agregar
                                    Cliente</button>
                            </div>

                            <!-- Contenedor para clientes adicionales -->
                            <div id="clientesAdicionales" class="mt-4"></div>
                            <hr>
                            <div class="text-center">
                                <label for="Destino" class="form-label text-center"><strong>Destino:</strong></label>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <label for="destino" class="form-label"><strong>Estado:</strong></label>
                                    <input type="text" placeholder="Ingrese el estado a visitar..." class="form-control"
                                        id="destino" name="destino" value="<?php echo $estado_a_visitar; ?>" required>
                                </div>
                            </div>

                            <!-- Botón para agregar ciudades -->
                            <div class="row mt-3">
                                <div class="col-md-12 text-center">
                                    <button type="button" class="btn btn-primary w-100" id="agregarCiudadBtn">Agregar
                                        Ciudad</button>
                                </div>
                            </div>

                            <!-- Contenedor para las ciudades adicionales -->
                            <div id="ciudadesAdicionales"></div>
                            <hr>

                            <?php
                            // Obtenemos el primer acompañante para el input inicial
                            $primerAcompanante = isset($acompanantes[0]['Nombre']) ? $acompanantes[0]['Nombre'] : '';
                            ?>

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <label for="destino" class="form-label"><strong>Acompañantes:</strong></label>
                                    <input type="text" class="form-control" id="acomp_1"
                                        placeholder="Ingrese nombre de acompañante(s)" name="acomp_1"
                                        value="<?php echo $primerAcompanante; ?>" required>
                                </div>
                            </div>

                            <div class="row" id="acomp-fields">
                                <!-- Aquí se insertarán dinámicamente los acompañantes adicionales -->
                                <?php
                                // Empezamos desde el segundo acompañante, ya que el primero está en otro input
                                if (count($acompanantes) > 1) {
                                    foreach (array_slice($acompanantes, 1) as $index => $acompanante) {
                                        $contador = $index + 2; // Los acompañantes empiezan desde el 2 en adelante
                                        echo "
                                    <div class='col-md-6 text-center mt-2' id='acomp-{$contador}'>
                                        <label for='acomp_{$contador}' class='form-label'>Acompañante {$contador}:</label>
                                        <input type='text' class='form-control' id='acomp_{$contador}' name='acomp_{$contador}' value='{$acompanante['Nombre']}' required>
                                    </div>
                                    ";
                                    }
                                }
                                ?>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12 text-center">
                                    <button type="button" class="btn btn-primary" onclick="addField()">Agregar
                                        Acompañante</button>
                                    <button type="button" class="btn btn-danger" onclick="removeFields()">Eliminar
                                        Acompañante</button>
                                </div>
                            </div>
                            <hr>
                            <div class="row mt-4">
                                <div class="col-md-12 text-center">
                                    <label for="Conceptos" class="form-label"><strong>Conceptos:</strong></label>
                                </div>
                                <hr>
                                <div class="col-md-6 text-center">
                                    <label for="MATERIALES" class="form-label"><strong>MATERIALES:</strong></label>
                                    <input type="number" class="form-control" id="MATERIALES" name="MATERIALES"
                                        value="<?php echo $MATERIALES; ?>" required>
                                </div>
                                <div class="col-md-6 text-center">
                                    <label for="GASTOS_MÉDICOS" class="form-label"><strong>GASTOS
                                            MÉDICOS:</strong></label>
                                    <input type="number" class="form-control" id="GASTOS_MEDICOS" name="GASTOS_MEDICOS"
                                        value="<?php echo $GASTOS_MEDICOS; ?>" required>
                                </div>
                                <div class="col-md-6 text-center">
                                    <label for="EQUIPOS" class="form-label"><strong>EQUIPOS:</strong></label>
                                    <input type="number" class="form-control" id="EQUIPOS" name="EQUIPOS"
                                        value="<?php echo $EQUIPOS; ?>" required>
                                </div>
                                <div class="col-md-6 text-center">
                                    <label for="HOSPEDAJE" class="form-label"><strong>HOSPEDAJE:</strong></label>
                                    <input type="number" class="form-control" id="HOSPEDAJE" name="HOSPEDAJE"
                                        value="<?php echo $HOSPEDAJE; ?>" required>
                                </div>
                                <div class="col-md-6 text-center">
                                    <label for="VUELOS" class="form-label"><strong>VUELOS:</strong></label>
                                    <input type="number" class="form-control" id="VUELOS" name="VUELOS"
                                        value="<?php echo $VUELOS; ?>" required>
                                </div>
                                <div class="col-md-6 text-center">
                                    <label for="ALIMENTACIÓN"
                                        class="form-label"></label><strong>ALIMENTACIÓN:</strong></label>
                                    <input type="number" class="form-control" id="ALIMENTACIÓN" name="ALIMENTACION"
                                        value="<?php echo $ALIMENTACION; ?>" required>
                                </div>
                                <div class="col-md-6 text-center">
                                    <label for="TRANSPORTE" class="form-label"><strong>TRANSPORTE:</strong></label>
                                    <input type="number" class="form-control" id="TRANSPORTE" name="TRANSPORTE"
                                        value="<?php echo $TRANSPORTE; ?>" required>
                                </div>
                                <div class="col-md-6 text-center">
                                    <label for="ESTACIONAMIENTO"
                                        class="form-label"><strong>ESTACIONAMIENTO:</strong></label>
                                    <input type="number" class="form-control" id="ESTACIONAMIENTO"
                                        name="ESTACIONAMIENTO" value="<?php echo $ESTACIONAMIENTO; ?>" required>
                                </div>
                                <div class="col-md-6 text-center">
                                    <label for="GASOLINA" class="form-label"><strong>GASOLINA:</strong></label>
                                    <input type="number" class="form-control" id="GASOLINA" name="GASOLINA"
                                        value="<?php echo $GASOLINA; ?>" required>
                                </div>
                                <div class="col-md-6 text-center">
                                    <label for="CASETAS" class="form-label"><strong>CASETAS:</strong></label>
                                    <input type="number" class="form-control" id="CASETAS" name="CASETAS"
                                        value="<?php echo $CASETAS; ?>" required>
                                </div>

                                <div class="col-md-6 text-center">
                                    <label for="Nombre_Concepto"
                                        class="form-label"><strong><?php echo $Nombre_Concepto; ?>:</strong></label>
                                    <input type="hidden" name="Nombre_Concepto" value="<?php echo $Nombre_Concepto; ?>">
                                    <input type="number" class="form-control" id="Nombre_Concepto"
                                        name="Nombre_Concepto_value" value="<?php echo $Otro; ?>" required>
                                </div>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-success w-100">Finalizar Solicitud</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS and dependencies (Popper.js and jQuery) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts para gestionar fechas y horas -->
    <script>
        const fechaSalida = document.getElementById('fechaSalida');
        const fechaRegreso = document.getElementById('fechaRegreso');

        // ------------------Diferencia de dias ---------------------
        // Función para calcular la diferencia de días
        function calcularDiferenciaDias() {
            const fechaSalidaValue = fechaSalida.value;
            const fechaRegresoValue = fechaRegreso.value;
            // Verificar que ambas fechas estén seleccionadas
            if (fechaSalidaValue && fechaRegresoValue) {
                const fechaSalidaDate = new Date(fechaSalidaValue);
                const fechaRegresoDate = new Date(fechaRegresoValue);

                // Calcular la diferencia en milisegundos y convertir a días
                const diferenciaMilisegundos = fechaRegresoDate - fechaSalidaDate;
                const diferenciaDias = (diferenciaMilisegundos / (1000 * 60 * 60 * 24)) + 1;  // Sumar 1 para incluir el día de salida
                diasDiferencia.textContent = `Días de diferencia: ${diferenciaDias}`;
                return diferenciaDias;
            } else {
                // Si no se han seleccionado ambas fechas
                diasDiferencia.textContent = 'Días de diferencia: ';
                return 0;
            }
        }

        // Función para establecer la fecha mínima de salida (el día siguiente a hoy)
        function setMinFechaSalida() {
            const today = new Date();  // Obtener la fecha actual
            today.setDate(today.getDate() + 1);  // Incrementar en un día
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');  // Meses en JavaScript van de 0 a 11
            const dd = String(today.getDate()).padStart(2, '0');
            const minDate = `${yyyy}-${mm}-${dd}`;  // Formatear la fecha en yyyy-mm-dd

            fechaSalida.min = minDate;  // Establecer la fecha mínima en el input de fecha de salida
        }

        // Función para establecer la fecha mínima de regreso (después de la fecha de salida)
        function setMinFechaRegreso() {
            const selectedFechaSalida = fechaSalida.value;  // Obtener la fecha seleccionada en el input de fecha de salida
            if (selectedFechaSalida) {
                fechaRegreso.min = selectedFechaSalida;  // Establecer la fecha mínima en el input de fecha de regreso
            }
        }

        // Añadir un event listener para actualizar la fecha mínima de regreso al cambiar la fecha de salida
        fechaSalida.addEventListener('change', setMinFechaRegreso);

        // Establecer la fecha mínima de salida al cargar la página
        setMinFechaSalida();
        setMinFechaRegreso();


        // Añadir event listeners para actualizar la fecha mínima de regreso y calcular la diferencia en días al cambiar las fechas
        fechaSalida.addEventListener('change', () => {
            setMinFechaRegreso();
            calcularDiferenciaDias();
        });
        fechaRegreso.addEventListener('change', calcularDiferenciaDias);
    </script>

    <!-- Scripts para agregar y eliminar clientes -->
    <script>document.addEventListener('DOMContentLoaded', function () {
            let clienteCounter = <?php echo count($clientes); ?>;
            const maxClientes = 3; // Ajusta el límite si es necesario
            const agregarClienteBtn = document.getElementById('agregarClienteBtn');
            const clientesAdicionales = document.getElementById('clientesAdicionales');

            // Evento para agregar un nuevo cliente
            agregarClienteBtn.addEventListener('click', function () {
                if (clienteCounter < maxClientes) {
                    clienteCounter++;

                    const nuevoCliente = document.createElement('div');
                    nuevoCliente.classList.add('row', 'mt-3');
                    nuevoCliente.setAttribute('id', `cliente_${clienteCounter}`);
                    // Asegúrate de que el valor de la opción coincida con el valor almacenado en la base de datos
                    nuevoCliente.innerHTML = `
                <div class="col-md-6 text-center">
                    <label for="Nombre_Cliente_${clienteCounter}" class="form-label">Nombre:</label>
                    <input type="text" class="form-control" id="Nombre_Cliente_${clienteCounter}" name="clientes[${clienteCounter - 1}][nombre]" required>
                </div>
                <div class="col-md-6 text-center">
                    <label for="Motivo_Cliente_${clienteCounter}" class="form-label">Motivo:</label>
                    <select class="form-select motivo-cliente" id="Motivo_Cliente_${clienteCounter}" name="clientes[${clienteCounter - 1}][motivo]" required>
                    <option value="">Seleccione una opción</option>
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
                    <input type="text" placeholder="Especifique otro motivo" class="form-control mt-3 otro-motivo-input" id="Otro_Cliente_${clienteCounter}" name="clientes[${clienteCounter - 1}][otro_motivo]" style="display:none;" oninput="this.value = this.value.toUpperCase()">
                </div>
                <div class="col-md-6 text-center">
                    <label for="Fecha_Cliente_${clienteCounter}" class="form-label">Fecha:</label>
                    <input type="date" class="form-control cliente-fecha" id="Fecha_Cliente_${clienteCounter}" name="clientes[${clienteCounter - 1}][fecha]" required>
                </div>
                <div class="col-md-6 text-center mt-4">
                    <button type="button" class="btn btn-danger eliminar-cliente" data-cliente-id="${clienteCounter}">Eliminar</button>
                </div>
            `;

                    clientesAdicionales.appendChild(nuevoCliente);

                    // Deshabilitar el botón de agregar cuando se alcance el límite
                    if (clienteCounter === maxClientes) {
                        agregarClienteBtn.disabled = true;
                    }
                }
            });

            // Evento de delegación para eliminar un cliente específico
            document.addEventListener('click', function (event) {
                if (event.target.classList.contains('eliminar-cliente')) {
                    const clienteId = event.target.getAttribute('data-cliente-id');
                    const clienteDiv = document.getElementById(`cliente_${clienteId}`);
                    clienteDiv.remove();

                    clienteCounter--;
                    agregarClienteBtn.disabled = false;
                }
            });
        });

         // Mostrar input de "Otro" cuando se selecciona la opción "Otro"
         document.addEventListener('change', function (event) {
                if (event.target.classList.contains('motivo-cliente')) {
                    const otroInput = event.target.nextElementSibling;
                    if (event.target.value === "Otro") {
                        otroInput.style.display = 'block';
                        otroInput.required = true; // Hacerlo obligatorio
                    } else {
                        otroInput.style.display = 'none';
                        otroInput.required = false; // No obligatorio si no es "Otro"
                    }
                }
            });



    </script>

    <!-- Scripts para agregar y eliminar ciudades -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let ciudadCounter = 0;
            const maxCiudades = 3;
            const agregarCiudadBtn = document.getElementById('agregarCiudadBtn');
            const ciudadesAdicionales = document.getElementById('ciudadesAdicionales');

            // Aquí agregamos las ciudades guardadas en PHP
            <?php
            // Recorremos las ciudades guardadas en el arreglo y las mostramos en los inputs
            if (!empty($ciudades)) {
                foreach ($ciudades as $index => $ciudad) {
                    echo "agregarCiudadDinamica('{$ciudad['Ciudad']}');";

                }
            }
            ?>

            // Función para agregar una nueva ciudad de manera dinámica
            function agregarCiudadDinamica(ciudadNombre = '') {
                if (ciudadCounter < maxCiudades) {
                    ciudadCounter++;

                    const nuevaCiudad = document.createElement('div');
                    nuevaCiudad.classList.add('row', 'mt-3');
                    nuevaCiudad.setAttribute('id', `ciudad_${ciudadCounter}`);
                    nuevaCiudad.innerHTML = `
                <div class="col-md-6 text-center">
                    <label for="Nombre_Ciudad_${ciudadCounter}" class="form-label">Ciudad:</label>
                    <input type="text" class="form-control" id="Nombre_Ciudad_${ciudadCounter}" name="ciudades[${ciudadCounter - 1}][nombre]" value="${ciudadNombre}" required>
                </div>
                <div class="col-md-6 text-center mt-4">
                    <button type="button" class="btn btn-danger eliminar-ciudad" data-ciudad-id="${ciudadCounter}">Eliminar</button>
                </div>
            `;

                    ciudadesAdicionales.appendChild(nuevaCiudad);

                    if (ciudadCounter === maxCiudades) {
                        agregarCiudadBtn.disabled = true;
                    }
                }
            }

            // Evento para agregar una nueva ciudad con el botón
            agregarCiudadBtn.addEventListener('click', function () {
                agregarCiudadDinamica();
            });

            // Evento para eliminar una ciudad
            ciudadesAdicionales.addEventListener('click', function (event) {
                if (event.target.classList.contains('eliminar-ciudad')) {
                    const ciudadId = event.target.getAttribute('data-ciudad-id');
                    const ciudadDiv = document.getElementById(`ciudad_${ciudadId}`);
                    ciudadDiv.remove();

                    ciudadCounter--;
                    agregarCiudadBtn.disabled = false;
                }
            });
        });
    </script>

    <!-- Scripts para agregar y eliminar acompañantes -->
    <script>
        // Contador inicial basado en los acompañantes ya cargados
        let counter = <?php echo count($acompanantes); ?>;
        const maxFields = 6;

        function addField() {
            if (counter < maxFields) {
                counter++;
                const newField = `
                <div class="col-md-6 text-center mt-2" id="acomp-${counter}">
                    <label for="acomp_${counter}" class="form-label">Acompañante ${counter}:</label>
                    <input type="text" class="form-control" id="acomp_${counter}" name="acomp_${counter}" required>
                </div>
            `;
                document.getElementById('acomp-fields').insertAdjacentHTML('beforeend', newField);
            } else {
                alert('Solo puedes agregar hasta 6 acompañantes.');
            }
        }

        function removeFields() {
            if (counter > 1) {
                document.getElementById(`acomp-${counter}`).remove();
                counter--;
            }
        }
    </script>

</body>

</html>
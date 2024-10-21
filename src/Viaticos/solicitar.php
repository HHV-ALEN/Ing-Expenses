<?php
session_start();
if (!isset($_SESSION['ID'])) {
    // La sesión ha caducado o el usuario no ha iniciado sesión
    session_unset(); // Elimina todas las variables de sesión
    session_destroy(); // Destruye la sesión

    header('Location: ../../index.php'); // Redirige al formulario de inicio de sesión
    exit();
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Formulario de Solicitud</title>
    <link rel="shortcut icon" href="/resources/img/logo-icon.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
        }

        .bg {
            background-image: url('../../resources/img/FONDONEGRO.png');
            height: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 20px;
        }

        .card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
        }

        .card-header-custom {
            background-color: #3b4ba1;
            color: white;
        }

        @media (max-width: 991.98px) {
            .navbar-nav .nav-link {
                text-align: center;
                border-bottom: 1px solid #e9ecef;
                padding: 10px 0;
                width: 100%;
            }

            .navbar-nav .nav-link:last-child {
                border-bottom: none;
            }

            .navbar-nav .dropdown-divider {
                display: none;
            }

            .navbar-collapse {
                display: flex;
                flex-direction: column;
                align-items: center;
                width: 100%;
            }

            .navbar-nav {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <!-- Inicio de la barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a href="../Users/index.php"><img src="../../resources/img/Alen.png" alt="ALEN Viáticos" class="img-fluid"
                    style="padding: 5px; height: 47px;"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <?php
                    if ($_SESSION['Position'] == 'Admin' || $_SESSION['Position'] == 'Control') {
                        echo '
                        <li class="nav-item">
                            <a class="nav-link" href="../Viaticos/solicitar.php">Solicitar Viáticos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Viaticos/ListadoViaticos.php">Listado Viáticos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Viaticos/misViaticos.php">Mis Viáticos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Viaticos/reembolsar.php">Solicitar Reembolso</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Control/listadoReembolsos.php">Reembolsos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Viaticos/verificacionEvidencias.php">Verificación de Evidencias</a>
                        </li>';
                    } elseif ($_SESSION['Position'] == 'Empleado') {
                        echo '
                        <li class="nav-item">
                            <a class="nav-link" href="../Viaticos/solicitar.php">Solicitar Viáticos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Viaticos/misViaticos.php">Mis Viáticos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Viaticos/reembolsar.php">Solicitar Reembolso</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Viaticos/misReembolsos.php">Mis Reembolsos</a>
                        </li>';
                    } elseif ($_SESSION['Position'] == 'Gerente') {
                        echo '
                        <li class="nav-item">
                            <a class="nav-link" href="../Viaticos/solicitar.php">Solicitar Viáticos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Viaticos/misViaticos.php">Mis Viáticos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Viaticos/reembolsar.php">Solicitar Reembolso</a>
                        </li>
                        
                        <li class="nav-item ">
                            <a class="nav-link" href="../Viaticos/misReembolsos.php">Mis Reembolsos</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                A mi cargo
                            </a>
                            <ul class="dropdown-menu text-center" aria-labelledby="navbarDropdown">
                                <li class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="../Viaticos/ViaticosACargo.php">Solicitudes</a></li>
                                <li class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="../Viaticos/enEvidencia.php">Evidencias</a></li>
                                <li class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="../Viaticos/reembolsosACargo.php">Reembolsos</a></li>
                                <li class="dropdown-divider"></li>
                            </ul>
                        </li>';
                    }
                    ?>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../perfil.php"><?php echo $_SESSION['Name'] ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../index.php">Salir</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <br>
    <!-- Fin de la barra de navegación -->

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-custom">
                <div class="card-header card-header-custom">
                    <h5 class="card-title">Solicitud de Viáticos</h5>
                </div>
                <div class="card-body">


                    <form action="../../resources/Back/Viaticos/añadir.php" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="FechaSalida" class="form-label">Fecha de Salida:</label>
                                <input type="date" class="form-control" id="FechaSalida" name="FechaSalida" required>
                                <br>
                                <label for="HoraSalida" class="form-label">Hora de Salida:</label>
                                <input type="time" class="form-control" id="HoraSalida" name="HoraSalida" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="FechaRegreso" class="form-label">Fecha de Regreso:</label>
                                <input type="date" class="form-control" id="FechaRegreso" name="FechaRegreso" required>
                                <br>
                                <label for="HoraSalida" class="form-label">Hora de Regreso:</label>
                                <input type="time" class="form-control" id="HoraRegreso" name="HoraRegreso" required>
                            </div>
                            <p id="diasDiferencia">Días Solicitados: </p>
                        </div>
                        <hr>

                        <div class="row">
                            <div id="clientesContainer">
                                <!-- Aquí se agregarán dinámicamente los campos de cliente -->
                            </div>

                            <button type="button" id="agregarCliente" class="btn btn-primary">Agregar Cliente</button>
                        </div>

                        <hr>
                        <div class="row mb-3" id="ciudad1-container">
                            <div class="col-sm-6">
                                <lablel for="Estado" class="col-form-label">Estado:</lablel>
                                <input type="text" class="form-control" id="Estado" name="Estado"
                                    placeholder="Estado a visitar..." required>
                                <label for="Ciudad1" class="col-form-label">Ciudad:</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="Ciudad1" name="Ciudad[]"
                                    placeholder="Nombre de la ciudad a visitar..." required>
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-danger btn-remove"
                                    onclick="eliminarCiudad(this)">Eliminar</button>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div id="ciudades-adicionales"></div>
                            <div class="col-sm-6">
                                <button type="button" id="agregarCiudad" class="btn btn-outline-primary">+
                                    Agregar
                                    Ciudad</button>
                                <div id="emailHelp" class="form-text">Máx. 3 Ciudades</div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <div id="contadorInputs">Número de Personas: 1</div>
                                <br>
                                <button type="button" id="agregarAcompañante" class="btn btn-outline-primary"
                                    onclick="agregarInput()">Agregar
                                    Acompañante</button>
                                <div id="emailHelp" class="form-text">Máx. 6 Acompañanates</div>
                                <br>
                            </div>

                            <div class="col-lg-6 mb-2">
                                <br>
                                <div id="contenedorInputs">
                                    <!-- Aquí se agregarán los inputs dinámicamente -->
                                </div>
                            </div>

                        </div>
                        <hr>

                        <?php
                        if ($_SESSION['Position'] === 'Gerente') {
                            ?>
                            <div class="row">
                                <p><strong>Monto Solicitado: </strong></p>
                                <div class="col-md-6 mb-3">
                                    <label for="Hospedaje" class="col-form-label">Hospedaje:</label>
                                    <input type="number" class="form-control suma-input" id="Hospedaje" name="Hospedaje"
                                        min="0" value="0">

                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="Gasolina" class="col-form-label">Gasolina:</label>
                                    <input type="number" class="form-control suma-input" id="Gasolina" name="Gasolina"
                                    value="0" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Casetas" class="col-form-label">Casetas:</label>
                                    <input type="number" class="form-control suma-input" id="Casetas" name="Casetas" value="0"
                                        required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="Alimentos" class="col-form-label">Alimentos:</label>
                                    <input type="number" class="form-control suma-input" id="Alimentacion"
                                    value="0" name="Alimentacion" 
                                         required
                                        data-bs-toggle="popover" data-bs-trigger="manual"
                                       >
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Vuelos" class="col-form-label">Vuelos:</label>
                                    <input type="number" class="form-control suma-input" id="Vuelos" name="Vuelos" value="0"
                                        placeholder="Monto de Vuelos..." required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="Transporte" class="col-form-label">Transporte:</label>
                                    <input type="number" class="form-control suma-input" id="Transporte" name="Transporte" value="0"
                                        placeholder="Monto de Transporte..." required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Estacionamiento" class="col-form-label">Estacionamiento:</label>
                                    <input type="number" class="form-control suma-input" id="Estacionamiento" name="Estacionamiento" value="0"
                                        placeholder="Monto de Estacionamiento..." required>
                                </div>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="row">
                                <p><strong>Monto Solicitado: </strong></p>
                                <div class="col-md-6 mb-3">
                                    <label for="Hospedaje" class="col-form-label">Hospedaje:</label>
                                    <input type="number" class="form-control suma-input" id="Hospedaje" name="Hospedaje"
                                        min="0" value="0">

                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="Gasolina" class="col-form-label">Gasolina:</label>
                                    <input type="number" class="form-control suma-input" id="Gasolina" name="Gasolina"
                                    value="0" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Acompañantes" class="col-form-label">Casetas:</label>
                                    <input type="number" class="form-control suma-input" id="Casetas" name="Casetas"
                                    value="0" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="Acompañantes" class="col-form-label">Alimento:</label>
                                    <input type="number" class="form-control suma-input" id="Alimentacion"
                                        placeholder="Monto de Alimentación..." name="Alimentacion" required value="0"
                                        data-bs-toggle="popover" data-bs-trigger="manual">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Vuelos" class="col-form-label">Vuelos:</label>
                                    <input type="number" class="form-control suma-input" id="Vuelos" name="Vuelos" value="0"
                                        placeholder="Monto de Vuelos..." required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="Transporte" class="col-form-label">Transporte:</label>
                                    <input type="number" class="form-control suma-input" id="Transporte" name="Transporte" value="0"
                                        placeholder="Monto de Transporte..." required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="Estacionamiento" class="col-form-label">Estacionamiento:</label>
                                    <input type="number" class="form-control suma-input" id="Estacionamiento" name="Estacionamiento" value="0"
                                        placeholder="Monto de Estacionamiento..." required>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <hr>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="TotalDeViaticos" class="col-form-label">Monto Total:</label>
                                <input type="text" class="form-control" id="TotalDeViaticos" name="TotalDeViaticos"
                                    readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="TotalDeViaticos" class="col-form-label">Completar Solicitud:</label><br>
                                <button type="submit" class="btn btn-success">Enviar</button>
                            </div>
                        </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    </div>
    <br>

    <!-- Bootstrap JS and dependencies (Popper.js and jQuery) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Obtener Fechas de los elementos del DOM
        const fechaSalida = document.getElementById('FechaSalida');
        const fechaRegreso = document.getElementById('FechaRegreso');
        const diasDiferencia = document.getElementById('diasDiferencia');
        const inputs = document.querySelectorAll('.suma-input');
        const totalDeViaticos = document.getElementById('TotalDeViaticos');
        const hospedajeInput = document.getElementById('Hospedaje');
        const alimentacionInput = document.getElementById('Alimentacion');
        const totalDeViaticosAcompañantes = document.getElementById('TotalDeViaticosXAcompañantes');
        const contInputs = document.getElementById('contadorInputs');
        const TotalViaticosxDiaxAcompanantes = document.getElementById('TotalViaticosxDiaxAcompanantes');
        let contador = 0;
        
        let clienteCounter = 0;
        const maxClientes = 3;
        const fechasOcupadas = new Set();
        const clientesIds = [];

        const clientesContainer = document.getElementById('clientesContainer');
        const agregarClienteBtn = document.getElementById('agregarCliente');

        function calcularFechasDisponibles() {
            const fechaSalidaValue = fechaSalida.value;
            const fechaRegresoValue = fechaRegreso.value;
            let fechasDisponibles = [];

            if (fechaSalidaValue && fechaRegresoValue) {
                const fechaSalidaDate = new Date(fechaSalidaValue);
                const fechaRegresoDate = new Date(fechaRegresoValue);

                const diferenciaMilisegundos = fechaRegresoDate - fechaSalidaDate;
                const diferenciaDias = (diferenciaMilisegundos / (1000 * 60 * 60 * 24)) + 1;

                if (diferenciaDias >= 0) {
                    diasDiferencia.textContent = `Días Solicitados: ${diferenciaDias}`;

                    for (let i = 0; i < diferenciaDias; i++) {
                        const fecha = new Date(fechaSalidaDate);
                        fecha.setDate(fecha.getDate() + i);
                        fechasDisponibles.push(fecha.toISOString().split('T')[0]);
                    }
                } else {
                    diasDiferencia.textContent = 'Error: La fecha de regreso debe ser posterior o igual a la fecha de salida';
                }
            }
            return fechasDisponibles;
        }

        function MostrarFechas() {
            calcularFechasDisponibles();
            actualizarFechasDisponiblesParaClientes();
        }

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

        

        /// -------------- Gestion de Clientes -------------------

        function actualizarFechasDisponiblesParaClientes() {
            const fechasDisponibles = calcularFechasDisponibles();

            clientesContainer.querySelectorAll('.fechaVisita').forEach(select => {
                const selectedValue = select.value;
                select.innerHTML = '<option value="">Fechas Disponibles:</option>';
                fechasDisponibles.forEach(fecha => {
                    const isDisabled = fechasOcupadas.has(fecha) && fecha !== selectedValue;
                    const option = document.createElement('option');
                    option.value = fecha;
                    option.textContent = fecha;
                    if (isDisabled) {
                        option.disabled = true;
                    }
                    select.appendChild(option);
                });

                if (selectedValue) {
                    select.value = selectedValue;
                }
            });
        }

        function agregarCliente() {
            if (clientesIds.length >= maxClientes) {
                return;
            }

            clienteCounter++;
            const clienteId = `cliente${clienteCounter}`;
            clientesIds.push(clienteId);

            const clienteDiv = document.createElement('div');
            clienteDiv.className = 'row mb-3';
            clienteDiv.id = clienteId;
            clienteDiv.innerHTML = `
                <div class="col-md-6 mb-3">
                    <label for="Cliente${clienteCounter}" class="form-label">Nombre Del cliente:</label>
                    <input type="text" class="form-control" id="Cliente${clienteCounter}" name="Cliente${clienteCounter}" required>
                    <br>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="Motivo${clienteCounter}" class="form-label">Motivo de Visita:</label>
                    <select class="form-select" id="Motivo${clienteCounter}" name="Motivo${clienteCounter}" required>
                        <option value="Visita">Visita</option>
                        <option value="Capacitacion">Capacitación</option>
                        <option value="Reunion">Reunión</option>
                        <option value="Otro">Otro</option>
                    </select>
                    <br>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="FechaDeVisita${clienteCounter}" class="form-label">Fecha de Visita:</label>
                    <select class="form-select fechaVisita" id="FechaDeVisita${clienteCounter}" name="FechaDeVisita${clienteCounter}" required>
                        <option value="">Fechas Disponibles:</option>
                    </select>
                    <br>
                </div>
                <div class="col-md-6 mb-3">
                    <button type="button" class="btn btn-danger" onclick="eliminarCliente('${clienteId}')">Eliminar Cliente</button>
                </div>
            `;

            clientesContainer.appendChild(clienteDiv);

            clienteDiv.querySelector('.fechaVisita').addEventListener('change', function() {
                const fechaSeleccionada = this.value;
                if (fechaSeleccionada) {
                    fechasOcupadas.add(fechaSeleccionada);
                } else {
                    fechasOcupadas.delete(fechaSeleccionada);
                }
                actualizarFechasDisponiblesParaClientes();
            });

            actualizarFechasDisponiblesParaClientes();

            if (clientesIds.length >= maxClientes) {
                agregarClienteBtn.disabled = true;
            }
        }

        function eliminarCliente(id) {
            const clienteDiv = document.getElementById(id);
            const fechaVisitaSelect = clienteDiv.querySelector('.fechaVisita');
            const fechaSeleccionada = fechaVisitaSelect.value;

            if (fechaSeleccionada) {
                fechasOcupadas.delete(fechaSeleccionada);
            }

            clienteDiv.remove();
            const index = clientesIds.indexOf(id);
            if (index > -1) {
                clientesIds.splice(index, 1);
            }

            actualizarFechasDisponiblesParaClientes();

            if (clientesIds.length < maxClientes) {
                agregarClienteBtn.disabled = false;
            }
        }

        fechaSalida.addEventListener('change', MostrarFechas);
        fechaRegreso.addEventListener('change', MostrarFechas);
        agregarClienteBtn.addEventListener('click', agregarCliente);

        //// ------------------------- Gestion de ciudades ----------------------------
        // Gestion de ciudades 
        document.addEventListener('DOMContentLoaded', function () {
            const maxCiudades = 3;
            let contadorCiudades = 1;
            const agregarCiudadBtn = document.getElementById('agregarCiudad');
            const agregarAcompañanteBtn = document.getElementById('agregarAcompañante');
            const ciudadesAdicionales = document.getElementById('ciudades-adicionales');


            // Agregar Ciudad
            agregarCiudadBtn.addEventListener('click', function () {
                if (contadorCiudades < maxCiudades) {
                    contadorCiudades++;
                    const nuevaCiudadDiv = document.createElement('div');
                    nuevaCiudadDiv.className = 'row mb-3';
                    nuevaCiudadDiv.id = `ciudad${contadorCiudades}-container`;
                    nuevaCiudadDiv.innerHTML = `
                        <div class="col-sm-4">
                            <label for="Ciudad${contadorCiudades}" class="col-form-label">Ciudad Extra:</label>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="Ciudad${contadorCiudades}" name="Ciudad[]" required>
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-danger btn-remove" onclick="eliminarCiudad(this)">Eliminar</button>
                        </div>`;
                    /// Agregar la nueva ciudad al contenedor
                    ciudadesAdicionales.appendChild(nuevaCiudadDiv);
                    /// Si se llega al maximo de ciudades, deshabilitar el boton
                    if (contadorCiudades === maxCiudades) {
                        agregarCiudadBtn.disabled = true;
                    }
                }
            });


            // Eliminar Ciudad
            window.eliminarCiudad = function (btn) {
                const ciudadContainer = btn.closest('.row');
                ciudadContainer.remove();
                contadorCiudades--;
                if (contadorCiudades < maxCiudades) {
                    agregarCiudadBtn.disabled = false;
                }
            };
        });

        /// --------------------------------------------------------------------------------------------
        
        function SumarConceptos(){
            let total = 0;
            inputs.forEach(input => {
                const value = parseFloat(input.value) || 0;
                total += value;
            });
            totalDeViaticos.value = total;
        }

         // Añadir event listeners para calcular el monto total al cambiar los valores de los inputs
         inputs.forEach(input => {
            input.addEventListener('input', SumarConceptos);
        });

        SumarConceptos();



        // Agregar input de acompañantes
        function agregarInput() {
            if (contador >= 6) {
                alert('No puedes agregar más de 6 acompañantes.');
                return;
            }

            const contenedorInputs = document.getElementById('contenedorInputs');
            const nuevoInput = document.createElement('input');
            nuevoInput.type = 'text';
            nuevoInput.className = 'form-control';
            nuevoInput.name = 'Acompanantes[]';
            nuevoInput.placeholder = 'Nombre del acompañante';
            nuevoInput.required = true;

            const btnEliminar = document.createElement("button");
            btnEliminar.textContent = "Eliminar";
            btnEliminar.type = "button";
            btnEliminar.className = "btn btn-danger btn-sm ms-2";

            btnEliminar.onclick = function () {
                nuevoInput.remove();
                btnEliminar.remove();
                contador--;
                actualizarContador();
            };

            const nuevoContenedor = document.createElement("div");
            nuevoContenedor.className = "mb-3 d-flex align-items-center";

            nuevoContenedor.appendChild(nuevoInput);
            nuevoContenedor.appendChild(btnEliminar);

            contenedorInputs.appendChild(nuevoContenedor);

            contador++;
            actualizarContador();
        }
        
        
/*
        // Funcion para calcular el total de viaticos multiplicado por la cantidad de dias solicitados
        function calcularTotalXDias(total) {
            const fechaSalidaValue = fechaSalida.value;
            const fechaRegresoValue = fechaRegreso.value;
            
            // Verificar que ambas fechas estén seleccionadas
            if (fechaSalidaValue && fechaRegresoValue) {
                const fechaSalidaDate = new Date(fechaSalidaValue);
                const fechaRegresoDate = new Date(fechaRegresoValue);

                const numPersonas = contador + 1;
                const totalAcompañantes = total * numPersonas;

                // Calcular la diferencia en milisegundos y convertir a días
                const diferenciaMilisegundos = fechaRegresoDate - fechaSalidaDate;
                const diferenciaDias = (diferenciaMilisegundos / (1000 * 60 * 60 * 24)) + 1;  // Sumar 1 para incluir el día de salida
                diasDiferencia.textContent = `Días de diferencia: ${diferenciaDias}`;

                const totalXDias = totalAcompañantes * diferenciaDias;
                TotalViaticosxDiaxAcompanantes.value = totalXDias;
            } else {
                // Si no se han seleccionado ambas fechas
                diasDiferencia.textContent = 'Días de diferencia: ';
                return 0;
            }
        }

        // Funcion para calcular el total de viaticos multiplicado por la cantidad de acompañantes
        function calcularTotalXAcompañantes(total) {
            const numPersonas = contador + 1;
            const totalAcompañantes = total * numPersonas;
            totalDeViaticosAcompañantes.value = totalAcompañantes;
        }

        
        // Calcular el monto total de los viaticos al cambiar los valores de los inputs
        function calcularTotal() {
            let total = 0;
            inputs.forEach(input => {
                const value = parseFloat(input.value) || 0; // Convertir a 0 si es num. vacio
                total += value;
            });
            totalDeViaticos.value = total;
            calcularTotalXAcompañantes(total);
            calcularTotalXDias(total);
        }

        // Añadir event listeners para calcular el monto total al cambiar los valores de los inputs
        inputs.forEach(input => {
            input.addEventListener('input', calcularTotal);
        });

        calcularTotal();

        

        // Función para calcular el total de viáticos multiplicado por el número de acompañantes
        function calcularTotalXAcompanantes(total) {
            const numPersonas = contador + 1;  // Contador + 1 para incluir al usuario principal
            const totalXAcompanantes = total * numPersonas;
            totalDeViaticosXAcompanantes.value = totalXAcompanantes.toFixed(2);  // Mostrar el total con 2 decimales
            calcularTotalViaticosxDiaxAcompanantes(totalXAcompanantes);  // Calcular el total por días y acompañantes
        }


        

        // Función para calcular el total de viáticos por días y acompañantes
        function calcularTotalViaticosxDiaxAcompanantes(totalXAcompanantes) {
            const diferenciaDias = calcularDiferenciaDias();
            const totalViaticosFinal = totalXAcompanantes * diferenciaDias;
            totalViaticosxDiaxAcompanantes.value = totalViaticosFinal;  // Mostrar el total con 2 decimales
        }

       

        // Acutalizar Contador de personas
        function actualizarContador() {
            const contadorInputs = document.getElementById('contadorInputs');
            if (contadorInputs) {
                contadorInputs.textContent = `Número de Personas: ${contador + 1}`; // +1 para incluir al usuario principal
            }
            calcularTotal();
        }

    
        
        }*/

    </script>

</body>

</html>
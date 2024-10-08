<?php
session_start();
require('../../resources/config/db.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Viáticos</title>
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
    <?php include '../../src/navbar.php' ?>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-black text-white text-center">
                        <h5>Registro de solicitud de viático</h5>
                    </div>
                    <div class="card-body">
                        <form action="/resources/Back/Viaticos/AddViatico.php" method="POST">
                            <!-- Fecha de salida y regreso -->
                            <div class="row my-2">
                                <div class="col-md-6 text-center">
                                    <label for="fechaSalida" class="form-label"><strong>Fecha de
                                            Salida:</strong></label>
                                    <input type="date" class="form-control" id="fechaSalida" name="Fecha_Salida"
                                        required>
                                </div>
                                <div class="col-md-6 text-center">
                                    <label for="fechaRegreso" class="form-label"><strong>Fecha de
                                            Regreso:</strong></label>
                                    <input type="date" class="form-control" id="fechaRegreso" name="Fecha_Regreso"
                                        required>
                                </div>
                            </div>
                            <!-- Hora de salida y regreso -->
                            <div class="row my-4">
                                <div class="col-md-6 text-center">
                                    <label for="horaSalida" class="form-label"><strong>Hora de Salida:</strong></label>
                                    <input type="time" class="form-control" id="horaSalida" name="horaSalida" required>
                                </div>
                                <div class="col-md-6 text-center">
                                    <label for="horaRegreso" class="form-label"><strong>Hora de
                                            Regreso:</strong></label>
                                    <input type="time" class="form-control" id="horaRegreso" name="horaRegreso"
                                        required>
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
                                <div class="col-md-12 text-center">
                                    <label for="nombreProyecto" class="form-label"><strong>Nombre del
                                            proyecto:</strong></label>
                                    <input type="text" placeholder="Ingrese Nombre del proyecto" class="form-control"
                                        onkeyup="javascript:this.value = this.value.toUpperCase().replace(/[^A-Z0-9\s]/g, '');"
                                        id="nombreProyecto" name="nombreProyecto" required>
                                </div>
                            </div>

                            <hr>
                            <div class="text-center">
                                <label for="clientes" class="form-label text-center"><strong>Clientes a
                                        visitar:</strong></label>
                            </div>

                            <div class="row">
                                <!-- Cliente 1 -->
                                <div class="col-md-6 mt-4 text-center">
                                    <label for="Nombre_Cliente_1" class="form-label"><strong>Nombre:</strong></label>
                                    <input type="text" placeholder="Nombre del cliente" class="form-control"
                                        id="Nombre_Cliente_1" name="clientes[0][nombre]" required>
                                </div>
                                <div class="col-md-6 mt-4 text-center">
                                    <label for="Motivo_Cliente_1" class="form-label"><strong>Motivo:</strong></label>
                                    <select class="form-select motivo-cliente" id="Motivo_Cliente_1"
                                        name="clientes[0][motivo]" required>
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
                                    <!-- Campo de texto para "Otro" inicialmente oculto -->
                                    <input type="text" placeholder="Especifique otro motivo"
                                        class="form-control mt-3 otro-motivo-input" id="Otro_Cliente_1"
                                        name="clientes[0][otro_motivo]" style="display:none;"
                                        oninput="this.value = this.value.toUpperCase()">
                                </div>
                                <div class="col-md-6 mt-4 text-center">
                                    <label for="Fecha_Cliente_1" class="form-label"><strong>Fecha:</strong></label>
                                    <input type="date" class="form-control cliente-fecha" id="Fecha_Cliente_1"
                                        name="clientes[0][fecha]" required>
                                </div>

                                <div class="col-md-6 mt-4 text-center">
                                    <label for="Hora_Cliente_1" class="form-label"><strong>Agregar
                                            Otro:</strong></label>
                                    <button type="button" class="btn btn-primary w-100" id="agregarClienteBtn">+ Agregar
                                        Cliente</button>
                                </div>
                            </div>

                            <!-- Contenedor para clientes adicionales -->
                            <div id="clientesAdicionales" class="mt-4"></div>



                            <hr>
                            <div class="text-center">
                                <label for="clientes" class="form-label text-center"><strong>Destino:</strong></label>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <label for="destino" class="form-label"><strong>Estado:</strong></label>
                                    <input type="text" placeholder="Ingrese el estado a visitar..." class="form-control"
                                        id="destino" name="destino" required>
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

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <label for="destino" class="form-label"><strong>Acompañantes:</strong></label>
                                    <input type="text" class="form-control" id="acomp_1"
                                        placeholder="Ingrese nombre de acompañante(s)" name="acomp_1" required>
                                </div>
                            </div>
                            <div class="row" id="acomp-fields"></div>
                            <div class="row mt-3">
                                <div class="col-md-12 text-center">
                                    <button type="button" class="btn btn-primary" onclick="addField()">Agregar
                                        Acompañante</button>
                                    <button type="button" class="btn btn-danger" onclick="removeFields()">Eliminar
                                        Acompañante</button>
                                </div>
                            </div>
                            <hr>
                            <div class="col-md-12 text-center">
                                <label for="Conceptos" class="form-label"><strong>Conceptos:</strong></label>
                            </div>

                            <hr>
                            <div class="row mt-4">
                                <div class="col-md-6 text-center">
                                    <label for="hospedaje" class="form-label"><strong>Hospedaje:</strong></label>
                                    <input type="number" class="form-control" placeholder="Ingrese Monto" id="hospedaje"
                                        name="hospedaje" required>
                                </div>
                                <div class="col-md-6 text-center">
                                    <label for="vuelos" class="form-label"><strong>Vuelos:</strong></label>
                                    <input type="number" class="form-control" id="vuelos" placeholder="Ingrese Monto"
                                        name="vuelos" required>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-6 text-center">
                                    <label for="alimentacion" class="form-label"><strong>Alimentación:</strong></label>
                                    <input type="number" class="form-control" id="alimentacion"
                                        placeholder="Ingrese Monto" name="alimentacion" required>
                                </div>
                                <div class="col-md-6 text-center">
                                    <label for="transporte" class="form-label"><strong>Transporte:</strong></label>
                                    <input type="number" class="form-control" id="transporte"
                                        placeholder="Ingrese Monto" name="transporte" required>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-6 text-center">
                                    <label for="estacionamiento"
                                        class="form-label"><strong>Estacionamiento:</strong></label>
                                    <input type="number" class="form-control" id="estacionamiento"
                                        placeholder="Ingrese Monto" name="estacionamiento" required>
                                </div>
                                <div class="col-md-6 text-center">
                                    <label for="gasolina" class="form-label"><strong>Gasolina:</strong></label>
                                    <input type="number" class="form-control" id="gasolina" placeholder="Ingrese Monto"
                                        name="gasolina" required>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-6 text-center">
                                    <label for="casetas" class="form-label"><strong>Casetas:</strong></label>
                                    <input type="number" class="form-control" id="casetas" placeholder="Ingrese Monto"
                                        name="casetas" required>
                                </div>
                                <div class="col-md-6 text-center">
                                    <label for="materiales" class="form-label"><strong>Materiales:</strong></label>
                                    <input type="number" class="form-control" placeholder="Ingrese Monto"
                                        id="materiales" name="materiales" required>
                                </div>
                            </div>
                            <hr>
                            <div class="row mt-4">
                                <div class="col-md-6 text-center">
                                    <label for="equipos" class="form-label"><strong>Equipos de
                                            seguridad:</strong></label>
                                    <input type="number" class="form-control" id="equipos" placeholder="Ingrese Monto"
                                        name="equipos" required>
                                </div>
                                <div class="col-md-6 text-center">
                                    <label for="gastosMedicos" class="form-label"><strong>Gastos
                                            Medicos:</strong></label>
                                    <input type="number" class="form-control" id="gastosMedicos"
                                        placeholder="Ingrese Monto" name="gastosMedicos" required>
                                </div>

                            </div>
                            <div class="row mt-4">
                                <div class="col-md-6 text-center">
                                    <label type="text" class="form-label"><strong>Concepto:</strong></label>
                                    <input type="text" class="form-control" id="Concepto" placeholder="Escriba el Concepto"
                                        name="Concepto" required>
                                </div>
                                <div class="col-md-6 text-center">
                                    <label type="text" class="form-label"><strong>Monto:</strong></label>
                                    <input type="number" class="form-control" id="Monto" placeholder="Ingrese Monto"
                                        name="Monto" required>
                                </div>
                            </div>
                            <hr>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let clienteCounter = 1;
            const maxClientes = 3;
            const agregarClienteBtn = document.getElementById('agregarClienteBtn');
            const clientesAdicionales = document.getElementById('clientesAdicionales');
            const fechaSalida = document.getElementById('fechaSalida');
            const fechaRegreso = document.getElementById('fechaRegreso');

            // Actualiza las fechas disponibles para cada cliente
            function updateClientDateFields() {
                const salida = fechaSalida.value;
                const regreso = fechaRegreso.value;

                const clientesFechas = document.querySelectorAll('.cliente-fecha');
                clientesFechas.forEach(fechaField => {
                    fechaField.min = salida;
                    fechaField.max = regreso;
                });
            }

            // Evento para agregar un nuevo cliente
            agregarClienteBtn.addEventListener('click', function () {
                if (clienteCounter < maxClientes) {
                    clienteCounter++;

                    // Crear el nuevo cliente con su botón de eliminación
                    const nuevoCliente = document.createElement('div');
                    nuevoCliente.classList.add('row', 'mt-3');
                    nuevoCliente.setAttribute('id', `cliente_${clienteCounter}`);
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
                    updateClientDateFields();

                    // Deshabilitar el botón de agregar cuando se alcance el límite
                    if (clienteCounter === maxClientes) {
                        agregarClienteBtn.disabled = true;
                    }
                }
            });

            // Evento para eliminar un cliente específico
            clientesAdicionales.addEventListener('click', function (event) {
                if (event.target.classList.contains('eliminar-cliente')) {
                    const clienteId = event.target.getAttribute('data-cliente-id');
                    const clienteDiv = document.getElementById(`cliente_${clienteId}`);
                    clienteDiv.remove();

                    clienteCounter--; // Reducir el contador
                    agregarClienteBtn.disabled = false; // Habilitar el botón de agregar de nuevo
                }
            });

            document.addEventListener('change', function (event) {
    if (event.target.classList.contains('motivo-cliente')) {
        const otroInput = event.target.nextElementSibling; // Selecciona el input de "Otro"
        if (event.target.value === "Otro") {
            otroInput.style.display = 'block';
            otroInput.required = true; // Hacer obligatorio
        } else {
            otroInput.style.display = 'none';
            otroInput.required = false; // No obligatorio si no es "Otro"
        }
    }
});

            // Actualiza las fechas cuando se cambian las fechas de salida o regreso
            fechaSalida.addEventListener('change', updateClientDateFields);
            fechaRegreso.addEventListener('change', updateClientDateFields);

            // Inicializa las fechas si ya se han seleccionado
            updateClientDateFields();
        });

    </script>

    <!-- Scripts para agregar y eliminar ciudades -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let ciudadCounter = 0;
            const maxCiudades = 3;
            const agregarCiudadBtn = document.getElementById('agregarCiudadBtn');
            const ciudadesAdicionales = document.getElementById('ciudadesAdicionales');

            // Evento para agregar una nueva ciudad
            agregarCiudadBtn.addEventListener('click', function () {
                if (ciudadCounter < maxCiudades) {
                    ciudadCounter++;

                    // Crear el nuevo bloque para la ciudad
                    const nuevaCiudad = document.createElement('div');
                    nuevaCiudad.classList.add('row', 'mt-3');
                    nuevaCiudad.setAttribute('id', `ciudad_${ciudadCounter}`);
                    nuevaCiudad.innerHTML = `
                <div class="col-md-6 text-center">
                    <label for="Nombre_Ciudad_${ciudadCounter}" class="form-label">Ciudad:</label>
                    <input type="text" class="form-control" id="Nombre_Ciudad_${ciudadCounter}" name="ciudades[${ciudadCounter - 1}][nombre]" required>
                </div>
                <div class="col-md-6 text-center mt-4">
                    <button type="button" class="btn btn-danger eliminar-ciudad" data-ciudad-id="${ciudadCounter}">Eliminar</button>
                </div>
            `;

                    ciudadesAdicionales.appendChild(nuevaCiudad);

                    // Deshabilitar el botón de agregar cuando se llegue al límite
                    if (ciudadCounter === maxCiudades) {
                        agregarCiudadBtn.disabled = true;
                    }
                }
            });

            // Evento para eliminar una ciudad específica
            ciudadesAdicionales.addEventListener('click', function (event) {
                if (event.target.classList.contains('eliminar-ciudad')) {
                    const ciudadId = event.target.getAttribute('data-ciudad-id');
                    const ciudadDiv = document.getElementById(`ciudad_${ciudadId}`);
                    ciudadDiv.remove();

                    ciudadCounter--; // Reducir el contador
                    agregarCiudadBtn.disabled = false; // Habilitar el botón de agregar de nuevo
                }
            });
        });
    </script>

    <!-- Scripts para agregar y eliminar acompañantes -->
    <script>
        let counter = 1;
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
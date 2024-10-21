<?php
session_start();
require('../../resources/config/db.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Reembolsos</title>
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
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-black text-white text-center">
                        <h5>Registro de solicitud de Reembolso</h5>
                    </div>
                    <div class="card-body">
                        <form action="/resources/Back/Reembolsos/AddRembolso.php" method="POST"
                            enctype="multipart/form-data">
                            <!-- Monto y Concepto -->
                            <div class="row my-2">
                                <div class="col-md-6 text-center">
                                    <label for="Monto" class="form-label"><strong>Monto:</strong></label>
                                    <input type="number" class="form-control" id="Monto" name="Monto" required>
                                </div>
                                <div class="col-md-6 text-center">
                                    <label for="Concepto" class="form-label"><strong>Concepto:</strong></label>
                                    <!-- Select de los conceptos -->
                                    <select class="form-select" id="selectConcepto" name="selectConcepto" aria-label="Default select example"
                                        onchange="mostrarInput()">
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
                                <button type="submit" class="btn btn-success w-100">Finalizar Solicitud</button>
                            </div>

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
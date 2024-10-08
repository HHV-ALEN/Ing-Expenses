<?php
session_start();
require('../../resources/config/db.php');

$Id = $_GET['id'];

$sql_query = "SELECT * FROM reembolsos WHERE Id = $Id";
$result = $conn->query($sql_query);
$row = $result->fetch_assoc();
$Concepto = $row['Concepto'];
$Monto = $row['Monto'];
$Destino = $row['Destino'];
$Fecha = $row['Fecha'];
$Descripcion = $row['Descripcion'];
$Nombre_Archivo = $row['Nombre_Archivo'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Reembolso</title>
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
                    <form action="/resources/Back/Reembolsos/editarReembolso.php?Id=<?php echo $Id ?>" method="POST"
                        enctype="multipart/form-data">
                        <!-- Monto y Concepto -->
                         <input type="hidden" name="Id" value="<?php echo $Id ?>">
                        <div class="row my-2">
                            <div class="col-md-6 text-center">
                                <label for="Monto" class="form-label"><strong>Monto:</strong></label>
                                <input type="number" class="form-control" id="Monto" name="Monto" value="<?php echo $Monto; ?>" required>
                            </div>
                            <div class="col-md-6 text-center">
                                <label for="Concepto" class="form-label"><strong>Concepto:</strong></label>
                                <!-- Select de los conceptos -->
                                <select class="form-select" id="selectConcepto" name="selectConcepto" aria-label="Default select example"
                                    onchange="mostrarInput()">
                                    <option value="<?php echo $Concepto ?>" selected><?php echo $Concepto ?></option>
                                    <option value="Materiales">Materiales</option>
                                    <option value="Equipos de seguridad">Equipos de seguridad</option>
                                    <option value="Gastos Medicos">Gastos Medicos</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                        </div>

                        <!-- Input oculto por defecto -->
                        <div class="col-md-6 text-center" id="inputOtroConcepto"
                            style="display: none; margin-top: 10px;">
                            <label for="otroConcepto" class="form-label"><strong>Especifica el
                                    concepto:</strong></label>
                            <input type="text" class="form-control" id="otroConcepto" name="Concepto"
                                placeholder="Escribe el concepto" >
                        </div>

                        <!-- Destino y Imagen -->
                        <div class="row my-4">
                            <div class="col-md-6 text-center">
                                <label for="Destino" class="form-label"><strong>Destino:</strong></label>
                                <input type="Text" class="form-control" id="Destino" name="Destino" value="<?php echo $Destino ?>" required>
                            </div>
                            <div class="col-md-6 text-center">
                                <label for="Fecha" class="form-label"><strong>Fecha:</strong></label>
                                <input type="Date" class="form-control" id="Fecha" name="Fecha" value="<?php echo $Fecha ?>" required>
                            </div>
                        </div>
                        <div class="row my-4">

                            <div class="col-md-6 text-center">
                                <!-- Si el archivo ya existe,Mostrar el nombre y agregar un input por si se quiere actualizar a otro nuevo -->
                                <label for="Nombre_Archivo" class="form-label">

                                </label><strong>Imagen Actual:</strong></label><br>
                                <img src="../../uploads/<?php echo $Nombre_Archivo ?>"  width="100" height="100"><br>
                                <input type="hidden" name="Nombre_Archivo" value="<?php echo $Nombre_Archivo ?>">
                                <input type="file" class="form-control" id="file" name="file">
                            </div>

                            <div class="col-md-6 text-center">
                                <label for="Descripcion" class="form-label"><strong>Descripcion:</strong></label>
                                <input type="Text" class="form-control" id="Descripcion" name="Descripcion" value="<?php echo $Descripcion ?>"
                                    required>
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
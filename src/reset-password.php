<?php

$correo = $_GET['correo'];

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="path/to/your/custom.css"> <!-- Asegúrate de enlazar tu archivo CSS -->
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            background-color: #000;
            /* Fondo negro */
        }

        .bg {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .card-custom {
            background-color: rgba(255, 255, 255, 0.9);
            /* Fondo blanco con un poco de transparencia */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .card-header-custom {
            background-color: #2c3e50;
            /* Azul oscuro */
            color: #ecf0f1;
            /* Blanco humo */
            border-radius: 10px 10px 0 0;
            padding: 10px;
        }

        .btn-custom {
            background-color: #2c3e50;
            /* Azul oscuro */
            color: #ecf0f1;
            /* Blanco humo */
        }

        .btn-custom:hover {
            background-color: #34495e;
            /* Azul oscuro un poco más claro */
            color: #bdc3c7;
            /* Gris claro */
        }
    </style>
</head>

<body>
    <div class="bg">
        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-100">
                <div class="col-md-6 col-lg-4">
                    <div class="card card-custom">
                        <div class="card-header text-center card-header-custom">
                            <h5>Recuperar Contraseña</h5>
                        </div>
                        <div class="card-body">
                            <form action="/resources/Back/Mail/resetpass.php" method="POST">
                                <div class="form-group">
                                    <label for="email">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $correo; ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block btn-custom">Enviar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
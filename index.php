<?php
if (isset($_GET['message']) && $_GET['message'] == 'session_expired') {
    echo "<p>Tu sesión ha caducado. Por favor, inicia sesión nuevamente.</p>";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="shortcut icon" href="/resources/img/logo-icon.png" />
    <style>
        .bg {
            background-image: url('resources/img/Inside/Main-Background.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background: rgba(255, 255, 255, 0.74);
        }
    </style>
</head>

<body>
    <div class="bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center">
                                <br>
                                <img src="resources/img/Inside/Logo-Login.png" class="img-fluid mb-3" alt="Logo">
                            </div>
                            <hr>
                            <form action="/resources/Login/Login.php" method="POST">
                                <div class="form-group">
                                    <label for="username">Usuario:</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Contraseña:</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <hr>
                                <br>
                                <button type="submit" class="btn btn-outline-primary btn-block">Ingresar</button>
                            </form>
                            <div class="text-center mt-3">
                                <a href="forgot-password.php">¿Olvidaste tu contraseña?</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Error</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Usuario o contraseña incorrectos
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Mostrar el modal si hay un error -->
    <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
        <script>
            $(document).ready(function () {
                $('#errorModal').modal('show');
            });
        </script>
    <?php endif; ?>
</body>

</html>
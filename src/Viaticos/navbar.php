<!-- Inicio de la barra de navegación -->
<?php 
session_start();
?>

<nav class="navbar navbar-expand-lg navbar navbar-dark bg-dark text-center">
        <div class="container-fluid">

        <a href="/src/dashboard.php">
            <img src="/resources/img/AlenW.png" alt="ALEN Viáticos Ingeniería" class="img-fluid" style="padding: 5px; height: 47px;">
        </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <?php
                    if ($_SESSION['Position'] == 'Admin') {
                        echo '
                        <li class="nav-item">
                            <a class="nav-link" href="/src/Admin/Usuarios.php">Usuarios</a>
                        </li>';
                    } elseif ($_SESSION['Position'] == 'Control') {
                        echo '
                        <li class="nav-item">
                            <a class="nav-link" href="/src/Viaticos/Solicitar.php">Solicitar Viático</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/src/Viaticos/MisViaticos.php">Mis Viáticos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/src/Viaticos/Superior/ListadoViaticos.php">Listado de Viáticos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/src/Reembolsos/Superior/ListadoReembolsos.php">Listado de Reembolsos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/src/Reembolsos/Solicitar.php">Solicitar Reembolso</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/src/Reembolsos/MisReembolsos.php">Mis Reembolsos</a>
                        </li>
                    
                        ';
                        
                    } elseif ($_SESSION['Position'] == 'Empleado') {
                        echo '
                        <li class="nav-item">
                            <a class="nav-link" href="/src/Viaticos/Solicitar.php">Solicitar Viático</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/src/Viaticos/MisViaticos.php">Mis Viáticos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/src/Reembolsos/Solicitar.php">Solicitar Reembolso</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/src/Reembolsos/MisReembolsos.php">Mis Reembolsos</a>
                        </li>
                        ';
                       
                    } elseif ($_SESSION['Position'] == 'Gerente') {
                        echo '
                        <li class="nav-item">
                            <a class="nav-link" href="/src/Viaticos/Solicitar.php">Solicitar Viático</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/src/Viaticos/MisViaticos.php">Mis Viáticos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/src/Reembolsos/Solicitar.php">Solicitar Reembolso</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/src/Reembolsos/MisReembolsos.php">Mis Reembolsos</a>
                        </li>
                        
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                A mi cargo
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                                <li><a class="dropdown-item" href="/src/Viaticos/Superior/Viaticos_AMiCargo.php">Viáticos a mi cargo</a></li>
                                <li><a class="dropdown-item" href="/src/Reembolsos/Superior/Reembolsos_AMiCargo.php">Reembolsos a mi cargo</a></li>
                                
                            </ul>
                        </li>
                        ';
                    }
                    ?>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/src/perfil.php"><?php echo $_SESSION['Name'] ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/resources/Logout/Logout.php">Salir</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
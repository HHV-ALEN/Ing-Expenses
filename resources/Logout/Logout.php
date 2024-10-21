<?php

// Metodo para cerrar la sesion y regresar al formulario de inicio
session_start();
session_destroy();
header('Location: /index.php');


?>
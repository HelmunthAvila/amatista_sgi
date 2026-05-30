<?php

// Cierra la sesión del usuario y lo redirige a la página de inicio de sesión
session_start();
session_destroy();

header("Location: login.php");

?>
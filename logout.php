<?php

// Cierra la sesión del usuario y lo redirige a la página de inicio de sesión
require_once("includes/iniciar_sesion.php");
session_destroy();

header("Location: login.php");

?>
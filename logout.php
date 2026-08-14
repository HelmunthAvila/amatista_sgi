<?php

// Cierra la sesión del usuario: vacía los datos, invalida la cookie y destruye la sesión (AM-014)
require_once("includes/iniciar_sesion.php");

// 1. Vaciar el arreglo de sesión
$_SESSION = [];

// 2. Invalidar la cookie de sesión en el navegador
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}

// 3. Destruir la sesión en el servidor
session_destroy();

header("Location: login.php");

?>

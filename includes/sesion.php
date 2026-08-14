<?php
// Control de acceso: valida sesión del usuario y evita acceso directo a módulos sin autenticación
require_once __DIR__ . '/iniciar_sesion.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../login.php");
    exit();
}

// Control de permisos por rol (AM-006)
function requiere_rol($roles_permitidos) {
    $roles = (array) $roles_permitidos;
    if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], $roles, true)) {
        $_SESSION['alerta'] = [
            'tipo' => 'danger',
            'mensaje' => '<strong>Acceso denegado:</strong> No tienes permisos para realizar esta acción.'
        ];
        header("Location: ../../dashboard.php");
        exit();
    }
}
?>

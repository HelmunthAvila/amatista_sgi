<?php
include("../../includes/sesion.php");
requiere_rol('admin');
// Iniciamos sesión para interactuar con las notificaciones

// Incluye el archivo de conexión a la base de datos
include("../../conexion.php");
// Verificación CSRF (AM-008)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !csrf_verificar()) {
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'La sesión expiró. Recarga la página e intenta nuevamente.'];
    header("Location: listar.php");
    exit();
}


// Verifica que se haya recibido el ID del cliente mediante la URL
if (isset($_POST['id'])) {

    // Limpia el valor del ID
    $id = mysqli_real_escape_string($conexion, $_POST['id']);

    // Consulta SQL para eliminar el cliente seleccionado
    $sql = "DELETE FROM clientes WHERE id = '$id'";

    // Ejecuta la consulta de eliminación
    if (mysqli_query($conexion, $sql)) {
        $_SESSION['alerta'] = [
            'tipo' => 'success',
            'mensaje' => '<strong>¡Eliminado!</strong> El cliente ha sido retirado del sistema.'
        ];
    } else {
        $_SESSION['alerta'] = [
            'tipo' => 'danger',
            'mensaje' => '<strong>Error crítico:</strong> El cliente no puede ser eliminado porque cuenta con historial activo en el sistema.'
        ];
    }
}

// Redirige siempre al listado
header("Location: listar.php");
exit();
?>
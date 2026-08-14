<?php
include("../../includes/sesion.php");
requiere_rol('admin');
// Iniciamos sesión para interactuar con el sistema de notificaciones

// Incluye el archivo de conexión a la base de datos
include("../../conexion.php");
// Verificación CSRF (AM-008)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !csrf_verificar()) {
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'La sesión expiró. Recarga la página e intenta nuevamente.'];
    header("Location: listar.php");
    exit();
}


// Recibe los datos enviados desde el formulario mediante POST
$id       = intval($_POST['id']);
$nombre   = $_POST['nombre'];
$telefono = $_POST['telefono'];
$email    = $_POST['email'];

// Consulta preparada para actualizar los datos del cliente seleccionado (AM-005)
$stmt = mysqli_prepare($conexion, "UPDATE clientes SET nombre = ?, telefono = ?, email = ? WHERE id = ?");
mysqli_stmt_bind_param($stmt, "sssi", $nombre, $telefono, $email, $id);

// Ejecuta la consulta en la base de datos
if (mysqli_stmt_execute($stmt)) {
    $_SESSION['alerta'] = [
        'tipo' => 'success',
        'mensaje' => '<strong>¡Actualizado!</strong> Los datos del cliente se modificaron con éxito.'
    ];
} else {
    $_SESSION['alerta'] = [
        'tipo' => 'danger',
        'mensaje' => '<strong>Error:</strong> No se pudo actualizar la información del cliente.'
    ];
}

mysqli_stmt_close($stmt);

// Redirige al listado sin ensuciar la URL
header("Location: listar.php");
exit();
?>
<?php
include("../../includes/sesion.php");
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
$nombre   = mysqli_real_escape_string($conexion, $_POST['nombre']);
$telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
$email    = mysqli_real_escape_string($conexion, $_POST['email']);

// Consulta SQL para actualizar los datos del cliente seleccionado
$sql = "UPDATE clientes SET 
        nombre = '$nombre', 
        telefono = '$telefono', 
        email = '$email' 
        WHERE id = $id";

// Ejecuta la consulta en la base de datos
if (mysqli_query($conexion, $sql)) {
    $_SESSION['alerta'] = [
        'tipo' => 'success',
        'mensaje' => '<strong>¡Actualizado!</strong> Los datos del cliente se modificaron con éxito.'
    ];
} else {
    $_SESSION['alerta'] = [
        'tipo' => 'danger',
        'mensaje' => '<strong>Error:</strong> No se pudo actualizar la información del cliente. ' . mysqli_error($conexion)
    ];
}

// Redirige al listado sin ensuciar la URL
header("Location: listar.php");
exit();
?>
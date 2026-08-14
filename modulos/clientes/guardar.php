<?php
include("../../includes/sesion.php");
requiere_rol('admin');
// Iniciamos sesión para poder usar las alertas inteligentes

// Incluye el archivo de conexión a la base de datos
include("../../conexion.php");
// Verificación CSRF (AM-008)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !csrf_verificar()) {
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'La sesión expiró. Recarga la página e intenta nuevamente.'];
    header("Location: listar.php");
    exit();
}


// Recibe y limpia los datos enviados desde el formulario agregar.php
$nombre   = mysqli_real_escape_string($conexion, $_POST['nombre']);
$telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
$email    = mysqli_real_escape_string($conexion, $_POST['email']);

// Consulta SQL para insertar un nuevo cliente en la tabla clientes
$sql = "INSERT INTO clientes (nombre, telefono, email) 
        VALUES ('$nombre', '$telefono', '$email')";

// Ejecuta la consulta de inserción en la base de datos
if (mysqli_query($conexion, $sql)) {
    // Alerta inteligente de éxito
    $_SESSION['alerta'] = [
        'tipo' => 'success',
        'mensaje' => '<strong>¡Éxito!</strong> El cliente se ha registrado correctamente en Amatista SGI.'
    ];
} else {
    // Alerta inteligente de error
    $_SESSION['alerta'] = [
        'tipo' => 'danger',
        'mensaje' => '<strong>Error:</strong> No se pudo registrar el cliente. ' . mysqli_error($conexion)
    ];
}

// Redirige al listado de clientes de forma limpia
header("Location: listar.php");
exit();
?>
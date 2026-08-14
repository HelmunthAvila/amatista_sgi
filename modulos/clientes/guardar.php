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


// Recibe los datos enviados desde el formulario agregar.php
$nombre   = $_POST['nombre'];
$telefono = $_POST['telefono'];
$email    = $_POST['email'];

// Consulta preparada para insertar un nuevo cliente (AM-005)
$stmt = mysqli_prepare($conexion, "INSERT INTO clientes (nombre, telefono, email) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($stmt, "sss", $nombre, $telefono, $email);

// Ejecuta la consulta de inserción en la base de datos
if (mysqli_stmt_execute($stmt)) {
    // Alerta inteligente de éxito
    $_SESSION['alerta'] = [
        'tipo' => 'success',
        'mensaje' => '<strong>¡Éxito!</strong> El cliente se ha registrado correctamente en Amatista SGI.'
    ];
} else {
    // Alerta inteligente de error
    $_SESSION['alerta'] = [
        'tipo' => 'danger',
        'mensaje' => '<strong>Error:</strong> No se pudo registrar el cliente.'
    ];
}

mysqli_stmt_close($stmt);

// Redirige al listado de clientes de forma limpia
header("Location: listar.php");
exit();
?>
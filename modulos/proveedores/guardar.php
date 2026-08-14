<?php
include("../../includes/sesion.php");
requiere_rol('admin');
// Iniciamos sesión para gestionar las alertas estructuradas

// Incluir el archivo de conexión a la base de datos
include("../../conexion.php");
// Verificación CSRF (AM-008)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !csrf_verificar()) {
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'La sesión expiró. Recarga la página e intenta nuevamente.'];
    header("Location: listar.php");
    exit();
}


// Recepción de los datos enviados desde el formulario (POST)
$nombre   = $_POST['nombre'];
$telefono = $_POST['telefono'];
$empresa  = $_POST['empresa'];

// Sentencia preparada para insertar un nuevo proveedor (AM-005)
$stmt = mysqli_prepare($conexion, "INSERT INTO proveedores (nombre, telefono, empresa) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($stmt, "sss", $nombre, $telefono, $empresa);

// Ejecutar la consulta en la base de datos
if (mysqli_stmt_execute($stmt)) {
    $_SESSION['alerta'] = [
        'tipo' => 'success',
        'mensaje' => '<strong>¡Éxito!</strong> El proveedor ha sido registrado correctamente en Amatista SGI.'
    ];
} else {
    $_SESSION['alerta'] = [
        'tipo' => 'danger',
        'mensaje' => '<strong>Error:</strong> No se pudo registrar el proveedor.'
    ];
}

mysqli_stmt_close($stmt);

// Redirección limpia al listado maestro
header("Location: listar.php");
exit();
?>
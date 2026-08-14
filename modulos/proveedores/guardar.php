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
$nombre   = mysqli_real_escape_string($conexion, $_POST['nombre']);
$telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
$empresa  = mysqli_real_escape_string($conexion, $_POST['empresa']);

// Sentencia SQL para insertar un nuevo proveedor
$sql = "INSERT INTO proveedores (nombre, telefono, empresa) 
        VALUES ('$nombre', '$telefono', '$empresa')";

// Ejecutar la consulta en la base de datos
if (mysqli_query($conexion, $sql)) {
    $_SESSION['alerta'] = [
        'tipo' => 'success',
        'mensaje' => '<strong>¡Éxito!</strong> El proveedor ha sido registrado correctamente en Amatista SGI.'
    ];
} else {
    $_SESSION['alerta'] = [
        'tipo' => 'danger',
        'mensaje' => '<strong>Error:</strong> No se pudo registrar el proveedor. ' . mysqli_error($conexion)
    ];
}

// Redirección limpia al listado maestro
header("Location: listar.php");
exit();
?>
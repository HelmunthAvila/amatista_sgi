<?php
include("../../includes/sesion.php");
requiere_rol('admin');
// Inicia la sesión para administrar flujos informativos

// Incluye el archivo de conexión a la base de datos
include("../../conexion.php");
// Verificación CSRF (AM-008)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !csrf_verificar()) {
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'La sesión expiró. Recarga la página e intenta nuevamente.'];
    header("Location: listar.php");
    exit();
}


// Recibe los campos de texto enviados desde el formulario
$nombre   = $_POST['nombre'];
$marca    = $_POST['marca'];
$talla    = $_POST['talla'];
$color    = $_POST['color'];

// Recibe los valores numéricos del producto
$precio   = $_POST['precio'];
$stock    = $_POST['stock'];

// Consulta preparada para insertar un nuevo producto (AM-005)
$stmt = mysqli_prepare($conexion, "INSERT INTO productos (nombre, marca, talla, color, precio, stock) VALUES (?, ?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ssssss", $nombre, $marca, $talla, $color, $precio, $stock);

// Ejecuta la consulta y define alertas correspondientes
if (mysqli_stmt_execute($stmt)) {
    $_SESSION['alerta'] = [
        'tipo' => 'success',
        'mensaje' => '<strong>¡Producto agregado!</strong> El modelo <strong>"' . htmlspecialchars($nombre) . '"</strong> se registró correctamente en el inventario.'
    ];
} else {
    $_SESSION['alerta'] = [
        'tipo' => 'danger',
        'mensaje' => '<strong>Error al agregar:</strong> No se pudo registrar el modelo.'
    ];
}

mysqli_stmt_close($stmt);

// Redirección al listado principal
header("Location: listar.php");
exit();
?>
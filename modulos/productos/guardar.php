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


// Recibe y limpia los campos de texto enviados para evitar inyecciones SQL
$nombre   = mysqli_real_escape_string($conexion, $_POST['nombre']);
$marca    = mysqli_real_escape_string($conexion, $_POST['marca']);
$talla    = mysqli_real_escape_string($conexion, $_POST['talla']);
$color    = mysqli_real_escape_string($conexion, $_POST['color']);

// Recibe los valores numéricos del producto
$precio   = $_POST['precio'];
$stock    = $_POST['stock'];

// Consulta SQL para insertar un nuevo producto en la tabla productos
$sql = "INSERT INTO productos (nombre, marca, talla, color, precio, stock) 
        VALUES ('$nombre', '$marca', '$talla', '$color', '$precio', '$stock')";

// Ejecuta la consulta y define alertas correspondientes
if (mysqli_query($conexion, $sql)) {
    $_SESSION['alerta'] = [
        'tipo' => 'success',
        'mensaje' => '<strong>¡Producto agregado!</strong> El modelo <strong>"' . htmlspecialchars($nombre) . '"</strong> se registró correctamente en el inventario.'
    ];
} else {
    $_SESSION['alerta'] = [
        'tipo' => 'danger',
        'mensaje' => '<strong>Error al agregar:</strong> No se pudo registrar el modelo. ' . mysqli_error($conexion)
    ];
}

// Redirección al listado principal
header("Location: listar.php");
exit();
?>
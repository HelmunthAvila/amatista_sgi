<?php
include("../../includes/sesion.php");
requiere_rol('admin');
// Inicia la sesión para almacenar el estado de la alerta corporativa

// Incluye el archivo de conexión a la base de datos
include("../../conexion.php");
// Verificación CSRF (AM-008)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !csrf_verificar()) {
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'La sesión expiró. Recarga la página e intenta nuevamente.'];
    header("Location: listar.php");
    exit();
}


// Recibe los datos enviados desde el formulario de edición de productos
$id       = intval($_POST['id']);
$nombre   = $_POST['nombre'];
$marca    = $_POST['marca'];
$talla    = $_POST['talla'];
$color    = $_POST['color'];

// Recibe valores numéricos del producto
$precio   = $_POST['precio'];
$stock    = $_POST['stock'];

// Consulta preparada para actualizar la información del producto según su ID (AM-005)
$stmt = mysqli_prepare($conexion, "UPDATE productos SET nombre = ?, marca = ?, talla = ?, color = ?, precio = ?, stock = ? WHERE id = ?");
mysqli_stmt_bind_param($stmt, "ssssssi", $nombre, $marca, $talla, $color, $precio, $stock, $id);

// Ejecuta la consulta de actualización y define alertas de sesión estilo Amatista SGI
if (mysqli_stmt_execute($stmt)) {
    $_SESSION['alerta'] = [
        'tipo' => 'success',
        'mensaje' => '<strong>¡Producto modificado!</strong> Los cambios en el modelo <strong>"' . htmlspecialchars($nombre) . '"</strong> fueron guardados con éxito.'
    ];
} else {
    $_SESSION['alerta'] = [
        'tipo' => 'danger',
        'mensaje' => '<strong>Error al modificar:</strong> No se guardaron los cambios.'
    ];
}

mysqli_stmt_close($stmt);

// Redirige al inventario limpio de parámetros URL
header("Location: listar.php");
exit();
?>
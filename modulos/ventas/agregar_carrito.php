<?php
// Inicia la sesión para poder utilizar variables de sesión como el carrito de compras
session_start();

// Incluye el archivo de conexión a la base de datos
include("../../conexion.php");

// Verifica que el formulario haya enviado el id del producto y la cantidad
if(!isset($_POST['producto_id']) || !isset($_POST['cantidad'])){
    header("Location: pos.php");
    exit();
}

// Obtiene y convierte a entero el id del producto y la cantidad solicitada
$id = intval($_POST['producto_id']);
$cantidad = intval($_POST['cantidad']);

// Valida que la cantidad sea mayor a cero
if($cantidad <= 0){
    header("Location: pos.php");
    exit();
}

// Consulta en la base de datos la información del producto seleccionado
$consulta = mysqli_query($conexion, "SELECT id, nombre, precio, stock FROM productos WHERE id = $id");

// Verifica que el producto exista en la base de datos
if(!$consulta || mysqli_num_rows($consulta) == 0){
    header("Location: pos.php?error=producto_no_existe");
    exit();
}

// Obtiene los datos del producto en un arreglo asociativo
$p = mysqli_fetch_assoc($consulta);

// Valida que haya suficiente stock disponible para la cantidad solicitada
if($cantidad > $p['stock']){
    header("Location: pos.php?error=stock_insuficiente");
    exit();
}

// Crea el carrito en la sesión si aún no existe
if(!isset($_SESSION['carrito'])){
    $_SESSION['carrito'] = [];
}

// Variable para verificar si el producto ya está en el carrito
$producto_encontrado = false;

// Recorre el carrito para verificar si el producto ya fue agregado anteriormente
foreach($_SESSION['carrito'] as $index => $item){

    // Si el producto ya existe en el carrito
    if($item['id'] == $id){

        // Suma la nueva cantidad a la cantidad existente
        $_SESSION['carrito'][$index]['cantidad'] += $cantidad;

        $producto_encontrado = true;
        break;
    }
}

// Si el producto no está en el carrito se agrega como nuevo
if(!$producto_encontrado){

    // Se crea el arreglo con la información del producto
    $item = [
        "id" => $p['id'],
        "nombre" => $p['nombre'],
        "precio" => $p['precio'],
        "cantidad" => $cantidad
    ];

    // Se agrega el producto al carrito almacenado en sesión
    $_SESSION['carrito'][] = $item;
}

// Redirige nuevamente a la página del POS después de agregar el producto
header("Location: pos.php");
exit();
?>
<?php
session_start();
include("../../conexion.php");

if(!isset($_POST['producto_id']) || !isset($_POST['cantidad'])){
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'Parámetros del formulario incompletos.'];
    header("Location: pos.php");
    exit();
}

$id = intval($_POST['producto_id']);
$cantidad = intval($_POST['cantidad']);

if($cantidad <= 0){
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'La cantidad ingresada debe ser mayor a cero.'];
    header("Location: pos.php");
    exit();
}

$consulta = mysqli_query($conexion, "SELECT id, nombre, precio, stock FROM productos WHERE id = $id");

if(!$consulta || mysqli_num_rows($consulta) == 0){
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'El producto seleccionado no existe en el sistema.'];
    header("Location: pos.php");
    exit();
}

$p = mysqli_fetch_assoc($consulta);

if($cantidad > $p['stock']){
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => "Stock insuficiente para <strong>{$p['nombre']}</strong>. Máximo disponible: {$p['stock']} unds."];
    header("Location: pos.php");
    exit();
}

if(!isset($_SESSION['carrito'])){
    $_SESSION['carrito'] = [];
}

$producto_encontrado = false;
foreach($_SESSION['carrito'] as $index => $item){
    if($item['id'] == $id){
        // Validación adicional: Evitar que la suma del carrito supere el stock real
        if(($_SESSION['carrito'][$index]['cantidad'] + $cantidad) > $p['stock']){
            $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => "No puedes agregar más unidades. El total acumulado en el carrito supera el stock de <strong>{$p['nombre']}</strong>."];
            header("Location: pos.php");
            exit();
        }
        $_SESSION['carrito'][$index]['cantidad'] += $cantidad;
        $producto_encontrado = true;
        break;
    }
}

if(!$producto_encontrado){
    $item = [
        "id" => $p['id'],
        "nombre" => $p['nombre'],
        "precio" => $p['precio'],
        "cantidad" => $cantidad
    ];
    $_SESSION['carrito'][] = $item;
}

$_SESSION['alerta'] = ['tipo' => 'success', 'mensaje' => "Se añadió <strong>{$p['nombre']}</strong> ({$cantidad} unds.) al carrito."];
header("Location: pos.php");
exit();
?>
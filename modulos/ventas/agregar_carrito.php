<?php
session_start();
include("../../conexion.php");

// Verificar que lleguen los datos
if(!isset($_POST['producto_id']) || !isset($_POST['cantidad'])){
    header("Location: pos.php");
    exit();
}

$id = intval($_POST['producto_id']);
$cantidad = intval($_POST['cantidad']);

if($cantidad <= 0){
    header("Location: pos.php");
    exit();
}

// Consultar producto en base de datos
$consulta = mysqli_query($conexion, "SELECT id, nombre, precio, stock FROM productos WHERE id = $id");

if(!$consulta || mysqli_num_rows($consulta) == 0){
    header("Location: pos.php?error=producto_no_existe");
    exit();
}

$p = mysqli_fetch_assoc($consulta);

// Validar stock disponible
if($cantidad > $p['stock']){
    header("Location: pos.php?error=stock_insuficiente");
    exit();
}

// Crear carrito si no existe
if(!isset($_SESSION['carrito'])){
    $_SESSION['carrito'] = [];
}

// Verificar si el producto ya está en el carrito
$producto_encontrado = false;

foreach($_SESSION['carrito'] as $index => $item){

    if($item['id'] == $id){

        // Sumar cantidad si ya existe
        $_SESSION['carrito'][$index]['cantidad'] += $cantidad;

        $producto_encontrado = true;
        break;
    }
}

// Si no existe en el carrito lo agregamos
if(!$producto_encontrado){

    $item = [
        "id" => $p['id'],
        "nombre" => $p['nombre'],
        "precio" => $p['precio'],
        "cantidad" => $cantidad
    ];

    $_SESSION['carrito'][] = $item;
}

// Redirigir al POS
header("Location: pos.php");
exit();
?>
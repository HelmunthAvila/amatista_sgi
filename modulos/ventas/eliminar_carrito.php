<?php
// Inicia la sesión para poder acceder al carrito almacenado en variables de sesión
session_start();

// Verifica que exista el carrito en la sesión
if(!isset($_SESSION['carrito'])){
    header("Location: pos.php");
    exit();
}

// Verifica que se haya recibido el índice del producto a eliminar
if(isset($_GET['id'])){

    // Obtiene el índice del producto dentro del carrito
    $index = $_GET['id'];

    // Valida que el índice exista dentro del arreglo del carrito
    if(isset($_SESSION['carrito'][$index])){

        // Elimina el producto seleccionado del carrito
        unset($_SESSION['carrito'][$index]);

        // Reorganiza los índices del arreglo para evitar huecos
        $_SESSION['carrito'] = array_values($_SESSION['carrito']);
    }
}

// Redirige nuevamente al sistema POS
header("Location: pos.php");
exit();
?>
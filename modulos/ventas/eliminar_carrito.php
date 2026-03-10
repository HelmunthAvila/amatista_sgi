<?php
session_start();

// Verificar que exista el carrito
if(!isset($_SESSION['carrito'])){
    header("Location: pos.php");
    exit();
}

// Verificar que llegue el índice
if(isset($_GET['id'])){

    $index = $_GET['id'];

    // Validar que el índice exista en el carrito
    if(isset($_SESSION['carrito'][$index])){

        // Eliminar producto del carrito
        unset($_SESSION['carrito'][$index]);

        // Reordenar índices del arreglo
        $_SESSION['carrito'] = array_values($_SESSION['carrito']);
    }
}

// Volver al POS
header("Location: pos.php");
exit();
?>
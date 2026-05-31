<?php
session_start();

if(!isset($_SESSION['carrito'])){
    header("Location: pos.php");
    exit();
}

if(isset($_GET['id'])){
    $index = intval($_GET['id']);

    if(isset($_SESSION['carrito'][$index])){
        $nombre_prod = $_SESSION['carrito'][$index]['nombre'];
        unset($_SESSION['carrito'][$index]);
        $_SESSION['carrito'] = array_values($_SESSION['carrito']); // Reindexar
        
        $_SESSION['alerta'] = ['tipo' => 'success', 'mensaje' => "Se quitó <strong>{$nombre_prod}</strong> del carrito de compras."];
    } else {
        $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'El ítem que intenta remover no pertenece al carrito activo.'];
    }
}

header("Location: pos.php");
exit();
?>
// Control de acceso: valida sesión del usuario y evita acceso directo a módulos sin autenticación
<?php
session_start();
if(!isset($_SESSION['id_usuario'])){
    header("Location: ../../login.php");
    exit();
}
?>
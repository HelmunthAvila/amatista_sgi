<?php
include("../../conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $rol = mysqli_real_escape_string($conexion, $_POST['rol']);
    
    // Encriptamos la contraseña antes de guardarla
    $pass_input = $_POST['password'];
    $password_encriptada = password_hash($pass_input, PASSWORD_DEFAULT);

    $query = "INSERT INTO usuarios (nombre, usuario, password, rol, estado) 
              VALUES ('$nombre', '$usuario', '$password_encriptada', '$rol', 1)";
    
    if (mysqli_query($conexion, $query)) {
        header("Location: listar.php?msj=1");
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?>
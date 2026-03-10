<?php
include("../../conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $rol = $_POST['rol'];
    $estado = $_POST['estado'];
    $pass = $_POST['password'];

    // Base de la consulta
    $sql = "UPDATE usuarios SET nombre='$nombre', usuario='$usuario', rol='$rol', estado='$estado'";

    // Si el usuario ingresó una nueva contraseña, la encriptamos y la agregamos al SQL
    if (!empty($pass)) {
        $password_encriptada = password_hash($pass, PASSWORD_DEFAULT);
        $sql .= ", password='$password_encriptada'";
    }

    $sql .= " WHERE id='$id'";

    if (mysqli_query($conexion, $sql)) {
        header("Location: listar.php?msj=2");
    } else {
        echo "Error al actualizar: " . mysqli_error($conexion);
    }
}
?>
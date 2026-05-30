<?php
// Inicia la sesión para manejar autenticación de usuarios
session_start();

// Incluye el archivo de conexión a la base de datos
include("conexion.php");

// Verifica si el formulario fue enviado por método POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Limpia el valor del usuario para evitar inyección SQL
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);

    // Obtiene la contraseña ingresada por el usuario
    $pass = $_POST['password'];

    // Consulta el usuario activo en la base de datos
    $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND estado = 1";
    $res = mysqli_query($conexion, $sql);

    // Verifica si el usuario existe
    if ($u = mysqli_fetch_assoc($res)) {

        // Validación dual de contraseña: compatible con password_hash y sistemas antiguos con MD5
        if (password_verify($pass, $u['password']) || md5($pass) == $u['password']) {

            // Guarda los datos principales del usuario en la sesión
            $_SESSION['id_usuario'] = $u['id'];
            $_SESSION['nombre_usuario'] = $u['nombre'];
            $_SESSION['rol'] = $u['rol'];

            // Redirige al dashboard del sistema
            header("Location: dashboard.php");

        } else {
            // Mensaje si la contraseña es incorrecta
            $error = "Contraseña incorrecta";
        }

    } else {
        // Mensaje si el usuario no existe o está inactivo
        $error = "Usuario no encontrado o inactivo";
    }
}
?>
<?php
include("../../includes/sesion.php");
requiere_rol('admin');

// 1. Incluir conexión a la base de datos
include("../../conexion.php");
// Verificación CSRF (AM-008)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !csrf_verificar()) {
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'La sesión expiró. Recarga la página e intenta nuevamente.'];
    header("Location: listar.php");
    exit();
}


/*
----------------------------------------------------
PROCESAR ACTUALIZACIÓN DE USUARIO
----------------------------------------------------
Este archivo recibe los datos enviados desde el
formulario de edición de usuario y actualiza
la información en la base de datos.
----------------------------------------------------
*/

// 2. Verificar que el formulario se haya enviado por método POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // 3. Obtener datos enviados desde el formulario
    $id = intval($_POST['id']);
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];

    // Rol del usuario (admin, vendedor, etc.)
    $rol = $_POST['rol'];

    // Estado del usuario (activo / inactivo)
    $estado = $_POST['estado'];

    // Contraseña ingresada en el formulario
    $pass = $_POST['password'];

    /*
    ----------------------------------------------------
    CONSTRUCCIÓN DE LA CONSULTA SQL PREPARADA (AM-005)
    ----------------------------------------------------
    Si el campo password no está vacío se encripta y se
    agrega a la actualización; en caso contrario se
    conserva la contraseña actual del usuario.
    */
    if (!empty($pass)) {
        $password_encriptada = password_hash($pass, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET nombre=?, usuario=?, rol=?, estado=?, password=? WHERE id=?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "sssssi", $nombre, $usuario, $rol, $estado, $password_encriptada, $id);
    } else {
        $sql = "UPDATE usuarios SET nombre=?, usuario=?, rol=?, estado=? WHERE id=?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssssi", $nombre, $usuario, $rol, $estado, $id);
    }

    /*
    ----------------------------------------------------
    EJECUTAR CONSULTA
    ----------------------------------------------------
    */
    if (mysqli_stmt_execute($stmt)) {

        // Redirigir al listado de usuarios con mensaje de éxito
        header("Location: listar.php?msj=2");

    } else {

        // Mostrar error si falla la consulta
        echo "Error al actualizar el usuario. Inténtalo de nuevo.";

    }
}

?>
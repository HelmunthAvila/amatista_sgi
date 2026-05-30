<?php

// 1. Incluir conexión a la base de datos
include("../../conexion.php");

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
    $id = $_POST['id'];

    // Escapar caracteres especiales para evitar inyección SQL
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);

    // Rol del usuario (admin, vendedor, etc.)
    $rol = $_POST['rol'];

    // Estado del usuario (activo / inactivo)
    $estado = $_POST['estado'];

    // Contraseña ingresada en el formulario
    $pass = $_POST['password'];

    /*
    ----------------------------------------------------
    CONSTRUCCIÓN DE LA CONSULTA SQL
    ----------------------------------------------------
    Se actualizan los datos básicos del usuario
    */
    $sql = "UPDATE usuarios 
            SET nombre='$nombre',
                usuario='$usuario',
                rol='$rol',
                estado='$estado'";

    /*
    ----------------------------------------------------
    VALIDAR SI SE CAMBIÓ LA CONTRASEÑA
    ----------------------------------------------------
    Si el campo password no está vacío,
    se encripta la nueva contraseña y se agrega
    a la consulta SQL
    */
    if (!empty($pass)) {

        // Encriptar contraseña usando algoritmo seguro
        $password_encriptada = password_hash($pass, PASSWORD_DEFAULT);

        // Agregar contraseña al UPDATE
        $sql .= ", password='$password_encriptada'";
    }

    // Condición para actualizar solo el usuario seleccionado
    $sql .= " WHERE id='$id'";

    /*
    ----------------------------------------------------
    EJECUTAR CONSULTA
    ----------------------------------------------------
    */
    if (mysqli_query($conexion, $sql)) {

        // Redirigir al listado de usuarios con mensaje de éxito
        header("Location: listar.php?msj=2");

    } else {

        // Mostrar error si falla la consulta
        echo "Error al actualizar: " . mysqli_error($conexion);

    }
}

?>
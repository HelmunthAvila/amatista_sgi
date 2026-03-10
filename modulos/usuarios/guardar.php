<?php

// 1. Incluir conexión a la base de datos
include("../../conexion.php");

/*
----------------------------------------------------
PROCESAR REGISTRO DE NUEVO USUARIO
----------------------------------------------------
Este archivo recibe los datos del formulario
"Nuevo Usuario" y los guarda en la base de datos.
----------------------------------------------------
*/

// 2. Verificar que el formulario se haya enviado por método POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // 3. Obtener datos enviados desde el formulario

    // Escapar caracteres para evitar inyección SQL
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $rol = mysqli_real_escape_string($conexion, $_POST['rol']);

    /*
    ----------------------------------------------------
    ENCRIPTAR CONTRASEÑA
    ----------------------------------------------------
    Se recibe la contraseña ingresada por el usuario
    y se encripta usando password_hash()
    */
    $pass_input = $_POST['password'];

    // Generar hash seguro de la contraseña
    $password_encriptada = password_hash($pass_input, PASSWORD_DEFAULT);

    /*
    ----------------------------------------------------
    CONSULTA PARA INSERTAR EL NUEVO USUARIO
    ----------------------------------------------------
    Se guarda el usuario con estado activo por defecto
    */
    $query = "INSERT INTO usuarios (nombre, usuario, password, rol, estado) 
              VALUES ('$nombre', '$usuario', '$password_encriptada', '$rol', 1)";
    
    /*
    ----------------------------------------------------
    EJECUTAR CONSULTA
    ----------------------------------------------------
    */
    if (mysqli_query($conexion, $query)) {

        // Redirigir al listado de usuarios con mensaje de éxito
        header("Location: listar.php?msj=1");

    } else {

        // Mostrar error si la consulta falla
        echo "Error: " . mysqli_error($conexion);

    }
}

?>
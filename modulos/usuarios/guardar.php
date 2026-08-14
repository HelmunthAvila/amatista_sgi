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
PROCESAR REGISTRO DE NUEVO USUARIO
----------------------------------------------------
Este archivo recibe los datos del formulario
"Nuevo Usuario" y los guarda en la base de datos.
----------------------------------------------------
*/

// 2. Verificar que el formulario se haya enviado por método POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // 3. Obtener datos enviados desde el formulario

    // Obtener datos enviados desde el formulario
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $rol = $_POST['rol'];

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
              VALUES (?, ?, ?, ?, 1)";
    
    /*
    ----------------------------------------------------
    EJECUTAR CONSULTA PREPARADA (AM-005)
    ----------------------------------------------------
    */
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $nombre, $usuario, $password_encriptada, $rol);
    if (mysqli_stmt_execute($stmt)) {

        // Redirigir al listado de usuarios con mensaje de éxito
        header("Location: listar.php?msj=1");

    } else {

        // Mostrar error si la consulta falla
        echo "Error al registrar el usuario. Inténtalo de nuevo.";

    }
}

?>
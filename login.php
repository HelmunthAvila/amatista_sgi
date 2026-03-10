<?php
session_start();
include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $pass = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND estado = 1";
    $res = mysqli_query($conexion, $sql);

    if ($u = mysqli_fetch_assoc($res)) {
        // Validación dual: Soporta hash nuevo y MD5 antiguo
        if (password_verify($pass, $u['password']) || md5($pass) == $u['password']) {
            $_SESSION['id_usuario'] = $u['id'];
            $_SESSION['nombre_usuario'] = $u['nombre'];
            $_SESSION['rol'] = $u['rol'];
            header("Location: dashboard.php");
        } else {
            $error = "Contraseña incorrecta";
        }
    } else {
        $error = "Usuario no encontrado o inactivo";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AMATISTA SGI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background: linear-gradient(135deg, #2c3e50, #8a2be2); height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; }
        .card-login { width: 100%; max-width: 400px; border: none; border-radius: 20px; }
        .btn-amatista { background-color: #8a2be2; color: white; border: none; }
        .btn-amatista:hover { background-color: #7a1fd1; color: white; }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="card card-login shadow-lg p-4">
            <div class="text-center mb-4">
                <h2 class="fw-bold text-dark"> AMATISTA SGI</h2>
                <p class="text-muted small">Ingresa tus credenciales de acceso</p>
            </div>

            <?php if(isset($error)): ?>
                <div class="alert alert-danger py-2 small text-center"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Usuario</label>
                    <input type="text" name="usuario" class="form-control form-control-lg" required>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">Contraseña</label>
                    <input type="password" name="password" class="form-control form-control-lg" required>
                </div>

                <button type="submit" class="btn btn-amatista btn-lg w-100 shadow-sm">Ingresar al Sistema</button>
            </form>
            
            <div class="text-center mt-4">
                <small class="text-muted">&copy; 2026 Amatista SGI</small>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
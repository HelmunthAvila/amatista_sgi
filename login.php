<?php
session_start();
include("conexion.php"); //

if(isset($_POST['usuario'])){
    $usuario = $_POST['usuario'];
    $password = md5($_POST['password']); //

    $sql = "SELECT * FROM usuarios WHERE usuario='$usuario' AND password='$password'";
    $resultado = mysqli_query($conexion,$sql);

    if(mysqli_num_rows($resultado)>0){
        $_SESSION['usuario']=$usuario;
        header("Location: dashboard.php");
    }else{
        $error = "Usuario o contraseña incorrectos";
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
    <style>
        body { background: linear-gradient(135deg, #2c3e50, #8a2be2); height: 100 vh; display: flex; align-items: center; justify-content: center; margin: 0; }
        .card-login { width: 100%; max-width: 400px; border: none; border-radius: 15px; }
        .btn-amatista { background-color: #8a2be2; color: white; border: none; }
        .btn-amatista:hover { background-color: #7a1fd1; color: white; }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="card card-login shadow-lg p-4">
            <div class="text-center mb-4">
                <h2 class="fw-bold text-dark">💎 AMATISTA SGI</h2>
                <p class="text-muted">Ingresa tus credenciales</p>
            </div>

            <?php if(isset($error)): ?>
                <div class="alert alert-danger py-2 small text-center"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Usuario</label>
                    <input type="text" name="usuario" class="form-control form-control-lg" placeholder="Tu usuario" required>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">Contraseña</label>
                    <input type="password" name="password" class="form-control form-control-lg" placeholder="••••••••" required>
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
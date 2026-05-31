<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("conexion.php");

$error = ""; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $pass = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND estado = 1";
    $res = mysqli_query($conexion, $sql);

    if ($u = mysqli_fetch_assoc($res)) {
        if (password_verify($pass, $u['password']) || md5($pass) == $u['password']) {
            $_SESSION['id_usuario'] = $u['id'];
            $_SESSION['nombre_usuario'] = $u['nombre'];
            $_SESSION['rol'] = $u['rol'];

            header("Location: dashboard.php");
            exit; 
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
    <title>Login - Amatista SGI</title>
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #512da8; /* El morado característico de tu menú */
            --primary-hover: #432293;
            --bg-color: #f8f9fa;      /* El fondo gris claro de tu dashboard */
            --text-color: #333333;
            --card-bg: #ffffff;
            --border-radius: 12px;     /* Bordes redondeados sutiles como tus tarjetas */
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--bg-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: var(--text-color);
        }

        .login-container {
            background-color: var(--card-bg);
            padding: 2.5rem;
            border-radius: var(--border-radius);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .brand-title {
            color: var(--primary-color);
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .system-subtitle {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 2rem;
        }

        .form-group {
            text-align: left;
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            border: 1px solid #dddddd;
            border-radius: 8px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(81, 45, 168, 0.15);
        }

        .btn-submit {
            width: 100%;
            padding: 0.75rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 1rem;
        }

        .btn-submit:hover {
            background-color: var(--primary-hover);
        }

        .error-message {
            background-color: #ffeef0;
            color: #d9383a;
            padding: 0.75rem;
            border-radius: 6px;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #d9383a;
            text-align: left;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="brand-title">Amatista SGI</div>
        <div class="system-subtitle">Gestión e Inteligencia de Calzado</div>

        <?php if (!empty($error)): ?>
            <div class="error-message">
                ⚠ <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="usuario">Usuario</label>
                <input type="text" name="usuario" id="usuario" placeholder="Ej: helmunth" required autocomplete="username">
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" placeholder="••••••••" required autocomplete="current-password">
            </div>

            <button type="submit" class="btn-submit">Ingresar al Sistema</button>
        </form>
    </div>

</body>
</html>
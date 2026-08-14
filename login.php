<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("includes/iniciar_sesion.php");
include("conexion.php");

$error = ""; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!csrf_verificar()) {
        $error = "La sesión expiró. Recarga la página e inténtalo de nuevo.";
    } else {
        $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
        $pass = $_POST['password'];

        $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND estado = 1";
        $res = mysqli_query($conexion, $sql);

        if ($u = mysqli_fetch_assoc($res)) {
            $password_valida = false;

            if (password_verify($pass, $u['password'])) {
                $password_valida = true;
            } elseif (md5($pass) == $u['password']) {
                // Hash MD5 legado: migra automáticamente a bcrypt en el primer inicio de sesión (AM-004)
                $password_valida = true;
                $nuevo_hash = password_hash($pass, PASSWORD_DEFAULT);
                $nuevo_hash_escapado = mysqli_real_escape_string($conexion, $nuevo_hash);
                mysqli_query($conexion, "UPDATE usuarios SET password = '$nuevo_hash_escapado' WHERE id = " . intval($u['id']));
            }

            if ($password_valida) {
                session_regenerate_id(true); // Previene fijación de sesión (AM-007)

                $_SESSION['id_usuario'] = $u['id'];
                $_SESSION['nombre_usuario'] = $u['nombre'];
                $_SESSION['rol'] = $u['rol'];

                // Redirección según rol: el cajero opera únicamente en el POS
                if ($_SESSION['rol'] === 'admin') {
                    header("Location: dashboard.php");
                } else {
                    header("Location: modulos/ventas/pos.php");
                }
                exit; 
            } else {
                $error = "Contraseña incorrecta";
            }
        } else {
            $error = "Usuario no encontrado o inactivo";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Amatista SGI</title>
    <!-- Iconos Bootstrap (misma fuente que el resto del sistema) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #512da8; /* El morado característico de Amatista */
            --primary-hover: #432293;
            --primary-deep: #3b1f7a;
            --primary-soft: #f0ebfa;
            --bg-color: #f8f9fa;
            --text-color: #333333;
            --card-bg: #ffffff;
            --border-radius: 20px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #eef0f6 0%, #f8f9fa 45%, #f4effb 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 24px;
            color: var(--text-color);
        }

        /* ===== Tarjeta principal de dos paneles ===== */
        .login-card {
            display: flex;
            width: 100%;
            max-width: 980px;
            background: var(--card-bg);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(81, 45, 168, 0.14);
        }

        /* ===== Panel izquierdo: presentación empresarial ===== */
        .brand-side {
            flex: 0 0 46%;
            background: linear-gradient(150deg, #6d3fc4 0%, #512da8 48%, #3b1f7a 100%);
            color: #ffffff;
            padding: 46px 38px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        /* Círculos decorativos de fondo */
        .brand-side::before,
        .brand-side::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
        }
        .brand-side::before { width: 300px; height: 300px; top: -130px; right: -130px; }
        .brand-side::after  { width: 220px; height: 220px; bottom: -90px; left: -90px; }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.45rem;
            font-weight: 700;
            letter-spacing: 0.4px;
            position: relative;
            z-index: 1;
        }
        .brand-logo i { font-size: 1.75rem; }

        .brand-hero { position: relative; z-index: 1; }
        .brand-title {
            font-size: 1.9rem;
            font-weight: 700;
            margin: 26px 0 12px;
            line-height: 1.25;
        }
        .brand-subtitle {
            opacity: 0.85;
            font-size: 0.95rem;
            line-height: 1.65;
            margin-bottom: 26px;
        }

        .features { list-style: none; display: flex; flex-direction: column; gap: 15px; }
        .features li { display: flex; align-items: flex-start; gap: 13px; }
        .features .icon-box {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            flex-shrink: 0;
        }
        .features strong { display: block; font-size: 0.92rem; font-weight: 600; }
        .features span { font-size: 0.8rem; opacity: 0.72; line-height: 1.4; }

        .brand-footer {
            margin-top: 28px;
            font-size: 0.78rem;
            opacity: 0.6;
            position: relative;
            z-index: 1;
        }

        /* ===== Panel derecho: formulario de acceso ===== */
        .form-side {
            flex: 1;
            padding: 52px 54px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-heading h2 { font-size: 1.6rem; font-weight: 700; color: #222222; }
        .form-heading p { color: #8a8a8a; font-size: 0.9rem; margin: 6px 0 26px; }

        .form-group {
            text-align: left;
            margin-bottom: 1.2rem;
        }
        .form-group label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            margin-bottom: 0.45rem;
            color: #555555;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .input-wrap { position: relative; }
        .input-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #a99cc9;
            font-size: 1rem;
            pointer-events: none;
        }
        .input-wrap input {
            width: 100%;
            padding: 0.78rem 1rem 0.78rem 42px;
            font-size: 0.95rem;
            border: 1px solid #dddddd;
            border-radius: 10px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #fafafa;
        }
        .input-wrap input:focus {
            border-color: var(--primary-color);
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(81, 45, 168, 0.15);
        }

        .btn-submit {
            width: 100%;
            padding: 0.8rem;
            background: linear-gradient(135deg, #6d3fc4 0%, #512da8 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.2s, background 0.2s;
            margin-top: 0.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-submit:hover {
            background: linear-gradient(135deg, #5f36a8 0%, var(--primary-hover) 100%);
            box-shadow: 0 8px 20px rgba(81, 45, 168, 0.28);
            transform: translateY(-1px);
        }

        .error-message {
            background-color: #ffeef0;
            color: #d9383a;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-size: 0.85rem;
            margin-bottom: 1.4rem;
            border-left: 4px solid #d9383a;
            text-align: left;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-foot {
            margin-top: 20px;
            font-size: 0.78rem;
            color: #9a9a9a;
            text-align: center;
        }

        /* ===== Responsive: tablet y móvil ===== */
        @media (max-width: 880px) {
            .login-card { flex-direction: column; max-width: 460px; }
            .brand-side { padding: 30px 26px; }
            .brand-title { font-size: 1.5rem; margin-top: 18px; }
            .features { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
            .form-side { padding: 38px 30px; }
        }

        @media (max-width: 480px) {
            body { padding: 14px; }
            .features { grid-template-columns: 1fr; }
            .brand-footer { margin-top: 18px; }
        }
    </style>
</head>
<body>

    <div class="login-card">

        <!-- PANEL INFORMATIVO: qué es Amatista SGI y para qué sirve -->
        <aside class="brand-side">
            <div class="brand-logo">
                <i class="bi bi-gem"></i> Amatista SGI
            </div>

            <div class="brand-hero">
                <h1 class="brand-title">Tu negocio, en un solo sistema</h1>
                <p class="brand-subtitle">
                    Sistema de inventario, ventas y administración para Amatista Zapatería:
                    ágil, seguro y profesional.
                </p>

                <ul class="features">
                    <li>
                        <span class="icon-box"><i class="bi bi-calculator"></i></span>
                        <span>
                            <strong>Punto de Venta (POS)</strong>
                            <span>Ventas y tickets al instante.</span>
                        </span>
                    </li>
                    <li>
                        <span class="icon-box"><i class="bi bi-box-seam"></i></span>
                        <span>
                            <strong>Inventario</strong>
                            <span>Stock, tallas y precios en tiempo real.</span>
                        </span>
                    </li>
                    <li>
                        <span class="icon-box"><i class="bi bi-people"></i></span>
                        <span>
                            <strong>Clientes</strong>
                            <span>Directorio de clientes.</span>
                        </span>
                    </li>
                    <li>
                        <span class="icon-box"><i class="bi bi-truck"></i></span>
                        <span>
                            <strong>Proveedores</strong>
                            <span>Canales de suministro.</span>
                        </span>
                    </li>
                    <li>
                        <span class="icon-box"><i class="bi bi-file-earmark-bar-graph"></i></span>
                        <span>
                            <strong>Reportes</strong>
                            <span>Ventas, stock bajo y exportación a Excel.</span>
                        </span>
                    </li>
                    <li>
                        <span class="icon-box"><i class="bi bi-shield-lock"></i></span>
                        <span>
                            <strong>Acceso Seguro</strong>
                            <span>Usuarios y contraseñas protegidas.</span>
                        </span>
                    </li>
                </ul>
            </div>

            <div class="brand-footer">
                © 2026 Amatista SGI · Gestión de Inventario y Ventas
            </div>
        </aside>

        <!-- PANEL DE ACCESO: formulario de inicio de sesión -->
        <main class="form-side">
            <div class="form-heading">
                <h2>Iniciar Sesión</h2>
                <p>Accede con tus credenciales</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="error-message">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                <div class="form-group">
                    <label for="usuario">Usuario</label>
                    <div class="input-wrap">
                        <i class="bi bi-person"></i>
                        <input type="text" name="usuario" id="usuario" placeholder="Ej: helmunth" required autocomplete="username">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="input-wrap">
                        <i class="bi bi-lock"></i>
                        <input type="password" name="password" id="password" placeholder="••••••••" required autocomplete="current-password">
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    Ingresar <i class="bi bi-box-arrow-in-right"></i>
                </button>
            </form>

            <p class="form-foot">
                ¿Problemas? Contacta al administrador.
            </p>
        </main>

    </div>

</body>
</html>

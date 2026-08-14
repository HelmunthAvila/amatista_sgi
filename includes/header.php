<?php
// Inicia la sesión si aún no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Detecta la ruta actual de manera limpia
$archivo_actual = $_SERVER['PHP_SELF'];
$base = (strpos($archivo_actual, '/modulos/') !== false) ? "../../" : "";

// Seguridad básica: valida sesión activa (defensa en profundidad junto con includes/sesion.php)
if (!isset($_SESSION['id_usuario'])) {
    header("Location: " . $base . "login.php");
    exit();
}

// Menú por rol: el cajero únicamente opera en el Punto de Venta (POS)
$es_admin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AMATISTA SGI - Sistema de Gestión</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Iconos Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --sidebar-width: 260px;
            /* MEJORA: Tono exacto Amatista extraído de image_978a3b.png */
            --amatista-primary: #581c87; 
            --amatista-hover: rgba(255, 255, 255, 0.08); 
            --amatista-active: rgba(255, 255, 255, 0.15); 
        }

        body {
            background-color: #f8fafc;
            margin: 0;
            display: flex;
            min-height: 100vh;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        /* MEJORA: Sidebar con Flexbox en lugar de posicionamiento absoluto para el botón inferior */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--amatista-primary);
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            padding: 25px 15px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.05);
        }

        #main-wrapper {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            padding: 40px;
            min-height: 100vh;
            background-color: #f1f5f9; /* Un fondo ligeramente más gris resalta el contenido blanco */
        }

        .logo-container {
            padding: 5px 10px 20px 10px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }

        .logo-title {
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 10px 16px;
            border-radius: 12px; /* MEJORA: Bordes más redondeados y modernos */
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 0.95rem;
        }

        .nav-link:hover {
            background: var(--amatista-hover);
            color: white;
            padding-left: 20px; /* MEJORA: Sutil efecto dinámico al pasar el cursor */
        }

        .nav-link.active {
            background: var(--amatista-active);
            color: white;
            font-weight: 600;
        }

        /* MEJORA: Categorías con mejor legibilidad */
        .menu-category {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: rgba(255, 255, 255, 0.45);
            margin: 20px 0 8px 14px;
            font-weight: 700;
        }

        .nav-link i {
            font-size: 1.1rem;
        }

        /* MEJORA: Estilo limpio para el botón de salida */
        .logout-container {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 15px;
        }
        
        .logout-link {
            color: rgba(255, 255, 255, 0.6) !important;
        }
        
        .logout-link:hover {
            color: #ef4444 !important; /* Rojo suave en el hover de salir */
            background: rgba(239, 68, 68, 0.1);
        }
    </style>
</head>

<body>

<nav id="sidebar">
    <!-- Contenedor Superior (Logo + Navegación) -->
    <div>
        <div class="logo-container">
            <h4 class="logo-title mb-0 text-white">
                <i class="bi bi-gem me-2"></i>Amatista SGI
            </h4>
        </div>

        <div class="nav flex-column">
            <?php if ($es_admin): ?>
            <!-- Dashboard -->
            <a href="<?= $base ?>dashboard.php" class="nav-link <?= (basename($archivo_actual) == 'dashboard.php') ? 'active' : '' ?>">
                <i class="bi bi-grid-1x2 me-3"></i> Dashboard
            </a>
            <?php endif; ?>

            <div class="menu-category">Operaciones</div>

            <!-- Ventas (POS) -->
            <a href="<?= $base ?>modulos/ventas/pos.php" class="nav-link <?= (strpos($archivo_actual, 'ventas/') !== false) ? 'active' : '' ?>">
                <i class="bi bi-calculator me-3"></i> Ventas (POS)
            </a>

            <?php if ($es_admin): ?>
            <!-- Inventario -->
            <a href="<?= $base ?>modulos/productos/listar.php" class="nav-link <?= (strpos($archivo_actual, 'productos/') !== false) ? 'active' : '' ?>">
                <i class="bi bi-box-seam me-3"></i> Inventario
            </a>

            <div class="menu-category">Administración</div>

            <!-- Clientes -->
            <a href="<?= $base ?>modulos/clientes/listar.php" class="nav-link <?= (strpos($archivo_actual, 'clientes/') !== false) ? 'active' : '' ?>">
                <i class="bi bi-people me-3"></i> Clientes
            </a>

            <!-- Proveedores -->
            <a href="<?= $base ?>modulos/proveedores/listar.php" class="nav-link <?= (strpos($archivo_actual, 'proveedores/') !== false) ? 'active' : '' ?>">
                <i class="bi bi-truck me-3"></i> Proveedores
            </a>

            <div class="menu-category">Análisis</div>

            <!-- Reportes -->
            <a href="<?= $base ?>modulos/reportes/inventario.php" class="nav-link <?= (strpos($archivo_actual, 'reportes/') !== false) ? 'active' : '' ?>">
                <i class="bi bi-file-earmark-bar-graph me-3"></i> Reportes
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Contenedor Inferior (Botón salir separado limpiamente) -->
    <div class="logout-container">
        <a href="<?= $base ?>logout.php" class="nav-link logout-link small">
            <i class="bi bi-box-arrow-left me-2"></i> Salir del sistema
        </a>
    </div>
</nav>

<div id="main-wrapper">
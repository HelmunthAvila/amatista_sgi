<?php
/**
 * AMATISTA SGI - Header Principal
 * Resuelve: Error de sesión, Rutas relativas y Diseño Responsivo
 */

// 1. Gestión de Sesión Segura
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Lógica de Rutas Dinámicas
// Detecta si estamos en la raíz o dentro de /modulos/ para ajustar los enlaces
$archivo_actual = $_SERVER['PHP_SELF'];
$base = (strpos($archivo_actual, '/modulos/') !== false) ? "../../" : "";

// 3. Verificación de seguridad básica
if (!isset($_SESSION['usuario'])) {
    // Si no hay sesión, redirigir al login (ajustar ruta según tu estructura)
    // header("Location: " . $base . "index.php"); 
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AMATISTA SGI - Sistema de Gestión</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --sidebar-width: 260px;
            --amatista-primary: #4c1d95; /* Morado Amatista */
            --amatista-hover: rgba(255, 255, 255, 0.1);
        }

        body {
            background-color: #f8fafc;
            margin: 0;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Fijo y Estilizado */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--amatista-primary);
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            padding: 20px 15px;
            transition: all 0.3s;
            border-right: 1px solid rgba(255,255,255,0.1);
        }

        /* Contenedor Principal: Empuja el contenido a la derecha */
        #main-wrapper {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            padding: 30px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Estilos de Navegación */
        .nav-link {
            color: rgba(255, 255, 255, 0.7);
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: 0.2s;
        }

        .nav-link:hover {
            background: var(--amatista-hover);
            color: white;
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 600;
        }

        .menu-category {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.4);
            margin: 20px 0 10px 15px;
        }

        .logo-container {
            padding: 10px 15px 30px 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<nav id="sidebar">
    <div class="logo-container">
        <h4 class="fw-bold mb-0 text-white">
            <i class=""></i>Amatista SGI
        </h4>
    </div>

    <div class="nav flex-column">
        <a href="<?= $base ?>dashboard.php" class="nav-link <?= (basename($archivo_actual) == 'dashboard.php') ? 'active' : '' ?>">
            <i class="bi bi-grid-1x2-fill me-3"></i> Dashboard
        </a>

        <div class="menu-category">Operaciones</div>
        <a href="<?= $base ?>modulos/ventas/pos.php" class="nav-link <?= (strpos($archivo_actual, 'ventas/') !== false) ? 'active' : '' ?>">
            <i class="bi bi-calculator-fill me-3"></i> Ventas (POS)
        </a>
        
        <a href="<?= $base ?>modulos/productos/listar.php" class="nav-link <?= (strpos($archivo_actual, 'productos/') !== false) ? 'active' : '' ?>">
            <i class="bi bi-box-seam-fill me-3"></i> Inventario
        </a>

        <div class="menu-category">Administración</div>
        <a href="<?= $base ?>modulos/clientes/listar.php" class="nav-link <?= (strpos($archivo_actual, 'clientes/') !== false) ? 'active' : '' ?>">
            <i class="bi bi-people-fill me-3"></i> Clientes
        </a>
        <a href="<?= $base ?>modulos/proveedores/listar.php" class="nav-link <?= (strpos($archivo_actual, 'proveedores/') !== false) ? 'active' : '' ?>">
            <i class="bi bi-truck me-3"></i> Proveedores
        </a>

        <div class="menu-category">Análisis</div>
        <a href="<?= $base ?>modulos/reportes/inventario.php" class="nav-link <?= (strpos($archivo_actual, 'reportes/') !== false) ? 'active' : '' ?>">
            <i class="bi bi-file-earmark-bar-graph-fill me-3"></i> Reportes
        </a>
    </div>

    <div class="position-absolute bottom-0 start-0 w-100 p-3">
        <a href="<?= $base ?>logout.php" class="nav-link text-white-50 small">
            <i class="bi bi-box-arrow-left me-2"></i> Salir del sistema
        </a>
    </div>
</nav>

<div id="main-wrapper">
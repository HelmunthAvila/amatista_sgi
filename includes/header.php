<?php
// Inicia la sesión si aún no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Detecta la ruta actual para ajustar enlaces dependiendo si está en /modulos/ o en la raíz
$archivo_actual = $_SERVER['PHP_SELF'];
$base = (strpos($archivo_actual, '/modulos/') !== false) ? "../../" : "";

// Verifica si existe sesión de usuario (seguridad básica del sistema)
if (!isset($_SESSION['usuario'])) {
    // Redirección al login si no hay sesión activa
    // header("Location: " . $base . "index.php"); 
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <!-- Permite diseño responsive en móviles -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Título del sistema -->
    <title>AMATISTA SGI - Sistema de Gestión</title>
    
    <!-- Carga de Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Librería de iconos Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --sidebar-width: 260px; /* Ancho del menú lateral */
            --amatista-primary: #4c1d95; /* Color principal del sistema */
            --amatista-hover: rgba(255, 255, 255, 0.1); /* Color hover del menú */
        }

        /* Estilos base del body */
        body {
            background-color: #f8fafc;
            margin: 0;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar lateral fijo */
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

        /* Contenedor principal donde se carga el contenido */
        #main-wrapper {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            padding: 30px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Estilo general de los enlaces del menú */
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

        /* Efecto hover del menú */
        .nav-link:hover {
            background: var(--amatista-hover);
            color: white;
        }

        /* Estilo del enlace activo */
        .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 600;
        }

        /* Categorías del menú */
        .menu-category {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.4);
            margin: 20px 0 10px 15px;
        }

        /* Contenedor del logo del sistema */
        .logo-container {
            padding: 10px 15px 30px 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

<!-- Menú lateral principal del sistema -->
<nav id="sidebar">

    <!-- Logo o nombre del sistema -->
    <div class="logo-container">
        <h4 class="fw-bold mb-0 text-white">
            <i class=""></i>Amatista SGI
        </h4>
    </div>

    <!-- Menú de navegación principal -->
    <div class="nav flex-column">

        <!-- Enlace al dashboard -->
        <a href="<?= $base ?>dashboard.php" class="nav-link <?= (basename($archivo_actual) == 'dashboard.php') ? 'active' : '' ?>">
            <i class="bi bi-grid-1x2-fill me-3"></i> Dashboard
        </a>

        <!-- Categoría operaciones -->
        <div class="menu-category">Operaciones</div>

        <!-- Punto de venta -->
        <a href="<?= $base ?>modulos/ventas/pos.php" class="nav-link <?= (strpos($archivo_actual, 'ventas/') !== false) ? 'active' : '' ?>">
            <i class="bi bi-calculator-fill me-3"></i> Ventas (POS)
        </a>
        
        <!-- Gestión de inventario -->
        <a href="<?= $base ?>modulos/productos/listar.php" class="nav-link <?= (strpos($archivo_actual, 'productos/') !== false) ? 'active' : '' ?>">
            <i class="bi bi-box-seam-fill me-3"></i> Inventario
        </a>

        <!-- Categoría administración -->
        <div class="menu-category">Administración</div>

        <!-- Gestión de clientes -->
        <a href="<?= $base ?>modulos/clientes/listar.php" class="nav-link <?= (strpos($archivo_actual, 'clientes/') !== false) ? 'active' : '' ?>">
            <i class="bi bi-people-fill me-3"></i> Clientes
        </a>

        <!-- Gestión de proveedores -->
        <a href="<?= $base ?>modulos/proveedores/listar.php" class="nav-link <?= (strpos($archivo_actual, 'proveedores/') !== false) ? 'active' : '' ?>">
            <i class="bi bi-truck me-3"></i> Proveedores
        </a>

        <!-- Categoría análisis -->
        <div class="menu-category">Análisis</div>

        <!-- Módulo de reportes -->
        <a href="<?= $base ?>modulos/reportes/inventario.php" class="nav-link <?= (strpos($archivo_actual, 'reportes/') !== false) ? 'active' : '' ?>">
            <i class="bi bi-file-earmark-bar-graph-fill me-3"></i> Reportes
        </a>
    </div>

    <!-- Botón para cerrar sesión -->
    <div class="position-absolute bottom-0 start-0 w-100 p-3">
        <a href="<?= $base ?>logout.php" class="nav-link text-white-50 small">
            <i class="bi bi-box-arrow-left me-2"></i> Salir del sistema
        </a>
    </div>

</nav>

<!-- Contenedor principal donde se cargan los módulos -->
<div id="main-wrapper">
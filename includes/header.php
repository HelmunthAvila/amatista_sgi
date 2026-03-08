<?php
// Evita el error naranja de "session already active"
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lógica para que los links funcionen desde cualquier carpeta
$archivo_actual = $_SERVER['PHP_SELF'];
$es_modulo = (strpos($archivo_actual, '/modulos/') !== false);
$base = $es_modulo ? "../../" : "";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AMATISTA SGI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root { 
            --sidebar-width: 260px; 
            --color-amatista: #4c1d95; 
        }
        
        body { 
            background-color: #f8fafc; 
            margin: 0; 
            overflow-x: hidden;
        }

        /* Sidebar Fijo */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--color-amatista);
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            padding: 20px 15px;
            transition: all 0.3s;
        }

        /* Área de contenido con MARGEN para no solaparse */
        #content-area {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            padding: 30px;
            position: relative;
        }

        .nav-link { 
            color: rgba(255,255,255,0.8); 
            border-radius: 10px; 
            margin-bottom: 8px;
            padding: 12px;
        }
        
        .nav-link:hover, .nav-link.active { 
            background: rgba(255,255,255,0.15); 
            color: white; 
        }
    </style>
</head>
<body>

<div id="sidebar">
    <div class="text-center mb-5">
        <h4 class="fw-bold"><i class="bi bi-gem me-2"></i>AMATISTA SGI</h4>
    </div>
    
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="<?= $base ?>dashboard.php" class="nav-link <?= (basename($archivo_actual) == 'dashboard.php') ? 'active' : '' ?>">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= $base ?>modulos/productos/listar.php" class="nav-link <?= (strpos($archivo_actual, 'productos/') !== false) ? 'active' : '' ?>">
                <i class="bi bi-box-seam me-2"></i> Inventario
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= $base ?>modulos/ventas/listar.php" class="nav-link <?= (strpos($archivo_actual, 'ventas/') !== false) ? 'active' : '' ?>">
                <i class="bi bi-cart-check me-2"></i> Ventas
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= $base ?>modulos/reportes/inventario.php" class="nav-link <?= (strpos($archivo_actual, 'reportes/') !== false) ? 'active' : '' ?>">
                <i class="bi bi-graph-up-arrow me-2"></i> Reportes
            </a>
        </li>
    </ul>

    <div class="position-absolute bottom-0 start-0 w-100 p-3">
        <hr class="text-white-50">
        <a href="<?= $base ?>logout.php" class="nav-link text-white-50 small">
            <i class="bi bi-box-arrow-left me-2"></i> Salir del sistema
        </a>
    </div>
</div>

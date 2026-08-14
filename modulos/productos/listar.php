<?php
include("../../includes/sesion.php");
requiere_rol('admin');
// Iniciamos sesión al principio para renderizar las notificaciones instantáneas

// Incluye la conexión a la base de datos y el encabezado general del sistema
include("../../conexion.php");
include("../../includes/header.php");

// 1. Configurar la cantidad de registros por página
$por_pagina = 10;

// 2. Determinar la página actual
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_actual < 1) { $pagina_actual = 1; }

// 3. Calcular el offset (inicio del límite para SQL)
$offset = ($pagina_actual - 1) * $por_pagina;

// Captura los filtros enviados por la URL (GET)
$busqueda = $_GET['busqueda'] ?? '';
$filtro_stock = $_GET['filtro_stock'] ?? '';


// --- CONSULTA PARA CONTAR EL TOTAL DE REGISTROS (Necesario para la paginación) ---
$query_conteo = "SELECT COUNT(*) as total_registros FROM productos WHERE 1=1";

if (!empty($busqueda)) {
    $query_conteo .= " AND (nombre LIKE ? OR marca LIKE ?)";
}

if ($filtro_stock === 'bajo') {
    $query_conteo .= " AND stock <= 5";
} elseif ($filtro_stock === 'disponible') {
    $query_conteo .= " AND stock > 5";
}

// Ejecuta el conteo con consulta preparada (AM-005)
$stmt_conteo = mysqli_prepare($conexion, $query_conteo);
if (!empty($busqueda)) {
    $busqueda_like = "%" . $busqueda . "%";
    mysqli_stmt_bind_param($stmt_conteo, "ss", $busqueda_like, $busqueda_like);
}
mysqli_stmt_execute($stmt_conteo);
$fila_conteo = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_conteo));
$total_registros = $fila_conteo['total_registros'];
mysqli_stmt_close($stmt_conteo);

// Calcular el total de páginas necesarias
$total_paginas = ceil($total_registros / $por_pagina);


// --- CONSULTA PRINCIPAL CON LIMIT Y OFFSET ---
$query = "SELECT * FROM productos WHERE 1=1";

if (!empty($busqueda)) {
    $query .= " AND (nombre LIKE ? OR marca LIKE ?)";
}

if ($filtro_stock === 'bajo') {
    $query .= " AND stock <= 5";
} elseif ($filtro_stock === 'disponible') {
    $query .= " AND stock > 5";
}

// Ordena los productos alfabéticamente por nombre con los límites de paginación
$query .= " ORDER BY nombre ASC LIMIT $por_pagina OFFSET $offset";

// Ejecuta la consulta preparada (AM-005)
$stmt = mysqli_prepare($conexion, $query);
if (!empty($busqueda)) {
    $busqueda_like = "%" . $busqueda . "%";
    mysqli_stmt_bind_param($stmt, "ss", $busqueda_like, $busqueda_like);
}
mysqli_stmt_execute($stmt);
$productos = mysqli_stmt_get_result($stmt);

if (!$productos) {
    die("Error al consultar la información. Inténtalo de nuevo.");
}

// Conservar los filtros activos al cambiar de página
$params_busqueda = "";
if(!empty($busqueda)){ $params_busqueda .= "&busqueda=" . urlencode($busqueda); }
if(!empty($filtro_stock)){ $params_busqueda .= "&filtro_stock=" . urlencode($filtro_stock); }
?>

<!-- Estilos unificados basados en la identidad visual de Amatista SGI -->
<style>
    :root {
        --primary-color: #512da8;
        --primary-hover: #432293;
        --accent-success: #2e7d32;
        --accent-danger: #c62828;
        --bg-light-gray: #f8f9fa;
        --border-radius-card: 16px;
    }

    .btn-amatista-primary {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
        color: #ffffff !important;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .btn-amatista-primary:hover {
        background-color: var(--primary-hover) !important;
        border-color: var(--primary-hover) !important;
        transform: translateY(-1px);
    }

    .btn-amatista-secondary {
        background-color: #f1f3f5 !important;
        border: 1px solid #dee2e6 !important;
        color: #495057 !important;
        font-weight: 600;
    }
    .btn-amatista-secondary:hover {
        background-color: #e9ecef !important;
    }

    .card-custom {
        border-radius: var(--border-radius-card) !important;
        border: none !important;
        background-color: #ffffff;
    }

    .form-control-custom {
        border-radius: 10px !important;
        border: 1px solid #ced4da !important;
        padding: 0.6rem 1rem;
    }
    .form-control-custom:focus {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 0.25rem rgba(81, 45, 168, 0.15) !important;
    }

    .table-custom-header {
        background-color: #f8f9fa !important;
        color: #495057;
        font-weight: 600;
    }
    
    .table-hover tbody tr:hover {
        background-color: #fcfbfe !important;
    }

    /* Badges de Talla y Color en tonos pastel */
    .badge-pastel-purple {
        background-color: #f0ebfa !important;
        color: var(--primary-color) !important;
        font-weight: 600;
        padding: 0.5em 0.8em;
        border-radius: 6px;
    }
    .badge-pastel-gray {
        background-color: #f1f3f5 !important;
        color: #495057 !important;
        font-weight: 600;
        padding: 0.5em 0.8em;
        border-radius: 6px;
    }

    /* Estados de stock corporativos */
    .badge-stock-success {
        background-color: #e8f5e9 !important;
        color: var(--accent-success) !important;
        font-weight: bold;
        padding: 0.5em 0.85em;
    }
    .badge-stock-danger {
        background-color: #ffebee !important;
        color: var(--accent-danger) !important;
        font-weight: bold;
        padding: 0.5em 0.85em;
    }

    /* Estilos personalizados para la paginación Amatista */
    .pagination .page-link {
        color: var(--primary-color);
        border: none;
        padding: 0.6rem 0.9rem;
        margin: 0 2px;
        border-radius: 8px;
    }
    .pagination .page-item.active .page-link {
        background-color: var(--primary-color) !important;
        color: #fff !important;
    }
    .pagination .page-link:hover {
        background-color: #f0ebfa;
        color: var(--primary-hover);
    }
</style>

<div class="container-fluid px-4 py-3">

    <!-- ALERTA INTELIGENTE CON TEMPORIZADOR DE CIERRE -->
    <?php if(isset($_SESSION['alerta'])): ?>
        <div id="alerta-automatica" class="alert alert-<?php echo $_SESSION['alerta']['tipo']; ?> alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4 p-3" role="alert">
            <div class="d-flex align-items-center">
                <?php if($_SESSION['alerta']['tipo'] == 'success'): ?>
                    <i class="bi bi-check-circle-fill me-3 fs-4 text-success"></i>
                <?php else: ?>
                    <i class="bi bi-exclamation-triangle-fill me-3 fs-4 text-danger"></i>
                <?php endif; ?>
                <div>
                    <?php echo $_SESSION['alerta']['mensaje']; ?>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['alerta']); ?>
    <?php endif; ?>

    <!-- Encabezado del Módulo -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px;">Inventario de Calzado</h2>
            <p class="text-muted small mb-0">Control detallado de existencias, tallas y modelos del almacén.</p>
        </div>

        <a href="agregar.php" class="btn btn-amatista-primary rounded-pill px-4 shadow-sm d-flex align-items-center">
            <i class="bi bi-plus-lg me-2 fw-bold"></i> Agregar Zapato
        </a>
    </div>

    <!-- Filtros Inteligentes -->
    <div class="card card-custom shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET" action="listar.php">
                <div class="row g-3 align-items-end">
                    
                    <div class="col-md-5">
                        <label class="form-label small fw-semibold text-secondary text-uppercase">Buscar calzado</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 border text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" name="busqueda" class="form-control form-control-custom" style="border-radius: 0 10px 10px 0 !important;"
                                   placeholder="Ej. Alpargata, Nike, Bosi..." value="<?php echo htmlspecialchars($busqueda); ?>">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-secondary text-uppercase">Disponibilidad (Stock)</label>
                        <select name="filtro_stock" class="form-select form-control-custom">
                            <option value="">Todos los productos</option>
                            <option value="bajo" <?php echo $filtro_stock === 'bajo' ? 'selected' : ''; ?>>⚠️ Stock Crítico (5 unidades o menos)</option>
                            <option value="disponible" <?php echo $filtro_stock === 'disponible' ? 'selected' : ''; ?>>✅ Stock Estable (Más de 5 unidades)</option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-amatista-primary w-100 rounded-pill py-2 shadow-sm">
                            <i class="bi bi-funnel me-1"></i> Filtrar
                        </button>
                        <a href="listar.php" class="btn btn-amatista-secondary w-100 rounded-pill py-2 d-flex align-items-center justify-content-center">
                            <i class="bi bi-arrow-repeat me-1"></i> Limpiar
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- Tabla Maestra de Productos -->
    <div class="card card-custom shadow-sm overflow-hidden mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">
                <thead>
                    <tr class="table-custom-header border-bottom">
                        <th class="ps-4 py-3 border-0 small text-uppercase text-secondary">Producto</th>
                        <th class="py-3 border-0 small text-uppercase text-secondary">Marca</th>
                        <th class="py-3 border-0 small text-uppercase text-secondary">Talla / Color</th>
                        <th class="py-3 border-0 small text-uppercase text-secondary">Precio</th>
                        <th class="py-3 border-0 small text-uppercase text-secondary">Stock Activo</th>
                        <th class="text-center py-3 border-0 small text-uppercase text-secondary">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($productos) > 0) { ?>
                        <?php while($p = mysqli_fetch_array($productos)){ 
                            $badge_class = ($p['stock'] <= 5) ? 'badge-stock-danger' : 'badge-stock-success';
                            $icono_stock = ($p['stock'] <= 5) ? '⚠️ Crítico:' : '✅ Estable:';
                        ?>
                        <tr class="border-bottom">
                            <!-- Producto -->
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; background-color: #f0ebfa !important; color: var(--primary-color) !important;">
                                        <i class="bi bi-tag-fill fs-5"></i>
                                    </div>
                                    <span class="fw-bold text-dark"><?php echo htmlspecialchars($p['nombre']); ?></span>
                                </div>
                            </td>

                            <!-- Marca -->
                            <td>
                                <span class="text-secondary fw-semibold"><?php echo htmlspecialchars($p['marca']); ?></span>
                            </td>

                            <!-- Talla / Color -->
                            <td>
                                <span class="badge badge-pastel-purple me-1">
                                    T: <?php echo htmlspecialchars($p['talla']); ?>
                                </span>
                                <span class="badge badge-pastel-gray">
                                    <?php echo htmlspecialchars($p['color']); ?>
                                </span>
                            </td>

                            <!-- Precio Formateado -->
                            <td class="fw-bold text-dark">
                                $<?php echo number_format($p['precio'], 0, ',', '.'); ?>
                            </td>

                            <!-- Stock Dinámico -->
                            <td>
                                <span class="badge <?php echo $badge_class; ?> rounded-pill">
                                    <?php echo $icono_stock; ?> <?php echo $p['stock']; ?> unds.
                                </span>
                            </td>

                            <!-- Botonera de Acciones -->
                            <td class="text-center">
                                <div class="btn-group shadow-sm rounded-3 overflow-hidden">
                                    <a href="editar.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-white bg-white border-end" title="Editar Par">
                                        <i class="bi bi-pencil-square text-primary fs-6"></i>
                                    </a>
                                    <form method="POST" action="eliminar.php" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este producto de Amatista SGI?')">
                                        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                                        <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-white bg-white" title="Eliminar Par">
                                            <i class="bi bi-trash3 text-danger fs-6"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-clipboard-x display-6 d-block mb-3 text-light"></i>
                                No se encontraron productos que coincidan con los filtros aplicados actualmente.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- CONTROL DE PAGINACIÓN -->
    <?php if($total_paginas > 1): ?>
        <div class="d-flex justify-content-between align-items-center px-2">
            <div class="text-muted small">
                Mostrando página <strong><?php echo $pagina_actual; ?></strong> de <strong><?php echo $total_paginas; ?></strong> (Total de productos: <?php echo $total_registros; ?>)
            </div>
            <nav aria-label="Navegación de inventario">
                <ul class="pagination pagination-sm mb-0 shadow-sm rounded-3 bg-white p-1">
                    <!-- Botón Anterior -->
                    <li class="page-item <?php echo ($pagina_actual <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?pagina=<?php echo $pagina_actual - 1 . $params_busqueda; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo; Anterior</span>
                        </a>
                    </li>

                    <!-- Páginas numéricas -->
                    <?php 
                    $rango = 2; 
                    for($i = 1; $i <= $total_paginas; $i++): 
                        if ($i == 1 || $i == $total_paginas || ($i >= $pagina_actual - $rango && $i <= $pagina_actual + $rango)):
                    ?>
                        <li class="page-item <?php echo ($pagina_actual == $i) ? 'active' : ''; ?>">
                            <a class="page-link" href="?pagina=<?php echo $i . $params_busqueda; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php 
                        endif;
                    endfor; 
                    ?>

                    <!-- Botón Siguiente -->
                    <li class="page-item <?php echo ($pagina_actual >= $total_paginas) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?pagina=<?php echo $pagina_actual + 1 . $params_busqueda; ?>" aria-label="Next">
                            <span aria-hidden="true">Siguiente &raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>

<!-- CONTROL TEMPORAL AUTOMÁTICO DE LAS NOTIFICACIONES (2 SEGUNDOS) -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const alerta = document.getElementById('alerta-automatica');
        if (alerta) {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alerta);
                bsAlert.close();
            }, 2000);
        }
    });
</script>

<?php include("../../includes/footer.php"); ?>
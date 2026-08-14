<?php
include("../../includes/sesion.php");

include("../../conexion.php");
include("../../includes/header.php");

// 1. Configurar la cantidad de registros por página
$por_pagina = 10;

// 2. Determinar la página actual
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_actual < 1) { $pagina_actual = 1; }

// 3. Calcular el offset (inicio del límite para SQL)
$offset = ($pagina_actual - 1) * $por_pagina;

// Filtros de fecha (valor crudo para salida, escapado para SQL)
$fecha_inicio_raw = $_GET['fecha_inicio'] ?? '';
$fecha_fin_raw = $_GET['fecha_fin'] ?? '';
$fecha_inicio = mysqli_real_escape_string($conexion, $fecha_inicio_raw);
$fecha_fin = mysqli_real_escape_string($conexion, $fecha_fin_raw);

// --- CONSULTA PARA CONTAR EL TOTAL DE REGISTROS (Necesario para la paginación) ---
$query_conteo = "SELECT COUNT(*) as total_registros 
                 FROM ventas v
                 INNER JOIN clientes c ON v.id_cliente = c.id
                 WHERE 1=1";

if(!empty($fecha_inicio)){ $query_conteo .= " AND DATE(v.fecha) >= '$fecha_inicio'"; }
if(!empty($fecha_fin)){ $query_conteo .= " AND DATE(v.fecha) <= '$fecha_fin'"; }

$resultado_conteo = mysqli_query($conexion, $query_conteo);
$fila_conteo = mysqli_fetch_assoc($resultado_conteo);
$total_registros = $fila_conteo['total_registros'];

// Calcular el total de páginas necesarias
$total_paginas = ceil($total_registros / $por_pagina);


// --- CONSULTA PRINCIPAL CON LIMIT Y OFFSET ---
$query = "SELECT v.id, v.fecha, v.total, c.nombre as cliente 
          FROM ventas v
          INNER JOIN clientes c ON v.id_cliente = c.id
          WHERE 1=1";

if(!empty($fecha_inicio)){ $query .= " AND DATE(v.fecha) >= '$fecha_inicio'"; }
if(!empty($fecha_fin)){ $query .= " AND DATE(v.fecha) <= '$fecha_fin'"; }

$query .= " ORDER BY v.fecha DESC LIMIT $por_pagina OFFSET $offset";
$ventas = mysqli_query($conexion, $query);

if(!$ventas){ die("Error en consulta: ".mysqli_error($conexion)); }

// Conservar los filtros activos al cambiar de página
$params_busqueda = "";
if(!empty($fecha_inicio_raw)){ $params_busqueda .= "&fecha_inicio=" . urlencode($fecha_inicio_raw); }
if(!empty($fecha_fin_raw)){ $params_busqueda .= "&fecha_fin=" . urlencode($fecha_fin_raw); }
?>

<style>
    :root {
        --primary-color: #512da8;
        --primary-hover: #432293;
        --accent-success: #2e7d32;
        --bg-badge-id: #f0ebfa;
    }
    .btn-amatista-primary {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
        color: #ffffff !important;
        font-weight: 600;
    }
    .btn-amatista-primary:hover {
        background-color: var(--primary-hover) !important;
        border-color: var(--primary-hover) !important;
    }
    .btn-amatista-secondary {
        background-color: #f1f3f5 !important;
        border: 1px solid #dee2e6 !important;
        color: #495057 !important;
        font-weight: 600;
    }
    .btn-amatista-secondary:hover { background-color: #e9ecef !important; }
    .card-custom { border-radius: 16px !important; border: none !important; }
    .badge-id {
        background-color: var(--bg-badge-id) !important;
        color: var(--primary-color) !important;
        font-weight: 600;
        padding: 0.5em 0.85em;
        border-radius: 8px;
    }
    .form-control-custom { border-radius: 10px !important; padding: 0.6rem 1rem; }
    .table-custom-header { background-color: #f8f9fa !important; color: #495057; font-weight: 600; }
    
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
        background-color: var(--bg-badge-id);
        color: var(--primary-hover);
    }
</style>

<div class="container-fluid px-4 py-3">
    <?php if(isset($_SESSION['alerta'])): ?>
        <div class="alert alert-<?php echo $_SESSION['alerta']['tipo']; ?> alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4 p-3" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi <?php echo ($_SESSION['alerta']['tipo'] == 'success') ? 'bi-check-circle-fill text-success' : 'bi-exclamation-triangle-fill text-danger'; ?> me-3 fs-4"></i>
                <div><?php echo $_SESSION['alerta']['mensaje']; ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['alerta']); ?>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px;">Historial de Ventas</h2>
            <p class="text-muted small mb-0">Consulta, filtra y anula facturas operativas de Amatista SGI.</p>
        </div>
        <a href="pos.php" class="btn btn-amatista-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-cart-plus-fill me-2"></i> Nueva Venta
        </a>
    </div>

    <div class="card card-custom shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-secondary">Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" class="form-control form-control-custom" value="<?php echo htmlspecialchars($fecha_inicio_raw); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-secondary">Fecha Fin</label>
                        <input type="date" name="fecha_fin" class="form-control form-control-custom" value="<?php echo htmlspecialchars($fecha_fin_raw); ?>">
                    </div>
                    <div class="col-md-4 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-amatista-primary rounded-pill px-4 w-100 py-2 shadow-sm">
                            <i class="bi bi-search me-2"></i> Filtrar
                        </button>
                        <a href="listar.php" class="btn btn-amatista-secondary rounded-pill px-4 w-100 py-2">
                            <i class="bi bi-arrow-repeat me-2"></i> Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-custom shadow-sm overflow-hidden mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">
                <thead>
                    <tr class="table-custom-header border-bottom">
                        <th class="ps-4 py-3 border-0">ID Venta</th>
                        <th class="py-3 border-0">Fecha y Hora</th>
                        <th class="py-3 border-0">Cliente</th>
                        <th class="py-3 border-0">Total Recaudado</th>
                        <th class="text-center py-3 border-0">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($ventas) > 0){ ?>
                        <?php while($v = mysqli_fetch_assoc($ventas)){ ?>
                            <tr class="border-bottom">
                                <td class="ps-4"><span class="badge-id">#<?php echo $v['id']; ?></span></td>
                                <td class="text-secondary small"><i class="bi bi-calendar3 me-2"></i><?php echo date("d/m/Y H:i", strtotime($v['fecha'])); ?></td>
                                <td class="fw-semibold text-dark"><?php echo htmlspecialchars($v['cliente']); ?></td>
                                <td class="fw-bold" style="color: var(--accent-success);">$<?php echo number_format($v['total'], 0, ',', '.'); ?></td>
                                <td class="text-center">
                                    <div class="btn-group shadow-sm rounded-3 overflow-hidden">
                                        <a href="ver_detalle.php?id=<?php echo $v['id']; ?>" class="btn btn-sm btn-white bg-white border-end" title="Ver Detalle">
                                            <i class="bi bi-eye text-primary fs-6"></i>
                                        </a>
                                        <a href="ticket.php?id=<?php echo $v['id']; ?>" target="_blank" class="btn btn-sm btn-white bg-white border-end" title="Imprimir Ticket">
                                            <i class="bi bi-printer text-secondary fs-6"></i>
                                        </a>
                                        <a href="eliminar.php?id=<?php echo $v['id']; ?>" class="btn btn-sm btn-white bg-white" onclick="return confirm('¿Está seguro de que desea anular la venta #<?php echo $v['id']; ?> y regresar los productos al inventario?')" title="Anular Venta">
                                            <i class="bi bi-trash3 text-danger fs-6"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-clipboard-x display-6 d-block mb-3 text-light"></i> No se encontraron ventas registradas.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if($total_paginas > 1): ?>
        <div class="d-flex justify-content-between align-items-center px-2">
            <div class="text-muted small">
                Mostrando página <strong><?php echo $pagina_actual; ?></strong> de <strong><?php echo $total_paginas; ?></strong> (Total de registros: <?php echo $total_registros; ?>)
            </div>
            <nav aria-label="Navegación de historial">
                <ul class="pagination pagination-sm mb-0 shadow-sm rounded-3 bg-white p-1">
                    <li class="page-item <?php echo ($pagina_actual <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?pagina=<?php echo $pagina_actual - 1 . $params_busqueda; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo; Anterior</span>
                        </a>
                    </li>

                    <?php 
                    // Limitar el número de páginas visibles si hay demasiadas
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

<?php include("../../includes/footer.php"); ?>
<?php
include("../../includes/sesion.php");
// Iniciamos sesión al principio para renderizar las notificaciones instantáneas

// 1. Incluir conexión a la base de datos
include("../../conexion.php");

// 2. Incluir encabezado del sistema (menú, estilos y estructura)
include("../../includes/header.php");

// --- CONFIGURACIÓN DE LA PAGINACIÓN ---
$por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_actual < 1) { $pagina_actual = 1; }
$offset = ($pagina_actual - 1) * $por_pagina;

// --- CONSULTA PARA CONTAR TOTAL DE REGISTROS BAJOS ---
$query_conteo = "SELECT COUNT(*) as total FROM productos WHERE stock <= 5";
$res_conteo = mysqli_query($conexion, $query_conteo);
$fila_conteo = mysqli_fetch_assoc($res_conteo);
$total_registros = $fila_conteo['total'];
$total_paginas = ceil($total_registros / $por_pagina);

// --- CONSULTA PRINCIPAL CON LÍMITES ---
$productos = mysqli_query($conexion, "SELECT * FROM productos WHERE stock <= 5 ORDER BY stock ASC LIMIT $por_pagina OFFSET $offset");

if (!$productos) {
    die("Error en la consulta de stock bajo: " . mysqli_error($conexion));
}
?>

<style>
    /* Se conservan los mismos estilos Amatista del archivo anterior */
    :root { --primary-color: #512da8; --primary-hover: #432293; --border-radius-card: 16px; }
    .btn-amatista-primary { background-color: var(--primary-color) !important; border-color: var(--primary-color) !important; color: #ffffff !important; font-weight: 600; }
    .btn-amatista-primary:hover { background-color: var(--primary-hover) !important; }
    .card-custom { border-radius: var(--border-radius-card) !important; border: none !important; background-color: #ffffff; }
    .form-control-custom { border-radius: 10px !important; border: 1px solid #ced4da !important; padding: 0.6rem 1rem; }
    .table-custom-header { background-color: #f8f9fa !important; color: #495057; font-weight: 600; }
    .table-hover tbody tr:hover { background-color: #fcfbfe !important; }
    .pagination .page-link { color: var(--primary-color); border: none; padding: 0.6rem 0.9rem; margin: 0 2px; border-radius: 8px; }
    .pagination .page-item.active .page-link { background-color: var(--primary-color) !important; color: #fff !important; }
</style>

<div class="container-fluid px-4 py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px;">Productos con Stock Bajo</h2>
            <p class="text-muted small mb-0">Productos críticos que requieren una reposición inmediata de inventario.</p>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div class="btn-group shadow-sm rounded-3 overflow-hidden">
            <a href="inventario.php" class="btn btn-white bg-white text-dark"><i class="bi bi-box-seam text-primary me-1"></i> Inventario</a>
            <a href="stock_bajo.php" class="btn btn-amatista-primary active"><i class="bi bi-exclamation-triangle me-1"></i> Stock Bajo</a>
            <a href="ventas_dia.php" class="btn btn-white bg-white border-start text-dark"><i class="bi bi-calendar-day text-success me-1"></i> Ventas Día</a>
            <a href="ventas_mes.php" class="btn btn-white bg-white border-start text-dark"><i class="bi bi-calendar-month text-info me-1"></i> Ventas Mes</a>
            <a href="exportar_excel.php" class="btn btn-white bg-white border-start text-dark"><i class="bi bi-file-earmark-excel text-success me-1"></i> Exportar Excel</a>
        </div>

        <form method="GET" action="ventas_filtro.php" class="d-flex gap-2 align-items-center">
            <input type="date" name="fecha_inicio" class="form-control form-control-custom" required>
            <input type="date" name="fecha_fin" class="form-control form-control-custom" required>
            <button type="submit" class="btn btn-amatista-primary rounded-pill px-4 shadow-sm"><i class="bi bi-search me-1"></i> Filtrar</button>
        </form>
    </div>

    <div class="card card-custom shadow-sm overflow-hidden mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">
                <thead>
                    <tr class="table-custom-header border-bottom">
                        <th class="ps-4 py-3 border-0 small text-uppercase text-secondary">Producto</th>
                        <th class="py-3 border-0 small text-uppercase text-secondary">Marca</th>
                        <th class="py-3 border-0 small text-uppercase text-secondary">Talla</th>
                        <th class="py-3 border-0 small text-uppercase text-secondary">Precio</th>
                        <th class="py-3 border-0 small text-uppercase text-secondary">Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($productos) > 0) { ?>
                        <?php while($p = mysqli_fetch_array($productos)){ ?>
                        <tr class="border-bottom">
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; background-color: #fbeeed !important; color: #dc3545 !important;">
                                        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                                    </div>
                                    <span class="fw-bold text-dark"><?php echo htmlspecialchars($p['nombre']); ?></span>
                                </div>
                            </td>
                            <td><span class="text-secondary small fw-semibold"><?php echo htmlspecialchars($p['marca']); ?></span></td>
                            <td><span class="badge bg-light text-dark border p-2 rounded-3" style="font-size: 0.85rem;"><?php echo htmlspecialchars($p['talla']); ?></span></td>
                            <td class="fw-bold text-success">$<?php echo number_format($p['precio'], 0, ",", "."); ?></td>
                            <td>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill fw-bold">
                                    <i class="bi bi-graph-down me-1"></i><?php echo $p['stock']; ?> Unidades
                                </span>
                            </td>
                        </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-success fw-semibold">
                                <i class="bi bi-shield-check display-6 d-block mb-3 text-success"></i>¡No hay productos con stock crítico!
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- NAVEGACIÓN DE PAGINACIÓN -->
    <?php if($total_paginas > 1): ?>
        <div class="d-flex justify-content-between align-items-center px-2">
            <div class="text-muted small">
                Mostrando página <strong><?php echo $pagina_actual; ?></strong> de <strong><?php echo $total_paginas; ?></strong> (Total: <?php echo $total_registros; ?> en alerta)
            </div>
            <nav>
                <ul class="pagination pagination-sm mb-0 shadow-sm rounded-3 bg-white p-1">
                    <li class="page-item <?php echo ($pagina_actual <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?pagina=<?php echo $pagina_actual - 1; ?>">&laquo; Anterior</a>
                    </li>
                    <?php for($i = 1; $i <= $total_paginas; $i++): ?>
                        <li class="page-item <?php echo ($pagina_actual == $i) ? 'active' : ''; ?>">
                            <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo ($pagina_actual >= $total_paginas) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?pagina=<?php echo $pagina_actual + 1; ?>">Siguiente &raquo;</a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>

<?php include("../../includes/footer.php"); ?>
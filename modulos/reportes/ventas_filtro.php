<?php
include("../../includes/sesion.php");
// Iniciamos sesión al principio para renderizar las notificaciones instantáneas

// 1. Incluir conexión a la base de datos
include("../../conexion.php");

// 2. Incluir encabezado del sistema (menú, estilos, estructura)
include("../../includes/header.php");

// Capturar y validar las fechas del formulario o de la URL (para la paginación)
$fecha_inicio_raw = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin_raw = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';
$fecha_inicio = mysqli_real_escape_string($conexion, $fecha_inicio_raw);
$fecha_fin = mysqli_real_escape_string($conexion, $fecha_fin_raw);

if (empty($fecha_inicio_raw) || empty($fecha_fin_raw)) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>Por favor, seleccione un rango de fechas válido.</div></div>";
    include("../../includes/footer.php");
    exit;
}

// --- CONFIGURACIÓN DE LA PAGINACIÓN ---
$por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_actual < 1) { $pagina_actual = 1; }
$offset = ($pagina_actual - 1) * $por_pagina;

// --- CONSULTA PARA CONTAR TOTAL DE REGISTROS FILTRADOS ---
$query_conteo = "SELECT COUNT(*) as total FROM ventas WHERE fecha BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59'";
$res_conteo = mysqli_query($conexion, $query_conteo);
$fila_conteo = mysqli_fetch_assoc($res_conteo);
$total_registros = $fila_conteo['total'];
$total_paginas = ceil($total_registros / $por_pagina);

/*--------------------------------------------------
CONSULTA PRINCIPAL: VENTAS FILTRADAS CON LÍMITES
--------------------------------------------------*/
$ventas = mysqli_query($conexion, "
    SELECT ventas.*, clientes.nombre 
    FROM ventas
    JOIN clientes ON ventas.id_cliente = clientes.id
    WHERE ventas.fecha BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59'
    ORDER BY ventas.fecha DESC
    LIMIT $por_pagina OFFSET $offset
");

if (!$ventas) {
    die("Error en la consulta de filtro de ventas: " . mysqli_error($conexion));
}

/*--------------------------------------------------
CONSULTA: TOTAL ACUMULADO DEL RANGO (Sin límites de página)
--------------------------------------------------*/
$total_filtro_query = mysqli_query($conexion, "SELECT SUM(total) as total_rango FROM ventas WHERE fecha BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59'");
$total_filtro_data = mysqli_fetch_assoc($total_filtro_query);
$total_rango = $total_filtro_data['total_rango'] ?? 0;
?>

<style>
    :root {
        --primary-color: #512da8;
        --primary-hover: #432293;
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
    }
    .btn-amatista-secondary {
        background-color: #f1f3f5 !important;
        border: 1px solid #dee2e6 !important;
        color: #495057 !important;
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

    .table-custom-header {
        background-color: #f8f9fa !important;
        color: #495057;
        font-weight: 600;
    }
    
    .table-hover tbody tr:hover {
        background-color: #fcfbfe !important;
    }

    /* Paginación Amatista */
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

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px;">Resultados del Filtro</h2>
            <p class="text-muted small mb-0">Ventas reportadas desde el <strong><?php echo htmlspecialchars($fecha_inicio_raw); ?></strong> hasta el <strong><?php echo htmlspecialchars($fecha_fin_raw); ?></strong>.</p>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div class="btn-group shadow-sm rounded-3 overflow-hidden">
            <a href="inventario.php" class="btn btn-white bg-white text-dark"><i class="bi bi-box-seam text-primary me-1"></i> Inventario</a>
            <a href="stock_bajo.php" class="btn btn-white bg-white border-start text-dark"><i class="bi bi-exclamation-triangle text-warning me-1"></i> Stock Bajo</a>
            <a href="ventas_dia.php" class="btn btn-white bg-white border-start text-dark"><i class="bi bi-calendar-day text-success me-1"></i> Ventas Día</a>
            <a href="ventas_mes.php" class="btn btn-white bg-white border-start text-dark"><i class="bi bi-calendar-month text-info me-1"></i> Ventas Mes</a>
            <a href="exportar_excel.php" class="btn btn-white bg-white border-start text-dark"><i class="bi bi-file-earmark-excel text-success me-1"></i> Exportar Excel</a>
        </div>

        <form method="GET" action="ventas_filtro.php" class="d-flex gap-2 align-items-center">
            <input type="date" name="fecha_inicio" value="<?php echo htmlspecialchars($fecha_inicio_raw); ?>" class="form-control form-control-custom" required>
            <input type="date" name="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin_raw); ?>" class="form-control form-control-custom" required>
            <button type="submit" class="btn btn-amatista-primary rounded-pill px-4 shadow-sm"><i class="bi bi-search me-1"></i> Filtrar</button>
        </form>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card card-custom shadow-sm border-start border-4 border-primary p-2">
                <div class="card-body">
                    <h6 class="text-secondary text-uppercase small fw-bold mb-2">Total en este Rango</h6>
                    <h3 class="fw-bold text-primary mb-0">$<?php echo number_format($total_rango, 0, ",", "."); ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom shadow-sm overflow-hidden mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">
                <thead>
                    <tr class="table-custom-header border-bottom">
                        <th class="ps-4 py-3 border-0 small text-uppercase text-secondary">ID Factura</th>
                        <th class="py-3 border-0 small text-uppercase text-secondary">Cliente</th>
                        <th class="py-3 border-0 small text-uppercase text-secondary">Fecha y Hora</th>
                        <th class="py-3 border-0 small text-uppercase text-secondary">Monto Total</th>
                        <th class="text-center py-3 border-0 small text-uppercase text-secondary">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($ventas) > 0) { ?>
                        <?php while($v = mysqli_fetch_array($ventas)){ ?>
                        <tr class="border-bottom">
                            <td class="ps-4 fw-bold text-dark">#<?php echo htmlspecialchars($v['id']); ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2 bg-light text-primary" style="width: 30px; height: 30px;"><i class="bi bi-person-fill"></i></div>
                                    <span class="fw-semibold text-dark small"><?php echo htmlspecialchars($v['nombre']); ?></span>
                                </div>
                            </td>
                            <td class="small text-secondary"><i class="bi bi-clock me-1 text-muted"></i><?php echo htmlspecialchars($v['fecha']); ?></td>
                            <td class="fw-bold text-success">$<?php echo number_format($v['total'], 0, ",", "."); ?></td>
                            <td class="text-center">
                                <a href="../ventas/ver_detalle.php?id=<?php echo $v['id']; ?>" class="btn btn-sm btn-amatista-secondary rounded-pill px-3 py-1"><i class="bi bi-eye me-1"></i>Ver Detalle</a>
                            </td>
                        </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-calendar-x display-6 d-block mb-3 text-light"></i>No se encontraron ventas en este rango de fechas.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- NAVEGACIÓN DE PAGINACIÓN MANTENIENDO LAS VARIABLES DEL FILTRO -->
    <?php if($total_paginas > 1): ?>
        <div class="d-flex justify-content-between align-items-center px-2">
            <div class="text-muted small">
                Mostrando página <strong><?php echo $pagina_actual; ?></strong> de <strong><?php echo $total_paginas; ?></strong> (Total de resultados: <?php echo $total_registros; ?>)
            </div>
            <nav>
                <ul class="pagination pagination-sm mb-0 shadow-sm rounded-3 bg-white p-1">
                    <li class="page-item <?php echo ($pagina_actual <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?fecha_inicio=<?php echo urlencode($fecha_inicio_raw); ?>&fecha_fin=<?php echo urlencode($fecha_fin_raw); ?>&pagina=<?php echo $pagina_actual - 1; ?>">&laquo; Anterior</a>
                    </li>
                    <?php for($i = 1; $i <= $total_paginas; $i++): ?>
                        <li class="page-item <?php echo ($pagina_actual == $i) ? 'active' : ''; ?>">
                            <a class="page-link" href="?fecha_inicio=<?php echo urlencode($fecha_inicio_raw); ?>&fecha_fin=<?php echo urlencode($fecha_fin_raw); ?>&pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo ($pagina_actual >= $total_paginas) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?fecha_inicio=<?php echo urlencode($fecha_inicio_raw); ?>&fecha_fin=<?php echo urlencode($fecha_fin_raw); ?>&pagina=<?php echo $pagina_actual + 1; ?>">Siguiente &raquo;</a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>

<?php include("../../includes/footer.php"); ?>
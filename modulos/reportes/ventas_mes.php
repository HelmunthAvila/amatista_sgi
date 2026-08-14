<?php
include("../../includes/sesion.php");
requiere_rol('admin');
// Iniciamos sesión al principio para renderizar las notificaciones instantáneas

// 1. Conexión a la base de datos
include("../../conexion.php");

// 2. Cargar encabezado del sistema (menú, estilos, navbar)
include("../../includes/header.php");

// 3. Obtener mes y año actual del servidor
$mes = date("m");
$anio = date("Y");

// --- CONFIGURACIÓN DE LA PAGINACIÓN ---
$por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_actual < 1) { $pagina_actual = 1; }
$offset = ($pagina_actual - 1) * $por_pagina;

// --- CONSULTA PARA CONTAR TOTAL DE VENTAS DEL MES ---
$query_conteo = "SELECT COUNT(*) as total FROM ventas WHERE MONTH(fecha)='$mes' AND YEAR(fecha)='$anio'";
$res_conteo = mysqli_query($conexion, $query_conteo);
$fila_conteo = mysqli_fetch_assoc($res_conteo);
$total_registros = $fila_conteo['total'];
$total_paginas = ceil($total_registros / $por_pagina);

/*--------------------------------------------------
CONSULTA PRINCIPAL: VENTAS DEL MES CON LÍMITES
--------------------------------------------------*/
$ventas = mysqli_query($conexion, "
    SELECT ventas.*, clientes.nombre 
    FROM ventas
    JOIN clientes ON ventas.id_cliente = clientes.id
    WHERE MONTH(ventas.fecha)='$mes' 
    AND YEAR(ventas.fecha)='$anio'
    ORDER BY ventas.fecha DESC
    LIMIT $por_pagina OFFSET $offset
");

if (!$ventas) {
    die("Error al consultar la información. Inténtalo de nuevo.");
}

/*--------------------------------------------------
CONSULTA: TOTAL DE DINERO VENDIDO EN EL MES (Sin límites de página)
--------------------------------------------------*/
$total_mes_query = mysqli_query($conexion, "SELECT SUM(total) as total_mes FROM ventas WHERE MONTH(fecha)='$mes' AND YEAR(fecha)='$anio'");
$total_mes_data = mysqli_fetch_assoc($total_mes_query);
$total_mes = $total_mes_data['total_mes'] ?? 0;
?>

<style>
    /* Se conservan los mismos estilos Amatista */
    :root { --primary-color: #512da8; --primary-hover: #432293; --border-radius-card: 16px; }
    .btn-amatista-primary { background-color: var(--primary-color) !important; border-color: var(--primary-color) !important; color: #ffffff !important; font-weight: 600; }
    .btn-amatista-secondary { background-color: #f1f3f5 !important; border: 1px solid #dee2e6 !important; color: #495057 !important; }
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
            <h2 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px;">Ventas del Mes</h2>
            <p class="text-muted small mb-0">Listado consolidado de las ventas facturadas durante el mes en curso.</p>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div class="btn-group shadow-sm rounded-3 overflow-hidden">
            <a href="inventario.php" class="btn btn-white bg-white text-dark"><i class="bi bi-box-seam text-primary me-1"></i> Inventario</a>
            <a href="stock_bajo.php" class="btn btn-white bg-white border-start text-dark"><i class="bi bi-exclamation-triangle text-warning me-1"></i> Stock Bajo</a>
            <a href="ventas_dia.php" class="btn btn-white bg-white border-start text-dark"><i class="bi bi-calendar-day text-success me-1"></i> Ventas Día</a>
            <a href="ventas_mes.php" class="btn btn-amatista-primary active"><i class="bi bi-calendar-month me-1"></i> Ventas Mes</a>
            <a href="exportar_excel.php" class="btn btn-white bg-white border-start text-dark"><i class="bi bi-file-earmark-excel text-success me-1"></i> Exportar Excel</a>
        </div>

        <form method="GET" action="ventas_filtro.php" class="d-flex gap-2 align-items-center">
            <input type="date" name="fecha_inicio" class="form-control form-control-custom" required>
            <input type="date" name="fecha_fin" class="form-control form-control-custom" required>
            <button type="submit" class="btn btn-amatista-primary rounded-pill px-4 shadow-sm"><i class="bi bi-search me-1"></i> Filtrar</button>
        </form>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card card-custom shadow-sm border-start border-4 border-success p-2">
                <div class="card-body">
                    <h6 class="text-secondary text-uppercase small fw-bold mb-2">Total Facturado este Mes</h6>
                    <h3 class="fw-bold text-success mb-0">$<?php echo number_format($total_mes, 0, ",", "."); ?></h3>
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
                                <i class="bi bi-calendar-x display-6 d-block mb-3 text-light"></i>No se registran ventas este mes.
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
                Mostrando página <strong><?php echo $pagina_actual; ?></strong> de <strong><?php echo $total_paginas; ?></strong> (Total de facturas: <?php echo $total_registros; ?>)
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
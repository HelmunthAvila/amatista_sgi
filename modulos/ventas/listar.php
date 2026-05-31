<?php
session_start();
include("../../conexion.php");
include("../../includes/header.php");

$fecha_inicio = $_GET['fecha_inicio'] ?? '';
$fecha_fin = $_GET['fecha_fin'] ?? '';

$query = "SELECT v.id, v.fecha, v.total, c.nombre as cliente 
          FROM ventas v
          INNER JOIN clientes c ON v.id_cliente = c.id
          WHERE 1=1";

if(!empty($fecha_inicio)){ $query .= " AND DATE(v.fecha) >= '$fecha_inicio'"; }
if(!empty($fecha_fin)){ $query .= " AND DATE(v.fecha) <= '$fecha_fin'"; }

$query .= " ORDER BY v.fecha DESC";
$ventas = mysqli_query($conexion, $query);

if(!$ventas){ die("Error en consulta: ".mysqli_error($conexion)); }
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
</style>

<div class="container-fluid px-4 py-3">
    <!-- ALERTAS -->
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

    <!-- FILTROS -->
    <div class="card card-custom shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-secondary">Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" class="form-control form-control-custom" value="<?php echo $fecha_inicio; ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-secondary">Fecha Fin</label>
                        <input type="date" name="fecha_fin" class="form-control form-control-custom" value="<?php echo $fecha_fin; ?>">
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

    <!-- TABLA HISTORIAL -->
    <div class="card card-custom shadow-sm overflow-hidden">
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
                                <td class="fw-semibold text-dark"><?php echo $v['cliente']; ?></td>
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
</div>

<?php include("../../includes/footer.php"); ?>
<?php
session_start();
include("../../conexion.php");
include("../../includes/header.php");

if(!isset($_GET['id']) || empty($_GET['id'])){
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'ID de venta no proporcionado o inválido.'];
    header("Location: listar.php");
    exit();
}

$id_venta = intval($_GET['id']);

// 1. Consultar maestro de la venta y datos del cliente (Corregido según image_a2f287.png)
$query_master = mysqli_query($conexion, "SELECT v.id, v.fecha, v.total, c.nombre, c.telefono 
                                         FROM ventas v 
                                         INNER JOIN clientes c ON v.id_cliente = c.id 
                                         WHERE v.id = $id_venta");

if(!$query_master || mysqli_num_rows($query_master) == 0){
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'La venta solicitada no existe en el sistema.'];
    header("Location: listar.php");
    exit();
}

$venta = mysqli_fetch_assoc($query_master);

// 2. Consultar desglose detallado de los productos vendidos
$query_detalle = mysqli_query($conexion, "SELECT dv.cantidad, dv.precio_unitario, p.nombre as producto 
                                          FROM detalle_venta dv 
                                          INNER JOIN productos p ON dv.id_producto = p.id 
                                          WHERE dv.id_venta = $id_venta");
?>

<style>
    :root {
        --primary-color: #512da8;
        --accent-success: #2e7d32;
        --bg-badge-id: #f0ebfa;
    }
    .text-amatista { color: var(--primary-color) !important; }
    .card-custom { border-radius: 16px !important; border: none !important; background-color: #ffffff; }
    .badge-id {
        background-color: var(--bg-badge-id) !important;
        color: var(--primary-color) !important;
        font-weight: 600;
        padding: 0.5em 0.85em;
        border-radius: 8px;
    }
    .table-custom-header { background-color: #f8f9fa !important; color: #495057; font-weight: 600; }
</style>

<div class="container-fluid px-4 py-3">

    <!-- Encabezado de la página -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h2 class="fw-bold text-dark mb-0" style="letter-spacing: -0.5px;">Detalle de Factura</h2>
                <span class="badge-id">#<?php echo $venta['id']; ?></span>
            </div>
            <p class="text-muted small mb-0">Comprobante de desglose comercial interno de Amatista SGI.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="ticket.php?id=<?php echo $venta['id']; ?>" target="_blank" class="btn btn-light border rounded-pill px-3">
                <i class="bi bi-printer me-2"></i> Imprimir Ticket
            </a>
            <a href="listar.php" class="btn btn-outline-secondary rounded-pill px-3">
                <i class="bi bi-arrow-left-short me-1"></i> Volver al Historial
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- METADATOS DEL COMPRADOR -->
        <div class="col-md-4">
            <div class="card card-custom shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 text-amatista"><i class="bi bi-person-badge-fill me-2"></i> Información de Venta</h5>
                    <hr class="text-muted opacity-25">
                    
                    <div class="mb-3">
                        <label class="text-muted small d-block mb-1">Cliente Receptor</label>
                        <span class="fw-bold text-dark"><?php echo $venta['nombre']; ?></span>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small d-block mb-1">Teléfono de Contacto</label>
                        <span class="text-dark fw-semibold"><?php echo !empty($venta['telefono']) ? $venta['telefono'] : 'No registrado'; ?></span>
                    </div>
                    <div class="mb-0">
                        <label class="text-muted small d-block mb-1">Fecha de Procesamiento</label>
                        <span class="text-dark fw-semibold"><i class="bi bi-calendar-event me-1 text-muted"></i> <?php echo date("d/m/Y H:i:s", strtotime($venta['fecha'])); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- DETALLE DE ARTÍCULOS -->
        <div class="col-md-8">
            <div class="card card-custom shadow-sm overflow-hidden">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr class="table-custom-header border-bottom">
                                <th class="ps-4 py-3 text-uppercase small">Producto Especificado</th>
                                <th class="text-uppercase small">Precio Unitario</th>
                                <th class="text-uppercase small">Cantidad</th>
                                <th class="text-end pe-4 text-uppercase small">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            while($d = mysqli_fetch_assoc($query_detalle)){ 
                                $subtotal_item = $d['precio_unitario'] * $d['cantidad'];
                            ?>
                                <tr class="border-bottom">
                                    <td class="ps-4 fw-semibold text-dark"><?php echo $d['producto']; ?></td>
                                    <td class="text-secondary">$<?php echo number_format($d['precio_unitario'], 0, ',', '.'); ?></td>
                                    <td><span class="badge bg-light text-dark border px-3 py-2 rounded-3">x <?php echo $d['cantidad']; ?></span></td>
                                    <td class="text-end pe-4 fw-bold text-dark">$<?php echo number_format($subtotal_item, 0, ',', '.'); ?></td>
                                </tr>
                            <?php } ?>
                            <!-- FILA DE TOTAL TOTAL -->
                            <tr class="bg-light">
                                <td colspan="3" class="text-end fw-bold py-3 text-uppercase small text-secondary">Monto Total Liquidado:</td>
                                <td class="text-end pe-4 fw-bold fs-5" style="color: var(--accent-success);">$<?php echo number_format($venta['total'], 0, ',', '.'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("../../includes/footer.php"); ?>
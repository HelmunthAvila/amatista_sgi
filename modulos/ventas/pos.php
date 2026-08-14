<?php
include("../../includes/sesion.php");

include("../../conexion.php");
include("../../includes/header.php");

$productos = mysqli_query($conexion, "SELECT * FROM productos WHERE stock > 0 ORDER BY nombre ASC");
$clientes = mysqli_query($conexion, "SELECT * FROM clientes ORDER BY nombre ASC");

if(!isset($_SESSION['carrito'])){
    $_SESSION['carrito'] = [];
}
?>

<style>
    :root {
        --primary-color: #512da8;
        --primary-hover: #432293;
        --accent-success: #2e7d32;
        --accent-success-hover: #1b5e20;
        --bg-light-gray: #f8f9fa;
        --border-radius-card: 16px;
    }
    .text-amatista { color: var(--primary-color) !important; }
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
    .btn-amatista-outline {
        border: 1px solid var(--primary-color) !important;
        color: var(--primary-color) !important;
        background-color: transparent !important;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .btn-amatista-outline:hover {
        background-color: var(--primary-color) !important;
        color: #ffffff !important;
    }
    .btn-amatista-success {
        background-color: var(--accent-success) !important;
        border-color: var(--accent-success) !important;
        color: white !important;
        font-weight: 600;
    }
    .btn-amatista-success:hover:not([disabled]) {
        background-color: var(--accent-success-hover) !important;
        border-color: var(--accent-success-hover) !important;
    }
    .card-custom {
        border-radius: var(--border-radius-card) !important;
        border: none !important;
        background-color: #ffffff;
    }
    .form-control-custom {
        border-radius: 10px !important;
        border: 1px solid #ced4da !important;
        padding: 0.65rem 1rem;
        background-color: var(--bg-light-gray) !important;
    }
    .form-control-custom:focus {
        border-color: var(--primary-color) !important;
        background-color: #ffffff !important;
        box-shadow: 0 0 0 0.25rem rgba(81, 45, 168, 0.15) !important;
    }
    .table-custom-header {
        background-color: #f8f9fa !important;
        color: #6c757d;
        font-weight: 600;
    }
    .badge-quantity {
        background-color: #f0ebfa !important;
        color: var(--primary-color) !important;
        font-weight: 600;
        padding: 0.5em 0.8em;
        border-radius: 6px;
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
        
        <?php if(isset($_SESSION['imprimir_ticket_id'])): ?>
            <script>
                // Abre el documento de impresión en un popup emergente adaptado a tiqueteras térmicas
                window.open('ticket.php?id=<?php echo $_SESSION['imprimir_ticket_id']; ?>', '_blank', 'width=320,height=600,menubar=no,scrollbars=yes,status=no,resizable=yes');
            </script>
            <?php unset($_SESSION['imprimir_ticket_id']); ?>
        <?php endif; ?>

        <?php unset($_SESSION['alerta']); ?>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px;">Terminal Punto de Venta (POS)</h2>
            <p class="text-muted small mb-0">Registra flujos comerciales y gestiona el carrito en tiempo real.</p>
        </div>
        <a href="listar.php" class="btn btn-amatista-outline rounded-pill px-4 shadow-sm">
            <i class="bi bi-receipt me-2"></i> Ver Historial de Ventas
        </a>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card card-custom shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-amatista">
                        <i class="bi bi-plus-circle-fill me-2"></i> Agregar al Carrito
                    </h5>
                    <form action="agregar_carrito.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary text-uppercase">Producto / Modelo</label>
                            <select name="producto_id" class="form-select form-control-custom" required>
                                <option value="">Seleccionar producto...</option>
                                <?php while($p = mysqli_fetch_array($productos)){ ?>
                                    <option value="<?php echo $p['id']; ?>">
                                        <?php echo htmlspecialchars($p['nombre'])." (Stock: ".$p['stock'].") - $".number_format($p['precio'], 0, ',', '.'); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-semibold text-secondary text-uppercase">Cantidad Unidades</label>
                            <input type="number" name="cantidad" class="form-control form-control-custom" value="1" min="1" required>
                        </div>
                        <button type="submit" class="btn btn-amatista-primary w-100 rounded-pill py-2 shadow-sm">
                            <i class="bi bi-cart-plus-fill me-2"></i> Agregar Producto
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card card-custom shadow-sm overflow-hidden d-flex flex-column h-100">
                <div class="table-responsive flex-grow-1">
                    <table class="table align-middle mb-0 table-hover">
                        <thead>
                            <tr class="table-custom-header border-bottom">
                                <th class="ps-4 py-3 text-uppercase small">Producto</th>
                                <th class="text-uppercase small">Precio</th>
                                <th class="text-uppercase small">Cantidad</th>
                                <th class="text-uppercase small">Subtotal</th>
                                <th class="text-center text-uppercase small">Quitar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            if(empty($_SESSION['carrito'])){
                            ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-cart-x display-6 d-block mb-2 text-light"></i> El carrito de compras está vacío actualmente.
                                    </td>
                                </tr>
                            <?php
                            } else {
                                foreach($_SESSION['carrito'] as $index => $item){
                                    $subtotal = $item['precio'] * $item['cantidad'];
                                    $total += $subtotal;
                            ?>
                                    <tr class="border-bottom">
                                        <td class="ps-4 fw-semibold text-dark"><?php echo htmlspecialchars($item['nombre']); ?></td>
                                        <td class="text-secondary">$<?php echo number_format($item['precio'], 0, ',', '.'); ?></td>
                                        <td><span class="badge badge-quantity">Cant: <?php echo $item['cantidad']; ?> und.</span></td>
                                        <td class="fw-bold text-amatista">$<?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                                        <td class="text-center">
                                            <a href="eliminar_carrito.php?id=<?php echo $index; ?>" class="btn btn-sm btn-light text-danger rounded-circle p-2">
                                                <i class="bi bi-trash3-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-white p-4 border-top-0 mt-auto">
                    <form action="guardar_venta.php" method="POST">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <label class="form-label small fw-semibold text-secondary text-uppercase mb-2">Asignar Cliente</label>
                                <select name="id_cliente" class="form-select form-control-custom" required>
                                    <option value="">Seleccionar cliente...</option>
                                    <?php 
                                    mysqli_data_seek($clientes, 0);
                                    while($c = mysqli_fetch_array($clientes)){ 
                                    ?>
                                        <option value="<?php echo $c['id']; ?>"><?php echo $c['nombre']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-5 text-end mt-3 mt-md-0">
                                <p class="mb-0 text-muted small text-uppercase fw-semibold">Total Facturado</p>
                                <h2 class="fw-bold text-dark mb-3">$<?php echo number_format($total, 0, ',', '.'); ?></h2>
                                <button type="submit" class="btn btn-amatista-success w-100 rounded-pill shadow-sm py-2 d-flex align-items-center justify-content-center" <?php echo (empty($_SESSION['carrito'])) ? 'disabled' : ''; ?>>
                                    <i class="bi bi-bag-check-fill me-2 fs-5"></i> Completar Venta
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("../../includes/footer.php"); ?>
<?php
// 1. Iniciamos sesión y validamos acceso
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// 2. Incluimos archivos necesarios
include("conexion.php");
include("includes/header.php");

/* LÓGICA DE DATOS DEL DASHBOARD */
$ventas_dia = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT SUM(total) as total FROM ventas WHERE DATE(fecha)=CURDATE()"));
$ventas_mes = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT SUM(total) as total FROM ventas WHERE MONTH(fecha)=MONTH(CURDATE()) AND YEAR(fecha)=YEAR(CURDATE())"));
$productos = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) as total FROM productos"));
$stock_bajo_total = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) as total FROM productos WHERE stock <= 5"));
$stock_bajo = mysqli_query($conexion, "SELECT * FROM productos WHERE stock <= 5 ORDER BY stock ASC");
?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-dark">Dashboard AMATISTA SGI</h1>

        <div class="dropdown">
            <button class="btn btn-white shadow-sm rounded-pill px-4 py-2 border dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle text-primary me-2"></i>
                <span class="text-muted me-2">Hola,</span>
                <strong class="text-dark"><?php echo $_SESSION['usuario'] ?? 'Usuario'; ?></strong>
            </button>
            
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4 mt-2">
                <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin'): ?>
                <li>
                    <a class="dropdown-item py-2" href="modulos/usuarios/listar.php">
                        <i class="bi bi-shield-lock me-2 text-primary"></i> Gestionar Usuarios
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <?php endif; ?>
                <li>
                    <a class="dropdown-item py-2 text-danger" href="logout.php">
                        <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-white" style="background:#2563eb">
                <div class="card-body text-center">
                    <i class="bi bi-cash-stack fs-1"></i>
                    <p class="small text-uppercase mt-2">Ventas Hoy</p>
                    <h3 class="fw-bold">$<?php echo number_format($ventas_dia['total'] ?? 0, 0, ",", "."); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-white" style="background:#059669">
                <div class="card-body text-center">
                    <i class="bi bi-graph-up fs-1"></i>
                    <p class="small text-uppercase mt-2">Ventas del Mes</p>
                    <h3 class="fw-bold">$<?php echo number_format($ventas_mes['total'] ?? 0, 0, ",", "."); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-white" style="background:#7c3aed">
                <div class="card-body text-center">
                    <i class="bi bi-box-seam fs-1"></i>
                    <p class="small text-uppercase mt-2">Productos</p>
                    <h3 class="fw-bold"><?php echo $productos['total']; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-white" style="background:#dc2626">
                <div class="card-body text-center">
                    <i class="bi bi-exclamation-triangle fs-1"></i>
                    <p class="small text-uppercase mt-2">Stock Bajo</p>
                    <h3 class="fw-bold"><?php echo $stock_bajo_total['total']; ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Accesos rápidos</h5>
            <div class="row g-3">
                <div class="col-md-3"><a href="modulos/ventas/pos.php" class="btn btn-primary w-100 py-3"><i class="bi bi-cart fs-4"></i><br>Nueva Venta</a></div>
                <div class="col-md-3"><a href="modulos/productos/listar.php" class="btn btn-success w-100 py-3"><i class="bi bi-box-seam fs-4"></i><br>Productos</a></div>
                <div class="col-md-3"><a href="modulos/clientes/listar.php" class="btn btn-info w-100 py-3 text-white"><i class="bi bi-people fs-4"></i><br>Clientes</a></div>
                <div class="col-md-3"><a href="modulos/reportes/inventario.php" class="btn btn-dark w-100 py-3"><i class="bi bi-bar-chart fs-4"></i><br>Reportes</a></div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="fw-bold text-danger mb-0"><i class="bi bi-exclamation-triangle-fill me-2"></i>Productos con Stock Bajo</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Producto</th>
                        <th>Marca</th>
                        <th>Talla</th>
                        <th class="text-center">Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $hay = false;
                    while($p = mysqli_fetch_array($stock_bajo)){
                        $hay = true;
                    ?>
                    <tr>
                        <td class="ps-4 fw-bold"><?php echo $p['nombre']; ?></td>
                        <td><?php echo $p['marca']; ?></td>
                        <td><?php echo $p['talla']; ?></td>
                        <td class="text-center"><span class="badge bg-danger px-3 py-2"><?php echo $p['stock']; ?></span></td>
                    </tr>
                    <?php } 
                    if(!$hay) echo "<tr><td colspan='4' class='text-center py-5 text-muted'>Inventario en niveles óptimos</td></tr>";
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
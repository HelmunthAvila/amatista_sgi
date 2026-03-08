<?php
// Incluimos la conexión y el header corregido
include("conexion.php");
include("includes/header.php");

// 1. Consulta de Ventas del Día
$consulta_ventas = mysqli_query($conexion, "SELECT SUM(total) as total FROM ventas WHERE DATE(fecha) = CURDATE()");
$datos_ventas = mysqli_fetch_assoc($consulta_ventas);

// 2. Consulta de Total de Productos
$consulta_productos = mysqli_query($conexion, "SELECT COUNT(*) as total FROM productos");
$datos_productos = mysqli_fetch_assoc($consulta_productos);

// 3. Consulta de Stock Crítico (productos con 5 o menos unidades)
$stock_bajo = mysqli_query($conexion, "SELECT * FROM productos WHERE stock <= 5");
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="fw-bold text-dark">Dashboard AMATISTA SGI</h1>
        <div class="bg-white shadow-sm px-4 py-2 rounded-pill border">
            <span class="text-muted small">Bienvenido, </span>
            <strong><?php echo $_SESSION['usuario'] ?? 'admin'; ?></strong>
            <i class="bi bi-person-circle ms-2 text-primary"></i>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 text-white p-2" style="background: #2563eb;">
                <div class="card-body text-center">
                    <p class="text-uppercase small fw-bold opacity-75">Ventas del Día</p>
                    <h2 class="display-6 fw-bold mb-0">
                        $ <?php echo number_format($datos_ventas['total'] ?? 0, 2); ?>
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 text-white p-2" style="background: #10b981;">
                <div class="card-body text-center">
                    <p class="text-uppercase small fw-bold opacity-75">Total Productos</p>
                    <h2 class="display-6 fw-bold mb-0">
                        <?php echo $datos_productos['total'] ?? 0; ?>
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="fw-bold text-danger mb-0">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>Stock Crítico
            </h5>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Producto</th>
                        <th class="text-center">Existencia</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $hay_critico = false;
                    while($prod = mysqli_fetch_array($stock_bajo)){ 
                        $hay_critico = true;
                    ?>
                    <tr>
                        <td class="ps-4 fw-bold"><?php echo $prod['nombre']; ?></td>
                        <td class="text-center">
                            <span class="badge bg-danger rounded-pill px-3">
                                <?php echo $prod['stock']; ?>
                            </span>
                        </td>
                    </tr>
                    <?php } 
                    if(!$hay_critico){
                        echo "<tr><td colspan='2' class='text-center py-5 text-muted'>Inventario en niveles óptimos.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
// Incluimos el pie de página
include("includes/footer.php"); 
?>
<?php
session_start();
// Seguridad: Si no hay sesión iniciada, redirigir al login
if(!isset($_SESSION['usuario'])){
    header("Location: login.php");
    exit();
}

include("conexion.php"); // Conexión a la BD
include("includes/header.php"); // Cargamos el menú y estilos

// Lógica de datos (Tu código original)
$ventas_dia = mysqli_query($conexion,"
    SELECT SUM(total) as total 
    FROM ventas 
    WHERE DATE(fecha)=CURDATE()
");
$vd = mysqli_fetch_array($ventas_dia);

$productos = mysqli_query($conexion,"
    SELECT COUNT(*) as total FROM productos
");
$tp = mysqli_fetch_array($productos);

$stock = mysqli_query($conexion,"
    SELECT * FROM productos
    WHERE stock <= 5
");
?>

<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-5 fw-bold text-dark">Dashboard AMATISTA SGI</h1>
            <p class="text-muted">Resumen general del inventario y ventas.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body p-4 text-center">
                    <h5 class="card-title opacity-75">Ventas del día</h5>
                    <h2 class="display-4 fw-bold">$ <?php echo number_format($vd['total'] ?? 0, 2); ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 bg-success text-white">
                <div class="card-body p-4 text-center">
                    <h5 class="card-title opacity-75">Total productos</h5>
                    <h2 class="display-4 fw-bold"><?php echo $tp['total']; ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-danger fw-bold">⚠️ Productos con stock bajo</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre del Producto</th>
                                    <th class="text-center">Stock Disponible</th>
                                    <th class="text-end">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($s = mysqli_fetch_array($stock)){ ?>
                                <tr>
                                    <td class="fw-semibold"><?php echo $s['nombre']; ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-danger fs-6"><?php echo $s['stock']; ?></span>
                                    </td>
                                    <td class="text-end text-muted small italic">Reabastecer pronto</td>
                                </tr>
                                <?php } ?>
                                <?php if(mysqli_num_rows($stock) == 0): ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Todo el inventario está en niveles óptimos.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); // Cargamos el pie de página ?>
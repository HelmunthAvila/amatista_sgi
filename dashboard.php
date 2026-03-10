<?php
// Iniciar sesión
session_start();

// 1. Validar que el usuario haya iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// 2. Incluir archivos necesarios
require_once("conexion.php");
include("includes/header.php");

/* --- LÓGICA DE DATOS --- */
function obtenerDato($conexion, $sql) {
    $resultado = mysqli_query($conexion, $sql);
    return mysqli_fetch_assoc($resultado);
}

// Consultas para los indicadores
$ventas_dia = obtenerDato($conexion, "SELECT SUM(total) as total FROM ventas WHERE DATE(fecha)=CURDATE()");
$ventas_mes = obtenerDato($conexion, "SELECT SUM(total) as total FROM ventas WHERE MONTH(fecha)=MONTH(CURDATE()) AND YEAR(fecha)=YEAR(CURDATE())");
$productos = obtenerDato($conexion, "SELECT COUNT(*) as total FROM productos");
$stock_bajo = mysqli_query($conexion, "SELECT nombre, marca, talla, stock FROM productos WHERE stock <= 5 ORDER BY stock ASC");
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-dark">Dashboard AMATISTA SGI</h1>

        <div class="dropdown">
            <button class="btn btn-white shadow-sm rounded-pill px-4 py-2 border dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle text-primary me-2"></i>
                <span class="text-muted me-2">Hola,</span>
                <strong class="text-dark"><?php echo $_SESSION['nombre_usuario'] ?? 'Usuario'; ?></strong>
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
        <?php 
        $cards = [
            ["Ventas Hoy", $ventas_dia['total'] ?? 0, "bi-cash-stack", "#2563eb"],
            ["Ventas Mes", $ventas_mes['total'] ?? 0, "bi-graph-up", "#059669"],
            ["Productos", $productos['total'] ?? 0, "bi-box-seam", "#7c3aed"],
            ["Stock Bajo", mysqli_num_rows($stock_bajo), "bi-exclamation-triangle", "#dc2626"]
        ];

        foreach ($cards as $c) {
            echo '<div class="col-md-3">
                    <div class="card border-0 shadow-sm text-white" style="background:'.$c[3].'">
                        <div class="card-body text-center">
                            <i class="bi '.$c[2].' fs-2"></i>
                            <p class="small text-uppercase mt-2">'.$c[0].'</p>
                            <h3 class="fw-bold">$'.number_format($c[1], 0, ",", ".").'</h3>
                        </div>
                    </div>
                  </div>';
        }
        ?>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="fw-bold text-danger mb-0"><i class="bi bi-exclamation-triangle-fill me-2"></i>Productos con Stock Crítico</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th class="ps-4">Producto</th><th>Marca</th><th>Talla</th><th class="text-center">Stock</th></tr>
                </thead>
                <tbody>
                    <?php while($p = mysqli_fetch_assoc($stock_bajo)): ?>
                    <tr>
                        <td class="ps-4 fw-bold"><?php echo htmlspecialchars($p['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($p['marca']); ?></td>
                        <td><?php echo htmlspecialchars($p['talla']); ?></td>
                        <td class="text-center"><span class="badge bg-danger px-3 py-2"><?php echo $p['stock']; ?></span></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
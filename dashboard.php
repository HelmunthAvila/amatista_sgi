<?php
// Inicia la sesión para acceder a las variables de usuario autenticado
session_start();

// Valida que exista una sesión activa; si no, redirige al login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// Incluye el archivo de conexión a la base de datos
require_once("conexion.php");

// Incluye el encabezado del sistema (menú, estilos, sidebar)
include("includes/header.php");

/* --- LÓGICA DE DATOS --- */

// Función reutilizable para ejecutar una consulta y devolver un resultado asociativo
function obtenerDato($conexion, $sql) {
    $resultado = mysqli_query($conexion, $sql);
    return mysqli_fetch_assoc($resultado);
}

// Consulta para obtener el total de ventas del día
$ventas_dia = obtenerDato($conexion, "SELECT SUM(total) as total FROM ventas WHERE DATE(fecha)=CURDATE()");

// Consulta para obtener el total de ventas del mes actual
$ventas_mes = obtenerDato($conexion, "SELECT SUM(total) as total FROM ventas WHERE MONTH(fecha)=MONTH(CURDATE()) AND YEAR(fecha)=YEAR(CURDATE())");

// Consulta para contar el total de productos registrados
$productos = obtenerDato($conexion, "SELECT COUNT(*) as total FROM productos");

// Consulta para listar productos con stock bajo o crítico
$stock_bajo = mysqli_query($conexion, "SELECT nombre, marca, talla, stock FROM productos WHERE stock <= 5 ORDER BY stock ASC");
?>

<!-- Contenedor principal del dashboard -->
<div class="container-fluid mt-4">

    <!-- Encabezado del dashboard con saludo y menú de usuario -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-dark">Dashboard AMATISTA SGI</h1>

        <!-- Menú desplegable del usuario -->
        <div class="dropdown">
            <button class="btn btn-white shadow-sm rounded-pill px-4 py-2 border dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle text-primary me-2"></i>
                <span class="text-muted me-2">Hola,</span>

                <!-- Muestra el nombre del usuario autenticado -->
                <strong class="text-dark"><?php echo $_SESSION['nombre_usuario'] ?? 'Usuario'; ?></strong>
            </button>
            
            <!-- Opciones del menú de usuario -->
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4 mt-2">

                <!-- Opción visible solo para administradores -->
                <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin'): ?>
                <li>
                    <a class="dropdown-item py-2" href="modulos/usuarios/listar.php">
                        <i class="bi bi-shield-lock me-2 text-primary"></i> Gestionar Usuarios
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <?php endif; ?>

                <!-- Opción para cerrar sesión -->
                <li>
                    <a class="dropdown-item py-2 text-danger" href="logout.php">
                        <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Tarjetas de indicadores del sistema -->
    <div class="row g-4 mb-4">
        <?php 
        // Definición de las tarjetas estadísticas del dashboard
        $cards = [
            ["Ventas Hoy", $ventas_dia['total'] ?? 0, "bi-cash-stack", "#2563eb"],
            ["Ventas Mes", $ventas_mes['total'] ?? 0, "bi-graph-up", "#059669"],
            ["Productos", $productos['total'] ?? 0, "bi-box-seam", "#7c3aed"],
            ["Stock Bajo", mysqli_num_rows($stock_bajo), "bi-exclamation-triangle", "#dc2626"]
        ];

        // Genera dinámicamente las tarjetas con sus indicadores
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

    <!-- Tabla que muestra los productos con stock crítico -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <!-- Encabezado de la tabla -->
        <div class="card-header bg-white py-3 border-0">
            <h5 class="fw-bold text-danger mb-0">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>Productos con Stock Crítico
            </h5>
        </div>

        <!-- Tabla responsiva de productos -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <!-- Encabezado de columnas -->
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Producto</th>
                        <th>Marca</th>
                        <th>Talla</th>
                        <th class="text-center">Stock</th>
                    </tr>
                </thead>

                <!-- Cuerpo de la tabla con datos de productos -->
                <tbody>
                    <?php while($p = mysqli_fetch_assoc($stock_bajo)): ?>
                    <tr>
                        <td class="ps-4 fw-bold"><?php echo htmlspecialchars($p['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($p['marca']); ?></td>
                        <td><?php echo htmlspecialchars($p['talla']); ?></td>

                        <!-- Muestra el stock con una alerta visual -->
                        <td class="text-center">
                            <span class="badge bg-danger px-3 py-2"><?php echo $p['stock']; ?></span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>

<?php 
// Incluye el pie de página del sistema
include("includes/footer.php"); 
?>
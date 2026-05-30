<?php
// Incluye la conexión a la base de datos
include("../../conexion.php");

// Incluye el encabezado del sistema (menú, estilos, navegación)
include("../../includes/header.php");

// Captura los filtros de fecha enviados por la URL (GET)
$fecha_inicio = $_GET['fecha_inicio'] ?? '';
$fecha_fin = $_GET['fecha_fin'] ?? '';

// Define la consulta base para obtener el historial de ventas con su cliente
$query = "SELECT v.id, v.fecha, v.total, c.nombre as cliente 
          FROM ventas v
          INNER JOIN clientes c ON v.id_cliente = c.id
          WHERE 1=1";

// Aplica filtro de fecha inicial si el usuario lo especifica
if(!empty($fecha_inicio)){
    $query .= " AND DATE(v.fecha) >= '$fecha_inicio'";
}

// Aplica filtro de fecha final si el usuario lo especifica
if(!empty($fecha_fin)){
    $query .= " AND DATE(v.fecha) <= '$fecha_fin'";
}

// Ordena las ventas mostrando primero las más recientes
$query .= " ORDER BY v.fecha DESC";

// Ejecuta la consulta en la base de datos
$ventas = mysqli_query($conexion,$query);

// Verifica si ocurrió un error en la consulta SQL
if(!$ventas){
    die("Error en consulta: ".mysqli_error($conexion));
}
?>

<div class="container-fluid">

<!-- Encabezado del módulo de historial de ventas -->
<div class="d-flex justify-content-between align-items-center mb-4">
<div>
<h2 class="fw-bold mb-0 text-dark">Historial de Ventas</h2>
<p class="text-muted small">Consulta y reimprime facturas de Amatista SGI.</p>
</div>

<!-- Botón para crear una nueva venta en el POS -->
<a href="pos.php" class="btn btn-primary rounded-pill px-4 shadow-sm">
<i class="bi bi-cart-plus me-2"></i>Nueva Venta
</a>
</div>

<!-- Tarjeta que contiene el formulario de filtros -->
<div class="card shadow-sm border-0 mb-4">
<div class="card-body">

<!-- Formulario para filtrar ventas por rango de fechas -->
<form method="GET">

<div class="row">

<!-- Campo para seleccionar la fecha inicial -->
<div class="col-md-4">
<label class="form-label">Fecha Inicio</label>
<input type="date" name="fecha_inicio" class="form-control"
value="<?php echo $fecha_inicio; ?>">
</div>

<!-- Campo para seleccionar la fecha final -->
<div class="col-md-4">
<label class="form-label">Fecha Fin</label>
<input type="date" name="fecha_fin" class="form-control"
value="<?php echo $fecha_fin; ?>">
</div>

<!-- Botones para aplicar filtros o limpiar la búsqueda -->
<div class="col-md-4 d-flex align-items-end gap-2">

<button class="btn btn-primary">
<i class="bi bi-search"></i> Filtrar
</button>

<a href="listar.php" class="btn btn-secondary">
<i class="bi bi-arrow-repeat"></i> Limpiar
</a>

</div>

</div>

</form>

</div>
</div>

<!-- Tarjeta que contiene la tabla de historial de ventas -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">

<div class="table-responsive">

<!-- Tabla que muestra las ventas registradas -->
<table class="table align-middle mb-0 table-hover">

<thead class="table-light">
<tr>
<th class="ps-4">ID</th>
<th>Fecha</th>
<th>Cliente</th>
<th>Total</th>
<th class="text-center">Acciones</th>
</tr>
</thead>

<tbody>

<!-- Verifica si existen ventas registradas -->
<?php if(mysqli_num_rows($ventas)>0){ ?>

<!-- Recorre cada venta obtenida en la consulta -->
<?php while($v = mysqli_fetch_assoc($ventas)){ ?>

<tr>

<!-- Muestra el ID de la venta -->
<td class="ps-4">
<span class="badge bg-primary">
#<?php echo $v['id']; ?>
</span>
</td>

<!-- Muestra la fecha formateada de la venta -->
<td>
<?php echo date("d/m/Y H:i",strtotime($v['fecha'])); ?>
</td>

<!-- Muestra el nombre del cliente -->
<td>
<?php echo $v['cliente']; ?>
</td>

<!-- Muestra el total de la venta formateado -->
<td class="fw-bold text-success">
$<?php echo number_format($v['total']); ?>
</td>

<!-- Acciones disponibles para cada venta -->
<td class="text-center">

<div class="btn-group">

<!-- Botón para ver el detalle de la venta -->
<a href="ver_detalle.php?id=<?php echo $v['id']; ?>"
class="btn btn-sm btn-light border">
<i class="bi bi-eye"></i>
</a>

<!-- Botón para imprimir el ticket de la venta -->
<a href="ticket.php?id=<?php echo $v['id']; ?>"
target="_blank"
class="btn btn-sm btn-light border">
<i class="bi bi-printer"></i>
</a>

<!-- Botón para eliminar o anular la venta -->
<a href="eliminar.php?id=<?php echo $v['id']; ?>"
class="btn btn-sm btn-light text-danger"
onclick="return confirm('¿Anular venta?')">
<i class="bi bi-trash"></i>
</a>

</div>

</td>

</tr>

<?php } ?>

<?php } else { ?>

<!-- Mensaje mostrado cuando no existen ventas en el rango seleccionado -->
<tr>
<td colspan="5" class="text-center py-4">
No hay ventas en ese rango de fechas.
</td>
</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<!-- Incluye el pie de página del sistema -->
<?php include("../../includes/footer.php"); ?>
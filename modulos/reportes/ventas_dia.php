<?php

// 1. Conexión a la base de datos
include("../../conexion.php");

// 2. Cargar encabezado del sistema (menú, estilos y estructura)
include("../../includes/header.php");

// 3. Obtener la fecha actual del servidor
$hoy = date("Y-m-d");

// 4. Consulta para obtener todas las ventas realizadas hoy
$ventas = mysqli_query($conexion,"
SELECT ventas.*, clientes.nombre 
FROM ventas
JOIN clientes ON ventas.id_cliente = clientes.id
WHERE DATE(ventas.fecha) = '$hoy'
ORDER BY ventas.fecha DESC
");

?>

<div class="container-fluid">

<!-- TITULO DEL REPORTE -->
<div class="d-flex justify-content-between align-items-center mb-2">
    <div>
        <!-- Nombre del reporte -->
        <h2 class="fw-bold mb-0 text-dark">Ventas del Día</h2>

        <!-- Descripción -->
        <p class="text-muted small">Listado de ventas realizadas hoy.</p>
    </div>
</div>


<!-- MENU DE REPORTES DEL SISTEMA -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

<div class="btn-group shadow-sm">

<!-- Acceso al inventario -->
<a href="inventario.php" class="btn btn-outline-primary">
<i class="bi bi-box-seam"></i> Inventario
</a>

<!-- Reporte de stock bajo -->
<a href="stock_bajo.php" class="btn btn-outline-warning">
<i class="bi bi-exclamation-triangle"></i> Stock Bajo
</a>

<!-- Reporte actual: ventas del día -->
<a href="ventas_dia.php" class="btn btn-success">
<i class="bi bi-calendar-day"></i> Ventas Día
</a>

<!-- Reporte de ventas del mes -->
<a href="ventas_mes.php" class="btn btn-outline-info">
<i class="bi bi-calendar-month"></i> Ventas Mes
</a>

<!-- Exportar inventario a Excel -->
<a href="exportar_excel.php" class="btn btn-outline-success">
<i class="bi bi-file-earmark-excel"></i> Exportar Excel
</a>

</div>


<!-- FORMULARIO PARA FILTRAR VENTAS POR RANGO DE FECHAS -->
<form method="GET" action="ventas_filtro.php" class="d-flex gap-2">

<!-- Fecha inicial -->
<input type="date" name="fecha_inicio" class="form-control">

<!-- Fecha final -->
<input type="date" name="fecha_fin" class="form-control">

<!-- Botón de búsqueda -->
<button class="btn btn-dark">
<i class="bi bi-search"></i> Filtrar
</button>

</form>

</div>


<!-- TABLA DE VENTAS -->

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">

<div class="table-responsive">

<table class="table align-middle mb-0 table-hover">

<!-- ENCABEZADO DE TABLA -->
<thead class="table-light">

<tr>
<th class="ps-4 py-3 text-uppercase small fw-bold text-muted">ID</th>
<th class="text-uppercase small fw-bold text-muted">Cliente</th>
<th class="text-uppercase small fw-bold text-muted">Fecha</th>
<th class="text-uppercase small fw-bold text-muted">Total</th>
<th class="text-uppercase small fw-bold text-muted text-center">Acciones</th>
</tr>

</thead>

<tbody>

<!-- Recorrer las ventas obtenidas en la consulta -->
<?php while($v=mysqli_fetch_array($ventas)){ ?>

<tr>

<!-- ID de la venta -->
<td class="ps-4 fw-bold"><?php echo $v['id']; ?></td>

<!-- Nombre del cliente -->
<td>
<i class="bi bi-person text-primary me-2"></i>
<?php echo $v['nombre']; ?>
</td>

<!-- Fecha de la venta -->
<td>
<i class="bi bi-clock text-muted me-2"></i>
<?php echo $v['fecha']; ?>
</td>

<!-- Total de la venta formateado -->
<td class="fw-bold text-success">
$<?php echo number_format($v['total'],0,",","."); ?>
</td>

<!-- Botón para ver el detalle de la venta -->
<td class="text-center">

<a href="../ventas/ver_detalle.php?id=<?php echo $v['id']; ?>" 
class="btn btn-sm btn-outline-primary">

<i class="bi bi-eye"></i> Ver

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<?php

// 5. Cargar pie de página del sistema
include("../../includes/footer.php");

?>
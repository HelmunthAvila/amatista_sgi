<?php

// 1. Incluir conexión a la base de datos
include("../../conexion.php");

// 2. Incluir encabezado del sistema (menú, estilos, estructura)
include("../../includes/header.php");

// 3. Consulta para obtener todos los productos ordenados por nombre
$productos = mysqli_query($conexion,"SELECT * FROM productos ORDER BY nombre ASC");

?>

<div class="container-fluid">

<!-- TITULO DEL MODULO DE INVENTARIO -->
<div class="d-flex justify-content-between align-items-center mb-2">
    <div>
        <h2 class="fw-bold mb-0 text-dark">Inventario General</h2>
        <p class="text-muted small">Listado completo de productos disponibles.</p>
    </div>
</div>


<!-- MENU DE REPORTES DEL SISTEMA -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

<!-- Botones de acceso rápido a los diferentes reportes -->
<div class="btn-group shadow-sm">

<!-- Reporte de inventario general -->
<a href="inventario.php" class="btn btn-outline-primary">
<i class="bi bi-box-seam"></i> Inventario
</a>

<!-- Reporte de productos con stock bajo -->
<a href="stock_bajo.php" class="btn btn-outline-warning">
<i class="bi bi-exclamation-triangle"></i> Stock Bajo
</a>

<!-- Reporte de ventas del día -->
<a href="ventas_dia.php" class="btn btn-outline-success">
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


<!-- Formulario para filtrar ventas por rango de fechas -->
<form method="GET" action="ventas_filtro.php" class="d-flex gap-2">

<!-- Campo fecha inicio -->
<input type="date" name="fecha_inicio" class="form-control">

<!-- Campo fecha fin -->
<input type="date" name="fecha_fin" class="form-control">

<!-- Botón para ejecutar el filtro -->
<button class="btn btn-dark">
<i class="bi bi-search"></i> Filtrar
</button>

</form>

</div>


<!-- TABLA DEL INVENTARIO -->

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">

<div class="table-responsive">

<table class="table align-middle mb-0 table-hover">

<!-- ENCABEZADO DE LA TABLA -->
<thead class="table-light">

<tr>
<th class="ps-4 py-3 text-uppercase small fw-bold text-muted">Producto</th>
<th class="text-uppercase small fw-bold text-muted">Marca</th>
<th class="text-uppercase small fw-bold text-muted">Talla</th>
<th class="text-uppercase small fw-bold text-muted">Precio</th>
<th class="text-uppercase small fw-bold text-muted">Stock</th>
</tr>

</thead>

<tbody>

<!-- Recorrer todos los productos obtenidos de la base de datos -->
<?php while($p=mysqli_fetch_array($productos)){ ?>

<tr>

<!-- Columna producto -->
<td class="ps-4">

<div class="d-flex align-items-center">

<!-- Icono del producto -->
<div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
style="width:35px;height:35px;">
<i class="bi bi-box"></i>
</div>

<!-- Nombre del producto -->
<span class="fw-bold"><?php echo $p['nombre']; ?></span>

</div>

</td>


<!-- Marca del producto -->
<td><?php echo $p['marca']; ?></td>


<!-- Talla del producto -->
<td>

<span class="badge bg-secondary">
<?php echo $p['talla']; ?>
</span>

</td>


<!-- Precio del producto formateado -->
<td class="fw-bold text-success">

$<?php echo number_format($p['precio'],0,",","."); ?>

</td>


<!-- Stock del producto -->
<td>

<!-- Validación para mostrar alerta si el stock es bajo -->
<?php if($p['stock'] <= 5){ ?>

<span class="badge bg-danger">
<?php echo $p['stock']; ?> Bajo
</span>

<?php } else { ?>

<span class="badge bg-success">
<?php echo $p['stock']; ?>
</span>

<?php } ?>

</td>


</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<?php

// 4. Incluir pie de página del sistema
include("../../includes/footer.php");

?>
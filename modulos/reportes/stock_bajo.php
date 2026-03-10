<?php

// 1. Incluir conexión a la base de datos
include("../../conexion.php");

// 2. Incluir encabezado del sistema (menú, estilos y estructura)
include("../../includes/header.php");

// 3. Consulta para obtener productos con stock menor o igual a 5 unidades
$productos = mysqli_query($conexion,"
SELECT * FROM productos 
WHERE stock <= 5 
ORDER BY stock ASC
");

?>

<div class="container-fluid">

<!-- TITULO DEL REPORTE -->
<div class="d-flex justify-content-between align-items-center mb-2">
    <div>
        <!-- Nombre del reporte -->
        <h2 class="fw-bold mb-0 text-dark">Productos con Stock Bajo</h2>

        <!-- Descripción del reporte -->
        <p class="text-muted small">Productos que requieren reposición de inventario.</p>
    </div>
</div>


<!-- MENU DE REPORTES DEL SISTEMA -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

<!-- Botones de acceso a los diferentes reportes -->
<div class="btn-group shadow-sm">

<!-- Acceso al inventario general -->
<a href="inventario.php" class="btn btn-outline-primary">
<i class="bi bi-box-seam"></i> Inventario
</a>

<!-- Reporte actual de productos con stock bajo -->
<a href="stock_bajo.php" class="btn btn-warning">
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


<!-- FORMULARIO PARA FILTRAR VENTAS POR RANGO DE FECHAS -->
<form method="GET" action="ventas_filtro.php" class="d-flex gap-2">

<!-- Fecha inicial del filtro -->
<input type="date" name="fecha_inicio" class="form-control">

<!-- Fecha final del filtro -->
<input type="date" name="fecha_fin" class="form-control">

<!-- Botón para ejecutar la búsqueda -->
<button class="btn btn-dark">
<i class="bi bi-search"></i> Filtrar
</button>

</form>

</div>


<!-- TABLA DE PRODUCTOS CON STOCK BAJO -->

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

<!-- Recorrer todos los productos con stock bajo -->
<?php while($p=mysqli_fetch_array($productos)){ ?>

<tr>

<!-- Columna nombre del producto -->
<td class="ps-4">

<div class="d-flex align-items-center">

<!-- Icono de advertencia -->
<div class="bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center me-3"
style="width:35px;height:35px;">
<i class="bi bi-exclamation-triangle"></i>
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

<!-- Precio formateado -->
<td class="fw-bold text-success">

$<?php echo number_format($p['precio'],0,",","."); ?>

</td>

<!-- Stock bajo resaltado -->
<td>

<span class="badge bg-danger">
<?php echo $p['stock']; ?> Bajo
</span>

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
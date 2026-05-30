<!-- CONTENEDOR PRINCIPAL DEL MENÚ DE REPORTES Y FILTROS -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

<!-- GRUPO DE BOTONES DE REPORTES -->
<div class="btn-group shadow-sm">

<!-- Botón para ver el reporte de inventario general -->
<a href="inventario.php" class="btn btn-outline-primary">
<i class="bi bi-box-seam"></i> Inventario
</a>

<!-- Botón para ver productos con stock bajo -->
<a href="stock_bajo.php" class="btn btn-outline-warning">
<i class="bi bi-exclamation-triangle"></i> Stock Bajo
</a>

<!-- Botón para ver ventas realizadas en el día -->
<a href="ventas_dia.php" class="btn btn-outline-success">
<i class="bi bi-calendar-day"></i> Ventas Día
</a>

<!-- Botón para ver ventas realizadas en el mes -->
<a href="ventas_mes.php" class="btn btn-outline-info">
<i class="bi bi-calendar-month"></i> Ventas Mes
</a>

<!-- Botón para exportar el inventario a Excel -->
<a href="exportar_excel.php" class="btn btn-outline-success">
<i class="bi bi-file-earmark-excel"></i> Exportar Excel
</a>

</div>


<!-- FORMULARIO PARA FILTRAR REPORTES POR RANGO DE FECHAS -->
<form method="GET" action="ventas_filtro.php" class="d-flex gap-2">

<!-- Campo para seleccionar fecha inicial -->
<input type="date" name="fecha_inicio" class="form-control">

<!-- Campo para seleccionar fecha final -->
<input type="date" name="fecha_fin" class="form-control">

<!-- Botón para ejecutar el filtro de búsqueda -->
<button class="btn btn-dark">
<i class="bi bi-search"></i> Filtrar
</button>

</form>

</div>
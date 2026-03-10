<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

<div class="btn-group shadow-sm">

<a href="inventario.php" class="btn btn-outline-primary">
<i class="bi bi-box-seam"></i> Inventario
</a>

<a href="stock_bajo.php" class="btn btn-outline-warning">
<i class="bi bi-exclamation-triangle"></i> Stock Bajo
</a>

<a href="ventas_dia.php" class="btn btn-outline-success">
<i class="bi bi-calendar-day"></i> Ventas Día
</a>

<a href="ventas_mes.php" class="btn btn-outline-info">
<i class="bi bi-calendar-month"></i> Ventas Mes
</a>

<a href="exportar_excel.php" class="btn btn-outline-success">
<i class="bi bi-file-earmark-excel"></i> Exportar Excel
</a>

</div>


<form method="GET" action="ventas_filtro.php" class="d-flex gap-2">

<input type="date" name="fecha_inicio" class="form-control">

<input type="date" name="fecha_fin" class="form-control">

<button class="btn btn-dark">
<i class="bi bi-search"></i> Filtrar
</button>

</form>

</div>



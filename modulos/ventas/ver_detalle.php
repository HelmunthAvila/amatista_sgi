<?php
include("../../conexion.php");
include("../../includes/header.php");

// Verificar que llegue el ID
if(!isset($_GET['id'])){
    header("Location: listar.php");
    exit();
}

$id_venta = intval($_GET['id']);

// 1. Obtener datos de la venta
$query_venta = mysqli_query($conexion, "SELECT v.*, c.nombre as cliente 
    FROM ventas v
    INNER JOIN clientes c ON v.id_cliente = c.id
    WHERE v.id = $id_venta");

if(!$query_venta){
    die("Error en consulta: " . mysqli_error($conexion));
}

$venta = mysqli_fetch_assoc($query_venta);

if(!$venta){
    echo "Venta no encontrada.";
    exit();
}

// 2. Obtener productos vendidos
$query_productos = mysqli_query($conexion, "SELECT d.cantidad, d.precio_unitario, p.nombre
    FROM detalle_venta d
    INNER JOIN productos p ON d.id_producto = p.id
    WHERE d.id_venta = $id_venta");

if(!$query_productos){
    die("Error en consulta: " . mysqli_error($conexion));
}
?>

<div class="container-fluid">

<div class="mb-4">

<a href="listar.php" class="btn btn-sm btn-light mb-2">
<i class="bi bi-arrow-left"></i> Volver
</a>

<h2 class="fw-bold">
Detalle de Venta #<?php echo $id_venta; ?>
</h2>

<p class="text-muted">
Cliente: <strong><?php echo $venta['cliente']; ?></strong>
</p>

<p class="text-muted">
Fecha: <?php echo date("d/m/Y H:i", strtotime($venta['fecha'])); ?>
</p>

</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">

<table class="table align-middle mb-0">

<thead class="table-light">
<tr>
<th class="ps-4">Producto</th>
<th>Precio Unitario</th>
<th>Cantidad</th>
<th class="text-end pe-4">Subtotal</th>
</tr>
</thead>

<tbody>

<?php
$total = 0;

while($p = mysqli_fetch_assoc($query_productos)){

$precio = $p['precio_unitario'];
$cantidad = $p['cantidad'];

$subtotal = $precio * $cantidad;
$total += $subtotal;
?>

<tr>

<td class="ps-4 fw-bold">
<?php echo $p['nombre']; ?>
</td>

<td>
$<?php echo number_format($precio); ?>
</td>

<td>
<?php echo $cantidad; ?>
</td>

<td class="text-end pe-4 fw-bold text-primary">
$<?php echo number_format($subtotal); ?>
</td>

</tr>

<?php } ?>

</tbody>

<tfoot class="table-light">

<tr>

<td colspan="3" class="text-end fw-bold">
TOTAL VENTA
</td>

<td class="text-end pe-4 h4 fw-bold text-dark">
$<?php echo number_format($total); ?>
</td>

</tr>

</tfoot>

</table>

</div>

</div>

<?php include("../../includes/footer.php"); ?>
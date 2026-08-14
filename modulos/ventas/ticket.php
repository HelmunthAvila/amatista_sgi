<?php
include("../../includes/sesion.php");
// 1. Conexión a la base de datos
include("../../conexion.php");

if(!isset($_GET['id'])){
    die("ID de venta no proporcionado.");
}

$id_venta = intval($_GET['id']); // Validamos como entero por seguridad

// 2. Consultar encabezado de la venta y datos del cliente
$stmt_venta = mysqli_prepare($conexion, "SELECT v.*, c.nombre as cliente, c.telefono 
    FROM ventas v 
    INNER JOIN clientes c ON v.id_cliente = c.id 
    WHERE v.id = ?");
mysqli_stmt_bind_param($stmt_venta, "i", $id_venta);
mysqli_stmt_execute($stmt_venta);
$query_venta = mysqli_stmt_get_result($stmt_venta);
$venta = mysqli_fetch_array($query_venta);

if(!$venta){
    die("La venta especificada no existe.");
}

// 3. Selección con alias coincidente para el bucle
$stmt_detalle = mysqli_prepare($conexion, "SELECT d.cantidad, d.precio_unitario as precio, p.nombre 
    FROM detalle_venta d 
    INNER JOIN productos p ON d.id_producto = p.id 
    WHERE d.id_venta = ?");
mysqli_stmt_bind_param($stmt_detalle, "i", $id_venta);
mysqli_stmt_execute($stmt_detalle);
$detalle = mysqli_stmt_get_result($stmt_detalle);

if(!$detalle){
    die("Error al consultar el detalle. Inténtalo de nuevo.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket #<?php echo $id_venta; ?></title>
    <style>
        * { font-family: 'Courier New', Courier, monospace; }
        body { width: 75mm; margin: 0; padding: 10px; font-size: 12px; color: #000; }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .divider { border-top: 1px dashed #000; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        .header p { margin: 2px 0; }
        .total { font-size: 16px; font-weight: bold; margin-top: 10px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="text-center header">
        <h2 style="margin: 0; font-size: 18px;">AMATISTA SGI</h2>
        <p>Calzado y Estilo Profesional</p>
        <p>Nit: 123456789-0</p>
        <p>---------------------------</p>
        <p><strong>TICKET DE VENTA #<?php echo $id_venta; ?></strong></p>
        <?php if($venta['estado'] == 0): ?>
            <p style="color: #d9383a; font-weight: bold;">*** FACTURA ANULADA ***</p>
        <?php endif; ?>
        <p>Fecha: <?php echo date("d/m/Y H:i", strtotime($venta['fecha'])); ?></p>
    </div>

    <div class="divider"></div>

    <div class="cliente">
        <p><strong>Cliente:</strong> <?php echo htmlspecialchars($venta['cliente']); ?></p>
        <p><strong>Tel:</strong> <?php echo htmlspecialchars($venta['telefono'] ? $venta['telefono'] : 'N/A'); ?></p>
    </div>

    <div class="divider"></div>

    <table>
        <thead>
            <tr>
                <th align="left">PRODUCTO</th>
                <th align="center">CANT.</th>
                <th align="right">SUBT.</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total_acumulado = 0;
            while($d = mysqli_fetch_array($detalle)){ 
                $subtotal_item = $d['cantidad'] * $d['precio'];
                $total_acumulado += $subtotal_item;
            ?>
            <tr>
                <td style="padding-top: 5px;"><?php echo htmlspecialchars($d['nombre']); ?></td>
                <td align="center">x<?php echo $d['cantidad']; ?></td>
                <td align="right">$<?php echo number_format($subtotal_item); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="divider"></div>

    <div class="text-end total">
        TOTAL A PAGAR: $<?php echo number_format($total_acumulado); ?>
    </div>

    <div class="text-center" style="margin-top: 20px;">
        <p>¡Gracias por su compra en Amatista!</p>
        <p>Conserve este ticket para cambios.</p>
        <p>Visite nuestro inventario pronto.</p>
    </div>

    <div class="no-print" style="margin-top: 30px;">
        <button onclick="window.print()" style="width: 100%; padding: 10px; background: #000; color: #fff; border: none; cursor: pointer;">
            Reimprimir Ticket
        </button>
        <a href="pos.php" style="display: block; text-align: center; margin-top: 10px; color: #666; text-decoration: none;">
            ← Volver al Punto de Venta
        </a>
    </div>

    <script>
        window.onload = function() {
            window.print();
            // Cierra la pestaña secundaria automáticamente después de completar o cancelar la cola
            setTimeout(function() {
                window.close();
            }, 1000);
        }
    </script>
</body>
</html>
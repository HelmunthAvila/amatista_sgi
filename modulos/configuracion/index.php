<?php
include("../../includes/sesion.php");
requiere_rol('admin');

include("../../conexion.php");
include("../../includes/configuracion.php");
// Verificación CSRF (AM-008)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !csrf_verificar()) {
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'La sesión expiró. Recarga la página e intenta nuevamente.'];
    header("Location: index.php");
    exit();
}

// Guardar el parámetro de stock mínimo
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nuevo_valor = isset($_POST['stock_minimo']) ? trim($_POST['stock_minimo']) : '';

    if ($nuevo_valor === '' || !ctype_digit($nuevo_valor)) {
        $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'El stock mínimo debe ser un número entero mayor o igual a cero.'];
    } else {
        $stock_minimo = intval($nuevo_valor);
        $stmt = mysqli_prepare($conexion, "UPDATE configuracion SET valor = ? WHERE clave = 'stock_minimo'");
        mysqli_stmt_bind_param($stmt, "s", $stock_minimo);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['alerta'] = ['tipo' => 'success', 'mensaje' => '<strong>¡Guardado!</strong> El stock mínimo ahora es <strong>' . $stock_minimo . '</strong> unidades.'];
        } else {
            $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'No se pudo guardar el parámetro.'];
        }
        mysqli_stmt_close($stmt);
    }
    header("Location: index.php");
    exit();
}

$stock_minimo_actual = obtener_stock_minimo($conexion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php $titulo_pagina = "Configuración - Amatista SGI"; ?>
    <?php include("../../includes/head.php"); ?>
</head>
<body>
<?php include("../../includes/header.php"); ?>

<div id="main-wrapper">
    <main class="content-area">
        <div class="container-fluid px-4 py-3">

            <?php if(isset($_SESSION['alerta'])): ?>
                <div id="alerta-automatica" class="alert alert-<?php echo $_SESSION['alerta']['tipo']; ?> alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4 p-3" role="alert">
                    <div class="d-flex align-items-center">
                        <?php if($_SESSION['alerta']['tipo'] == 'success'): ?>
                            <i class="bi bi-check-circle-fill me-3 fs-4 text-success"></i>
                        <?php else: ?>
                            <i class="bi bi-exclamation-triangle-fill me-3 fs-4 text-danger"></i>
                        <?php endif; ?>
                        <div><?php echo $_SESSION['alerta']['mensaje']; ?></div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['alerta']); ?>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px;">Configuración</h2>
                    <p class="text-muted small mb-0">Parámetros globales del sistema.</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-custom shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center me-3"
                                     style="width: 44px; height: 44px; background-color: #f0ebfa !important; color: var(--primary-color) !important;">
                                    <i class="bi bi-box-seam fs-5"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-0 text-dark">Stock mínimo</h5>
                                    <small class="text-muted">Umbral de "stock bajo" en todo el sistema</small>
                                </div>
                            </div>

                            <p class="text-muted small mb-4">
                                Los productos con existencia menor o igual a este valor se consideran
                                <strong>críticos</strong> y aparecen en el dashboard y en el reporte de stock bajo.
                            </p>

                            <form method="POST" action="index.php">
                                <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold text-secondary text-uppercase">Unidades mínimas</label>
                                    <input type="number" name="stock_minimo" class="form-control form-control-custom"
                                           value="<?php echo $stock_minimo_actual; ?>" min="0" max="999" required>
                                </div>
                                <button type="submit" class="btn btn-amatista-primary rounded-pill px-4 shadow-sm">
                                    <i class="bi bi-check2-circle me-2"></i> Guardar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const alerta = document.getElementById('alerta-automatica');
        if (alerta) {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alerta);
                bsAlert.close();
            }, 2500);
        }
    });
</script>

</body>
</html>

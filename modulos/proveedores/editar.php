<?php
// 1. Inclusión del archivo de conexión a la base de datos y del encabezado del sistema
include("../../conexion.php");
include("../../includes/header.php");

// 2. Obtener el ID del proveedor enviado por URL y consultar los datos actuales
if (isset($_GET['id'])) {

    // Limpieza del ID para evitar inyección SQL
    $id = mysqli_real_escape_string($conexion, $_GET['id']);

    // Consulta para obtener los datos del proveedor
    $consulta = mysqli_query($conexion, "SELECT * FROM proveedores WHERE id = '$id'");

    // Convertir el resultado en un arreglo
    $p = mysqli_fetch_array($consulta);

    // Si el proveedor no existe, redirige al listado
    if (!$p) { 
        header("Location: listar.php"); 
    }

} else {

    // Si no se recibe el ID, regresar al listado
    header("Location: listar.php");

}
?>

<!-- Contenedor principal -->
<div class="container-fluid">

    <!-- Título del módulo -->
    <div class="mb-4">
        <h2 class="fw-bold mb-0 text-dark">Editar Proveedor</h2>
        <p class="text-muted small">Actualiza la información de contacto o la empresa proveedora.</p>
    </div>

    <!-- Distribución en columnas -->
    <div class="row">

        <!-- Columna del formulario -->
        <div class="col-md-6">

            <!-- Tarjeta visual -->
            <div class="card border-0 shadow-sm rounded-4">

                <!-- Cuerpo de la tarjeta -->
                <div class="card-body p-4">

                    <!-- Formulario que envía datos al archivo actualizar.php -->
                    <form action="actualizar.php" method="POST">

                        <!-- Campo oculto que guarda el ID del proveedor -->
                        <input type="hidden" name="id" value="<?php echo $p['id']; ?>">

                        <!-- Campo para editar nombre del contacto -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">Nombre de Contacto</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" name="nombre" class="form-control bg-light border-0" 
                                       value="<?php echo $p['nombre']; ?>" required>
                            </div>
                        </div>

                        <!-- Campo para editar teléfono -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">Teléfono</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">
                                    <i class="bi bi-telephone"></i>
                                </span>
                                <input type="text" name="telefono" class="form-control bg-light border-0" 
                                       value="<?php echo $p['telefono']; ?>" required>
                            </div>
                        </div>

                        <!-- Campo para editar empresa o fábrica -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted small text-uppercase">Empresa / Fábrica</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">
                                    <i class="bi bi-building"></i>
                                </span>
                                <input type="text" name="empresa" class="form-control bg-light border-0" 
                                       value="<?php echo $p['empresa']; ?>" required>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="d-flex gap-2">

                            <!-- Botón para actualizar los datos -->
                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                                <i class="bi bi-check-lg me-2"></i>Actualizar Datos
                            </button>

                            <!-- Botón para cancelar y volver al listado -->
                            <a href="listar.php" class="btn btn-light rounded-pill px-4">Cancelar</a>

                        </div>

                    </form>

                </div>
            </div>
        </div>
        
        <!-- Columna de información o ayuda -->
        <div class="col-md-4">

            <!-- Tarjeta informativa -->
            <div class="card border-0 bg-primary text-white rounded-4 shadow-sm h-100">

                <div class="card-body p-4 d-flex flex-column justify-content-center text-center">

                    <!-- Icono informativo -->
                    <i class="bi bi-info-circle mb-3" style="font-size: 2rem;"></i>

                    <!-- Título del consejo -->
                    <h5 class="fw-bold">Consejo de Gestión</h5>

                    <!-- Texto de recomendación -->
                    <p class="small opacity-75">
                        Mantener los datos de contacto actualizados asegura una comunicación fluida con tus fabricantes de calzado.
                    </p>

                </div>
            </div>

        </div>
    </div>
</div>

<?php 
// 3. Inclusión del pie de página del sistema
include("../../includes/footer.php"); 
?>
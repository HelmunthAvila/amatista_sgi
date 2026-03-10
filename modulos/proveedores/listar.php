<?php

// 1. Incluir el archivo de conexión a la base de datos
include("../../conexion.php");

// 2. Incluir el encabezado del sistema (menú, estilos, estructura)
include("../../includes/header.php");

// 3. Consulta para obtener todos los proveedores ordenados por nombre de empresa
$proveedores = mysqli_query($conexion, "SELECT * FROM proveedores ORDER BY empresa ASC");

?>

<!-- Contenedor principal del módulo -->
<div class="container-fluid">

    <!-- Encabezado del módulo con título y botón de registro -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <!-- Título del módulo -->
            <h2 class="fw-bold mb-0 text-dark">Gestión de Proveedores</h2>

            <!-- Descripción del módulo -->
            <p class="text-muted small">Directorio de fábricas y contactos de suministros de calzado.</p>
        </div>

        <!-- Botón para ir al formulario de registro de proveedores -->
        <a href="agregar.php" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-person-plus-fill me-2"></i>Registrar Proveedor
        </a>
    </div>

    <!-- Tarjeta que contiene la tabla -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <!-- Contenedor responsivo de la tabla -->
        <div class="table-responsive">

            <!-- Tabla de proveedores -->
            <table class="table align-middle mb-0 table-hover">

                <!-- Encabezado de la tabla -->
                <thead class="table-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">Empresa / Fábrica</th>
                        <th class="text-uppercase small fw-bold text-muted">Contacto Principal</th>
                        <th class="text-uppercase small fw-bold text-muted">Teléfono</th>
                        <th class="text-center text-uppercase small fw-bold text-muted">Acciones</th>
                    </tr>
                </thead>

                <!-- Cuerpo de la tabla -->
                <tbody>

                    <!-- Recorrer todos los proveedores obtenidos de la base de datos -->
                    <?php while($p = mysqli_fetch_array($proveedores)){ ?>

                    <tr>

                        <!-- Columna de empresa -->
                        <td class="ps-4">

                            <div class="d-flex align-items-center">

                                <!-- Icono visual -->
                                <div class="bg-light text-primary rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-building"></i>
                                </div>

                                <!-- Información del proveedor -->
                                <div>
                                    <span class="fw-bold text-dark d-block"><?php echo $p['empresa']; ?></span>
                                    <span class="text-muted small">Proveedor Activo</span>
                                </div>

                            </div>

                        </td>

                        <!-- Columna del contacto principal -->
                        <td>
                            <span class="text-dark fw-medium"><?php echo $p['nombre']; ?></span>
                        </td>

                        <!-- Columna del teléfono -->
                        <td>

                            <div class="d-flex flex-column">

                                <!-- Enlace para llamar directamente -->
                                <a href="tel:<?php echo $p['telefono']; ?>" class="text-decoration-none text-muted mb-1 small">
                                    <i class="bi bi-telephone-fill text-primary me-2"></i>
                                    <?php echo $p['telefono']; ?>
                                </a>

                                <!-- Enlace directo para abrir conversación en WhatsApp -->
                                <a href="https://wa.me/57<?php echo str_replace(' ', '', $p['telefono']); ?>" target="_blank" class="btn btn-sm btn-outline-success border-0 p-0 text-start" style="font-size: 0.75rem;">
                                    <i class="bi bi-whatsapp me-1"></i> WhatsApp
                                </a>

                            </div>

                        </td>

                        <!-- Columna de acciones -->
                        <td class="text-center">

                            <div class="btn-group shadow-sm rounded-3">

                                <!-- Botón para editar proveedor -->
                                <a href="editar.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-white border-end" title="Editar">
                                    <i class="bi bi-pencil-square text-primary"></i>
                                </a>

                                <!-- Botón para eliminar proveedor -->
                                <a href="eliminar.php?id=<?php echo $p['id']; ?>" 
                                   class="btn btn-sm btn-white"
                                   onclick="return confirm('¿Seguro que desea eliminar este proveedor?')" 
                                   title="Eliminar">
                                    <i class="bi bi-trash3 text-danger"></i>
                                </a>

                            </div>

                        </td>

                    </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php 

// 4. Incluir el pie de página del sistema
include("../../includes/footer.php"); 

?>
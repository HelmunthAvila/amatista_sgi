<?php
include("../../includes/sesion.php");
requiere_rol('admin');

// 1. Incluir conexión a la base de datos
include("../../conexion.php");

// 2. Incluir encabezado del sistema (menú, estilos, navbar)
include("../../includes/header.php");

/*
----------------------------------------------------
CONSULTA DE USUARIOS
----------------------------------------------------
Se obtienen todos los usuarios registrados en el
sistema ordenados alfabéticamente por nombre
*/
// Filtro de estado (auditoría): activos por defecto; ?filtro_estado=inactivo muestra desactivados
$filtro_estado = $_GET['filtro_estado'] ?? '';
$condicion_estado = ($filtro_estado === 'inactivo') ? ' WHERE u.estado = 0' : ' WHERE u.estado = 1';
$usuarios = mysqli_query($conexion, "SELECT u.*, uu.nombre as eliminado_por_nombre FROM usuarios u LEFT JOIN usuarios uu ON u.eliminado_por = uu.id" . $condicion_estado . " ORDER BY u.nombre ASC");

?>

<div class="container-fluid">

    <!-- TITULO Y BOTÓN DE REGISTRO -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-0 text-dark">Gestión de Usuarios</h2>

            <p class="text-muted small">
            Administra los accesos y roles del personal del sistema.
            </p>

        </div>

        <!-- Botón para registrar nuevo usuario -->
        <div class="d-flex gap-2">
            <?php if ($filtro_estado === 'inactivo'): ?>
                <a href="listar.php" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
                    <i class="bi bi-arrow-repeat me-2"></i>Ver Activos
                </a>
            <?php else: ?>
                <a href="listar.php?filtro_estado=inactivo" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
                    <i class="bi bi-person-slash me-2"></i>Ver Desactivados
                </a>
            <?php endif; ?>
            <a href="agregar.php" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-person-plus-fill me-2"></i>Registrar Usuario
            </a>
        </div>

    </div>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'deleted'): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
            <div>
                <strong class="text-success">¡Operación exitosa!</strong> El acceso del usuario ha sido removido del sistema de forma permanente.
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>


    <!-- TARJETA CONTENEDORA DE LA TABLA -->

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <div class="table-responsive">

            <table class="table align-middle mb-0 table-hover">

                <!-- ENCABEZADO DE TABLA -->
                <thead class="table-light">

                    <tr>

                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">
                        Nombre y Usuario
                        </th>

                        <th class="text-uppercase small fw-bold text-muted">
                        Rol
                        </th>

                        <th class="text-uppercase small fw-bold text-muted">
                        Estado
                        </th>

                        <th class="text-uppercase small fw-bold text-muted">
                        Registro
                        </th>

                        <th class="text-center text-uppercase small fw-bold text-muted">
                        Acciones
                        </th>

                    </tr>

                </thead>


                <tbody>

                <!-- RECORRER LISTADO DE USUARIOS -->
                <?php while($u = mysqli_fetch_array($usuarios)){ 

                    /*
                    ------------------------------------------
                    DEFINIR COLOR DEL ROL
                    ------------------------------------------
                    Administrador = rojo
                    Cajero = azul
                    */
                    $rol_class = ($u['rol'] == 'admin') ? 'bg-danger' : 'bg-info text-dark';

                    /*
                    ------------------------------------------
                    DEFINIR ESTADO DEL USUARIO
                    ------------------------------------------
                    1 = Activo
                    0 = Inactivo
                    */
                    $estado_text = ($u['estado'] == 1) ? 'Activo' : 'Inactivo';

                    $estado_class = ($u['estado'] == 1) ? 'bg-success' : 'bg-secondary';

                ?>

                    <tr>

                        <!-- NOMBRE Y USUARIO -->

                        <td class="ps-4">

                            <div class="d-flex align-items-center">

                                <!-- Icono de usuario -->
                                <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 35px; height: 35px;">
                                <i class="bi bi-shield-lock"></i>
                                </div>

                                <!-- Datos del usuario -->
                                <div class="d-flex flex-column">

                                    <span class="fw-bold">
                                    <?php echo $u['nombre']; ?>
                                    </span>

                                    <span class="text-muted small">
                                    @<?php echo $u['usuario']; ?>
                                    </span>

                                    <?php if($u['estado'] == 0 && !empty($u['motivo_eliminacion'])): ?>
                                        <span class="small text-danger">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Desactivado por <?php echo htmlspecialchars($u['eliminado_por_nombre'] ?? 'Usuario'); ?>
                                            el <?php echo date('d/m/Y H:i', strtotime($u['eliminado_en'])); ?> —
                                            <?php echo htmlspecialchars($u['motivo_eliminacion']); ?>
                                        </span>
                                    <?php endif; ?>

                                </div>

                            </div>

                        </td>


                        <!-- ROL DEL USUARIO -->

                        <td>

                            <span class="badge rounded-pill <?php echo $rol_class; ?> px-3">

                                <?php echo ucfirst($u['rol']); ?>

                            </span>

                        </td>


                        <!-- ESTADO -->

                        <td>

                            <span class="badge dot-indicator <?php echo $estado_class; ?> p-1 me-1"></span>

                            <small class="fw-bold">

                                <?php echo $estado_text; ?>

                            </small>

                        </td>


                        <!-- FECHA DE REGISTRO -->

                        <td>

                            <span class="text-muted small">

                                <i class="bi bi-calendar3 me-1"></i>

                                <?php echo date('d/m/Y', strtotime($u['fecha_registro'])); ?>

                            </span>

                        </td>


                        <!-- ACCIONES -->

                        <td class="text-center">

                            <div class="btn-group shadow-sm rounded-3">

                                <!-- Editar usuario -->
                                <a href="editar.php?id=<?php echo $u['id']; ?>"
                                class="btn btn-sm btn-white border-end"
                                title="Editar">

                                <i class="bi bi-pencil-square text-primary"></i>

                                </a>
                                <?php if($u['estado'] == 0): ?>
                                    <form method="POST" action="reactivar.php" class="d-inline">
                                        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                                        <input type="hidden" name="id" value="<?php echo $u['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-white bg-white" title="Reactivar">
                                            <i class="bi bi-arrow-counterclockwise text-success"></i>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <form method="POST" action="eliminar.php" class="d-inline" onsubmit="var m=prompt('Motivo de la desactivación (obligatorio):'); if(!m || !m.trim()){ alert('Debe indicar el motivo.'); return false; } this.motivo.value=m.trim();">
                                        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                                        <input type="hidden" name="id" value="<?php echo $u['id']; ?>">
                                        <input type="hidden" name="motivo" value="">
                                        <button type="submit" class="btn btn-sm btn-white bg-white" title="Desactivar">
                                            <i class="bi bi-trash text-danger"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>

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

// 3. Incluir pie de página del sistema
include("../../includes/footer.php"); 

?>
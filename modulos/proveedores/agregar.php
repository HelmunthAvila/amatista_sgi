<!-- Contenedor principal del formulario alineado con el menú lateral -->
<div class="contenedor-formulario">

    <!-- Título del formulario -->
    <h2>Registrar Nuevo Proveedor</h2>
    <hr>
    
    <!-- Formulario que envía los datos al archivo guardar.php mediante método POST -->
    <form action="guardar.php" method="POST" class="form-amatista">

        <!-- Campo para registrar el nombre del contacto del proveedor -->
        <div class="grupo-input">
            <label>Nombre del Contacto</label>
            <input type="text" name="nombre" placeholder="Ej. Carlos Ruiz" required>
        </div>

        <!-- Campo para registrar teléfono o WhatsApp del proveedor -->
        <div class="grupo-input">
            <label>Teléfono / WhatsApp</label>
            <input type="text" name="telefono" placeholder="Ej. 310 000 0000" required>
        </div>

        <!-- Campo para registrar la empresa o fábrica proveedora -->
        <div class="grupo-input">
            <label>Empresa / Fábrica</label>
            <input type="text" name="empresa" placeholder="Ej. Calzado Vizzano" required>
        </div>

        <!-- Contenedor de botones de acción del formulario -->
        <div class="acciones-form">

            <!-- Botón para guardar el proveedor -->
            <button type="submit" class="btn-guardar">Guardar Proveedor</button>

            <!-- Enlace para regresar al listado de proveedores -->
            <a href="listar.php" class="btn-cancelar">Volver al listado</a>

        </div>
    </form>
</div>

<style>

/* Contenedor principal alineado con el menú lateral del sistema */
.contenedor-formulario {
    margin-left: 260px;
    padding: 30px;
    max-width: 600px;
}

/* Estilo general del formulario */
.form-amatista {
    background: #fdfdfd;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 4px 10px rgba(0,0,0,0.05);
}

/* Grupo que contiene cada campo del formulario */
.grupo-input {
    margin-bottom: 15px;
    display: flex;
    flex-direction: column;
}

/* Estilo de las etiquetas del formulario */
.grupo-input label {
    font-weight: bold;
    margin-bottom: 5px;
    color: #4a148c; /* Morado oscuro del tema Amatista */
}

/* Estilo de los campos de entrada */
.grupo-input input {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
}

/* Contenedor de los botones de acción */
.acciones-form {
    margin-top: 20px;
}

/* Botón principal para guardar */
.btn-guardar {
    background-color: #6a1b9a;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
}

/* Botón o enlace para cancelar */
.btn-cancelar {
    display: inline-block;
    margin-left: 10px;
    color: #666;
    text-decoration: none;
    font-size: 14px;
}

/* Efecto hover del botón guardar */
.btn-guardar:hover {
    background-color: #4a148c;
}
</style>
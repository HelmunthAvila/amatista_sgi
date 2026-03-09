<div class="contenedor-formulario">
    <h2>Registrar Nuevo Proveedor</h2>
    <hr>
    
    <form action="guardar.php" method="POST" class="form-amatista">
        <div class="grupo-input">
            <label>Nombre del Contacto</label>
            <input type="text" name="nombre" placeholder="Ej. Carlos Ruiz" required>
        </div>

        <div class="grupo-input">
            <label>Teléfono / WhatsApp</label>
            <input type="text" name="telefono" placeholder="Ej. 310 000 0000" required>
        </div>

        <div class="grupo-input">
            <label>Empresa / Fábrica</label>
            <input type="text" name="empresa" placeholder="Ej. Calzado Vizzano" required>
        </div>

        <div class="acciones-form">
            <button type="submit" class="btn-guardar">Guardar Proveedor</button>
            <a href="listar.php" class="btn-cancelar">Volver al listado</a>
        </div>
    </form>
</div>

<style>
/* Alineación con el menú lateral morado */
.contenedor-formulario {
    margin-left: 260px;
    padding: 30px;
    max-width: 600px;
}

.form-amatista {
    background: #fdfdfd;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 4px 10px rgba(0,0,0,0.05);
}

.grupo-input {
    margin-bottom: 15px;
    display: flex;
    flex-direction: column;
}

.grupo-input label {
    font-weight: bold;
    margin-bottom: 5px;
    color: #4a148c; /* Morado oscuro */
}

.grupo-input input {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
}

.acciones-form {
    margin-top: 20px;
}

.btn-guardar {
    background-color: #6a1b9a;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
}

.btn-cancelar {
    display: inline-block;
    margin-left: 10px;
    color: #666;
    text-decoration: none;
    font-size: 14px;
}

.btn-guardar:hover {
    background-color: #4a148c;
}
</style>
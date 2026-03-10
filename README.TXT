====================================================
AMATISTA SGI
Sistema de Gestión de Inventario y Ventas
====================================================

Autor: Helmuth Vladimir Avila Ordoñez
Programa: ADSO – Análisis y Desarrollo de Software
Repositorio: https://github.com/HelmunthAvila/amatista_sgi

----------------------------------------------------
1. DESCRIPCIÓN DEL PROYECTO
----------------------------------------------------

Amatista SGI es un sistema web diseñado para la gestión
comercial de inventario, ventas y administración de
usuarios dentro de un negocio.

El sistema permite controlar productos, realizar ventas
mediante un Punto de Venta (POS) y administrar los
accesos de los usuarios al sistema.

Está desarrollado utilizando tecnologías web abiertas
como PHP, MySQL, HTML, CSS y JavaScript.

El sistema está pensado para pequeños y medianos
negocios que necesitan controlar su inventario y
registrar ventas de forma sencilla.

----------------------------------------------------
2. CARACTERÍSTICAS DEL SISTEMA
----------------------------------------------------

El sistema incluye los siguientes módulos principales:

1. Gestión de Usuarios
2. Gestión de Productos
3. Gestión de Clientes
4. Punto de Venta (POS)
5. Gestión de Inventario
6. Reportes
7. Exportación de información

----------------------------------------------------
3. TECNOLOGÍAS UTILIZADAS
----------------------------------------------------

Lenguajes y tecnologías utilizadas:

- PHP
- MySQL / MariaDB
- HTML5
- CSS3
- Bootstrap
- JavaScript
- Bootstrap Icons

Servidor recomendado:

- Apache
- XAMPP o WAMP
- PHP 7.4 o superior

----------------------------------------------------
4. ESTRUCTURA DEL PROYECTO
----------------------------------------------------

amatista_sgi/

conexion.php
login.php
logout.php

includes/
    header.php
    footer.php

modulos/
    productos/
    clientes/
    usuarios/
    ventas/
    reportes/

pos/
    pos.php
    agregar_carrito.php
    eliminar_producto.php
    finalizar_venta.php

----------------------------------------------------
5. MÓDULO DE USUARIOS
----------------------------------------------------

Permite administrar el acceso al sistema.

Funciones principales:

- Registrar usuarios
- Editar usuarios
- Eliminar usuarios
- Activar o desactivar usuarios
- Asignar roles

Seguridad:

Las contraseñas se almacenan encriptadas utilizando:

password_hash()

Esto evita guardar contraseñas en texto plano.

----------------------------------------------------
6. MÓDULO DE PRODUCTOS
----------------------------------------------------

Permite registrar y administrar el inventario.

Funciones:

- Registrar productos
- Editar productos
- Eliminar productos
- Controlar stock
- Visualizar inventario

Campos principales:

- Nombre
- Marca
- Talla
- Color
- Precio
- Stock

----------------------------------------------------
7. MÓDULO DE CLIENTES
----------------------------------------------------

Permite administrar la información de los clientes.

Funciones:

- Registrar clientes
- Editar clientes
- Eliminar clientes
- Consultar clientes

----------------------------------------------------
8. PUNTO DE VENTA (POS)
----------------------------------------------------

El sistema incluye un módulo de ventas tipo POS.

Funciones:

- Agregar productos al carrito
- Validar disponibilidad de stock
- Modificar cantidades
- Eliminar productos del carrito
- Calcular el total de la venta
- Registrar ventas en base de datos

El carrito de compras se almacena mediante sesiones:

$_SESSION['carrito']

----------------------------------------------------
9. REPORTES
----------------------------------------------------

El sistema permite generar reportes para el control
del negocio.

Reportes disponibles:

- Reporte de inventario
- Reporte de ventas
- Exportación de datos a Excel

----------------------------------------------------
10. SEGURIDAD
----------------------------------------------------

El sistema implementa varias medidas básicas de
seguridad:

- Encriptación de contraseñas
- Validación de formularios
- Control de sesiones
- Validación de stock en ventas

----------------------------------------------------
11. INSTALACIÓN DEL SISTEMA
----------------------------------------------------

1. Descargar o clonar el repositorio

git clone https://github.com/HelmunthAvila/amatista_sgi

2. Copiar la carpeta dentro del servidor web

Ejemplo:

htdocs/

3. Crear la base de datos en phpMyAdmin

CREATE DATABASE amatista_sgi;

4. Importar el archivo SQL de la base de datos.

5. Configurar la conexión en el archivo:

conexion.php

Ejemplo:

$host = "localhost";
$user = "root";
$pass = "";
$db   = "amatista_sgi";

6. Ejecutar el sistema desde el navegador

http://localhost/amatista_sgi

----------------------------------------------------
12. USUARIO DE PRUEBA
----------------------------------------------------

Usuario: helmunth
Contraseña: 123456

----------------------------------------------------
13. POSIBLES MEJORAS
----------------------------------------------------

Algunas mejoras que se pueden implementar:

- Dashboard con estadísticas
- Gráficas de ventas
- Control de proveedores
- Facturación en PDF
- Control de caja diaria
- Alertas de stock bajo
- Historial de ventas por usuario

----------------------------------------------------
14. AUTOR
----------------------------------------------------

Helmuth Vladimir Avila Ordoñez

Programa:
Análisis y Desarrollo de Software (ADSO)

----------------------------------------------------
FIN DEL DOCUMENTO
----------------------------------------------------
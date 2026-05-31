========================================================================
 AMATISTA SGI - Sistema de Gestión de Inventario y Ventas
========================================================================

INFORMACIÓN GENERAL
------------------------------------------------------------------------
Autor:       Helmuth Vladimir Avila Ordoñez
Programa:    ADSO – Análisis y Desarrollo de Software (SENA)
Repositorio: https://github.com/HelmunthAvila/amatista_sgi
Contacto:    A través del repositorio oficial de GitHub

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

------------------------------------------------------------------------
2. ARQUITECTURA Y TECNOLOGÍAS UTILIZADAS
------------------------------------------------------------------------
El sistema está construido sobre tecnologías web abiertas:
- Frontend: HTML5, CSS3, JavaScript (ES6+), Bootstrap 5 y Bootstrap Icons.
- Backend:  PHP 7.4 o superior (Programación imperativa y segura).
- Base de Datos: MySQL / MariaDB (Motor relacional estructurado).
- Servidor recomendado: Apache (mediante suites como XAMPP o WAMP).

------------------------------------------------------------------------
3. ESTRUCTURA DEL PROYECTO (DIRECTORIOS PRINCIPALES)
------------------------------------------------------------------------
amatista_sgi/
│
├── conexion.php            # Conexión centralizada a la Base de Datos
├── login.php               # Pantalla de autenticación y acceso seguro
├── logout.php              # Cierre de sesión y destrucción de cookies
│
├── includes/
│   ├── header.php          # Barra de navegación, sidebar y estilos globales
│   └── footer.php          # Cierres de etiquetas HTML y scripts comunes
│
├── modulos/
│   ├── clientes/           # Gestión completa de Clientes (Directorio)
│   ├── proveedores/        # Gestión de Proveedores y Canales de Suministro
│   ├── productos/          # Catálogo e Inventario de productos (calzado)
│   ├── usuarios/           # Administración de Usuarios y Roles del sistema
│   ├── ventas/             # Historial técnico de transacciones realizadas
│   └── reportes/           # Exportación de datos y reportes comerciales
│
└── pos/
    ├── pos.php             # Interfaz de Punto de Venta para el cajero
    ├── agregar_carrito.php # Control lógico de adición mediante variables $_SESSION
    ├── eliminar_producto.php # Remoción de ítems en el carrito de compras
    └── finalizar_venta.php # Cierre de transacción y descuento automático de stock

------------------------------------------------------------------------
4. REQUISITOS TÉCNICOS ESENCIALES (INGENIERÍA)
------------------------------------------------------------------------
- Identidad Visual: Interfaz corporativa regida bajo la paleta Amatista SGI
  (--primary-color: #512da8).
- Experiencia de Usuario: Cierre automático de alertas informativas a los
  2 segundos exactos mediante JavaScript nativo.
- Seguridad Robustecida: Mitigación de ataques por inyección SQL mediante 
  sanitización estricta (mysqli_real_escape_string) y casteo de IDs (intval).
- Redirecciones Limpias: Transferencia de estados usando variables de sesión 
  ($_SESSION['alerta']) para evitar parámetros sucios en las URLs (?res=exito).

----------------------------------------------------
5. CARACTERÍSTICAS DEL SISTEMA
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
6. TECNOLOGÍAS UTILIZADAS
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
7. MÓDULO DE USUARIOS
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
8. MÓDULO DE PRODUCTOS
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
9. MÓDULO DE CLIENTES
----------------------------------------------------

Permite administrar la información de los clientes.

Funciones:

- Registrar clientes
- Editar clientes
- Eliminar clientes
- Consultar clientes

----------------------------------------------------
10. PUNTO DE VENTA (POS)
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
11. REPORTES
----------------------------------------------------

El sistema permite generar reportes para el control
del negocio.

Reportes disponibles:

- Reporte de inventario
- Reporte de ventas
- Exportación de datos a Excel

----------------------------------------------------
12. SEGURIDAD
----------------------------------------------------

El sistema implementa varias medidas básicas de
seguridad:

- Encriptación de contraseñas
- Validación de formularios
- Control de sesiones
- Validación de stock en ventas

----------------------------------------------------
13. INSTALACIÓN DEL SISTEMA
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
14. USUARIO DE PRUEBA
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
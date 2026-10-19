# 🦅 FALK - Plataforma de Aprendizaje Online

Plataforma web/móvil tipo LMS (*Learning Management System*) orientada a estudiantes, que permite la autenticación segura de usuarios, la gestión de cursos en progreso, la visualización e impresión de certificados, la consulta de un calendario académico con eventos por fecha y la administración integral de cuentas y auditoría de accesos.

> Trabajo práctico grupal – Materia: Programación de Sistemas – Universidad Nacional Raúl Scalabrini Ortiz (UNSO).

---

## 👥 Equipo

| Rol | Integrante |
|---|---|
| Project Manager | Alexander Soler |
| Frontend | Karina Cuadra |
| Frontend / Backend | Laura Villalba |
| Backend | Fabiola Villalba |
| Base de datos | Franco Lamarca |

---

## ⚙️ Funcionamiento del Sistema

El sistema está dividido en tres grandes módulos que integran los requisitos funcionales, no funcionales y las mejoras opcionales del proyecto:

### 1. Módulo de Autenticación y Seguridad
* **Registro de Usuarios (`pages-front/crear-cuenta.html` / `controllers/register-controller.php`):** Permite dar de alta nuevas cuentas validando que el nombre de usuario (`Username`) y el correo electrónico (`Email`) sean únicos. Incluye el campo opcional de **Fecha de nacimiento**.
* **Política de Contraseñas:** Exige un mínimo de 8 caracteres, incluyendo al menos una letra mayúscula, un número y un símbolo. Las contraseñas se almacenan cifradas mediante hash **bcrypt** (`password_hash` / `password_verify`), nunca en texto plano.
* **Inicio de Sesión (`index.html` / `controllers/login-controller.php`):** Permite ingresar con usuario o email. Implementa protección contra ataques de enumeración por tiempo (verificación con hash señuelo si el usuario no existe) y **bloqueo automático de cuenta** al alcanzar 5 intentos fallidos consecutivos.
* **Recuperación de Contraseña (`pages-front/recuperar-password.html`):** Flujo para restablecer el acceso en caso de olvido de credenciales.
* **Seguridad de Sesión y Transporte (`config/session.php` / `.htaccess`):**
  * Redirección automática a **HTTPS/TLS** mediante reglas en `.htaccess`.
  * Regeneración de ID de sesión tras el login, cookies con atributos `HttpOnly`, `SameSite=Strict` y `Secure`, y cierre automático por inactividad luego de **15 minutos**.
  * Protección contra ataques **CSRF** mediante tokens en formularios de modificación/eliminación y escape de salidas con `htmlspecialchars` para prevenir **XSS**.
  * Manejo de errores con bloques `try/catch (PDOException)` que muestran mensajes amigables al usuario (sin exponer datos sensibles, según recomendaciones OWASP) y registran el detalle técnico en el log del servidor mediante `error_log()`.

### 2. Plataforma de Aprendizaje (Producto LMS)
* **Home (`pages-back/home.php`):** Pantalla de bienvenida personalizada que presenta el resumen de los cursos en los que el usuario está inscripto (con botón desplegable accesible para mostrar/ocultar cursos adicionales) y acceso rápido al calendario.
* **Mis Cursos (`pages-back/mis-cursos.php`):** Listado completo y dinámico de los cursos del usuario logueado obtenidos desde la base de datos, indicando su estado (`En progreso` o `Finalizado`).
* **Reproductor de Clases (`pages-back/curso.php`):** Al seleccionar *"Continuar desde última lección"* en un curso en progreso, carga dinámicamente el módulo pendiente, el reproductor de video simulado y el material de lectura, validando que el curso pertenezca al usuario en sesión.
* **Certificados (`pages-back/certificado.php`):** Para los cursos con estado `Finalizado`, permite visualizar e imprimir (con diseño adaptado para impresión `@media print`) el certificado oficial con los datos del estudiante y la fecha de finalización, impidiendo el acceso a certificados de terceros.
* **Calendario Académico (`pages-back/calendario.php`) y Perfil (`pages-back/perfil.php`):** Consulta interactiva de eventos académicos por fecha (clases, entregas, exámenes) y visualización de los datos personales de la cuenta activa.

### 3. Panel de Administración y Auditoría (Solo Rol `admin`)
* **Listado de Accesos / Log de Auditoría (`pages-back/admin-logs.php`):** Muestra de forma paginada (20 registros por página) todos los intentos de inicio de sesión registrados en la tabla `audit_log` (fecha y hora, ID de usuario, identificador ingresado, resultado `Exitoso` / `Fallido` / `Cuenta bloqueada` / `Cuenta inactiva` y dirección IP). Es de solo lectura (*Append-Only*) e inaccesible para usuarios sin rol administrador.
* **Gestión de Usuarios (`pages-back/admin-usuarios.php`):**
  * **Activar, Desactivar y Desbloquear cuentas:** Permite cambiar el estado de cualquier usuario; al activar o desbloquear una cuenta, se reinicia automáticamente su contador de intentos fallidos a `0`.
  * **Eliminación de Usuarios:** Permite borrar cuentas previa confirmación en pantalla (bloqueando la autoeliminación del propio administrador). Por integridad referencial, al eliminar un usuario se borran sus cursos en cascada (`ON DELETE CASCADE`), pero se conserva su historial en el log de auditoría (`ON DELETE SET NULL`).
  * **Buscador por Email:** Permite filtrar cuentas por coincidencia parcial de correo electrónico (insensible a mayúsculas/minúsculas), conservando el filtro activo tras ejecutar acciones sobre la tabla.

---

## 📁 Estructura del Proyecto

```text
proyecto-falk/
├── config/                 # Conexión PDO (db.php), sesiones (session.php) y consultas (usuarios.php, cursos-mock.php)
├── controllers/            # Controladores de login, registro, cierre de sesión y eventos
├── js/                     # Scripts modulares de frontend (login, registro, menú, cursos, calendario, admin)
├── pages-back/             # Vistas PHP protegidas por sesión y rol (home, mis-cursos, curso, certificado, perfil, admin)
├── pages-front/            # Vistas públicas de creación de cuenta y recuperación de contraseña
├── pictures/               # Recursos gráficos e identidad visual (logo FALK)
├── sass/                   # Arquitectura de estilos SASS (base, components, layout, utilities, main.scss)
├── styles/                 # Hoja de estilos CSS compilada (styles.css)
├── .htaccess               # Configuración de Apache para forzar HTTPS/TLS
├── falk_db.sql             # Script completo de base de datos para instalación desde cero
├── migraciones.sql         # Script incremental (ALTER TABLE) para actualizar bases existentes sin perder datos
├── index.html              # Pantalla principal de inicio de sesión (Login)
└── README.md               # Documentación del sistema

```

---

## 🚀 Instrucciones para Ejecutar la Aplicación

### 1. Requisitos previos

* Entorno de servidor local **XAMPP** (con **Apache** y **MySQL / MariaDB** y **PHP 8.0 o superior**).
* Navegador web moderno (Chrome, Edge, Firefox).

### 2. Ubicación de los archivos

1. Descargar o clonar este repositorio.
2. Copiar la carpeta del proyecto dentro del directorio `htdocs` de XAMPP, de modo que quede en la ruta:
```text
C:\xampp\htdocs\proyecto-falk\

```



### 3. Iniciar los servicios

1. Abrir el **Panel de Control de XAMPP**.
2. Iniciar los módulos **Apache** y **MySQL** (ambos deben quedar en verde).

### 4. Creación e importación de la Base de Datos

1. Abrir el navegador e ingresar a **phpMyAdmin**: `http://localhost/phpmyadmin/`
2. Crear una nueva base de datos llamada exactamente **`falk_db`** (cotejamiento `utf8mb4_general_ci`).
3. Seleccionar la base **`falk_db`**, ir a la pestaña **Importar**, elegir el archivo **`falk_db.sql`** ubicado en la raíz del proyecto y hacer clic en **Continuar / Importar**.
* *Nota sobre actualizaciones:* Si ya se contaba con una versión previa de la base de datos y se desea actualizar su estructura sin perder los datos cargados, importar únicamente el archivo **`migraciones.sql`**.



### 5. Acceso al sistema

1. Abrir el navegador e ingresar a la URL local del proyecto:
```text
https://localhost/proyecto-falk/

```


*(Si se ingresa con `http://`, el archivo `.htaccess` redirigirá automáticamente a `https://`. En entorno local, si el navegador muestra la advertencia de certificado autofirmado de XAMPP, hacer clic en "Configuración avanzada" -> "Continuar a localhost").*

---

## 🔑 Usuarios de Prueba Precargados

El script `falk_db.sql` incluye cuentas iniciales para probar los distintos roles, estados y reglas de negocio del sistema:

### 1. Perfil Administrador

* **Usuario / Email:** `admin` o `admin@falk.com`
* **Contraseña:** `FalkAdmin2026!*`
* *Permisos / Datos:* Acceso al panel de administración, visualización del log de auditoría (`admin-logs.php`), gestión/búsqueda de usuarios (`admin-usuarios.php`) y visualización de 4 cursos inscriptos (3 en progreso y 1 finalizado con certificado habilitado).

*(También es posible registrar un nuevo usuario desde el botón **"Crear cuenta"** en la pantalla de inicio de sesión para verificar el flujo completo de alta, validación de contraseñas seguras y campos opcionales).*

```

```

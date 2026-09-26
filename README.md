# 🦅 FALK - Plataforma de Aprendizaje Online

Plataforma web/móvil tipo LMS (Learning Management System) orientada a estudiantes, que permite la autenticación de usuarios, la gestión de cursos en progreso, la visualización de certificados y la consulta de un calendario académico con eventos por fecha.

> Trabajo práctico grupal – Universidad Nacional Raúl Scalabrini Ortiz (UNSO). Entrega final: **20 de octubre de 2026**.

---

## 📋 Índice
1. [Descripción del Proyecto](#-1-descripción-del-proyecto)
2. [Características Principales](#-2-características-principales)
3. [Tecnologías Utilizadas](#️-3-tecnologías-utilizadas)
4. [Estructura del Proyecto](#-4-estructura-del-proyecto)
5. [Instalación y Configuración Local](#️-5-instalación-y-configuración-local)
6. [Roles y Permisos](#-6-roles-y-permisos)
7. [Estado del Desarrollo](#-7-estado-del-desarrollo)
8. [Forma de Trabajo](#-8-forma-de-trabajo)
9. [Equipo de Desarrollo](#-9-equipo-de-desarrollo)

---

## 🚀 1. Descripción del Proyecto
FALK surge como respuesta a la necesidad de contar con un entorno digital intuitivo, enfocado en la experiencia mobile-first. Permite a los usuarios registrarse de forma segura, iniciar sesión, acceder a sus cursos inscriptos y gestionar sus fechas académicas a través de un calendario interactivo.

El objetivo principal es simular un sistema de autenticación de usuarios (login) con perfiles de **usuario** y **administrador**, y un registro de auditoría de los accesos.

---

## ✨ 2. Características Principales
* **Sistema de Autenticación:** registro de nuevos usuarios, inicio de sesión validado y recuperación de contraseña.
* **Validaciones de Seguridad:** ID y correo electrónico únicos; contraseña de mínimo 8 caracteres con al menos una mayúscula, un número y un símbolo.
* **Control de Perfiles:** distinción entre los roles de Usuario/Estudiante y Administrador.
* **Mis Cursos:** listado de cursos inscriptos, opción para continuar lecciones en progreso y visualización de certificados al completar un curso.
* **Calendario Académico:** vista mensual con indicador visual de eventos y detalle por fecha seleccionada (clases, entregas, exámenes).
* **Auditoría:** log de accesos (exitosos y fallidos), exclusivo para el perfil de administrador.

---

## 🛠️ 3. Tecnologías Utilizadas
* **Frontend:** HTML5, CSS3 y JavaScript. Tipografía Poppins (Google Fonts).
* **Backend:** a definir.
* **Base de Datos:** a definir.
* **Control de Versiones:** Git y GitHub.
* **Gestión del proyecto:** Trello.

---

## 📂 4. Estructura del Proyecto

```
proyecto-falk/
│
├── index.html            # Pantalla de Login
├── styles/
│   └── styles.css        # Estilos generales y paleta de colores
├── pictures/
│   └── logo-falk.png     # Logo de FALK
├── js/                   # Lógica del front (pendiente)
├── database/             # Script de creación de la base de datos (pendiente)
├── docs/                 # Documentación: SRS, mockups y flujo de usuario (pendiente)
└── README.md             # Documentación principal del proyecto
```

### Paleta de colores
Los colores están definidos como variables en `styles/styles.css` (`:root`) para usarlos en todas las pantallas:

| Variable | Color | Uso |
|---|---|---|
| `--color-texto` | `#17517D` | Títulos, textos y texto de los campos |
| `--color-campo` | `#48AF69` | Fondo de los campos |
| `--color-boton` | `#368F8A` | Botón principal |
| `--color-link` | `#368F8A` | Links |
| `--color-fondo` | `#C2D9D1` | Fondo de la página |
| `--color-encabezado` | `#FFFFFF` | Encabezado con el logo |
| `--color-texto-boton` | `#FFFFFF` | Texto del botón |
| `--color-error` | `#8C1D18` | Mensajes de error |

---

## ⚙️ 5. Instalación y Configuración Local

1. Clonar el repositorio:
```bash
   git clone https://github.com/karinacuadracode/proyecto-falk.git
```
2. Entrar a la carpeta del proyecto:
```bash
   cd proyecto-falk
```
3. Abrir `index.html` en el navegador (doble clic) o con la extensión **Live Server** de VS Code.

> Las instrucciones para levantar el backend y la base de datos se agregarán cuando estén definidos.

---

## 👥 6. Roles y Permisos

| Perfil | Permisos |
|---|---|
| **Usuario / Estudiante** | Iniciar sesión, ver Mis cursos, continuar lecciones, ver certificados y consultar el calendario. |
| **Administrador** | Todo lo anterior, más el acceso al listado de accesos (log de auditoría). |

---

## 📌 7. Estado del Desarrollo

| Requisito | Descripción | Estado |
|---|---|---|
| RF-01 | Inicio de sesión (pantalla de Login) | ✅ Front listo – en revisión |
| RF-02 | Validación de credenciales | ⏳ Pendiente (back) |
| RF-03 | Recuperación de contraseña | ⏳ Pendiente |
| RF-04 | Redirección a Home | ⏳ Pendiente (depende de RF-02) |
| RF-05 a RF-10 | Registro, validaciones y persistencia de usuarios | ⏳ Pendiente |
| RF-11 a RF-22 | Home, Mis cursos y Calendario | ⏳ Pendiente |
| RF-23 y RF-24 | Administrador y log de auditoría | ⏳ Pendiente |

---

## 🔀 8. Forma de Trabajo
* Cada integrante trabaja en una **rama propia** (por ejemplo, `feature/login`).
* Los cambios se suben mediante un **Pull Request** y otro integrante lo revisa antes de unirlo a `main`.
* El avance de cada tarea se registra en el tablero de **Trello**.

---

## 🧑‍💻 9. Equipo de Desarrollo

| Rol | Integrante |
|---|---|
| Project Manager | Alexander Soler |
| Frontend | Karina Cuadra |
| Frontend / Backend (según necesidad) | Laura Villalba |
| Backend | Fabiola Villalba |
| Base de Datos | Franco Lamarca |

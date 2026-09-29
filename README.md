# 🦅 FALK - Plataforma de Aprendizaje Online

Plataforma web/móvil tipo LMS (Learning Management System) orientada a estudiantes, que permite la autenticación de usuarios, la gestión de cursos en progreso, la visualización de certificados y la consulta de un calendario académico con eventos por fecha.

> Trabajo práctico grupal – Universidad Nacional Raúl Scalabrini Ortiz (UNSO).

---

## 📋 Índice
1. [Descripción del Proyecto](#-1-descripción-del-proyecto)
2. [Características Principales](#-2-características-principales)
3. [Tecnologías Utilizadas](#️-3-tecnologías-utilizadas)
4. [Estructura del Proyecto](#-4-estructura-del-proyecto)
5. [Roles y Permisos](#-6-roles-y-permisos)
6. [Equipo de Desarrollo](#-8-equipo-de-desarrollo)

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

---

## 👥 5. Roles y Permisos

| Perfil | Permisos |
|---|---|
| **Usuario / Estudiante** | Iniciar sesión, ver Mis cursos, continuar lecciones, ver certificados y consultar el calendario. |
| **Administrador** | Todo lo anterior, más el acceso al listado de accesos (log de auditoría). |

---

## 🧑‍💻 6. Equipo de Desarrollo

| Rol | Integrante |
|---|---|
| Project Manager | Alexander Soler |
| Frontend | Karina Cuadra |
| Frontend / Backend (según necesidad) | Laura Villalba |
| Backend | Fabiola Villalba |
| Base de Datos | Franco Lamarca |

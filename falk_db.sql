-- Script de Base de Datos Proyecto FALK

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `audit_log`
CREATE TABLE `audit_log` (
  `Log_ID` int(11) NOT NULL,
  `User_Email` varchar(255) NOT NULL,
  `Attempt_Time` datetime DEFAULT current_timestamp(),
  `Result` varchar(50) NOT NULL,
  `IP_Address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- (La tabla audit_log se inicializa vacía para un entorno limpio)

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `users`
CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `Username` varchar(30) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` enum('usuario','admin') NOT NULL DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcado de datos para la tabla `users`
INSERT INTO `users` (`ID`, `Username`, `Email`, `Password`, `Role`) VALUES
(1, 'admin', 'admin@falk.com', '$2y$10$r00h6XLxXZFH.mwherkTxObYc1PYCsVDCD2/o8c/kxids3hiFvHZG', 'admin'),
(2, 'Elpepe', 'elpepe@falk.com', '$2y$10$mij3DfYD7Ab7K9.N5PIp6ewjplS.DFQelvfmdel9NelDeiMWFO/ma', 'usuario');

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `cursos`
CREATE TABLE `cursos` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `estado` enum('en_progreso','finalizado') DEFAULT 'en_progreso',
  `fecha_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcado de datos para la tabla `cursos`
INSERT INTO `cursos` (`id`, `user_id`, `nombre`, `estado`, `fecha_fin`) VALUES
(1, 1, 'Programación inicial', 'en_progreso', NULL),
(2, 1, 'Diseño web responsive', 'finalizado', '2026-10-05'),
(3, 1, 'Base de Datos', 'en_progreso', NULL),
(4, 1, 'Programación Web', 'en_progreso', NULL);

-- --------------------------------------------------------

-- Índices para tablas volcadas

ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`Log_ID`);

ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Username` (`Username`);

-- --------------------------------------------------------

-- AUTO_INCREMENT de las tablas volcadas

ALTER TABLE `audit_log`
  MODIFY `Log_ID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

-- --------------------------------------------------------

-- Restricciones para tablas volcadas

ALTER TABLE `cursos`
  ADD CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE;

COMMIT;
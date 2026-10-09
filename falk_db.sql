-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-10-2026 a las 09:08:54
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `falk_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `audit_log`
--

CREATE TABLE `audit_log` (
  `Log_ID` int(11) NOT NULL,
  `User_Email` varchar(255) NOT NULL,
  `Attempt_Time` datetime DEFAULT current_timestamp(),
  `Result` varchar(50) NOT NULL,
  `IP_Address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `audit_log`
--

INSERT INTO `audit_log` (`Log_ID`, `User_Email`, `Attempt_Time`, `Result`, `IP_Address`) VALUES
(1, 'admin@falk.com', '2026-10-08 01:20:15', 'FALLIDO', '::1'),
(2, 'admin@falk.com', '2026-10-08 01:20:33', 'EXITOSO', '::1'),
(3, 'admin@falk.com', '2026-10-08 02:46:25', 'EXITOSO', '::1'),
(4, 'admin@falk.com', '2026-10-08 03:04:33', 'EXITOSO', '::1'),
(5, 'admin@falk.com', '2026-10-08 03:17:25', 'EXITOSO', '::1'),
(6, 'admin@falk.com', '2026-10-08 03:24:50', 'EXITOSO', '::1'),
(7, 'admin@falk.com', '2026-10-08 16:54:46', 'EXITOSO', '::1'),
(8, 'admin@falk.com', '2026-10-08 19:47:41', 'EXITOSO', '::1'),
(9, 'Elpepe', '2026-10-08 19:56:17', 'EXITOSO', '::1'),
(10, 'admin@falk.com', '2026-10-08 20:33:20', 'EXITOSO', '::1'),
(11, 'admin@falk.com', '2026-10-08 20:57:03', 'EXITOSO', '::1'),
(12, 'admin@falk.com', '2026-10-09 01:02:39', 'EXITOSO', '::1'),
(13, 'admin@falk.com', '2026-10-09 01:39:49', 'EXITOSO', '::1'),
(14, 'admin@falk.com', '2026-10-09 01:56:58', 'FALLIDO', '::1'),
(15, 'admin@falk.com', '2026-10-09 01:57:14', 'EXITOSO', '::1'),
(16, 'admin@falk.com', '2026-10-09 02:52:51', 'EXITOSO', '::1'),
(17, 'Elpepe', '2026-10-09 04:00:39', 'EXITOSO', '::1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `estado` enum('en_progreso','finalizado') DEFAULT 'en_progreso',
  `fecha_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id`, `user_id`, `nombre`, `estado`, `fecha_fin`) VALUES
(1, 1, 'Programación inicial', 'en_progreso', NULL),
(2, 1, 'Diseño web responsive', 'finalizado', '2026-10-05'),
(3, 1, 'Base de Datos', 'en_progreso', NULL),
(4, 1, 'Programación Web', 'en_progreso', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `Username` varchar(30) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` enum('usuario','admin') NOT NULL DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`ID`, `Username`, `Email`, `Password`, `Role`) VALUES
(1, 'admin', 'admin@falk.com', '$2y$10$r00h6XLxXZFH.mwherkTxObYc1PYCsVDCD2/o8c/kxids3hiFvHZG', 'admin'),
(2, 'Elpepe', 'elpepe@falk.com', '$2y$10$mij3DfYD7Ab7K9.N5PIp6ewjplS.DFQelvfmdel9NelDeiMWFO/ma', 'usuario');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`Log_ID`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `Log_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

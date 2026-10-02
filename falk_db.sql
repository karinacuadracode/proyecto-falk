-- Script de Base de Datos Proyecto FALK

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

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
-- Índices para tablas volcadas
--

ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`Log_ID`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

ALTER TABLE `audit_log`
  MODIFY `Log_ID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Volcado de datos iniciales para la tabla `users` (Admin por defecto)
--
INSERT INTO `users` (`Username`, `Email`, `Password`, `Role`) VALUES
('admin', 'admin@falk.com', '$2y$10$r00h6XLxXZFH.mwherkTxObYc1PYCsVDCD2/o8c/kxids3hiFvHZG', 'admin');

COMMIT;

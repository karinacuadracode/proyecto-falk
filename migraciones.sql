-- Cambios de estructura para una base falk_db que ya existe.
-- Si importás falk_db.sql desde cero no hace falta correr este archivo.
-- Se puede correr más de una vez: lo que ya existe se saltea.
-- Si cambiás la base, agregá tu ALTER al final de este archivo y también en falk_db.sql.

USE `falk_db`;

-- RF-25: estado de la cuenta e intentos fallidos
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `Estado` enum('activo','bloqueado','inactivo') NOT NULL DEFAULT 'activo',
  ADD COLUMN IF NOT EXISTS `Intentos_Fallidos` tinyint(3) UNSIGNED NOT NULL DEFAULT 0;

-- RF-23 / RF-24: ID de usuario en cada intento e índices del listado de accesos
ALTER TABLE `audit_log`
  ADD COLUMN IF NOT EXISTS `User_ID` int(11) DEFAULT NULL AFTER `Log_ID`,
  ADD KEY IF NOT EXISTS `Attempt_Time` (`Attempt_Time`),
  ADD KEY IF NOT EXISTS `User_ID` (`User_ID`);

ALTER TABLE `audit_log`
  ADD CONSTRAINT `audit_log_ibfk_1` FOREIGN KEY IF NOT EXISTS (`User_ID`) REFERENCES `users` (`ID`) ON DELETE SET NULL;

-- RF-26: fecha de nacimiento opcional
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `Fecha_Nacimiento` date DEFAULT NULL;

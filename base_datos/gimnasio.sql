-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-04-2025 a las 23:17:36
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
-- Base de datos: `gimnasio`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fechas`
--

CREATE TABLE `fechas` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `disponible` tinyint(1) NOT NULL DEFAULT 1,
  `max_personas` int(11) NOT NULL DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `fechas`
--

INSERT INTO `fechas` (`id`, `fecha`, `disponible`, `max_personas`) VALUES
(1, '2025-03-19', 1, 5),
(2, '2025-03-20', 1, 3),
(3, '2025-03-21', 0, 0),
(4, '2025-04-25', 1, 20),
(5, '2025-04-18', 1, 20),
(6, '2025-04-17', 1, 20),
(7, '2025-04-30', 1, 20),
(8, '2025-04-23', 1, 20),
(9, '2025-04-10', 1, 20),
(10, '2025-04-16', 1, 20),
(11, '2025-04-24', 1, 20),
(12, '2025-04-02', 1, 20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `id` int(11) NOT NULL,
  `id_fecha` int(11) NOT NULL,
  `hora` time NOT NULL,
  `ocupado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `horarios`
--

INSERT INTO `horarios` (`id`, `id_fecha`, `hora`, `ocupado`) VALUES
(1, 1, '08:00:00', 0),
(2, 1, '09:00:00', 0),
(3, 1, '10:00:00', 0),
(4, 2, '08:00:00', 1),
(5, 2, '09:00:00', 0),
(6, 3, '10:00:00', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `nombre_real` varchar(100) NOT NULL,
  `es_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_usuario`, `contrasena`, `nombre_real`, `es_admin`) VALUES
(1, 'user1', 'password123', 'Juan Perez', 0),
(2, 'admin1', 'adminpass', 'Carlos Gomez', 1),
(3, 'effy12', '$2y$10$Zl1qBR8viEi4509CgRiAmOhYdRR40MoWF7NR3Rwpo/SORcszG2MkC', 'Effy Azaria Cappuccino', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_horarios`
--

CREATE TABLE `usuario_horarios` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_horario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario_horarios`
--

INSERT INTO `usuario_horarios` (`id`, `id_usuario`, `id_horario`) VALUES
(1, 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `fechas`
--
ALTER TABLE `fechas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_fecha` (`id_fecha`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario_horarios`
--
ALTER TABLE `usuario_horarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`,`id_horario`),
  ADD KEY `id_horario` (`id_horario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `fechas`
--
ALTER TABLE `fechas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario_horarios`
--
ALTER TABLE `usuario_horarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD CONSTRAINT `horarios_ibfk_1` FOREIGN KEY (`id_fecha`) REFERENCES `fechas` (`id`);

--
-- Filtros para la tabla `usuario_horarios`
--
ALTER TABLE `usuario_horarios`
  ADD CONSTRAINT `usuario_horarios_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `usuario_horarios_ibfk_2` FOREIGN KEY (`id_horario`) REFERENCES `horarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

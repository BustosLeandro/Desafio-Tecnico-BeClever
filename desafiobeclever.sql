-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-04-2023 a las 06:40:03
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `desafiobeclever`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `Codigo` int(11) NOT NULL,
  `Nombre` varchar(12) NOT NULL,
  `Apellido` varchar(15) NOT NULL,
  `Sexo` tinyint(4) NOT NULL,
  `CodigoSucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`Codigo`, `Nombre`, `Apellido`, `Sexo`, `CodigoSucursal`) VALUES
(1, 'Leandro', 'Bustos', 1, 1),
(2, 'Juan', 'Perez', 1, 1),
(3, 'Laura', 'Gallego', 2, 3),
(4, 'Luis', 'Adriano', 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros`
--

CREATE TABLE `registros` (
  `Codigo` int(11) NOT NULL,
  `Fecha` date NOT NULL DEFAULT current_timestamp(),
  `CodigoTipoRegistro` int(11) NOT NULL,
  `CodigoEmpleado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registros`
--

INSERT INTO `registros` (`Codigo`, `Fecha`, `CodigoTipoRegistro`, `CodigoEmpleado`) VALUES
(1, '2023-04-14', 1, 1),
(2, '2023-04-19', 1, 2),
(3, '2023-04-07', 2, 3),
(4, '2023-04-16', 2, 1),
(5, '2023-04-30', 1, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursales`
--

CREATE TABLE `sucursales` (
  `Codigo` int(11) NOT NULL,
  `Sucursal` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sucursales`
--

INSERT INTO `sucursales` (`Codigo`, `Sucursal`) VALUES
(1, 'Argentina'),
(2, 'Brasil'),
(3, 'España');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiporegistros`
--

CREATE TABLE `tiporegistros` (
  `Codigo` int(11) NOT NULL,
  `TipoRegistro` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tiporegistros`
--

INSERT INTO `tiporegistros` (`Codigo`, `TipoRegistro`) VALUES
(1, 'Ingreso'),
(2, 'Egreso');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `CodigoSucursal` (`CodigoSucursal`);

--
-- Indices de la tabla `registros`
--
ALTER TABLE `registros`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `CodigoEmpleado` (`CodigoEmpleado`),
  ADD KEY `CodigoTipoRegistro` (`CodigoTipoRegistro`);

--
-- Indices de la tabla `sucursales`
--
ALTER TABLE `sucursales`
  ADD PRIMARY KEY (`Codigo`);

--
-- Indices de la tabla `tiporegistros`
--
ALTER TABLE `tiporegistros`
  ADD PRIMARY KEY (`Codigo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `registros`
--
ALTER TABLE `registros`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `sucursales`
--
ALTER TABLE `sucursales`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tiporegistros`
--
ALTER TABLE `tiporegistros`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD CONSTRAINT `empleados_ibfk_1` FOREIGN KEY (`CodigoSucursal`) REFERENCES `sucursales` (`Codigo`);

--
-- Filtros para la tabla `registros`
--
ALTER TABLE `registros`
  ADD CONSTRAINT `registros_ibfk_1` FOREIGN KEY (`CodigoEmpleado`) REFERENCES `empleados` (`Codigo`),
  ADD CONSTRAINT `registros_ibfk_2` FOREIGN KEY (`CodigoTipoRegistro`) REFERENCES `tiporegistros` (`Codigo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

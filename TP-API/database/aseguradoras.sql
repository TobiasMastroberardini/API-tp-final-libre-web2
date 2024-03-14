-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-03-2024 a las 23:29:53
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `aseguradoras`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestadores`
--

CREATE TABLE `prestadores` (
  `prestadorId` int(11) NOT NULL,
  `fechaCreacion` date NOT NULL,
  `origen` varchar(45) NOT NULL,
  `nombrePrestador` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestadores`
--

INSERT INTO `prestadores` (`prestadorId`, `fechaCreacion`, `origen`, `nombrePrestador`) VALUES
(1, '2003-03-11', 'Argentina', 'Rivadavia'),
(2, '1952-12-30', 'EEUU', 'JPmorgan'),
(3, '1975-01-20', 'Brasil', 'BRSeguros');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reviews`
--

CREATE TABLE `reviews` (
  `reviewId` int(11) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `puntuacion` int(11) NOT NULL,
  `usuario` varchar(200) NOT NULL,
  `seguroId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reviews`
--

INSERT INTO `reviews` (`reviewId`, `descripcion`, `puntuacion`, `usuario`, `seguroId`) VALUES
(1, 'Buena atencion al cliente y buen precio.', 7, 'webadmin', 1),
(2, 'Buena experiencia cobrando seguro', 8, 'webadmin', 2),
(3, 'Tardaron mucho enn pagar mi seguro', 4, 'webadmin', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguros`
--

CREATE TABLE `seguros` (
  `seguroId` int(11) NOT NULL,
  `nombreSeguro` varchar(45) NOT NULL,
  `fechaLanzamiento` date NOT NULL,
  `prestadorId` int(11) NOT NULL,
  `descripcionSeguro` text NOT NULL,
  `coberturaMaxima` int(11) NOT NULL,
  `imagen` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `seguros`
--

INSERT INTO `seguros` (`seguroId`, `nombreSeguro`, `fechaLanzamiento`, `prestadorId`, `descripcionSeguro`, `coberturaMaxima`, `imagen`) VALUES
(1, 'Rivadavia500', '2019-10-24', 1, 'Seguro con cobertura contra 3ros y granizo.', 80000000, 'https://images.app.goo.gl/VGVeSr2wno9K7h2d9'),
(2, 'JPMorgan1500', '2021-11-26', 2, 'Seguro con cobertura contra todo riesgo', 25, 'https://images.app.goo.gl/rgkWBRgoiZtdtcfTA'),
(3, 'BR300', '2003-10-30', 3, 'Posee una Cobertura contra 3ros, robo e incendio', 30000000, 'https://images.app.goo.gl/BawJw94tJbw31xwY7');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario` varchar(200) NOT NULL,
  `contrasenia` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usuario`, `contrasenia`) VALUES
('webadmin', '$2y$10$5iX0BZS3E2qRR090rtKUqOfAc0XDL6XFpVFBpWODBWxIsw/t65DRq');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `prestadores`
--
ALTER TABLE `prestadores`
  ADD PRIMARY KEY (`prestadorId`);

--
-- Indices de la tabla `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`reviewId`),
  ADD KEY `seguroId` (`seguroId`),
  ADD KEY `usuario` (`usuario`) USING BTREE;

--
-- Indices de la tabla `seguros`
--
ALTER TABLE `seguros`
  ADD PRIMARY KEY (`seguroId`),
  ADD KEY `prestador` (`prestadorId`) USING BTREE;

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `reviews`
--
ALTER TABLE `reviews`
  MODIFY `reviewId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`usuario`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`seguroId`) REFERENCES `seguros` (`seguroId`);

--
-- Filtros para la tabla `seguros`
--
ALTER TABLE `seguros`
  ADD CONSTRAINT `seguros` FOREIGN KEY (`prestadorId`) REFERENCES `prestadores` (`prestadorId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

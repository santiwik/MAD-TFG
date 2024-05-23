-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-05-2024 a las 16:06:06
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
-- Base de datos: `tfg_mad`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `descripcion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id`, `name`, `descripcion`) VALUES
(1, 'Camisetas', 'Categoria de camisetas'),
(2, 'Pantalones', 'Categoria de Pantalones'),
(4, 'Sudaderas', 'Categoria de las sudaderas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `user_id`, `total_price`, `date`) VALUES
(1, 3, 25.99, '2024-05-21 20:18:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_producto`
--

CREATE TABLE `pedido_producto` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `precio` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido_producto`
--

INSERT INTO `pedido_producto` (`id`, `pedido_id`, `producto_id`, `quantity`, `precio`) VALUES
(1, 1, 2, 1, 25.99);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(5,2) NOT NULL,
  `category` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `name`, `descripcion`, `precio`, `category`) VALUES
(2, 'Pantalones jogger de VALORANT \"Desaf?a los l?mites\"', 'Superaos a vosotros mismos. Estos pantalones jogger pr?mium os sacar?n de cualquier aprieto.\r\n\r\nCaracter?sticas:\r\n\r\nCintura el?stica\r\nDos bolsillos laterales con cremallera\r\nUn bolsillo grande lateral', 67.50, 2),
(3, 'Camiseta', 'Efectivamente es una camiseta', 99.99, 1),
(4, 'Pantalones deportivos shatterprint de VALORANT', 'Pantalones deportivos tan c?modos que es posible que sean de otro mundo.\r\n\r\nCaracter?sticas:\r\n\r\nDoble capa\r\nCintura el?stica\r\nBolsillo lateral shatterprint\r\nLogo reflectante de VALORANT', 79.50, 2),
(5, 'Dim Mak x Teamfight Tactics - Chaqueta deportiva de sat?n de B Llota', 'Llevad a otro nivel vuestra ropa festivalera con la nueva colecci?n de Remix r?nico de Dim Mak x Teamfight Tactics.\r\n\r\nCon el logo de Dim Mak x Teamfight Tactics bordado en la parte superior izquierda del pecho, una imagen de Remix r?nico bordada en negro sobre negro en la espalda y el parche de \"Bangers Only\" de Teamfight Tactics en la manga izquierda, esta estilosa bomber negra os ayudar? a conservar el calor y derrochar clase sobre el escenario y en partida.\r\n\r\nHecha en un 100 % de poli?ster. Limpiar solo las manchas. No lavar en seco. Importado.', 194.99, 4),
(6, 'Camiseta tradicional de Ahri florecer espiritual', 'Llevad el estilo y la elegancia del festival del Florecer espiritual a vuestro armario con la camiseta tradicional de Ahri florecer espiritual.\r\n\r\nInspirada en la ropa de trabajo y las chaquetas tradicionales del festival, esta camiseta incluye ilustraciones de Ahri florecer espiritual en su ic?nica forma de raposa sobre las colinas jonias, con la paleta de colores del Florecer espiritual.\r\n\r\nAl ser holgada, nuestra camiseta tradicional de Ahri florecer espiritual es perfecta para quedarse en casa, salir con los amigos o lo que se os apetezca.', 98.99, 4),
(7, 'Pantalones jogger del Mundial 2023', 'THE GRIND THE GLORY\r\n\r\nAqu? llega el Mundial. Donde todo lo que tanto os gusta de League of Legends llega al cl?max. Para 22 equipos y 12 regiones, estas pr?ximas semanas en Corea del Sur pondr?n a prueba su dominio en el juego.\r\n\r\nDisfrutad de parte de la gloria que les espera al final del campeonato con unos pantalones jogger del Mundial 2023.\r\n\r\nCaracter?sticas:\r\n\r\n100 % algod?n de gramaje medio\r\nBolsillos delanteros y cintura el?stica\r\nDecoloraci?n moderna tipo cristal\r\nEmblemas del Mundial serigrafiados\r\nHerretes con recubrimiento\r\nEl modelo mide 1,82 m y lleva una talla L. La modelo mide 1,65 m y lleva una talla M.', 67.99, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `Update` tinyint(1) NOT NULL,
  `Insert` tinyint(1) NOT NULL,
  `Delete` tinyint(1) NOT NULL,
  `Cat` tinyint(1) NOT NULL,
  `Prod` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `Update`, `Insert`, `Delete`, `Cat`, `Prod`) VALUES
(1, 'Usuario normal', 0, 0, 0, 0, 0),
(2, 'Admin', 1, 1, 1, 1, 1),
(3, 'Admin cat', 1, 1, 1, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `user` varchar(254) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  `surname` varchar(20) DEFAULT NULL,
  `email` varchar(30) NOT NULL,
  `pwd` varchar(254) DEFAULT NULL,
  `rol` int(11) NOT NULL DEFAULT 1,
  `whencreated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `direction` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `user`, `name`, `surname`, `email`, `pwd`, `rol`, `whencreated`, `direction`) VALUES
(2, 'santiwik ___', NULL, NULL, 'danielsantiso04@gmail.com', NULL, 2, '2024-05-21 21:22:45', ''),
(3, 'dani', NULL, NULL, 'dani@gmail.com', '$2y$10$Pq5jKF0Pm2BtPgwBrQg5aOtlKylgiDLcLX1DFfHza0/tm6DGcbsIe', 2, '2024-05-21 20:28:15', 'dad'),
(4, 'JFELGALAN', NULL, NULL, 'Ppepe@gmail.com', '$2y$10$RpP8VAB1ljrNiCPWlSedKeZ91x7XIOAVfT5W8KcioQ5SEgGXkLMqO', 2, '2024-05-23 13:50:52', ''),
(5, 'Daniel Santiso Cande', NULL, NULL, 'dsantiso@eulen.com', NULL, 1, '2024-05-22 09:33:01', ''),
(21, 'Daniel Santiso', 'Miau', 'WQWD', 'danielsantisoifp@gmail.com', NULL, 1, '2024-05-22 10:53:16', 'ADASD'),
(22, 'Daniel Santiso Candel', NULL, NULL, 'dsantiso@eulen.com', NULL, 1, '2024-05-22 11:18:50', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `pedido_producto`
--
ALTER TABLE `pedido_producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user` (`user`),
  ADD KEY `rol` (`rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pedido_producto`
--
ALTER TABLE `pedido_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `pedido_producto`
--
ALTER TABLE `pedido_producto`
  ADD CONSTRAINT `pedido_producto_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `pedido_producto_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`category`) REFERENCES `categoria` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

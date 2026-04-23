-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-04-2026 a las 02:12:57
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `creative_spot_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `almacenes`
--

CREATE TABLE `almacenes` (
  `idAlmacen` int(11) NOT NULL,
  `hashAlmacen` varchar(64) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `ubicacion` varchar(150) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `almacenes`
--

INSERT INTO `almacenes` (`idAlmacen`, `hashAlmacen`, `nombre`, `ubicacion`, `estado`, `deleted_at`) VALUES
(1, '0746bbefba5cd82b1e287f58f636bc54c2a2154ec6de0e910089ba60074a2f2c', 'Almacén Norte', 'Sección Distribución', 1, NULL),
(2, 'f605925414e261d7628e1fe22b57c21fc34f12273cacca8d12517ce4026c72d5', 'Almacén Sur', 'Sección Recepción', 1, NULL),
(3, '2a8b31fe8e99837eba32e65dee1a79afba4c3fea333ea24ae102a0f4f842e451', 'Almacén Tránsito', 'Mercancía en movimiento', 1, NULL),
(4, '2f38f65fe9d9a174558e3695ae28601778b670134cbd0dba4404c70e92761fce', 'Almacén PT', 'Producto Terminado', 1, NULL),
(5, '9866daef02ab8f82d3ef72d162f103117f3a8544f535ba57c13f6eae8ea85edf', 'Almacén Merma', 'Material Dañado/Desechos', 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `idCompra` int(11) NOT NULL,
  `hashCompra` varchar(64) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `idAlmacen` int(11) NOT NULL,
  `lote` varchar(50) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `precio_unitario_compra` decimal(10,2) NOT NULL,
  `fecha_compra` date NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`idCompra`, `hashCompra`, `idProducto`, `idAlmacen`, `lote`, `cantidad`, `precio_unitario_compra`, `fecha_compra`, `deleted_at`) VALUES
(1, '356a192b7913b04c54574d18c28d46e6395428ab', 1, 1, 'LOT-20260408-99E', 1000.00, 0.40, '2026-04-08', NULL),
(2, 'da4b9237bacccdf19c0760cab7aec4a8359010b0', 5, 1, 'LOT-20260408-679', 20.00, 10.00, '2026-04-08', NULL),
(3, '77de68daecd823babbb58edb1c8e14d7106e83bb', 5, 2, 'LOT-20260408-679', 10.00, 11.00, '2026-04-08', NULL),
(4, '1b6453892473a467d07372d45eb05abc2031647a', 3, 1, 'LOT-20260408-C59', 100.00, 0.50, '2026-04-08', NULL),
(5, 'ac3478d69a3c81fa62e60f5c3696165a4e5e6ac4', 3, 2, 'LOT-20260408-AD6', 100.00, 0.55, '2026-04-08', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventarios`
--

CREATE TABLE `inventarios` (
  `idProducto` int(11) NOT NULL,
  `idAlmacen` int(11) NOT NULL,
  `stock_actual` decimal(10,2) DEFAULT 0.00,
  `punto_critico` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventarios`
--

INSERT INTO `inventarios` (`idProducto`, `idAlmacen`, `stock_actual`, `punto_critico`) VALUES
(1, 1, 550.00, 5.00),
(1, 2, 670.00, 5.00),
(1, 3, 0.00, 5.00),
(1, 4, 0.00, 5.00),
(1, 5, 0.00, 5.00),
(2, 1, 15.00, 5.00),
(2, 2, 0.00, 5.00),
(2, 3, 0.00, 5.00),
(2, 4, 0.00, 5.00),
(2, 5, 0.00, 5.00),
(3, 1, 85.00, 5.00),
(3, 2, 89.00, 5.00),
(3, 3, 0.00, 5.00),
(3, 4, 0.00, 5.00),
(3, 5, 0.00, 5.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos`
--

CREATE TABLE `movimientos` (
  `idMovimiento` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `idAlmacen` int(11) NOT NULL,
  `tipo` enum('Entrada','Salida','Ajuste') NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `movimientos`
--

INSERT INTO `movimientos` (`idMovimiento`, `idProducto`, `idAlmacen`, `tipo`, `cantidad`, `motivo`, `fecha`) VALUES
(1, 1, 2, 'Entrada', 1000.00, 'Compra Lote: LOT-20260407-BFA', '2026-04-07 22:52:26'),
(2, 2, 1, 'Entrada', 15.00, 'Compra Lote: LOT-20260407-3A4', '2026-04-08 01:30:28'),
(3, 1, 1, 'Entrada', 1000.00, 'Compra Lote: LOT-20260408-99E', '2026-04-08 04:43:08'),
(4, 5, 1, 'Entrada', 20.00, 'Compra Lote: LOT-20260408-679', '2026-04-08 07:12:34'),
(5, 5, 2, 'Entrada', 10.00, 'Compra Lote: LOT-20260408-679', '2026-04-08 07:15:01'),
(6, 3, 1, 'Entrada', 100.00, 'Compra Lote: LOT-20260408-C59', '2026-04-08 07:49:23'),
(7, 3, 2, 'Entrada', 100.00, 'Compra Lote: LOT-20260408-AD6', '2026-04-08 07:50:10'),
(8, 1, 2, 'Salida', 30.00, 'Consumo Producción #0', '2026-04-08 23:25:52'),
(9, 3, 2, 'Salida', 1.00, 'Consumo Producción #0', '2026-04-08 23:25:52'),
(10, 6, 4, 'Entrada', 1.00, 'Ingreso Producción #0', '2026-04-08 23:25:52'),
(11, 1, 1, 'Salida', 450.00, 'Consumo Producción #0', '2026-04-08 23:27:45'),
(12, 3, 1, 'Salida', 15.00, 'Consumo Producción #0', '2026-04-08 23:27:45'),
(13, 6, 4, 'Entrada', 15.00, 'Ingreso Producción #0', '2026-04-08 23:27:45'),
(14, 1, 2, 'Salida', 300.00, 'Consumo Producción #0', '2026-04-08 23:41:48'),
(15, 3, 2, 'Salida', 10.00, 'Consumo Producción #0', '2026-04-08 23:41:48'),
(16, 6, 4, 'Entrada', 10.00, 'Ingreso Producción #0', '2026-04-08 23:41:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil`
--

CREATE TABLE `perfil` (
  `idPerfil` int(11) NOT NULL,
  `hashPerfil` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` enum('Activo','Inactivo') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `perfil`
--

INSERT INTO `perfil` (`idPerfil`, `hashPerfil`, `nombre`, `descripcion`, `estado`) VALUES
(1, '356a192b7913b04c54574d18c28d46e6395428ab', 'Admin', NULL, 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producciones`
--

CREATE TABLE `producciones` (
  `idProduccion` int(11) NOT NULL,
  `hashProduccion` varchar(100) NOT NULL,
  `idReceta` int(11) NOT NULL,
  `cantidad_producida` decimal(10,2) NOT NULL,
  `costo_total_lote` decimal(10,2) NOT NULL,
  `fecha_produccion` datetime DEFAULT current_timestamp(),
  `idUsuario` int(11) NOT NULL,
  `estado` varchar(20) DEFAULT 'Finalizado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccion_detalle`
--

CREATE TABLE `produccion_detalle` (
  `idProdDetalle` int(11) NOT NULL,
  `idProduccion` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `cantidad_usada` decimal(10,4) NOT NULL,
  `precio_unitario_momento` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `idProducto` int(11) NOT NULL,
  `hashProducto` varchar(64) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `unidad_medida` varchar(20) DEFAULT NULL,
  `tipo` enum('Insumo','Producto Final') DEFAULT 'Insumo',
  `estado` tinyint(1) DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`idProducto`, `hashProducto`, `nombre`, `sku`, `unidad_medida`, `tipo`, `estado`, `deleted_at`) VALUES
(1, 'aa8801ba2bed23490fada92422a16762e4d39e0181a0e613e268ad2442722f5f', 'Tinta Impresora (CMYK)', 'INS-TIN-001', 'ml', 'Insumo', 1, NULL),
(2, '066da5ac245757dbe3927e306d3ed43a01f315e49954fac990394982be528df1', 'Polera Talla M', 'INS-POL-M', 'Unidades', 'Insumo', 1, NULL),
(3, '141e09ba9f948d13c03deebc00b3fce6ca3a80cbddf451b14d36b04e77035815', 'Papel de Sublimación A4', 'INS-PAP-A4', 'Unidades', 'Insumo', 1, NULL),
(4, 'c6f7d5c1e8a1c9b3c6a0d4e5f9a2b7c3d8e1f2a4', 'Polera M Sublimada', 'PRO-POL-SUB-M-001', 'unidad', 'Producto Final', 1, NULL),
(5, 'ac3478d69a3c81fa62e60f5c3696165a4e5e6ac4', 'Taza Blanca 11 oz', 'TAZ-BLA-11OZ', 'Unidad', 'Insumo', 1, NULL),
(6, 'c1dfd96eea8cc2b62785275bca38ac261256e278', 'Taza Sublimada 11 oz', 'TAZ-SUB-11OZ', 'Unidad', 'Producto Final', 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recetas`
--

CREATE TABLE `recetas` (
  `idReceta` int(11) NOT NULL,
  `hashReceta` varchar(64) NOT NULL,
  `nombre_servicio` varchar(100) NOT NULL,
  `costo_operativo_sugerido` decimal(10,2) DEFAULT 0.00,
  `estado` tinyint(1) DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `idProductoFinal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recetas`
--

INSERT INTO `recetas` (`idReceta`, `hashReceta`, `nombre_servicio`, `costo_operativo_sugerido`, `estado`, `deleted_at`, `idProductoFinal`) VALUES
(1, '356a192b7913b04c54574d18c28d46e6395428ab', 'Polera M Sublimada', 150.00, 0, NULL, 4),
(2, 'da4b9237bacccdf19c0760cab7aec4a8359010b0', 'Polera M Sublimada 2', 150.00, 0, NULL, 4),
(3, '77de68daecd823babbb58edb1c8e14d7106e83bb', 'Polera M Sublimada 3', 150.00, 0, NULL, 4),
(4, '1b6453892473a467d07372d45eb05abc2031647a', 'Polera M Sublimada 4', 200.00, 0, NULL, 4),
(5, 'ac3478d69a3c81fa62e60f5c3696165a4e5e6ac4', 'Taza 11oz Sublimada', 60.00, 0, NULL, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receta_detalles`
--

CREATE TABLE `receta_detalles` (
  `idDetalle` int(11) NOT NULL,
  `idReceta` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `cantidad_necesaria` decimal(10,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `receta_detalles`
--

INSERT INTO `receta_detalles` (`idDetalle`, `idReceta`, `idProducto`, `cantidad_necesaria`) VALUES
(1, 3, 1, 50.0000),
(2, 3, 2, 1.0000),
(3, 3, 3, 1.0000),
(4, 4, 1, 70.0000),
(5, 4, 2, 1.0000),
(6, 4, 3, 1.0000),
(7, 5, 1, 30.0000),
(8, 5, 3, 1.0000),
(9, 5, 5, 1.0000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idUsuario` int(11) NOT NULL,
  `hashUsuario` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `pswd` blob NOT NULL,
  `idPerfil` int(11) NOT NULL,
  `estado` enum('Activo','Inactivo') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idUsuario`, `hashUsuario`, `nombre`, `username`, `pswd`, `idPerfil`, `estado`) VALUES
(1, '356a192b7913b04c54574d18c28d46e6395428ab', 'Admin', 'admin', 0xcdbe4075454283250819e7e2ce57b1cf, 1, 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `idVenta` int(11) NOT NULL,
  `hashVenta` varchar(100) DEFAULT NULL,
  `idCliente` int(11) DEFAULT NULL,
  `idUsuario` int(11) DEFAULT NULL,
  `fechaVenta` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) DEFAULT NULL,
  `estado` enum('Pendiente','Entregado','Cancelado') DEFAULT 'Entregado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_items`
--

CREATE TABLE `venta_items` (
  `idVentaItem` int(11) NOT NULL,
  `idVenta` int(11) DEFAULT NULL,
  `idProducto` int(11) DEFAULT NULL,
  `cantidad` decimal(10,2) DEFAULT NULL,
  `precioVenta` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `almacenes`
--
ALTER TABLE `almacenes`
  ADD PRIMARY KEY (`idAlmacen`),
  ADD UNIQUE KEY `hashAlmacen` (`hashAlmacen`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`idCompra`),
  ADD UNIQUE KEY `hashCompra` (`hashCompra`),
  ADD KEY `idProducto` (`idProducto`),
  ADD KEY `idAlmacen` (`idAlmacen`);

--
-- Indices de la tabla `inventarios`
--
ALTER TABLE `inventarios`
  ADD PRIMARY KEY (`idProducto`,`idAlmacen`),
  ADD KEY `idAlmacen` (`idAlmacen`);

--
-- Indices de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD PRIMARY KEY (`idMovimiento`),
  ADD KEY `idProducto` (`idProducto`),
  ADD KEY `idAlmacen` (`idAlmacen`);

--
-- Indices de la tabla `producciones`
--
ALTER TABLE `producciones`
  ADD PRIMARY KEY (`idProduccion`),
  ADD KEY `fk_prod_receta` (`idReceta`);

--
-- Indices de la tabla `produccion_detalle`
--
ALTER TABLE `produccion_detalle`
  ADD PRIMARY KEY (`idProdDetalle`),
  ADD KEY `fk_det_produccion` (`idProduccion`),
  ADD KEY `fk_det_insumo` (`idProducto`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`idProducto`),
  ADD UNIQUE KEY `hashProducto` (`hashProducto`),
  ADD UNIQUE KEY `sku` (`sku`);

--
-- Indices de la tabla `recetas`
--
ALTER TABLE `recetas`
  ADD PRIMARY KEY (`idReceta`),
  ADD UNIQUE KEY `hashReceta` (`hashReceta`);

--
-- Indices de la tabla `receta_detalles`
--
ALTER TABLE `receta_detalles`
  ADD PRIMARY KEY (`idDetalle`),
  ADD KEY `idReceta` (`idReceta`),
  ADD KEY `idProducto` (`idProducto`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`idVenta`);

--
-- Indices de la tabla `venta_items`
--
ALTER TABLE `venta_items`
  ADD PRIMARY KEY (`idVentaItem`),
  ADD KEY `idVenta` (`idVenta`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `almacenes`
--
ALTER TABLE `almacenes`
  MODIFY `idAlmacen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `idCompra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  MODIFY `idMovimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `producciones`
--
ALTER TABLE `producciones`
  MODIFY `idProduccion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `produccion_detalle`
--
ALTER TABLE `produccion_detalle`
  MODIFY `idProdDetalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `idProducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `recetas`
--
ALTER TABLE `recetas`
  MODIFY `idReceta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `receta_detalles`
--
ALTER TABLE `receta_detalles`
  MODIFY `idDetalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `idVenta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `venta_items`
--
ALTER TABLE `venta_items`
  MODIFY `idVentaItem` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`idProducto`) REFERENCES `productos` (`idProducto`),
  ADD CONSTRAINT `compras_ibfk_2` FOREIGN KEY (`idAlmacen`) REFERENCES `almacenes` (`idAlmacen`);

--
-- Filtros para la tabla `inventarios`
--
ALTER TABLE `inventarios`
  ADD CONSTRAINT `inventarios_ibfk_1` FOREIGN KEY (`idProducto`) REFERENCES `productos` (`idProducto`),
  ADD CONSTRAINT `inventarios_ibfk_2` FOREIGN KEY (`idAlmacen`) REFERENCES `almacenes` (`idAlmacen`);

--
-- Filtros para la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD CONSTRAINT `movimientos_ibfk_1` FOREIGN KEY (`idProducto`) REFERENCES `productos` (`idProducto`),
  ADD CONSTRAINT `movimientos_ibfk_2` FOREIGN KEY (`idAlmacen`) REFERENCES `almacenes` (`idAlmacen`);

--
-- Filtros para la tabla `producciones`
--
ALTER TABLE `producciones`
  ADD CONSTRAINT `fk_prod_receta` FOREIGN KEY (`idReceta`) REFERENCES `recetas` (`idReceta`);

--
-- Filtros para la tabla `produccion_detalle`
--
ALTER TABLE `produccion_detalle`
  ADD CONSTRAINT `fk_det_insumo` FOREIGN KEY (`idProducto`) REFERENCES `productos` (`idProducto`),
  ADD CONSTRAINT `fk_det_produccion` FOREIGN KEY (`idProduccion`) REFERENCES `producciones` (`idProduccion`);

--
-- Filtros para la tabla `receta_detalles`
--
ALTER TABLE `receta_detalles`
  ADD CONSTRAINT `receta_detalles_ibfk_1` FOREIGN KEY (`idReceta`) REFERENCES `recetas` (`idReceta`),
  ADD CONSTRAINT `receta_detalles_ibfk_2` FOREIGN KEY (`idProducto`) REFERENCES `productos` (`idProducto`);

--
-- Filtros para la tabla `venta_items`
--
ALTER TABLE `venta_items`
  ADD CONSTRAINT `venta_items_ibfk_1` FOREIGN KEY (`idVenta`) REFERENCES `ventas` (`idVenta`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

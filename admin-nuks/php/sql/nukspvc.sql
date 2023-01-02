-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-01-2023 a las 17:09:39
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `articulos_promocionales`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_category`
--

CREATE TABLE `tbl_category` (
  `cat_id` int(100) NOT NULL,
  `cat_name` varchar(50) NOT NULL,
  `cat_depen` int(250) NOT NULL,
  `cat_level` int(3) NOT NULL,
  `cat_order` int(5) NOT NULL,
  `cat_url` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_client`
--

CREATE TABLE `tbl_client` (
  `client_id` int(250) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `client_bussines` varchar(255) NOT NULL,
  `client_phone` text NOT NULL,
  `client_email` varchar(100) NOT NULL,
  `client_message` text NOT NULL,
  `client_file` varchar(100) NOT NULL,
  `client_type` int(3) NOT NULL,
  `client_level` int(3) NOT NULL,
  `client_pass` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_client_file`
--

CREATE TABLE `tbl_client_file` (
  `file_id` int(250) NOT NULL,
  `client_id` int(250) NOT NULL,
  `file_name` varchar(150) NOT NULL,
  `file_url` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_client_type`
--

CREATE TABLE `tbl_client_type` (
  `type_id` int(10) NOT NULL,
  `type_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_contacto`
--

CREATE TABLE `tbl_contacto` (
  `contact_id` int(11) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `contact_whatsapp` varchar(25) NOT NULL,
  `contact_phone_numbers` varchar(40) NOT NULL,
  `contact_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_decoration`
--

CREATE TABLE `tbl_decoration` (
  `decor_id` int(20) NOT NULL,
  `decor_title` varchar(50) NOT NULL,
  `decor_img` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_product`
--

CREATE TABLE `tbl_product` (
  `product_id` int(255) NOT NULL,
  `code_product` varchar(50) NOT NULL,
  `code` varchar(250) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `category` int(50) NOT NULL,
  `img` varchar(250) NOT NULL,
  `url` varchar(250) NOT NULL,
  `product_depen` int(250) NOT NULL,
  `color` varchar(250) NOT NULL,
  `info` text NOT NULL,
  `price` float(10,2) NOT NULL,
  `price_client` float(10,2) NOT NULL,
  `price_distributor_level_one` float(10,2) NOT NULL,
  `price_distributor_level_two` float(10,2) NOT NULL,
  `price_distributor_level_three` float(10,2) NOT NULL,
  `price_general` decimal(10,2) NOT NULL,
  `supplier` varchar(10) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `prov_website` varchar(255) NOT NULL,
  `clicks` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_prog`
--

CREATE TABLE `tbl_prog` (
  `id_prog` int(11) NOT NULL,
  `prog_percent` int(11) NOT NULL,
  `prog_lastinsertedid` varchar(255) NOT NULL,
  `prog_lastproductname` varchar(255) NOT NULL,
  `prog_lastproductprovcode` varchar(255) NOT NULL,
  `prog_lastproductprov` varchar(255) NOT NULL,
  `prog_errmsg` varchar(255) NOT NULL,
  `prog_sucmsg` varchar(255) NOT NULL,
  `prog_dateaddedorfailed` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_quote`
--

CREATE TABLE `tbl_quote` (
  `quote_id` int(200) NOT NULL,
  `quote_date` date NOT NULL,
  `quote_bussines` varchar(50) NOT NULL,
  `quote_name` varchar(50) NOT NULL,
  `quote_email` varchar(50) NOT NULL,
  `quote_phone` varchar(20) NOT NULL,
  `quote_quantity` varchar(50) NOT NULL,
  `quote_id_product` varchar(100) NOT NULL,
  `quote_client_id` varchar(50) NOT NULL,
  `quote_message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_roles`
--

CREATE TABLE `tbl_roles` (
  `id_role` int(11) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_seo`
--

CREATE TABLE `tbl_seo` (
  `seo_id` int(1) NOT NULL,
  `seo_keywords` text NOT NULL,
  `seo_description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_slider`
--

CREATE TABLE `tbl_slider` (
  `slider_id` int(50) NOT NULL,
  `slider_name` varchar(50) NOT NULL,
  `slider_img` varchar(255) NOT NULL,
  `slider_url` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_slider_product`
--

CREATE TABLE `tbl_slider_product` (
  `slider_prod_id` int(200) NOT NULL,
  `prod_id` int(255) NOT NULL,
  `slider_reference` varchar(100) NOT NULL,
  `slider_img` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_supplier`
--

CREATE TABLE `tbl_supplier` (
  `supplier_id` int(11) NOT NULL,
  `supplier_code` int(11) NOT NULL,
  `supplier_name` varchar(50) NOT NULL,
  `supplier_website` varchar(100) NOT NULL,
  `supplier_api` varchar(255) NOT NULL,
  `supplier_prod_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` int(50) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_lastname` varchar(50) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_pass` varchar(50) NOT NULL,
  `user_role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `user_name`, `user_lastname`, `user_email`, `user_pass`, `user_role`) VALUES
(1, 'admin', 'administrador', 'admin@email.com', '202cb962ac59075b964b07152d234b70', '3'),
(6, 'Jessica', 'Osorio', 'jessica.osorio@promosoluciones.com.mx', 'e3778380b0360f5e35411c728fcfbd0b', '1'),
(9, 'Jorge', 'Arellano', 'jorge@email.com', 'e3778380b0360f5e35411c728fcfbd0b', '1'),
(10, 'prueba', 'prueba', 'prueba@email.com', 'cc0cd62eebcbe0137be59d0fef23a8d0', '2'),
(12, 'Cristian', 'Solis', 'cristian.solis@promosoluciones.com.mx', 'd41d8cd98f00b204e9800998ecf8427e', '3'),
(14, 'Alexis', 'Garcia', 'alexis.garcia@promosoluciones.com.mx', '5afc5853217bd3d068abe172d8f7b63c', '4');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indices de la tabla `tbl_client`
--
ALTER TABLE `tbl_client`
  ADD PRIMARY KEY (`client_id`);

--
-- Indices de la tabla `tbl_client_type`
--
ALTER TABLE `tbl_client_type`
  ADD PRIMARY KEY (`type_id`);

--
-- Indices de la tabla `tbl_contacto`
--
ALTER TABLE `tbl_contacto`
  ADD PRIMARY KEY (`contact_id`);

--
-- Indices de la tabla `tbl_decoration`
--
ALTER TABLE `tbl_decoration`
  ADD PRIMARY KEY (`decor_id`);

--
-- Indices de la tabla `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indices de la tabla `tbl_prog`
--
ALTER TABLE `tbl_prog`
  ADD PRIMARY KEY (`id_prog`);

--
-- Indices de la tabla `tbl_quote`
--
ALTER TABLE `tbl_quote`
  ADD PRIMARY KEY (`quote_id`);

--
-- Indices de la tabla `tbl_roles`
--
ALTER TABLE `tbl_roles`
  ADD PRIMARY KEY (`id_role`);

--
-- Indices de la tabla `tbl_seo`
--
ALTER TABLE `tbl_seo`
  ADD PRIMARY KEY (`seo_id`);

--
-- Indices de la tabla `tbl_slider`
--
ALTER TABLE `tbl_slider`
  ADD PRIMARY KEY (`slider_id`);

--
-- Indices de la tabla `tbl_slider_product`
--
ALTER TABLE `tbl_slider_product`
  ADD PRIMARY KEY (`slider_prod_id`);

--
-- Indices de la tabla `tbl_supplier`
--
ALTER TABLE `tbl_supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indices de la tabla `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `cat_id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_client`
--
ALTER TABLE `tbl_client`
  MODIFY `client_id` int(250) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_client_type`
--
ALTER TABLE `tbl_client_type`
  MODIFY `type_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_contacto`
--
ALTER TABLE `tbl_contacto`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_decoration`
--
ALTER TABLE `tbl_decoration`
  MODIFY `decor_id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `product_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_prog`
--
ALTER TABLE `tbl_prog`
  MODIFY `id_prog` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_quote`
--
ALTER TABLE `tbl_quote`
  MODIFY `quote_id` int(200) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_roles`
--
ALTER TABLE `tbl_roles`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_seo`
--
ALTER TABLE `tbl_seo`
  MODIFY `seo_id` int(1) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_slider`
--
ALTER TABLE `tbl_slider`
  MODIFY `slider_id` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_slider_product`
--
ALTER TABLE `tbl_slider_product`
  MODIFY `slider_prod_id` int(200) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

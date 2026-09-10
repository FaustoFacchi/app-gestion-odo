-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-09-2026 a las 20:42:04
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
-- Base de datos: `odonto_app`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anamnesis`
--

CREATE TABLE `anamnesis` (
  `id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `sg_tratamiento` tinyint(1) DEFAULT 0,
  `sg_tratamiento_txt` varchar(255) DEFAULT NULL,
  `sg_medicacion` tinyint(1) DEFAULT 0,
  `sg_medicacion_txt` varchar(255) DEFAULT NULL,
  `sg_alergia` tinyint(1) DEFAULT 0,
  `sg_alergia_txt` varchar(255) DEFAULT NULL,
  `sg_cirugia` tinyint(1) DEFAULT 0,
  `sg_cirugia_txt` varchar(255) DEFAULT NULL,
  `sg_presion` tinyint(1) DEFAULT 0,
  `sg_presion_alta` tinyint(1) DEFAULT 0,
  `sg_presion_baja` tinyint(1) DEFAULT 0,
  `sg_diabetico` tinyint(1) DEFAULT 0,
  `sg_cardiaco` tinyint(1) DEFAULT 0,
  `sg_epilepsia` tinyint(1) DEFAULT 0,
  `sg_asma` tinyint(1) DEFAULT 0,
  `sg_hemorragia` tinyint(1) DEFAULT 0,
  `sg_fuma` tinyint(1) DEFAULT 0,
  `sg_embarazo` tinyint(1) DEFAULT 0,
  `sb_dolor` tinyint(1) DEFAULT 0,
  `sb_sangrado` tinyint(1) DEFAULT 0,
  `sb_bruxismo` tinyint(1) DEFAULT 0,
  `sb_movilidad` tinyint(1) DEFAULT 0,
  `sb_anestesia` tinyint(1) DEFAULT 0,
  `sb_frecuencia_cepillado` varchar(10) DEFAULT '2',
  `sb_motivo_consulta` text DEFAULT NULL,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `anamnesis`
--

INSERT INTO `anamnesis` (`id`, `paciente_id`, `sg_tratamiento`, `sg_tratamiento_txt`, `sg_medicacion`, `sg_medicacion_txt`, `sg_alergia`, `sg_alergia_txt`, `sg_cirugia`, `sg_cirugia_txt`, `sg_presion`, `sg_presion_alta`, `sg_presion_baja`, `sg_diabetico`, `sg_cardiaco`, `sg_epilepsia`, `sg_asma`, `sg_hemorragia`, `sg_fuma`, `sg_embarazo`, `sb_dolor`, `sb_sangrado`, `sb_bruxismo`, `sb_movilidad`, `sb_anestesia`, `sb_frecuencia_cepillado`, `sb_motivo_consulta`, `fecha_actualizacion`) VALUES
(1, 3, 0, '', 0, '', 1, 'acaros', 1, 'apendicitis', 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, '3', 'Limpieza', '2026-08-07 13:12:38'),
(2, 8, 0, '', 0, '', 0, '', 0, '', 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 0, 0, 0, 1, 0, '2', '', '2026-08-07 13:30:11'),
(3, 8, 0, '', 0, '', 0, '', 0, '', 0, 0, 0, 0, 0, 1, 1, 0, 0, 1, 0, 0, 0, 1, 0, '2', '', '2026-08-07 14:03:37'),
(4, 8, 0, '', 0, '', 0, '', 0, '', 0, 0, 0, 0, 0, 1, 1, 0, 0, 1, 0, 0, 0, 1, 0, '2', '', '2026-08-07 14:08:52'),
(5, 8, 0, '', 0, '', 0, '', 0, '', 1, 0, 0, 0, 0, 1, 1, 0, 0, 1, 0, 0, 0, 1, 0, '2', '', '2026-08-07 14:08:58'),
(6, 8, 0, '', 0, '', 0, '', 0, '', 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 0, 0, 0, 1, 0, '2', '', '2026-08-07 14:09:05'),
(7, 9, 0, '', 0, '', 0, '', 0, '', 0, 0, 0, 0, 0, 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, '2', '', '2026-08-07 14:09:21'),
(8, 9, 0, '', 0, '', 0, '', 0, '', 0, 0, 0, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, '2', '', '2026-08-07 14:10:09'),
(9, 16, 0, '', 0, '', 0, '', 0, '', 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, '2', '', '2026-08-07 14:24:52'),
(10, 16, 0, '', 0, '', 0, '', 0, '', 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, '2', '', '2026-08-07 14:24:52'),
(11, 16, 0, '', 0, '', 0, '', 1, '', 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, '2', '', '2026-08-07 14:25:00'),
(12, 16, 0, '', 0, '', 0, '', 1, '', 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, '2', '', '2026-08-07 14:25:00'),
(13, 16, 0, '', 1, '', 0, '', 1, '', 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, '2', '', '2026-08-07 14:25:08'),
(14, 16, 0, '', 1, '', 0, '', 1, '', 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, '2', '', '2026-08-07 14:25:08'),
(15, 4, 0, '', 0, '', 0, '', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, '2', '', '2026-08-20 07:16:57'),
(16, 4, 0, '', 0, '', 0, '', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, '2', '', '2026-08-20 07:16:57'),
(17, 17, 0, '', 0, '', 0, '', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2', '', '2026-08-20 08:15:03'),
(18, 17, 1, '', 1, '', 1, '', 1, '', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2', '', '2026-08-20 08:15:03'),
(19, 4, 0, '', 0, '', 0, '', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, '2', '', '2026-08-20 08:38:31'),
(20, 4, 0, '', 0, '', 0, '', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, '2', '', '2026-08-20 08:38:31'),
(21, 4, 0, '', 1, '', 0, '', 0, '', 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 1, 0, '2', '', '2026-09-10 18:02:10'),
(22, 4, 0, '', 1, '', 0, '', 0, '', 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 1, 0, '2', '', '2026-09-10 18:02:10'),
(23, 17, 0, '', 1, '', 0, '', 0, '', 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 1, 0, 0, 0, '3', '', '2026-09-10 18:03:11'),
(24, 17, 0, '', 1, '', 0, '', 0, '', 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 1, 0, 0, 0, '3', '', '2026-09-10 18:03:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos_pacientes`
--

CREATE TABLE `documentos_pacientes` (
  `id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `nombre_archivo` varchar(255) DEFAULT NULL,
  `ruta_archivo` varchar(255) DEFAULT NULL,
  `fecha_subida` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `documentos_pacientes`
--

INSERT INTO `documentos_pacientes` (`id`, `paciente_id`, `nombre_archivo`, `ruta_archivo`, `fecha_subida`) VALUES
(1, 3, 'Ficha Clínica, Odontograma, Anamnese - Odontologia.jpg', 'uploads/1787215377_Ficha Clínica, Odontograma, Anamnese - Odontologia.jpg', '2026-08-20 08:42:57'),
(2, 3, 'silla odo.jpg', 'uploads/1787215568_silla odo.jpg', '2026-08-20 08:46:08'),
(3, 3, 'silla odo.jpg', 'uploads/1787215656_silla odo.jpg', '2026-08-20 08:47:36'),
(4, 4, 'Ficha Clínica, Odontograma, Anamnese - Odontologia.jpg', 'uploads/paciente_4/1787215910_Ficha Clínica, Odontograma, Anamnese - Odontologia.jpg', '2026-08-20 08:51:50'),
(5, 3, 'silla odo.jpg', 'uploads/paciente_3/1787215944_silla odo.jpg', '2026-08-20 08:52:24'),
(6, 3, 'Ficha Clínica, Odontograma, Anamnese - Odontologia.jpg', 'uploads/paciente_3/1787215982_Ficha Clínica, Odontograma, Anamnese - Odontologia.jpg', '2026-08-20 08:53:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evoluciones`
--

CREATE TABLE `evoluciones` (
  `id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `pieza_numero` int(11) DEFAULT NULL,
  `descripcion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `odontograma`
--

CREATE TABLE `odontograma` (
  `id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `pieza_numero` int(11) NOT NULL,
  `cara_vestibular` enum('sano','tratado','pendiente','ausente') DEFAULT 'sano',
  `cara_lingual_palatina` enum('sano','tratado','pendiente','ausente') DEFAULT 'sano',
  `cara_mesial` enum('sano','tratado','pendiente','ausente') DEFAULT 'sano',
  `cara_distal` enum('sano','tratado','pendiente','ausente') DEFAULT 'sano',
  `cara_oclusal_incisal` enum('sano','tratado','pendiente','ausente') DEFAULT 'sano',
  `estado_general` enum('sano','en_tratamiento','ausente') DEFAULT 'sano',
  `ultima_modificacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE `pacientes` (
  `id` int(11) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `obra_social` varchar(100) DEFAULT NULL,
  `numero_afiliado` varchar(50) DEFAULT NULL,
  `alertas_medicas` text DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`id`, `dni`, `nombre`, `apellido`, `telefono`, `email`, `fecha_nacimiento`, `obra_social`, `numero_afiliado`, `alertas_medicas`, `creado_en`) VALUES
(1, '36890123', 'María', 'Gómez', '+54 341 555-4321', NULL, NULL, 'OSDE 210', NULL, 'Alérgica a Penicilina / Hipertensa', '2026-08-04 07:03:13'),
(2, '32111222', 'Juan', 'Pérez', '+54 341 555-8899', NULL, NULL, 'SWISS MEDICAL', NULL, 'Sin antecedentes de riesgo', '2026-08-04 07:03:13'),
(3, '43379883', 'Fausto', 'Facchinetti', '+543413720969', NULL, NULL, 'osde', NULL, 'no alergias', '2026-08-04 07:17:45'),
(4, '43379', 'Fau', 'facchi', '+5434137', NULL, NULL, 'osde', NULL, 'hipertenso', '2026-08-04 08:24:40'),
(5, '4334523', 'Jorge', 'Benitez', '+543413720222', NULL, NULL, 'no tiene', NULL, '', '2026-08-07 09:14:14'),
(6, '12345678', 'ruben', 'emilio diaz', '03413720966', NULL, NULL, 'exclusiva', NULL, '', '2026-08-07 11:57:44'),
(7, '12345669', 'cami', 'puig', '+', NULL, NULL, 'amur', NULL, '', '2026-08-07 12:13:43'),
(8, '21381076', 'Fernando', 'Facchinetti', '945385439', NULL, NULL, 'amur', NULL, '', '2026-08-07 13:29:07'),
(9, '12343333', 'dsasdsad', 'dsasdsadas', '3333333333', NULL, NULL, 'fghd', NULL, '', '2026-08-07 14:04:03'),
(10, '654654654', 'sdasdsadas', 'dasd5rgsdhf', '645645654', NULL, NULL, '656456', NULL, '', '2026-08-07 14:07:59'),
(11, '43379888', 'lopez', 'jorge', '1111111111111111111', NULL, NULL, 'amur', NULL, '', '2026-08-07 14:09:55'),
(12, '43366555', 'martin', 'rolinga', '03413720222', NULL, NULL, 'osde', NULL, '', '2026-08-07 14:10:40'),
(13, '12342222', 'Fausto', 'puig', '03413720888', NULL, NULL, 'amur', NULL, '', '2026-08-07 14:13:10'),
(15, '12343355', 'Fausto', 'emilio diaz', '03413720969', NULL, NULL, 'amur', NULL, '', '2026-08-07 14:15:25'),
(16, '12345660', 'Fausto', 'dsasdsadas', '03413720969', NULL, NULL, 'amur', NULL, '', '2026-08-07 14:24:25'),
(17, '1234334444', 'Fausto', 'emilio diaz', '03413720969', NULL, NULL, 'exclusiva', NULL, '', '2026-08-20 07:16:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turnos`
--

CREATE TABLE `turnos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `fecha_hora_inicio` datetime NOT NULL,
  `fecha_hora_fin` datetime NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `estado` enum('pendiente','confirmado','atendido','cancelado') DEFAULT 'pendiente',
  `observaciones` text DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `turnos`
--

INSERT INTO `turnos` (`id`, `usuario_id`, `paciente_id`, `fecha_hora_inicio`, `fecha_hora_fin`, `motivo`, `estado`, `observaciones`, `creado_en`) VALUES
(16, 2, 2, '2026-08-08 06:30:00', '2026-08-08 07:30:00', 'Limpieza', 'pendiente', '', '2026-08-05 10:33:06'),
(17, 2, 1, '2026-08-06 13:15:00', '2026-08-06 14:45:00', 'Consulta General', 'pendiente', '', '2026-08-05 10:45:25'),
(18, 2, 3, '2026-08-08 08:00:00', '2026-08-08 10:00:00', 'Ortodoncia', 'pendiente', '', '2026-08-05 10:58:04'),
(19, 2, 3, '2026-08-07 10:15:00', '2026-08-07 11:15:00', 'Extracción', 'pendiente', '', '2026-08-05 19:56:25'),
(23, 2, 15, '2026-08-07 08:45:00', '2026-08-07 09:00:00', 'Ortodoncia', 'pendiente', '', '2026-08-07 14:21:49'),
(24, 2, 3, '2026-09-10 06:30:00', '2026-09-10 08:00:00', 'Limpieza', 'pendiente', '', '2026-09-10 17:51:55'),
(25, 2, 7, '2026-09-10 09:30:00', '2026-09-10 11:00:00', 'Ortodoncia', 'pendiente', '', '2026-09-10 17:52:23'),
(26, 2, 5, '2026-09-11 07:00:00', '2026-09-11 08:30:00', 'Extracción', 'pendiente', '', '2026-09-10 17:52:58'),
(27, 2, 6, '2026-09-11 08:30:00', '2026-09-11 09:00:00', 'Limpieza', 'pendiente', '', '2026-09-10 17:53:37'),
(28, 2, 6, '2026-09-11 10:00:00', '2026-09-11 11:00:00', 'Consulta General', 'pendiente', '', '2026-09-10 17:54:10'),
(29, 2, 1, '2026-09-12 06:30:00', '2026-09-12 11:00:00', 'Extracción', 'pendiente', '', '2026-09-10 17:54:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','odontologo','recepcion') DEFAULT 'odontologo',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `email`, `password`, `rol`, `creado_en`) VALUES
(1, 'Dra. Sofía', '', 'sofia@odontoapp.com', '123456', 'admin', '2026-08-04 07:03:13'),
(2, 'Admin', 'Odontólogo', 'admin@odontoapp.com', '$2y$10$UpwsPrAJmvHABnTC9zYH7uDgRKW4.Fb0jF.R9YIulZc3zUrk/4d/.', 'odontologo', '2026-08-04 08:02:39');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `anamnesis`
--
ALTER TABLE `anamnesis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paciente_id` (`paciente_id`);

--
-- Indices de la tabla `documentos_pacientes`
--
ALTER TABLE `documentos_pacientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `evoluciones`
--
ALTER TABLE `evoluciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paciente_id` (`paciente_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `odontograma`
--
ALTER TABLE `odontograma`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `paciente_pieza_unique` (`paciente_id`,`pieza_numero`);

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`);

--
-- Indices de la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paciente_id` (`paciente_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `anamnesis`
--
ALTER TABLE `anamnesis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `documentos_pacientes`
--
ALTER TABLE `documentos_pacientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `evoluciones`
--
ALTER TABLE `evoluciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `odontograma`
--
ALTER TABLE `odontograma`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `turnos`
--
ALTER TABLE `turnos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `anamnesis`
--
ALTER TABLE `anamnesis`
  ADD CONSTRAINT `anamnesis_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `evoluciones`
--
ALTER TABLE `evoluciones`
  ADD CONSTRAINT `evoluciones_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evoluciones_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `odontograma`
--
ALTER TABLE `odontograma`
  ADD CONSTRAINT `odontograma_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD CONSTRAINT `turnos_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

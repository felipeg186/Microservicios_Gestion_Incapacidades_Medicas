-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 31-05-2026 a las 00:41:58
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS `db_seguimiento`;

-- Indicarle al servidor que use esta base de datos para lo que sigue
USE `db_seguimiento`;


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_seguimiento`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguimientos`
--

CREATE TABLE `seguimientos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `incapacidad_id` bigint(20) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `comentario` text NOT NULL,
  `estado` enum('registrada','en_revision','aprobada','rechazada','finalizada') NOT NULL,
  `usuario_responsable` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `seguimientos`
--

INSERT INTO `seguimientos` (`id`, `incapacidad_id`, `fecha`, `comentario`, `estado`, `usuario_responsable`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-06-01', 'Incapacidad registrada correctamente', 'registrada', 'gestionhumana', '2026-05-30 20:33:59', '2026-05-30 20:33:59'),
(2, 1, '2026-06-02', 'Incapacidad aprobada por gestion humana', 'aprobada', 'admin', '2026-05-30 20:33:59', '2026-05-30 20:33:59'),
(3, 2, '2026-06-08', 'Pendiente validacion de soportes medicos', 'en_revision', 'gestionhumana', '2026-05-30 20:33:59', '2026-05-30 20:33:59');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `seguimientos`
--
ALTER TABLE `seguimientos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `seguimientos`
--
ALTER TABLE `seguimientos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.2.13.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 27, 2015 at 10:33 PM
-- Server version: 10.0.13-MariaDB
-- PHP Version: 5.6.1


--USE Negocios

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `u944467267_gacet`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

CREATE TABLE IF NOT EXISTS `categorias` (
`categoria_id` int(11) NOT NULL,
  `nombre` varchar(512) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Table structure for table `llaves`
--

CREATE TABLE IF NOT EXISTS `llaves` (
  `llave` varchar(32) NOT NULL,
  `negocio_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `negocios`
--

CREATE TABLE IF NOT EXISTS `negocios` (
`negocio_id` int(11) NOT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `nombre` varchar(512) DEFAULT NULL,
  `duenio` varchar(512) DEFAULT NULL,
  `descripcion` longtext,
  `logo` longtext,
  `banner` longtext,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sucursales`
--

CREATE TABLE IF NOT EXISTS `sucursales` (
`sucursal_id` bigint(20) NOT NULL,
  `negocio_id` int(11) NOT NULL,
  `nombre` varchar(512) DEFAULT NULL,
  `telefonos` longtext,
  `celulares` longtext,
  `emails` longtext,
  `calle` varchar(512) DEFAULT NULL,
  `numero_exterior` varchar(16) DEFAULT NULL,
  `numero_interior` varchar(16) DEFAULT NULL,
  `entre_calles` varchar(512) DEFAULT NULL,
  `localidad` varchar(512) DEFAULT NULL,
  `municipio` varchar(512) DEFAULT NULL,
  `estado` varchar(512) DEFAULT NULL,
  `codigo_postal` varchar(5) DEFAULT NULL,
  `otras_referencias` longtext
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
 ADD PRIMARY KEY (`categoria_id`);

--
-- Indexes for table `llaves`
--
ALTER TABLE `llaves`
 ADD PRIMARY KEY (`llave`), ADD KEY `negocios_llaves_fk` (`negocio_id`);

--
-- Indexes for table `negocios`
--
ALTER TABLE `negocios`
 ADD PRIMARY KEY (`negocio_id`), ADD KEY `categorias_negocios_fk` (`categoria_id`);

--
-- Indexes for table `sucursales`
--
ALTER TABLE `sucursales`
 ADD PRIMARY KEY (`sucursal_id`), ADD KEY `negocios_sucursales_fk` (`negocio_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
MODIFY `categoria_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `negocios`
--
ALTER TABLE `negocios`
MODIFY `negocio_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `sucursales`
--
ALTER TABLE `sucursales`
MODIFY `sucursal_id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `llaves`
--
ALTER TABLE `llaves`
ADD CONSTRAINT `negocios_llaves_fk` FOREIGN KEY (`negocio_id`) REFERENCES `negocios` (`negocio_id`) ON DELETE SET NULL;

--
-- Constraints for table `negocios`
--
ALTER TABLE `negocios`
ADD CONSTRAINT `categorias_negocios_fk` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`categoria_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `sucursales`
--
ALTER TABLE `sucursales`
ADD CONSTRAINT `negocios_sucursales_fk` FOREIGN KEY (`negocio_id`) REFERENCES `negocios` (`negocio_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



--
-- Dumping data for table `categorias`
--

INSERT INTO `categorias` (`nombre`) VALUES
('Administración / Oficina'),
('Almacén / Logística'),
('Construccion y obra'),
('Contabilidad / Finanzas'),
('Diseño / Artes gráficas'),
('Docencia'),
('Hostelería / Turismo'),
('Informática / Telecomunicaciones'),
('Ingeniería'),
('Legal / Asesoría'),
('Mantenimiento y Reparaciones Técnicas'),
('Medicina / Salud'),
('Producción / Operaciones'),
('Venta de productos para consumo'),
('Recursos Humanos'),
('Servicios Generales, Aseo y Seguridad'),
('Belleza'),
('Deportes'),
('Otro');

-- --------------------------------------------------------
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 08-10-2024 a las 21:37:16
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
-- Base de datos: `horarios`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignatura`
--

CREATE TABLE `asignatura` (
  `idAsig` int(11) NOT NULL COMMENT 'Número de Identificación de la Asignatura',
  `codAsig` varchar(11) DEFAULT NULL COMMENT 'Código de la Asignatura',
  `nombreAsig` varchar(30) DEFAULT NULL COMMENT 'Nombre de la Asignatura',
  `codProg` int(11) NOT NULL COMMENT 'Código del Programa',
  `periodoAcade` varchar(30) DEFAULT NULL COMMENT 'Periodo Académico',
  `codInclu` int(11) NOT NULL COMMENT 'Código de Inclusión(Discapacitados)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asignatura`
--

INSERT INTO `asignatura` (`idAsig`, `codAsig`, `nombreAsig`, `codProg`, `periodoAcade`, `codInclu`) VALUES
(1, '750022M', 'Sistemas de informacion', 2711, '6', 1),
(2, '750057M', 'Practicas profesionales', 2711, '6', 1),
(3, '730125M', 'impacto ambiental', 2712, '4', 1),
(4, '204104M', 'Ingles II', 2712, '4', 1),
(5, '204104M', 'Ingles II', 2716, '4', 1),
(6, '730169M', 'Sistemas agricolas', 2716, '3', 1),
(7, '701006C', 'Talle I', 2724, '1', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `criteriodocente`
--

CREATE TABLE `criteriodocente` (
  `idCritDoc` int(11) NOT NULL COMMENT 'Número de Identificación del criterio del Docente',
  `diasHorario` varchar(30) DEFAULT NULL COMMENT 'Cantidad de Días permitidos en el Horario',
  `idDoc` int(11) NOT NULL COMMENT 'Número de Identificación del Docente',
  `fechaRegistroCri` date DEFAULT NULL COMMENT 'Fecha del Registro de un nuevo Criterio',
  `codInclu` int(11) NOT NULL COMMENT 'Código de Inclusión(Discapacitados)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `criteriodocente`
--

INSERT INTO `criteriodocente` (`idCritDoc`, `diasHorario`, `idDoc`, `fechaRegistroCri`, `codInclu`) VALUES
(43, '36', 1, '2024-10-07', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamento`
--

CREATE TABLE `departamento` (
  `idDepto` int(11) NOT NULL COMMENT 'Número de Identificación del Departamento',
  `nombreDepto` varchar(30) DEFAULT NULL COMMENT 'Nombre del Departamento',
  `descriDepto` varchar(30) DEFAULT NULL COMMENT 'Descripción del Departamento',
  `nomenDepto` varchar(3) DEFAULT NULL COMMENT 'Nomenclatura del Departamento',
  `idPais` int(11) NOT NULL COMMENT 'Número de Identificación del País'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `departamento`
--

INSERT INTO `departamento` (`idDepto`, `nombreDepto`, `descriDepto`, `nomenDepto`, `idPais`) VALUES
(1, 'Valle del Cauca', 'Departamento de ubicacion', '132', 1),
(2, 'Quindio', 'Departamento de ubicacion', '132', 1),
(3, 'Risaralda', 'Departamento de ubicacion', '44', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docasighorario`
--

CREATE TABLE `docasighorario` (
  `idDocAsig` int(11) NOT NULL COMMENT 'Número de Identificación del Docente y Asignatura',
  `idDoc` int(11) NOT NULL COMMENT 'Número de Identificación del Docente',
  `idAsig` int(11) NOT NULL COMMENT 'Número de Identificación de la Asignatura',
  `idEsp` int(11) NOT NULL COMMENT 'Número de Identificación del Espacio',
  `diaAsigHorario` varchar(30) DEFAULT NULL COMMENT 'Dias de la Asignatura y Horario',
  `horaAsigHorario` varchar(30) DEFAULT NULL COMMENT 'Hora de la Asignatura y Horario',
  `fechaRegistro` date DEFAULT NULL COMMENT 'Fecha de un Nuevo Registro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `docasighorario`
--

INSERT INTO `docasighorario` (`idDocAsig`, `idDoc`, `idAsig`, `idEsp`, `diaAsigHorario`, `horaAsigHorario`, `fechaRegistro`) VALUES
(2, 1, 4, 5, 'virnes y sabado', '8 horas', '2024-10-07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docente`
--

CREATE TABLE `docente` (
  `idDoc` int(11) NOT NULL COMMENT 'Número de Identificación del Docente',
  `cedulaDoc` varchar(11) DEFAULT NULL COMMENT 'Cédula del Docente',
  `nombreDoc` varchar(30) DEFAULT NULL COMMENT 'Nombre del Docente',
  `localDoc` varchar(30) DEFAULT NULL COMMENT 'Localidad donde habita el Docente',
  `idTipoDoc` int(11) NOT NULL COMMENT 'Número de Identificación del Tipo de Docente',
  `idMuni` int(11) NOT NULL COMMENT 'Número de Identificación del Municipio'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `docente`
--

INSERT INTO `docente` (`idDoc`, `cedulaDoc`, `nombreDoc`, `localDoc`, `idTipoDoc`, `idMuni`) VALUES
(1, '1111', 'Pepita', 'Valle del Cauca', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `espacio`
--

CREATE TABLE `espacio` (
  `idEsp` int(11) NOT NULL COMMENT 'Número de Identificación del Espacio',
  `idTipoEsp` int(11) NOT NULL COMMENT 'Número de Identificación del tipo de Espacio',
  `codUbi` int(11) NOT NULL COMMENT 'Código de la Ubicación',
  `capacidad` int(11) DEFAULT NULL COMMENT 'Capacidad estimada',
  `piso` varchar(11) DEFAULT NULL COMMENT 'Cantidad de pisos en un Espacio',
  `cumpleInclusion` int(3) DEFAULT NULL COMMENT '¿Cumple con la inclusión para Discapacitados?'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `espacio`
--

INSERT INTO `espacio` (`idEsp`, `idTipoEsp`, `codUbi`, `capacidad`, `piso`, `cumpleInclusion`) VALUES
(5, 2, 1, 200, '2', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inclusionsocial`
--

CREATE TABLE `inclusionsocial` (
  `codInclu` int(11) NOT NULL COMMENT 'código de la Inclusión Social',
  `nombreInclu` varchar(30) DEFAULT NULL COMMENT 'Nombre de la Inclusión Social',
  `descriInclu` varchar(30) DEFAULT NULL COMMENT 'Descripción de la Inclusión Social',
  `nomenInclu` varchar(3) DEFAULT NULL COMMENT 'Nomenclatura de la Inclusión Social'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inclusionsocial`
--

INSERT INTO `inclusionsocial` (`codInclu`, `nombreInclu`, `descriInclu`, `nomenInclu`) VALUES
(1, 'Discapacidad motriz', 'Personas con problemas de movi', '123');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipio`
--

CREATE TABLE `municipio` (
  `idMuni` int(11) NOT NULL COMMENT 'Número de Identificación del Municipio',
  `nombreMuni` varchar(30) DEFAULT NULL COMMENT 'Nombre del Municipio',
  `descriMuni` varchar(30) DEFAULT NULL COMMENT 'Descripción del Municipio',
  `nomenMuni` varchar(3) DEFAULT NULL COMMENT 'Nomenclatura del Municipio',
  `idDepto` int(11) NOT NULL COMMENT 'Número de Identificación del Departamento'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `municipio`
--

INSERT INTO `municipio` (`idMuni`, `nombreMuni`, `descriMuni`, `nomenMuni`, `idDepto`) VALUES
(1, 'Zarzal', '', '11', 1),
(2, 'Roldanillo', '', '12', 1),
(3, 'Armenia', '', '22', 2),
(4, 'Montenegro', '', '23', 2),
(5, 'Pereira', '', '33', 3),
(6, 'Dosquebradas', 'origen', '3', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pais`
--funciono?

CREATE TABLE `pais` (
  `idPais` int(11) AUTO_INCREMENT NOT NULL COMMENT 'Número de Identificación del País',
  `nombrePais` varchar(30) DEFAULT NULL COMMENT 'Nombre del País',
  `descriPais` varchar(30) DEFAULT NULL COMMENT 'Descripción del País',
  `nomenPais` varchar(3) DEFAULT NULL COMMENT 'Nomenclatura del País'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pais`
--

INSERT INTO `pais` (`idPais`, `nombrePais`, `descriPais`, `nomenPais`) VALUES
(1, 'colombia', 'Pais de ', '123');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programa`
--

CREATE TABLE `programa` (
  `codProg` int(11) NOT NULL COMMENT 'Código del Programa Académico',
  `nombreProg` varchar(30) DEFAULT NULL COMMENT 'Nombre del Programa Académico',
  `descriProg` varchar(100) DEFAULT NULL COMMENT 'Descripción del Programa Académico',
  `SNIES` varchar(30) DEFAULT NULL,
  `jornada` varchar(30) DEFAULT NULL COMMENT 'Tipo de Jornada del Programa Académico',
  `idDocApoyo` int(11) DEFAULT NULL COMMENT 'Número de Identificación del Docente de Apoyo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `programa`
--

INSERT INTO `programa` (`codProg`, `nombreProg`, `descriProg`, `SNIES`, `jornada`, `idDocApoyo`) VALUES
(2711, 'Tecnologia en sistemas de la i', 'Tecnologia en sistemas de la informacion', '12480', 'Diurna', 1),
(2712, 'Tecnologia en alimentos', 'Tecnologia en alimentos', '222', 'Diurna', 2),
(2716, 'Tecnologia Agroambiental', 'Tecnologia Agroambiental', '33', 'Diurna', 3),
(2724, 'Tecnologia en Desarrollo de so', 'Tecnologia en Desarrollo de software', '111', 'Diurna', 1),
(2725, 'Tecnologia en electronica indu', 'Tecnologia en electronica industrial', '44', 'Diurna', 4),
(27112, 'Tecnologia en sistemas de la i', 'Tecnologia en sistemas de la informacion', '12480', 'Nocturna', 1),
(27122, 'Tecnologia en alimentos', 'Tecnologia en alimentos', '222', 'Nocturna', 2),
(27162, 'Tecnologia Agroambiental', 'Tecnologia Agroambiental', '33', 'Nocturna', 3),
(27242, 'Tecnologia en Desarrollo de so', 'Tecnologia en Desarrollo de software', '111', 'Nocturna', 1),
(27252, 'Tecnologia en electronica indu', 'Tecnologia en electronica industrial', '44', 'Nocturna', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodocente`
--

CREATE TABLE `tipodocente` (
  `idTipoDoc` int(11) NOT NULL COMMENT 'Número de Identificación del Tipo de Docente',
  `nombreTipoDoc` varchar(30) DEFAULT NULL COMMENT 'Nombre del Tipo de Docente',
  `descTipoDoc` varchar(30) DEFAULT NULL COMMENT 'Descripción del Tipo de Docente',
  `nomenTipoDoc` varchar(3) DEFAULT NULL COMMENT 'Nomenclatura del ipo de Docente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipodocente`
--

INSERT INTO `tipodocente` (`idTipoDoc`, `nombreTipoDoc`, `descTipoDoc`, `nomenTipoDoc`) VALUES
(1, 'Catedra', 'Catedra', '123'),
(2, 'Nombrado', 'Nombrado', '123'),
(3, 'Por horas', 'Por horas', '124');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoespacio`
--

CREATE TABLE `tipoespacio` (
  `idTipoEsp` int(11) NOT NULL COMMENT 'Número de Identificación del Tipo de Espacio',
  `nombreTipoEsp` varchar(30) DEFAULT NULL COMMENT 'Nombre del Tipo de Espacio',
  `descriTipoEsp` varchar(30) DEFAULT NULL COMMENT 'Descripción del Tipo de Espacio',
  `nomenTipoEsp` varchar(3) DEFAULT NULL COMMENT 'Nomenclatura del Tipo de Espacio'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipoespacio`
--

INSERT INTO `tipoespacio` (`idTipoEsp`, `nombreTipoEsp`, `descriTipoEsp`, `nomenTipoEsp`) VALUES
(1, 'Balsas', 'Campus', '123'),
(2, 'Bolivar', 'Sede principal', '123');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicacion`
--

CREATE TABLE `ubicacion` (
  `codUbi` int(11) NOT NULL COMMENT 'Código de Ubicación',
  `nombreUbi` varchar(30) DEFAULT NULL COMMENT 'Nombre de la Ubicación',
  `descriUbi` varchar(30) DEFAULT NULL COMMENT 'Descripción de la Ubicación',
  `nomenUbi` varchar(3) DEFAULT NULL COMMENT 'Nomenclatura de la Ubicación'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ubicacion`
--

INSERT INTO `ubicacion` (`codUbi`, `nombreUbi`, `descriUbi`, `nomenUbi`) VALUES
(1, 'Zarzal', 'Municipio', '123');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_cargo` int(5) NOT NULL,
  `usuario` varchar(30) NOT NULL,
  `passwd` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_cargo`, `usuario`, `passwd`) VALUES
(1, 'prueba', '12345'),
(2, 'jc', '12345');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignatura`
--
ALTER TABLE `asignatura`
  ADD PRIMARY KEY (`idAsig`),
  ADD KEY `codProg` (`codProg`),
  ADD KEY `codInclu` (`codInclu`);

--
-- Indices de la tabla `criteriodocente`
--
ALTER TABLE `criteriodocente`
  ADD PRIMARY KEY (`idCritDoc`),
  ADD KEY `idDoc` (`idDoc`),
  ADD KEY `codInclu` (`codInclu`);

--
-- Indices de la tabla `departamento`
--
ALTER TABLE `departamento`
  ADD PRIMARY KEY (`idDepto`),
  ADD KEY `idPais` (`idPais`);

--
-- Indices de la tabla `docasighorario`
--
ALTER TABLE `docasighorario`
  ADD PRIMARY KEY (`idDocAsig`),
  ADD KEY `idDoc` (`idDoc`),
  ADD KEY `idAsig` (`idAsig`),
  ADD KEY `idEsp` (`idEsp`);

--
-- Indices de la tabla `docente`
--
ALTER TABLE `docente`
  ADD PRIMARY KEY (`idDoc`),
  ADD KEY `idTipoDoc` (`idTipoDoc`),
  ADD KEY `idMuni` (`idMuni`);

--
-- Indices de la tabla `espacio`
--
ALTER TABLE `espacio`
  ADD PRIMARY KEY (`idEsp`),
  ADD KEY `idTipoEsp` (`idTipoEsp`),
  ADD KEY `codUbi` (`codUbi`);

--
-- Indices de la tabla `inclusionsocial`
--
ALTER TABLE `inclusionsocial`
  ADD PRIMARY KEY (`codInclu`);

--
-- Indices de la tabla `municipio`
--
ALTER TABLE `municipio`
  ADD PRIMARY KEY (`idMuni`),
  ADD KEY `idDepto` (`idDepto`);

--
-- Indices de la tabla `pais`
--
ALTER TABLE `pais`
  ADD PRIMARY KEY (`idPais`);

--
-- Indices de la tabla `programa`
--
ALTER TABLE `programa`
  ADD PRIMARY KEY (`codProg`);

--
-- Indices de la tabla `tipodocente`
--
ALTER TABLE `tipodocente`
  ADD PRIMARY KEY (`idTipoDoc`);

--
-- Indices de la tabla `tipoespacio`
--
ALTER TABLE `tipoespacio`
  ADD PRIMARY KEY (`idTipoEsp`);

--
-- Indices de la tabla `ubicacion`
--
ALTER TABLE `ubicacion`
  ADD PRIMARY KEY (`codUbi`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD UNIQUE KEY `1` (`id_cargo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_cargo` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignatura`
--
ALTER TABLE `asignatura`
  ADD CONSTRAINT `asignatura_ibfk_1` FOREIGN KEY (`codProg`) REFERENCES `programa` (`codProg`),
  ADD CONSTRAINT `asignatura_ibfk_2` FOREIGN KEY (`codInclu`) REFERENCES `inclusionsocial` (`codInclu`);

--
-- Filtros para la tabla `criteriodocente`
--
ALTER TABLE `criteriodocente`
  ADD CONSTRAINT `criteriodocente_ibfk_1` FOREIGN KEY (`idDoc`) REFERENCES `docente` (`idDoc`),
  ADD CONSTRAINT `criteriodocente_ibfk_2` FOREIGN KEY (`codInclu`) REFERENCES `inclusionsocial` (`codInclu`);

--
-- Filtros para la tabla `departamento`
--
ALTER TABLE `departamento`
  ADD CONSTRAINT `departamento_ibfk_1` FOREIGN KEY (`idPais`) REFERENCES `pais` (`idPais`);

--
-- Filtros para la tabla `docasighorario`
--
ALTER TABLE `docasighorario`
  ADD CONSTRAINT `docasighorario_ibfk_1` FOREIGN KEY (`idDoc`) REFERENCES `docente` (`idDoc`),
  ADD CONSTRAINT `docasighorario_ibfk_2` FOREIGN KEY (`idAsig`) REFERENCES `asignatura` (`idAsig`),
  ADD CONSTRAINT `docasighorario_ibfk_3` FOREIGN KEY (`idEsp`) REFERENCES `espacio` (`idEsp`);

--
-- Filtros para la tabla `docente`
--
ALTER TABLE `docente`
  ADD CONSTRAINT `docente_ibfk_1` FOREIGN KEY (`idTipoDoc`) REFERENCES `tipodocente` (`idTipoDoc`),
  ADD CONSTRAINT `docente_ibfk_2` FOREIGN KEY (`idMuni`) REFERENCES `municipio` (`idMuni`);

--
-- Filtros para la tabla `espacio`
--
ALTER TABLE `espacio`
  ADD CONSTRAINT `espacio_ibfk_1` FOREIGN KEY (`idTipoEsp`) REFERENCES `tipoespacio` (`idTipoEsp`),
  ADD CONSTRAINT `espacio_ibfk_2` FOREIGN KEY (`codUbi`) REFERENCES `ubicacion` (`codUbi`);

--
-- Filtros para la tabla `municipio`
--
ALTER TABLE `municipio`
  ADD CONSTRAINT `municipio_ibfk_1` FOREIGN KEY (`idDepto`) REFERENCES `departamento` (`idDepto`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

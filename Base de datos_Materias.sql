-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3308
-- Tiempo de generación: 25-11-2026 a las 17:13:25
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `uaq_registro_materias`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

CREATE TABLE `materias` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre_facultad` varchar(100) NOT NULL,
  `campus` varchar(50) NOT NULL,
  `carrera` tinyint(3) UNSIGNED NOT NULL,
  `plan_estudios` char(5) NOT NULL,
  `periodo` char(6) NOT NULL,
  `nivel_educativo` varchar(20) NOT NULL,
  `clave_materia` varchar(20) NOT NULL,
  `nombre_materia` varchar(150) NOT NULL,
  `etapa` tinyint(3) UNSIGNED NOT NULL,
  `pre_requisitos` varchar(255) DEFAULT NULL,
  `creditos` tinyint(3) UNSIGNED NOT NULL,
  `horas_teoria` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `horas_presenciales` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `horas_independiente` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `horas_linea` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `horas_virtuales` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `horas_campo` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `horas_laboratorio` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `modalidad_plan` varchar(20) NOT NULL,
  `opcion_educativa` varchar(20) NOT NULL,
  `objetivo_general` text NOT NULL,
  `objetivos_especificos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`objetivos_especificos`)),
  `introduccion` text DEFAULT NULL,
  `justificacion` text NOT NULL,
  `competencias` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`competencias`)),
  `unidades` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`unidades`)),
  `estrategias` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`estrategias`)),
  `eval_diagnostica` text NOT NULL,
  `eval_formativa` text NOT NULL,
  `eval_sumativa` text NOT NULL,
  `eval_independientes` text NOT NULL,
  `instrumentos_eval` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`instrumentos_eval`)),
  `ponderacion` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`ponderacion`)),
  `perfil_docente` text NOT NULL,
  `bibliografia` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`bibliografia`)),
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `materias`
--

INSERT INTO `materias` (`id`, `nombre_facultad`, `campus`, `carrera`, `plan_estudios`, `periodo`, `nivel_educativo`, `clave_materia`, `nombre_materia`, `etapa`, `pre_requisitos`, `creditos`, `horas_teoria`, `horas_presenciales`, `horas_independiente`, `horas_linea`, `horas_virtuales`, `horas_campo`, `horas_laboratorio`, `modalidad_plan`, `opcion_educativa`, `objetivo_general`, `objetivos_especificos`, `introduccion`, `justificacion`, `competencias`, `unidades`, `estrategias`, `eval_diagnostica`, `eval_formativa`, `eval_sumativa`, `eval_independientes`, `instrumentos_eval`, `ponderacion`, `perfil_docente`, `bibliografia`, `fecha_registro`) VALUES
(1, 'Facultad de Informática', 'Juriquilla', 3, 'SOF18', '2026-2', 'Licenciatura', '2001', 'ADMINISTRACION', 1, NULL, 5, 2, 2, 0, 0, 0, 0, 0, 'Escolarizada', 'Presencial', 'Nose', '[\"Ejemplo de los especificos\"]', 'EJEMPLO', 'NOSE', '[\"Nose\"]', '[{\"unidad\":1,\"titulo\":\"NOSE\",\"subtemas\":[\"Nose\",\"Nose\"]}]', '[\"Nose\"]', 'Su propósito es establecer un vínculo significativo entre lo que el estudiante sabe, piensa o siente antes de iniciar su proceso enseñanza-aprendizaje sobre el contenido a abordar.', 'Ocurre durante todo el proceso enseñanza-aprendizaje y juega un importante papel regulador en dicho proceso, ya que permite conocer los aprendizajes logrados y retroalimentar a los estudiantes y al docente.', 'Modalidad que implica recuperar todas las actividades que permiten dar cuenta del avance individual de los alumnos, al final de cada tema y al término del curso.', 'Definir los proyectos y experiencias de aprendizaje que realizarán los estudiantes, así como las rúbricas o instrumentos de evaluación que se usarán.', '[\"Prácticas en clase\",\"Análisis de casos prácticos\",\"Tareas\",\"Proyecto final\"]', '[{\"rubro\":\"Prácticas \\/ Tareas \\/ Portafolio de evidencias\",\"porcentaje\":30},{\"rubro\":\"Proyecto final \\/ Rúbrica de evaluación \\/ Lista de cotejo\",\"porcentaje\":30},{\"rubro\":\"Exámenes (orales \\/ escritos)\",\"porcentaje\":40}]', 'NOSE', '[\"Nose\"]', '2026-11-18 23:05:47'),
(2, 'Facultad de Informática', 'Juriquilla', 3, 'SOF18', '2026-2', 'Licenciatura', '2002', 'INTRODUCCION A LAS TECNOLOGIAS DE INFORMACION', 3, NULL, 6, 2, 2, 0, 0, 0, 0, 0, 'Escolarizada', 'Presencial', 'O', '[\"X\"]', 'INTRODUCCION', 'X', '[\"X\"]', '[{\"unidad\":1,\"titulo\":\"ESTRUCTURAS CONDICIONALES\",\"subtemas\":[\"IF\",\"SWITCH\"]}]', '[\"COLABORATIVA\"]', 'Su propósito es establecer un vínculo significativo entre lo que el estudiante sabe, piensa o siente antes de iniciar su proceso enseñanza-aprendizaje sobre el contenido a abordar.', 'Ocurre durante todo el proceso enseñanza-aprendizaje y juega un importante papel regulador en dicho proceso, ya que permite conocer los aprendizajes logrados y retroalimentar a los estudiantes y al docente.', 'Modalidad que implica recuperar todas las actividades que permiten dar cuenta del avance individual de los alumnos, al final de cada tema y al término del curso.', 'Definir los proyectos y experiencias de aprendizaje que realizarán los estudiantes, así como las rúbricas o instrumentos de evaluación que se usarán.', '[\"Prácticas en clase\",\"Análisis de casos prácticos\",\"Tareas\"]', '[{\"rubro\":\"Prácticas \\/ Tareas \\/ Portafolio de evidencias\",\"porcentaje\":30},{\"rubro\":\"Proyecto final \\/ Rúbrica de evaluación \\/ Lista de cotejo\",\"porcentaje\":30},{\"rubro\":\"Exámenes (orales \\/ escritos)\",\"porcentaje\":40}]', 'X', '[\"CONOLLY\"]', '2026-11-19 16:31:59');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `materias`
--
ALTER TABLE `materias`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

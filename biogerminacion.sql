-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-10-2026 a las 20:15:57
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
-- Base de datos: `biogerminacion`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activities`
--

CREATE TABLE `activities` (
  `id` int(11) NOT NULL,
  `title` varchar(180) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `activities`
--

INSERT INTO `activities` (`id`, `title`, `description`, `created_at`) VALUES
(1, 'Factores de la germinación', 'Responde las preguntas sobre las condiciones necesarias para que una semilla germine.', '2026-10-24 23:11:03'),
(2, 'Partes de la semilla', 'Identifica las partes de una semilla y conoce su función durante la germinación.', '2026-10-25 16:38:19'),
(3, 'Etapas de la germinación', 'Reconoce las principales etapas desde la absorción de agua hasta la aparición de la plántula.', '2026-10-25 16:38:19'),
(4, 'Importancia del agua', 'Comprende cómo el agua permite que una semilla inicie su proceso de germinación.', '2026-10-25 16:38:19'),
(5, 'Influencia de la temperatura', 'Aprende cómo la temperatura afecta el proceso de germinación de las semillas.', '2026-10-25 16:38:19'),
(6, 'Función de la luz', 'Diferencia las necesidades de luz de las semillas y de las plantas jóvenes.', '2026-10-25 16:38:19'),
(7, 'Condiciones para germinar', 'Relaciona el agua, el oxígeno, la temperatura y las características de cada especie.', '2026-10-25 16:38:19'),
(8, 'Crecimiento de las plantas', 'Identifica las necesidades de una planta joven después de la germinación.', '2026-10-25 16:38:19'),
(9, 'Problemas durante la germinación', 'Analiza situaciones comunes que pueden afectar la germinación y el desarrollo inicial.', '2026-10-25 16:38:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contents`
--

CREATE TABLE `contents` (
  `id` int(11) NOT NULL,
  `title` varchar(180) NOT NULL,
  `body` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `contents`
--

INSERT INTO `contents` (`id`, `title`, `body`, `created_at`) VALUES
(1, '¿Qué es la germinación?', 'La germinación es el proceso por el cual una semilla comienza a desarrollarse y da origen a una nueva planta. Para que ocurra necesita condiciones adecuadas de agua, oxígeno y temperatura.', '2026-10-24 23:11:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `germinacion_contenidos`
--

CREATE TABLE `germinacion_contenidos` (
  `id` int(11) NOT NULL,
  `seccion` varchar(50) NOT NULL,
  `tipo` varchar(40) NOT NULL DEFAULT 'item',
  `titulo` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `detalle` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `texto_imagen` varchar(255) DEFAULT NULL,
  `orden` int(11) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `dato` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `germinacion_contenidos`
--

INSERT INTO `germinacion_contenidos` (`id`, `seccion`, `tipo`, `titulo`, `descripcion`, `detalle`, `imagen`, `texto_imagen`, `orden`, `activo`, `fecha_creacion`, `fecha_actualizacion`, `dato`) VALUES
(1, 'factores', 'intro', 'Tres factores que hacen posible el crecimiento', 'El agua y la temperatura son fundamentales para iniciar la germinación. La influencia de la luz depende de la especie.', 'Para que una semilla comience a desarrollarse, necesita condiciones ambientales apropiadas. Conocer estos factores permite comprender por qué algunas semillas germinan rápidamente mientras que otras tardan más tiempo.', NULL, NULL, 1, 1, '2026-10-25 17:00:39', '2026-10-25 17:00:39', NULL),
(2, 'factores', 'factor', 'Humedad', 'El agua hidrata la semilla, favorece su hinchamiento y activa los procesos necesarios para comenzar a crecer.', 'Mantén el sustrato húmedo, pero evita el exceso de agua, porque puede reducir el oxígeno disponible.', 'img/contenidos/9bcd595bfce10f81aa9fb0f8e97e006c.jpg', 'Agua humedeciendo semillas durante la germinación', 2, 1, '2026-10-25 17:00:39', '2026-10-25 17:29:33', ''),
(3, 'factores', 'factor', 'Temperatura', 'Cada especie necesita un rango de temperatura favorable para que ocurran los procesos que permiten la germinación.', 'Ubica las semillas en un lugar apropiado y consulta las condiciones recomendadas para la especie que estás observando.', 'img/contenidos/84f00fa069bb286383c737116b17051c.jpg', 'Semillas germinando en un ambiente adecuado', 3, 1, '2026-10-25 17:00:39', '2026-10-25 17:28:22', ''),
(4, 'factores', 'factor', 'Luz', 'La influencia de la luz sobre la germinación depende de la especie. Cuando emerge la planta, la iluminación favorece la fotosíntesis.', 'Cuando aparezca el brote, proporciona iluminación adecuada para favorecer el desarrollo de sus hojas.', 'img/contenidos/16189955f8d0b62d37835f38c73524bc.jpg', 'Plántula joven recibiendo luz natural', 4, 1, '2026-10-25 17:00:39', '2026-10-25 17:26:52', ''),
(5, 'factores', 'recordatorio', 'Recuerda', 'La germinación también requiere oxígeno. El exceso de agua puede dificultar su disponibilidad. Una vez que la planta desarrolla sus hojas, necesita luz para realizar la fotosíntesis y continuar creciendo.', NULL, NULL, NULL, 5, 1, '2026-10-25 17:00:39', '2026-10-25 17:00:39', NULL),
(6, 'germinacion', 'intro', 'De semilla a plántula', 'Conoce qué es la germinación, cómo es una semilla por dentro y las etapas que atraviesa al iniciar su desarrollo.', 'Es el proceso en el que una semilla, que estaba inactiva, despierta y empieza a crecer hasta convertirse en una plántula. Dentro de cada semilla hay un embrión y una reserva de alimento que lo sostiene hasta que puede producir el suyo propio.', 'assets/img/germinacion.jpg', 'Semilla de frijol germinando', 1, 1, '2026-10-25 17:32:53', '2026-10-25 17:32:53', 'Para que ocurra, la semilla necesita encontrar el ambiente adecuado.'),
(7, 'germinacion', 'condicion', 'Agua', 'Activa la semilla y ablanda su cubierta.', NULL, NULL, NULL, 1, 1, '2026-10-25 17:32:53', '2026-10-25 17:32:53', NULL),
(8, 'germinacion', 'condicion', 'Oxígeno', 'Permite obtener energía de las reservas.', NULL, NULL, NULL, 2, 1, '2026-10-25 17:32:53', '2026-10-25 17:32:53', NULL),
(9, 'germinacion', 'condicion', 'Temperatura adecuada', 'Cada especie tiene un rango en el que germina mejor.', NULL, NULL, NULL, 3, 1, '2026-10-25 17:32:53', '2026-10-25 17:32:53', NULL),
(10, 'germinacion', 'parte', 'Cubierta (testa)', 'Capa externa que protege al embrión de golpes, sequía y microorganismos hasta que las condiciones sean favorables.', 'PROTECCIÓN', 'assets/img/partes-semilla.jpg', 'Partes internas de una semilla', 1, 1, '2026-10-25 17:32:53', '2026-10-25 17:32:53', NULL),
(11, 'germinacion', 'parte', 'Alimento almacenado', 'Almidones, grasas y proteínas guardadas en el endospermo o en los cotiledones. Alimentan al embrión hasta que pueda hacer fotosíntesis.', 'RESERVA', 'assets/img/partes-semilla.jpg', 'Reservas alimenticias de la semilla', 2, 1, '2026-10-25 17:32:53', '2026-10-25 17:32:53', NULL),
(12, 'germinacion', 'parte', 'Radícula', 'Parte del embrión que se convierte en la raíz. Es la primera en salir de la semilla.', 'FUTURA RAÍZ', 'assets/img/partes-semilla.jpg', 'Radícula de la semilla', 3, 1, '2026-10-25 17:32:53', '2026-10-25 17:32:53', NULL),
(13, 'germinacion', 'parte', 'Plúmula', 'Pequeño brote del embrión que da origen al tallo y a las primeras hojas.', 'FUTURO TALLO', 'assets/img/partes-semilla.jpg', 'Plúmula de la semilla', 4, 1, '2026-10-25 17:32:53', '2026-10-25 17:32:53', NULL),
(14, 'germinacion', 'parte', 'Cotiledones', 'Hojas embrionarias. En muchas especies almacenan alimento; en otras funcionan como las primeras hojas de la plántula.', 'PRIMERAS HOJAS', 'assets/img/partes-semilla.jpg', 'Cotiledones de la semilla', 5, 1, '2026-10-25 17:32:53', '2026-10-25 17:32:53', NULL),
(15, 'germinacion', 'etapa', 'Hidratación', 'La semilla absorbe agua (imbibición), se hincha y su cubierta se ablanda. El agua activa las enzimas que transforman las reservas en energía.', 'La semilla aumenta de tamaño y su cubierta se afloja o se arruga.', 'img/contenidos/cba4a2501b14714ca9f2c218821da74a.jpg', 'Semilla durante la hidratación', 1, 1, '2026-10-25 17:32:53', '2026-10-25 17:34:06', '01'),
(16, 'germinacion', 'etapa', 'Salida de la raíz', 'La radícula rompe la cubierta y crece hacia abajo. Fija la planta y empieza a absorber agua.', 'Aparece una pequeña punta clara que sale de la semilla.', 'img/contenidos/4a61045914cd60d91f8376268d23e1bb.jpg', 'Salida de la raíz durante la germinación', 2, 1, '2026-10-25 17:32:53', '2026-10-25 17:36:08', '02'),
(17, 'germinacion', 'etapa', 'Desarrollo del brote', 'El tallo se alarga hacia arriba en busca de la luz y pueden aparecer las primeras hojas.', 'Se observa un tallito que se endereza al salir.', 'img/contenidos/26fbf174bdbbbcc4f8ceddf17e8a8973.jpg', 'Desarrollo del brote', 3, 1, '2026-10-25 17:32:53', '2026-10-25 17:37:04', '03'),
(18, 'germinacion', 'etapa', 'Plántula', 'La planta joven despliega sus primeras hojas y comienza a hacer fotosíntesis, dependiendo cada vez menos de las reservas.', 'Aparecen hojas verdes y raíces cada vez más desarrolladas.', 'img/contenidos/3b1b816934592b1fb4e6fdc9fdb331bb.jpg', 'Plántula con hojas y raíces', 4, 1, '2026-10-25 17:32:53', '2026-10-25 17:39:12', '04'),
(19, 'germinacion', 'tipo_germinacion', 'Epigea', 'Los cotiledones salen del suelo cuando el tallo se alarga por debajo de ellos.', 'Ejemplo: frijol.', 'img/contenidos/92fa4695e2ac99bb6e13a6d4fccada4e.png', 'Ejemplo de germinación epigea', 1, 1, '2026-10-25 17:32:53', '2026-10-25 17:35:13', ''),
(20, 'germinacion', 'tipo_germinacion', 'Hipogea', 'Los cotiledones permanecen bajo tierra y solo sale el tallo con las primeras hojas.', 'Ejemplos: arveja y haba.', 'img/contenidos/6d392a35c6182a37887b03f066db00f0.jpg', 'Ejemplo de germinación hipogea', 2, 1, '2026-10-25 17:32:53', '2026-10-25 17:38:00', ''),
(21, 'germinacion', 'material', 'Un frasco de vidrio transparente o una bolsa transparente', NULL, NULL, NULL, NULL, 1, 1, '2026-10-25 17:32:53', '2026-10-25 17:32:53', NULL),
(22, 'germinacion', 'material', 'Algodón o papel absorbente', NULL, NULL, NULL, NULL, 2, 1, '2026-10-25 17:32:53', '2026-10-25 17:32:53', NULL),
(23, 'germinacion', 'material', '3 o 4 semillas de frijol', NULL, NULL, NULL, NULL, 3, 1, '2026-10-25 17:32:53', '2026-10-25 17:32:53', NULL),
(24, 'germinacion', 'material', 'Agua', NULL, NULL, NULL, NULL, 4, 1, '2026-10-25 17:32:53', '2026-10-25 17:32:53', NULL),
(25, 'germinacion', 'material', 'Un cuaderno para anotar o dibujar', NULL, NULL, NULL, NULL, 5, 1, '2026-10-25 17:32:53', '2026-10-25 17:32:53', NULL),
(26, 'germinacion', 'paso', 'Remoja las semillas en agua durante unas horas.', NULL, NULL, NULL, NULL, 1, 1, '2026-10-25 17:32:53', '2026-10-25 17:32:53', NULL),
(27, 'germinacion', 'paso', 'Humedece el algodón, sin empaparlo, y colócalo dentro del frasco.', NULL, NULL, NULL, NULL, 2, 1, '2026-10-25 17:32:53', '2026-10-25 17:32:53', NULL),
(28, 'germinacion', 'paso', 'Pon las semillas entre el algodón y la pared de vidrio para poder observarlas.', NULL, NULL, NULL, NULL, 3, 1, '2026-10-25 17:32:53', '2026-10-25 17:32:53', NULL),
(29, 'germinacion', 'paso', 'Deja el frasco en un lugar templado, con luz indirecta.', NULL, NULL, NULL, NULL, 4, 1, '2026-10-25 17:32:53', '2026-10-25 17:32:53', NULL),
(30, 'germinacion', 'paso', 'Mantén el algodón húmedo y observa cada día. Anota los cambios y dibuja lo que ves.', NULL, NULL, NULL, NULL, 5, 1, '2026-10-25 17:32:53', '2026-10-25 17:32:53', NULL),
(31, 'germinacion', 'glosario', 'Germinación', 'Proceso por el cual el embrión retoma su crecimiento y la semilla da origen a una nueva planta.', NULL, NULL, NULL, 1, 1, '2026-10-25 17:32:53', '2026-10-25 17:32:53', NULL),
(32, 'germinacion', 'glosario', 'Latencia', 'Periodo en el que la semilla está viva pero inactiva, esperando condiciones adecuadas.', NULL, NULL, NULL, 2, 1, '2026-10-25 17:32:53', '2026-10-25 17:32:53', NULL),
(33, 'germinacion', 'glosario', 'Imbibición', 'Absorción inicial de agua que hincha la semilla y da comienzo a la germinación.', NULL, NULL, NULL, 3, 1, '2026-10-25 17:32:53', '2026-10-25 17:32:53', NULL),
(34, 'germinacion', 'glosario', 'Plántula', 'Planta joven en las primeras etapas después de germinar.', NULL, NULL, NULL, 4, 1, '2026-10-25 17:32:53', '2026-10-25 17:32:53', NULL),
(35, 'germinacion', 'glosario', 'Fotosíntesis', 'Proceso con el que la planta usa luz, agua y dióxido de carbono para producir su alimento.', NULL, NULL, NULL, 5, 1, '2026-10-25 17:32:53', '2026-10-25 17:32:53', NULL),
(36, 'germinacion', 'pregunta', '¿Todas las semillas necesitan luz para germinar?', 'No. Muchas germinan en la oscuridad, bajo tierra, y otras necesitan luz; depende de la especie. Cuando el brote sale, la plántula sí necesita luz para crecer.', NULL, NULL, NULL, 1, 1, '2026-10-25 17:32:53', '2026-10-25 17:32:53', NULL),
(37, 'germinacion', 'pregunta', '¿Por qué una semilla no germina?', 'Las causas más comunes son semillas viejas o dañadas, exceso o falta de agua, una temperatura inadecuada o sembrarlas demasiado profundo. Algunas especies tienen latencia y requieren un tratamiento previo.', NULL, NULL, NULL, 2, 1, '2026-10-25 17:32:53', '2026-10-25 17:32:53', NULL),
(38, 'germinacion', 'pregunta', '¿Cuánto tarda en germinar una semilla?', 'Varía mucho: desde pocos días hasta semanas o más, según la especie y las condiciones del ambiente.', NULL, NULL, NULL, 3, 1, '2026-10-25 17:32:53', '2026-10-25 17:32:53', NULL),
(39, 'germinacion', 'pregunta', '¿Se puede germinar sin tierra?', 'Sí. La germinación depende de agua, oxígeno y temperatura. La semilla se alimenta de sus propias reservas; la tierra se necesita después, para aportar nutrientes a la plántula.', NULL, NULL, NULL, 4, 1, '2026-10-25 17:32:53', '2026-10-25 17:32:53', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `asunto` varchar(150) DEFAULT NULL,
  `contenido` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `mensajes`
--

INSERT INTO `mensajes` (`id`, `nombre`, `correo`, `asunto`, `contenido`, `fecha_creacion`) VALUES
(2, 'cindy', 'cindy@example.com', 'Germinación', 'khkkklkljll', '2026-10-25 16:42:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `correct_option` enum('a','b','c') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `questions`
--

INSERT INTO `questions` (`id`, `activity_id`, `question`, `option_a`, `option_b`, `option_c`, `correct_option`) VALUES
(1, 1, '¿Qué necesita una semilla para iniciar la germinación?', 'Agua y condiciones adecuadas', 'Pintura', 'Plástico', 'a'),
(2, 2, '¿Qué estructura protege el interior de la semilla?', 'La cubierta', 'La raíz', 'La flor', 'a'),
(3, 2, '¿Qué parte contiene la planta en desarrollo?', 'El fruto', 'El embrión', 'La hoja adulta', 'b'),
(4, 2, '¿Qué reserva alimenta al embrión en muchas semillas?', 'La corteza', 'El pétalo', 'Los cotiledones', 'c'),
(5, 2, '¿Qué estructura dará origen a la raíz?', 'La radícula', 'El tallo adulto', 'El fruto', 'a'),
(6, 2, '¿Cuál es la función principal de la semilla?', 'Producir flores inmediatamente', 'Permitir el desarrollo de una nueva planta', 'Absorber luz como una hoja adulta', 'b'),
(7, 3, '¿Qué suele ocurrir al comenzar la germinación?', 'La semilla produce flores', 'La semilla absorbe agua', 'La planta produce frutos', 'b'),
(8, 3, '¿Qué estructura suele emerger primero de la semilla?', 'La radícula', 'La flor', 'El fruto', 'a'),
(9, 3, '¿Qué es una plántula?', 'Una semilla seca', 'Una raíz aislada', 'Una planta joven recién desarrollada', 'c'),
(10, 3, '¿Qué sucede después de que emerge la raíz?', 'La semilla deja de desarrollarse', 'Continúa el crecimiento de la planta', 'Se convierte inmediatamente en fruto', 'b'),
(11, 3, '¿Todas las semillas germinan al mismo tiempo?', 'No, depende de la especie y las condiciones', 'Sí, siempre tardan lo mismo', 'Sí, si están enterradas', 'a'),
(12, 4, '¿Por qué es importante el agua para la germinación?', 'Porque reemplaza todos los nutrientes', 'Porque permite la hidratación y activa procesos metabólicos', 'Porque convierte la semilla en flor inmediatamente', 'b'),
(13, 4, '¿Qué puede suceder si una semilla no recibe suficiente agua?', 'Puede no germinar', 'Crece más rápido siempre', 'Produce frutos de inmediato', 'a'),
(14, 4, '¿Qué sucede cuando una semilla absorbe agua?', 'Se convierte en suelo', 'Pierde el embrión', 'Se hidrata y comienza su actividad interna', 'c'),
(15, 4, '¿Qué problema puede causar el exceso de agua en el suelo?', 'Mayor cantidad de oxígeno siempre', 'Falta de oxígeno y posibles daños a la semilla', 'Germinación garantizada', 'b'),
(16, 4, '¿Cómo se debe regar una semilla durante la germinación?', 'Manteniendo una humedad adecuada sin encharcar', 'Inundándola constantemente', 'Evitando toda humedad', 'a'),
(17, 5, '¿Cómo influye la temperatura en la germinación?', 'No tiene ningún efecto', 'Puede favorecer o dificultar el proceso', 'Siempre impide el crecimiento', 'b'),
(18, 5, '¿Qué puede ocurrir si la temperatura es demasiado baja?', 'La germinación puede ralentizarse', 'La semilla siempre florece', 'La semilla produce más agua', 'a'),
(19, 5, '¿Qué efecto puede tener una temperatura excesivamente alta?', 'Garantiza el crecimiento', 'No cambia nada', 'Puede dañar la semilla', 'c'),
(20, 5, '¿Todas las especies necesitan la misma temperatura?', 'Sí, sin excepción', 'No, sus necesidades varían', 'Sí, si reciben agua', 'b'),
(21, 5, '¿Qué conviene hacer antes de germinar una especie desconocida?', 'Consultar sus condiciones de germinación', 'Exponerla siempre al calor intenso', 'Mantenerla completamente seca', 'a'),
(22, 6, '¿Todas las semillas necesitan luz para germinar?', 'Sí, todas necesitan luz directa', 'No, algunas germinan en oscuridad', 'Sí, únicamente luz artificial', 'b'),
(23, 6, '¿Para qué utilizan principalmente la luz las plantas después de emerger?', 'Para realizar la fotosíntesis', 'Para absorber minerales directamente del aire', 'Para reemplazar el agua', 'a'),
(24, 6, '¿Qué puede pasar si una plántula recibe luz insuficiente?', 'Produce frutos de inmediato', 'Deja de necesitar nutrientes', 'Puede crecer débil y alargada', 'c'),
(25, 6, '¿Qué proceso permite a la planta producir azúcares usando luz?', 'La germinación', 'La fotosíntesis', 'La absorción de agua', 'b'),
(26, 6, '¿Qué se debe considerar al ubicar semillas para germinar?', 'Las necesidades de luz propias de cada especie', 'Que todas estén siempre bajo sol intenso', 'Que ninguna reciba agua', 'a'),
(27, 7, '¿Qué elementos suelen ser importantes para la germinación?', 'Pintura y plástico', 'Agua, oxígeno y temperatura adecuada', 'Únicamente luz artificial', 'b'),
(28, 7, '¿Por qué necesita oxígeno una semilla que germina?', 'Para realizar procesos de respiración celular', 'Para fabricar tierra', 'Para reemplazar el embrión', 'a'),
(29, 7, '¿Qué función cumple un sustrato apropiado?', 'Impide la absorción de agua', 'Elimina todas las raíces', 'Puede aportar soporte, humedad y aireación', 'c'),
(30, 7, '¿Qué se debe hacer si varias semillas no germinan?', 'Suponer que todas están muertas', 'Revisar la viabilidad y las condiciones de germinación', 'Añadir siempre más agua sin revisar', 'b'),
(31, 7, '¿Por qué es importante seguir las recomendaciones de cada especie?', 'Porque sus necesidades pueden ser diferentes', 'Porque todas crecen de forma idéntica', 'Porque ninguna necesita oxígeno', 'a'),
(32, 8, '¿Qué necesitan las raíces para desarrollarse adecuadamente?', 'Pintura y plástico', 'Únicamente luz directa', 'Agua, oxígeno y condiciones apropiadas del suelo', 'c'),
(33, 8, '¿Qué función cumple el tallo en muchas plantas?', 'Sostiene hojas y conecta distintas partes de la planta', 'Absorbe toda el agua directamente del aire', 'Protege la semilla antes de sembrarla', 'a'),
(34, 8, '¿Qué función realizan principalmente las hojas verdes?', 'Absorber minerales exclusivamente', 'Realizar la fotosíntesis', 'Producir raíces bajo tierra', 'b'),
(35, 8, '¿Qué puede ocurrir si una planta no recibe suficiente agua?', 'Crece mejor en todos los casos', 'Produce más oxígeno en cualquier condición', 'Puede marchitarse y crecer menos', 'c'),
(36, 8, '¿Por qué es importante observar una planta durante su crecimiento?', 'Para identificar cambios y posibles problemas', 'Para impedir que desarrolle raíces', 'Para evitar cualquier contacto con el agua', 'a'),
(37, 9, '¿Qué puede favorecer la aparición de moho en un semillero?', 'Una identificación correcta de las semillas', 'Humedad excesiva y poca ventilación', 'La observación diaria', 'b'),
(38, 9, '¿Qué conviene revisar si el sustrato está siempre encharcado?', 'El drenaje y la frecuencia de riego', 'El color de las etiquetas únicamente', 'La cantidad de plástico decorativo', 'a'),
(39, 9, '¿Qué puede indicar una semilla que no germina después del tiempo esperado?', 'Que todas las semillas germinan al mismo tiempo', 'Que no necesita condiciones adecuadas', 'Que conviene revisar su viabilidad y las condiciones', 'c'),
(40, 9, '¿Qué práctica ayuda a observar mejor la germinación?', 'Cambiar constantemente todas las condiciones', 'Registrar fechas, riego y cambios observados', 'Evitar mirar las semillas', 'b'),
(41, 9, '¿Qué se recomienda hacer si las plántulas crecen débiles por falta de luz?', 'Evaluar y mejorar la iluminación según la especie', 'Dejar de regarlas por completo', 'Cubrirlas permanentemente', 'a');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resultados_quiz`
--

CREATE TABLE `resultados_quiz` (
  `id` int(10) UNSIGNED NOT NULL,
  `puntos` tinyint(3) UNSIGNED NOT NULL,
  `total` tinyint(3) UNSIGNED NOT NULL DEFAULT 3,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `resultados_quiz`
--

INSERT INTO `resultados_quiz` (`id`, `puntos`, `total`, `fecha_creacion`) VALUES
(1, 1, 3, '2026-10-24 23:01:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `results`
--

INSERT INTO `results` (`id`, `user_id`, `activity_id`, `score`, `total`, `created_at`) VALUES
(1, 1, 1, 1, 1, '2026-10-25 16:29:19'),
(2, 1, 1, 1, 1, '2026-10-25 16:29:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(160) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','usuario') NOT NULL DEFAULT 'usuario',
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `active`, `created_at`) VALUES
(1, 'cindy', 'cindy@example.com', '$2y$10$ZSEvMP8Ha91PYLnO0DODJunqcuWIVIrW3WVgA7auQx0bSv/fL7/xW', 'usuario', 1, '2026-10-24 23:15:44'),
(2, 'Administrador BIOGERMINACIÓN', 'admin.biogerminacion@example.com', '$2y$10$A4/QuPuZgxBNmc9oobJeWeERK/DacWS.gsXoR6yCrsegT8WL0LVTK', 'admin', 1, '2026-10-25 16:45:45');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `germinacion_contenidos`
--
ALTER TABLE `germinacion_contenidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_seccion_orden` (`seccion`,`orden`),
  ADD KEY `idx_activo` (`activo`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_id` (`activity_id`);

--
-- Indices de la tabla `resultados_quiz`
--
ALTER TABLE `resultados_quiz`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `activity_id` (`activity_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `contents`
--
ALTER TABLE `contents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `germinacion_contenidos`
--
ALTER TABLE `germinacion_contenidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de la tabla `resultados_quiz`
--
ALTER TABLE `resultados_quiz`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `results_ibfk_2` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

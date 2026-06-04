-- ============================================================
--  CD PIEMONTE TRASCO — Esquema de Base de Datos
--  MySQL 5.7+ / MariaDB 10.2+
-- ============================================================

-- ============================================================
--  JUGADORES
-- ============================================================
DROP TABLE IF EXISTS `jugadores`;
DROP TABLE IF EXISTS `jugadores`;
CREATE TABLE `jugadores` (
  `id`         INT AUTO_INCREMENT PRIMARY KEY,
  `dorsal`     INT NOT NULL,
  `nombre`     VARCHAR(100) NOT NULL,
  `posicion`   VARCHAR(50)  NOT NULL,
  `pais`       VARCHAR(100) DEFAULT NULL COMMENT 'País asignado y bandera del jugador',
  `capitan`    TINYINT(1)   NOT NULL DEFAULT 0,
  `goles`      INT          NOT NULL DEFAULT 0,
  `activo`     TINYINT(1)   NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uniq_dorsal` (`dorsal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  PARTIDOS (Calendario + resultados)
-- ============================================================
DROP TABLE IF EXISTS `partidos`;
CREATE TABLE `partidos` (
  `id`         INT AUTO_INCREMENT PRIMARY KEY,
  `fecha`      DATE NOT NULL,
  `hora`       TIME DEFAULT NULL,
  `local`      VARCHAR(100) NOT NULL,
  `visitante`  VARCHAR(100) NOT NULL,
  `goles_local`     INT DEFAULT NULL,
  `goles_visitante` INT DEFAULT NULL,
  `condicion`  ENUM('casa','fuera') NOT NULL DEFAULT 'casa',
  `jugado`     TINYINT(1) NOT NULL DEFAULT 0,
  `jornada`    INT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY `idx_fecha` (`fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  CLASIFICACIÓN
-- ============================================================
DROP TABLE IF EXISTS `clasificacion`;
CREATE TABLE `clasificacion` (
  `id`         INT AUTO_INCREMENT PRIMARY KEY,
  `equipo`     VARCHAR(100) NOT NULL,
  `pj`         INT NOT NULL DEFAULT 0,
  `pg`         INT NOT NULL DEFAULT 0,
  `pe`         INT NOT NULL DEFAULT 0,
  `pp`         INT NOT NULL DEFAULT 0,
  `gf`         INT NOT NULL DEFAULT 0,
  `gc`         INT NOT NULL DEFAULT 0,
  `puntos`     INT NOT NULL DEFAULT 0,
  `es_piemonte` TINYINT(1) NOT NULL DEFAULT 0,
  UNIQUE KEY `uniq_equipo` (`equipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  PATROCINADORES
-- ============================================================
DROP TABLE IF EXISTS `patrocinadores`;
CREATE TABLE `patrocinadores` (
  `id`         INT AUTO_INCREMENT PRIMARY KEY,
  `nombre`     VARCHAR(100) NOT NULL,
  `descripcion` VARCHAR(255) DEFAULT NULL,
  `url`        VARCHAR(255) DEFAULT NULL,
  `orden`      INT NOT NULL DEFAULT 0,
  `activo`     TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  MENSAJES DE CONTACTO
-- ============================================================
DROP TABLE IF EXISTS `mensajes`;
CREATE TABLE `mensajes` (
  `id`         INT AUTO_INCREMENT PRIMARY KEY,
  `nombre`     VARCHAR(100) NOT NULL,
  `email`      VARCHAR(150) NOT NULL,
  `mensaje`    TEXT NOT NULL,
  `ip`         VARCHAR(45) DEFAULT NULL,
  `leido`      TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY `idx_leido` (`leido`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  NUEVA TABLA: GOLES_PARTIDOS (Versión ultra simplificada)
-- ============================================================
DROP TABLE IF EXISTS `goles_partidos`;
CREATE TABLE `goles_partidos` (
  `id`         INT AUTO_INCREMENT PRIMARY KEY,
  `partido_id` INT NOT NULL,
  `goleador`   VARCHAR(100) NOT NULL COMMENT 'Nombre de quien hizo los goles',
  `goles`      INT NOT NULL DEFAULT 1 COMMENT 'Cantidad de goles marcados',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_goles_partido_simple` FOREIGN KEY (`partido_id`) REFERENCES `partidos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  DATOS INICIALES (SEED)
-- ============================================================
INSERT INTO `jugadores` (`dorsal`, `nombre`, `posicion`, `pais`, `capitan`) VALUES
(1,  'Dyeicob',  'Portero',           '🇪🇸 España',           0),
(13, 'Navas',    'Portero',           '🇵🇸 Palestina',        0),
(15, 'Gallego',  'Lateral Der.',      '🇪🇸 España',           0),
(14, 'Xoki',     'Central',           '🇪🇸 España',           0),
(4,  'Viso',     'Central',           '🇻🇪 Venezuela',        0),
(36, 'Oso',      'Lateral Izq.',      '🇪🇸 España',           0),
(49, 'Lucho',    'Mediocentro Def.',  '🇪🇸 España',           0),
(6,  'Bandín',   'Mediocentro',       '🇪🇸 España',           0),
(67, 'Brian',    'Mediocentro',       '🇪🇸 España',           0),
(99, 'Raspa',    'Mediocentro',       '🇪🇸 España',           1), -- Capitán
(10, 'Jowi',     'Mediapunta',        '🇺🇸 Estados Unidos',   0),
(1312, 'Moi',     'Mediapunta',        '🇪🇸 España',          0),
(11, 'Viñas',    'Extremo Der.',      '🇲🇦 Marruecos',        0),
(7,  'Inho',     'Extremo Izq.',      '🇰🇵 Corea del Norte',  0),
(9,  'Grasa',    'Delantero',         '🇪🇸 España',           0),
(17, 'Ostos',    'Delantero',         '🇨🇴 Colombia',         0),
(0,  'Traskis',  'Entrenador',        '🇪🇸 España',           0);

INSERT INTO `partidos` (`fecha`, `hora`, `local`, `visitante`, `goles_local`, `goles_visitante`, `condicion`, `jugado`, `jornada`) VALUES 
('2026-03-25', '19:00:00', 'CD Piemonte Trasco', 'SD Informaquinas', 1, 6, 'casa', 1, NULL),
('2026-02-23', '17:00:00', 'CD Piemonte Trasco', 'Sativa Galega FC', 4, 5, 'casa', 1, NULL),
('2026-02-19', '18:00:00', 'CD Piemonte Trasco', 'Pitukos FC', 3, 7, 'casa', 1, NULL),
('2026-02-09', '19:00:00', 'CD Piemonte Trasco', 'SPK Xabarís', 5, 4, 'casa', 1, NULL),
('2025-02-03', '21:00:00', 'Aston Birra', 'CD Piemonte Trasco', 4, 3, 'fuera', 1, NULL),
('2025-12-04', '17:00:00', 'CD Piemonte Trasco', 'Cervezas Tomglezz', 1, 4, 'casa', 1, NULL),
('2025-11-03', '19:00:00', 'Pitukos FC', 'CD Piemonte Trasco', 6, 2, 'fuera', 1, NULL);

INSERT INTO `clasificacion` (`equipo`,`pj`,`pg`,`pe`,`pp`,`gf`,`gc`,`puntos`,`es_piemonte`) VALUES
('Ourense SD B',       3,2,1,0,6,3,7,0),
('CD Piemonte Trasco', 3,2,1,0,6,2,7,1),
('Velle CF',           3,1,1,1,4,5,4,0),
('Allariz CF',         3,1,0,2,3,4,3,0),
('Atlético Barbadás',  3,1,0,2,3,5,3,0),
('Pereiro de Aguiar',  3,0,1,2,2,5,1,0);

INSERT INTO `patrocinadores` (`nombre`,`descripcion`,`orden`) VALUES
('Hamburguesería Queen', 'La hamburguesa oficial del vestuario', 1),
('Júpiter Ourense',      'Energía para los noventa minutos',    2),
('Cristina KDK',         'Apoyo incondicional desde la grada',  3);

